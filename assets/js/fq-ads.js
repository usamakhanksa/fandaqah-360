/* Fandaqah 360 · Google Ads tag + Meta Pixel (linked from the <head> of every PHP copy by tools/build-php.py).

   These two tags were pasted by hand into the live existing-customers and fandaqah-360-landing-v2 pages on fandaqah.com and were
   not in the repo, so any upload of the built pages would have removed them. They live here now, with the same IDs:
     Google Ads  AW-18383069867, conversion label AW-18383069867/8w92CO-ejqodEKvF3b1E
     Meta Pixel  992779690541049

   Every page: the Google tag (config, for remarketing) and the Meta PageView.
   Only where the script tag carries data-ads-events (the two Fandaqah 360 landing pages):
     Google Ads conversion  once per CONFIRMED lead, transaction_id = submission_id
     Meta Lead              once per CONFIRMED lead, eventID = submission_id (ready for Meta's Conversions API to pair with)
   The live pages fired the Google Ads conversion on page load, so every visit counted as a conversion. It now fires on the page's
   own confirmation (the fq:lead event, sent by site.js and the v2 script only after the CRM accepted the request).
   QA: ?fqads=debug logs each call in the console. */
(() => {
  'use strict';
  if (window.FQAds) return;
  const GOOGLE_ADS = 'AW-18383069867', GOOGLE_LEAD = 'AW-18383069867/8w92CO-ejqodEKvF3b1E', META_PIXEL = '992779690541049';
  const me = document.currentScript, EVENTS = !!(me && me.hasAttribute('data-ads-events'));
  const DEBUG = /[?&]fqads=debug\b/.test(location.search);
  const log = (...a) => { if (DEBUG) console.info('[fq-ads]', ...a); };
  const load = src => { const s = document.createElement('script'); s.async = true; s.src = src; (document.head || document.documentElement).appendChild(s); };

  /* ---------- Google tag (gtag.js) ---------- */
  window.dataLayer = window.dataLayer || [];
  window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
  load('https://www.googletagmanager.com/gtag/js?id=' + GOOGLE_ADS);
  window.gtag('js', new Date());
  window.gtag('config', GOOGLE_ADS);

  /* ---------- Meta Pixel (Meta's base code) ---------- */
  !function (f, b, e, v, n, t, s) {
    if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments); };
    if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
    n.queue = []; t = b.createElement(e); t.async = !0;
    t.src = v; s = b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t, s);
  }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
  window.fbq('init', META_PIXEL);
  window.fbq('track', 'PageView');
  log('ready', { google: GOOGLE_ADS, meta: META_PIXEL, events: EVENTS });

  window.FQAds = { google: GOOGLE_ADS, meta: META_PIXEL, events: EVENTS };
  if (!EVENTS) return;

  /* ---------- the confirmed lead ---------- */
  const counted = new Set();
  document.addEventListener('fq:lead', e => {
    const id = e.detail && e.detail.id;
    if (!id || counted.has(id)) return;   // once per request
    counted.add(id);
    window.gtag('event', 'conversion', { send_to: GOOGLE_LEAD, transaction_id: id });
    window.fbq('track', 'Lead', { content_name: 'Fandaqah 360', currency: 'SAR' }, { eventID: id });
    log('lead', id);
  });
})();
