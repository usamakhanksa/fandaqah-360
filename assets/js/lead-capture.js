/* Fandaqah 360 · unfinished-form lead capture (linked from every PHP copy by tools/build-php.py).

   A visitor who types a valid mobile number or email address into any form on the page and then leaves without sending it still
   reaches the team, as a lead marked "partial" (lead_stage: 'partial'). The page's own form script keeps sending complete
   requests exactly as before; this file only covers the people who stop half-way.

   - What counts: a complete Saudi or international mobile number (the same rules as api/lead.php) or a valid email address, from
     any provider (Gmail, iCloud, Outlook, Yahoo or a business domain, which is reported as such). A number or address written
     inside a message box is found too. Half-typed values are never sent; a value the visitor deletes is forgotten.
   - WhatsApp: when the visitor picks WhatsApp as their channel, or fills a WhatsApp field, their number is sent as `whatsapp`.
     A click on a WhatsApp, call or email link is recorded as intent on the lead (the link holds our number, never theirs).
   - When: when the page is hidden or closed, when the demo dialog is closed unsent, or after 90 s without typing. Never once the
     page's own form has been sent (its generate_lead / newsletter_signup event), never for bots (honeypot, no real keystrokes),
     and at most three times per visit, each time with the same submission_id so the CRM can merge them.
   - Where: the page's lead proxy (<meta name="fq-lead-proxy">, api/lead.php), which adds the CRM key on the server. Without the
     proxy (the GitHub Pages copies) this file does nothing, so the key never ships with it.
   - Honest: each form it watches gets one line saying so, in the page's language; a browser sending Global Privacy Control is
     left alone. Nothing personal is kept in the browser's storage.
   QA: add ?fqcap=debug to the page address to log each lead in the console; window.FQCapture.peek() shows the lead as it stands. */
