/*!
 * F360 Measurement Layer v1.0.0  -  Fandaqah 360 landing pages
 * Google Ads AW-18383069867 | GA4 G-70NH8KGTDQ | Enhanced Conversions for Leads
 *
 * Load AFTER the F360 head block (consent defaults + gtag config):
 *   <script defer src="/landing/fandaqah-360/assets/js/f360-measurement.js"></script>
 *
 * What it does
 *  - Fires F360_Lead_Success ONLY on confirmed success (the page's own
 *    dataLayer `generate_lead` push, which happens after a 2xx from the CRM).
 *  - Sends Enhanced Conversions user data as client-side SHA-256 hashes
 *    (no raw PII ever enters dataLayer, URLs or cookies).
 *  - Fires the 8 secondary micro-conversions (observation only).
 *  - Adds campaign_type (existing_customer / new_customer) to every event.
 *  - Captures gclid / gbraid / wbraid + UTMs (first & last touch) for CRM /
 *    Data Manager API offline imports: window.F360.clickIds() / .attribution()
 *  - Debug: append ?f360_debug=1 to the URL to log every hit to the console.
 *
 * On this site (googleads/ pack, 27 Oct 2026): linked from the <head> of every PHP copy by tools/build-php.py, after
 * assets/js/fq-ads.js (which holds the pack's head block). Every page keeps the click ids and UTMs (window.F360); the
 * conversions run only where the script tag carries data-google-events, the two Fandaqah 360 landing pages.
 * One lead, one conversion, one id: transaction_id = the request's submission_id (the id every other platform uses), and
 * this file sends NO Meta Lead (assets/js/fq-meta-events.js does, paired with the server), so nothing is counted twice.
 */
