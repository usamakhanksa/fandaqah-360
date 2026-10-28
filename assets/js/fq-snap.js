/* Fandaqah 360 · Snap Pixel (browser half of Pixel + Conversions API).

   Loaded in the <head> of every PHP copy by tools/build-php.py. It never touches the page's own form code: it watches the page
   and the lead request instead, so site.js, the v2 landing script and lead-capture.js stay exactly as they are.

   Event map (Snap has no LEAD event and no named custom events, so B2B stages use SIGN_UP and CUSTOM_EVENT_1..5):
     PAGE_VIEW        every page load
     VIEW_CONTENT     meaningful engagement: 50% scroll or 30 s of visible time, once per page
     CUSTOM_EVENT_1   CTA click: WhatsApp, call, email, "book a demo / reserve" (description = cta_<type>), once per type per page
     CUSTOM_EVENT_2   lead form opened: the form (or the v2 dialog) is on screen, once per page
     CUSTOM_EVENT_3   lead form started: first real value typed into the form, once per page
     SIGN_UP          confirmed lead: ONLY after api/lead.php (or the CRM) answers ok. Never on load, never on an invalid form, never
                      for the honeypot, never for lead-capture.js "partial" leads. client_dedup_id = the lead's submission_id,
                      the same id api/lead.php sends to the Conversions API as event_id, so Snap counts the lead once.
     CUSTOM_EVENT_4   qualified lead       } sent offline from the CRM by tools/snap-offline-capi.py (never from the browser)
     CUSTOM_EVENT_5   opportunity          }
     SUBSCRIBE        customer (won)       } PURCHASE is never used on this account.
   Every event carries event_tag = existing_customer | new_customer and customer_status = returning | new, so Custom Conversions
   and audiences can be split per campaign.

   Privacy: a browser sending Global Privacy Control gets no Snap tracking at all. Email/phone are SHA-256 hashed in the browser
   before they reach Snap. QA: add ?fqsnap=debug to the page address to log every event in the console. */
