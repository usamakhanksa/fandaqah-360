/*!
 * Fandaqah 360 - TikTok Pixel + Events API 2.0 bridge   v1.0.0
 * ---------------------------------------------------------------
 * Drop-in, non-invasive. Does NOT modify site.js or the inline form code.
 *
 *  Event map (TikTok standard events unless marked custom)
 *  ---------------------------------------------------------
 *  Page load ............ PageView        (ttq.page)
 *  50% scroll OR 30s .... ViewContent     once per page view
 *  WhatsApp / Call / CTA  Contact         once per channel per page view
 *  Form >=50% visible .... LeadFormOpen    custom, micro, once
 *  First valid input .... LeadFormStart   custom, micro, once
 *  Server-confirmed OK .. Lead            PRIMARY - shares event_id with Events API
 *
 *  How success is detected: both landing pages push
 *  dataLayer {event:'generate_lead'} ONLY after the lead API returns OK.
 *  Lead fires on that signal, never on page load, never on invalid submit.
 *
 *  Dedup: an event_id (UUID) is created at submit, injected into the JSON body
 *  of the same-origin lead POST as `tt.event_id`, and reused for ttq.track('Lead').
 *  lead.php forwards the same id to the Events API -> TikTok keeps one.
 *
 *  Debug: add ?tt_debug=1 to the URL -> every event is logged to the console.
 *  Test events: add ?tt_test=TEST12345 -> server sends test_event_code.
 */