(function () {
  'use strict';
  if (window.F360 && window.F360.version) return; // load once
  var me = document.currentScript, EVENTS = !!(me && me.hasAttribute('data-google-events'));

  // ------------------------------------------------------------------ config
  var AW = 'AW-18383069867';
  var GA4 = 'G-70NH8KGTDQ';
  var LABEL = {
    lead:      '8w92CO-ejqodEKvF3b1E', // F360_Lead_Success  (PRIMARY, count = One)
    pageview:  'iXCiCOizyKsdEKvF3b1E', // F360_PageView      (secondary)
    engaged:   'LycCCOuzyKsdEKvF3b1E', // F360_EngagedVisit  (secondary)
    whatsapp:  'AjcGCO6zyKsdEKvF3b1E', // F360_WhatsApp_Click(secondary)
    phone:     'OP7mCPGzyKsdEKvF3b1E', // F360_Phone_Click   (secondary)
    email:     'H2BYCMGiyqsdEKvF3b1E', // F360_Email_Click   (secondary)
    cta:       'IkmCCMSiyqsdEKvF3b1E', // F360_CTA_Click     (secondary)
    formOpen:  'F9RyCMeiyqsdEKvF3b1E', // F360_LeadForm_Open (secondary)
    formStart: '2J90CMqiyqsdEKvF3b1E'  // F360_LeadForm_Start(secondary)
  };
  var ENGAGED_SCROLL = 0.5;     // 50 % of page
  var ENGAGED_MS = 30000;       // 30 s of visible time
  var ATTR_KEY = 'f360_attr';
  var ATTR_TTL_MS = 90 * 864e5; // matches 90-day click-through window
  var SEND_META_LEAD = false;   // off: fq-meta-events.js sends the one Meta Lead (eventID = submission_id), paired with Meta CAPI

  var path = location.pathname;
  var CAMPAIGN_TYPE =
    /\/existing-customers\/?/.test(path) ? 'existing_customer' :
    /\/fandaqah-360-landing-v2\/?/.test(path) ? 'new_customer' : 'unknown';
  var LANDING = path.replace(/\/+$/, '/');
  var DEBUG = /[?&]f360_debug=1\b/.test(location.search);

  window.dataLayer = window.dataLayer || [];
  function g() {
    if (typeof window.gtag === 'function') return window.gtag.apply(window, arguments);
    window.dataLayer.push(arguments);
  }
  function log() { if (DEBUG && window.console) console.log.apply(console, ['[F360]'].concat([].slice.call(arguments))); }

  // ------------------------------------------------------------- storage
  function sGet(k) { try { return JSON.parse(localStorage.getItem(k)); } catch (e) { return null; } }
  function sSet(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); return true; } catch (e) { return false; } }
  var once = {};
  function first(key) { if (once[key]) return false; once[key] = true; return true; }

  // ----------------------------------------------------------- attribution
  var CLICK_KEYS = ['gclid', 'gbraid', 'wbraid', 'fbclid', 'li_fat_id', 'ttclid', 'ScCid'];
  var UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
  var attr = (function () {
    var qs = new URLSearchParams(location.search), touch = {}, has = false;
    CLICK_KEYS.concat(UTM_KEYS).forEach(function (k) {
      var v = qs.get(k); if (v) { touch[k] = v.slice(0, 200); has = true; }
    });
    var now = Date.now(), saved = sGet(ATTR_KEY);
    if (saved && now - (saved.ts || 0) > ATTR_TTL_MS) saved = null;
    if (!has) return saved || { first: {}, last: {}, ts: now };
    touch.landing_page = LANDING; touch.at = new Date(now).toISOString();
    var rec = { first: (saved && saved.first && Object.keys(saved.first).length) ? saved.first : touch, last: touch, ts: now };
    sSet(ATTR_KEY, rec);
    return rec;
  })();
  function clickIds() {
    var out = {}, l = attr.last || {}, f = attr.first || {};
    ['gclid', 'gbraid', 'wbraid'].forEach(function (k) { var v = l[k] || f[k]; if (v) out[k] = v; });
    return out;
  }

  // ----------------------------------------------------------- base params
  function params(extra) {
    var p = { campaign_type: CAMPAIGN_TYPE, landing_page: LANDING };
    if (extra) for (var k in extra) if (extra[k] !== undefined) p[k] = extra[k];
    return p;
  }
  // One helper = one Ads micro-conversion + one GA4 event + one dataLayer event
  function micro(key, gaEvent, extra) {
    if (!first(key)) return;
    var p = params(extra);
    g('event', 'conversion', { send_to: AW + '/' + LABEL[key] });
    g('event', gaEvent, Object.assign({ send_to: GA4 }, p));
    window.dataLayer.push(Object.assign({ event: gaEvent }, p));
    log(key, gaEvent, p);
  }

  // every other page: the click ids and UTMs only (window.F360), no conversions
  if (!EVENTS) {
    window.F360 = { version: '1.0.0', campaignType: CAMPAIGN_TYPE, events: false, clickIds: clickIds,
      attribution: function () { return JSON.parse(JSON.stringify(attr)); } };
    return;
  }

  // context for any GTM container added later
  window.dataLayer.push(params({ event: 'f360_context' }));
  g('set', { campaign_type: CAMPAIGN_TYPE });

  // ------------------------------------------------------------ page view
  micro('pageview', 'f360_page_view');

  // --------------------------------------------------------- engaged visit
  (function () {
    var visibleMs = 0, last = Date.now(), timer;
    function done() { clearInterval(timer); window.removeEventListener('scroll', onScroll); micro('engaged', 'f360_engaged_visit'); }
    function onScroll() {
      var h = document.documentElement, max = (h.scrollHeight - h.clientHeight) || 1;
      if ((window.scrollY || h.scrollTop) / max >= ENGAGED_SCROLL) done();
    }
    timer = setInterval(function () {
      var now = Date.now();
      if (document.visibilityState === 'visible') visibleMs += now - last;
      last = now;
      if (visibleMs >= ENGAGED_MS) done();
    }, 1000);
    window.addEventListener('scroll', onScroll, { passive: true });
  })();

  // -------------------------------------------------- clicks (delegated)
  var CTA_SEL = '[data-cta],[data-lead],a[href="#contact"],a[href="#lead"],a[href="#lead-form"],a[href$="#contact"]';
  document.addEventListener('click', function (e) {
    var a = e.target && e.target.closest ? e.target.closest('a,button,[data-cta],[data-lead]') : null;
    if (!a) return;
    var href = (a.getAttribute('href') || '').toLowerCase();
    if (/wa\.me|whatsapp\.com|whatsapp:/.test(href)) return micro('whatsapp', 'f360_whatsapp_click');
    if (href.indexOf('tel:') === 0) return micro('phone', 'f360_phone_click');
    if (href.indexOf('mailto:') === 0) return micro('email', 'f360_email_click');
    if (a.type === 'submit' || (a.closest && a.closest('form') && a.tagName === 'BUTTON')) return; // submit != CTA
    if (a.matches && a.matches(CTA_SEL)) {
      micro('cta', 'f360_cta_click', { cta_name: (a.getAttribute('data-cta') || a.textContent || '').trim().slice(0, 60) });
    }
  }, true);

  // --------------------------------------------------- form open / start
  var captured = null; // user-provided data, held in memory only
  function bindForm(form) {
    if (form.__f360) return; form.__f360 = true;
    var formName = form.id || form.getAttribute('name') || 'lead_form';

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (ents) {
        ents.forEach(function (en) {
          if (en.isIntersecting && en.intersectionRatio >= 0.4) { micro('formOpen', 'f360_form_open', { form_name: formName }); io.disconnect(); }
        });
      }, { threshold: [0.4] });
      io.observe(form);
    }
    form.addEventListener('focusin', function (e) {
      var n = e.target && e.target.name;
      if (n && n !== 'website') { micro('formOpen', 'f360_form_open', { form_name: formName }); micro('formStart', 'f360_form_start', { form_name: formName }); }
    });
    // capture values at submit (before the async CRM call). Never pushed anywhere raw.
    form.addEventListener('submit', function () {
      var v = function (n) { var el = form.elements[n]; return el && el.value ? String(el.value) : ''; };
      if (v('website')) return; // honeypot
      captured = { email: v('email'), phone: v('phone'), name: v('name') || v('full_name') || v('contact_name'),
                   form_name: formName, plan: (form.querySelector('[name="term"]:checked') || {}).value };
    }, true);
  }
  function scanForms() { [].forEach.call(document.forms, bindForm); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scanForms); else scanForms();
  if ('MutationObserver' in window) new MutationObserver(scanForms).observe(document.documentElement, { childList: true, subtree: true });

  // ------------------------------------------------ normalise + hash (EC)
  // Arabic-Indic (U+0660-0669) and Extended/Persian (U+06F0-06F9) digits -> ASCII.
  // Escaped on purpose: the file stays pure ASCII whatever charset the server sends.
  function asciiDigits(s) {
    return s.replace(/[\u0660-\u0669\u06F0-\u06F9]/g, function (d) {
      var c = d.charCodeAt(0); return String(c >= 0x06F0 ? c - 0x06F0 : c - 0x0660);
    });
  }
  function normEmail(s) {
    s = String(s || '').trim().toLowerCase();
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(s)) return '';
    var p = s.split('@');
    if (p[1] === 'gmail.com' || p[1] === 'googlemail.com') p[0] = p[0].replace(/\./g, '');
    return p[0] + '@' + p[1];
  }
  function normPhone(s) {
    s = asciiDigits(String(s || ''));
    var plus = /^\s*\+/.test(s); s = s.replace(/\D/g, '');
    if (!s) return '';
    if (s.indexOf('00') === 0) { s = s.slice(2); plus = true; }
    if (!plus) {
      if (/^05\d{8}$/.test(s)) s = '966' + s.slice(1);   // 05XXXXXXXX
      else if (/^5\d{8}$/.test(s)) s = '966' + s;        // 5XXXXXXXX
      else if (!/^966\d{9}$/.test(s)) return '';         // unknown local format -> skip
    }
    if (/^9660\d{9}$/.test(s)) s = '966' + s.slice(4);   // +966 05... typo
    return (s.length >= 8 && s.length <= 15) ? '+' + s : '';
  }
  function normName(s) { return String(s || '').trim().toLowerCase().replace(/[.,'"`\u2019]/g, '').replace(/\s+/g, ' '); }
  function sha256(str) {
    if (!str || !(window.crypto && crypto.subtle && window.TextEncoder)) return Promise.resolve('');
    return crypto.subtle.digest('SHA-256', new TextEncoder().encode(str)).then(function (buf) {
      return Array.prototype.map.call(new Uint8Array(buf), function (b) { return ('0' + b.toString(16)).slice(-2); }).join('');
    }).catch(function () { return ''; });
  }
  function hashedUserData(c) {
    if (!c) return Promise.resolve(null);
    var parts = normName(c.name).split(' ');
    var fn = parts[0] || '', ln = parts.length > 1 ? parts.slice(1).join(' ') : '';
    return Promise.all([sha256(normEmail(c.email)), sha256(normPhone(c.phone)), sha256(fn), sha256(ln)]).then(function (h) {
      var ud = {};
      if (h[0]) ud.sha256_email_address = h[0];
      if (h[1]) ud.sha256_phone_number = h[1];
      if (h[2] && h[3]) ud.address = { sha256_first_name: h[2], sha256_last_name: h[3], country: 'SA' };
      return Object.keys(ud).length ? ud : null;
    });
  }
  function uuid() {
    if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
      var r = Math.random() * 16 | 0; return (c === 'x' ? r : (r & 3 | 8)).toString(16);
    });
  }

  // ------------------------------------------------------- LEAD SUCCESS
  var leadFired = false;
  function onLeadSuccess(src) {
    if (leadFired) return; leadFired = true;           // once per page load
    var c = captured, leadId = (src && src.submission_id) || uuid();   // the request's own id, shared with every platform
    var p = params({ form_name: (c && c.form_name) || 'lead_form', lead_id: leadId, lead_plan: (src && (src.lead_plan || src.interest)) || (c && c.plan) || undefined });
    hashedUserData(c).then(function (ud) {
      if (ud) g('set', 'user_data', ud);
      window.__F360_LEAD_GATE = true;                    // opens the head-snippet guard
      g('event', 'conversion', { send_to: AW + '/' + LABEL.lead, transaction_id: leadId });
      window.__F360_LEAD_GATE = false;
      g('event', 'generate_lead', Object.assign({ send_to: GA4 }, p));
      window.dataLayer.push(Object.assign({ event: 'f360_lead_success' }, p)); // no PII
      if (SEND_META_LEAD && typeof window.fbq === 'function') {
        try { window.fbq('track', 'Lead', { content_name: 'Fandaqah 360', content_category: CAMPAIGN_TYPE }, { eventID: leadId }); } catch (e) {}
      }
      log('LEAD SUCCESS', p, ud ? Object.keys(ud) : 'no user_data');
      captured = null;                                   // drop PII from memory
    });
  }
  // Listen for the page's own success signal: dataLayer.push({event:'generate_lead'})
  // Two independent paths (push hook + 400 ms scan) so it survives gtag.js/GTM
  // re-wrapping dataLayer.push. leadFired guarantees a single conversion.
  (function watch() {
    var dl = window.dataLayer, seen = 0;
    function scan() {
      for (; seen < dl.length; seen++) {
        var o = dl[seen];
        if (o && typeof o === 'object' && !('length' in o) && o.event === 'generate_lead') onLeadSuccess(o);
      }
    }
    var orig = dl.push;
    dl.push = function () { var r = orig.apply(dl, arguments); scan(); return r; };
    scan();
    setInterval(scan, 400);
  })();

  // ------------------------------------------------------------ public API
  window.F360 = {
    version: '1.0.0',
    campaignType: CAMPAIGN_TYPE,
    events: true,
    clickIds: clickIds,
    attribution: function () { return JSON.parse(JSON.stringify(attr)); },
    _norm: { email: normEmail, phone: normPhone, name: normName } // for QA
  };
  log('ready', CAMPAIGN_TYPE, clickIds());
})();