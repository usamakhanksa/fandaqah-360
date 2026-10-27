// QA harness: loads the real f360-measurement.js + head snippet into fixture
// pages that replicate both live landing pages' structure and success flow.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const ROOT = __dirname;
const head = fs.readFileSync(path.join(ROOT, 'f360-head-snippet.html'), 'utf8');
const lib = fs.readFileSync(path.join(ROOT, 'f360-measurement.js'), 'utf8');

const body = (variant, legacyLine) => `
<header><a class="btn btn--pill" href="#contact">ثبّت سعر الإطلاق</a>
${variant === 'v2' ? '<button type="button" class="btn btn-coral" data-lead="hero">احجز سعر الإطلاق</button>' : ''}</header>
<div style="height:2400px">content</div>
<a href="mailto:info@fandaqah.com">info@fandaqah.com</a>
<a href="tel:+966920000000">call</a>
<a class="btn-whatsapp" href="https://wa.me/966500000000">wa</a>
<section id="contact">
 <form ${variant === 'v1' ? 'id="lead-form" action="../api/lead.php" method="post"' : ''} novalidate>
  ${variant === 'v1' ? '<input name="company">' : ''}
  <input name="name"><input name="phone"><input name="email">
  ${variant === 'v1' ? '<label><input type="radio" name="term" value="2y" checked>2y</label>' : ''}
  <input name="website" style="display:none">
  <button type="submit" class="btn">submit</button>
 </form>
</section>
<div style="height:1200px"></div>
<script>
 document.forms[0].addEventListener('submit', async function(e){
   e.preventDefault();
   const f=e.target; if(!f.email.value || !f.phone.value){ return; } // invalid → no success
   if (f.website.value) return;
   await new Promise(r=>setTimeout(r,150));            // CRM round-trip
   if (window.dataLayer) window.dataLayer.push({ event:'generate_lead', ${variant === 'v1' ? "campaign_code:'X', segment:'existing', interest:'upgrade'" : "lead_plan:'2y'"} });
 });
</script>
${legacyLine ? "<script>gtag('event','conversion',{'send_to':'AW-18383069867/8w92CO-ejqodEKvF3b1E'});</script>" : ''}
`;

const page = (variant, legacy) => `<!doctype html><html lang="ar" dir="rtl"><head>${head}</head><body>${body(variant, legacy)}</body></html>`;

function hits(dl) {
  return dl.filter(e => Array.isArray(e) || (e && e[0] !== undefined)).map(e => Array.from(e));
}