(() => {
  'use strict';
  // ↓↓↓ The only value to fill in: the Pixel ID from Snap Events Manager (Organization Admin creates it). Empty = this file does nothing.
  const PIXEL_ID = (window.FQ_SNAP && window.FQ_SNAP.pixelId) || '';
  // ↑↑↑ api/config.php holds the same Pixel ID (FQ_SNAP_PIXEL_ID) plus the Conversions API token, which never ships to the browser.

  // On this site (snapcha-retargeting pack, 27 Oct 2026): every PHP page gets PAGE_VIEW and keeps the Snap click id; the
  // conversion events (VIEW_CONTENT, CUSTOM_EVENT_1-3, SIGN_UP) run only where the script tag carries data-snap-events, the two
  // Fandaqah 360 landing pages, the same pages api/lead.php sends the server SIGN_UP for (as with LinkedIn and TikTok).
  const me = document.currentScript, EVENTS = !!(me && me.hasAttribute('data-snap-events'));

  if (window.FQSnap || !PIXEL_ID || navigator.globalPrivacyControl === true || /[?&]render=1\b/.test(location.search)) return;

  const DEBUG = /[?&]fqsnap=debug\b/.test(location.search);
  const log = (...a) => { if (DEBUG) console.info('[fq-snap]', ...a); };
  const qs = new URLSearchParams(location.search);
  const store = {
    get: k => { try { return JSON.parse(localStorage.getItem(k)); } catch (e) { return null; } },
    set: (k, v) => { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
  };
  const uuid = () => (crypto.randomUUID ? crypto.randomUUID() : 'fq-' + Date.now().toString(36) + Math.random().toString(36).slice(2));

  /* ---------- which campaign this visit belongs to ---------- */
  // utm_campaign wins (A_snap_existing_upgrade_oct2026 → existing); otherwise the page: existing-customers is the upgrade page,
  // the v2 landing page carries the new-customer (hotel owners) campaign.
  const campaignType = (() => {
    const c = (qs.get('utm_campaign') || (store.get('fq360_attr') || {}).latest?.utm_campaign || '').toLowerCase();
    if (/existing|upgrade|^a[_\-\s]/.test(c)) return 'existing_customer';
    if (c) return 'new_customer';
    return /existing-customers/.test(location.pathname) ? 'existing_customer' : 'new_customer';
  })();
  const BASE = { event_tag: campaignType, customer_status: campaignType === 'existing_customer' ? 'returning' : 'new' };

  /* ---------- Snap click id: kept 28 days so the lead can be matched to the click ---------- */
  const clickId = qs.get('ScCid') || qs.get('sccid');
  if (clickId) store.set('fq_snap_click', { id: clickId.slice(0, 200), at: Date.now() });
  const savedClick = () => { const c = store.get('fq_snap_click'); return c && Date.now() - c.at < 28 * 864e5 ? c.id : undefined; };
  const cookie = name => { const m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)')); return m ? decodeURIComponent(m[1]) : undefined; };

  /* ---------- the official base code ---------- */
  (function (e, t, n) {
    if (e.snaptr) return;
    const a = e.snaptr = function () { a.handleRequest ? a.handleRequest.apply(a, arguments) : a.queue.push(arguments); };
    a.queue = [];
    const r = t.createElement('script'); r.async = !0; r.src = n;
    const u = t.getElementsByTagName('script')[0]; u.parentNode.insertBefore(r, u);
  })(window, document, 'https://sc-static.net/scevent.min.js');
  snaptr('init', PIXEL_ID, {});

  const fired = new Set();
  function track(name, params, onceKey) {
    if (onceKey) { if (fired.has(onceKey)) return; fired.add(onceKey); }
    const p = Object.assign({}, BASE, params);
    snaptr('track', name, p);
    log(name, p);
    if (window.dataLayer) window.dataLayer.push({ event: 'snap_' + name.toLowerCase(), snap_params: p });
  }

  track('PAGE_VIEW', { description: location.pathname });

  // every other page: the pixel and the click id only
  if (!EVENTS) {
    window.FQSnap = { pixel: PIXEL_ID, campaignType, events: false, track, fired: () => [...fired] };
    log('ready (page view only)', { pixel: PIXEL_ID });
    return;
  }

  /* ---------- VIEW_CONTENT: 50% scroll or 30 s visible ---------- */
  const engaged = why => track('VIEW_CONTENT', { item_category: 'fandaqah_360', description: 'engaged_' + why }, 'view_content');
  const onScroll = () => {
    const h = document.documentElement.scrollHeight - innerHeight;
    if (h > 0 && scrollY / h >= 0.5) { engaged('scroll50'); removeEventListener('scroll', onScroll); }
  };
  addEventListener('scroll', onScroll, { passive: true });
  let visibleMs = 0, since = document.visibilityState === 'visible' ? Date.now() : 0;
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') since = Date.now(); else if (since) { visibleMs += Date.now() - since; since = 0; }
  });
  const timer = setInterval(() => {
    const ms = visibleMs + (since ? Date.now() - since : 0);
    if (ms >= 30000) { engaged('time30s'); clearInterval(timer); }
  }, 1000);

  /* ---------- CUSTOM_EVENT_1: CTA clicks ---------- */
  const ctaType = el => {
    const href = (el.getAttribute('href') || '').toLowerCase();
    if (/wa\.me|whatsapp/.test(href)) return 'whatsapp';
    if (href.startsWith('tel:')) return 'call';
    if (href.startsWith('mailto:')) return 'email';
    if (el.hasAttribute('data-lead') || href === '#contact') return 'demo';
    return null;
  };
  document.addEventListener('click', e => {
    const el = e.target.closest('a[href], button[data-lead]');
    const type = el && ctaType(el);
    if (type) track('CUSTOM_EVENT_1', { description: 'cta_' + type }, 'cta_' + type);
  }, true);

  /* ---------- CUSTOM_EVENT_2 / 3: lead form opened and started ---------- */
  const isLeadForm = f => f && f.matches && f.matches('#lead-form, form.lead-form, dialog#lead form');
  const watchForms = () => document.querySelectorAll('#lead-form, form.lead-form, dialog#lead form').forEach(f => {
    if (f.dataset.fqSnap) return;
    f.dataset.fqSnap = '1';
    new IntersectionObserver((entries, io) => entries.forEach(en => {
      if (en.isIntersecting) { track('CUSTOM_EVENT_2', { description: 'lead_form_open' }, 'form_open'); io.disconnect(); }
    }), { threshold: 0.4 }).observe(f);
  });
  watchForms();
  document.addEventListener('DOMContentLoaded', watchForms);
  document.addEventListener('input', e => {
    const el = e.target, f = el.form;
    if (!isLeadForm(f) || el.name === 'website' || el.type === 'hidden' || el.tabIndex === -1) return;
    if ((el.type === 'checkbox' ? el.checked : (el.value || '').trim().length >= 2)) track('CUSTOM_EVENT_3', { description: 'lead_form_start' }, 'form_start');
  }, true);

  /* ---------- CUSTOM_EVENT_4: a valid mobile typed (fq:phone from f360-formopen.js), once; no number sent ---------- */
  document.addEventListener('fq:phone', () => track('CUSTOM_EVENT_4', { description: 'phone_captured' }, 'phone_captured'));

  /* ---------- SIGN_UP: the confirmed lead ---------- */
  const sha256 = async v => {
    if (!v || !crypto.subtle) return undefined;
    const buf = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(v));
    return [...new Uint8Array(buf)].map(b => b.toString(16).padStart(2, '0')).join('');
  };
  const DONE = 'fq_snap_signups';
  async function signUp(body) {
    const id = body.submission_id;
    const sent = store.get(DONE) || [];
    if (!id || sent.includes(id)) return;                          // once per lead, even across retries and reloads
    store.set(DONE, sent.concat(id).slice(-20));
    // advanced matching, hashed here: Snap receives only the SHA-256 of the lower-cased email and the digits-only phone
    const em = body.email ? await sha256(String(body.email).trim().toLowerCase()) : undefined;
    const ph = body.phone ? await sha256(String(body.phone).replace(/\D/g, '').replace(/^00/, '')) : undefined;
    if (em || ph) snaptr('init', PIXEL_ID, Object.assign({}, em && { user_hashed_email: em }, ph && { user_hashed_phone_number: ph }));
    track('SIGN_UP', { client_dedup_id: id, sign_up_method: 'lead_form', success: 1,
      description: 'lead_' + (body.interest || body.segment || 'fandaqah_360') });
  }

  // The lead goes out as fetch(…api/lead.php) (or straight to the CRM when the proxy is missing). We watch that request:
  // add a submission_id when the page has none (v2), pass the Snap identifiers to the proxy, and fire SIGN_UP only on a real "ok".
  const nativeFetch = window.fetch;
  const ids = new Map();                                            // a retried body keeps its first id
  const isProxy = u => /\/api\/lead\.php$/.test(u.pathname);
  const isCrm = u => u.hostname === 'crm.fandaqah.com' && /\/ingest\/lead/.test(u.pathname);
  window.fetch = function (input, init) {
    try {
      const u = new URL(typeof input === 'string' ? input : input.url, location.href);
      if ((isProxy(u) || isCrm(u)) && init && String(init.method).toUpperCase() === 'POST' && typeof init.body === 'string') {
        const body = JSON.parse(init.body);
        if (body && typeof body === 'object' && body.lead_stage !== 'partial') {
          if (!body.submission_id) { body.submission_id = ids.get(init.body) || uuid(); ids.set(init.body, body.submission_id); }
          const out = Object.assign({}, body);
          if (isProxy(u)) out._snap = { sc_cookie1: cookie('_scid'), sc_click_id: savedClick(), page_url: location.href.split('#')[0],
                                        campaign_type: campaignType, user_agent: navigator.userAgent };
          const p = nativeFetch.call(this, input, Object.assign({}, init, { body: JSON.stringify(out) }));
          p.then(async res => {
            if (!res.ok) return log('lead not confirmed', res.status);
            let ok = true;
            if (isProxy(u)) { try { ok = (await res.clone().json()).ok === true; } catch (e) { ok = false; } }
            if (ok) signUp(body);
          }).catch(() => {});
          return p;
        }
      }
    } catch (e) { log('fetch watch skipped', e); }
    return nativeFetch.apply(this, arguments);
  };

  window.FQSnap = { pixel: PIXEL_ID, campaignType, events: true, track, fired: () => [...fired] };
  log('ready', { pixel: PIXEL_ID, campaignType, clickId: savedClick() });
})();
