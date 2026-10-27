/* Fandaqah 360 · Google tag + Meta Pixel (linked from the <head> of every PHP copy by tools/build-php.py, ONE time per page).

   These two tags were pasted by hand into the live existing-customers and fandaqah-360-landing-v2 pages on fandaqah.com and were
   not in the repo, so any upload of the built pages would have removed them. They live here now, once:
     Google Ads  AW-18383069867 (Enhanced Conversions on)      GA4  G-70NH8KGTDQ      Meta Pixel  992779690541049

   Google (googleads/ pack, "F360 Google tag block"): a guarded gtag, Consent Mode v2 defaults BEFORE config (EEA/UK/CH denied
   until a banner grants it; Saudi Arabia and the rest of the world granted), ads_data_redaction, the Ads and GA4 configs.
   The guard: the lead conversion label (…/8w92CO-ejqodEKvF3b1E) can fire ONLY through assets/js/f360-measurement.js after a
   confirmed CRM success, never on page load (the live pages counted every visit as a conversion) and never from a stray line.
   Meta: the base pixel and its PageView; its events are assets/js/fq-meta-events.js.
   The script tag is plain (not async/defer), at the end of <head>: consent and config are queued before any other tag, and a copy
   pasted into the <head> by hand is seen and not repeated. QA: ?fqads=debug logs each call in the console. */
(() => {
  'use strict';
  if (window.FQAds) return;
  const GOOGLE_ADS = 'AW-18383069867', GA4 = 'G-70NH8KGTDQ', LEAD_LABEL = '8w92CO-ejqodEKvF3b1E', META_PIXEL = '992779690541049';
  const DEBUG = /[?&]fqads=debug\b/.test(location.search);
  const log = (...a) => { if (DEBUG) console.info('[fq-ads]', ...a); };
  const load = src => { const s = document.createElement('script'); s.async = true; s.src = src; (document.head || document.documentElement).appendChild(s); };

  /* ---------- one time only: if the snippet was also pasted into the page by hand, that copy already did this part ---------- */
  window.dataLayer = window.dataLayer || [];
  const pastedGoogle = !!document.querySelector(`script[src*="googletagmanager.com/gtag/js?id=${GOOGLE_ADS}"]`)
    || window.dataLayer.some(a => a && a[0] === 'config' && a[1] === GOOGLE_ADS);
  const pastedMeta = typeof window.fbq === 'function';

  /* ---------- Google tag (gtag.js) ---------- */
  if (!pastedGoogle) {
    // guarded gtag: the lead label passes only while f360-measurement.js holds the gate open, after a confirmed success
    window.gtag = function () {
      const a = arguments;
      if (a[0] === 'event' && a[1] === 'conversion' && a[2] && String(a[2].send_to || '').indexOf(LEAD_LABEL) > -1 && !window.__F360_LEAD_GATE) {
        console.warn('[F360] Blocked lead conversion outside confirmed form success');
        return;
      }
      window.dataLayer.push(a);
    };
    // Consent Mode v2, before config: EEA / UK / CH visitors denied until a consent banner grants it
    window.gtag('consent', 'default', {
      ad_storage: 'denied', ad_user_data: 'denied', ad_personalization: 'denied', analytics_storage: 'denied',
      region: ['AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IS', 'IE', 'IT', 'LV', 'LI', 'LT', 'LU',
               'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'GB', 'CH'],
      wait_for_update: 500
    });
    // Saudi Arabia and the rest of the world: granted (approved policy; PDPL notice on the privacy page)
    window.gtag('consent', 'default', { ad_storage: 'granted', ad_user_data: 'granted', ad_personalization: 'granted', analytics_storage: 'granted' });
    window.gtag('set', 'ads_data_redaction', true);   // applies only where ad_storage is denied
    load('https://www.googletagmanager.com/gtag/js?id=' + GOOGLE_ADS);
    window.gtag('js', new Date());
    window.gtag('config', GOOGLE_ADS, { allow_enhanced_conversions: true });
    window.gtag('config', GA4);
  } else {
    window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
  }

  /* ---------- Meta Pixel (Meta's base code) ---------- */
  if (!pastedMeta) {
    !function (f, b, e, v, n, t, s) {
      if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments); };
      if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
      n.queue = []; t = b.createElement(e); t.async = !0;
      t.src = v; s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s);
    }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    window.fbq('init', META_PIXEL);
    window.fbq('track', 'PageView');
  }
  log('ready', { google: GOOGLE_ADS, ga4: GA4, meta: META_PIXEL, pastedGoogle, pastedMeta });
  if (pastedGoogle || pastedMeta) console.warn('[fq-ads] the Google/Meta snippet is also pasted into this page by hand; it is used once, not twice. Remove the pasted copy: assets/js/fq-ads.js already carries it.');

  window.FQAds = { google: GOOGLE_ADS, ga4: GA4, meta: META_PIXEL, pastedGoogle, pastedMeta };
})();
