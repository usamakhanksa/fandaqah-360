/* Campaign book data: chapter facts and the 25-second reel scripts (playbook storyboards, 20 Oct 2026).
   Beats follow the playbook timing: hook 0–3 s, solution 3–10 s, proof 10–20 s, call to action 20–25 s. */
window.BOOK_DATA = {
  beats: [3, 7, 10, 5],
  campaigns: {
    A: {
      folder: 'A-existing-customers', route: 'existing-customers/', theme: 'light', photo: 'hero-existing.webp', photoPos: '86% 40%', device: 'laptopA',
      en: {
        name: 'Existing customers', buyer: 'Owner, GM or finance manager already on Fandaqah', action: 'Launch-price upgrade',
        promise: 'More value from the Fandaqah you already use.', cta: 'Lock in my launch price', chip: 'For existing Fandaqah customers',
        // the hook's three manual records pay off in scene 3: purchasing, housekeeping and accounting get ticked
        motif: ['Purchase spreadsheet', 'Paper room log', 'Month-end by hand'],
        scenes: [
          { h: 'Already with Fandaqah? <em>Get more value.</em>', cap: 'Still on paper and spreadsheets?' },
          { h: 'Connect more of <em>your daily work.</em>', s: 'Same system, same team, more of your hotel.', cap: 'Accounting and purchasing join the Fandaqah you already use.' },
          { h: 'See what your <em>upgrade includes.</em>', list: ['Accounting', 'Purchasing', 'Housekeeping', 'Maintenance', 'Inventory'], cap: 'And many more services.' },
          { h: 'Lock in the <em>launch price for two years.</em>', s: 'Fandaqah 360, all 20 modules: SAR 3,495 a year, excl. VAT. 30-day Golden Guarantee.', cap: 'This launch price won’t be offered again. Lock it in today.' }
        ]
      },
      ar: {
        name: 'العملاء الحاليون', buyer: 'المالك أو المدير العام أو المدير المالي من عملاء فندقة', action: 'الترقية بسعر الإطلاق',
        promise: 'قيمة أكبر من فندقة التي تستخدمها اليوم.', cta: 'ثبّت سعر الإطلاق', chip: 'لعملاء فندقة الحاليين',
        motif: ['جدول المشتريات', 'سجل الغرف الورقي', 'إقفال الشهر يدوياً'],
        scenes: [
          { h: 'تستخدم فندقة؟ <em>احصل على قيمة أكبر.</em>', cap: 'ما زلت على الورق وجداول البيانات؟' },
          { h: 'اربط مزيداً من <em>عملك اليومي.</em>', s: 'النظام نفسه والفريق نفسه، وجزء أكبر من فندقك مترابط.', cap: 'المحاسبة والمشتريات تنضمّان إلى فندقة التي تستخدمها.' },
          { h: 'اكتشف ما <em>تشمله ترقيتك.</em>', list: ['المحاسبة', 'المشتريات', 'التدبير الفندقي', 'الصيانة', 'المخزون'], cap: 'والمزيد من الخدمات الأخرى.' },
          { h: 'ثبّت <em>سعر الإطلاق لمدة سنتين.</em>', s: 'فندقة 360 بوحداتها العشرين: 3,495 ريالاً سنوياً غير شاملة الضريبة. ضمان ذهبي لمدة 30 يوماً.', cap: 'سعر الإطلاق هذا لن يتكرر. ثبّته اليوم.' }
        ]
      }
    },
    B: {
      folder: 'B-new-hotels', route: 'new-hotels/', theme: 'light', photo: 'hero-new.webp', photoPos: '50% 50%', device: 'laptopB',
      en: {
        name: 'New hotels', buyer: 'Independent owner or GM', action: 'Hotel demo',
        promise: 'Your hotel operations. One connected platform.', cta: 'Book my hotel demo', chip: 'For new independent hotels',
        motif: ['Reservations', 'Accounting', 'POS'],
        scenes: [
          { h: 'Still managing your hotel in <em>separate systems?</em>', cap: 'Too many systems, repeated entry, unclear responsibility?' },
          { h: 'Bring your operations <em>together.</em>', s: 'One connected platform, built for Saudi hospitality.', cap: 'With Fandaqah 360, everything starts from the reservation.' },
          { h: 'See the workflow in <em>Fandaqah 360.</em>', flow: ['Reservation', 'Room readiness', 'Guest service', 'Accounting'], cap: 'Rooms get ready, service is logged, accounting updates in one place.' },
          { h: 'Demo first. <em>Then lock in your price.</em>', s: 'Subscribe for 2 years at the launch price. Standard pricing applies after the launch.', cap: 'Book your demo now, and lock in the launch price for two years.' }
        ]
      },
      ar: {
        name: 'الفنادق الجديدة', buyer: 'مالك أو مدير عام فندق مستقل', action: 'عرض توضيحي',
        promise: 'عمليات فندقك. منصة واحدة مترابطة.', cta: 'احجز عرضاً توضيحياً لفندقي', chip: 'للفنادق المستقلة الجديدة',
        motif: ['الحجوزات', 'المحاسبة', 'نقاط البيع'],
        scenes: [
          { h: 'ما زلت تدير فندقك <em>بأنظمة متفرقة؟</em>', cap: 'أنظمة كثيرة، وإدخال متكرر، ومسؤوليات غير واضحة؟' },
          { h: 'اجمع عملياتك <em>في منصة واحدة.</em>', s: 'منصة مترابطة مصمَّمة لقطاع الضيافة السعودي.', cap: 'مع فندقة 360، يبدأ كل شيء من الحجز.' },
          { h: 'شاهد سير العمل في <em>فندقة 360.</em>', flow: ['الحجز', 'جاهزية الغرف', 'خدمة الضيوف', 'المحاسبة'], cap: 'الغرفة تُجهَّز، والخدمة تُسجَّل، والمحاسبة تُحدَّث في مكان واحد.' },
          { h: 'احجز عرضاً توضيحياً. <em>وثبّت سعر الإطلاق.</em>', s: 'اشترك لمدة سنتين بسعر الإطلاق، وبعد انتهاء فترة الإطلاق ستُطبَّق الأسعار الاعتيادية.', cap: 'احجز عرضك التوضيحي الآن، وثبّت سعر الإطلاق لمدة سنتين.' }
        ]
      }
    },
    C: {
      folder: 'C-opera-pms', route: 'opera-pms/', theme: 'dark', photo: 'hero-opera.webp', photoPos: '60% 50%', device: 'monitorC',
      en: {
        name: 'Opera / PMS', buyer: 'GM or owner, IT manager or director, and finance director', action: 'Operational and technology review',
        promise: 'Fewer systems around Opera. A clearer view of your hotel.', cta: 'Request an operational and technology review', chip: 'For Opera & mandated-PMS properties',
        // the hook's three separate systems are what scene 2 brings into one platform
        motif: ['HR system', 'Procurement & inventory', 'Finance & reporting'], sample: 'Sample figures',
        scenes: [
          { h: 'Running your hotel around Opera through <em>too many systems?</em>', cap: 'HR, procurement, inventory and finance, each in its own system?' },
          { h: 'One platform. <em>Opera stays your PMS.</em>', s: 'HR, procurement, inventory and finance.', cap: 'Fandaqah brings them into one platform. Your PMS doesn’t change.' },
          { h: 'Fewer systems. Clearer visibility. <em>Room to grow.</em>', list: ['Purchases flow to inventory and finance', 'One report across departments', 'Add outlets and properties'], icons: ['i-shopping-cart', 'i-chart-line', 'i-building-2'], cap: 'Less repeated work, one view of the hotel, and room to grow.' },
          { h: 'See what you can <em>simplify around Opera.</em>', checks: ['Systems in use today', 'Where work is repeated', 'PMS product & interfaces', 'What to consolidate first'], cap: 'We map your systems first and confirm compatibility before any proposal.' }
        ],
        legal: 'Oracle and OPERA are trademarks of Oracle and/or its affiliates. No certification or endorsement implied.'
      },
      ar: {
        name: 'Opera / أنظمة PMS', buyer: 'المدير العام أو المالك، ومدير التقنية، والمدير المالي', action: 'مراجعة تشغيلية وتقنية',
        promise: 'أنظمة أقل حول Opera، ورؤية أوضح لفندقك.', cta: 'اطلب مراجعة تشغيلية وتقنية', chip: 'لمنشآت Opera وأنظمة PMS المعتمدة',
        motif: ['نظام الموارد البشرية', 'المشتريات والمخزون', 'المالية والتقارير'], sample: 'أرقام توضيحية',
        scenes: [
          { h: 'تدير فندقك حول <bdi>Opera</bdi> <em>بأنظمة كثيرة؟</em>', cap: 'الموارد البشرية والمشتريات والمخزون والمالية، كلٌّ في نظام منفصل؟' },
          { h: 'منصة واحدة لعملياتك، <em>ويبقى <bdi>Opera</bdi> نظامك.</em>', s: 'الموارد البشرية والمشتريات والمخزون والمالية.', cap: 'فندقة تجمعها في منصة واحدة، ونظام PMS لديك لا يتغيّر.' },
          { h: 'أنظمة أقل، ورؤية أوضح، <em>ومساحة للنمو.</em>', list: ['المشتريات تصل إلى المخزون والمالية', 'تقرير واحد يجمع الأقسام', 'أضف منافذ ومنشآت جديدة'], icons: ['i-shopping-cart', 'i-chart-line', 'i-building-2'], cap: 'عمل مكرر أقل، ورؤية واحدة للفندق، ومساحة للنمو.' },
          { h: 'اعرف ما يمكن <em>تبسيطه حول <bdi>Opera</bdi>.</em>', checks: ['الأنظمة المستخدمة اليوم', 'أين يتكرر العمل', 'منتج PMS وواجهاته', 'ما يُوحَّد أولاً'], cap: 'نحصر أنظمتك أولاً، ونؤكّد التوافق قبل أي عرض.' }
        ],
        legal: 'Oracle وOPERA علامتان تجاريتان لشركة Oracle و/أو الشركات التابعة لها. لا يعني ذلك اعتماداً أو تأييداً.'
      }
    },
    D: {
      folder: 'D-hotel-groups', route: 'hotel-groups/', theme: 'light', photo: 'hero-groups.webp', photoPos: '62% 50%', device: 'laptopD',
      en: {
        name: 'Hotel groups', buyer: 'Group owner, CEO, COO or IT director (10+ properties)', action: 'Group discovery meeting',
        promise: 'A dedicated platform built around your group.', cta: 'Book a group discovery meeting', chip: 'For hotel groups & operators',
        motif: ['Property 1', 'Property 2', 'Property 3'],
        scenes: [
          { h: 'Managing a hotel group through <em>disconnected systems?</em>', cap: 'Every property with its own system and reports?' },
          { h: 'See your group and <em>each property clearly.</em>', s: 'Group reporting with property-level detail.', cap: 'See your whole group, and every property in detail.' },
          { h: 'Align supported workflows <em>with your brand.</em>', brand: ['Riyadh', 'Jeddah', 'Al Khobar'], cap: 'Align supported workflows with your group’s brand.' },
          { h: 'Discuss a <em>dedicated technology platform.</em>', s: 'Customised to an agreed scope, defined in your proposal.', cap: 'Book a discovery meeting. We’ll map the scope together.' }
        ]
      },
      ar: {
        name: 'مجموعات الفنادق', buyer: 'مالك المجموعة أو الرئيس التنفيذي أو مدير العمليات أو التقنية (10 منشآت فأكثر)', action: 'اجتماع استكشافي',
        promise: 'منصة مخصّصة مبنية حول مجموعتك.', cta: 'احجز اجتماعاً استكشافياً للمجموعة', chip: 'لمجموعات الفنادق والمشغّلين',
        motif: ['المنشأة 1', 'المنشأة 2', 'المنشأة 3'],
        scenes: [
          { h: 'تدير مجموعة فندقية <em>بأنظمة غير مترابطة؟</em>', cap: 'كل منشأة بنظامها وتقاريرها؟' },
          { h: 'رؤية واضحة لمجموعتك <em>ولكل منشأة.</em>', s: 'تقارير للمجموعة مع تفاصيل كل منشأة.', cap: 'شاهد مجموعتك كاملة، وكل منشأة بتفاصيلها.' },
          { h: 'وائم مسارات العمل المدعومة <em>مع هويتك.</em>', brand: ['الرياض', 'جدة', 'الخبر'], cap: 'وحّد مسارات العمل المدعومة بهوية مجموعتك.' },
          { h: 'ناقش منصة تقنية <em>مخصّصة لمجموعتك.</em>', s: 'يتم التخصيص وفق نطاق متفق عليه يحدّده عرض السعر.', cap: 'احجز اجتماعاً استكشافياً، ونرسم النطاق معاً.' }
        ]
      }
    }
  },
  tags: { en: { ui: 'Illustrative interface', brand: 'Illustrative branding', url: 'fandaqah.com' }, ar: { ui: 'واجهة توضيحية', brand: 'هوية توضيحية', url: 'fandaqah.com' } }
};
