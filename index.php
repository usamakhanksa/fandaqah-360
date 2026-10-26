<?php /* Generated from index.html by tools/build-php.py: edit the .html page, then run the build. */ require __DIR__ . '/api/page.php'; ?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8"><meta name="fq-lead-proxy" content="api/lead.php">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
/* One shared campaign URL (playbook p.8): ?utm_campaign=A…D or ?segment=A…D opens the matching route, tags preserved */
(function () {
  var q = new URLSearchParams(location.search), m = /^\s*([abcd])(?:$|[\s_\-:.])/i.exec(q.get('utm_campaign') || q.get('segment') || '');
  if (!m) return;
  window.FQ_REDIRECTING = true;
  try { if (document.referrer) sessionStorage.setItem('fq360_ref', document.referrer); } catch (e) {}
  location.replace({ A: 'existing-customers/', B: 'new-hotels/', C: 'opera-pms/', D: 'hotel-groups/' }[m[1].toUpperCase()] + location.search + location.hash);
})();
</script>
<title>Fandaqah 360 | Built for Saudi hospitality</title>
<meta name="description" content="Fandaqah 360 connects hotel operations, finance, guest services and supported regulatory workflows. Choose your route: existing customers, new hotels, Opera and mandated-PMS properties, or hotel groups.">
<link rel="canonical" href="https://usamakhanksa.github.io/fandaqah-360/">
<meta name="theme-color" content="#F0EDE9">
<link rel="icon" href="favicon.ico">
<link rel="apple-touch-icon" href="assets/img/icon-180.png">
<link rel="manifest" href="site.webmanifest">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Fandaqah 360">
<meta property="og:title" content="Fandaqah 360 | Built for Saudi hospitality">
<meta property="og:description" content="One connected platform for hotel operations, finance, guest services and supported regulatory workflows.">
<meta property="og:url" content="https://usamakhanksa.github.io/fandaqah-360/">
<meta property="og:image" content="https://usamakhanksa.github.io/fandaqah-360/assets/img/og/home.jpg">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@fandaqah">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Organization","name":"Fandaqah","alternateName":"فندقة","url":"https://www.fandaqah.com","email":"info@fandaqah.com","telephone":"+966920066456","slogan":"Your Hospitality, Our Priority",
 "address":{"@type":"PostalAddress","streetAddress":"Prince Turki Street, Aldughaither Village","addressLocality":"Al Khobar","addressCountry":"SA"},
 "sameAs":["https://www.linkedin.com/company/fandaqah/","https://x.com/fandaqah","https://www.instagram.com/fandaqah.pms","https://www.facebook.com/pmsfandaqah/"]}
</script>
<link rel="preload" href="assets/fonts/source-serif-4-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/fonts/inter-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/img/hero-existing.webp" as="image" type="image/webp">
<link rel="stylesheet" href="assets/css/site.css">
<script>/* Arabic by default: English only when the link asks (?lang=en) or the reader chose it before. The page stays hidden until its text is in that language, with a 1.5 s safety net. */(function(r){var l;try{l=new URLSearchParams(location.search).get('lang')||localStorage.getItem('fq360-lang')}catch(e){}r.classList.add('js');if(l!=='en'){r.lang='ar';r.dir='rtl';r.classList.add('i18n-wait');setTimeout(function(){r.classList.remove('i18n-wait')},1500)}})(document.documentElement)</script><style>.i18n-wait body{visibility:hidden}</style>
</head>
<body>
<a class="skip" href="#main" data-i18n="skip">Skip to content</a>

