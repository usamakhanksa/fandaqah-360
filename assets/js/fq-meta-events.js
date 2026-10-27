/*!
 * Fandaqah 360 — Meta Pixel event layer  v1.0  (2026-10-27)
 * Dataset / Pixel: 992779690541049  (the ONLY pixel — do not add another)
 *
 * INSTALL: upload to /landing/fandaqah-360/assets/js/fq-meta-events.js and load it
 * on BOTH landing pages, AFTER the existing base pixel snippet:
 *   <script src="/landing/fandaqah-360/assets/js/fq-meta-events.js" defer></script>
 *
 * The base snippet keeps firing PageView (unchanged). This file adds:
 *   ViewContent       once, at 50% scroll OR 30 s on page (whichever first)
 *   Contact           WhatsApp / tel / mailto / demo / pricing CTA clicks
 *   InitiateCheckout  once, when the lead form is at least 50% visible
 *   LeadFormStart     once, on the first VALID value typed into the form (custom)
 *   Lead              ONLY when the page code calls fqMeta.lead() after the CRM/proxy says OK
 *
 * Every event carries campaign_type (existing_customer | new_customer) and landing_page.
 * Every event carries an eventID so the server (CAPI) copy can be deduplicated.
 * Uses trackSingle so events never leak into any other pixel added later.
 *
 * On this site (meta/ pack, 27 Oct 2026): linked from the <head> of every PHP copy by tools/build-php.py, after
 * assets/js/fq-ads.js (the base pixel: init + PageView). The events above run only where the script tag carries
 * data-meta-events, the two Fandaqah 360 landing pages, the same pages api/lead.php sends the server Lead for.
 * Lead fires on the page's own confirmation (the fq:lead event, sent by site.js and the v2 script after the CRM accepted the
 * request) with eventID = submission_id; the server reads _fbp/_fbc from the same-site request, so the forms need no patch.
 * Events wait for the base pixel instead of creating fbq themselves (that would stop Meta's library from loading).
 * QA: ?fqmeta=debug logs every event in the console.
 */