(function (w, d) {
  'use strict';

  /* ------------------------- CONFIG ------------------------- */
  var CFG = {
    pixelId: 'REPLACE_WITH_PIXEL_ID',           // <- paste from Events Manager (e.g. C0ABC123DEF456GHI789)
    formSelector: '#lead-form, form.lead-form, form[data-tt-lead-form]',
    engagement: { scrollPct: 50, seconds: 30 },
    product: {
      content_id: 'fandaqah-360',
      content_name: 'Fandaqah 360',
      content_type: 'product',
      currency: 'SAR'
    },
    ttclidCookieDays: 7
  };

  var qs = w.location.search;
  var DEBUG = /[?&]tt_debug=1\b/.test(qs);
  var TEST_CODE = (qs.match(/[?&]tt_test=([A-Za-z0-9]+)/) || [])[1] || null;

  function log() {
    if (!DEBUG || !w.console) return;
    var a = Array.prototype.slice.call(arguments);
    a.unshift('%c[F360|TikTok]', 'background:#000;color:#25F4EE;padding:2px 6px;border-radius:3px');
    console.log.apply(console, a);
  }

  if (!/^[A-Z0-9]{15,25}$/.test(CFG.pixelId)) {
    if (w.console) console.warn('[F360|TikTok] Pixel ID not set - tracking disabled.');
    return;
  }
  if (w.__F360_TT_LOADED__) return;              // never double-install
  w.__F360_TT_LOADED__ = true;

  /* --------------------- CAMPAIGN CONTEXT -------------------- */
  var path = w.location.pathname;
  var override = (w.F360_TT && w.F360_TT.campaignType) || d.documentElement.getAttribute('data-campaign-type');
  var CAMPAIGN_TYPE = override || (/existing-customers/i.test(path) ? 'existing_customer' : 'new_customer');
  var LANDING_VARIANT = /landing-v2/i.test(path) ? 'v2' : (/existing-customers/i.test(path) ? 'existing' : 'other');

  function baseProps(extra) {
    var p = {
      contents: [{ content_id: CFG.product.content_id, content_name: CFG.product.content_name, content_type: CFG.product.content_type }],
      content_type: CFG.product.content_type,
      currency: CFG.product.currency,
      campaign_type: CAMPAIGN_TYPE,
      landing_variant: LANDING_VARIANT,
      page_lang: d.documentElement.lang || 'ar'
    };
    for (var k in extra) if (Object.prototype.hasOwnProperty.call(extra, k)) p[k] = extra[k];
    return p;
  }

  /* ------------------------- UTILITIES ----------------------- */
  function uuid() {
    if (w.crypto && w.crypto.randomUUID) return w.crypto.randomUUID();
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
      var r = (Math.random() * 16) | 0; return (c === 'x' ? r : (r & 0x3) | 0x8).toString(16);
    });
  }
  function getCookie(n) {
    var m = d.cookie.match(new RegExp('(?:^|; )' + n.replace(/[.$?*|{}()[\]\\/+^]/g, '\\$&') + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : null;
  }
  function setCookie(n, v, days) {
    var e = new Date(Date.now() + days * 864e5).toUTCString();
    d.cookie = n + '=' + encodeURIComponent(v) + '; expires=' + e + '; path=/; SameSite=Lax; Secure';
  }
  function once(key) {                       // per page-view guard
    once._s = once._s || {};
    if (once._s[key]) return false;
    once._s[key] = 1; return true;
  }
  // Arabic-Indic / Persian digits -> ASCII
  function asciiDigits(s) {
    return String(s || '').replace(/[\u0660-\u0669]/g, function (c) { return c.charCodeAt(0) - 0x0660; })
                          .replace(/[\u06F0-\u06F9]/g, function (c) { return c.charCodeAt(0) - 0x06F0; });
  }
  // Saudi-first E.164 normalisation
  function normPhone(raw) {
    var s = asciiDigits(raw).replace(/[^\d+]/g, '');
    if (!s) return null;
    if (s.indexOf('00') === 0) s = '+' + s.slice(2);
    if (s.charAt(0) === '+') return s.length >= 10 ? s : null;
    if (/^9665\d{8}$/.test(s)) return '+' + s;
    if (/^05\d{8}$/.test(s)) return '+966' + s.slice(1);
    if (/^5\d{8}$/.test(s)) return '+966' + s;
    return s.length >= 9 ? '+' + s : null;
  }
  function normEmail(raw) {
    var s = String(raw || '').trim().toLowerCase();
    return /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(s) ? s : null;
  }
  function sha256(str) {
    if (!str || !w.crypto || !w.crypto.subtle || !w.TextEncoder) return Promise.resolve(null);
    return w.crypto.subtle.digest('SHA-256', new TextEncoder().encode(str)).then(function (buf) {
      return Array.prototype.map.call(new Uint8Array(buf), function (b) { return ('0' + b.toString(16)).slice(-2); }).join('');
    }).catch(function () { return null; });
  }

  /* --------------------- TikTok base pixel ------------------- */
  /* Standard TikTok loader. If Events Manager gives a newer snippet, keep this
     file and only replace the block between the two markers. */
  /* >>> TIKTOK BASE CODE >>> */
  !function (w, d, t) {
    w.TiktokAnalyticsObject = t; var ttq = w[t] = w[t] || [];
    ttq.methods = ['page', 'track', 'identify', 'instances', 'debug', 'on', 'off', 'once', 'ready', 'alias', 'group', 'enableCookie', 'disableCookie', 'holdConsent', 'revokeConsent', 'grantConsent'];
    ttq.setAndDefer = function (t, e) { t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))); }; };
    for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
    ttq.instance = function (t) { for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]); return e; };
    ttq.load = function (e, n) {
      var r = 'https://analytics.tiktok.com/i18n/pixel/events.js', o = n && n.partner;
      ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = r, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {};
      n = d.createElement('script'); n.type = 'text/javascript', n.async = !0, n.src = r + '?sdkid=' + e + '&lib=' + t;
      e = d.getElementsByTagName('script')[0]; e.parentNode.insertBefore(n, e);
    };
  }(w, d, 'ttq');
  /* <<< TIKTOK BASE CODE <<< */

  var ttq = w.ttq;
  ttq.load(CFG.pixelId);
  ttq.page();
  log('PageView', { campaign_type: CAMPAIGN_TYPE, variant: LANDING_VARIANT });

  function track(event, props, eventId) {
    var opts = { event_id: eventId || uuid() };
    ttq.track(event, props, opts);
    log(event, props, opts);
    if (w.dataLayer && w.dataLayer.push) {           // mirror for GA/GTM visibility & QA
      try { w.dataLayer.push({ event: 'tt_' + event, tt_event_id: opts.event_id, campaign_type: CAMPAIGN_TYPE }); } catch (e) {}
    }
    return opts.event_id;
  }

  /* --------------------- ttclid persistence ------------------- */
  var ttclid = (qs.match(/[?&]ttclid=([^&#]+)/) || [])[1];
  if (ttclid) { try { setCookie('f360_ttclid', decodeURIComponent(ttclid), CFG.ttclidCookieDays); } catch (e) {} }

  /* --------------------- ViewContent (engagement) ------------- */
  var vcTimer = null, visibleMs = 0, lastTick = Date.now();
  function fireViewContent(reason) {
    if (!once('vc')) return;
    w.removeEventListener('scroll', onScroll);
    if (vcTimer) clearInterval(vcTimer);
    track('ViewContent', baseProps({ engagement_trigger: reason }));
  }
  function onScroll() {
    var h = d.documentElement, b = d.body;
    var max = Math.max(h.scrollHeight, b ? b.scrollHeight : 0) - w.innerHeight;
    if (max <= 0) return;
    if (((w.pageYOffset || h.scrollTop) / max) * 100 >= CFG.engagement.scrollPct) fireViewContent('scroll_' + CFG.engagement.scrollPct);
  }
  w.addEventListener('scroll', onScroll, { passive: true });
  vcTimer = setInterval(function () {               // counts only foreground time
    var now = Date.now();
    if (d.visibilityState === 'visible') visibleMs += now - lastTick;
    lastTick = now;
    if (visibleMs >= CFG.engagement.seconds * 1000) fireViewContent('time_' + CFG.engagement.seconds + 's');
  }, 1000);

  /* ------------------------- Contact ------------------------- */
  function ctaChannel(el) {
    var href = (el.getAttribute('href') || '').toLowerCase();
    var tag = el.getAttribute('data-tt-cta');
    if (tag) return tag;
    if (/wa\.me|whatsapp/.test(href) || el.closest('.fq-wa, .btn-whatsapp')) return 'whatsapp';
    if (href.indexOf('tel:') === 0) return 'call';
    if (href === '#contact' || /#(demo|contact|upgrade-form)$/.test(href)) return 'demo';
    return null;
  }
  d.addEventListener('click', function (e) {
    var el = e.target && e.target.closest ? e.target.closest('a, button, [data-tt-cta]') : null;
    if (!el) return;
    var ch = ctaChannel(el);
    if (!ch || !once('contact_' + ch)) return;
    track('Contact', baseProps({ contact_channel: ch, cta_text: (el.textContent || '').trim().slice(0, 60) }));
  }, true);

  /* --------------- LeadFormOpen / LeadFormStart (micro) ------- */
  function bindForm(form) {
    if (form.__f360tt) return; form.__f360tt = true;

    if ('IntersectionObserver' in w) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting && once('lfo')) { track('LeadFormOpen', baseProps({})); io.disconnect(); }
        });
      }, { threshold: 0.5 });
      io.observe(form);
    }

    form.addEventListener('input', function (e) {
      var t = e.target;
      if (!t || t.name === 'website' || t.type === 'hidden') return;          // ignore honeypot
      var v = (t.value || '').trim();
      var valid = t.type === 'email' ? !!normEmail(v) : t.type === 'tel' ? !!normPhone(v) : v.length >= 2;
      if (valid && once('lfs')) track('LeadFormStart', baseProps({ first_field: t.name || t.id || t.type }));
    }, true);

    // Submit (capture phase - runs before site.js validation; no event fires here)
    form.addEventListener('submit', function () {
      ensureDataLayerHook();
      if (form.querySelector('[name="website"]') && form.querySelector('[name="website"]').value) return; // bot
      pending = { eventId: uuid(), at: Date.now(), form: form };
      var em = normEmail((form.querySelector('[type="email"], [name="email"]') || {}).value);
      var ph = normPhone((form.querySelector('[type="tel"], [name="phone"]') || {}).value);
      pending.identify = Promise.all([sha256(em), sha256(ph)]).then(function (h) {
        var id = {};
        if (h[0]) id.email = h[0];
        if (h[1]) id.phone_number = h[1];
        if (id.email || id.phone_number) { ttq.identify(id); log('identify (hashed)', id); }
      });
      log('submit captured -> pending event_id', pending.eventId);
    }, true);
  }
  function scanForms() { var f = d.querySelectorAll(CFG.formSelector); for (var i = 0; i < f.length; i++) bindForm(f[i]); }

  /* ---------------- Lead (server-confirmed success) ------------ */
  var pending = null;
  var firedLeadIds = {};

  function fireLead(src) {
    if (!pending || firedLeadIds[pending.eventId]) return;
    if (Date.now() - pending.at > 120000) { pending = null; return; }        // stale (2 min)
    var p = pending; pending = null;
    firedLeadIds[p.eventId] = 1;
    (p.identify || Promise.resolve()).then(function () {
      track('Lead', baseProps({ lead_source: 'landing_form', confirmed_by: src }), p.eventId);
    });
  }

  // 1) Inject event_id + click ids into the same-origin JSON lead POST
  if (w.fetch && !w.fetch.__f360) {
    var _fetch = w.fetch;
    var wrapped = function (input, init) {
      try {
        var url = typeof input === 'string' ? input : (input && input.url) || '';
        var sameOrigin = new URL(url, w.location.href).origin === w.location.origin;
        if (pending && sameOrigin && init && /post/i.test(init.method || '') && typeof init.body === 'string' && init.body.charAt(0) === '{') {
          var body = JSON.parse(init.body);
          body.tt = {
            event_id: pending.eventId,
            ttp: getCookie('_ttp'),
            ttclid: getCookie('f360_ttclid'),
            page_url: w.location.href.split('#')[0],
            referrer: d.referrer || null,
            campaign_type: CAMPAIGN_TYPE,
            landing_variant: LANDING_VARIANT,
            test_event_code: TEST_CODE
          };
          init = Object.assign({}, init, { body: JSON.stringify(body) });
          log('event_id injected into lead POST', body.tt.event_id);
        }
      } catch (e) { /* never break the form */ }
      return _fetch.call(this, input, init);
    };
    wrapped.__f360 = true;
    w.fetch = wrapped;
  }

  // 2) Fire Lead only on the site's own success signal
  function ensureDataLayerHook() {
    w.dataLayer = w.dataLayer || [];
    var dl = w.dataLayer;
    if (dl.push.__f360) return;
    var orig = dl.push;
    var hooked = function () {
      for (var i = 0; i < arguments.length; i++) {
        var a = arguments[i];
        if (a && a.event === 'generate_lead') fireLead('generate_lead');
      }
      return orig.apply(dl, arguments);
    };
    hooked.__f360 = true;
    dl.push = hooked;
  }

  /* ------------------------- BOOT ---------------------------- */
  function boot() {
    ensureDataLayerHook();
    scanForms();
    if ('MutationObserver' in w) new MutationObserver(scanForms).observe(d.body, { childList: true, subtree: true });
    log('ready', { pixel: CFG.pixelId, campaign_type: CAMPAIGN_TYPE, test_code: TEST_CODE });
  }
  if (d.readyState === 'loading') d.addEventListener('DOMContentLoaded', boot); else boot();
  w.addEventListener('load', ensureDataLayerHook);      // re-chain after gtag.js loads

  // Public API for QA / future CTAs
  w.F360TT = { track: function (e, p) { return track(e, baseProps(p || {})); }, campaignType: CAMPAIGN_TYPE, version: '1.0.0' };
})(window, document);