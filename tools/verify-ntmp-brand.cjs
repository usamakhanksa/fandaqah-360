const {chromium}=require(process.env.FQ_PLAYWRIGHT||'playwright');
const fs=require('fs');const assert=require('assert');
const base=process.env.FQ_QA_URL||'http://127.0.0.1:8876/';
const out=process.env.FQ_QA_DIR||'artifacts/ntmp-brand';fs.mkdirSync(out,{recursive:true});
async function fill(page){
 for(const[name,value]of Object.entries({fullName:'QA Integration Reviewer',property:'QA NTMP Hotel',whatsapp:'500000000',email:'ntmp.qa@example.invalid'}))await page.locator('[name='+name+']').fill(value);
 for(const name of ['hotelType','jobRole','roomCount','city'])await page.locator('[name='+name+']').selectOption({index:1});
 await page.locator('[data-fnext]').click();await page.locator('[name=notes]').fill('QA fixture; no real CRM delivery.');await page.locator('[name=focus][value=booking]').check();await page.locator('[name=consent]').check();
}
async function leaks(page,lang,root='main'){
 return page.evaluate(({lang,root})=>{
 const w=document.createTreeWalker(document.querySelector(root),NodeFilter.SHOW_TEXT),bad=[];let n;
 while(n=w.nextNode()){let e=n.parentElement,t=n.nodeValue.trim();if(!t||!e.getClientRects().length||getComputedStyle(e).visibility==='hidden'||e.closest('script,style,svg,[data-lang],option'))continue;
 if(lang==='en'&&/[\u0600-\u06ff]/.test(t))bad.push(t);
 if(lang==='ar'&&/[A-Za-z]{3,}/.test(t.replace(/(?:RSV|JV|FQ)-[\w-]+/g,'')))bad.push(t);
 }return [...new Set(bad)];},{lang,root});
}
(async()=>{
 const browser=await chromium.launch({channel:'msedge',headless:true});const results=[];
 for(const lang of ['en','ar'])for(const width of [320,390,768,1024,1180,1440,1920]){
  const page=await browser.newPage({viewport:{width,height:1000},reducedMotion:'reduce'});const errors=[];page.on('pageerror',e=>errors.push(e.message));let attempts=[];let responseMode='fail';
  await page.route('https://crm.fandaqah.com/api/ingest/lead',async route=>{
   if(route.request().method()!=='POST'){await route.continue();return;}
   const d=route.request().postDataJSON();attempts.push(d);assert(route.request().headers()['x-api-key']);
   assert.equal(d.platform,'fandaqah_tourism_platform_contact');assert(d.name&&d.email&&d.company&&d.phone&&d.message&&d.submission_id&&d.contact_consent);assert.equal(d.language,lang);assert(d.message.toLowerCase().includes('booking')||d.message.includes('الحجوزات'));
   if(responseMode==='timeout'){await new Promise(r=>setTimeout(r,200));await route.abort().catch(()=>{});return;}
   await route.fulfill({status:responseMode==='fail'?503:200,contentType:responseMode==='html'?'text/html':'application/json',body:responseMode==='html'?'<html>Error</html>':JSON.stringify(responseMode==='success'?{ok:true}:{ok:false})});
  });
  await page.goto(base+'?lang='+lang,{waitUntil:'networkidle'});await page.evaluate(()=>document.fonts.ready);
  for(let y=0;y<await page.evaluate(()=>document.documentElement.scrollHeight);y+=800){await page.evaluate(y=>scrollTo({top:y,behavior:'instant'}),y);await page.waitForTimeout(20);}
  await page.waitForFunction(()=>[...document.images].every(i=>i.complete&&i.naturalWidth));assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),lang+' overflow '+width);assert.equal(await page.locator('.has-menu,.mega').count(),0);assert.equal(await page.locator('.main-nav .nav-link[href^="#"]').count(),5);assert.deepEqual(await leaks(page,lang),[]);
  for(const a of await page.locator('.main-nav a').all())assert(await page.locator(await a.getAttribute('href')).count());
  await page.evaluate(()=>scrollTo({top:0,behavior:'instant'}));
  if(width===390||width===768||width===1440)await page.screenshot({path:out+'/'+lang+'-'+width+'.png',fullPage:true});
  if(width===390){
   await page.locator('.menu-btn').click();assert(await page.locator('#drawer').isVisible());await page.keyboard.press('Escape');
   await page.locator('.qa__btn').first().click();assert.deepEqual(await leaks(page,lang),[]);
   await page.locator('[data-fnext]').click();assert.equal(await page.locator('[name=fullName]').getAttribute('aria-invalid'),'true');await fill(page);
   await page.locator('button[type=submit]').evaluate(button=>{button.click();button.click();});await page.waitForFunction(()=>document.querySelector('#leadForm').dataset.sending==='0');assert(!await page.locator('.form-done').isVisible());assert.equal(await page.locator('[name=fullName]').inputValue(),'QA Integration Reviewer');
   responseMode='html';await page.locator('button[type=submit]').click();await page.waitForFunction(()=>document.querySelector('#leadForm').dataset.sending==='0');assert(!await page.locator('.form-done').isVisible());
   responseMode='negative';await page.locator('button[type=submit]').click();await page.waitForFunction(()=>document.querySelector('#leadForm').dataset.sending==='0');assert(!await page.locator('.form-done').isVisible());
   responseMode='timeout';await page.evaluate(()=>FQ_CONFIG.form.timeoutMs=50);await page.locator('button[type=submit]').click();await page.waitForFunction(()=>document.querySelector('#leadForm').dataset.sending==='0');assert(!await page.locator('.form-done').isVisible());await page.evaluate(()=>FQ_CONFIG.form.timeoutMs=15000);
   responseMode='success';await page.locator('button[type=submit]').click();await page.locator('.form-done').waitFor({state:'visible'});assert.equal(attempts.length,5);assert.equal(new Set(attempts.map(d=>d.submission_id)).size,1);assert.deepEqual(await leaks(page,lang),[]);
   await page.evaluate(()=>{document.querySelector('#leadForm').scrollIntoView();});
  }
  if(width===1440){
   await page.locator('[data-action="demo"]').first().click();
   for(let i=0;i<5;i++){await page.locator('[data-chip="'+i+'"]').click();await page.waitForTimeout(i===1?5000:500);assert.deepEqual(await leaks(page,lang,'#demoStage'),[],lang+' demo scene '+i);}
   await page.keyboard.press('Escape');
   const other=lang==='en'?'ar':'en';await page.locator('.header-actions [data-lang="'+other+'"]').click();assert(new URL(page.url()).searchParams.get('lang')===other);assert.deepEqual(await leaks(page,other),[]);await page.locator('.header-actions [data-lang="'+lang+'"]').click();assert.deepEqual(await leaks(page,lang),[]);
  }
  assert.deepEqual(errors,[]);results.push({lang,width,passed:true,delivery:attempts.length?'mocked CRM; failure/HTML/retry/success checked':'no submission'});await page.close();
 }
 fs.writeFileSync(out+'/results.json',JSON.stringify(results,null,2));console.log('PASS: 14 viewport/language variants; section links, single-language page/demo, validation, CRM payload, failed/HTML responses and safe retry. CRM responses mocked.');await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