(() => {
  'use strict';
  const PROXY = (document.querySelector('meta[name="fq-lead-proxy"]') || {}).content || '';
  if (!PROXY || window.FQCapture || navigator.globalPrivacyControl === true || /[?&]render=1\b/.test(location.search)) return;

  const root = document.documentElement, START = Date.now();
  const DEBUG = /[?&]fqcap=debug\b/.test(location.search);
  const IDLE = 90e3, MAX_SENDS = 3;
  const ID = 'fqcap-' + (crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2));
  const ar = () => (root.lang || 'ar').slice(0, 2) !== 'en';

  /* ---------- which campaign this page belongs to (A existing, B new hotels, C Opera/PMS, D groups) ---------- */
  const ROUTES = [
    ['existing-customers', 'A', 'Existing'], ['fandaqah-360-landing-v2', 'A', 'New'], ['new-hotels', 'B', 'New hotels'],
    ['opera-pms', 'C', 'Opera / PMS'], ['hotel-groups', 'D', 'Hotel groups'], ['Fandaqah_Campaign_D', 'D', 'Hotel groups'],
    ['/landing/', '', 'Fandaqah website']
  ];
  const [, SEGMENT, CAMPAIGN] = ROUTES.find(([k]) => location.pathname.includes(k)) || ['', '', 'Fandaqah 360'];

  /* ---------- reading what people type ---------- */
  const latin = s => String(s || '').replace(/[٠-٩۰-۹]/g, d => String(d.charCodeAt(0) & 0xF));
  // the same rules as api/lead.php, so a lead this file sends is never refused there
  const parsePhone = raw => {
    const v = latin(raw).trim(), d = v.replace(/\D/g, '');
    let m;
    if ((m = /^(?:00966|966)([1-9]\d{8})$/.exec(d))) return '+966' + m[1];
    if ((m = /^0([1-9]\d{8})$/.exec(d))) return '+966' + m[1];
    if (/^5\d{8}$/.test(d)) return '+966' + d;
    if (v.startsWith('+') && d.length >= 8 && d.length <= 15) return '+' + d;
    if (d.startsWith('00') && d.length >= 10 && d.length <= 17) return '+' + d.slice(2);
    return null;
  };
  // the slips people make on the big providers, put right (the typed value is kept as email_raw)
  const TYPOS = { 'gmail.con': 'gmail.com', 'gmail.co': 'gmail.com', 'gmial.com': 'gmail.com', 'gmai.com': 'gmail.com', 'gamil.com': 'gmail.com',
    'gmail.cm': 'gmail.com', 'icloud.con': 'icloud.com', 'iclod.com': 'icloud.com', 'icoud.com': 'icloud.com', 'hotmail.con': 'hotmail.com',
    'hotmial.com': 'hotmail.com', 'outlook.con': 'outlook.com', 'yahoo.con': 'yahoo.com' };
  const EMAIL = /[a-z0-9._%+-]+@[a-z0-9-]+(?:\.[a-z0-9-]+)*\.[a-z]{2,}/i;
  const parseEmail = raw => {
    const m = EMAIL.exec(latin(raw).trim());
    if (!m) return null;
    let [user, domain] = m[0].toLowerCase().split('@');
    domain = TYPOS[domain] || domain;
    if (/^(example|test|domain|email)\.(com|org|net)$/.test(domain) || /^(test|asdf|aaa+)$/.test(user)) return null;
    return { email: user + '@' + domain, raw: m[0] };
  };
  const PROVIDERS = { Gmail: ['gmail.com', 'googlemail.com'], iCloud: ['icloud.com', 'me.com', 'mac.com'],
    Outlook: ['outlook.com', 'hotmail.com', 'live.com', 'msn.com', 'outlook.sa', 'hotmail.sa'], Yahoo: ['yahoo.com', 'ymail.com'], Proton: ['proton.me', 'protonmail.com'] };
  const providerOf = email => {
    const domain = email.split('@')[1];
    const hit = Object.keys(PROVIDERS).find(p => PROVIDERS[p].includes(domain));
    return hit ? { email_provider: hit, email_type: 'personal' } : { email_provider: domain, email_type: 'business' };
  };
  // a mobile number written inside a message: +966 5…, 05…, 5… with spaces or dashes
  const PHONE_IN_TEXT = /(?:\+|00)?(?:966)?[\s-]?0?5(?:[\s-]?\d){8}|\+\d(?:[\s-]?\d){7,14}/;

  // what a field holds, from its type, name, id, autocomplete and label
  const SKIP_TYPES = /^(password|hidden|search|checkbox|radio|file|submit|button|reset|range|color|date|datetime-local|month|week|time|number)$/;
  const kindOf = el => {
    if (!el || !el.matches || !el.matches('input, textarea, select') || el.disabled || SKIP_TYPES.test(el.type || '')) return null;
    if ((el.name || '') === 'website' || el.closest('[aria-hidden="true"]')) return null;   // the honeypot
    const key = [el.name, el.id, el.getAttribute('autocomplete'), el.getAttribute('aria-label')].join(' ').toLowerCase();
    if (el.tagName === 'SELECT') return /channel|contact.?method|preferred/.test(key) ? 'channel' : /interest|topic|plan|package/.test(key) ? 'interest' : null;
    if (/whats|واتس/.test(key)) return 'whatsapp';
    if (el.type === 'email' || /e-?mail|بريد/.test(key)) return 'email';
    if (el.type === 'tel' || /phone|mobile|(^|\s)tel(-|\s|$)|جوال|هاتف/.test(key)) return 'phone';
    if (/(^|\s)contact(\s|$)/.test(key)) return 'contact';
    if (/(^|\s)(name|full.?name|first|contact-name|lead-name)(\s|$)|(^|\s)name$/.test(key)) return 'name';
    if (/(^|\s)role|organization-title|job/.test(key)) return 'role';
    if (/company|property|hotel|organization|organisation/.test(key)) return 'company';
    if (/(^|\s)city|address-level2/.test(key)) return 'city';
    if (el.tagName === 'TEXTAREA' || /message|note|priority|comment/.test(key)) return 'note';
    return 'text';
  };
  const CONTACT = new Set(['email', 'phone', 'whatsapp', 'contact']);

  /* ---------- what this visit has given us ---------- */
  const S = { values: new Map(), human: false, converted: false, submitted: new Set(), sends: 0, sentSig: '', intents: [], depth: 0, timer: 0 };

  function read(el) {
    const kind = kindOf(el);
    if (!kind) return;
    const v = (el.tagName === 'SELECT' ? (el.value ? el.options[el.selectedIndex].text + ' (' + el.value + ')' : '') : el.value || '').trim();
    if (!v) { S.values.delete(el); return; }                     // cleared by the visitor: forgotten
    S.values.set(el, { kind, value: v.slice(0, 1000) });
    arm();
  }

  // everything known so far, merged across the page's forms; contact details only count when complete and valid
  function contact() {
    const out = {}, fields = [];
    let channel = '';
    for (const [el, { kind, value }] of S.values) {
      if (!el.isConnected) continue;
      fields.push(el.name || el.id || kind);
      if (kind === 'phone' || kind === 'whatsapp' || kind === 'contact') {
        const p = parsePhone(value);
        if (p) { out.phone = out.phone || p; out.phone_raw = out.phone_raw || value; if (kind === 'whatsapp') out.whatsapp = p; }
      }
      if (kind === 'email' || kind === 'contact' || value.includes('@')) {
        const e = parseEmail(value);
        if (e && !out.email) { out.email = e.email; if (e.raw.toLowerCase() !== e.email) out.email_raw = e.raw; }
      }
      if (kind === 'note') {
        if (!out.phone) { const m = PHONE_IN_TEXT.exec(latin(value)); const p = m && parsePhone(m[0]); if (p) { out.phone = p; out.phone_raw = m[0].trim(); out.phone_found_in = 'message'; } }
        out.note = out.note || value;
      }
      if (kind === 'channel') channel = value;
      if (['name', 'company', 'city', 'role', 'interest'].includes(kind)) out[kind] = out[kind] || value;
    }
    if (channel) out.preferred_channel = channel;
    if (out.phone && !out.whatsapp && /whats|واتس/i.test(channel)) out.whatsapp = out.phone;
    out.fields = [...new Set(fields)];
    return out;
  }

  /* ---------- the lead ---------- */
  const store = k => { try { return JSON.parse(localStorage.getItem(k)); } catch (e) { return null; } };
  const mmss = s => `${Math.floor(s / 60)}m ${String(s % 60).padStart(2, '0')}s`;
  const INTENT = { whatsapp: 'Opened WhatsApp chat', call: 'Tapped call', email: 'Tapped email', cta: 'Clicked' };

  function lead(trigger) {
    const c = contact();
    const attr = store('fq360_attr') || {}, qs = new URLSearchParams(location.search);
    const first = attr.first || {}, latest = attr.latest || {};
    const utm = k => qs.get(k) || latest[k] || first[k] || undefined;
    const seconds = Math.round((Date.now() - START) / 1000);
    const mobile = !!c.phone && /^\+9665\d{8}$/.test(c.phone);
    const prov = c.email ? providerOf(c.email) : {};
    const route = SEGMENT ? `${SEGMENT} ${CAMPAIGN}` : CAMPAIGN;
    const did = [...new Set(S.intents.map(i => i.k === 'cta' ? `${INTENT.cta} “${i.label}”` : INTENT[i.k]))];
    const wa = c.whatsapp ? `WhatsApp: ${c.whatsapp}` + (c.preferred_channel ? ' (their chosen channel)' : '')
      : mobile ? 'WhatsApp: likely on this Saudi mobile' : '';
    const summary = [
      `[Fandaqah 360 · Unfinished form · ${route}]`,
      'The visitor entered their contact details on the page but left without sending the form. Contact them once, about the page they were reading.',
      c.name && `Name: ${c.name}`, c.company && `Property / company: ${c.company}`, c.city && `City: ${c.city}`, c.role && `Role: ${c.role}`,
      c.phone && `Mobile: ${c.phone}` + (c.phone_found_in ? ' (written in their message)' : ''), wa,
      c.email && `Email: ${c.email} (${prov.email_type === 'business' ? 'business address' : prov.email_provider})`,
      c.interest && `Interest: ${c.interest}`, c.preferred_channel && `Preferred channel: ${c.preferred_channel}`,
      c.note && `Their note (unsent): ${c.note}`,
      `Activity: ${mmss(seconds)} on the page · read ${S.depth}%` + (did.length ? ' · ' + did.join(', ') : ''),
      `Page: ${location.pathname} · ${ar() ? 'AR' : 'EN'} · captured when: ${trigger.replace(/_/g, ' ')}`,
      `Campaign: ${utm('utm_campaign') || 'Direct/Unknown'} | source: ${utm('utm_source') || 'Direct/Unknown'} | medium: ${utm('utm_medium') || 'Direct/Unknown'} | creative: ${utm('utm_content') || '-'}`
    ].filter(Boolean).join('\n');
    return {
      submission_id: ID, lead_stage: 'partial', capture_trigger: trigger, capture_revision: S.sends + 1,
      source: 'fandaqah_360_unfinished_form', campaign: CAMPAIGN, segment: SEGMENT || null,
      name: c.name || (ar() ? 'زائر لم يُكمل النموذج' : 'Visitor (unfinished form)'),
      phone: c.phone, phone_raw: c.phone_raw, whatsapp: c.whatsapp, whatsapp_likely: !c.whatsapp && mobile ? true : undefined,
      email: c.email, email_raw: c.email_raw, ...prov,
      company: c.company, city: c.city, role: c.role, interest: c.interest, preferred_channel: c.preferred_channel,
      customer_message: c.note, message: summary,
      intents: S.intents.length ? S.intents.slice(-10) : undefined, fields_completed: c.fields,
      time_on_page_s: seconds, scroll_depth_pct: S.depth, device: matchMedia('(pointer: coarse)').matches ? 'mobile' : 'desktop',
      utm_campaign: utm('utm_campaign'), utm_source: utm('utm_source'), utm_medium: utm('utm_medium'), utm_content: utm('utm_content'), utm_term: utm('utm_term'),
      first_touch: attr.first, latest_touch: attr.latest, landing_url: first.landing_url, referrer: document.referrer || undefined,
      page_url: location.href.split('#')[0], language: ar() ? 'ar' : 'en', captured_at: new Date().toISOString(),
      privacy_notice: { shown: true, text: noteText(), version: '2026-10' }
    };
  }

  /* ---------- sending ---------- */
  // the page's own form went through (or now shows its thank-you): nothing to recover
  const converted = () => S.converted || [...S.submitted].some(f => !f.isConnected || f.hidden || !!f.closest('[hidden]'));
  const bot = () => [...document.querySelectorAll('input[name="website"]')].some(i => i.value.trim());

  function flush(trigger) {
    clearTimeout(S.timer);
    if (!S.human || converted() || bot() || S.sends >= MAX_SENDS) return false;
    const body = lead(trigger);
    if (!body.phone && !body.email) return false;
    const sig = [body.phone, body.email, body.whatsapp].join('|');
    if (sig === S.sentSig) return false;                         // nothing new since the last send
    const json = JSON.stringify(body);
    let queued = false;
    // leaving the page: a beacon outlives it (same site, so the proxy accepts it); otherwise a keepalive request
    if (trigger === 'page_hidden' && navigator.sendBeacon) { try { queued = navigator.sendBeacon(PROXY, new Blob([json], { type: 'application/json' })); } catch (e) {} }
    if (!queued) fetch(PROXY, { method: 'POST', body: json, keepalive: true, credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' } }).catch(() => {});
    S.sentSig = sig; S.sends++;
    if (window.dataLayer) window.dataLayer.push({ event: 'partial_lead', campaign: CAMPAIGN, segment: SEGMENT || undefined, capture_trigger: trigger,
      has_phone: !!body.phone, has_email: !!body.email, email_type: body.email_type });
    if (DEBUG) console.info('[FQCapture] sent', body);
    return true;
  }
  // 90 s after the last keystroke with a complete contact detail on the page
  function arm() {
    clearTimeout(S.timer);
    S.timer = setTimeout(() => flush('idle_90s'), IDLE);
  }

  /* ---------- listening ---------- */
  const typing = new WeakMap();
  document.addEventListener('input', e => {
    if (!e.isTrusted || !kindOf(e.target)) return;
    S.human = true;
    clearTimeout(typing.get(e.target));
    typing.set(e.target, setTimeout(() => read(e.target), 800));
  }, true);
  ['change', 'focusout'].forEach(t => document.addEventListener(t, e => { if (kindOf(e.target)) read(e.target); }, true));
  document.addEventListener('keydown', e => { if (e.isTrusted) S.human = true; }, true);
  document.addEventListener('submit', e => S.submitted.add(e.target), true);

  // clicks that show intent; a link to our WhatsApp or phone holds our number, so it is recorded, never sent as theirs
  document.addEventListener('click', e => {
    if (!e.isTrusted) return;
    S.human = true;
    const a = e.target.closest('a[href], button, [data-demo]');
    if (!a) return;
    const href = a.getAttribute('href') || '';
    const k = /wa\.me\/|api\.whatsapp\.com|^whatsapp:/i.test(href) ? 'whatsapp' : /^tel:/i.test(href) ? 'call' : /^mailto:/i.test(href) ? 'email'
      : a.matches('[data-demo], .btn-coral, .button.primary, .btn-primary, [href*="#lead"], [href*="#contact"], [type="submit"]') ? 'cta' : '';
    if (!k) return;
    S.intents.push({ k, label: (a.getAttribute('aria-label') || a.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 60), t: Math.round((Date.now() - START) / 1000) });
    if (S.intents.length > 20) S.intents.shift();
    if (k !== 'cta' && window.dataLayer) window.dataLayer.push({ event: 'contact_intent', channel: k, campaign: CAMPAIGN });
  }, true);

  // the demo dialog closed without a request: give it a moment (a successful send closes it too), then recover
  document.addEventListener('close', e => {
    if (e.target.tagName === 'DIALOG' && [...S.values.keys()].some(el => e.target.contains(el))) setTimeout(() => flush('dialog_closed'), 1500);
  }, true);
  document.addEventListener('visibilitychange', () => { if (document.visibilityState === 'hidden') flush('page_hidden'); });
  addEventListener('pagehide', () => flush('page_hidden'));

  // how far they read
  let ticking = false;
  addEventListener('scroll', () => {
    if (ticking) return; ticking = true;
    requestAnimationFrame(() => {
      ticking = false;
      const h = document.documentElement.scrollHeight - innerHeight;
      S.depth = Math.max(S.depth, h > 0 ? Math.min(100, Math.round(scrollY / h * 100)) : 100);
    });
  }, { passive: true });

  // the page's own success events: generate_lead (any form) ends the watch; a newsletter sign-up ends it unless a number was given too
  window.dataLayer = window.dataLayer || [];
  const seen = ev => {
    const name = ev && ev.event;
    if (name === 'generate_lead' || (name === 'newsletter_signup' && !contact().phone)) { S.converted = true; clearTimeout(S.timer); }
  };
  window.dataLayer.forEach(seen);
  const push = window.dataLayer.push;
  window.dataLayer.push = function (...events) { events.forEach(seen); return push.apply(this, events); };

  /* ---------- the line that tells people, under every form it watches ---------- */
  const NOTE = {
    ar: 'إذا غادرت قبل الإرسال، فقد نتواصل معك مرة واحدة بخصوص طلبك عبر الرقم أو البريد الذي أدخلته.',
    en: 'If you leave before sending, we may contact you once about your enquiry using the number or email you entered.',
    // a form that asks only for an email (the newsletter)
    arMail: 'إذا غادرت قبل الإرسال، فقد نراسلك مرة واحدة على البريد الذي أدخلته.',
    enMail: 'If you leave before sending, we may email you once at the address you entered.'
  };
  const noteText = (mailOnly = false) => NOTE[(ar() ? 'ar' : 'en') + (mailOnly ? 'Mail' : '')];
  function annotate() {
    const style = document.createElement('style');
    style.textContent = '.fq-cap-note{margin:8px 0 0;font-size:.82em;line-height:1.6;opacity:.72;grid-column:1/-1}.fq-cap-note.is-inline{display:inline;margin:0;font-size:inherit;line-height:inherit;opacity:.85}';
    document.head.appendChild(style);
    for (const form of document.forms) {
      const kinds = [...form.elements].map(kindOf);
      if (form.querySelector('.fq-cap-note') || !kinds.some(k => CONTACT.has(k))) continue;
      const mailOnly = !kinds.some(k => k === 'phone' || k === 'whatsapp' || k === 'contact');
      const anchor = form.querySelector('.form-privacy, .demo-consent, .lead-note, .form-note, .privacy');
      // best: a second line inside the form's own privacy line, in its type and alignment
      if (anchor && !/flex|grid/.test(getComputedStyle(anchor).display)) {
        const s = document.createElement('span');
        s.className = 'fq-cap-note is-inline'; s.dataset.mail = mailOnly ? '1' : ''; s.textContent = noteText(mailOnly);
        anchor.append(document.createElement('br'), s);
        continue;
      }
      const p = document.createElement('p');
      p.className = 'fq-cap-note'; p.dataset.mail = mailOnly ? '1' : ''; p.textContent = noteText(mailOnly);
      if (anchor) { p.style.textAlign = getComputedStyle(anchor).textAlign; anchor.after(p); }
      else if (form.matches('.newsletter-form') || form.elements.length < 3) form.after(p);   // a one-line form: under it, not inside it
      else form.appendChild(p);
    }
    new MutationObserver(() => document.querySelectorAll('.fq-cap-note').forEach(p => { p.textContent = noteText(!!p.dataset.mail); }))
      .observe(root, { attributes: true, attributeFilter: ['lang'] });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', annotate); else annotate();

  window.FQCapture = { peek: () => lead('preview'), flush: () => flush('manual') };
})();
