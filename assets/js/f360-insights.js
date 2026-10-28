/* Fandaqah 360 · page insights: each F360 landing page's own Google Tag Manager container, GA4 property and Microsoft Clarity
   project (owner, 28 Oct 2026). Linked by tools/build-php.py into the <head> of the two F360 pages only, with the page's ids on
   the script tag:
     existing-customers/        GTM-NNWVV5T3   GA4 G-02TY6G2M89   Clarity z4rrag411j
     fandaqah-360-landing-v2/   GTM-WMQDXSDB   GA4 G-C4BHT10SGY   Clarity z4rqpud029
   (the ids live in PAGE_TAGS in tools/build-php.py; this file holds none).

   Order: a plain tag (not async/defer) right after assets/js/fq-ads.js, so the Consent Mode v2 defaults are queued before the
   container starts, as Google requires, and the page's GA4 property rides the one gtag.js fq-ads.js already loads (one Google
   tag per page; the site-wide GA4 G-70NH8KGTDQ keeps its own data). The GTM <noscript> iframe follows <body>.

   What each tool gets:
     GA4 (the page's property)  page_view, plus every F360 funnel event f360-measurement.js sends (CTA, WhatsApp, call, email,
                                form open/start, phone captured, form abandon, generate_lead), with campaign_type
     GTM                        the dataLayer funnel below, for tags added in the container later. The containers are empty
                                today: do NOT add GA4 event tags there for these events, GA4 already receives them.
     Clarity                    custom tags per session: campaign_type, ad_platform, utm_source, utm_campaign, page_lang,
                                f360_visit (the call-back list's session id), submission_id (the CRM lead's id); custom
                                events = the funnel; a session with WhatsApp, a call, a form start, a typed mobile, an
                                abandon or a lead is always kept (upgrade). The lead forms carry data-clarity-mask, so no
                                typed name, number or email is ever recorded.

   dataLayer funnel (one name per step, the same on both pages; f360-measurement.js and f360-formopen.js push them):
     f360_context · f360_page_view · f360_engaged_visit · f360_cta_click · f360_whatsapp_click · f360_phone_click ·
     f360_email_click · f360_form_open · f360_form_start · f360_phone_captured · f360_form_abandon · f360_lead_success
   A copy of any snippet pasted into the page by hand is detected and not loaded twice. Debug: ?f360_debug=1 */
