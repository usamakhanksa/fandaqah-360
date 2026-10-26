<?php
/* Fandaqah lead proxy. On a PHP host every form posts here instead of to the CRM, so the API key stays on the server.
   It accepts the JSON the pages send (fetch) or a plain form post (no JavaScript), applies the same spam traps as the pages,
   limits how often one address can send, checks the essentials, and forwards the lead to the CRM unchanged otherwise.
   Answers: 200 {ok} · 400 unreadable · 403 other site · 405 not POST · 413 too large · 422 details to fix · 429 too many · 502 CRM unreachable.
   PHP 7.4 or later, with curl (or allow_url_fopen). */
declare(strict_types=1);

$config = require __DIR__ . '/config.php';
const MAX_BODY = 65536;

$ctype   = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
$isForm  = strpos($ctype, 'application/x-www-form-urlencoded') !== false || strpos($ctype, 'multipart/form-data') !== false;
$wantsHtml = $isForm && strpos(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json') === false;

/* ---------- answers ---------- */
function reply(int $status, array $body, array $headers = []): void
{
    global $wantsHtml;
    http_response_code($status);
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    foreach ($headers as $h) header($h);
    if ($wantsHtml) { page($status >= 200 && $status < 300); exit; }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/* without JavaScript the visitor lands here after sending: a short Arabic-first page, then back to where they were */
function page(bool $ok): void
{
    header('Content-Type: text/html; charset=utf-8');
    $back = $_SERVER['HTTP_REFERER'] ?? '';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (!$back || parse_url($back, PHP_URL_HOST) . (parse_url($back, PHP_URL_PORT) ? ':' . parse_url($back, PHP_URL_PORT) : '') !== $host) $back = '../';
    $b = htmlspecialchars($back, ENT_QUOTES, 'UTF-8');
    [$arH, $arP, $enH, $enP] = $ok
        ? ['شكراً لك. وصل طلبك إلى فريقنا.', 'سيتواصل معك أحد مختصي فندقة قريباً.', 'Thank you. Your request is with our team.', 'A Fandaqah specialist will contact you shortly.']
        : ['تعذّر إرسال طلبك.', 'يرجى المحاولة مرة أخرى، أو الاتصال بنا على 920066456 أو مراسلتنا على info@fandaqah.com.', 'We couldn’t send your request.', 'Please try again, call +966 9200 66456 or email info@fandaqah.com.'];
    echo '<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">'
       . '<title>فندقة 360</title><style>body{margin:0;min-height:100vh;display:grid;place-items:center;padding:32px 16px;background:#F0EDE9;color:#18172F;font:16px/1.7 "Segoe UI",Tahoma,system-ui,sans-serif;text-align:center}'
       . 'main{max-width:520px}h1{margin:0 0 6px;font-size:28px}p{margin:0 0 6px;color:#3E3D54}.en{margin-top:18px;direction:ltr;color:#6B6A80;font-size:14px}'
       . 'a{display:inline-block;margin-top:24px;padding:12px 22px;border-radius:999px;background:#D4413B;color:#fff;font-weight:700;text-decoration:none}</style></head>'
       . "<body><main><h1>$arH</h1><p>$arP</p><p class=\"en\" lang=\"en\"><b>$enH</b> $enP</p><a href=\"$b\">رجوع · Back</a></main></body></html>";
}

/* ---------- only this site, only POST ---------- */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') reply(405, ['error' => 'Send the lead with POST.'], ['Allow: POST']);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && $origin !== 'null') {
    $self = (($_SERVER['HTTPS'] ?? '') && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
    $sameHost = parse_url($origin, PHP_URL_HOST) . (parse_url($origin, PHP_URL_PORT) ? ':' . parse_url($origin, PHP_URL_PORT) : '') === ($_SERVER['HTTP_HOST'] ?? '');
    if (!$sameHost && $origin !== $self && !in_array($origin, $config['allowed_origins'], true)) reply(403, ['error' => 'Requests from other sites are not accepted.']);
}

/* ---------- read the lead ---------- */
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > MAX_BODY) reply(413, ['error' => 'The request is too large.']);
if ($isForm) {
    $in = $_POST;
} else {
    $raw = (string) file_get_contents('php://input', false, null, 0, MAX_BODY + 1);
    if (strlen($raw) > MAX_BODY) reply(413, ['error' => 'The request is too large.']);
    $in = json_decode($raw, true);
}
if (!is_array($in)) reply(400, ['error' => 'The request could not be read.']);

// the hidden field only a script fills in: it sees success and nothing is sent
if (trim((string) ($in['website'] ?? '')) !== '') reply(200, ['ok' => true]);

/* ---------- one address cannot flood the CRM ---------- */
$ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$file = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'fq-lead-' . hash('sha256', $ip . '|' . __DIR__) . '.json';
$now  = time();
$fh   = @fopen($file, 'c+');
if ($fh && flock($fh, LOCK_EX)) {
    $hits = json_decode((string) stream_get_contents($fh), true);
    $hits = array_values(array_filter(is_array($hits) ? $hits : [], function ($t) use ($now, $config) { return is_int($t) && $t > $now - $config['rate_limit']['window']; }));
    if (count($hits) >= $config['rate_limit']['max']) {
        flock($fh, LOCK_UN); fclose($fh);
        reply(429, ['error' => 'Too many requests. Please try again in a few minutes.'], ['Retry-After: ' . $config['rate_limit']['window']]);
    }
    $hits[] = $now;
    ftruncate($fh, 0); rewind($fh); fwrite($fh, json_encode($hits));
    flock($fh, LOCK_UN); fclose($fh);
}

/* ---------- tidy every value: trimmed text, bounded sizes, at most two levels deep ---------- */
function tidy($v, int $depth = 0)
{
    if (is_string($v)) { $v = trim(str_replace("\0", '', $v)); return function_exists('mb_substr') ? mb_substr($v, 0, 4000) : substr($v, 0, 12000); }
    if (is_bool($v) || is_int($v) || is_float($v) || $v === null) return $v;
    if (is_array($v) && $depth < 2) { $out = []; foreach (array_slice($v, 0, 40, true) as $k => $x) $out[is_int($k) ? $k : substr((string) $k, 0, 64)] = tidy($x, $depth + 1); return $out; }
    return null;
}
$lead = [];
foreach ($in as $k => $v) if (is_string($k) && $k !== 'website') $lead[substr($k, 0, 64)] = tidy($v);

// the same mobile rules as the pages: Saudi numbers become +966…, others need their + or 00 prefix; Arabic-Indic digits accepted
function mobile(string $raw): ?string
{
    $v = strtr(trim($raw), ['٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
                            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9']);
    $d = preg_replace('/\D/', '', $v);
    if (preg_match('/^(00966|966)([1-9]\d{8})$/', $d, $m)) return '+966' . $m[2];
    if (preg_match('/^0([1-9]\d{8})$/', $d, $m)) return '+966' . $m[1];
    if (preg_match('/^5\d{8}$/', $d)) return '+966' . $d;
    if (substr($v, 0, 1) === '+' && strlen($d) >= 8 && strlen($d) <= 15) return '+' . $d;
    if (substr($d, 0, 2) === '00' && strlen($d) >= 10 && strlen($d) <= 17) return '+' . substr($d, 2);
    return null;
}

$errors = [];
$email = strtolower((string) ($lead['email'] ?? ''));
if ($email !== '') {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    else $lead['email'] = $email;
}
$phoneRaw = (string) ($lead['phone_raw'] ?? $lead['phone'] ?? '');
if ($phoneRaw !== '') {
    $phone = mobile($phoneRaw);
    if ($phone === null) $errors['phone'] = 'Enter a valid mobile number.';
    else { $lead['phone'] = $phone; $lead['phone_raw'] = $phoneRaw; }
}
if (isset($lead['name']) && $lead['name'] !== '' && (function_exists('mb_strlen') ? mb_strlen((string) $lead['name']) : strlen((string) $lead['name'])) < 2) $errors['name'] = 'Enter a name.';
if (($lead['email'] ?? '') === '' && ($lead['phone'] ?? '') === '') $errors['contact'] = 'A lead needs a phone number or an email address.';
if ($errors) reply(422, ['error' => 'Please check your details.', 'fields' => $errors]);

// a plain form post has no summary line: give the team one, with the visitor's own words first
if ($isForm) {
    $note = (string) ($lead['message'] ?? '');
    $lead['customer_message'] = $note !== '' ? $note : null;
    $rows = [];
    foreach (['company' => 'Company', 'phone' => 'Mobile', 'email' => 'Email', 'city' => 'City', 'role' => 'Role', 'term' => 'Offer term', 'interest' => 'Interest'] as $k => $label)
        if (($lead[$k] ?? '') !== '' && is_string($lead[$k])) $rows[] = "$label: {$lead[$k]}";
    $lead['message'] = ($note !== '' ? "$note\n———\n" : '') . "[Fandaqah 360 · form sent without JavaScript]\n" . implode("\n", $rows);
    $lead['source'] = $lead['source'] ?? 'fandaqah_php_form';
    $lead['page_url'] = $lead['page_url'] ?? ($_SERVER['HTTP_REFERER'] ?? null);
}
$lead['platform'] = $config['platform'];
$lead['received_via'] = 'php_proxy';
$lead = array_filter($lead, function ($v) { return $v !== null && $v !== ''; });

/* ---------- forward to the CRM ---------- */
$body = json_encode($lead, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$headers = ['Content-Type: application/json', 'Accept: application/json', 'X-API-Key: ' . $config['api_key']];
$status = 0; $answer = '';
if (function_exists('curl_init')) {
    $ch = curl_init($config['crm_url']);
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $body, CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_TIMEOUT => $config['timeout'], CURLOPT_FOLLOWLOCATION => false]);
    $answer = (string) curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    if ($status === 0) error_log('fq lead proxy: CRM unreachable: ' . curl_error($ch));
    curl_close($ch);
} else {
    $ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => implode("\r\n", $headers), 'content' => $body, 'timeout' => $config['timeout'], 'ignore_errors' => true]]);
    $answer = (string) @file_get_contents($config['crm_url'], false, $ctx);
    if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) $status = (int) $m[1];
}

$crm = json_decode($answer, true);
if ($status >= 200 && $status < 300) reply(200, ['ok' => true, 'crm' => is_array($crm) ? $crm : null]);
if ($status === 429) reply(429, ['error' => 'Too many requests. Please try again in a few minutes.']);
if ($status >= 400 && $status < 500) {
    error_log("fq lead proxy: CRM refused the lead ($status): " . substr($answer, 0, 300));
    reply(422, ['error' => is_array($crm) && isset($crm['error']) ? (string) $crm['error'] : 'Please check your details.']);
}
error_log("fq lead proxy: CRM answered $status");
reply(502, ['error' => 'The CRM could not be reached. Please try again.']);
