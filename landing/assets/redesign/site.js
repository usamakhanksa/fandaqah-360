(() => {
  const $ = (s, p = document) => p.querySelector(s);
  const $$ = (s, p = document) => [...p.querySelectorAll(s)];
  const menu = $('#mega-menu'), toggle = $('#features-toggle'), backdrop = $('.menu-backdrop');
  const setMenu = (open) => {
    menu.hidden = !open;
    backdrop.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
  };
  toggle.addEventListener('click', () => setMenu(menu.hidden));
  backdrop.addEventListener('click', () => setMenu(false));
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !menu.hidden) { setMenu(false); toggle.focus(); }
  });
  document.addEventListener('click', e => {
    if (!e.target.closest('.site-header') && !e.target.closest('.menu-backdrop') && !menu.hidden) setMenu(false);
  });
  const mobile = $('.mobile-nav'), mobileToggle = $('.mobile-toggle');
  mobileToggle.addEventListener('click', () => {
    mobile.hidden = !mobile.hidden;
    mobileToggle.setAttribute('aria-expanded', String(!mobile.hidden));
    mobileToggle.setAttribute('aria-label', mobile.hidden ? 'Open navigation' : 'Close navigation');
    mobileToggle.textContent = mobile.hidden ? '☰' : '×';
  });
  $('#mega-search').addEventListener('input', e => {
    const value = e.target.value.trim().toLowerCase();
    $$('.module-item', menu).forEach(item => item.hidden = !item.dataset.search.toLowerCase().includes(value));
    $$('.module-group', menu).forEach(group => group.hidden = !$$('.module-item', group).some(item => !item.hidden));
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
      $('#search-status').textContent = `${count} matching features`;
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
  const dialog = $('#demo-dialog'), form = $('#demo-form');
  $$('[data-demo]').forEach(button => button.addEventListener('click', () => {
    setMenu(false);
    form.hidden = false;
    $('#demo-result').hidden = true;
    const intent = button.dataset.intent || (button.textContent.includes('Basic') ? 'Basic Subscription' : button.textContent.includes('Compliance') ? 'Compliance Package' : button.textContent.includes('Free Trial') ? 'Free trial' : 'Fandaqah 360 demo');
    form.elements.interest.value = intent;
    dialog.showModal();
  }));
  $('.dialog-close').addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', e => {
    if (e.target === dialog) {
      const r = dialog.getBoundingClientRect();
      if (e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) dialog.close();
    }
  });
  form.addEventListener('submit', e => {
    e.preventDefault();
    const data = new FormData(form);
    const subject = `Fandaqah enquiry: ${data.get('interest')}`;
    const body = `Hello Fandaqah team,\n\nI would like to discuss ${data.get('interest')}.\n\nName: ${data.get('name')}\nEmail: ${data.get('email')}\nProperty: ${data.get('property')}\nPhone: ${data.get('phone') || 'Not provided'}\n\nPlease contact me to arrange a suitable time.\n\nThank you.`;
    $('#send-request').href = `mailto:info@fandaqah.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    form.hidden = true;
    $('#demo-result').hidden = false;
    $('#send-request').focus();
  });
  $('.newsletter-form').addEventListener('submit', e => {
    e.preventDefault();
    const email = $('#newsletter-email').value;
    const status = $('.newsletter-status');
    status.replaceChildren(document.createTextNode('Finish your subscription request by email: '));
    const link = document.createElement('a');
    link.textContent = 'Open email request →';
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
  const translations = {
    'About Us':'من نحن','Features':'المميزات','Packages':'الباقات','Blogs':'المدونة','Contact Us':'تواصل معنا','Refer & Earn':'شركاء النجاح','Login':'دخول','Free Trial':'تجربة مجانية','Book a Demo':'احجز عرضاً توضيحياً','Subscription Plans':'باقات الاشتراك','Explore All Features':'استكشف جميع المميزات','Home':'الرئيسية','Trusted By Over 800+ Hotels':'موثوق لدى أكثر من 800 فندق','Smarter Technologies':'تقنيات أكثر ذكاءً','Saudi Hotels':'الفنادق السعودية','Modern Hospitality':'الضيافة الحديثة','Why Fandaqah?':'لماذا فندقة؟','Core & Operations':'النظام والتشغيل','Sales & Finance':'المبيعات والمالية','Guest & Dining':'الضيوف والمطاعم','Government & Support':'الجهات الحكومية والدعم','PMS Core':'نظام إدارة الفندق','Housekeeping':'التدبير الفندقي','Maintenance':'الصيانة','Inventory':'المخزون','HR':'الموارد البشرية','Channel Manager':'مدير القنوات','Booking Engine':'محرك الحجز','Payment Gateway':'بوابة الدفع','Purchasing':'المشتريات','Accounting':'المحاسبة','Passport & ID Scanner (Fast Pass)':'مسح الجواز والهوية','Digital Signature on iPad (Sign Stay)':'التوقيع الرقمي','Guest Room QR Code':'رمز غرفة النزيل','CRM':'إدارة علاقات العملاء','POS / Restaurant Management':'نقاط البيع وإدارة المطاعم','Recipe Management':'إدارة الوصفات','Shomoos Wallet':'محفظة شموس','ZATCA Integration':'تكامل الزكاة والضريبة','NTMP I – II':'منصة الرصد السياحي','Support & Training':'الدعم والتدريب','Overview':'نظرة عامة','Integrations':'التكاملات','FAQs':'الأسئلة الشائعة','All':'الكل','What is Fandaqah 360?':'ما هي فندقة 360؟','Explore all':'استكشف','20 Fandaqah 360 features':'20 ميزة في فندقة 360','Frequently Asked Questions':'الأسئلة الشائعة','Pricing & Offers':'الباقات والعروض','The Complete Hospitality Upgrade':'الترقية المتكاملة لإدارة الضيافة','Basic Subscription':'الاشتراك الأساسي','Compliance Package':'باقة الامتثال','Total Reference Value':'إجمالي القيمة المرجعية','Recommended':'موصى به','Activate your upgrade today':'فعّل ترقيتك اليوم','Activate your upgrade':'فعّل ترقيتك','What’s Included':'ما الذي تتضمنه الباقة','See what’s included':'اكتشف مميزات الباقة','Integrations & Ecosystem':'التكاملات والمنظومة','Government Services':'الخدمات الحكومية','Payment Options':'خيارات الدفع','OTAs & Distribution':'قنوات الحجز والتوزيع','Business Devices':'الأجهزة المتكاملة','Guarantees':'الضمانات','Your success is our priority':'نجاحك أولويتنا','Activation Guarantee':'ضمان التفعيل','30-Day Upgrade Protection':'حماية الترقية لمدة 30 يوماً','Limited-Time Offer':'عرض لفترة محدودة','Get Started Now':'ابدأ الآن','Quick Links':'روابط سريعة','Contact Info':'معلومات التواصل','Join Our Newsletter':'اشترك في نشرتنا','Sign Me Up':'اشترك الآن','Privacy Policy':'سياسة الخصوصية','Terms And Conditions':'الشروط والأحكام','Explore all packages':'استكشف جميع الباقات','Ease Of Use':'سهولة الاستخدام','Full Control':'تحكم كامل','Real Time Monitoring':'متابعة لحظية','Automated System':'نظام آلي','Technical Support':'الدعم الفني','Secure Cloud-Based System':'نظام سحابي آمن','Multi Connectivity Feature':'تكاملات متعددة','Rapid Development':'تطوير مستمر','Your hospitality.':'ضيافتك.','Our priority.':'أولويتنا.','Let’s build a':'لنصنع معاً','better stay.':'إقامة أفضل.','Good Morning':'صباح الخير','Occupancy Rate':'نسبة الإشغال','Total Bookings':'إجمالي الحجوزات','Revenue':'الإيرادات','New Booking':'حجز جديد','The Right Choice Starts Here':'الخيار الصحيح يبدأ هنا','Because Your Satisfaction Is Our Priority':'لأن رضاكم أولويتنا'
  };
  const originalNodes = [];
  const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {acceptNode(node) {
    return node.parentElement.closest('script,style,[lang="ar"]') ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT;
  }});
  while (walker.nextNode()) {
    const node = walker.currentNode;
    const text = node.textContent.trim();
    if (translations[text]) originalNodes.push([node, node.textContent, translations[text]]);
  }
  let arabic = false;
  function language(isArabic) {
    arabic = isArabic;
    document.documentElement.dir = arabic ? 'rtl' : 'ltr';
    document.documentElement.lang = arabic ? 'ar' : 'en';
    originalNodes.forEach(([node, original, translated]) => node.textContent = arabic ? translated : original);
    $('.language span').textContent = arabic ? 'EN' : 'Ar';
    $('.language').setAttribute('aria-label', arabic ? 'Switch to English' : 'Switch to Arabic');
    if (featureSearch) featureSearch.placeholder = arabic ? 'ابحث عن جميع المميزات…' : 'Search all features…';
    try { localStorage.setItem('fandaqah-language', arabic ? 'ar' : 'en'); } catch {}
  }
  $('.language').addEventListener('click', () => language(!arabic));
  try { if (localStorage.getItem('fandaqah-language') === 'ar') language(true); } catch {}
})();