<header class="site-head">
  <div class="wrap head-in">
    <a class="logo" href="./" data-i18n-aria="aria.home" aria-label="Fandaqah 360 home"><img src="assets/img/brand-dark-340.webp" width="340" height="146" alt="Fandaqah"><span class="logo-360" aria-hidden="true">360</span></a>
    <nav class="nav" data-i18n-aria="aria.main" aria-label="Main">
      <a href="#solutions" data-i18n="nav.solutions">Solutions</a>
      <a href="#how" data-i18n="nav.how">How it works</a>
      <a href="mailto:info@fandaqah.com" data-i18n="nav.contact">Contact</a>
    </nav>
    <div class="head-end">
      <div class="lang" role="group" data-i18n-aria="aria.lang" aria-label="Language"><button type="button" data-lang="en" lang="en" aria-pressed="true">EN</button><span class="sep" aria-hidden="true"></span><button type="button" data-lang="ar" lang="ar" aria-pressed="false">عربي</button></div>
      <a class="btn btn-sm" href="#solutions" data-i18n="cta.main">Find my route</a>
      <button class="burger" type="button" aria-expanded="false" aria-controls="mnav" data-i18n-aria="aria.menu" aria-label="Open menu"><svg class="ic" aria-hidden="true"><use href="assets/icons.svg#i-menu"/></svg></button>
    </div>
  </div>
  <div class="mnav" id="mnav" hidden>
    <nav class="wrap mnav-in" data-i18n-aria="aria.main" aria-label="Main">
      <a href="existing-customers/" data-i18n="seg.A">Existing customers</a>
      <a href="new-hotels/" data-i18n="seg.B">New hotels</a>
      <a href="opera-pms/" data-i18n="seg.C">Opera / PMS</a>
      <a href="hotel-groups/" data-i18n="seg.D">Hotel groups</a>
    </nav>
  </div>
</header>

