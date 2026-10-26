<?php /* Generated from 404.html by tools/build-php.py: edit the .html page, then run the build. */ require __DIR__ . '/api/page.php'; ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"><meta name="fq-lead-proxy" content="api/lead.php">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>الصفحة غير موجودة | فندقة 360 · Page not found</title>
<script>
/* GitHub Pages serves this for any missing path. /landing/ and /fandaqah-360-landing-v2/ are live sites of their own.
   The campaign site briefly lived under /site/: those links forward to the same page at its clean URL. */
(function () {
  var base = location.pathname.indexOf('/fandaqah-360/') === 0 ? '/fandaqah-360/' : '/';
  var rest = location.pathname.slice(base.length);
  if (/^site(\/|$)/.test(rest)) location.replace(base + rest.replace(/^site\/?/, '') + location.search + location.hash);
  window.FQ_BASE = base;
  /* this page can sit at any depth, so the site fonts are loaded from the root */
  var f = base + 'assets/fonts/', ar = 'unicode-range:U+0600-06FF,U+0750-077F,U+0870-08FF,U+200C-200E,U+2010-2011,U+FB50-FDFF,U+FE70-FEFC';
  document.write('<style>'
    + '@font-face{font-family:"Almarai";font-weight:400;font-display:swap;src:url(' + f + 'almarai-arabic-400.woff2) format("woff2");' + ar + '}'
    + '@font-face{font-family:"Almarai";font-weight:700;font-display:swap;src:url(' + f + 'almarai-arabic-700.woff2) format("woff2");' + ar + '}'
    + '@font-face{font-family:"El Messiri";font-weight:400 700;font-display:swap;src:url(' + f + 'el-messiri-arabic.woff2) format("woff2");' + ar + '}'
    + '@font-face{font-family:"Inter";font-weight:100 900;font-display:swap;src:url(' + f + 'inter-latin.woff2) format("woff2")}'
    + '</style>');
})();
</script>
<style>
  :root{--bg:#F0EDE9;--ink:#18172F;--ink-2:#3E3D54;--ink-3:#6B6A80;--coral:#D4413B;--line:#E1DBD2}
  *{box-sizing:border-box}
  body{margin:0;min-height:100vh;display:grid;place-items:center;padding:32px 16px;background:var(--bg);color:var(--ink);font:400 16px/1.7 "Almarai","Segoe UI",Tahoma,system-ui,sans-serif}
  main{width:100%;max-width:560px;text-align:center}
  .code{margin:0;font:600 92px/1 "Inter",Georgia,serif;color:var(--coral);letter-spacing:-.04em;direction:ltr}
  h1{margin:14px 0 8px;font:700 32px/1.3 "El Messiri","Almarai",serif}
  p{margin:0;color:var(--ink-2)}
  [lang="en"]{font-family:"Inter","Segoe UI",system-ui,sans-serif}
  .en-lead{margin-top:6px;font-size:14px;line-height:1.5;color:var(--ink-3)}
  ul{display:grid;gap:10px;margin:28px 0 0;padding:0;list-style:none}
  a{display:block;padding:13px 18px;border:1px solid var(--line);border-radius:12px;background:#fff;color:var(--ink);font-weight:700;text-decoration:none;transition:border-color .2s}
  a small{display:block;margin-top:2px;font-size:12.5px;font-weight:500;line-height:1.4;color:var(--ink-3)}
  a:hover{border-color:var(--coral)}
  a.home{background:var(--coral);border-color:var(--coral);color:#fff} a.home small{color:rgba(255,255,255,.82)}
  :focus-visible{outline:2px solid var(--coral);outline-offset:3px}
</style>
</head>
<body>
<main>
  <p class="code">404</p>
  <h1>نقلنا هذه الصفحة.</h1>
  <p>أصبح لفندقة 360 موقع واحد بأربعة مسارات. اختر وجهتك التالية.</p>
  <p class="en-lead" lang="en" dir="ltr">This page has moved. Fandaqah 360 now has one home with four routes.</p>
  <ul>
    <li><a class="home" href="./" data-to="">الصفحة الرئيسية لفندقة 360<small lang="en" dir="ltr">Fandaqah 360 home</small></a></li>
    <li><a href="./existing-customers/" data-to="existing-customers/">العملاء الحاليون: تحقّق من عرض الترقية<small lang="en" dir="ltr">Existing customers: check my upgrade offer</small></a></li>
    <li><a href="./new-hotels/" data-to="new-hotels/">الفنادق الجديدة: احجز عرضاً توضيحياً لفندقي<small lang="en" dir="ltr">New hotels: book my hotel demo</small></a></li>
    <li><a href="./opera-pms/" data-to="opera-pms/">Opera / أنظمة PMS: اطلب مراجعة تكاليف التقنية<small lang="en" dir="ltr">Opera / PMS: request a technology cost review</small></a></li>
    <li><a href="./hotel-groups/" data-to="hotel-groups/">مجموعات الفنادق: احجز اجتماعاً استكشافياً للمجموعة<small lang="en" dir="ltr">Hotel groups: book a group discovery meeting</small></a></li>
  </ul>
</main>
<script>document.querySelectorAll('[data-to]').forEach(function (a) { a.href = window.FQ_BASE + a.dataset.to; });</script>
</body>
</html>
