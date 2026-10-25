/* Creative copy for the four Fandaqah 360 campaigns (playbook, 20 Oct 2026).
   Edit text here, then re-render: see campaigns/_studio/README.md.
   Rules baked in (playbook p.7 and p.11): one promise and one CTA per creative, no public price (A shows its approved
   launch price, SAR 3,495 a year excl. VAT, 1-year subscription with an optional second year at the same rate, 30-day Golden Guarantee; B names its launch price without the figure: a 1-year subscription with a 30-day money-back guarantee), no savings figures, countdowns, reviews or limited availability. */
window.STUDIO = {
  base: '/assets/',
  campaigns: {
    A: {
      folder: 'A-existing-customers', theme: 'light', photo: 'img/hero-existing.webp', photoPos: '86% 40%', device: 'laptopA',
      en: {
        chip: 'For existing Fandaqah customers', cta: 'Reserve launch pricing', url: 'fandaqah.com',
        ads: [
          { h: '<em>More value</em> from the Fandaqah you already use.', s: 'Upgrade to Fandaqah 360 for SAR 3,495 a year, excl. VAT, with the option of a second year at the same launch price.' },
          { h: 'A loyalty offer <em>built around your hotel.</em>', s: 'Try it for 30 days with the Golden Guarantee: a full refund and your previous plan back.' }
        ],
        slides: [
          { h: 'Already with Fandaqah? <em>Get more value.</em>' },
          { h: 'Connect more of <em>your daily work.</em>', s: 'Same system, same team, more of your hotel.' },
          { h: 'See what your <em>upgrade includes.</em>', list: ['Accounting', 'Purchasing', 'Housekeeping', 'Maintenance', 'Inventory'] },
          { h: 'The launch price, <em>plus a year at the same rate.</em>', s: 'Fandaqah 360 with all available features and services: SAR 3,495 / year, excl. VAT. 30‑day Golden Guarantee.' }
        ]
      },
      ar: {
        chip: 'لعملاء فندقة الحاليين', cta: 'احجز سعر الإطلاق', url: 'fandaqah.com',
        ads: [
          { h: '<em>قيمة أكبر</em> من فندقة التي تستخدمها اليوم.', s: 'قم بالترقية إلى فندقة 360 بسعر 3,495 ريالاً سنوياً غير شاملة الضريبة، مع خيار سنة ثانية بسعر الإطلاق نفسه.' },
          { h: 'عرض ولاء <em>مصمَّم حول فندقك.</em>', s: 'جرّبها 30 يوماً مع الضمان الذهبي: استرداد كامل وعودة إلى باقتك السابقة.' }
        ],
        slides: [
          { h: 'تستخدم فندقة؟ <em>احصل على قيمة أكبر.</em>' },
          { h: 'اربط مزيداً من <em>عملك اليومي.</em>', s: 'النظام نفسه والفريق نفسه، وجزء أكبر من فندقك مترابط.' },
          { h: 'اكتشف ما <em>تشمله ترقيتك.</em>', list: ['المحاسبة', 'المشتريات', 'التدبير الفندقي', 'الصيانة', 'المخزون'] },
          { h: 'سعر الإطلاق الآن، <em>وسنة إضافية بالسعر نفسه.</em>', s: 'فندقة 360 بجميع المزايا والخدمات المتوفرة: 3,495 ريال / سنوياً غير شاملة الضريبة. ضمان ذهبي لمدة 30 يوماً.' }
        ]
      }
    },
    B: {
      folder: 'B-new-hotels', theme: 'light', photo: 'img/hero-new.webp', photoPos: '50% 50%', device: 'laptopB',
      en: {
        chip: 'For new independent hotels', cta: 'Explore a simpler way to run my hotel', url: 'fandaqah.com',
        // benefit first: the owner's day gets easier; the product is how, not what
        ads: [
          { h: 'An easier way <em>to run your hotel.</em>', s: 'Manage more of your hotel from one platform, with less manual work and fewer vendors.' },
          { h: 'Too many systems for one hotel? <em>Bring them into one place.</em>', s: 'Less re-typing, clearer reports and one team to call for support.' }
        ],
        slides: [
          { h: 'Still running your hotel on <em>separate systems?</em>' },
          { h: 'Run more of your hotel <em>from one platform.</em>', s: 'Fewer systems and vendors to manage.' },
          { h: 'Enter it once. <em>See it everywhere.</em>', flow: ['Reservation', 'Room readiness', 'Guest service', 'Accounting'] },
          { h: 'An easier way <em>to run your hotel.</em>', s: 'Start at the launch price, with a 30-day money-back guarantee. Standard pricing applies after the launch.' }
        ]
      },
      ar: {
        chip: 'للفنادق المستقلة الجديدة', cta: 'اكتشف طريقة أبسط لإدارة فندقي', url: 'fandaqah.com',
        ads: [
          { h: 'طريقة أسهل <em>لإدارة فندقك.</em>', s: 'أدِر جزءاً أكبر من فندقك من منصة واحدة، بعمل يدوي وموردين أقل.' },
          { h: 'أنظمة كثيرة لفندق واحد؟ <em>اجمعها في مكان واحد.</em>', s: 'إدخال متكرر أقل، وتقارير أوضح، وفريق واحد للدعم.' }
        ],
        slides: [
          { h: 'ما زلت تدير فندقك <em>بأنظمة متفرقة؟</em>' },
          { h: 'أدِر جزءاً أكبر من فندقك <em>من منصة واحدة.</em>', s: 'أنظمة وموردون أقل تديرهم.' },
          { h: 'أدخِل البيانات مرة واحدة. <em>وشاهدها في كل مكان.</em>', flow: ['الحجز', 'جاهزية الغرف', 'خدمة الضيوف', 'المحاسبة'] },
          { h: 'طريقة أسهل <em>لإدارة فندقك.</em>', s: 'ابدأ بسعر الإطلاق مع ضمان استرداد المال خلال 30 يوماً، وتُطبَّق الأسعار الاعتيادية بعد انتهاء الإطلاق.' }
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
        chip: 'For hotel groups & operators', cta: 'Explore centralised management for my group', url: 'fandaqah.com',
        // portfolio and executive outcomes: visibility, standards, governance, growth; never single-property features
        ads: [
          { h: 'Centralised visibility <em>across every property.</em>', s: 'One management environment for the whole portfolio, with group reporting and standard processes.' },
          { h: 'Grow your portfolio. <em>Keep control.</em>', s: 'Standardise operations and governance across every property, scoped to your group.' }
        ],
        slides: [
          { h: 'Many properties. Many systems. <em>No single view.</em>' },
          { h: 'Centralised visibility <em>across every property.</em>', s: 'One management environment for the whole portfolio.' },
          { h: 'Standardise operations <em>across your portfolio.</em>', group: true },
          { h: 'Grow the portfolio. <em>Keep control.</em>', s: 'One operating environment for every property, scoped to your group.' }
        ]
      },
      ar: {
        chip: 'لمجموعات الفنادق والمشغّلين', cta: 'استكشف الإدارة المركزية لمجموعتي', url: 'fandaqah.com',
        ads: [
          { h: 'رؤية مركزية <em>لكل منشآتك.</em>', s: 'بيئة إدارة واحدة للمحفظة كاملة، بتقارير للمجموعة وإجراءات موحّدة.' },
          { h: 'توسّع في محفظتك. <em>وحافظ على السيطرة.</em>', s: 'وحّد العمليات والحوكمة عبر كل منشأة، وفق نطاق يناسب مجموعتك.' }
        ],
        slides: [
          { h: 'منشآت كثيرة، وأنظمة كثيرة، <em>ولا رؤية واحدة.</em>' },
          { h: 'رؤية مركزية <em>لكل منشآتك.</em>', s: 'بيئة إدارة واحدة للمحفظة كاملة.' },
          { h: 'وحّد العمليات <em>عبر محفظتك كاملة.</em>', group: true },
          { h: 'توسّع في محفظتك. <em>وحافظ على السيطرة.</em>', s: 'بيئة تشغيل واحدة لكل منشأة، وفق نطاق يناسب مجموعتك.' }
        ]
      }
    }
  },
  /* link-preview images for the site (1200x630, English) */
  og: {
    home: { c: 'A', h: 'Built for Saudi hospitality. <em>Choose your route.</em>', chip: 'Fandaqah 360' },
    'existing-customers': { c: 'A', h: '<em>More value</em> from the Fandaqah you already use.', chip: 'For existing Fandaqah customers' },
    'new-hotels': { c: 'B', h: 'An easier way <em>to run your hotel.</em>', chip: 'For new independent hotels' },
    'opera-pms': { c: 'C', h: 'Fewer systems around Opera. <em>A clearer view of your hotel.</em>', chip: 'For Opera & mandated-PMS properties' },
    'hotel-groups': { c: 'D', h: 'Centralised visibility <em>across every property.</em>', chip: 'For hotel groups & operators' },
    book: { c: 'B', h: 'Campaign Book. <em>Every reel and banner in one link.</em>', chip: 'Fandaqah 360 · Campaign Book' }
  },
  tags: { en: { ui: 'Illustrative interface', brand: 'Illustrative branding' }, ar: { ui: 'واجهة توضيحية', brand: 'هوية توضيحية' } }
};
