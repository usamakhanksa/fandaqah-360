/*!
 * f360-formopen.js v2 — Fandaqah 360 landing pages (micro events + partial lead)
 * Runs on top of fq-meta-events.js (pixel / dataset 992779690541049).
 *
 * MICRO events (never used for bidding — Lead stays the only primary conversion):
 *   F360_FormOpen_NoFill   custom — lead form ≥50% visible (v2: when the #lead dialog opens);
 *                                   once per page view; skipped if this browser already converted
 *   F360_Phone_Captured    custom — tel field left (blur) holding a valid Saudi mobile; once per page view.
 *                                   Sends NO phone data to Meta (no digits, no last4, no hash).
 *   F360_FormAbandon       custom — typed in the form, then left/hid the page without a Lead
 *
 * Dedup: every micro event is sent by the pixel AND by CAPI (api/meta-event.php) with ONE event_id.
 * Lead itself is untouched here: fq-meta-events.js fires it on `fq:lead` with eventID = submission_id,
 * and lead.php sends the CAPI copy with the same id.
 *
 * Partial lead (CRM call list, NOT advertising):
 *   On F360_Phone_Captured, the number is POSTed to api/f360-partial-lead.php ONLY when the visitor
 *   has consented on this form:
 *     - a checked consent checkbox  [name="contact_ok"] or [data-f360-consent]   (existing-customers), or
 *     - the form carries data-f360-partial-notice="1" AND that notice text is visible next to the phone field (v2)
 *   No consent → nothing leaves the browser except the anonymous Meta micro event.
 *
 * Install (both pages), after fq-meta-events.js:
 *   <script src="/landing/fandaqah-360/assets/js/f360-formopen.js?v=2" defer></script>
 * Debug: ?f360debug=1
 */
