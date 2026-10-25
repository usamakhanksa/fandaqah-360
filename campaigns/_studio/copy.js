/* Creative copy for the four Fandaqah 360 campaigns (playbook, 20 Oct 2026).
   Edit text here, then re-render: see campaigns/_studio/README.md.
   Rules baked in (playbook p.7 and p.11): one promise and one CTA per creative, no public price (A uses
   "Ask about our limited-time offer", B "Lock in the launch price", a 2-year subscription at a fixed price), no savings figures, countdowns, reviews or limited availability. */
window.STUDIO = {
  base: '/assets/',
  campaigns: {
    A: {
      folder: 'A-existing-customers', theme: 'light', photo: 'img/hero-existing.webp', photoPos: '86% 40%', device: 'laptopA',
      en: {
        chip: 'For existing Fandaqah customers', cta: 'Check my upgrade offer', url: 'fandaqah.com',
        ads: [
          { h: '<em>More value</em> from the Fandaqah you already use.', s: 'Review your subscription, the modules you need and your eligibility for our limited-time offer.' },
          { h: 'A loyalty offer <em>built around your hotel.</em>', s: 'Connect more of your daily operations. Ask about our limited-time offer.' }
        ],
        slides: [
          { h: 'Already with Fandaqah? <em>Get more value.</em>' },
          { h: 'Connect more of <em>your daily work.</em>', s: 'Same system, same team, more of your hotel.' },
          { h: 'See what your <em>upgrade includes.</em>', list: ['Accounting', 'Purchasing', 'Housekeeping', 'Maintenance', 'Inventory'] },
          { h: 'Check your <em><span style="white-space:nowrap">limited-time</span> upgrade offer.</em>', s: 'Your review confirms eligibility, the modules you need, fees and any approved credit.' }
        ]
      },
      ar: {
        chip: 'لعملاء فندقة الحاليين', cta: 'تحقّق من عرض الترقية', url: 'fandaqah.com',
        ads: [
          { h: '<em>قيمة أكبر</em> من فندقة التي تستخدمها اليوم.', s: 'راجع اشتراكك والوحدات التي تحتاجها وأهليتك لعرضنا لفترة محدودة.' },
          { h: 'عرض ولاء <em>مصمَّم حول فندقك.</em>', s: 'اربط مزيداً من عملياتك اليومية، واسأل عن عرضنا لفترة محدودة.' }
        ],
        slides: [
          { h: 'تستخدم فندقة؟ <em>احصل على قيمة أكبر.</em>' },
          { h: 'اربط مزيداً من <em>عملك اليومي.</em>', s: 'النظام نفسه والفريق نفسه، وجزء أكبر من فندقك مترابط.' },
          { h: 'اكتشف ما <em>تشمله ترقيتك.</em>', list: ['المحاسبة', 'المشتريات', 'التدبير الفندقي', 'الصيانة', 'المخزون'] },
          { h: 'تحقّق من <em>عرض الترقية لفترة محدودة.</em>', s: 'تؤكّد المراجعة أهليتك والوحدات التي تحتاجها والرسوم وأي رصيد معتمد.' }
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
          { h: 'Demo first. <em>Then lock in your price.</em>', s: 'One-time launch offer: subscribe for 2 years and your price stays fixed for both.' }
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
          { h: 'احجز عرضاً توضيحياً. <em>وثبّت سعر الإطلاق.</em>', s: 'عرض إطلاق لمرة واحدة: اشترك لمدة سنتين، ويبقى سعرك ثابتاً طوال المدة.' }
        ]
      }
    },
    C: {
      folder: 'C-opera-pms', theme: 'dark', photo: 'img/hero-opera.webp', photoPos: '60% 50%', device: 'monitorC',
      en: {
        chip: 'For Opera & mandated-PMS properties', cta: 'Request a technology cost review', url: 'fandaqah.com',
        ads: [
          { h: 'Strengthen your Opera PMS with <em>connected hotel operations.</em>', s: 'We review your current costs, required interfaces and approval process before preparing a proposal.' },
          { h: 'See where your hotel can <em>reduce technology costs.</em>', s: 'Build a like-for-like view of your current and proposed scope.' }
        ],
        slides: [
          { h: 'How much does your hotel technology <em>really cost?</em>' },
          { h: 'Strengthen the workflows <em>around Opera.</em>', s: 'Your existing PMS stays at the centre.' },
          { h: 'Connect back office, operations and <em>supported integrations.</em>', pms: true },
          { h: 'Request your <em>technology cost review.</em>', s: 'Compatibility and delivery are confirmed after technical assessment.' }
        ],
        legal: 'Oracle and OPERA are trademarks of Oracle and/or its affiliates. No certification or endorsement implied.'
      },
      ar: {
        chip: 'لمنشآت Opera وأنظمة PMS المعتمدة', cta: 'اطلب مراجعة تكاليف التقنية', url: 'fandaqah.com',
        ads: [
          { h: 'عزّز نظام <bdi>Opera PMS</bdi> لديك <em>بعمليات فندقية مترابطة.</em>', s: 'نراجع تكاليفك الحالية والواجهات المطلوبة ومسار الاعتماد قبل إعداد عرض السعر.' },
          { h: 'اكتشف أين يمكن لفندقك <em>خفض تكاليف التقنية.</em>', s: 'كوِّن صورة مقارنة دقيقة بين نطاقك الحالي والنطاق المقترح.' }
        ],
        slides: [
          { h: 'كم تكلّف تقنيات فندقك <em>فعلاً؟</em>' },
          { h: 'عزّز مسارات العمل <em>حول <bdi>Opera</bdi>.</em>', s: 'يبقى نظام PMS الحالي في قلب المنظومة.' },
          { h: 'اربط الإدارة الخلفية والتشغيل <em>والتكاملات المدعومة.</em>', pms: true },
          { h: 'اطلب <em>مراجعة تكاليف التقنية.</em>', s: 'يُؤكَّد التوافق والتسليم بعد التقييم الفني.' }
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
    'opera-pms': { c: 'C', h: 'Strengthen the workflows around your <em>Opera PMS.</em>', chip: 'For Opera & mandated-PMS properties' },
    'hotel-groups': { c: 'D', h: 'A dedicated platform <em>built around your group.</em>', chip: 'For hotel groups & operators' },
    book: { c: 'B', h: 'Campaign Book. <em>Every reel and banner in one link.</em>', chip: 'Fandaqah 360 · Campaign Book' }
  },
  tags: { en: { ui: 'Illustrative interface', brand: 'Illustrative branding' }, ar: { ui: 'واجهة توضيحية', brand: 'هوية توضيحية' } }
};
