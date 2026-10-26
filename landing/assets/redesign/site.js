(() => {
  const $ = (s, p = document) => p.querySelector(s);
  const $$ = (s, p = document) => [...p.querySelectorAll(s)];
  const root = document.documentElement;
  // feature pages exist as an English/Arabic pair of static pages: the language button goes to the twin
  const altHref = root.dataset.langAlt || '';
  const isAr = () => root.lang === 'ar';
  const menu = $('#mega-menu'), toggle = $('#features-toggle'), backdrop = $('.menu-backdrop');
  const setMenu = (open) => {
    if (!menu) return;
    menu.hidden = !open;
    backdrop.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
  };
  if (menu) {
    toggle.addEventListener('click', () => setMenu(menu.hidden));
    backdrop.addEventListener('click', () => setMenu(false));
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && !menu.hidden) { setMenu(false); toggle.focus(); }
    });
    document.addEventListener('click', e => {
      if (!e.target.closest('.site-header') && !e.target.closest('.menu-backdrop') && !menu.hidden) setMenu(false);
    });
    $('#mega-search').addEventListener('input', e => {
      const value = e.target.value.trim().toLowerCase();
      $$('.module-item', menu).forEach(item => item.hidden = !item.dataset.search.toLowerCase().includes(value));
      $$('.module-group', menu).forEach(group => group.hidden = !$$('.module-item', group).some(item => !item.hidden));
    });
  }
  const mobile = $('.mobile-nav'), mobileToggle = $('.mobile-toggle');
  mobileToggle.addEventListener('click', () => {
    mobile.hidden = !mobile.hidden;
    mobileToggle.setAttribute('aria-expanded', String(!mobile.hidden));
    mobileToggle.setAttribute('aria-label', mobile.hidden ? (isAr() ? 'فتح القائمة' : 'Open navigation') : (isAr() ? 'إغلاق القائمة' : 'Close navigation'));
    mobileToggle.textContent = mobile.hidden ? '☰' : '×';
  });
  const featureSearch = $('#feature-search');
  if (featureSearch) {
    let category = '-1';
    function filter() {
      let count = 0;
      const query = featureSearch.value.trim().toLowerCase();
      $$('.all-features .module-group').forEach(group => {
        let groupCount = 0;
        $$('[data-module]', group).forEach(item => {
          item.hidden = !item.dataset.search.toLowerCase().includes(query);
          if (!item.hidden) groupCount++;
        });
        group.hidden = !groupCount || (category !== '-1' && group.dataset.group !== category);
        if (!group.hidden) count += groupCount;
      });
      $('.empty-results').hidden = count > 0;
      $('#search-status').textContent = isAr() ? `${count} ميزة مطابقة` : `${count} matching features`;
    }
    featureSearch.addEventListener('input', filter);
    $$('[data-filter]').forEach(button => button.addEventListener('click', () => {
      category = button.dataset.filter;
      $$('[data-filter]').forEach(b => {
        b.classList.toggle('active', b === button);
        b.setAttribute('aria-pressed', String(b === button));
      });
      filter();
    }));
  }

  /* ---------- the lead dialog: every [data-demo] button opens it; requests post straight to Fandaqah CRM ---------- */
  /* On static hosting the requests post straight to the CRM, so this key ships to every visitor's browser: keep it ingest-only and
     rate-limited on the CRM side. The PHP copy of each page carries <meta name="fq-lead-proxy"> and posts to api/lead.php, which adds the key. */
  const API_URL = 'https://crm.fandaqah.com/api/ingest/lead';
  const API_KEY = 'fdq_d0930f18b74a5153c68b174748b5ed7ba17b8723ca05c21e';
  const PLATFORM = 'fandaqah_tourism_platform_contact';
  const PROXY = ($('meta[name="fq-lead-proxy"]') || {}).content || '';
  // if the proxy is missing (api/ not uploaded) or cannot reach the CRM from the server, the request goes straight to the CRM instead
  const sendLead = async (payload, signal) => {
    const direct = () => fetch(API_URL, { method: 'POST', body: JSON.stringify(payload), signal, headers: { 'Content-Type': 'application/json', 'X-API-Key': API_KEY } });
    if (!PROXY) return direct();
    try {
      const res = await fetch(PROXY, { method: 'POST', body: JSON.stringify(payload), signal, headers: { 'Content-Type': 'application/json', Accept: 'application/json' } });
      return res.status === 404 || res.status === 405 || res.status >= 500 ? direct() : res;
    } catch (err) { if (signal && signal.aborted) throw err; return direct(); }
  };
  const PACKAGES = {
    basic: { price: 1200, option: 'Basic Subscription',
      en: ['Basic Subscription', ['PMS Core + 5 modules', 'Standard support', 'Basic training']],
      ar: ['الاشتراك الأساسي', ['نظام إدارة الفندق + 5 وحدات', 'دعم عادي', 'تدريب أساسي']] },
    compliance: { price: 3200, option: 'Compliance Package',
      en: ['Compliance Package', ['PMS Core + ZATCA Integration', 'Shomoos Wallet + NTMP', 'Priority support', 'Full training']],
      ar: ['باقة الامتثال', ['نظام إدارة الفندق + تكامل الزكاة والضريبة', 'محفظة شموس + منصة الرصد السياحي', 'دعم ذو أولوية', 'تدريب كامل']] },
    '360': { price: 3495, option: 'Fandaqah 360 demo',
      en: ['Fandaqah 360', ['Everything in Compliance', 'Full access to all 20 modules', 'Premium support', 'Priority onboarding']],
      ar: ['فندقة 360', ['كل ما في باقة الامتثال', 'وصول كامل إلى الوحدات العشرين', 'دعم مميز', 'تهيئة ذات أولوية']] }
  };
  const money = n => n.toLocaleString('en-US', { minimumFractionDigits: n % 1 ? 2 : 0, maximumFractionDigits: 2 });
  const withVat = n => Math.round(n * 115) / 100;
  const MSG = {
    en: { sending: 'Sending…', price: (p, v) => `SAR ${p} a year + VAT · SAR ${v} including 15% VAT`,
          fail: (mail) => `We couldn’t send your request. Please try again, call <a href="tel:+966920066456" dir="ltr">+966 9200 66456</a> or <a href="${mail}">email us instead</a>.` },
    ar: { sending: 'جارٍ الإرسال…', price: (p, v) => `<bdi>${p}</bdi> ريال سنوياً + الضريبة · <bdi>${v}</bdi> ريال شاملة ضريبة القيمة المضافة 15%`,
          fail: (mail) => `تعذّر إرسال طلبك. حاول مرة أخرى، أو اتصل بنا على <a href="tel:+966920066456" dir="ltr">+966 9200 66456</a> أو <a href="${mail}">راسلنا بالبريد الإلكتروني</a>.` }
  };
  const latinDigits = s => s.replace(/[٠-٩۰-۹]/g, d => String(d.charCodeAt(0) & 0xF));
  // Saudi mobiles only: 05XXXXXXXX, 5XXXXXXXX, +9665…, 009665… and Arabic-Indic digits; returns the 9-digit national number or null
  const saMobile = raw => { const d = latinDigits(raw).replace(/\D/g, '').replace(/^(00966|966)/, '').replace(/^0/, ''); return /^5\d{8}$/.test(d) ? d : null; };
  const CHECKS = {
    name: v => v.trim().length >= 2,
    email: v => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()),
    property: v => v.trim().length >= 2,
    phone: v => !!saMobile(v)
  };
  const dialog = $('#demo-dialog'), form = $('#demo-form');
  if (dialog && form) {
    const pkgBox = $('.demo-package', dialog), alertEl = $('.demo-alert', dialog), submit = $('button[type="submit"]', form);
    const fieldNames = Object.keys(CHECKS);
    let opener = null, pkgKey = '';
    const showStep = s => $$('[data-demo-step]', dialog).forEach(x => { x.hidden = x.dataset.demoStep !== s; });
    const validate = name => {
      const el = form.elements[name], ok = CHECKS[name](el.value);
      el.setAttribute('aria-invalid', String(!ok));
      document.getElementById(el.getAttribute('aria-describedby')).hidden = ok;
      return ok;
    };
    const guess = text => /Basic|الأساسي/.test(text) ? 'Basic Subscription' : /Compliance|الامتثال/.test(text) ? 'Compliance Package'
      : /Free Trial|تجربة/.test(text) ? 'Free trial' : 'Fandaqah 360 demo';
    function open(button) {
      setMenu(false);
      opener = button;
      pkgKey = PACKAGES[button.dataset.package] ? button.dataset.package : '';
      const pkg = PACKAGES[pkgKey];
      const intent = button.dataset.intent || (pkg ? pkg.option : guess(button.textContent));
      const select = form.elements.interest;
      select.value = [...select.options].some(o => o.value === intent) ? intent : (pkg ? pkg.option : 'Fandaqah 360 demo');
      form.elements.topic.value = intent;
      if (pkg) {
        const lang = isAr() ? 'ar' : 'en';
        $('.demo-package-name', pkgBox).textContent = pkg[lang][0];
        $('.demo-package-price', pkgBox).innerHTML = MSG[lang].price(money(pkg.price), money(withVat(pkg.price)));
        $('.demo-package-items', pkgBox).replaceChildren(...pkg[lang][1].map(t => { const li = document.createElement('li'); li.textContent = t; return li; }));
      }
      pkgBox.hidden = !pkg;
      alertEl.hidden = true;
      showStep('form');
      dialog.showModal();
      form.elements.name.focus();
    }
    function done() {
      showStep('done');
      form.reset();
      delete form.dataset.tried;
      fieldNames.forEach(n => { form.elements[n].removeAttribute('aria-invalid'); document.getElementById(form.elements[n].getAttribute('aria-describedby')).hidden = true; });
      $('[data-demo-step="done"] h2', dialog).focus();
    }
    document.addEventListener('click', e => {
      const button = e.target.closest('[data-demo]');
      if (button) { e.preventDefault(); open(button); }
    });
    $('.dialog-close', dialog).addEventListener('click', () => dialog.close());
    $('[data-demo-close]', dialog).addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', e => {
      if (e.target === dialog) {
        const r = dialog.getBoundingClientRect();
        if (e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) dialog.close();
      }
    });
    dialog.addEventListener('close', () => { if (opener && document.contains(opener)) opener.focus(); });
    form.addEventListener('input', e => { if (form.dataset.tried && CHECKS[e.target.name]) validate(e.target.name); });
    form.addEventListener('submit', async e => {
      e.preventDefault();
      if (submit.disabled) return;
      form.dataset.tried = '1';
      const bad = fieldNames.filter(n => !validate(n));
      if (bad.length) { form.elements[bad[0]].focus(); return; }
      if (form.elements.website.value) { done(); return; } // honeypot filled: a bot, so fake success and send nothing
      const lang = isAr() ? 'ar' : 'en', get = n => form.elements[n].value.trim();
      const interest = get('interest'), topic = get('topic'), property = get('property'), note = get('message');
      const qs = new URLSearchParams(location.search), utm = {};
      ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(k => { if (qs.get(k)) utm[k] = qs.get(k); });
      const pkg = PACKAGES[pkgKey];
      const page = location.pathname.split('/').slice(-2).join('/');
      const payload = {
        name: get('name'), email: get('email'), phone: '+966' + saMobile(get('phone')),
        // the visitor's own words first; everything else also goes in the message, so sales sees it even if the CRM ignores the extra fields
        message: (note ? note + '\n———\n' : '') + `Property: ${property}\nInterest: ${interest}${topic && topic !== interest ? ` (${topic})` : ''}`
          + (pkg ? `\nPackage: ${pkg.en[0]} · SAR ${money(pkg.price)} / year + VAT` : '')
          + `\n[Fandaqah landing · ${page} · ${lang.toUpperCase()}]`,
        customer_message: note || undefined,
        platform: PLATFORM, source: 'fandaqah_landing', interest, topic, property, package: pkgKey || undefined,
        language: lang, page_url: location.href.split('#')[0], ...utm
      };
      const label = submit.firstElementChild, idle = label.textContent;
      submit.disabled = true; label.textContent = MSG[lang].sending; alertEl.hidden = true;
      const ctrl = new AbortController(), stop = setTimeout(() => ctrl.abort(), 15000);
      let sent = false;
      for (let attempt = 0; attempt < 2 && !sent; attempt++) {
        try {
          const res = await sendLead(payload, ctrl.signal);
          if (res.ok) sent = true;
          else if (res.status < 500) break; // a 4xx will not succeed on retry
        } catch (err) { if (ctrl.signal.aborted) break; }
      }
      clearTimeout(stop);
      submit.disabled = false; label.textContent = idle;
      if (sent) {
        if (window.dataLayer) window.dataLayer.push({ event: 'generate_lead', lead_interest: interest });
        done();
      } else {
        const mail = `mailto:info@fandaqah.com?subject=${encodeURIComponent('Fandaqah enquiry: ' + interest)}&body=${encodeURIComponent(payload.message.replace(/\n\[[^\n]*\]$/, '') + `\n\nName: ${payload.name}\nEmail: ${payload.email}\nMobile: ${payload.phone}`)}`;
        alertEl.innerHTML = MSG[lang].fail(mail);
        alertEl.hidden = false;
      }
    });
  }

  /* ---------- newsletter: the address goes to the CRM as a newsletter lead; email is the fallback if the CRM can't be reached ---------- */
  const newsletter = $('.newsletter-form');
  if (newsletter) newsletter.addEventListener('submit', async e => {
    e.preventDefault();
    const input = $('#newsletter-email'), button = $('button', newsletter), status = $('.newsletter-status');
    const email = latinDigits(input.value.trim()).toLowerCase(), ar = isAr();
    status.classList.remove('is-ok');
    if (!CHECKS.email(email)) { status.textContent = ar ? 'يرجى إدخال بريد إلكتروني صحيح.' : 'Please enter a valid email address.'; input.focus(); return; }
    if (button.disabled) return;
    const qs = new URLSearchParams(location.search), utm = {};
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(k => { if (qs.get(k)) utm[k] = qs.get(k); });
    const page = location.pathname.split('/').slice(-2).join('/');
    const payload = { email, message: `Newsletter sign-up\n[Fandaqah landing · ${page} · ${ar ? 'AR' : 'EN'}]`, interest: 'Newsletter', topic: 'Newsletter',
      marketing_consent: true, platform: PLATFORM, source: 'fandaqah_landing_newsletter', language: ar ? 'ar' : 'en', page_url: location.href.split('#')[0], ...utm };
    button.disabled = true; status.textContent = ar ? 'جارٍ الإرسال…' : 'Sending…';
    const ctrl = new AbortController(), stop = setTimeout(() => ctrl.abort(), 15000);
    let ok = false;
    try { ok = (await sendLead(payload, ctrl.signal)).ok; } catch (err) { /* reported below */ } finally { clearTimeout(stop); button.disabled = false; }
    if (ok) {
      newsletter.reset(); status.classList.add('is-ok');
      status.textContent = ar ? 'شكراً لك. تم تسجيل بريدك في نشرة فندقة.' : 'Thank you. You’re on the Fandaqah newsletter list.';
      if (window.dataLayer) window.dataLayer.push({ event: 'newsletter_signup' });
      return;
    }
    status.replaceChildren(document.createTextNode(ar ? 'تعذّر تسجيلك الآن. ' : 'We couldn’t sign you up just now. '));
    const link = document.createElement('a');
    link.textContent = ar ? 'أرسل الطلب بالبريد ←' : 'Send the request by email →';
    link.href = `mailto:info@fandaqah.com?subject=Newsletter%20subscription&body=${encodeURIComponent(`Please subscribe ${email} to the Fandaqah newsletter.`)}`;
    status.append(link);
  });
  const quotes = [
    ['One connected place to see your reservations, support your team, and focus on what matters most: your guests.', 'Your property. Your priorities.', 'The Fandaqah experience'],
    ['From the first reservation to the final farewell, bring the everyday details of hospitality into a clearer view.', 'A more connected guest journey.', 'Designed around your workflow'],
    ['Explore operations, finance, guest services and local integrations in one complete hospitality offering.', 'More control. More possibilities.', 'Discover Fandaqah 360']
  ];
  let quoteIndex = 0;
  function showQuote(index) {
    quoteIndex = (index + quotes.length) % quotes.length;
    const [text, name, role] = quotes[quoteIndex];
    $('#quote-text').textContent = text;
    $('#quote-name').textContent = name;
    $('#quote-role').textContent = role;
    $$('.carousel-dots button').forEach((b, i) => b.classList.toggle('active', i === quoteIndex));
  }
  if ($('.carousel-next')) {
    $('.carousel-next').addEventListener('click', () => showQuote(quoteIndex + 1));
    $('.carousel-prev').addEventListener('click', () => showQuote(quoteIndex - 1));
    $$('.carousel-dots button').forEach((b, i) => b.addEventListener('click', () => showQuote(i)));
  }
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduced && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.remove('reveal-ready');
          if (window.Motion) {
            window.Motion.animate(entry.target, { opacity: [0, 1], y: [22, 0] }, { duration: .7, ease: [.22, 1, .36, 1] });
          } else entry.target.classList.add('reveal-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: .07 });
    $$('.solution-card,.choices-grid article,.benefit-grid article,.price-card,.guarantee-grid article,.light .integration-grid article').forEach(el => {
      el.classList.add('reveal-ready');
      observer.observe(el);
    });
  }

  /* ---------- language ---------- */
  if (altHref) {
    // a static Arabic or English page: switching language means opening its twin
    $('.language').addEventListener('click', () => {
      try { localStorage.setItem('fandaqah-language', isAr() ? 'en' : 'ar'); } catch {}
      location.href = altHref;
    });
    return;
  }
  const translations = {
    'About Us':'من نحن','Features':'المميزات','Packages':'الباقات','Blogs':'المدونة','Contact Us':'تواصل معنا','Refer & Earn':'شركاء النجاح','Login':'دخول','Free Trial':'تجربة مجانية','Book a Demo':'احجز عرضاً توضيحياً','Subscription Plans':'باقات الاشتراك','Explore All Features':'استكشف جميع المميزات','Home':'الرئيسية','Trusted By Over 1400+ Hotels':'موثوق لدى أكثر من 1400 فندق','Trusted by over 1400+ hotels.':'موثوق لدى أكثر من 1400 فندق.','Hotels Partnered With Fandaqah':'فندق شريك لفندقة','Trusted by':'موثوق لدى','1400+ Hotels':'أكثر من 1400 فندق','Smarter Technologies':'تقنيات أكثر ذكاءً','Saudi Hotels':'الفنادق السعودية','Modern Hospitality':'الضيافة الحديثة','Why Fandaqah?':'لماذا فندقة؟','Core & Operations':'النظام والتشغيل','Sales & Finance':'المبيعات والمالية','Guest & Dining':'الضيوف والمطاعم','Government & Support':'الجهات الحكومية والدعم','PMS Core':'نظام إدارة الفندق','Housekeeping':'التدبير الفندقي','Maintenance':'الصيانة','Inventory':'المخزون','HR':'الموارد البشرية','Channel Manager':'مدير القنوات','Booking Engine':'محرك الحجز','Payment Gateway':'بوابة الدفع','Purchasing':'المشتريات','Accounting':'المحاسبة','Passport & ID Scanner (Fast Pass)':'مسح الجواز والهوية','Digital Signature on iPad (Sign Stay)':'التوقيع الرقمي','Guest Room QR Code':'رمز غرفة النزيل','CRM':'إدارة علاقات العملاء','POS / Restaurant Management':'نقاط البيع وإدارة المطاعم','Recipe Management':'إدارة الوصفات','Shomoos Wallet':'محفظة شموس','ZATCA Integration':'تكامل الزكاة والضريبة','NTMP I – II':'منصة الرصد السياحي','Support & Training':'الدعم والتدريب','Overview':'نظرة عامة','Integrations':'التكاملات','FAQs':'الأسئلة الشائعة','All':'الكل','What is Fandaqah 360?':'ما هي فندقة 360؟','Explore all':'استكشف','20 Fandaqah 360 features':'20 ميزة في فندقة 360','Frequently Asked Questions':'الأسئلة الشائعة','Pricing & Offers':'الباقات والعروض','The Complete Hospitality Upgrade':'الترقية المتكاملة لإدارة الضيافة','Basic Subscription':'الاشتراك الأساسي','Compliance Package':'باقة الامتثال','Total Reference Value':'إجمالي القيمة المرجعية','Recommended':'موصى به','Activate your upgrade today':'فعّل ترقيتك اليوم','Activate your upgrade':'فعّل ترقيتك','What’s Included':'ما الذي تتضمنه الباقة','See what’s included':'اكتشف مميزات الباقة','Integrations & Ecosystem':'التكاملات والمنظومة','Government Services':'الخدمات الحكومية','Payment Options':'خيارات الدفع','OTAs & Distribution':'قنوات الحجز والتوزيع','Business Devices':'الأجهزة المتكاملة','Guarantees':'الضمانات','Your success is our priority':'نجاحك أولويتنا','Activation Guarantee':'ضمان التفعيل','30-Day Upgrade Protection':'حماية الترقية لمدة 30 يوماً','Limited-Time Offer':'عرض لفترة محدودة','Get Started Now':'ابدأ الآن','Quick Links':'روابط سريعة','Contact Info':'معلومات التواصل','Join Our Newsletter':'اشترك في نشرتنا','Sign Me Up':'اشترك الآن','Privacy Policy':'سياسة الخصوصية','Terms And Conditions':'الشروط والأحكام','Explore all packages':'استكشف جميع الباقات','Ease Of Use':'سهولة الاستخدام','Full Control':'تحكم كامل','Real Time Monitoring':'متابعة لحظية','Automated System':'نظام آلي','Technical Support':'الدعم الفني','Secure Cloud-Based System':'نظام سحابي آمن','Multi Connectivity Feature':'تكاملات متعددة','Rapid Development':'تطوير مستمر','Your hospitality.':'ضيافتك.','Our priority.':'أولويتنا.','Let’s build a':'لنصنع معاً','better stay.':'إقامة أفضل.','Good Morning':'صباح الخير','Occupancy Rate':'نسبة الإشغال','Total Bookings':'إجمالي الحجوزات','Revenue':'الإيرادات','New Booking':'حجز جديد','The Right Choice Starts Here':'الخيار الصحيح يبدأ هنا','Because Your Satisfaction Is Our Priority':'لأن رضاكم أولويتنا',
    // lead dialog
    'LET’S TALK HOSPITALITY':'لنتحدث عن الضيافة','See what’s possible':'اكتشف ما يمكن تحقيقه','with':'مع','Fandaqah.':'فندقة.','Payment in instalments with tabby or tamara.':'الدفع بالتقسيط عبر تابي أو تمارا.','Tell us a little about your property. Our team will contact you to arrange a personal walkthrough.':'أخبرنا قليلاً عن منشأتك، وسيتواصل معك فريقنا لترتيب عرض توضيحي خاص.','Your name':'الاسم','Work email':'البريد الإلكتروني للعمل','Property name':'اسم المنشأة','Mobile number':'رقم الجوال','I’m interested in':'أرغب في','Fandaqah 360 demo':'عرض توضيحي لفندقة 360','Free trial':'تجربة مجانية','Partner with Fandaqah':'الشراكة مع فندقة','Talk to support':'التواصل مع الدعم','Send my request':'أرسل طلبي','Your message':'رسالتك','(optional)':'(اختياري)','Please enter your name.':'يرجى إدخال اسمك.','Please enter a valid email address.':'يرجى إدخال بريد إلكتروني صحيح.','Please enter your property name.':'يرجى إدخال اسم المنشأة.','Please enter a Saudi mobile number, for example 05XXXXXXXX.':'يرجى إدخال رقم جوال سعودي، مثل 05XXXXXXXX.','We use these details only to contact you about your request.':'نستخدم هذه البيانات للتواصل معك بشأن طلبك فقط.','REQUEST RECEIVED':'تم استلام طلبك','Thank you.':'شكراً لك.','We’ll be in touch.':'سنتواصل معك قريباً.','Our team will contact you to arrange a suitable time. Demo hours: Sunday to Thursday, 9:00 to 17:00.':'سيتواصل معك فريقنا لترتيب موعد مناسب. أوقات العروض: من الأحد إلى الخميس، من 9:00 إلى 17:00.','Done':'تم','Prefer a conversation?':'تفضّل المحادثة؟','Start a free trial':'ابدأ تجربة مجانية',
    // pricing
    'incl. 15% VAT':'شامل ضريبة القيمة المضافة 15%','Compare packages':'قارن الباقات','side by side':'جنباً إلى جنب','What each package includes, taken from the package details above.':'ما تشمله كل باقة، وفق تفاصيل الباقات أعلاه.','Fandaqah 360':'فندقة 360','/ year':'/ سنوياً','Feature':'الميزة','Additional modules':'وحدات إضافية','5 modules':'5 وحدات','ZATCA, Shomoos Wallet and NTMP':'الزكاة والضريبة ومحفظة شموس ومنصة الرصد','All 20 modules':'جميع الوحدات العشرين','Support':'الدعم','Standard':'عادي','Priority':'ذو أولوية','Premium':'مميز','Training':'التدريب','Basic':'أساسي','Full':'كامل','Full, with priority onboarding':'كامل مع تهيئة ذات أولوية','Instalments with tabby or tamara':'التقسيط عبر تابي أو تمارا','Price excluding VAT':'السعر غير شامل الضريبة','Price including 15% VAT':'السعر شامل ضريبة القيمة المضافة 15%','Included':'مشمول','Not included':'غير مشمول','Choose a package':'اختر باقة','Ask about Basic':'استفسر عن الأساسية','Ask about Compliance':'استفسر عن الامتثال','Prices are per year and exclude 15% VAT unless stated. Package inclusions are confirmed during your demo.':'الأسعار سنوية وغير شاملة ضريبة القيمة المضافة 15% ما لم يُذكر خلاف ذلك، وتُؤكد محتويات الباقات خلال العرض التوضيحي.',
    // journal
    'Read about PMS Core':'اقرأ عن نظام إدارة الفندق','Read about the Channel Manager':'اقرأ عن مدير القنوات','Explore Fandaqah 360':'استكشف فندقة 360',
    // privacy and terms
    'How this website handles the information you share with us.':'كيف يتعامل هذا الموقع مع المعلومات التي تشاركها معنا.','Requests you send us':'الطلبات التي ترسلها إلينا','When you send a demo, package or support request, the details you enter (your name, email address, mobile number, property name, the interest you select and any message you write) are sent securely to Fandaqah’s customer relationship system, together with the page you sent it from, the language you were using and any campaign tags in the page address. We use them only to respond to your request and arrange your walkthrough.':'عند إرسال طلب عرض توضيحي أو استفسار عن باقة أو طلب دعم، تُرسل البيانات التي تدخلها (الاسم والبريد الإلكتروني ورقم الجوال واسم المنشأة والاهتمام الذي تختاره وأي رسالة تكتبها) بأمان إلى نظام إدارة علاقات العملاء في فندقة، مع الصفحة التي أرسلت منها واللغة التي كنت تستخدمها وأي وسوم حملات في عنوان الصفحة. ونستخدمها فقط للرد على طلبك وترتيب العرض التوضيحي.','Newsletter':'النشرة البريدية','Newsletter sign-ups send only your email address to Fandaqah’s customer relationship system, and we use it only to send you our newsletter.':'عند الاشتراك في النشرة يُرسل بريدك الإلكتروني فقط إلى نظام إدارة علاقات العملاء في فندقة، ونستخدمه لإرسال نشرتنا إليك فقط.','Preferences stored on your device':'التفضيلات المحفوظة على جهازك','Your selected language is stored locally in your browser. This website does not use advertising cookies.':'تُحفظ اللغة التي تختارها محلياً في متصفحك، ولا يستخدم هذا الموقع ملفات تعريف ارتباط إعلانية.','Third parties':'الأطراف الثالثة','Links to social networks, the Fandaqah application and email applications are governed by those services’ own privacy practices.':'تخضع روابط الشبكات الاجتماعية وتطبيق فندقة وتطبيقات البريد لممارسات الخصوصية الخاصة بتلك الخدمات.','Contact':'التواصل','The terms that apply to this website and the package information shown on it.':'الشروط التي تنطبق على هذا الموقع ومعلومات الباقات المعروضة فيه.','Website information':'معلومات الموقع','This website presents Fandaqah hospitality products and the supplied package information for review. Prices, package inclusions, integration availability and offer terms must be confirmed with the Fandaqah team before purchase.':'يعرض هذا الموقع منتجات فندقة للضيافة ومعلومات الباقات للاطلاع. ويجب تأكيد الأسعار ومحتويات الباقات وتوفر التكاملات وشروط العروض مع فريق فندقة قبل الشراء.','Introduction offer':'العرض التعريفي','Displayed prices exclude 15% VAT; prices including VAT are shown for convenience. Any activation guarantee, upgrade protection or special pricing is subject to the agreed commercial terms. This website does not accept payments or create a subscription.':'الأسعار المعروضة غير شاملة ضريبة القيمة المضافة 15%، وتُعرض الأسعار شاملة الضريبة للتسهيل. ويخضع أي ضمان تفعيل أو حماية ترقية أو تسعير خاص للشروط التجارية المتفق عليها. ولا يقبل هذا الموقع المدفوعات ولا ينشئ اشتراكاً.'
  };
  const placeholders = { 'Rooms, your current system, or anything you would like us to prepare for your demo.': 'عدد الغرف، أو نظامك الحالي، أو أي شيء تودّ أن نجهّزه لعرضك التوضيحي.', 'Full name': 'الاسم الكامل', 'Your hotel or property': 'اسم الفندق أو المنشأة', 'Email Address': 'البريد الإلكتروني', 'Search all features…': 'ابحث عن جميع المميزات…' };
  const originalNodes = [];
  const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {acceptNode(node) {
    // skip Arabic islands, not the page itself: the head guard may already have set <html lang="ar">
    return node.parentElement.closest('script,style,[lang="ar"]:not(html)') ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT;
  }});
  while (walker.nextNode()) {
    const node = walker.currentNode;
    const text = node.textContent.trim();
    if (translations[text]) originalNodes.push([node, node.textContent, node.textContent.replace(text, translations[text])]);
  }
  const fields = $$('[placeholder]').filter(el => placeholders[el.getAttribute('placeholder')]).map(el => [el, el.getAttribute('placeholder')]);
  const arLinks = $$('a[data-ar-href]').map(a => [a, a.getAttribute('href')]);
  let arabic = false;
  function language(isArabic) {
    arabic = isArabic;
    root.dir = arabic ? 'rtl' : 'ltr';
    root.lang = arabic ? 'ar' : 'en';
    originalNodes.forEach(([node, original, translated]) => node.textContent = arabic ? translated : original);
    fields.forEach(([el, en]) => el.setAttribute('placeholder', arabic ? placeholders[en] : en));
    arLinks.forEach(([a, en]) => a.setAttribute('href', arabic ? a.dataset.arHref : en));
    $('.language span').textContent = arabic ? 'EN' : 'Ar';
    $('.language').setAttribute('aria-label', arabic ? 'Switch to English' : 'Switch to Arabic');
    try { localStorage.setItem('fandaqah-language', arabic ? 'ar' : 'en'); } catch {}
  }
  $('.language').addEventListener('click', () => language(!arabic));
  const fromUrl = new URLSearchParams(location.search).get('lang');
  let saved = null;
  try { saved = localStorage.getItem('fandaqah-language'); } catch {}
  if ((fromUrl || saved || 'ar') === 'ar') language(true);   // Arabic by default
  root.classList.remove('i18n-wait');
})();