(function () {
  'use strict';

  var PIXEL_ID     = '992779690541049';
  var META_EP      = '/landing/fandaqah-360/api/meta-event.php';
  var PARTIAL_EP   = '/landing/fandaqah-360/api/f360-partial-lead.php';
  var LEAD_FLAG    = 'f360_lead_done';
  var DEBUG        = /[?&]f360debug=1/.test(location.search);
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
      fbp: c.fbp || null, fbc: c.fbc || null,
      event_source_url: c.event_source_url || location.href,
      campaign_type: c.campaign_type || (w.fqMeta && fqMeta.campaignType) || null,
      landing_page: c.landing_page || location.pathname
    };
  }

  function post(url, obj) {
    var body = JSON.stringify(obj);
    try {
      if (navigator.sendBeacon && navigator.sendBeacon(url, new Blob([body], { type: 'application/json' }))) return;
    } catch (e) {}
    try { fetch(url, { method: 'POST', body: body, keepalive: true, headers: { 'Content-Type': 'application/json' } }); } catch (e) {}
  }

  function pixel(name, params, eventID, tries) {
    tries = tries || 0;
    if (typeof w.fbq === 'function') {
      w.fbq('trackSingleCustom', PIXEL_ID, name, params, { eventID: eventID });
      log('pixel', name, eventID, params);
    } else if (tries < 60) {
      setTimeout(function () { pixel(name, params, eventID, tries + 1); }, 250);
    }
  }

  /** Browser + CAPI with one event_id. Returns the event_id. */
  function send(name, extra) {
    var c = ctx();
    var params = Object.assign({
      campaign_type: c.campaign_type, landing_page: c.landing_page, form_name: 'f360_lead_form'
    }, extra || {});
    var id = uuid();
    pixel(name, params, id);
    post(META_EP, {
      event_name: name, event_id: id, event_time: Math.floor(Date.now() / 1000),
      event_source_url: c.event_source_url, fbp: c.fbp, fbc: c.fbc, custom_data: params
    });
    log('capi', name, id);
    return id;
  }

  /** Saudi mobile → 9665XXXXXXXX, or null. Accepts 05XXXXXXXX, 5XXXXXXXX, +9665…, 009665… */
  function saMobile(v) {
    var x = String(v || '').replace(/\D+/g, '');
    if (x.indexOf('00') === 0) x = x.slice(2);
    if (x.indexOf('966') === 0) x = x.slice(3);
    if (x.indexOf('0') === 0) x = x.slice(1);
    return /^5\d{8}$/.test(x) ? '966' + x : null;
  }

  function findForm() {
    return d.getElementById('lead-form') ||
      (d.querySelector('form input[name="phone"], form input[type="tel"]') || {}).form || null;
  }

  var leadDone = getLS(LEAD_FLAG) === '1';
  var started = false, openedSent = false, abandonSent = false, phoneSent = false, partialSent = false;
  var visitId = uuid();   // ties partial-lead rows from this page view together

  d.addEventListener('fq:lead', function () {
    leadDone = true;
    setLS(LEAD_FLAG, '1');
    log('lead confirmed — abandon/partial tracking disarmed');
  });

  function init() {
    var form = findForm();
    if (!form) { log('no lead form'); return; }
    var tel = form.querySelector('input[name="phone"], input[type="tel"]');

    function val(n) { var e = form.querySelector('[name="' + n + '"]'); return e ? String(e.value || '').trim() : ''; }
    function consent() {
      var box = form.querySelector('[name="contact_ok"], [data-f360-consent]');
      if (box) return box.checked ? 'checkbox' : null;
      return form.getAttribute('data-f360-partial-notice') === '1' ? 'notice' : null;
    }

    // ---- 1. F360_FormOpen_NoFill (≥50% visible; works for the v2 <dialog> too)
    if ('IntersectionObserver' in w) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (openedSent || !en.isIntersecting || en.intersectionRatio < 0.5) return;
          openedSent = true; io.disconnect();
          if (leadDone) { log('FormOpen skipped — already a lead'); return; }
          send('F360_FormOpen_NoFill', { trigger: 'form_50pct_visible' });
        });
      }, { threshold: [0.5] });
      io.observe(form);
    }

    // ---- 2. typing = started (honeypot `website` ignored)
    form.addEventListener('input', function (e) {
      var t = e.target;
      if (!t || !t.name || t.name === 'website' || t.type === 'hidden') return;
      if ((t.value || '').trim().length >= 2) started = true;
    }, true);

    // ---- 3. F360_Phone_Captured + partial lead
    function partial(phone, how) {
      if (partialSent || leadDone) return;
      partialSent = true;
      var c = ctx();
      post(PARTIAL_EP, {
        visit_id: visitId,
        phone: phone,                       // full number, CRM only — never sent to Meta
        name: val('name'), company: val('company'), email: val('email'),
        campaign_type: c.campaign_type, landing_page: c.landing_page,
        consent: how, page_url: location.href, captured_at: new Date().toISOString(),
        website: val('website')             // honeypot — server drops the row if filled
      });
      log('partial lead stored', how);
    }
    function onPhone() {
      if (leadDone || !tel) return;
      var phone = saMobile(tel.value);
      if (!phone) return;
      if (!phoneSent) {
        phoneSent = true;
        send('F360_Phone_Captured', { trigger: 'tel_blur_valid', phone_valid: true });
      }
      var how = consent();
      if (how) partial(phone, how);
      else log('phone captured, no consent yet → nothing sent to CRM');
    }
    if (tel) tel.addEventListener('blur', onPhone);
    // consent ticked AFTER the phone was typed
    var box = form.querySelector('[name="contact_ok"], [data-f360-consent]');
    if (box) box.addEventListener('change', function () { if (box.checked && phoneSent) onPhone(); });

    // ---- 4. F360_FormAbandon
    function maybeAbandon() {
      if (abandonSent || !started || leadDone) return;
      abandonSent = true;
      send('F360_FormAbandon', { trigger: 'left_after_typing', phone_given: phoneSent });
    }
    w.addEventListener('pagehide', maybeAbandon);
    d.addEventListener('visibilitychange', function () { if (d.visibilityState === 'hidden') maybeAbandon(); });
  }

  if (d.readyState === 'loading') d.addEventListener('DOMContentLoaded', init); else init();
})();