(async () => {
  const browser = await chromium.launch();
  const results = [];
  const check = (name, ok, detail = '') => { results.push({ name, ok, detail }); };

  for (const [variant, url] of [['v1', 'https://fandaqah.com/landing/fandaqah-360/existing-customers/?gclid=TEST_GCLID_123&utm_source=google&utm_medium=cpc'],
                                ['v2', 'https://fandaqah.com/landing/fandaqah-360/fandaqah-360-landing-v2/?gbraid=TEST_GBRAID']]) {
    const ctx = await browser.newContext({ viewport: { width: 390, height: 800 } });
    const p = await ctx.newPage();
    const warnings = [];
    p.on('console', m => { if (m.type() === 'warning') warnings.push(m.text()); });
    await p.route('**/*', r => {
      const u = r.request().url();
      if (u.includes('googletagmanager.com/gtag/js')) return r.fulfill({ contentType: 'text/javascript', body: '/* gtag stub */' });
      if (u.endsWith('/f360-measurement.js')) return r.fulfill({ contentType: 'text/javascript', body: lib });
      if (u.startsWith('https://fandaqah.com/landing/')) return r.fulfill({ contentType: 'text/html', body: page(variant, true) });
      return r.fulfill({ status: 204, body: '' });
    });
    await p.goto(url);
    await p.waitForTimeout(600);
    const T = (n) => `[${variant}] ${n}`;
    const getDL = () => p.evaluate(() => window.dataLayer.map(e => (e && typeof e === 'object' && 'length' in e) ? { __args: Array.from(e) } : e));
    const conv = async (label) => (await getDL()).filter(e => e.__args && e.__args[0] === 'event' && e.__args[1] === 'conversion' && String(e.__args[2].send_to).endsWith(label));
    const LEAD = '8w92CO-ejqodEKvF3b1E';

    check(T('No lead conversion on page load'), (await conv(LEAD)).length === 0);
    check(T('Legacy page-load conversion line blocked by guard'), warnings.some(w => w.includes('Blocked lead conversion')));
    check(T('Consent default commands precede config'), await p.evaluate(() => {
      const a = window.dataLayer.map(e => e && e[0] + ':' + (typeof e[1] === 'string' ? e[1] : ''));
      return a.indexOf('consent:default') > -1 && a.indexOf('consent:default') < a.indexOf('config:AW-18383069867');
    }));
    check(T('F360_PageView fired once'), (await conv('iXCiCOizyKsdEKvF3b1E')).length === 1);
    const ct = await p.evaluate(() => window.F360.campaignType);
    check(T('campaign_type'), ct === (variant === 'v1' ? 'existing_customer' : 'new_customer'), ct);
    const ids = await p.evaluate(() => window.F360.clickIds());
    check(T('Click ID captured for CRM'), variant === 'v1' ? ids.gclid === 'TEST_GCLID_123' : ids.gbraid === 'TEST_GBRAID', JSON.stringify(ids));

    // CTA
    await p.click(variant === 'v1' ? 'header a[href="#contact"]' : 'header [data-lead]');
    await p.click('header a[href="#contact"]');
    check(T('F360_CTA_Click fired once'), (await conv('IkmCCMSiyqsdEKvF3b1E')).length === 1);
    // contact links
    await p.evaluate(() => document.querySelectorAll('a[href^="mailto"],a[href^="tel"],a[href*="wa.me"]').forEach(a => a.addEventListener('click', e => e.preventDefault())));
    await p.click('a[href^="mailto"]'); await p.click('a[href^="tel"]'); await p.click('a[href*="wa.me"]'); await p.click('a[href*="wa.me"]');
    check(T('Email/Phone/WhatsApp clicks fired once each'),
      (await conv('H2BYCMGiyqsdEKvF3b1E')).length === 1 && (await conv('OP7mCPGzyKsdEKvF3b1E')).length === 1 && (await conv('AjcGCO6zyKsdEKvF3b1E')).length === 1);
    // engaged (scroll 50 %)
    await p.evaluate(() => window.scrollTo(0, document.documentElement.scrollHeight * 0.6));
    await p.waitForTimeout(300);
    check(T('F360_EngagedVisit fired on 50% scroll'), (await conv('LycCCOuzyKsdEKvF3b1E')).length === 1);
    // form open / start
    await p.locator('#contact').scrollIntoViewIfNeeded(); await p.waitForTimeout(300);
    await p.fill('form [name="name"]', 'محمد العتيبي');
    check(T('F360_LeadForm_Open + _Start fired once'), (await conv('F9RyCMeiyqsdEKvF3b1E')).length === 1 && (await conv('2J90CMqiyqsdEKvF3b1E')).length === 1);
    // invalid submit
    await p.click('form button[type=submit]'); await p.waitForTimeout(700);
    check(T('No lead conversion on invalid submit'), (await conv(LEAD)).length === 0);
    check(T('Submit button not counted as CTA'), (await conv('IkmCCMSiyqsdEKvF3b1E')).length === 1);
    // valid submit
    await p.fill('form [name="phone"]', '٠٥٥١٢٣٤٥٦٧');          // Arabic-Indic digits, local format
    await p.fill('form [name="email"]', '  Owner.Hotel@Gmail.com ');
    await p.click('form button[type=submit]'); await p.waitForTimeout(900);
    // duplicate success push
    await p.evaluate(() => window.dataLayer.push({ event: 'generate_lead' })); await p.waitForTimeout(600);
    const leads = await conv(LEAD);
    check(T('F360_Lead_Success fired exactly once on confirmed success'), leads.length === 1, JSON.stringify(leads.map(l => l.__args[2])));
    check(T('transaction_id present for dedupe'), leads[0] && /^[0-9a-f-]{36}$/.test(leads[0].__args[2].transaction_id || ''));
    const dl = await getDL();
    const ud = dl.find(e => e.__args && e.__args[0] === 'set' && e.__args[1] === 'user_data');
    const udIdx = dl.indexOf(ud), leadIdx = dl.findIndex(e => e.__args && e.__args[1] === 'conversion' && String(e.__args[2].send_to).endsWith(LEAD));
    check(T('user_data set BEFORE conversion'), ud && udIdx < leadIdx);
    const expectEmail = require('crypto').createHash('sha256').update('ownerhotel@gmail.com').digest('hex');
    const expectPhone = require('crypto').createHash('sha256').update('+966551234567').digest('hex');
    check(T('Email normalised+hashed correctly'), ud && ud.__args[2].sha256_email_address === expectEmail);
    check(T('Phone → E.164 +966551234567 hashed correctly'), ud && ud.__args[2].sha256_phone_number === expectPhone, 'got '+(ud&&ud.__args[2].sha256_phone_number)+' norm='+(await p.evaluate(()=>F360._norm.phone('٠٥٥١٢٣٤٥٦٧'))));
    check(T('Name hashed + country SA'), ud && ud.__args[2].address && ud.__args[2].address.country === 'SA' && /^[0-9a-f]{64}$/.test(ud.__args[2].address.sha256_first_name));
    const raw = JSON.stringify(dl);
    check(T('No raw PII anywhere in dataLayer'), !/gmail|0551|551234|محمد|العتيبي/i.test(raw));
    check(T('f360_lead_success dataLayer event carries campaign_type'), dl.some(e => e.event === 'f360_lead_success' && e.campaign_type === ct));
    check(T('GA4 generate_lead sent to G-70NH8KGTDQ'), dl.some(e => e.__args && e.__args[1] === 'generate_lead' && e.__args[2].send_to === 'G-70NH8KGTDQ'));
    check(T('No PII in URL'), !/[?&](email|phone|name)=/.test(p.url()));
    await ctx.close();
  }

  // honeypot / bot path: success screen but no conversion
  {
    const ctx = await browser.newContext(); const p = await ctx.newPage();
    await p.route('**/*', r => {
      const u = r.request().url();
      if (u.includes('gtag/js')) return r.fulfill({ contentType: 'text/javascript', body: '' });
      if (u.endsWith('/f360-measurement.js')) return r.fulfill({ contentType: 'text/javascript', body: lib });
      return r.fulfill({ contentType: 'text/html', body: page('v1', false) });
    });
    await p.goto('https://fandaqah.com/landing/fandaqah-360/existing-customers/');
    await p.waitForTimeout(300);
    await p.evaluate(() => { const f = document.forms[0]; f.website.value = 'bot'; f.email.value = 'a@b.co'; f.phone.value = '0551234567'; });
    await p.click('form button[type=submit]'); await p.waitForTimeout(700);
    const n = await p.evaluate(() => window.dataLayer.filter(e => e && e[1] === 'conversion' && String(e[2].send_to).includes('8w92')).length);
    check('[bot] Honeypot submit → no lead conversion', n === 0);
    // normaliser unit tests
    const t = await p.evaluate(() => [
      F360._norm.phone('0551234567'), F360._norm.phone('551234567'), F360._norm.phone('+966 55 123 4567'),
      F360._norm.phone('00966551234567'), F360._norm.phone('966551234567'), F360._norm.phone('+9660551234567'),
      F360._norm.phone('12345'), F360._norm.email('Bad'), F360._norm.email(' A.B@Fandaqah.com ')]);
    check('[unit] Phone/email normalisation', JSON.stringify(t) === JSON.stringify(['+966551234567','+966551234567','+966551234567','+966551234567','+966551234567','+966551234567','','','a.b@fandaqah.com']), JSON.stringify(t));
    await ctx.close();
  }
  await browser.close();
  const pass = results.filter(r => r.ok).length;
  results.forEach(r => console.log((r.ok ? 'PASS' : 'FAIL') + '  ' + r.name + (r.ok ? '' : '  ' + r.detail)));
  console.log(`\n${pass}/${results.length} passed`);
  fs.writeFileSync(path.join(__dirname, 'qa-results.json'), JSON.stringify(results, null, 1));
  process.exit(pass === results.length ? 0 : 1);
})();