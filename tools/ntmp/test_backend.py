"""Isolated delivery tests. Only a local mock CRM receives synthetic data."""
from pathlib import Path
from http.server import BaseHTTPRequestHandler,ThreadingHTTPServer
import json,os,subprocess,tempfile,threading,time,urllib.request,urllib.error,uuid,hashlib,hmac

ROOT=Path(__file__).resolve().parents[2]
received=[];mode={'status':503}
class CRM(BaseHTTPRequestHandler):
 def do_POST(self):
  data=json.loads(self.rfile.read(int(self.headers['Content-Length'])));received.append(data)
  self.send_response(mode['status']);self.send_header('Content-Type','application/json');self.end_headers()
  self.wfile.write(json.dumps({'ok':mode['status']==200,'id':'test-lead'}).encode())
 def log_message(self,*args):pass
crm=ThreadingHTTPServer(('127.0.0.1',0),CRM);threading.Thread(target=crm.serve_forever,daemon=True).start()
with tempfile.TemporaryDirectory(prefix='ntmp-test-') as tmp:
 env=os.environ.copy();env.update({'FQ_CRM_URL':f'http://127.0.0.1:{crm.server_port}/ingest','FQ_CRM_KEY':'isolated-test-key','NTMP_TEST_MODE':'1','NTMP_DATA_DIR':tmp})
 php=subprocess.Popen(['php','-S','127.0.0.1:8127','-t',str(ROOT)],env=env,stdout=subprocess.DEVNULL,stderr=subprocess.DEVNULL)
 def request(data=None,origin='http://127.0.0.1:8127',method='POST'):
  req=urllib.request.Request('http://127.0.0.1:8127/api/ntmp-review.php',data=json.dumps(data).encode() if data is not None else None,headers={'Content-Type':'application/json','Origin':origin},method=method)
  try:r=urllib.request.urlopen(req,timeout=20)
  except urllib.error.HTTPError as e:r=e
  raw=r.read();return r.status,json.loads(raw) if raw else {}
 try:
  for _ in range(40):
   try:
    if request(method='GET')[0]==405:break
   except OSError:time.sleep(.1)
  valid={'name':'QA Reviewer','role':'owner','property':'QA Property','city':'Riyadh','hotel_type':'hotel','rooms':'20-49','phone':'٠٥٠٠٠٠٠٠٠٠','email':'qa@example.invalid','pms':'QA PMS','ntmp_status':'not_sure','priority':'both','rollout':'research','properties':'1','consent':True,'marketing':False,'submission_id':str(uuid.uuid4()),'language':'ar','utm_source':'qa'}
  assert request(method='OPTIONS',origin='https://usamakhanksa.github.io')[0]==204
  assert request(valid,origin='https://attacker.invalid')[0]==403
  for patch in [{'consent':False},{'role':'injected'},{'email':'invalid'},{'phone':'123'},{'properties':'-1'},{'website':'spam'},{'name':'x'*200}]:
   assert request(dict(valid,**patch))[0]==422,patch
  assert not received
  assert request(valid)[0]==502
  mode['status']=200
  status,result=request(valid);assert status==200 and result['ok']
  assert received[-1]['phone']=='+966500000000'
  assert received[-1]['marketing_consent'] is False and received[-1]['request_contact_consent'] is True
  assert received[0]['submission_id']==received[1]['submission_id']
  assert received[-1]['lead_stage']=='new' and received[-1]['utm_source']=='qa'
  status,result=request(dict(valid,submission_id=str(uuid.uuid4())));assert status==200 and result['duplicate']
  assert len(received)==2
  records=list(Path(tmp).glob('review-*.json'));record=json.loads(records[0].read_text())
  assert record['consent']['request_contact'] is True and record['state']=='accepted'
  assert record['event']['id']==valid['submission_id']
  digest=hmac.new(b'isolated-test-key',b'qa@example.invalid',hashlib.sha256).hexdigest()
  (Path(tmp)/'do-not-contact.json').write_text(json.dumps([digest]))
  assert request(valid)[0]==403
  (Path(tmp)/'do-not-contact.json').write_text('[]')
  for _ in range(4):assert request(valid)[0]==200
  assert request(valid)[0]==429
  assert len(received)==2
  print('PASS: origin, method, validation, explicit consent, honeypot, CRM failure/retry, stable idempotency, Arabic phone normalization, accepted receipt, duplicate suppression, do-not-contact, rate limit. No production calls.')
 finally:php.terminate();php.wait(timeout=10)
crm.shutdown()
