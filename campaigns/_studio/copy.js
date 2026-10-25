/* Creative copy for the four Fandaqah 360 campaigns (playbook, 20 Oct 2026).
   Edit text here, then re-render: see campaigns/_studio/README.md.
   Rules baked in (playbook p.7 and p.11): one promise and one CTA per creative, no public price (A shows its approved
   launch price, SAR 3,495 a year excl. VAT, locked for two years with the 30-day Golden Guarantee; B "Lock in the launch price", a 2-year subscription at a fixed price), no savings figures, countdowns, reviews or limited availability. */
window.STUDIO = {
  base: '/assets/',
  campaigns: {
    A: {
      folder: 'A-existing-customers', theme: 'light', photo: 'img/hero-existing.webp', photoPos: '86% 40%', device: 'laptopA',
      en: {
        chip: 'For existing Fandaqah customers', cta: 'Lock in my launch price', url: 'fandaqah.com',
        ads: [
          { h: '<em>More value</em> from the Fandaqah you already use.', s: 'Upgrade to Fandaqah 360 for SAR 3,495 a year, excl. VAT. A one-time launch price, locked for two years.' },
          { h: 'A loyalty offer <em>built around your hotel.</em>', s: 'Try it for 30 days with the Golden Guarantee: a full refund and your previous plan back.' }
        ],
        slides: [
          { h: 'Already with Fandaqah? <em>Get more value.</em>' },
          { h: 'Connect more of <em>your daily work.</em>', s: 'Same system, same team, more of your hotel.' },
          { h: 'See what your <em>upgrade includes.</em>', list: ['Accounting', 'Purchasing', 'Housekeeping', 'Maintenance', 'Inventory'] },
          { h: 'Lock in the <em>launch price for two years.</em>', s: 'Fandaqah 360, all 20 modules: SAR 3,495 a year, excl. VAT. 30-day Golden Guarantee.' }
        ]
      },
      ar: {
        chip: 'لعملاء فندقة الحاليين', cta: 'ثبّت سعر الإطلاق', url: 'fandaqah.com',
        ads: [
          { h: '<em>قيمة أكبر</em> من فندقة التي تستخدمها اليوم.', s: 'قم بالترقية إلى فندقة 360 بسعر 3,495 ريالاً سنوياً غير شاملة الضريبة. سعر إطلاق لمرة واحدة، ثابت لمدة سنتين.' },
          { h: 'عرض ولاء <em>مصمَّم حول فندقك.</em>', s: 'جرّبها 30 يوماً مع الضمان الذهبي: استرداد كامل وعودة إلى باقتك السابقة.' }
        ],
        slides: [
          { h: 'تستخدم فندقة؟ <em>احصل على قيمة أكبر.</em>' },
          { h: 'اربط مزيداً من <em>عملك اليومي.</em>', s: 'النظام نفسه والفريق نفسه، وجزء أكبر من فندقك مترابط.' },
          { h: 'اكتشف ما <em>تشمله ترقيتك.</em>', list: ['المحاسبة', 'المشتريات', 'التدبير الفندقي', 'الصيانة', 'المخزون'] },
          { h: 'ثبّت <em>سعر الإطلاق لمدة سنتين.</em>', s: 'فندقة 360 بوحداتها العشرين: 3,495 ريالاً سنوياً غير شاملة الضريبة. ضمان ذهبي لمدة 30 يوماً.' }
        ]
      }
    },
    B: {
      folder: 'B-new-hotels', theme: 'light', photo: 'img/hero-new.webp', photoPos: '50% 50%', device: 'laptopB',
      en: {
        chip: 'For new independent hotels', cta: 'Book my hotel demo', url: 'fandaqah.com',
        ads: [
          { h: 'Your hotel operations. <em>One connected platform.</em>', s: 'Spend less time managing disconnected hotel systems.' },
          { h: 'Too many hotel systems? <em>Bring your operations together.</em>', s: 'Reservations, accounting, POS, housekeeping and more, connected in Fandaqah 360.' }
        ],
        slides: [
          { h: 'Still managing your hotel in <em>separate systems?</em>' },
          { h: 'Bring your operations <em>together.</em>', s: 'One connected platform, built for Saudi hospitality.' },
          { h: 'See the workflow in <em>Fandaqah 360.</em>', flow: ['Reservation', 'Room readiness', 'Guest service', 'Accounting'] },
          { h: 'Demo first. <em>Then lock in your price.</em>', s: 'Subscribe for 2 years at the launch price. Standard pricing applies after the launch.' }
        ]
      },
      ar: {
        chip: 'للفنادق المستقلة الجديدة', cta: 'احجز عرضاً توضيحياً لفندقي', url: 'fandaqah.com',
        ads: [
          { h: 'عمليات فندقك. <em>منصة واحدة مترابطة.</em>', s: 'وفّر الوقت الذي تقضيه في إدارة أنظمة فندقية متفرقة.' },
          { h: 'أنظمة فندقية كثيرة؟ <em>اجمع عملياتك في منصة واحدة.</em>', s: 'الحجوزات والمحاسبة ونقاط البيع والتدبير الفندقي وغيرها، مترابطة في فندقة 360.' }
        ],
        slides: [
          { h: 'ما زلت تدير فندقك <em>بأنظمة متفرقة؟</em>' },
          { h: 'اجمع عملياتك <em>في منصة واحدة.</em>', s: 'منصة مترابطة مصمَّمة لقطاع الضيافة السعودي.' },
          { h: 'شاهد سير العمل في <em>فندقة 360.</em>', flow: ['الحجز', 'جاهزية الغرف', 'خدمة الضيوف', 'المحاسبة'] },
          { h: 'احجز عرضاً توضيحياً. <em>وثبّت سعر الإطلاق.</em>', s: 'اشترك لمدة سنتين بسعر الإطلاق، وبعد انتهاء فترة الإطلاق ستُطبَّق الأسعار الاعتيادية.' }
        ]
      }
    },
    C: {
      folder: 'C-opera-pms', theme: 'dark', photo: 'img/hero-opera.webp', photoPos: '60% 50%', device: 'monitorC',
      en: {
        chip: 'For Opera & mandated-PMS properties', cta: 'Request an operational and technology review', url: 'fandaqah.com',
        // outcome first, integration second: the problem, what changes, what the hotel gets, then the review
        ads: [
          { h: 'Fewer systems around Opera. <em>A clearer view of your hotel.</em>', s: 'Bring HR, procurement, inventory, finance and compliance into one platform. Opera stays your core PMS.' },
          { h: 'Consolidate your hotel operations <em>without replacing Opera.</em>', s: 'We map your systems, costs and interfaces first, and confirm compatibility before any proposal.' }
        ],
        slides: [
          { h: 'Running your hotel around Opera through <em>too many systems?</em>' },
          { h: 'One platform. <em>Opera stays your PMS.</em>', s: 'HR, procurement, inventory, finance and compliance.' },
          { h: 'Fewer systems. Clearer visibility. <em>Room to grow.</em>', list: ['Purchases flow to inventory and finance', 'One report across departments', 'Add outlets and properties'], icons: ['i-shopping-cart', 'i-chart-line', 'i-building-2'] },
          { h: 'See what you can <em>simplify around Opera.</em>', s: 'We map your systems first and confirm compatibility before any proposal.' }
        ],
        legal: 'Oracle and OPERA are trademarks of Oracle and/or its affiliates. No certification or endorsement implied.'
      },
      ar: {
        chip: 'لمنشآت Opera وأنظمة PMS المعتمدة', cta: 'اطلب مراجعة تشغيلية وتقنية', url: 'fandaqah.com',
        ads: [
          { h: 'أنظمة أقل حول <bdi>Opera</bdi>، <em>ورؤية أوضح لفندقك.</em>', s: 'اجمع الموارد البشرية والمشتريات والمخزون والمالية والامتثال في منصة واحدة، ويبقى <bdi>Opera</bdi> نظامك الأساسي.' },
          { h: 'وحّد عمليات فندقك <em>دون استبدال <bdi>Opera</bdi>.</em>', s: 'نحصر أنظمتك وتكاليفك وواجهاتك أولاً، ونؤكّد التوافق قبل أي عرض.' }
        ],
        slides: [
          { h: 'تدير فندقك حول <bdi>Opera</bdi> <em>بأنظمة كثيرة؟</em>' },
          { h: 'منصة واحدة لعملياتك، <em>ويبقى <bdi>Opera</bdi> نظامك.</em>', s: 'الموارد البشرية والمشتريات والمخزون والمالية والامتثال.' },
          { h: 'أنظمة أقل، ورؤية أوضح، <em>ومساحة للنمو.</em>', list: ['المشتريات تصل إلى المخزون والمالية', 'تقرير واحد يجمع الأقسام', 'أضف منافذ ومنشآت جديدة'], icons: ['i-shopping-cart', 'i-chart-line', 'i-building-2'] },
          { h: 'اعرف ما يمكن <em>تبسيطه حول <bdi>Opera</bdi>.</em>', s: 'نحصر أنظمتك أولاً، ونؤكّد التوافق قبل أي عرض.' }
        ],
        legal: 'Oracle وOPERA علامتان تجاريتان لشركة Oracle و/أو الشركات التابعة لها. لا يعني ذلك اعتماداً أو تأييداً.'
      }
    },
    D: {
      folder: 'D-hotel-groups', theme: 'light', photo: 'img/hero-groups.webp', photoPos: '62% 50%', device: 'laptopD',
      en: {
        chip: 'For hotel groups & operators', cta: 'Book a group discovery meeting', url: 'fandaqah.com',
        ads: [
          { h: 'A dedicated hotel technology platform <em>built around your group.</em>', s: 'Agreed branding, workflows, integrations and group reporting, with one accountable partner.' },
          { h: 'Your brand. Your policies. <em>Your hotel technology.</em>', s: 'Dedicated deployment and branding to an agreed scope.' }
        ],
        slides: [
          { h: 'Managing a hotel group through <em>disconnected systems?</em>' },
          { h: 'See your group and <em>each property clearly.</em>', s: 'Group reporting with property-level detail.' },
          { h: 'Align supported workflows <em>with your brand.</em>', group: true },
          { h: 'Discuss a <em>dedicated technology platform.</em>', s: 'Customised to an agreed scope, defined in your proposal.' }
        ]
      },
      ar: {
        chip: 'لمجموعات الفنادق والمشغّلين', cta: 'احجز اجتماعاً استكشافياً للمجموعة', url: 'fandaqah.com',
        ads: [
          { h: 'منصة تقنية فندقية مخصّصة <em>مبنية حول مجموعتك.</em>', s: 'هوية ومسارات عمل وتكاملات وتقارير للمجموعة، مع شريك تقني واحد مسؤول.' },
          { h: 'هويتك. سياساتك. <em>تقنيتك الفندقية.</em>', s: 'نشر مخصّص وهوية خاصة وفق نطاق متفق عليه.' }
        ],
        slides: [
          { h: 'تدير مجموعة فندقية <em>بأنظمة غير مترابطة؟</em>' },
          { h: 'رؤية واضحة لمجموعتك <em>ولكل منشأة.</em>', s: 'تقارير للمجموعة مع تفاصيل كل منشأة.' },
          { h: 'وائم مسارات العمل المدعومة <em>مع هويتك.</em>', group: true },
          { h: 'ناقش منصة تقنية <em>مخصّصة لمجموعتك.</em>', s: 'يتم التخصيص وفق نطاق متفق عليه يحدّده عرض السعر.' }
        ]
      }
    }
  },
  /* link-preview images for the site (1200x630, English) */
  og: {
    home: { c: 'A', h: 'Built for Saudi hospitality. <em>Choose your route.</em>', chip: 'Fandaqah 360' },
    'existing-customers': { c: 'A', h: '<em>More value</em> from the Fandaqah you already use.', chip: 'For existing Fandaqah customers' },
    'new-hotels': { c: 'B', h: 'Your hotel operations. <em>One connected platform.</em>', chip: 'For new independent hotels' },
    'opera-pms': { c: 'C', h: 'Fewer systems around Opera. <em>A clearer view of your hotel.</em>', chip: 'For Opera & mandated-PMS properties' },
    'hotel-groups': { c: 'D', h: 'A dedicated platform <em>built around your group.</em>', chip: 'For hotel groups & operators' },
    book: { c: 'B', h: 'Campaign Book. <em>Every reel and banner in one link.</em>', chip: 'Fandaqah 360 · Campaign Book' }
  },
  tags: { en: { ui: 'Illustrative interface', brand: 'Illustrative branding' }, ar: { ui: 'واجهة توضيحية', brand: 'هوية توضيحية' } }
};