(function () {
  'use strict';
  var w = window, d = document, me = d.currentScript;
  if (w.F360Page) return;
  function attr(n, re) { var v = (me && me.getAttribute(n)) || ''; return re.test(v) ? v : ''; }
  var GTM = attr('data-gtm', /^GTM-[A-Z0-9]{4,12}$/), GA4 = attr('data-ga4', /^G-[A-Z0-9]{4,14}$/),
      CLARITY = attr('data-clarity', /^[a-z0-9]{6,14}$/);
  var DEBUG = /[?&]f360_debug=1\b/.test(location.search);
  var path = location.pathname;
  var CAMPAIGN_TYPE = /\/existing-customers\/?/.test(path) ? 'existing_customer' :
                      /\/fandaqah-360-landing-v2\/?/.test(path) ? 'new_customer' : 'unknown';
  function log() { if (DEBUG && w.console) console.log.apply(console, ['[F360 insights]'].concat([].slice.call(arguments))); }

  w.dataLayer = w.dataLayer || [];
  var page = w.F360Page = { gtm: GTM, ga4: GA4, clarity: CLARITY, campaignType: CAMPAIGN_TYPE, pasted: {} };

  /* ---------- GA4: the page's own property, on the gtag fq-ads.js set up (consent defaults first) ---------- */
  if (GA4) {
    page.pasted.ga4 = !!d.querySelector('script[src*="gtag/js?id=' + GA4 + '"]')
      || w.dataLayer.some(function (a) { return a && a[0] === 'config' && a[1] === GA4; });
    if (!page.pasted.ga4) {
      if (typeof w.gtag !== 'function') {             // fq-ads.js missing: a plain Google tag of its own
        w.gtag = function () { w.dataLayer.push(arguments); };
        var g = d.createElement('script'); g.async = true; g.src = 'https://www.googletagmanager.com/gtag/js?id=' + GA4;
        (d.head || d.documentElement).appendChild(g);
        w.gtag('js', new Date());
      }
      w.gtag('config', GA4, { campaign_type: CAMPAIGN_TYPE });
    }
  }

  /* ---------- Google Tag Manager: Google's container snippet, unchanged ---------- */
  if (GTM) {
    page.pasted.gtm = !!(w.google_tag_manager && w.google_tag_manager[GTM]) || !!d.querySelector('script[src*="gtm.js?id=' + GTM + '"]');
    if (!page.pasted.gtm) {
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(w,d,'script','dataLayer',GTM);
    }
  }

  /* ---------- Microsoft Clarity: Microsoft's snippet, unchanged ---------- */
  if (CLARITY) {
    page.pasted.clarity = typeof w.clarity === 'function';
    if (!page.pasted.clarity) {
      (function(c,l,a,r,i,t,y){
          c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
          t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
          y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
      })(w, d, "clarity", "script", CLARITY);
    }
  }
  if (page.pasted.ga4 || page.pasted.gtm || page.pasted.clarity)
    console.warn('[F360] a GTM / GA4 / Clarity snippet is also pasted into this page by hand; it is used once, not twice. Remove the pasted copy: assets/js/f360-insights.js already carries it.');
  log('ready', page);
  if (!CLARITY) return;

  function clarity() { if (typeof w.clarity === 'function') try { w.clarity.apply(w, arguments); } catch (e) {} }
  function tag(k, v) { v = String(v || '').slice(0, 100); if (v) { clarity('set', k, v); log('clarity tag', k, v); } }

  /* ---------- Clarity session tags: which ad paid for this session, and the ids that join it to the CRM ---------- */
  function tagSession() {
    var src = (w.F360 && typeof w.F360.source === 'function') ? w.F360.source() : {};
    tag('campaign_type', CAMPAIGN_TYPE);
    tag('ad_platform', src.ad_platform || 'none');
    tag('utm_source', src.utm_source);
    tag('utm_campaign', src.utm_campaign);
    tag('page_lang', (d.documentElement.lang || 'ar').slice(0, 2));
    if (w.F360FormOpen && w.F360FormOpen.visitId) tag('f360_visit', w.F360FormOpen.visitId);   // = the call-back list's session id
  }
  if (d.readyState === 'loading') d.addEventListener('DOMContentLoaded', tagSession); else tagSession();

  /* ---------- Clarity events: the funnel, from the dataLayer; 1 = always keep this session's recording ---------- */
  var KEEP = { f360_whatsapp_click: 1, f360_phone_click: 1, f360_email_click: 1, f360_form_start: 1, f360_phone_captured: 1,
               f360_form_abandon: 1, f360_lead_success: 1, f360_cta_click: 0, f360_form_open: 0, f360_engaged_visit: 0 };
  var dl = w.dataLayer, seen = 0, done = {};
  function scan() {
    for (; seen < dl.length; seen++) {
      var o = dl[seen];
      if (!o || typeof o !== 'object' || 'length' in o || !KEEP.hasOwnProperty(o.event) || done[o.event]) continue;
      done[o.event] = 1;
      clarity('event', o.event);
      if (KEEP[o.event]) clarity('upgrade', o.event);
      if (o.event === 'f360_lead_success' && o.lead_id) tag('submission_id', o.lead_id);   // = the CRM lead's submission_id
      log('clarity event', o.event);
    }
  }
  // the push hook catches an event sent as the page closes (abandon); the scan catches anything pushed past the hook
  var push = dl.push;
  dl.push = function () { var r = push.apply(dl, arguments); scan(); return r; };
  setInterval(scan, 500);
})();
