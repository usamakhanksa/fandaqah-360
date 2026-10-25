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
        name: 'New hotels', buyer: 'Independent owner or GM', action: 'A walkthrough: a simpler way to run the hotel',
        promise: 'An easier way to run your hotel.', cta: 'Explore a simpler way to run my hotel', chip: 'For new independent hotels',
        // the hook's three separate vendors are what scene 2 replaces with one platform
        motif: ['Booking system', 'Accounts software', 'Restaurant till'],
        scenes: [
          { h: 'Still running your hotel on <em>separate systems?</em>', cap: 'Several vendors, the same data typed twice, and no clear picture?' },
          { h: 'Run more of your hotel <em>from one platform.</em>', s: 'Fewer systems and vendors to manage.', cap: 'Less to manage, fewer vendors, and one place to see it all.' },
          { h: 'Enter it once. <em>See it everywhere.</em>', flow: ['Reservation', 'Room readiness', 'Guest service', 'Accounting'], cap: 'One booking updates rooms, service and accounts, with no re-typing.' },
          { h: 'An easier way <em>to run your hotel.</em>', s: 'Start at the launch price, with a 30-day money-back guarantee. Standard pricing applies after the launch.', cap: 'See how much simpler your hotel can run, at the launch price.' }
        ]
      },
      ar: {
        name: 'الفنادق الجديدة', buyer: 'مالك أو مدير عام فندق مستقل', action: 'جولة: طريقة أبسط لإدارة الفندق',
        promise: 'طريقة أسهل لإدارة فندقك.', cta: 'اكتشف طريقة أبسط لإدارة فندقي', chip: 'للفنادق المستقلة الجديدة',
        motif: ['نظام الحجوزات', 'برنامج الحسابات', 'نقاط بيع المطعم'],
        scenes: [
          { h: 'ما زلت تدير فندقك <em>بأنظمة متفرقة؟</em>', cap: 'عدة موردين، وبيانات تُدخل مرتين، ولا صورة واضحة؟' },
          { h: 'أدِر جزءاً أكبر من فندقك <em>من منصة واحدة.</em>', s: 'أنظمة وموردون أقل تديرهم.', cap: 'إدارة أقل، وموردون أقل، ومكان واحد ترى فيه كل شيء.' },
          { h: 'أدخِل البيانات مرة واحدة. <em>وشاهدها في كل مكان.</em>', flow: ['الحجز', 'جاهزية الغرف', 'خدمة الضيوف', 'المحاسبة'], cap: 'حجز واحد يحدّث الغرف والخدمة والحسابات، دون إعادة إدخال.' },
          { h: 'طريقة أسهل <em>لإدارة فندقك.</em>', s: 'ابدأ بسعر الإطلاق مع ضمان استرداد المال خلال 30 يوماً، وتُطبَّق الأسعار الاعتيادية بعد انتهاء الإطلاق.', cap: 'اكتشف كم يمكن أن يصبح تشغيل فندقك أبسط، بسعر الإطلاق.' }
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
        name: 'Hotel groups', buyer: 'Group owner, CEO, COO or IT director (10+ properties)', action: 'Group discovery workshop',
        promise: 'Centralised visibility across every property.', cta: 'Explore centralised management for my group', chip: 'For hotel groups & operators',
        motif: ['Property 1', 'Property 2', 'Property 3'],
        scenes: [
          { h: 'Many properties. Many systems. <em>No single view.</em>', cap: 'Every property with its own systems, processes and reports?' },
          { h: 'Centralised visibility <em>across every property.</em>', s: 'One environment for every property.', cap: 'See the whole portfolio, and any property in detail, from one place.' },
          { h: 'Standardise operations <em>across your portfolio.</em>', brand: ['Riyadh', 'Jeddah', 'Al Khobar'], cap: 'One set of standards, applied group-wide.' },
          { h: 'Grow the portfolio. <em>Keep control.</em>', s: 'One operating environment for every property, scoped to your group.', cap: 'Explore how your group can centralise operations and see the whole portfolio.' }
        ]
      },
      ar: {
        name: 'مجموعات الفنادق', buyer: 'مالك المجموعة أو الرئيس التنفيذي أو مدير العمليات أو التقنية (10 منشآت فأكثر)', action: 'ورشة استكشافية للمجموعة',
        promise: 'رؤية مركزية لكل منشآتك.', cta: 'استكشف الإدارة المركزية لمجموعتي', chip: 'لمجموعات الفنادق والمشغّلين',
        motif: ['المنشأة 1', 'المنشأة 2', 'المنشأة 3'],
        scenes: [
          { h: 'منشآت كثيرة، وأنظمة كثيرة، <em>ولا رؤية واحدة.</em>', cap: 'كل منشأة بأنظمتها وإجراءاتها وتقاريرها؟' },
          { h: 'رؤية مركزية <em>لكل منشآتك.</em>', s: 'بيئة إدارة واحدة للمحفظة كاملة.', cap: 'شاهد محفظتك كاملة، وأي منشأة بتفاصيلها، من مكان واحد.' },
          { h: 'وحّد العمليات <em>عبر محفظتك كاملة.</em>', brand: ['الرياض', 'جدة', 'الخبر'], cap: 'معايير واحدة تُطبَّق على المجموعة كلها.' },
          { h: 'توسّع في محفظتك. <em>وحافظ على السيطرة.</em>', s: 'بيئة تشغيل واحدة لكل منشأة، وفق نطاق يناسب مجموعتك.', cap: 'اكتشف كيف تدير مجموعتك عملياتها مركزياً وترى محفظتها كاملة.' }
        ]
      }
    }
  },
  tags: { en: { ui: 'Illustrative interface', brand: 'Illustrative branding', url: 'fandaqah.com' }, ar: { ui: 'واجهة توضيحية', brand: 'هوية توضيحية', url: 'fandaqah.com' } }
};
