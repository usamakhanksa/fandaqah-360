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
 * On this site (second meta/ pack, 28 Oct 2026): linked from the <head> of every PHP copy by tools/build-php.py, right after
 * fq-meta-events.js, no page skipped. It runs only where the script tag carries data-meta-events (the two F360 pages, as for
 * fq-meta-events.js); everywhere else it does nothing. The two server addresses are found from this script's own address
 * (assets/js/ → api/), so the same file works on fandaqah.com, a staging host and localhost. The notice must actually be
 * visible for "notice" consent to count. Arabic-Indic digits are read as digits. A form sent in the last 20 s is not counted
 * as abandoned (the visitor did send it; the Lead follows from the CRM's answer).
 * Debug: ?f360debug=1
 */
(function () {
  'use strict';

  var d = document, w = window;
  if (w.F360FormOpen) return;                  // loaded twice → do nothing the second time
  var me = d.currentScript;
  w.F360FormOpen = { version: 2, events: !!(me && me.hasAttribute('data-meta-events')) };
  if (!w.F360FormOpen.events) return;

  var PIXEL_ID     = '992779690541049';
  var META_EP      = new URL('../../api/meta-event.php', me.src).href;
  var PARTIAL_EP   = new URL('../../api/f360-partial-lead.php', me.src).href;
  var LEAD_FLAG    = 'f360_lead_done';
  var DEBUG        = /[?&]f360debug=1/.test(location.search);

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
      event_source_url: c.event_source_url || location.href.split('#')[0],
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

  /** Saudi mobile → 9665XXXXXXXX, or null. Accepts 05XXXXXXXX, 5XXXXXXXX, +9665…, 009665…, Arabic-Indic digits */
  function saMobile(v) {
    var x = String(v || '').replace(/[٠-٩]/g, function (c) { return c.charCodeAt(0) - 0x660; })
                           .replace(/[۰-۹]/g, function (c) { return c.charCodeAt(0) - 0x6F0; })
                           .replace(/\D+/g, '');
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
  var started = false, openedSent = false, abandonSent = false, phoneSent = false, partialSent = false, sentAt = 0;
  var visitId = uuid();   // ties partial-lead rows from this page view together
  w.F360FormOpen.visitId = visitId;   // and to this session's Clarity recording (tag f360_visit, assets/js/f360-insights.js)

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
    function shown(el) { return !!el && !el.hidden && el.getClientRects().length > 0 && getComputedStyle(el).visibility !== 'hidden'; }
    function consent() {
      var box = form.querySelector('[name="contact_ok"], [data-f360-consent]');
      if (box) return box.checked ? 'checkbox' : null;
      return form.getAttribute('data-f360-partial-notice') === '1' && shown(form.querySelector('.f360-notice')) ? 'notice' : null;
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
    form.addEventListener('submit', function () { sentAt = Date.now(); }, true);

    // ---- 3. F360_Phone_Captured + partial lead
    // held 2 s: a click on Send blurs the phone field first, and that visitor is a lead, not a call-back; leaving the page
    // in the meantime sends it at once
    var pending = null, holdTimer = 0;
    function flushPartial() {
      clearTimeout(holdTimer);
      var p = pending; pending = null;
      if (!p || partialSent || leadDone || Date.now() - sentAt < 20000) return;
      partialSent = true;
      post(PARTIAL_EP, p);
      log('partial lead stored', p.consent);
    }
    w.addEventListener('pagehide', flushPartial);
    d.addEventListener('visibilitychange', function () { if (d.visibilityState === 'hidden') flushPartial(); });
    function partial(phone, how) {
      if (partialSent || leadDone) return;
      var c = ctx(), src = (w.F360 && typeof F360.source === 'function') ? F360.source() : {};
      clearTimeout(holdTimer);
      holdTimer = setTimeout(flushPartial, 2000);
      pending = ({
        visit_id: visitId,
        phone: phone,                       // full number, CRM only — never sent to Meta
        name: val('name'), company: val('company'), email: val('email'),
        campaign_type: c.campaign_type, landing_page: c.landing_page,
        ad_platform: src.ad_platform || '', utm_campaign: src.utm_campaign || '',   // which ad paid for this visit
        consent: how, page_url: location.href.split('#')[0], captured_at: new Date().toISOString(),
        website: val('website')             // honeypot — server drops the row if filled
      });
    }
    function onPhone() {
      if (leadDone || !tel) return;
      var phone = saMobile(tel.value);
      if (!phone) return;
      if (!phoneSent) {
        phoneSent = true;
        send('F360_Phone_Captured', { trigger: 'tel_blur_valid', phone_valid: true });
        // the same moment for Google, TikTok, Snap and LinkedIn (their own scripts listen); anonymous, no number in it
        try { d.dispatchEvent(new CustomEvent('fq:phone', { detail: { campaign_type: ctx().campaign_type } })); } catch (e) {}
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
      if (abandonSent || !started || leadDone || Date.now() - sentAt < 20000) return;
      abandonSent = true;
      send('F360_FormAbandon', { trigger: 'left_after_typing', phone_given: phoneSent });
      // the same moment for GA4, GTM and Clarity (f360-measurement.js listens); no typed value in it
      try { d.dispatchEvent(new CustomEvent('fq:abandon', { detail: { phone_given: phoneSent } })); } catch (e) {}
    }
    w.addEventListener('pagehide', maybeAbandon);
    d.addEventListener('visibilitychange', function () { if (d.visibilityState === 'hidden') maybeAbandon(); });
  }

  if (d.readyState === 'loading') d.addEventListener('DOMContentLoaded', init); else init();
})();
