import { createRequire } from 'module'; const { chromium } = createRequire(import.meta.url)('playwright');
import fs from 'fs';

const BASE = 'http://127.0.0.1:8766';
const SNAPLOG = '/home/claude/fq/qa/mock/snap.log', CRMLOG = '/home/claude/fq/qa/mock/crm.log';
const STUB = `(function(){var s=window.snaptr;window.__snap=window.__snap||[];s.handleRequest=function(){window.__snap.push(JSON.parse(JSON.stringify(Array.from(arguments))))};s.queue.forEach(function(q){s.handleRequest.apply(s,q)});s.queue=[];})();`;
const results = [];
const check = (ok, msg) => { results.push([ok, msg]); console.log((ok ? 'PASS ' : 'FAIL ') + msg); };
const lines = f => fs.existsSync(f) ? fs.readFileSync(f, 'utf8').trim().split('\n').filter(Boolean).map(JSON.parse) : [];

async function ctx(browser) {
  const c = await browser.newContext({ viewport: { width: 390, height: 844 }, userAgent: 'Mozilla/5.0 (iPhone) QA' });
  await c.route('**/*', route => {
    const u = route.request().url();
    if (u.includes('sc-static.net/scevent.min.js')) return route.fulfill({ contentType: 'application/javascript', body: STUB });
    if (u.includes('/assets/js/fq-snap.js')) return route.fetch().then(r => r.text()).then(t =>
      route.fulfill({ contentType: 'application/javascript', body: t.replace("const PIXEL_ID = '';", "const PIXEL_ID = 'test-pixel-0000';") }));
    if (u.startsWith(BASE)) return route.continue();
    return route.abort();                                    // fonts, analytics, wa.me: not needed for QA
  });
  return c;
}
const events = page => page.evaluate(() => (window.__snap || []).filter(a => a[0] === 'track').map(a => ({ name: a[1], p: a[2] })));
const names = async page => (await events(page)).map(e => e.name);
const count = (arr, n) => arr.filter(x => x === n).length;

const browser = await chromium.launch();

/* ================= Campaign 1: existing customers ================= */
{
  fs.writeFileSync(SNAPLOG, ''); fs.writeFileSync(CRMLOG, '');
  const c = await ctx(browser), page = await c.newPage();
  await page.goto(BASE + '/existing-customers/index.php?utm_campaign=A_snap_existing_upgrade_oct2026&utm_source=snapchat&utm_medium=paid_social&ScCid=CLICK123&lang=ar');
  await page.waitForTimeout(800);
  let n = await names(page);
  check(count(n, 'PAGE_VIEW') === 1, 'C1: PAGE_VIEW fires once on load');
  check(!n.includes('SIGN_UP'), 'C1: no SIGN_UP on page load');
  const pv = (await events(page))[0];
  check(pv.p.event_tag === 'existing_customer' && pv.p.customer_status === 'returning', 'C1: event_tag=existing_customer, customer_status=returning');

  await page.evaluate(() => window.scrollTo(0, (document.documentElement.scrollHeight - innerHeight) * 0.55));
  await page.waitForTimeout(400);
  await page.evaluate(() => window.scrollTo(0, (document.documentElement.scrollHeight - innerHeight) * 0.6));
  await page.waitForTimeout(400);
  n = await names(page);
  check(count(n, 'VIEW_CONTENT') === 1, 'C1: VIEW_CONTENT fires once at 50% scroll');

  await page.evaluate(() => document.querySelector('a[href*="wa.me"]').click());
  await page.evaluate(() => document.querySelector('a[href*="wa.me"]').click());
  await page.waitForTimeout(300);
  n = await names(page);
  const cta = (await events(page)).filter(e => e.name === 'CUSTOM_EVENT_1');
  check(cta.length === 1 && cta[0].p.description === 'cta_whatsapp', 'C1: CTA_CLICK (CUSTOM_EVENT_1 cta_whatsapp) fires once on WhatsApp');

  await page.locator('#lead-form').scrollIntoViewIfNeeded();
  await page.waitForTimeout(500);
  check(count(await names(page), 'CUSTOM_EVENT_2') === 1, 'C1: LEAD_FORM_OPEN (CUSTOM_EVENT_2) fires when the form is on screen');

  await page.locator('#lead-form button[type=submit]').click();
  await page.waitForTimeout(800);
  n = await names(page);
  check(!n.includes('SIGN_UP') && lines(CRMLOG).length === 0, 'C1: invalid submit → no SIGN_UP, nothing sent');

  await page.fill('#lead-form [name=company]', 'فندق الاختبار');
  await page.waitForTimeout(200);
  check(count(await names(page), 'CUSTOM_EVENT_3') === 1, 'C1: LEAD_FORM_START (CUSTOM_EVENT_3) fires on first real input');
  await page.fill('#lead-form [name=name]', 'محمد العتيبي');
  await page.fill('#lead-form [name=phone]', '0501234567');
  await page.fill('#lead-form [name=email]', 'QA@Hotel.sa');
  const ok = page.locator('#lead-form [name=contact_ok]');
  if (await ok.count()) await ok.check({ force: true });
  await page.waitForTimeout(2600);                           // the form's own anti-bot timer
  await page.locator('#lead-form button[type=submit]').click();
  await page.waitForSelector('#lead-done:not([hidden])', { timeout: 15000 });
  await page.waitForTimeout(1500);
  const su = (await events(page)).filter(e => e.name === 'SIGN_UP');
  const crm = lines(CRMLOG), snap = lines(SNAPLOG);
  check(su.length === 1, 'C1: SIGN_UP fires exactly once on confirmed success');
  const id = su[0] && su[0].p.client_dedup_id;
  check(crm.length === 1 && crm[0].body.submission_id === id, 'C1: CRM lead carries the same submission_id');
  check(!('_snap' in (crm[0] || { body: {} }).body) && crm[0].body.sc_click_id === 'CLICK123', 'C1: Snap data stripped from CRM payload; ScCid kept for offline matching');
  check(snap.length === 1 && snap[0].body.data[0].event_id === id && snap[0].body.data[0].event_name === 'SIGN_UP', 'C1: CAPI SIGN_UP event_id === Pixel client_dedup_id (dedup)');
  check(snap[0] && snap[0].body.data[0].user_data.sc_click_id === 'CLICK123' && snap[0].body.data[0].custom_data.event_tag === 'existing_customer', 'C1: CAPI carries ScCid + existing_customer tag');
  check(crm[0] && crm[0].body.first_touch && crm[0].body.first_touch.ScCid === 'CLICK123', 'C1: site.js attribution now records ScCid');
  const init = await page.evaluate(() => (window.__snap || []).filter(a => a[0] === 'init').map(a => a[2]));
  check(init.some(o => o && /^[0-9a-f]{64}$/.test(o.user_hashed_email || '') && /^[0-9a-f]{64}$/.test(o.user_hashed_phone_number || '')), 'C1: advanced matching sent hashed only (SHA-256)');

  await page.reload(); await page.waitForTimeout(800);
  check(!(await names(page)).includes('SIGN_UP'), 'C1: reload after success → SIGN_UP not repeated');
  await c.close();
}