(function (w, d) {
  'use strict';
  if (w.fqMeta) return; // loaded twice → do nothing the second time
  var me = d.currentScript, EVENTS = !!(me && me.hasAttribute('data-meta-events'));
  var DEBUG = /[?&]fqmeta=debug\b/.test(location.search);

  var PIXEL_ID = '992779690541049';
  var path = location.pathname;
  var CAMPAIGN_TYPE = /existing-customers/.test(path) ? 'existing_customer'
                    : /landing-v2/.test(path)         ? 'new_customer'
                    : 'other';
  var LANDING = path.replace(/\/+$/, '').split('/').pop() || 'root';
  var STANDARD = { ViewContent: 1, Contact: 1, InitiateCheckout: 1, Lead: 1 };
  var fired = {};
  var log = [];

  function uuid() {
    if (w.crypto && typeof w.crypto.randomUUID === 'function') return w.crypto.randomUUID();
    return 'fq-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 12);
  }
  function cookie(name) {
    var m = d.cookie.match('(?:^|; )' + name.replace(/[.$?*|{}()[\]\\/+^]/g, '\\$&') + '=([^;]*)');
    return m ? decodeURIComponent(m[1]) : null;
  }
  // _fbc may not exist yet on the first page view; build it from fbclid exactly as Meta specifies
  function fbc() {
    var c = cookie('_fbc');
    if (c) return c;
    var id = new URLSearchParams(location.search).get('fbclid');
    return id ? 'fb.1.' + Date.now() + '.' + id : null;
  }
  function params(extra) {
    var o = { campaign_type: CAMPAIGN_TYPE, landing_page: LANDING };
    if (extra) for (var k in extra) if (extra[k] !== undefined && extra[k] !== null && extra[k] !== '') o[k] = extra[k];
    return o;
  }

  /** Fire one pixel event. opts.once → at most once per page view. Returns the eventID used. */
  function send(name, extra, opts) {
    opts = opts || {};
    var key = opts.key || name;
    if (opts.once && fired[key]) return fired[key];
    var eventID = opts.eventID || uuid();
    fired[key] = eventID;
    var p = params(extra);
    log.push({ event: name, eventID: eventID, params: p, t: Date.now() });
    deliver([STANDARD[name] ? 'trackSingle' : 'trackSingleCustom', PIXEL_ID, name, p, { eventID: eventID }]);
    if (DEBUG && w.console) console.info('[fq-meta]', name, p, eventID);
    return eventID;
  }
  // the base pixel (fq-ads.js) loads async: hold events until fbq exists, for up to 15 s, never define fbq here
  var waiting = [];
  function deliver(args) {
    if (typeof w.fbq === 'function') { w.fbq.apply(w, args); return; }
    waiting.push(args);
    if (waiting.length === 1) {
      var tries = 0, t = setInterval(function () {
        if (typeof w.fbq === 'function' || ++tries > 60) {
          clearInterval(t);
          if (typeof w.fbq === 'function') waiting.forEach(function (a) { w.fbq.apply(w, a); });
          waiting = [];
        }
      }, 250);
    }
  }

  // every other page: the context helper only, no events
  if (!EVENTS) {
    w.fqMeta = { version: '1.0', campaignType: CAMPAIGN_TYPE, events: false,
      capiContext: function (id) { return { event_id: id, fbp: cookie('_fbp'), fbc: fbc(), event_source_url: location.href.split('#')[0] }; },
      lead: function () { return null; }, track: function () { return null; }, debug: function () { return log.slice(); } };
    return;
  }

  /* ---------- ViewContent: 50% scroll or 30 s ---------- */
  function engaged(trigger) {
    send('ViewContent', { content_name: 'Fandaqah 360 | ' + LANDING, content_category: 'landing_page', engagement_trigger: trigger }, { once: true });
  }
  var timer = setTimeout(function () { engaged('time_30s'); }, 30000);
  function onScroll() {
    var h = d.documentElement;
    var seen = (w.scrollY + w.innerHeight) / Math.max(h.scrollHeight, 1);
    if (seen >= 0.5) { engaged('scroll_50'); clearTimeout(timer); w.removeEventListener('scroll', onScroll); }
  }
  w.addEventListener('scroll', onScroll, { passive: true });

  /* ---------- Contact: CTA clicks ---------- */
  var lastClick = {};
  function methodOf(a) {
    var href = (a.getAttribute('href') || '').toLowerCase();
    if (a.dataset && a.dataset.fqCta) return a.dataset.fqCta;           // explicit override: data-fq-cta="demo"
    if (/wa\.me|whatsapp/.test(href)) return 'whatsapp';
    if (href.indexOf('tel:') === 0) return 'call';
    if (href.indexOf('mailto:') === 0) return 'email';
    if (/^#(contact|lead|demo|form)/.test(href)) return 'demo';
    if (/^#(upgrade|pricing|price|plans?)/.test(href)) return 'pricing';
    return null;
  }
  d.addEventListener('click', function (e) {
    var a = e.target && e.target.closest ? e.target.closest('a[href], button[data-fq-cta]') : null;
    if (!a) return;
    var method = methodOf(a);
    if (!method) return;
    var name = (a.getAttribute('aria-label') || a.textContent || method).replace(/\s+/g, ' ').trim().slice(0, 60);
    var k = method + '|' + name;
    if (lastClick[k] && Date.now() - lastClick[k] < 2000) return;       // ignore double-clicks
    lastClick[k] = Date.now();
    send('Contact', { contact_method: method, cta_name: name });
  }, true);

  /* ---------- Lead form: InitiateCheckout (visible) + LeadFormStart (first valid input) ---------- */
  function findForm() {
    var f = d.getElementById('lead-form');
    if (f) return f;
    var ph = d.querySelector('form input[name="phone"], form input[name="email"]');
    return ph ? ph.form : null;
  }
  function valid(el) {
    if (!el || el.name === 'website' || el.type === 'hidden') return false;      // honeypot / hidden
    var v = (el.value || '').trim();
    if (el.type === 'checkbox' || el.type === 'radio') return el.checked;
    if (el.name === 'email' || el.type === 'email') return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v);
    if (el.name === 'phone' || el.type === 'tel') return v.replace(/\D/g, '').length >= 9;
    if (el.tagName === 'SELECT') return v !== '';
    return v.length >= 2;
  }
  function wireForm(form) {
    var formName = form.id || form.getAttribute('name') || 'lead_form';
    if ('IntersectionObserver' in w) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting && en.intersectionRatio >= 0.5) {
            send('InitiateCheckout', { content_name: 'F360 lead form', content_category: 'lead_form', form_name: formName }, { once: true });
            io.disconnect();
          }
        });
      }, { threshold: [0.5] });
      io.observe(form);
    }
    function onInput(e) {
      if (fired.LeadFormStart) return;
      if (valid(e.target)) {
        send('LeadFormStart', { form_name: formName, first_field: e.target.name || e.target.id || 'field' }, { once: true });
        form.removeEventListener('input', onInput, true);
        form.removeEventListener('change', onInput, true);
      }
    }
    form.addEventListener('input', onInput, true);
    form.addEventListener('change', onInput, true);
  }
  function init() { var f = findForm(); if (f) wireForm(f); }
  if (d.readyState === 'loading') d.addEventListener('DOMContentLoaded', init); else init();

  /* ---------- Public API used by the form code ---------- */
  w.fqMeta = {
    version: '1.0',
    campaignType: CAMPAIGN_TYPE,
    /** Data to attach to the lead payload so the server can send the SAME event to CAPI. No PII here. */
    capiContext: function (eventID) {
      return {
        event_id: eventID,
        fbp: cookie('_fbp'),
        fbc: fbc(),
        event_source_url: location.href.split('#')[0],
        campaign_type: CAMPAIGN_TYPE,
        landing_page: LANDING
      };
    },
    /** Call ONLY after the CRM/proxy confirmed success. eventID MUST equal capiContext().event_id. */
    lead: function (extra, eventID) {
      if (!eventID) return null;
      return send('Lead', extra, { once: true, key: 'Lead:' + eventID, eventID: eventID });
    },
    track: send,          // for future CTAs: fqMeta.track('Contact', {contact_method:'demo', cta_name:'...'})
    events: true,
    debug: function () { return log.slice(); }
  };

  // the confirmed lead, announced by the page's form code once the CRM accepted it (same id as the server Lead)
  d.addEventListener('fq:lead', function (e) {
    var id = e.detail && e.detail.id;
    if (id) w.fqMeta.lead({ lead_id: id, form_name: findForm() ? (findForm().id || 'f360_v2_form') : 'lead_form' }, id);
  });
})(window, document);