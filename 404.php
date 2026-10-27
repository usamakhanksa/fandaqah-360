<?php /* Generated from 404.html by tools/build-php.py: edit the .html page, then run the build. */ if (is_file(__DIR__ . '/api/page.php')) require __DIR__ . '/api/page.php'; ?>
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
  /* English visitors (?lang=en, or English chosen before) get English only */
  html[lang="en"] .ar,html[lang="en"] .en-lead{display:none}
  html[lang="en"] a small{margin:0;font-size:inherit;font-weight:inherit;line-height:inherit;color:inherit}
</style>
<script src="assets/js/linkedin-insight.js?v=6a5e12a7" defer></script>
<script src="assets/js/fandaqah-tiktok.js?v=1f880a5d" defer></script>
</head>
<body>
<main>
  <p class="code">404</p>
  <h1 data-en="This page has moved.">نقلنا هذه الصفحة.</h1>
  <p data-en="Fandaqah 360 now has one home with four routes. Choose where to go next.">أصبح لفندقة 360 موقع واحد بأربعة مسارات. اختر وجهتك التالية.</p>
  <p class="en-lead" lang="en" dir="ltr">This page has moved. Fandaqah 360 now has one home with four routes.</p>
  <ul>
    <li><a class="home" href="./" data-to=""><span class="ar">الصفحة الرئيسية لفندقة 360</span><small lang="en" dir="ltr">Fandaqah 360 home</small></a></li>
    <li><a href="./existing-customers/" data-to="existing-customers/"><span class="ar">العملاء الحاليون: تحقّق من عرض الترقية</span><small lang="en" dir="ltr">Existing customers: check my upgrade offer</small></a></li>
    <li><a href="./new-hotels/" data-to="new-hotels/"><span class="ar">الفنادق الجديدة: احجز عرضاً توضيحياً لفندقي</span><small lang="en" dir="ltr">New hotels: book my hotel demo</small></a></li>
    <li><a href="./opera-pms/" data-to="opera-pms/"><span class="ar">Opera / أنظمة PMS: اطلب مراجعة تكاليف التقنية</span><small lang="en" dir="ltr">Opera / PMS: request a technology cost review</small></a></li>
    <li><a href="./hotel-groups/" data-to="hotel-groups/"><span class="ar">مجموعات الفنادق: احجز اجتماعاً استكشافياً للمجموعة</span><small lang="en" dir="ltr">Hotel groups: book a group discovery meeting</small></a></li>
  </ul>
