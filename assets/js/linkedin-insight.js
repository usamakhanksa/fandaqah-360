/* Fandaqah 360 · LinkedIn Insight Tag (base code) + F360 browser events (f360-linkedin-dev-pack, 27 Oct 2026).
   Partner ID 11189657 (Campaign Manager account 507948919). Linked from the <head> of every PHP copy by tools/build-php.py;
   the .html pages (GitHub Pages, a preview) never load it, so reviewers' visits never reach the ad account.

   On every page: the base code (page views, website audiences, the URL rule 32466617 F360_LP_View) and the li_fat_id capture.
   Only where the script tag carries data-f360-events (the two Fandaqah 360 landing pages):
     32466625  F360_CTA_Click    once per CTA type per page view
     32466609  F360_Lead_Submit  after the CRM confirmed the lead; event_id = submission_id, the same id api/lead.php sends to
                                 the Conversions API (rule 32466657), so LinkedIn can pair the browser and server events. */
(function () {
  var me = document.currentScript, events = !!(me && me.hasAttribute('data-f360-events'));

  /* ---------- base code ---------- */
  window._linkedin_partner_id = '11189657';
  window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
  window._linkedin_data_partner_ids.push(window._linkedin_partner_id);
  (function (l) {
    if (!l) { window.lintrk = function (a, b) { window.lintrk.q.push([a, b]); }; window.lintrk.q = []; }
    var s = document.getElementsByTagName('script')[0];
    var b = document.createElement('script');
    b.type = 'text/javascript'; b.async = true;
    b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
    s.parentNode.insertBefore(b, s);
  })(window.lintrk);

  var F360 = window.F360LI = window.F360LI || {};
  F360.CONV = { CTA: 32466625, LEAD: 32466609 };

  /* ---------- li_fat_id: LinkedIn adds it to ad clicks (enhanced conversion tracking is on). Kept 90 days, first-party. ---------- */
  try {
    var q = new URLSearchParams(location.search), fat = q.get('li_fat_id');
    if (fat) {
      localStorage.setItem('f360_li_fat_id', JSON.stringify({ v: fat.slice(0, 200), at: Date.now() }));
      document.cookie = 'f360_li_fat_id=' + encodeURIComponent(fat.slice(0, 200)) +
        '; Max-Age=' + (90 * 86400) + '; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
    }
  } catch (e) {}
  F360.fatId = function () {
    try {
      var s = JSON.parse(localStorage.getItem('f360_li_fat_id') || 'null');
      if (s && Date.now() - s.at < 90 * 864e5) return s.v;
    } catch (e) {}
    var m = document.cookie.match(/(?:^|; )f360_li_fat_id=([^;]+)/);
    return m ? decodeURIComponent(m[1]) : '';
  };

  /* ---------- F360_Lead_Submit: call ONLY after the CRM confirmed the lead. A no-op on pages without the F360 events, so the
     shared site.js can call it everywhere. ---------- */
  F360.leadSubmitted = function (submissionId) {
    if (!events || !submissionId || F360._sent === submissionId) return;   // once per submission
    F360._sent = submissionId;
    if (window.lintrk) window.lintrk('track', { conversion_id: F360.CONV.LEAD, event_id: submissionId });
  };
  if (!events) return;

  /* ---------- F360_CTA_Click: once per CTA type per page view (no inflation) ---------- */
  var seen = {};
  function ctaType(el) {
    var href = (el.getAttribute('href') || '').toLowerCase();
    if (/wa\.me|whatsapp/.test(href)) return 'whatsapp';
    if (href.indexOf('tel:') === 0) return 'call';
    if (href === '#pricing' || href === '#upgrade') return 'pricing';
    if (el.hasAttribute('data-lead') || href === '#contact' || href === '#lead') return 'demo_contact';
    return null;
  }
  document.addEventListener('click', function (e) {
    var el = e.target.closest && e.target.closest('a,button');
    if (!el) return;
    var t = ctaType(el);
    if (!t || seen[t]) return;
    seen[t] = 1;
    if (window.lintrk) window.lintrk('track', { conversion_id: F360.CONV.CTA });
    if (window.dataLayer) window.dataLayer.push({ event: 'f360_cta_click', cta_type: t });
  }, true);
})();
