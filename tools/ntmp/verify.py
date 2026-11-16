from pathlib import Path
from playwright.sync_api import sync_playwright
import json
import os

ROOT=Path(__file__).resolve().parents[2]
OUT=ROOT/'artifacts'/'ntmp';OUT.mkdir(parents=True,exist_ok=True)
results=[]
with sync_playwright() as p:
 cached=list((Path(os.environ['LOCALAPPDATA'])/'ms-playwright').glob('chromium-*/chrome-win*/chrome.exe'))
 browser=p.chromium.launch(executable_path=str(cached[-1]) if cached else None)
 for lang in ['en','ar']:
  for width in [390,768,1440]:
   page=browser.new_page(viewport={'width':width,'height':1000},device_scale_factor=1)
   requests=[]
   def reject_delivery(route):
    requests.append(route.request.post_data_json)
    route.fulfill(status=502,content_type='application/json',body='{"error":"delivery_failed"}')
   page.route('**/api/ntmp-review.php',reject_delivery)
   errors=[];page.on('pageerror',lambda e:errors.append(str(e)))
   route=('ar/' if lang=='ar' else '')+'features/ntmp-tourism-platform.php'
   response=page.goto('http://127.0.0.1:8126/landing/'+route,wait_until='networkidle')
   page.evaluate('document.fonts.ready')
   for y in range(0,page.evaluate('document.documentElement.scrollHeight'),650):
    page.evaluate('(y)=>scrollTo(0,y)',y);page.wait_for_timeout(90)
   page.locator('footer').scroll_into_view_if_needed()
   page.wait_for_function('Array.from(document.images).every(i=>i.complete && i.naturalWidth>0)')
   page.evaluate('scrollTo({top:0,behavior:"instant"})');page.wait_for_timeout(1100)
   page.screenshot(path=str(OUT/f'{lang}-{width}.png'),full_page=True)
   assert response.status==200
   assert page.evaluate('document.documentElement.scrollWidth <= innerWidth'),(lang,width,'overflow')
   assert page.locator('h1').count()==1
   assert page.locator('.faq-item').count()==5
   assert page.locator('img').evaluate_all('(imgs)=>imgs.every(i=>i.complete && i.naturalWidth>0)')
   assert not errors,errors
   page.locator('.proof').first.click()
   assert page.locator('dialog[open]').count()==1
   page.keyboard.press('Escape');assert page.locator('dialog[open]').count()==0
   toggle=page.locator('.faq-toggle').first
   toggle.click();assert toggle.get_attribute('aria-expanded')=='false'
   toggle.click();assert toggle.get_attribute('aria-expanded')=='true'
   if width==390:
    page.locator('.next').click();assert page.locator('#essential').is_visible()
    for name,value in {'name':'QA Reviewer','property':'QA Hotel','city':'Riyadh','phone':'+966500000000','email':'qa@example.invalid'}.items():page.locator(f'[name={name}]').fill(value)
    for name in ['role','hotel_type','rooms']:page.locator(f'[name={name}]').select_option(index=1)
    page.locator('.next').click();assert page.locator('#needs').is_visible()
    page.locator('.back').click();assert page.locator('[name=name]').input_value()=='QA Reviewer'
    page.locator('.next').click()
    page.locator('[name=pms]').fill('QA')
    for name in ['ntmp_status','priority','rollout']:page.locator(f'[name={name}]').select_option(index=1)
    page.locator('[name=properties]').fill('1');page.locator('[name=consent]').check()
    page.locator('[type=submit]').click()
    page.wait_for_function('!document.querySelector("#review-form").hasAttribute("aria-busy")')
    assert page.locator('#form-status').inner_text()
    assert page.locator('[name=name]').input_value()=='QA Reviewer'
    assert requests[-1]['consent'] is True and requests[-1]['marketing'] is False
    assert requests[-1]['role']=='owner'
    page.unroute('**/api/ntmp-review.php',reject_delivery)
    page.route('**/api/ntmp-review.php',lambda route:route.fulfill(status=200,content_type='application/json',body='{"ok":true,"submission_id":"test-confirmed-delivery"}'))
    page.locator('[type=submit]').click();page.locator('.form-complete').wait_for(state='visible')
    assert page.evaluate('window.dataLayer === undefined')
   results.append({'language':lang,'width':width,'status':'passed'})
   page.close()
 # Progressive enhancement and reduced motion preserve readable content.
 for mode in ['reduced','no-js']:
  context=browser.new_context(reduced_motion='reduce',java_script_enabled=mode!='no-js',viewport={'width':390,'height':844})
  page=context.new_page();page.goto('http://127.0.0.1:8126/landing/features/ntmp-tourism-platform.php',wait_until='networkidle')
  assert page.locator('h1').is_visible()
  assert page.locator('.faq-answer').count()==5
  if mode=='reduced':assert page.locator('.reveal-ready').count()==0
  results.append({'mode':mode,'status':'passed'});context.close()
 browser.close()
(OUT/'verification.json').write_text(json.dumps(results,indent=2))
print(json.dumps(results))
