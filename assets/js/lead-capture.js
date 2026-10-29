/* Fandaqah 360 · anonymous lead funnel (linked from every PHP copy by tools/build-php.py).

   Records the steps of the journey, never the person:
     page_view → engaged (half the page read, or 30 s on it) → cta_click / whatsapp_click / call_click / email_click
     → lead_form_open → lead_form_submit → lead_success (or newsletter_success)
   Nothing a visitor types is read here. Name, mobile and email reach the CRM only when the visitor sends a form, through the
   page's own form script and the lead proxy (api/lead.php, which holds the CRM key). A WhatsApp, call or email click is recorded
   as a click: the link holds our number or address, never theirs.

   Each step goes to api/lead-event.php (same site), which keeps only the fields below and writes them to the private funnel log
   behind api/funnel.php. The visit id is a random number kept for this browser tab only (sessionStorage), so a funnel can count
   visits without knowing who made them. A browser sending Global Privacy Control is not counted.
   QA: ?fqfunnel=debug logs each step in the console. */
(() => {
  'use strict';
  const PROXY = (document.querySelector('meta[name="fq-lead-proxy"]') || {}).content || '';
  if (!PROXY || window.FQFunnel || navigator.globalPrivacyControl === true || /[?&]render=1\b/.test(location.search)) return;
  const ENDPOINT = PROXY.replace(/lead\.php$/, 'lead-event.php');
  const DEBUG = /[?&]fqfunnel=debug\b/.test(location.search);
  const root = document.documentElement, START = Date.now();

  // which campaign this page belongs to (A existing, B new hotels, C Opera/PMS, D groups)
  const ROUTES = [
    ['existing-customers', 'A', 'Existing'], ['fandaqah-360-landing-v2', 'A', 'New'], ['new-hotels', 'B', 'New hotels'],
    ['opera-pms', 'C', 'Opera / PMS'], ['hotel-groups', 'D', 'Hotel groups'], ['Fandaqah_Campaign_D', 'D', 'Hotel groups'],
    ['/landing/', '', 'Fandaqah website']
  ];
  const [, SEGMENT, CAMPAIGN] = ROUTES.find(([k]) => location.pathname.includes(k)) || ['', '', 'Fandaqah 360'];

  // a random visit id for this tab only: counts visits, identifies no one
  let VISIT = '';
  try { VISIT = sessionStorage.getItem('fq_funnel_visit') || ''; } catch (e) {}
  if (!VISIT) {
    VISIT = (crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2)).replace(/-/g, '').slice(0, 20);
    try { sessionStorage.setItem('fq_funnel_visit', VISIT); } catch (e) {}
  }
  // campaign tags: from this address, or the visit's first tagged address (the campaign site keeps them in fq360_attr, the
  // two F360 pages in f360_attr, 90 days each). ck is the NAME of the ad click id the visit carries (gclid, fbclid, ttclid,
  // ScCid, li_fat_id…), never its value: the server turns it into the ad platform (api/fq-visitor-lib.php)
  const CLICK_IDS = ['gclid', 'gbraid', 'wbraid', 'gad_campaignid', 'gad_source', 'fbclid', 'ttclid', 'ScCid', 'sccid', 'li_fat_id'];
  const tags = (() => {
    const q = new URLSearchParams(location.search), out = {}, kept = [];
    try { const a = JSON.parse(localStorage.getItem('fq360_attr') || '{}'); kept.push(a.latest || {}, a.first || {}); } catch (e) {}
    try { const a = JSON.parse(localStorage.getItem('f360_attr') || '{}'); kept.push(a.last || {}, a.first || {}); } catch (e) {}
    const has = k => q.get(k) ? q.get(k) : (kept.find(t => t && t[k]) || {})[k];
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'].forEach(k => { const v = has(k); if (v) out[k] = String(v).slice(0, 80); });
    const ck = CLICK_IDS.find(k => has(k));
    if (ck) out.ck = ck;
    return out;
  })();
  let refHost = '';
  try { refHost = document.referrer ? new URL(document.referrer).hostname : ''; } catch (e) {}
  // the visit id and the site the visit came from, for this tab's session only: the lead proxy (api/lead.php, same site) reads
  // them, so the Lead Center can show the journey behind each lead. No expiry date: they go when the browser closes
  try {
    const secure = location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = 'fq_visit=' + VISIT + '; path=/; SameSite=Lax' + secure;
    if (refHost && refHost !== location.hostname && !/(^|; )fq_ref=/.test(document.cookie))
      document.cookie = 'fq_ref=' + encodeURIComponent(refHost.slice(0, 100)) + '; path=/; SameSite=Lax' + secure;
  } catch (e) {}

  const sent = new Set();
  function step(event, extra = {}, onceKey = event) {
    if (sent.has(onceKey)) return;
    sent.add(onceKey);
    const body = {
      event, visit: VISIT, page: location.pathname, campaign: CAMPAIGN, segment: SEGMENT || undefined,
      lang: (root.lang || 'ar').slice(0, 2), device: matchMedia('(pointer: coarse)').matches ? 'mobile' : 'desktop',
      ref: refHost || undefined, sec: Math.round((Date.now() - START) / 1000), ...tags, ...extra
    };
    const json = JSON.stringify(body);
    let queued = false;
    try { queued = !!navigator.sendBeacon && navigator.sendBeacon(ENDPOINT, new Blob([json], { type: 'application/json' })); } catch (e) {}
    if (!queued) fetch(ENDPOINT, { method: 'POST', body: json, keepalive: true, credentials: 'same-origin', headers: { 'Content-Type': 'application/json' } }).catch(() => {});
    if (DEBUG) console.info('[FQFunnel]', event, body);
  }

  /* ---------- the steps ---------- */
  step('page_view');

  // engaged: half the page read, or 30 s with the tab in front
  let seen = 0, last = Date.now();
  const engaged = why => step('engaged', { why });
  addEventListener('scroll', () => {
    const h = document.documentElement.scrollHeight - innerHeight;
    if (h > 0 && scrollY / h >= 0.5) engaged('scroll_50');
  }, { passive: true });
  const timer = setInterval(() => {
    const now = Date.now();
    if (document.visibilityState === 'visible') seen += now - last;
    last = now;
    if (seen >= 30e3) { engaged('time_30s'); clearInterval(timer); }
  }, 1000);

  // clicks: WhatsApp, call and email once each per page view; each call to action once per label
  document.addEventListener('click', e => {
    const a = e.target.closest && e.target.closest('a[href], button, [data-demo], [data-lead]');
    if (!a) return;
    const href = (a.getAttribute('href') || '').toLowerCase();
    const label = (a.getAttribute('aria-label') || a.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 60);
    if (/wa\.me\/|api\.whatsapp\.com|^whatsapp:/.test(href)) return step('whatsapp_click', { label });
    if (href.startsWith('tel:')) return step('call_click', { label });
    if (href.startsWith('mailto:')) return step('email_click', { label });
    if (a.id === 'send-request') return step('lead_form_submit', { form: 'meeting-form' });   // Campaign D sends with a button
    if (a.matches('[data-demo], [data-lead], [href="#contact"], [href="#lead"], [href="#upgrade"], [href="#pricing"], .btn-coral, .button.primary'))
      step('cta_click', { label }, 'cta:' + label);
  }, true);

  // the form: first time the visitor is in a lead form, and each time they press send
  const isLeadForm = f => !!(f && f.matches && f.matches('form') && f.querySelector('input[type="email"], input[type="tel"], [name="email"], [name="phone"]'));
  const formName = f => f.id || (f.classList[0] || 'form');
  document.addEventListener('focusin', e => {
    const f = e.target.closest && e.target.closest('form');
    if (isLeadForm(f)) step('lead_form_open', { form: formName(f) }, 'open:' + formName(f));
  }, true);
  document.addEventListener('submit', e => {
    if (isLeadForm(e.target)) step('lead_form_submit', { form: formName(e.target) }, 'submit:' + formName(e.target) + ':' + Date.now());
  }, true);

  // success: the page's own confirmation (pushed only after the CRM accepted the request)
  window.dataLayer = window.dataLayer || [];
  const success = ev => {
    if (!ev || typeof ev !== 'object') return;
    if (ev.event === 'generate_lead') step('lead_success');
    if (ev.event === 'newsletter_signup') step('newsletter_success');
  };
  window.dataLayer.forEach(success);
  const push = window.dataLayer.push;
  window.dataLayer.push = function (...events) { events.forEach(success); return push.apply(this, events); };

  window.FQFunnel = { step: (e, x) => step(String(e), x || {}, String(e) + ':' + Date.now()), visit: VISIT };
})();