</main>
<script>
(function () {
  var l; try { l = new URLSearchParams(location.search).get('lang') || localStorage.getItem('fq360-lang'); } catch (e) {}
  var en = l === 'en', r = document.documentElement;
  if (en) { r.lang = 'en'; r.dir = 'ltr'; document.title = 'Page not found | Fandaqah 360';
    document.querySelectorAll('[data-en]').forEach(function (n) { n.textContent = n.dataset.en; }); }
  // every route keeps the visitor's language
  document.querySelectorAll('[data-to]').forEach(function (a) { a.href = window.FQ_BASE + a.dataset.to + (en ? '?lang=en' : ''); });
})();
</script>
<noscript><img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=11189657&fmt=gif"></noscript>
<script src="assets/js/lead-capture.js?v=978ac546" defer></script>
<!-- WhatsApp chat button (added to every PHP copy by tools/build-php.py) -->
<style>
.fq-wa{position:fixed;z-index:90;left:max(20px,env(safe-area-inset-left));bottom:calc(22px + env(safe-area-inset-bottom));margin:0;display:flex;align-items:center;transition:bottom .35s cubic-bezier(.2,.8,.2,1);animation:fq-wa-in .55s cubic-bezier(.2,.8,.2,1) .9s both}
.fq-wa .btn-whatsapp{position:relative;display:grid;place-items:center;width:58px;height:58px;border-radius:50%;background:#25D366;box-shadow:0 14px 28px -10px rgba(18,140,74,.8),0 2px 6px rgba(0,0,0,.18);text-decoration:none;outline:none;transition:transform .2s ease,box-shadow .2s ease}
.fq-wa .img-whatsapp{display:grid;place-items:center;width:31px;height:31px}
.fq-wa .img-whatsapp svg{width:100%;height:100%;fill:#fff}
.fq-wa .btn-whatsapp::before{content:"";position:absolute;inset:0;border-radius:50%;border:2px solid #25D366;opacity:0;animation:fq-wa-ring 6s ease-out 2.6s infinite}
.fq-wa .fq-wa-tip{position:absolute;left:70px;top:50%;white-space:nowrap;padding:10px 16px;border-radius:999px;background:#2a273c;color:#fff;font-size:14px;font-weight:600;line-height:1.2;box-shadow:0 12px 24px -12px rgba(42,39,60,.7);opacity:0;transform:translate(-6px,-50%);pointer-events:none;transition:opacity .2s ease,transform .2s ease}
.fq-wa .btn-whatsapp:hover,.fq-wa .btn-whatsapp:focus-visible{transform:translateY(-2px) scale(1.04)}
.fq-wa .btn-whatsapp:focus-visible{box-shadow:0 0 0 3px #fff,0 0 0 6px #25D366}
.fq-wa:hover .fq-wa-tip,.fq-wa:focus-within .fq-wa-tip{opacity:1;transform:translate(0,-50%)}
@keyframes fq-wa-in{from{opacity:0;transform:translateY(18px) scale(.86)}to{opacity:1;transform:none}}
@keyframes fq-wa-ring{0%{opacity:.7;transform:scale(1)}16%,100%{opacity:0;transform:scale(1.6)}}
body:has(.fx-clock:not(.is-docked)) .fq-wa{bottom:calc(86px + env(safe-area-inset-bottom))}
@media (hover:none){.fq-wa .fq-wa-tip{display:none}}
@media (max-width:760px){.fq-wa .btn-whatsapp{width:54px;height:54px}body:has(.m-cta.is-on) .fq-wa{bottom:calc(94px + env(safe-area-inset-bottom))}}
@media (prefers-reduced-motion:reduce){.fq-wa{animation:none;transition:none}.fq-wa .btn-whatsapp{transition:none}.fq-wa .btn-whatsapp::before{animation:none}}
@media print{.fq-wa{display:none}}
</style>
<div class="group-whatsapp flex-end mtop-1 fq-wa">
  <a href="https://wa.me/966920066456" target="_blank" rel="noopener" class="btn-whatsapp" aria-label="اتصل بنا عبر واتساب">
    <div class="img-whatsapp" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg></div>
  </a>
  <span class="fq-wa-tip" aria-hidden="true">تواصل معنا عبر واتساب</span>
</div>
<script>
(function () {
  var box = document.querySelector('.fq-wa'); if (!box) return;
  var a = box.querySelector('a'), tip = box.querySelector('.fq-wa-tip'), root = document.documentElement;
  var T = { ar: { label: 'اتصل بنا عبر واتساب', tip: 'تواصل معنا عبر واتساب', msg: 'مرحباً فندقة، أود الاستفسار عن فندقة 360.' },
            en: { label: 'Chat with us on WhatsApp', tip: 'Chat with us on WhatsApp', msg: 'Hello Fandaqah, I would like to ask about Fandaqah 360.' } };
  // the label, the tooltip and the opening message follow the page language, and switch with it
  function sync() {
    var t = T[(root.lang || 'ar').slice(0, 2) === 'en' ? 'en' : 'ar'];
    a.setAttribute('aria-label', t.label); tip.textContent = t.tip;
    a.href = 'https://wa.me/966920066456?text=' + encodeURIComponent(t.msg);
  }
  sync();
  new MutationObserver(sync).observe(root, { attributes: true, attributeFilter: ['lang'] });
  a.addEventListener('click', function () { if (window.dataLayer) window.dataLayer.push({ event: 'whatsapp_click', page: location.pathname }); });
})();
</script>
</body>
</html>