/* ================= Campaign 2: v2 landing (new customers) ================= */
{
  fs.writeFileSync(SNAPLOG, ''); fs.writeFileSync(CRMLOG, '');
  const c = await ctx(browser), page = await c.newPage();
  await page.goto(BASE + '/fandaqah-360-landing-v2/index.php?utm_campaign=B_snap_hotelowners_oct2026&utm_source=snapchat&ScCid=CLICK456&lang=ar');
  await page.waitForTimeout(800);
  let ev = await events(page);
  check(count(ev.map(e => e.name), 'PAGE_VIEW') === 1, 'C2: PAGE_VIEW fires once on load');
  check(ev[0] && ev[0].p.event_tag === 'new_customer' && ev[0].p.customer_status === 'new', 'C2: event_tag=new_customer, customer_status=new');

  await page.locator('[data-lead="general"]:visible').first().click();
  await page.waitForTimeout(600);
  let n = await names(page);
  check(n.includes('CUSTOM_EVENT_1') && (await events(page)).some(e => e.p.description === 'cta_demo'), 'C2: CTA_CLICK (cta_demo) on "Reserve launch pricing"');
  check(count(n, 'CUSTOM_EVENT_2') === 1, 'C2: LEAD_FORM_OPEN when the dialog opens');

  await page.locator('#lead .lead-submit').click();
  await page.waitForTimeout(600);
  check(!(await names(page)).includes('SIGN_UP') && lines(CRMLOG).length === 0, 'C2: invalid submit → no SIGN_UP');

  await page.fill('#lead [name=name]', 'Sara QA');
  await page.fill('#lead [name=email]', 'sara@hotel.sa');
  await page.fill('#lead [name=phone]', '0551234567');
  check(count(await names(page), 'CUSTOM_EVENT_3') === 1, 'C2: LEAD_FORM_START once');
  await page.locator('#lead .lead-submit').click();
  await page.waitForSelector('#lead [data-lead-step="done"]:not([hidden])', { timeout: 15000 });
  await page.waitForTimeout(1500);
  const su = (await events(page)).filter(e => e.name === 'SIGN_UP');
  const crm = lines(CRMLOG), snap = lines(SNAPLOG);
  const id = su[0] && su[0].p.client_dedup_id;
  check(su.length === 1 && !!id, 'C2: SIGN_UP once on confirmed success (page had no id: one was added)');
  check(crm.length === 1 && crm[0].body.submission_id === id, 'C2: CRM receives that submission_id');
  check(snap.length === 1 && snap[0].body.data[0].event_id === id, 'C2: CAPI event_id === Pixel client_dedup_id (dedup)');
  check(snap[0] && snap[0].body.data[0].custom_data.event_tag === 'new_customer', 'C2: CAPI tagged new_customer');
  await c.close();
}

/* ================= Privacy: Global Privacy Control ================= */
{
  const c = await ctx(browser), page = await c.newPage();
  await page.addInitScript(() => Object.defineProperty(navigator, 'globalPrivacyControl', { get: () => true }));
  await page.goto(BASE + '/existing-customers/index.php');
  await page.waitForTimeout(800);
  check(await page.evaluate(() => typeof window.snaptr === 'undefined'), 'GPC browser → Snap Pixel not loaded');
  await c.close();
}

await browser.close();
const failed = results.filter(r => !r[0]).length;
console.log(`\n${results.length - failed}/${results.length} passed`);
fs.writeFileSync('/home/claude/fq/qa/e2e-results.json', JSON.stringify(results));
process.exit(failed ? 1 : 0);