<main id="main">
  <!-- ============ HERO ============ -->
  <section class="hero hub-hero" aria-labelledby="hero-title">
    <nav class="seg" data-i18n-aria="aria.routes" aria-label="Choose your route">
      <div class="seg-in">
        <a href="existing-customers/" data-route="A" data-i18n="seg.A">Existing customers</a>
        <a href="new-hotels/" data-route="B" data-i18n="seg.B">New hotels</a>
        <a href="opera-pms/" data-route="C" data-i18n="seg.C">Opera / PMS</a>
        <a href="hotel-groups/" data-route="D" data-i18n="seg.D">Hotel groups</a>
      </div>
    </nav>
    <div class="wrap hero-in">
      <div class="hero-copy">
        <p class="eyebrow" style="--i:0" data-i18n="hero.eyebrow">Fandaqah 360</p>
        <h1 class="h1" id="hero-title" style="--i:1" data-i18n="hero.title">Built for Saudi hospitality. <span class="hl">Choose your route.</span></h1>
        <p class="hero-lead" style="--i:2" data-i18n="hero.lead">Fandaqah 360 brings reservations, operations, finance and supported regulatory workflows together. Tell us where you are today and we’ll show you the right next step.</p>
        <div class="hero-cta" style="--i:3"><a class="btn" href="#solutions"><span data-i18n="cta.main">Find my route</span><svg class="ic ic-go" aria-hidden="true"><use href="assets/icons.svg#i-arrow-right"/></svg></a></div>
        <p class="hero-note" style="--i:4" data-i18n="hero.note">Four routes. One connected platform.</p>
      </div>
    </div>
    <div class="hero-media">
      <figure class="hero-photo"><img src="assets/img/hero-existing.webp" width="1600" height="914" alt="" fetchpriority="high" style="object-position:86% 40%"></figure>
      <div class="hero-visual" aria-hidden="true" style="--vx:9%;--vy:44px">
        <div class="laptop" style="--w:470px">
          <div class="laptop-screen"><div class="ui">
            <div class="ui-side">
              <div class="ui-brand"><i></i>fandaqah <b>360</b></div>
              <ul>
                <li class="on"><svg class="ic"><use href="assets/icons.svg#i-layout-dashboard"/></svg>Dashboard</li>
                <li><svg class="ic"><use href="assets/icons.svg#i-calendar-days"/></svg>Reservations</li>
                <li><svg class="ic"><use href="assets/icons.svg#i-sparkles"/></svg>Housekeeping</li>
                <li><svg class="ic"><use href="assets/icons.svg#i-coins"/></svg>Accounting</li>
                <li><svg class="ic"><use href="assets/icons.svg#i-file-text"/></svg>Compliance</li>
              </ul>
            </div>
            <div class="ui-main">
              <div class="ui-top"><span>&larr;</span><span class="ui-tag" data-i18n="ui.tag">Illustrative interface</span></div>
              <div class="ui-grid" style="--n:3">
                <div class="ui-card ui-kpi"><small>Reservations</small><b>&nbsp;</b><div class="grp-bar"><i style="--v:72%"></i></div></div>
                <div class="ui-card ui-kpi"><small>Rooms ready</small><b>&nbsp;</b><div class="grp-bar"><i style="--v:58%"></i></div></div>
                <div class="ui-card ui-kpi"><small>Invoices</small><b>&nbsp;</b><div class="grp-bar"><i style="--v:84%"></i></div></div>
              </div>
              <div class="ui-card ui-chart"><svg viewBox="0 0 220 70" preserveAspectRatio="none"><g stroke="#EDE8E1" stroke-width="1"><path d="M0 17H220M0 35H220M0 53H220"/></g><path d="M4 58 L30 50 L56 54 L82 40 L108 46 L134 30 L160 36 L186 22 L216 12" fill="none" stroke="#E9584D" stroke-width="2" stroke-linejoin="round"/></svg></div>
            </div>
          </div></div>
          <div class="laptop-base"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ ROUTES ============ -->
  <section class="sec" id="solutions" aria-labelledby="routes-title">
    <div class="wrap">
      <div class="sec-head" data-reveal>
        <p class="eyebrow" data-i18n="routes.eyebrow">Four routes</p>
        <h2 class="h2" id="routes-title" data-i18n="routes.title">Where is your property today?</h2>
      </div>
      <div class="routes">
        <a class="route" href="existing-customers/" data-route="A" data-reveal style="--i:0">
          <figure class="route-media"><img src="assets/img/hero-existing.webp" width="1600" height="914" alt="" loading="lazy" style="object-position:80% 40%"><span class="route-code" aria-hidden="true">A</span></figure>
          <div class="route-body"><p class="eyebrow" data-i18n="seg.A">Existing customers</p><h3 data-i18n="rA.t">More value from the Fandaqah you already use.</h3><p data-i18n="rA.d">Review your subscription, the modules you need and your upgrade eligibility.</p><span class="route-link"><span data-i18n="rA.c">Check my upgrade offer</span><svg class="ic flip" aria-hidden="true"><use href="assets/icons.svg#i-arrow-right"/></svg></span></div>
        </a>
        <a class="route" href="new-hotels/" data-route="B" data-reveal style="--i:1">
          <figure class="route-media"><img src="assets/img/hero-new.webp" width="1180" height="822" alt="" loading="lazy"><span class="route-code" aria-hidden="true">B</span></figure>
          <div class="route-body"><p class="eyebrow" data-i18n="seg.B">New hotels</p><h3 data-i18n="rB.t">Your hotel operations. One connected platform.</h3><p data-i18n="rB.d">See your daily workflows connected, from the first reservation to accounting.</p><span class="route-link"><span data-i18n="rB.c">Book my hotel demo</span><svg class="ic flip" aria-hidden="true"><use href="assets/icons.svg#i-arrow-right"/></svg></span></div>
        </a>
        <a class="route" href="opera-pms/" data-route="C" data-reveal style="--i:2">
          <figure class="route-media"><img src="assets/img/hero-opera.webp" width="1040" height="692" alt="" loading="lazy"><span class="route-code" aria-hidden="true">C</span></figure>
          <div class="route-body"><p class="eyebrow" data-i18n="seg.C">Opera / PMS</p><h3 data-i18n="rC.t">Strengthen the workflows around your Opera PMS.</h3><p data-i18n="rC.d">Review costs, interfaces and approvals before a scoped proposal.</p><span class="route-link"><span data-i18n="rC.c">Request a technology cost review</span><svg class="ic flip" aria-hidden="true"><use href="assets/icons.svg#i-arrow-right"/></svg></span></div>
        </a>
        <a class="route" href="hotel-groups/" data-route="D" data-reveal style="--i:3">
          <figure class="route-media"><img src="assets/img/lobby-dashboard.webp" width="800" height="456" alt="" loading="lazy" style="object-position:40% 50%"><span class="route-code" aria-hidden="true">D</span></figure>
          <div class="route-body"><p class="eyebrow" data-i18n="seg.D">Hotel groups</p><h3 data-i18n="rD.t">A dedicated platform built around your group.</h3><p data-i18n="rD.d">Agreed branding, workflows, integrations and group reporting.</p><span class="route-link"><span data-i18n="rD.c">Book a group discovery meeting</span><svg class="ic flip" aria-hidden="true"><use href="assets/icons.svg#i-arrow-right"/></svg></span></div>
        </a>
      </div>
    </div>
  </section>

  <!-- ============ HOW IT WORKS ============ -->
  <section class="sec-tight" id="how" aria-labelledby="how-title">
    <div class="wrap">
      <div class="sec-head" data-reveal><h2 class="h2" id="how-title" data-i18n="how.title">How it works.</h2></div>
      <ol class="steps" data-reveal style="--i:1">
        <li class="step"><span class="step-num">01</span><h3 data-i18n="s1.t">Choose your route</h3><p data-i18n="s1.d">Each route is built for a different kind of property.</p></li>
        <li class="step"><span class="step-num">02</span><h3 data-i18n="s2.t">Talk to the right specialist</h3><p data-i18n="s2.d">Your enquiry reaches the team for your route.</p></li>
        <li class="step"><span class="step-num">03</span><h3 data-i18n="s3.t">Receive a scoped proposal</h3><p data-i18n="s3.d">Modules, fees and terms confirmed in writing.</p></li>
      </ol>
    </div>
  </section>

  <!-- ============ PLATFORM BAND ============ -->
  <section class="sec-tight" aria-labelledby="band-title">
    <div class="wrap">
      <div class="band" data-reveal>
        <p class="band-big"><span data-i18n="band.big">One platform.</span> <span class="sm" data-i18n="band.sm">Built around your property.</span></p>
        <span class="band-rule" aria-hidden="true"></span>
        <div>
          <h2 class="band-h" id="band-title"><span data-i18n="band.h">Operations, finance, guest services and regulatory workflows.</span></h2>
          <p data-i18n="band.p">PMS core, housekeeping, channel management, accounting, POS, Shomoos Wallet, ZATCA integration and NTMP, scoped to the way your property works.</p>
        </div>
        <svg class="band-leaf" viewBox="0 0 220 320" aria-hidden="true"><use href="assets/icons.svg#art-leaves"/></svg>
      </div>
    </div>
  </section>
