/* Campaign book data: chapter facts and the 25-second reel scripts (playbook storyboards, 20 Oct 2026).
   Beats follow the playbook timing: hook 0–3 s, solution 3–10 s, proof 10–20 s, call to action 20–25 s. */
window.BOOK_DATA = {
  beats: [3, 7, 10, 5],
  campaigns: {
    A: {
      folder: 'A-existing-customers', route: 'existing-customers/', theme: 'light', photo: 'hero-existing.webp', photoPos: '86% 40%', device: 'laptopA',
      en: {
        name: 'Existing customers', buyer: 'Owner, GM or finance manager already on Fandaqah', action: 'Upgrade review',
        promise: 'More value from the Fandaqah you already use.', cta: 'Check my upgrade offer', chip: 'For existing Fandaqah customers',
        motif: ['Spreadsheet', 'Paper log', 'Manual report'],
        scenes: [
          { h: 'Already with Fandaqah? <em>Get more value.</em>', cap: 'Already with Fandaqah? There’s more value waiting.' },
          { h: 'Connect more of <em>your daily work.</em>', s: 'Accounting, purchasing and housekeeping in the system you already use.', cap: 'Connect accounting and purchasing to the system you know.' },
          { h: 'See what your <em>upgrade includes.</em>', list: ['Accounting', 'Purchasing', 'Housekeeping'], cap: 'See each step flow into the next, without entering it twice.' },
          { h: 'Check your <em>two-year upgrade offer.</em>', s: 'Your review confirms eligibility, modules, fees and any approved credit.', cap: 'Request your upgrade review. Ask about our two-year offer.' }
        ]
      },
      ar: {
        name: 'العملاء الحاليون', buyer: 'المالك أو المدير العام أو المدير المالي من عملاء فندقة', action: 'مراجعة الترقية',
        promise: 'قيمة أكبر من فندقة التي تستخدمها اليوم.', cta: 'تحقّق من عرض الترقية', chip: 'لعملاء فندقة الحاليين',
        motif: ['جدول بيانات', 'سجل ورقي', 'تقرير يدوي'],
        scenes: [
          { h: 'تستخدم فندقة؟ <em>احصل على قيمة أكبر.</em>', cap: 'تستخدم فندقة؟ هناك قيمة أكبر بانتظارك.' },
          { h: 'اربط مزيداً من <em>عملك اليومي.</em>', s: 'المحاسبة والمشتريات والتدبير الفندقي في النظام الذي تستخدمه.', cap: 'اربط المحاسبة والمشتريات بالنظام الذي تعرفه.' },
          { h: 'اكتشف ما <em>تشمله ترقيتك.</em>', list: ['المحاسبة', 'المشتريات', 'التدبير الفندقي'], cap: 'شاهد كيف تنتقل العملية من خطوة إلى أخرى دون إدخال مكرر.' },
          { h: 'تحقّق من <em>عرض الترقية لمدة سنتين.</em>', s: 'تؤكّد المراجعة أهليتك والوحدات والرسوم وأي رصيد معتمد.', cap: 'اطلب مراجعة الترقية، واسأل عن عرضنا لمدة سنتين.' }
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
          { h: 'Book a demo. <em>Review the two-year offer.</em>', s: 'Ask about our two-year prepaid offer.', cap: 'Book a demo for your hotel. Ask about our two-year offer.' }
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
          { h: 'احجز عرضاً توضيحياً. <em>واطّلع على عرض السنتين.</em>', s: 'اسأل عن عرضنا المدفوع مسبقاً لمدة سنتين.', cap: 'احجز عرضاً توضيحياً لفندقك، واسأل عن عرضنا لمدة سنتين.' }
        ]
      }
    },
    C: {
      folder: 'C-opera-pms', route: 'opera-pms/', theme: 'dark', photo: 'hero-opera.webp', photoPos: '60% 50%', device: 'monitorC',
      en: {
        name: 'Opera / PMS', buyer: 'IT and finance leaders, with the GM or owner', action: 'Technology cost review',
        promise: 'Strengthen the workflows around your Opera PMS.', cta: 'Request a technology cost review', chip: 'For Opera & mandated-PMS properties',
        motif: ['Annual licence', 'Interfaces', 'Support (AMC)'], sample: 'Sample figures',
        scenes: [
          { h: 'How much does your hotel technology <em>really cost?</em>', cap: 'What do you really pay for your hotel technology each year?' },
          { h: 'Strengthen the workflows <em>around Opera.</em>', s: 'Your existing PMS stays at the centre.', cap: 'Your existing PMS stays. We strengthen what’s around it.' },
          { h: 'Connect back office, operations and <em>supported integrations.</em>', toast: 'Supported workflow · back-office posting', cap: 'Back office, operations and supported integrations, connected.' },
          { h: 'Request your <em>technology cost review.</em>', checks: ['PMS product & version', 'Deployment & interfaces', 'Required workflows', 'Approval route'], cap: 'Request a cost review. Compatibility is confirmed after assessment.' }
        ],
        legal: 'Oracle and OPERA are trademarks of Oracle and/or its affiliates. No certification or endorsement implied.'
      },
      ar: {
        name: 'Opera / أنظمة PMS', buyer: 'قادة التقنية والمالية مع المدير العام أو المالك', action: 'مراجعة تكاليف التقنية',
        promise: 'عزّز مسارات العمل حول نظام Opera PMS لديك.', cta: 'اطلب مراجعة تكاليف التقنية', chip: 'لمنشآت Opera وأنظمة PMS المعتمدة',
        motif: ['ترخيص سنوي', 'الواجهات', 'الدعم والصيانة'], sample: 'أرقام توضيحية',
        scenes: [
          { h: 'كم تكلّف تقنيات فندقك <em>فعلاً؟</em>', cap: 'كم تدفع فعلاً على تقنيات فندقك كل عام؟' },
          { h: 'عزّز مسارات العمل <em>حول <bdi>Opera</bdi>.</em>', s: 'يبقى نظام PMS الحالي في قلب المنظومة.', cap: 'نظامك الحالي يبقى كما هو، ونقوّي ما حوله.' },
          { h: 'اربط الإدارة الخلفية والتشغيل <em>والتكاملات المدعومة.</em>', toast: 'مسار عمل مدعوم · ترحيل للإدارة الخلفية', cap: 'نربط الإدارة الخلفية والتشغيل والتكاملات المدعومة.' },
          { h: 'اطلب <em>مراجعة تكاليف التقنية.</em>', checks: ['منتج PMS وإصداره', 'التشغيل والواجهات', 'مسارات العمل المطلوبة', 'مسار الاعتماد'], cap: 'اطلب مراجعة التكاليف، ونؤكّد التوافق بعد التقييم الفني.' }
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
