<?php
/** Qualified NTMP reviews: bounded validation, consent, file-lock rate limits and CRM idempotency. */
declare(strict_types=1);
ini_set('display_errors', '0');
require_once __DIR__ . '/fq-visitor-lib.php';
$config = require __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
function ntmp_reply(int $status, array $body): void {
    http_response_code($status); echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); exit;
}
if(!function_exists('mb_strlen'))ntmp_reply(503,['error'=>'not_configured']);
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$host = $_SERVER['HTTP_HOST'] ?? '';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
if ($origin !== '' && $origin !== $scheme . '://' . $host) {
    if (!in_array($origin, $config['allowed_origins'], true)) ntmp_reply(403, ['error'=>'origin']);
    header('Access-Control-Allow-Origin: ' . $origin); header('Vary: Origin');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS'); header('Access-Control-Allow-Headers: Content-Type, Accept');
    http_response_code(204); exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {header('Allow: POST, OPTIONS');ntmp_reply(405,['error'=>'method']);}
if (($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '') === 'cross-site' && $origin === '') ntmp_reply(403,['error'=>'origin']);
if ($scheme !== 'https' && !in_array(parse_url('http://'.$host, PHP_URL_HOST), ['127.0.0.1','localhost','[::1]'], true)) ntmp_reply(403,['error'=>'https_required']);
if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0)>16384) ntmp_reply(413,['error'=>'too_large']);
$raw=(string)file_get_contents('php://input',false,null,0,16385);
if(strlen($raw)>16384)ntmp_reply(413,['error'=>'too_large']);
$in=json_decode($raw,true);
if(!is_array($in))ntmp_reply(400,['error'=>'invalid_json']);
if(!empty($in['website']))ntmp_reply(422,['error'=>'validation']);
$required=['name','role','property','city','hotel_type','rooms','phone','email','pms','ntmp_status','priority','rollout','properties'];
$data=[];$errors=[];
foreach($required as $key){
    $v=$in[$key]??null;
    if(!is_string($v)||trim($v)===''||mb_strlen($v)>160||preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/',$v)){$errors[$key]='required';continue;}
    $data[$key]=trim($v);
}
$enums=['role'=>['owner','gm','finance','it','other'],'hotel_type'=>['hotel','serviced','other'],'rooms'=>['under20','20-49','50-99','100+'],'ntmp_status'=>['connected','errors','not_connected','not_sure'],'priority'=>['operations','financial','both'],'rollout'=>['0-30','31-90','later','research']];
foreach($enums as $key=>$values)if(isset($data[$key])&&!in_array($data[$key],$values,true))$errors[$key]='invalid';
if(isset($data['email'])){
    $data['email']=strtolower($data['email']);
    if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))$errors['email']='invalid';
}
if(isset($data['phone'])){
    $phone=strtr($data['phone'],array_combine(preg_split('//u','٠١٢٣٤٥٦٧٨٩۰۱۲۳۴۵۶۷۸۹',-1,PREG_SPLIT_NO_EMPTY),str_split('01234567890123456789')));
    $phone=preg_replace('/[\s().-]/','',$phone);
    if(preg_match('/^05\d{8}$/',$phone))$phone='+966'.substr($phone,1);
    elseif(preg_match('/^5\d{8}$/',$phone))$phone='+966'.$phone;
    elseif(strpos($phone,'00966')===0)$phone='+'.substr($phone,2);
    elseif(preg_match('/^9665\d{8}$/',$phone))$phone='+'.$phone;
    if(!preg_match('/^\+[1-9]\d{7,14}$/',$phone))$errors['phone']='invalid';
    $data['phone']=$phone;
}
if(isset($data['properties'])&&(!ctype_digit($data['properties'])||(int)$data['properties']<1||(int)$data['properties']>10000))$errors['properties']='invalid';
foreach(['name','property','city'] as $key)if(isset($data[$key])&&mb_strlen($data[$key])<2)$errors[$key]='invalid';
if(($in['consent']??false)!==true)$errors['consent']='required';
if(!isset($in['submission_id'])||!is_string($in['submission_id'])||!preg_match('/^[a-f0-9-]{20,64}$/i',$in['submission_id']))$errors['submission_id']='invalid';
if($errors)ntmp_reply(422,['error'=>'validation','fields'=>$errors]);
if(empty($config['api_key'])||!function_exists('curl_init'))ntmp_reply(503,['error'=>'not_configured']);
$crmUrl=(string)$config['crm_url'];
if(parse_url($crmUrl,PHP_URL_SCHEME)!=='https' && !(getenv('NTMP_TEST_MODE')==='1'&&in_array(parse_url($crmUrl,PHP_URL_HOST),['127.0.0.1','localhost'],true)))ntmp_reply(503,['error'=>'not_configured']);
// File data is under the existing protected lead store, never in public artifacts or Git.
$dir=(string)(getenv('NTMP_DATA_DIR')?:rtrim($config['leads']['dir'],'/\\').'/ntmp');
if(!is_dir($dir)&&!@mkdir($dir,0700,true))ntmp_reply(503,['error'=>'storage']);
if(!is_file($dir.'/.htaccess'))@file_put_contents($dir.'/.htaccess',"Require all denied\n");
function ntmp_lock(string $file){
    $f=@fopen($file,'c+');if(!$f||!flock($f,LOCK_EX|LOCK_NB)){if($f)fclose($f);ntmp_reply(429,['error'=>'busy']);} @chmod($file,0600);return $f;
}
function ntmp_save($f,array $data): bool {
    $json=json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    rewind($f);if(!ftruncate($f,0))return false;$ok=fwrite($f,$json)===strlen($json);fflush($f);return $ok;
}
$now=time();$secret=$config['api_key'];
$ip=hash_hmac('sha256',fq_client_ip(),$secret);
$rate=ntmp_lock($dir.'/rate-'.$ip.'.json');$hits=json_decode((string)stream_get_contents($rate),true);
$hits=array_values(array_filter(is_array($hits)?$hits:[],static function($t)use($now){return is_int($t)&&$t>$now-600;}));
if(count($hits)>=8){header('Retry-After: 600');ntmp_reply(429,['error'=>'rate_limit']);}
$hits[]=$now;if(!ntmp_save($rate,$hits))ntmp_reply(503,['error'=>'storage']);flock($rate,LOCK_UN);fclose($rate);
// An owner-managed suppression list of HMAC email/phone identifiers; never returned to visitors.
$dncFile=$dir.'/do-not-contact.json';
if(is_file($dncFile)){
    $dnc=json_decode((string)file_get_contents($dncFile),true);
    if(!is_array($dnc))ntmp_reply(503,['error'=>'suppression_unavailable']);
    foreach([$data['email'],$data['phone']] as $contact)if(in_array(hash_hmac('sha256',$contact,$secret),$dnc,true))ntmp_reply(403,['error'=>'contact_restricted']);
}
$fingerprint=hash_hmac('sha256',mb_strtolower($data['property']).'|'.$data['email'].'|'.$data['phone'],$secret);
$record=ntmp_lock($dir.'/review-'.$fingerprint.'.json');$previous=json_decode((string)stream_get_contents($record),true);
if(is_array($previous)&&($previous['state']??'')==='accepted'&&($previous['at']??0)>$now-86400){
    ntmp_reply(200,['ok'=>true,'submission_id'=>$previous['submission_id'],'duplicate'=>true]);
}
// Reuse the same CRM idempotency ID after a network failure or interrupted process.
$id=(is_array($previous)&&($previous['at']??0)>$now-86400)?$previous['submission_id']:$in['submission_id'];
$recordData=['at'=>$now,'submission_id'=>$id,'state'=>'pending','consent'=>['request_contact'=>true,'marketing'=>($in['marketing']??false)===true,'policy_version'=>'ntmp-2026-11','accepted_at'=>gmdate('c'),'language'=>($in['language']??'en')==='ar'?'ar':'en']];
if(!ntmp_save($record,$recordData))ntmp_reply(503,['error'=>'storage']);
$payload=$data;
$payload['platform']=$config['platform'];$payload['source']='ntmp_readiness_review';$payload['lead_stage']='new';
$payload['company']=$data['property'];$payload['interest']='NTMP readiness review';$payload['submission_id']=$id;
$payload['request_contact_consent']=true;$payload['marketing_consent']=$recordData['consent']['marketing'];
$payload['consent']=$recordData['consent'];$payload['language']=$recordData['consent']['language'];
$payload['received_via']='ntmp_php_adapter';
$payload['message']='NTMP readiness review: '.implode(' | ',array_map(static function($key)use($data){return $key.': '.$data[$key];},['role','property','city','hotel_type','rooms','pms','ntmp_status','priority','rollout','properties']));
foreach(['utm_source','utm_medium','utm_campaign','utm_content','utm_term'] as $key)if(isset($in[$key])&&is_string($in[$key]))$payload[$key]=mb_substr(trim($in[$key]),0,200);
// Never retain the URL query: it may contain data outside the UTM allowlist.
if(isset($in['page_url'])&&is_string($in['page_url'])){
    $parts=parse_url($in['page_url']);if(is_array($parts)&&isset($parts['host'])&&in_array($parts['scheme']??'',['https','http'],true))$payload['page_url']=$parts['scheme'].'://'.$parts['host'].($parts['path']??'/');
}
$ch=curl_init($crmUrl);curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>json_encode($payload,JSON_UNESCAPED_UNICODE),CURLOPT_HTTPHEADER=>['Content-Type: application/json','Accept: application/json','X-API-Key: '.$secret,'Idempotency-Key: '.$id],CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>12,CURLOPT_FOLLOWLOCATION=>false]);
$answer=curl_exec($ch);$status=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);$crm=json_decode(is_string($answer)?$answer:'',true);unset($ch);
$ack=is_array($crm)&&(($crm['ok']??false)===true||($crm['success']??false)===true||isset($crm['id'])||isset($crm['lead_id'])||isset($crm['data']['id'])||isset($crm['lead']['id']));
$accepted=$status>=200&&$status<300&&$ack&&(($crm['ok']??$crm['success']??true)!==false);
$recordData['state']=$accepted?'accepted':'failed';$recordData['crm_http']=$status;
if($accepted)$recordData['event']=['name'=>'ntmp_review_form_submit_success','id'=>$id,'analytics_consent'=>($in['analytics_consent']??false)===true];
$saved=ntmp_save($record,$recordData);flock($record,LOCK_UN);fclose($record);
// Retention follows the already configured company lead-store policy; bounded work per request.
$cutoff=$now-max(1,(int)$config['leads']['days'])*86400;$checked=0;
foreach(new DirectoryIterator($dir) as $f){
    if($checked++>100)break;
    if(!$f->isFile()||!preg_match('/^(review|rate)-[a-f0-9]{64}\.json$/',$f->getFilename())||$f->getMTime()>$cutoff)continue;
    $old=@fopen($f->getPathname(),'r+');if($old&&flock($old,LOCK_EX|LOCK_NB)){@unlink($f->getPathname());flock($old,LOCK_UN);}if($old)fclose($old);
}
if(!$accepted)ntmp_reply($status===429?429:502,['error'=>$status===429?'rate_limit':'delivery_failed']);
if(!$saved)error_log('NTMP: CRM accepted lead but local receipt could not be persisted');
ntmp_reply(200,['ok'=>true,'submission_id'=>$id]);