</main>

<footer class="site-foot">
  <div class="wrap foot-in">
    <a class="logo" href="./" data-i18n-aria="aria.home" aria-label="Fandaqah 360 home"><img src="assets/img/brand-light-340.webp" width="340" height="144" alt="Fandaqah" loading="lazy"><span class="logo-360" aria-hidden="true">360</span></a>
    <span class="foot-rule" aria-hidden="true"></span>
    <p class="foot-tag" data-i18n="foot.tag">Your hospitality, our priority.</p>
    <ul class="foot-links" data-i18n-aria="aria.foot" aria-label="Footer">
      <li><a href="mailto:info@fandaqah.com">info@fandaqah.com</a></li>
      <li><a href="https://www.fandaqah.com" target="_blank" rel="noopener">fandaqah.com</a></li>
      <li><a href="https://fandaqah.com/en/store/policy" data-href-ar="https://fandaqah.com/store/policy" target="_blank" rel="noopener" data-i18n="foot.privacy">Privacy</a></li>
      <li><a href="https://fandaqah.com/en/store/policy" data-href-ar="https://fandaqah.com/store/policy" target="_blank" rel="noopener" data-i18n="foot.terms">Terms</a></li>
    </ul>
  </div>
  <p class="wrap foot-legal" data-i18n="foot.legal">© 2026 Fandaqah. All rights reserved.</p>
