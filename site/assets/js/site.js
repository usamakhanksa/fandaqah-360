/* Fandaqah 360 campaign site: language, campaign attribution, navigation and the CRM lead form.
   Each page sets window.FQ_PAGE = { segment, ar, meta } before loading this file. */
(() => {
  if (window.FQ_REDIRECTING) return; // the hub is forwarding this visit to a route; that page records the touch
  const root = document.documentElement;
  root.classList.add('js');
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const PAGE = window.FQ_PAGE || {};
  const reduce = matchMedia('(prefers-reduced-motion: reduce)');
  const store = {
    get: k => { try { return JSON.parse(localStorage.getItem(k)); } catch (e) { return null; } },
    set: (k, v) => { try { localStorage.setItem(k, JSON.stringify(v)); return true; } catch (e) { return false; } }
  };

  /* ---------- Campaign routes (playbook: A existing, B new, C Opera/PMS, D groups) ---------- */
  const ROUTES = { A: 'existing-customers', B: 'new-hotels', C: 'opera-pms', D: 'hotel-groups' };
  const INTEREST = { A: 'existing_upgrade', B: 'new_hotel_demo', C: 'technology_cost_review', D: 'group_discovery' };
  const codeOf = v => { const m = /^\s*([abcd])(?:$|[\s_\-:.])/i.exec(v || ''); return m ? m[1].toUpperCase() : null; };

  /* ---------- Attribution: keep the original source, refresh the latest touch ---------- */
  const UTM = ['utm_campaign', 'utm_source', 'utm_medium', 'utm_content', 'utm_term'];
  const qs = new URLSearchParams(location.search);
  const hasUtm = UTM.some(k => qs.get(k));
  let referrer = document.referrer || '';
  try { referrer = sessionStorage.getItem('fq360_ref') || referrer; sessionStorage.removeItem('fq360_ref'); } catch (e) {}
  const touch = { landing_url: location.href.split('#')[0], referrer, at: new Date().toISOString() };
  UTM.forEach(k => { if (qs.get(k)) touch[k] = qs.get(k).slice(0, 150); });
  ['gclid', 'fbclid', 'li_fat_id'].forEach(k => { if (qs.get(k)) touch[k] = qs.get(k).slice(0, 200); });
  const attr = store.get('fq360_attr') || {};
  if (!attr.first || Date.now() - Date.parse(attr.first.at) > 90 * 864e5) attr.first = touch;
  if (hasUtm || !attr.latest) attr.latest = touch;
  // storage blocked: carry the tags on route links so switching routes keeps the source
  if (!store.set('fq360_attr', attr) && hasUtm) {
    $$('a[data-route]').forEach(a => { const u = new URL(a.href); UTM.forEach(k => { if (qs.get(k)) u.searchParams.set(k, qs.get(k)); }); a.href = u.href; });
  }

  /* ---------- Language: English is in the markup, Arabic lives in the dictionaries ---------- */
  const SHARED_AR = {
    'skip': 'تخطَّ إلى المحتوى', 'aria.home': 'فندقة 360، الصفحة الرئيسية', 'aria.main': 'القائمة الرئيسية', 'aria.lang': 'اللغة',
    'aria.menu': 'فتح القائمة', 'aria.routes': 'اختر مسارك', 'aria.foot': 'روابط التذييل',
    'nav.solutions': 'الحلول', 'nav.how': 'كيف نعمل', 'nav.contact': 'تواصل معنا',
    'seg.A': 'العملاء الحاليون', 'seg.B': 'الفنادق الجديدة', 'seg.C': 'Opera / أنظمة PMS', 'seg.D': 'مجموعات الفنادق',
    'ui.tag': 'واجهة توضيحية', 'ui.brandTag': 'هوية توضيحية', 'ui.scope': 'واجهة توضيحية · النطاق خاضع للمراجعة',
    'f.company': 'الفندق / الشركة', 'f.group': 'المجموعة / الشركة', 'f.city': 'المدينة', 'f.hq': 'مدينة المقر الرئيسي',
    'f.name': 'اسم المسؤول', 'f.role': 'المنصب', 'f.contact': 'البريد الإلكتروني للعمل أو رقم الهاتف',
    'f.contactHint': 'مثال: name@hotel.sa أو 05XXXXXXXX', 'f.account': 'رقم الحساب', 'f.opt': '(اختياري)',
    'f.channel': 'قناة التواصل المفضّلة', 'f.select': 'يرجى الاختيار', 'ch.phone': 'مكالمة هاتفية', 'ch.whatsapp': 'واتساب', 'ch.email': 'البريد الإلكتروني',
    'f.interest': 'الاهتمام المحدد', 'int.A': 'ترقية عميل حالي', 'int.B': 'عرض توضيحي لفندق جديد', 'int.C': 'مراجعة تكاليف التقنية', 'int.D': 'اجتماع استكشافي للمجموعة',
    'f.pms': 'نظام PMS الحالي', 'f.pmsv': 'الإصدار / طريقة التشغيل', 'f.count': 'عدد المنشآت', 'f.own': 'هيكل المنشآت',
    'cnt.1': 'أقل من 10', 'cnt.2': 'من 10 إلى 20', 'cnt.3': 'من 21 إلى 50', 'cnt.4': 'أكثر من 50',
    'own.managed': 'مُدارة', 'own.owned': 'مملوكة', 'own.franchised': 'بنظام الامتياز', 'own.mixed': 'مزيج',
    'f.privacy': 'نستخدم بياناتك للرد على هذا الاستفسار.', 'f.privacyLink': 'إشعار الخصوصية', 'f.consent': 'أرغب أيضاً في تلقي التحديثات التسويقية.',
    'f.picked': 'وحدات للمراجعة:',
    'err.company': 'يرجى إدخال اسم الفندق أو الشركة.', 'err.name': 'يرجى إدخال اسم المسؤول.', 'err.contact': 'أدخل بريداً إلكترونياً صحيحاً أو رقم هاتف مثل 05XXXXXXXX.',
    'done.t': 'شكراً لك. وصل طلبك إلى فريقنا.', 'done.d': 'سيتواصل معك أحد مختصي فندقة عبر قناة التواصل التي اخترتها لترتيب الخطوة التالية.', 'done.ref': 'رقم المرجع:',
    'foot.tag': 'ضيافتك أولويتنا.', 'foot.privacy': 'الخصوصية', 'foot.terms': 'الشروط', 'foot.legal': '© 2026 فندقة. جميع الحقوق محفوظة.'
  };
  const JS_TXT = {
    en: { sending: 'Sending…', fail: 'We couldn’t reach our system just now. Your details are saved on this device and we’ll retry. You can also call <a href="tel:+966920066456">+966 9200 66456</a> or email <a href="mailto:info@fandaqah.com">info@fandaqah.com</a>.', reject: 'Please check your details and try again.', pickedNone: 'Select the modules you want included in your review.', pickedSome: n => `<b>${n}</b> selected for your upgrade review` },
    ar: { sending: 'جارٍ الإرسال…', fail: 'تعذّر الوصول إلى نظامنا حالياً. حفظنا بياناتك على هذا الجهاز وسنعيد المحاولة. يمكنك أيضاً الاتصال على <a href="tel:+966920066456"><bdi dir="ltr">+966 9200 66456</bdi></a> أو مراسلتنا على <a href="mailto:info@fandaqah.com">info@fandaqah.com</a>.', reject: 'يرجى مراجعة بياناتك والمحاولة مرة أخرى.', pickedNone: 'اختر الوحدات التي تريد إضافتها إلى مراجعتك.', pickedSome: n => `تم اختيار <b>${n}</b> لمراجعة الترقية` }
  };
  const AR = Object.assign({}, SHARED_AR, PAGE.ar || {});
  const textNodes = $$('[data-i18n]'), ariaNodes = $$('[data-i18n-aria]'), altNodes = $$('[data-i18n-alt]'), phNodes = $$('[data-i18n-ph]');
  const EN = {}, EN_ARIA = new Map(), EN_ALT = new Map(), EN_PH = new Map();
  textNodes.forEach(n => { if (!(n.dataset.i18n in EN)) EN[n.dataset.i18n] = n.innerHTML; });
  ariaNodes.forEach(n => EN_ARIA.set(n, n.getAttribute('aria-label')));
  altNodes.forEach(n => EN_ALT.set(n, n.alt));
  phNodes.forEach(n => EN_PH.set(n, n.placeholder));
  const META_EN = { title: document.title, desc: ($('meta[name="description"]') || {}).content || '' };
  const T = () => JS_TXT[root.lang === 'ar' ? 'ar' : 'en'];
  const listeners = [];

  function setLang(lang, persist) {
    const ar = lang === 'ar';
    root.lang = ar ? 'ar' : 'en';
    root.dir = ar ? 'rtl' : 'ltr';
    textNodes.forEach(n => { const v = ar ? AR[n.dataset.i18n] : EN[n.dataset.i18n]; if (v != null) n.innerHTML = v; });
    ariaNodes.forEach(n => n.setAttribute('aria-label', ar ? (AR[n.dataset.i18nAria] || EN_ARIA.get(n)) : EN_ARIA.get(n)));
    altNodes.forEach(n => { n.alt = ar ? (AR[n.dataset.i18nAlt] || EN_ALT.get(n)) : EN_ALT.get(n); });
    phNodes.forEach(n => { n.placeholder = ar ? (AR[n.dataset.i18nPh] || EN_PH.get(n)) : EN_PH.get(n); });
    const meta = ar && PAGE.meta ? PAGE.meta : META_EN;
    document.title = meta.title;
    const md = $('meta[name="description"]'); if (md) md.content = meta.desc;
    $$('[data-lang]').forEach(b => b.setAttribute('aria-pressed', String(b.dataset.lang === root.lang)));
    $$('[data-href-ar]').forEach(a => { if (!a.dataset.hrefEn) a.dataset.hrefEn = a.getAttribute('href'); a.href = ar ? a.dataset.hrefAr : a.dataset.hrefEn; });
    if (persist) { try { localStorage.setItem('fq360-lang', root.lang); } catch (e) {} }
    listeners.forEach(fn => fn());
  }
  $$('[data-lang]').forEach(b => b.addEventListener('click', () => setLang(b.dataset.lang, true)));
  let saved = null;
  try { saved = localStorage.getItem('fq360-lang'); } catch (e) {}
  const urlLang = qs.get('lang');
  const browserAr = (navigator.languages || [navigator.language || '']).some(l => /^ar\b/i.test(l));
  setLang(urlLang === 'ar' || urlLang === 'en' ? urlLang : (saved || (browserAr ? 'ar' : 'en')), false);

  /* ---------- Header: shadow once scrolled, mobile menu ---------- */
  const head = $('.site-head');
  const onScroll = () => head && head.classList.toggle('is-stuck', scrollY > 8);
  addEventListener('scroll', onScroll, { passive: true }); onScroll();
  const burger = $('.burger'), mnav = $('#mnav');
  if (burger && mnav) {
    const icon = $('use', burger);
    const setMenu = open => { mnav.hidden = !open; burger.setAttribute('aria-expanded', String(open)); icon.setAttribute('href', icon.getAttribute('href').replace(/#.*/, open ? '#i-x' : '#i-menu')); };
    burger.addEventListener('click', () => { const open = mnav.hidden; setMenu(open); if (open) $('a', mnav).focus(); });
    mnav.addEventListener('click', e => { if (e.target.closest('a')) setMenu(false); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !mnav.hidden) { setMenu(false); burger.focus(); } });
    matchMedia('(min-width: 1041px)').addEventListener('change', e => { if (e.matches) setMenu(false); });
  }

  /* ---------- CTAs that jump to the form also put the cursor in it ---------- */
  const form = $('#lead-form');
  $$('a[href="#contact"]').forEach(a => a.addEventListener('click', () => {
    if (!form) return;
    setTimeout(() => { const f = $('input:not([type=hidden]):not([tabindex="-1"])', form); if (f) f.focus({ preventScroll: true }); }, reduce.matches ? 0 : 650);
  }));

  /* ---------- Reveal on scroll ---------- */
  const io = new IntersectionObserver(entries => entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } }), { rootMargin: '0px 0px -8% 0px', threshold: .12 });
  $$('[data-reveal]').forEach(el => io.observe(el));

  /* ---------- Mobile CTA bar: shown after the hero, hidden while the form is on screen ---------- */
  const mcta = $('.m-cta'), heroCta = $('.hero-cta'), contact = $('#contact');
  if (mcta && heroCta) {
    document.body.classList.add('has-mcta');
    let pastHero = false, atForm = false;
    const sync = () => mcta.classList.toggle('is-on', pastHero && !atForm);
    new IntersectionObserver(([e]) => { pastHero = !e.isIntersecting && e.boundingClientRect.top < 0; sync(); }).observe(heroCta);
    if (contact) new IntersectionObserver(([e]) => { atForm = e.isIntersecting; sync(); }, { threshold: .05 }).observe(contact);
  }

  /* ---------- A: module cards add modules to the upgrade review ---------- */
  const picks = $$('button.card[data-module]');
  const pickHint = $('.pick-hint'), pickedBox = $('.picked');
  const renderPicks = () => {
    const on = picks.filter(b => b.getAttribute('aria-pressed') === 'true');
    if (pickHint) $('span', pickHint).innerHTML = on.length ? T().pickedSome(on.length) : T().pickedNone;
    if (pickedBox) {
      pickedBox.hidden = !on.length;
      $('.picked-list', pickedBox).innerHTML = on.map(b => `<span>${$('.card-title', b).textContent}</span>`).join('');
    }
  };
  picks.forEach(b => b.addEventListener('click', () => { b.setAttribute('aria-pressed', String(b.getAttribute('aria-pressed') !== 'true')); renderPicks(); }));
  if (picks.length) { renderPicks(); listeners.push(renderPicks); }

  /* ---------- B: platform cards expand to the module list ---------- */
  $$('.mod-btn').forEach(btn => btn.addEventListener('click', () => {
    const open = btn.getAttribute('aria-expanded') !== 'true', panel = document.getElementById(btn.getAttribute('aria-controls'));
    btn.setAttribute('aria-expanded', String(open)); panel.hidden = !open; btn.closest('.mod').toggleAttribute('data-open', open);
  }));

  /* ---------- Lead form → Fandaqah CRM ---------- */
  /* The key ships to every visitor's browser: keep it ingest-only and rate-limited on the CRM side.
     Switch API_URL to production before launch. */
  const API_URL = 'https://crm-dev.fandaqah.com/api/ingest/lead';
  const API_KEY = 'fdq_d0930f18b74a5153c68b174748b5ed7ba17b8723ca05c21e';
  const PLATFORM = 'fandaqah_tourism_platform_contact';
  const OUTBOX = 'fq360_outbox';

  async function post(body) {
    for (let attempt = 0; attempt < 3; attempt++) {
      const ctrl = new AbortController(), stop = setTimeout(() => ctrl.abort(), 12000);
      try {
        const res = await fetch(API_URL, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-API-Key': API_KEY }, body: JSON.stringify(body), signal: ctrl.signal });
        if (res.ok) return 'ok';
        if (res.status < 500 && res.status !== 429) return 'rejected'; // a 4xx will not fix itself on retry
      } catch (e) { /* network or timeout: retry */ } finally { clearTimeout(stop); }
      if (attempt < 2) await new Promise(r => setTimeout(r, 1200 * (attempt + 1)));
    }
    return 'failed';
  }
  // enquiries that could not be delivered earlier are retried on the next visit (same submission_id, so the CRM can de-duplicate)
  (async () => {
    const box = store.get(OUTBOX) || [];
    if (!box.length || !navigator.onLine) return;
    const left = [];
    for (const item of box) { if ((await post(item)) === 'failed') left.push(item); }
    store.set(OUTBOX, left);
  })();

  if (form) {
    const done = $('#lead-done'), alertEl = $('.form-alert', form), submit = $('button[type=submit]', form);
    const field = n => form.elements[n];
    const started = Date.now();
    const latin = s => s.replace(/[٠-٩۰-۹]/g, d => String(d.charCodeAt(0) & 0xF));
    // business email or phone; Saudi numbers are normalised to +966 only when that is clearly the country, the raw value is always kept
    const parseContact = raw => {
      const v = latin(raw.trim());
      if (!v) return null;
      if (v.includes('@')) return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) ? { email: v.toLowerCase() } : null;
      const d = v.replace(/\D/g, '');
      let nsn = null;
      if (/^(00966|966)[1-9]\d{8}$/.test(d)) nsn = d.replace(/^(00966|966)/, '');
      else if (/^0[1-9]\d{8}$/.test(d)) nsn = d.slice(1);
      else if (/^5\d{8}$/.test(d)) nsn = d;
      else if (/^800\d{7}$/.test(d)) nsn = d;
      if (nsn) return { phone: '+966' + nsn, phone_raw: raw.trim() };
      if (v.startsWith('+') && d.length >= 8 && d.length <= 15) return { phone: '+' + d, phone_raw: raw.trim() };
      if (d.startsWith('00') && d.length >= 10 && d.length <= 17) return { phone: '+' + d.slice(2), phone_raw: raw.trim() };
      return null;
    };
    const RULES = { company: v => v.trim().length >= 2, name: v => v.trim().length >= 2, contact: v => !!parseContact(v) };
    const check = n => {
      const el = field(n), ok = RULES[n](el.value), err = document.getElementById(el.getAttribute('aria-describedby').split(' ')[0]);
      el.setAttribute('aria-invalid', String(!ok)); err.hidden = ok; return ok;
    };
    form.addEventListener('input', e => { if (form.dataset.tried && RULES[e.target.name]) check(e.target.name); });
    // keep the interest select in step with the route by default, without touching attribution
    const interest = field('interest');
    if (interest && PAGE.segment && !interest.value) interest.value = INTEREST[PAGE.segment];

    const val = n => (field(n) && field(n).value.trim()) || '';
    const optText = n => { const el = field(n); return el && el.value ? el.options[el.selectedIndex].text : ''; };

    form.addEventListener('submit', async e => {
      e.preventDefault();
      if (submit.disabled) return;
      form.dataset.tried = '1';
      const bad = Object.keys(RULES).filter(n => field(n) && !check(n));
      if (bad.length) { field(bad[0]).focus(); return; }
      const id = (crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2));
      const showDone = () => {
        form.hidden = true; done.hidden = false;
        $('.ref b', done).textContent = 'FQ-' + id.replace(/-/g, '').slice(0, 8).toUpperCase();
        $('h3', done).focus();
      };
      // bots: honeypot filled or submitted faster than a person can type, so fake success and send nothing
      if (val('website') || Date.now() - started < 2500) { showDone(); return; }

      const contactInfo = parseContact(val('contact'));
      const first = attr.first || touch, latest = attr.latest || touch;
      const src = k => latest[k] || first[k] || 'Direct/Unknown';
      const campaign = latest.utm_campaign || first.utm_campaign || 'Direct/Unknown';
      const modules = picks.filter(b => b.getAttribute('aria-pressed') === 'true').map(b => b.dataset.module);
      const consent = !!(field('consent') && field('consent').checked);
      const now = new Date().toISOString();
      const routeName = { A: 'Existing customers', B: 'New hotels', C: 'Opera / PMS', D: 'Hotel groups' }[PAGE.segment] || 'Hub';
      const details = [
        ['Company', val('company')], ['City', val('city')], ['Role', val('role')], ['Preferred channel', optText('channel')],
        ['Account ID', val('account_id')], ['Current PMS', val('current_pms')], ['PMS version / deployment', val('pms_version')],
        ['Properties', optText('property_count')], ['Property structure', optText('ownership')], ['Modules for review', modules.join(', ')]
      ].filter(([, v]) => v).map(([k, v]) => `${k}: ${v}`);
      const payload = {
        submission_id: id,
        name: val('name'), ...contactInfo,
        company: val('company'), city: val('city'), role: val('role'),
        preferred_channel: val('channel'), interest: val('interest'), interest_label: optText('interest') || val('interest'),
        account_id: val('account_id') || undefined, current_pms: val('current_pms') || undefined, pms_version: val('pms_version') || undefined,
        property_count: val('property_count') || undefined, ownership: val('ownership') || undefined, modules: modules.length ? modules : undefined,
        message: [`[Fandaqah 360 · ${PAGE.segment || '-'} ${routeName} · ${optText('interest') || val('interest')}]`, ...details,
          `Campaign: ${campaign} | source: ${src('utm_source')} | medium: ${src('utm_medium')} | creative: ${src('utm_content')}`,
          `Marketing updates: ${consent ? 'yes' : 'no'}`].join('\n'),
        platform: PLATFORM, source: 'fandaqah_360_campaign_site', segment: PAGE.segment || null,
        campaign, campaign_code: codeOf(campaign) || 'Direct/Unknown',
        utm_campaign: campaign, utm_source: src('utm_source'), utm_medium: src('utm_medium'), utm_content: src('utm_content'), utm_term: latest.utm_term || first.utm_term || undefined,
        first_touch: first, latest_touch: latest,
        landing_url: first.landing_url, page_url: location.href.split('#')[0], submitted_at: now, language: root.lang,
        privacy_notice: { shown: true, url: $('.form-privacy a', form).href, version: '2026-10' },
        marketing_consent: consent, marketing_consent_text: consent ? $('.consent span', form).textContent.trim() : undefined, marketing_consent_at: consent ? now : undefined
      };

      submit.disabled = true; alertEl.hidden = true;
      const label = $('span', submit), was = label.innerHTML;
      label.textContent = T().sending;
      const result = await post(payload);
      submit.disabled = false; label.innerHTML = was;
      if (result === 'ok') {
        if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', campaign_code: payload.campaign_code, segment: PAGE.segment, interest: payload.interest });
        showDone();
      } else if (result === 'rejected') {
        alertEl.innerHTML = T().reject; alertEl.hidden = false;
      } else {
        const box = store.get(OUTBOX) || []; box.push(payload); store.set(OUTBOX, box.slice(-5));
        alertEl.innerHTML = T().fail; alertEl.hidden = false;
      }
    });
  }
})();
