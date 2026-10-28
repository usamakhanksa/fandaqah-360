/*!
 * f360-formopen.js — Fandaqah 360 landing pages
 * Adds two retargeting signals on top of fq-meta-events.js (pixel 992779690541049):
 *
 *   F360_FormOpen_NoFill  custom  — lead form is ≥50% visible (once per page view,
 *                                    skipped if this browser already submitted a lead)
 *   F360_FormAbandon      custom  — visitor typed in the form, then left without a Lead
 *
 * Every event is sent twice with the SAME event_id:
 *   1) browser pixel  → fbq('trackSingleCustom', …, {eventID})
 *   2) server (CAPI)  → POST api/meta-event.php  {event_name, event_id, …}
 * Meta keeps one copy (deduplication on event_name + event_id).
 *
 * Load AFTER fq-meta-events.js on both landing pages:
 *   <script src="/landing/fandaqah-360/assets/js/f360-formopen.js?v=1" defer></script>
 * Debug: add ?f360debug=1 to the URL → console logs.
 */
(function () {
  'use strict';

  var PIXEL_ID   = '992779690541049';
  var ENDPOINT   = '/landing/fandaqah-360/api/meta-event.php';
  var LEAD_FLAG  = 'f360_lead_done';          // localStorage: browser already converted
  var DEBUG      = /[?&]f360debug=1/.test(location.search);
  var d = document, w = window;

  function log() { if (DEBUG && w.console) console.log.apply(console, ['[F360]'].concat([].slice.call(arguments))); }
  function uuid() {
    return (w.crypto && crypto.randomUUID) ? crypto.randomUUID()
      : 'f360-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);
  }
  function getLS(k) { try { return w.localStorage.getItem(k); } catch (e) { return null; } }
  function setLS(k, v) { try { w.localStorage.setItem(k, v); } catch (e) {} }

  function ctx() {
    var c = (w.fqMeta && typeof fqMeta.capiContext === 'function') ? fqMeta.capiContext() : {};
    return {
      fbp: c.fbp || null,
      fbc: c.fbc || null,
      event_source_url: c.event_source_url || location.href,
      campaign_type: c.campaign_type || (w.fqMeta && fqMeta.campaignType) || null,
      landing_page: c.landing_page || location.pathname
    };
  }

  // fbq may load late — retry for up to 15 s (same policy as fq-meta-events.js)
  function pixel(name, params, eventID, tries) {
    tries = tries || 0;
    if (typeof w.fbq === 'function') {
      w.fbq('trackSingleCustom', PIXEL_ID, name, params, { eventID: eventID });
      log('pixel', name, eventID, params);
    } else if (tries < 60) {
      setTimeout(function () { pixel(name, params, eventID, tries + 1); }, 250);
    }
  }

  function server(name, params, eventID) {
    var c = ctx();
    var body = JSON.stringify({
      event_name: name,
      event_id: eventID,
      event_time: Math.floor(Date.now() / 1000),
      event_source_url: c.event_source_url,
      fbp: c.fbp, fbc: c.fbc,
      custom_data: params
    });
    try {
      if (navigator.sendBeacon && navigator.sendBeacon(ENDPOINT, new Blob([body], { type: 'application/json' }))) {
        log('capi beacon', name, eventID); return;
      }
    } catch (e) {}
    try { fetch(ENDPOINT, { method: 'POST', body: body, keepalive: true, headers: { 'Content-Type': 'application/json' } }); } catch (e) {}
  }

  function send(name, extra) {
    var c = ctx();
    var params = Object.assign({
      campaign_type: c.campaign_type,
      landing_page: c.landing_page,
      form_name: 'f360_lead_form'
    }, extra || {});
    var id = uuid();
    pixel(name, params, id);
    server(name, params, id);
  }

  function findForm() {
    return d.getElementById('lead-form') ||
      (d.querySelector('form input[name="phone"], form input[name="email"]') || {}).form || null;
  }

  var leadDone = getLS(LEAD_FLAG) === '1';
  var started = false, openedSent = false, abandonSent = false;

  // A confirmed lead (site.js / v2 inline dispatch this only after the CRM accepts)
  d.addEventListener('fq:lead', function () {
    leadDone = true;
    setLS(LEAD_FLAG, '1');
    log('lead confirmed — abandon tracking disarmed');
  });

  function init() {
    var form = findForm();
    if (!form) { log('no lead form on page'); return; }

    // 1) F360_FormOpen_NoFill — ≥50% of the form visible
    if ('IntersectionObserver' in w) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (openedSent || !en.isIntersecting || en.intersectionRatio < 0.5) return;
          openedSent = true; io.disconnect();
          if (leadDone) { log('FormOpen skipped — browser already a lead'); return; }
          send('F360_FormOpen_NoFill', { trigger: 'form_50pct_visible' });
        });
      }, { threshold: [0.5] });
      io.observe(form);
    }

    // 2) Mark "started" on the first real input (honeypot `website` ignored)
    form.addEventListener('input', function (e) {
      var t = e.target;
      if (!t || !t.name || t.name === 'website' || t.type === 'hidden') return;
      if ((t.value || '').trim().length >= 2) started = true;
    }, true);

    // 3) F360_FormAbandon — started, no Lead, page is being left/hidden
    function maybeAbandon() {
      if (abandonSent || !started || leadDone) return;
      abandonSent = true;
      send('F360_FormAbandon', { trigger: 'left_after_typing' });
    }
    w.addEventListener('pagehide', maybeAbandon);
    d.addEventListener('visibilitychange', function () { if (d.visibilityState === 'hidden') maybeAbandon(); });
  }

  if (d.readyState === 'loading') d.addEventListener('DOMContentLoaded', init); else init();
})();