</footer>

<script>
window.FQ_PAGE = {
  segment: null,
  meta: { title: 'فندقة 360 | مصمَّمة لقطاع الضيافة السعودي', desc: 'تربط فندقة 360 عمليات الفندق والمالية وخدمات الضيوف والإجراءات التنظيمية المدعومة. اختر مسارك: العملاء الحاليون، الفنادق الجديدة، منشآت Opera والأنظمة المعتمدة، أو مجموعات الفنادق.' },
  ar: {
    'nav.contact': 'تواصل معنا', 'cta.main': 'اعثر على مسارك',
    'hero.eyebrow': 'فندقة 360',
    'hero.title': 'مصمَّمة لقطاع الضيافة السعودي. <span class="hl">اختر مسارك.</span>',
    'hero.lead': 'تجمع فندقة 360 الحجوزات والتشغيل والمالية والإجراءات التنظيمية المدعومة في منصة واحدة. أخبرنا أين أنت اليوم، وسنرشدك إلى الخطوة المناسبة.',
    'hero.note': 'أربعة مسارات، ومنصة واحدة مترابطة.',
    'routes.eyebrow': 'أربعة مسارات', 'routes.title': 'أين تقف منشأتك اليوم؟',
    'rA.t': 'قيمة أكبر من فندقة التي تستخدمها اليوم.', 'rA.d': 'راجع اشتراكك والوحدات التي تحتاجها وأهليتك للترقية.', 'rA.c': 'تحقّق من عرض الترقية',
    'rB.t': 'عمليات فندقك. منصة واحدة مترابطة.', 'rB.d': 'شاهد سير عملك اليومي مترابطاً، من الحجز الأول حتى المحاسبة.', 'rB.c': 'احجز عرضاً توضيحياً لفندقي',
    'rC.t': 'عزّز مسارات العمل حول نظام Opera PMS لديك.', 'rC.d': 'راجع التكاليف والواجهات والاعتمادات قبل عرض سعر محدد النطاق.', 'rC.c': 'اطلب مراجعة تكاليف التقنية',
    'rD.t': 'منصة مخصّصة مبنية حول مجموعتك.', 'rD.d': 'هوية ومسارات عمل وتكاملات وتقارير للمجموعة وفق نطاق متفق عليه.', 'rD.c': 'احجز اجتماعاً استكشافياً للمجموعة',
    'how.title': 'كيف نعمل.',
    's1.t': 'اختر مسارك', 's1.d': 'صُمِّم كل مسار لنوع مختلف من المنشآت.',
    's2.t': 'تحدّث مع المختص المناسب', 's2.d': 'يصل استفسارك إلى الفريق المسؤول عن مسارك.',
    's3.t': 'استلم عرض سعر محدد النطاق', 's3.d': 'الوحدات والرسوم والشروط مؤكَّدة كتابياً.',
    'band.big': 'منصة واحدة.', 'band.sm': 'مبنية حول منشأتك.',
    'band.h': 'التشغيل والمالية وخدمات الضيوف والإجراءات التنظيمية.',
    'band.p': 'نظام إدارة المنشآت الفندقية والتدبير الفندقي وإدارة القنوات والمحاسبة ونقاط البيع ومحفظة شموس والتكامل مع هيئة الزكاة والضريبة والجمارك ومنصة الرصد السياحي، بنطاق يناسب طريقة عمل منشأتك.'
  }
};
</script>
<script src="assets/js/site.js" defer></script>
</body>
</html>
