<?php /* Generated from linkdein-message.html by tools/build-php.py: edit the .html page, then run the build. */ if (is_file(__DIR__ . '/api/page.php')) require __DIR__ . '/api/page.php'; ?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8"><meta name="fq-lead-proxy" content="api/lead.php"><script>/* Arabic by default: English only when the link asks (?lang=en) or the reader chose it before. The page stays hidden until its text is in that language, with a 1.5 s safety net. */(function(r){var l;try{l=new URLSearchParams(location.search).get('lang')||(JSON.parse(localStorage.getItem('fq360-linkedin-kit-v2')||'{}').lang)}catch(e){}if(l!=='en'){r.lang='ar';r.dir='rtl';r.classList.add('i18n-wait');setTimeout(function(){r.classList.remove('i18n-wait')},1500)}})(document.documentElement)</script><style>.i18n-wait body{visibility:hidden}</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LinkedIn Outreach Kit | Fandaqah 360</title>
<meta name="description" content="Fandaqah 360 LinkedIn kit for campaigns A to D, built from the Marketing Campaign Playbook: routing, contact eligibility, five-touch sequences that follow each campaign's storyboard, sponsored content and posts, in Arabic and English.">
<!-- internal sales tool: published with the site for the team, kept out of search engines -->
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#F0EDE9">
<link rel="icon" href="favicon.ico">
<link rel="preload" href="assets/fonts/inter-latin.woff2" as="font" type="font/woff2" crossorigin>
<style>
@font-face{font-family:"Inter";font-style:normal;font-weight:100 900;font-display:swap;src:url(assets/fonts/inter-latin.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
@font-face{font-family:"Source Serif 4";font-style:normal;font-weight:200 900;font-display:swap;src:url(assets/fonts/source-serif-4-latin.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
@font-face{font-family:"Almarai";font-style:normal;font-weight:400;font-display:swap;src:url(assets/fonts/almarai-arabic-400.woff2) format("woff2");unicode-range:U+0600-06FF,U+0750-077F,U+0870-08FF,U+200C-200E,U+2010-2011,U+FB50-FDFF,U+FE70-FEFC}
@font-face{font-family:"Almarai";font-style:normal;font-weight:700;font-display:swap;src:url(assets/fonts/almarai-arabic-700.woff2) format("woff2");unicode-range:U+0600-06FF,U+0750-077F,U+0870-08FF,U+200C-200E,U+2010-2011,U+FB50-FDFF,U+FE70-FEFC}
@font-face{font-family:"El Messiri";font-style:normal;font-weight:400 700;font-display:swap;src:url(assets/fonts/el-messiri-arabic.woff2) format("woff2");unicode-range:U+0600-06FF,U+0750-077F,U+0870-08FF,U+200C-200E,U+2010-2011,U+FB50-FDFF,U+FE70-FEFC}

/* campaign site palette (assets/css/site.css), set as surface roles */
:root{
  --bg:#F0EDE9; --surface:#FFFFFF; --panel:#F8F6F3; --field:#F5F1ED; --line:#E1DBD2; --line-soft:#ECE7E0;
  --ink:#18172F; --ink-2:#47455C; --ink-3:#69677C; --navy:#282639; --navy-2:#34324A;
  --coral:#E64D44; --coral-ink:#B8352E; --coral-btn:#D4413B; --coral-btn-h:#BF3832; --peach:#F7D0B5; --peach-50:#FCEEE5;
  --ok:#2E7550; --ok-50:#E4F1E9; --warn:#8A5A0A; --warn-50:#FBF0DA;
  --f-sans:"Inter", "Segoe UI", system-ui, sans-serif; --f-display:"Source Serif 4", Georgia, serif;
  --r-sm:8px; --r-md:12px; --r-lg:16px;
  --e1:0 1px 2px rgba(24,23,47,.05), 0 4px 12px -6px rgba(24,23,47,.12);
  --e2:0 1px 2px rgba(24,23,47,.05), 0 14px 30px -16px rgba(24,23,47,.22);
  --e3:0 2px 6px rgba(24,23,47,.06), 0 28px 60px -24px rgba(24,23,47,.35);
  --ease:cubic-bezier(.23,1,.32,1);
  --bar-h:64px; --status-h:40px; --side-w:252px; --inbox-w:388px;
}
html[lang="ar"]{ --f-sans:"Almarai", "Inter", system-ui, sans-serif; --f-display:"El Messiri", "Source Serif 4", serif; }
html[lang="ar"] body *{letter-spacing:0 !important}

*,*::before,*::after{box-sizing:border-box}
html{-webkit-text-size-adjust:100%;scroll-behavior:smooth;scroll-padding-top:calc(var(--bar-h) + 20px)}
body{margin:0;background:var(--bg);color:var(--ink);font:400 14px/1.55 var(--f-sans);-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility;padding-bottom:var(--status-h)}
h1,h2,h3,h4,p,ul,ol,dl,dd,figure{margin:0}
ul,ol{padding:0;list-style:none}
img,svg{display:block;max-width:100%}
a{color:inherit}
button,input,select,textarea{font:inherit;color:inherit}
button{cursor:pointer}
::selection{background:var(--peach);color:var(--ink)}
:focus-visible{outline:2px solid var(--coral-btn);outline-offset:2px;border-radius:6px}
input,textarea{caret-color:var(--coral-btn)}
.ic{width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}
.skip{position:absolute;inset-inline-start:12px;top:-60px;z-index:100;padding:10px 14px;border-radius:var(--r-sm);background:var(--navy);color:#fff;font-weight:600;text-decoration:none}
.skip:focus{top:10px}
[hidden]{display:none !important}
.num{font-variant-numeric:tabular-nums}
.thin-scroll{scrollbar-width:thin;scrollbar-color:#CFC7BC transparent}

/* ---------- buttons ---------- */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:36px;padding:0 14px;border:0;border-radius:var(--r-sm);font-size:13px;font-weight:600;line-height:1;white-space:nowrap;text-decoration:none;transition:background-color .15s var(--ease), box-shadow .15s var(--ease), transform .12s var(--ease)}
.btn:active{transform:translateY(1px)}
.btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
.btn-primary{background:var(--coral-btn);color:#fff;box-shadow:0 1px 2px rgba(180,52,46,.25), 0 8px 16px -10px rgba(212,65,59,.7)}
.btn-ghost{background:var(--surface);color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}
.btn-sm{height:32px;padding:0 12px;font-size:12.5px}
.btn-icon{display:inline-grid;place-items:center;width:32px;height:32px;padding:0;border:0;border-radius:var(--r-sm);background:transparent;color:var(--ink-2);transition:background-color .15s var(--ease), color .15s var(--ease)}
.btn-icon:active{transform:translateY(1px)}
.btn.is-done{background:var(--ok);box-shadow:none;color:#fff}
.btn-ghost.is-done,.btn-icon.is-done{background:var(--ok-50);color:var(--ok);box-shadow:inset 0 0 0 1px rgba(46,117,80,.3)}
@media (hover:hover) and (pointer:fine){
  .btn-primary:hover{background:var(--coral-btn-h)}
  .btn-ghost:hover{box-shadow:inset 0 0 0 1px #CFC7BC, var(--e1)}
  .btn-icon:hover{background:var(--field);color:var(--ink)}
}

/* ---------- app chrome: bar ---------- */
.bar{position:sticky;top:0;z-index:40;display:flex;align-items:center;gap:16px;height:var(--bar-h);padding:0 20px;background:rgba(255,255,255,.96);-webkit-backdrop-filter:saturate(1.2) blur(8px);backdrop-filter:saturate(1.2) blur(8px);box-shadow:0 1px 0 var(--line)}
.brand{display:flex;align-items:center;flex:none;border-radius:6px}
.brand img{height:34px;width:auto}
.bar-title{display:grid;line-height:1.2;padding-inline-start:16px;border-inline-start:1px solid var(--line);min-width:0}
.bar-title strong{font-size:14px;font-weight:700;white-space:nowrap}
.bar-title span{font-size:12px;color:var(--ink-3);white-space:nowrap}
.bar-ctl{display:flex;align-items:center;gap:10px;margin-inline-start:auto}
.seg{display:inline-flex;padding:3px;border-radius:10px;background:var(--field);box-shadow:inset 0 0 0 1px var(--line-soft)}
.seg button{position:relative;display:inline-flex;align-items:center;gap:7px;height:30px;padding:0 11px;border:0;border-radius:var(--r-sm);background:transparent;color:var(--ink-2);font-size:12.5px;font-weight:600;white-space:nowrap;transition:background-color .15s var(--ease), color .15s var(--ease)}
.seg button[aria-pressed="true"]{background:var(--surface);color:var(--ink);box-shadow:var(--e1)}
.seg button[lang="ar"]{font-family:"Almarai", var(--f-sans);font-weight:700}
.seg-code{display:inline-grid;place-items:center;width:18px;height:18px;border-radius:5px;background:var(--line-soft);font-size:10.5px;font-weight:800;color:var(--ink-2)}
.seg button[aria-pressed="true"] .seg-code{background:var(--navy);color:#fff}
.seg-camp button.is-primary::after{content:"";position:absolute;top:5px;inset-inline-end:5px;width:6px;height:6px;border-radius:50%;background:var(--coral)}
.seg-camp button.is-routed .seg-code{box-shadow:0 0 0 2px var(--surface), 0 0 0 3.5px var(--ok)}
@media (hover:hover) and (pointer:fine){ .seg button[aria-pressed="false"]:hover{color:var(--ink);background:rgba(255,255,255,.6)} }
.inbox-toggle{display:none;align-items:center;gap:8px;height:36px;padding:0 12px;border:0;border-radius:var(--r-sm);background:var(--navy);color:#fff;font-size:12.5px;font-weight:600}
.inbox-toggle .count{display:inline-grid;place-items:center;min-width:20px;height:20px;padding:0 6px;border-radius:10px;background:var(--coral-btn);font-size:11px;font-weight:700}

/* ---------- app grid ---------- */
.app{display:grid;grid-template-columns:var(--side-w) minmax(0, 1fr) var(--inbox-w);align-items:start}

/* sidebar: the sequence is the navigation */
.side{position:sticky;top:var(--bar-h);height:calc(100dvh - var(--bar-h) - var(--status-h));overflow:auto;padding:20px 14px 24px;border-inline-end:1px solid var(--line)}
.side-h{margin:0 10px 8px;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-3)}
.side-h + * {margin-bottom:20px}
.nav-steps{position:relative}
.nav-steps::before{content:"";position:absolute;top:18px;bottom:18px;inset-inline-start:21px;width:1px;background:var(--line)}
.nav-steps a,.nav-tools a{position:relative;display:grid;grid-template-columns:24px 1fr;align-items:center;column-gap:10px;padding:7px 10px;border-radius:var(--r-sm);text-decoration:none;color:var(--ink-2);transition:background-color .15s var(--ease), color .15s var(--ease)}
.nav-steps .dot{position:relative;z-index:1;display:grid;place-items:center;width:24px;height:24px;border-radius:50%;background:var(--surface);box-shadow:inset 0 0 0 1.5px var(--line);font-size:11px;font-weight:700;color:var(--ink-3)}
.nav-steps .is-ready .dot{background:var(--ok-50);box-shadow:inset 0 0 0 1.5px rgba(46,117,80,.45);color:var(--ok)}
.nav-steps .is-missing .dot{background:#FFF3EF;box-shadow:inset 0 0 0 1.5px rgba(212,65,59,.5);color:var(--coral-ink)}
.nav-l{font-size:13px;font-weight:600;line-height:1.3;color:inherit}
.nav-d{grid-column:2;font-size:11.5px;color:var(--ink-3)}
.nav-steps a.is-active,.nav-tools a.is-active{background:var(--surface);color:var(--ink);box-shadow:var(--e1)}
.nav-tools .ic{justify-self:center;color:var(--ink-3)}
@media (hover:hover) and (pointer:fine){ .nav-steps a:hover,.nav-tools a:hover{background:rgba(255,255,255,.7);color:var(--ink)} }

/* ---------- main column ---------- */
.main{min-width:0;padding:28px clamp(20px, 3vw, 44px) 40px}
.main-in{max-width:780px;margin-inline:auto}

/* campaign brief: the playbook page for this campaign, at a glance */
.brief{position:relative;overflow:hidden;padding:22px 24px 20px;border-radius:var(--r-lg);background:var(--navy);color:#fff;box-shadow:var(--e3)}
.brief::after{content:"";position:absolute;inset-inline-end:-60px;top:-80px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle, rgba(230,77,68,.26), rgba(230,77,68,0) 70%);pointer-events:none}
.brief-top{position:relative;z-index:1;display:flex;flex-wrap:wrap;align-items:center;gap:8px 12px}
.brief-code{display:grid;place-items:center;width:34px;height:34px;border-radius:9px;background:var(--coral-btn);font-size:16px;font-weight:800}
.brief h1{font:600 26px/1.15 var(--f-display);letter-spacing:-.01em}
.role{display:inline-flex;align-items:center;gap:6px;height:24px;padding:0 10px;border-radius:999px;background:rgba(255,255,255,.1);box-shadow:inset 0 0 0 1px rgba(255,255,255,.18);font-size:11.5px;font-weight:700;color:#fff}
.role.is-primary{background:var(--coral-btn);box-shadow:none}
.brief-grid{position:relative;z-index:1;display:grid;grid-template-columns:repeat(3, minmax(0, 1fr));gap:14px 22px;margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,.14)}
.brief-grid dt{font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.6)}
.brief-grid dd{margin-top:4px;font-size:13.5px;line-height:1.5;color:rgba(255,255,255,.92)}
.brief-grid .cta-v{display:inline-flex;align-items:center;gap:6px;margin-top:6px;padding:5px 10px;border-radius:7px;background:rgba(255,255,255,.1);font-weight:700}
.brief-li{position:relative;z-index:1;margin-top:14px;font-size:13px;line-height:1.55;color:rgba(255,255,255,.8)}
.beats{position:relative;z-index:1;display:grid;grid-template-columns:repeat(4, minmax(0, 1fr));gap:8px;margin-top:10px}
.beat{padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.07);box-shadow:inset 0 0 0 1px rgba(255,255,255,.1);font-size:12.5px;line-height:1.4;color:rgba(255,255,255,.9)}
.beat b{display:block;margin-bottom:4px;font-size:10.5px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--peach)}
.beats-k{position:relative;z-index:1;margin-top:14px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.6)}

/* account panel: fields, routing, eligibility, release */
.panel{margin-top:18px;padding:20px 22px 22px;border-radius:var(--r-lg);background:var(--surface);box-shadow:var(--e2), inset 0 0 0 1px var(--line-soft)}
.pr-head{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
.pr-head h2{font-size:15px;font-weight:700}
.chip-demo{display:inline-flex;align-items:center;height:22px;padding:0 9px;border-radius:999px;background:var(--warn-50);color:var(--warn);font-size:11.5px;font-weight:700}
.pr-actions{display:flex;gap:8px;margin-inline-start:auto}
.pr-grid{display:grid;grid-template-columns:repeat(6, minmax(0, 1fr));gap:14px;margin-top:16px}
.field{display:grid;align-content:start;gap:6px;grid-column:span 2;min-width:0}
.field label,.field .lbl{font-size:12.5px;font-weight:600;color:var(--ink-2)}
.field .opt{font-weight:500;color:var(--ink-3)}
.field input,.field select,.field textarea,.sel{width:100%;min-height:40px;padding:0 12px;border:1px solid var(--line);border-radius:var(--r-sm);background:var(--field);font-size:14px;transition:border-color .15s var(--ease), box-shadow .15s var(--ease), background-color .15s var(--ease)}
.sel{min-height:38px;font-size:13.5px}
.field textarea{min-height:64px;padding:10px 12px;line-height:1.5;resize:vertical}
.field input:focus,.field select:focus,.field textarea:focus,.sel:focus{outline:0;border-color:var(--coral);background:#fff;box-shadow:0 0 0 3px rgba(230,77,68,.16)}
.field.is-empty input,.field.is-empty select{border-color:rgba(212,65,59,.45);background:#FFF8F5}
.field-6{grid-column:span 6}
.field-3{grid-column:span 3}
/* every campaign fills its rows: no empty trailing cell */
.pr-grid[data-c="A"] [data-f="plan"],.pr-grid[data-c="A"] [data-f="renewal"],.pr-grid[data-c="B"] [data-f="rep"],.pr-grid[data-c="B"] [data-f="date"]{grid-column:span 3}
.pr-grid[data-c="C"] [data-f="persona"]{grid-column:span 6}
.hint{display:flex;justify-content:space-between;gap:10px;font-size:11.5px;line-height:1.45;color:var(--ink-3)}
.hint.warn{color:var(--warn)}
.hint .count{flex:none;font-variant-numeric:tabular-nums}
.chips{display:flex;flex-wrap:wrap;gap:6px}
.chips button{height:30px;padding:0 11px;border:0;border-radius:999px;background:var(--field);box-shadow:inset 0 0 0 1px var(--line);font-size:12.5px;font-weight:600;color:var(--ink-2);transition:background-color .15s var(--ease), color .15s var(--ease), box-shadow .15s var(--ease)}
.chips button[aria-pressed="true"]{background:var(--navy);color:#fff;box-shadow:none}
.chips button:disabled{opacity:.45;cursor:not-allowed}
.field.is-empty .chips button{box-shadow:inset 0 0 0 1px rgba(212,65,59,.45)}
.mini-seg{display:inline-flex;flex-wrap:wrap;gap:4px;padding:3px;border-radius:10px;background:var(--field);box-shadow:inset 0 0 0 1px var(--line-soft)}
.mini-seg button{min-height:30px;padding:0 11px;border:0;border-radius:var(--r-sm);background:transparent;font-size:12.5px;font-weight:600;color:var(--ink-2)}
.mini-seg button[aria-pressed="true"]{background:var(--surface);color:var(--ink);box-shadow:var(--e1)}
.groups{display:grid;grid-template-columns:minmax(0, 1fr) minmax(0, 1fr);margin-top:20px;border-radius:var(--r-md);box-shadow:inset 0 0 0 1px var(--line)}
.group{padding:16px 18px;min-width:0}
.group + .group{border-inline-start:1px solid var(--line)}
.group h3{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700}
.group h3 .ic{color:var(--coral)}
.group .sub{margin-top:3px;font-size:12px;color:var(--ink-3)}
.q{display:grid;gap:6px;margin-top:12px}
.q > span,.q > label{font-size:12.5px;font-weight:600;color:var(--ink-2)}
.verdict{display:flex;align-items:flex-start;gap:10px;margin-top:14px;padding:11px 12px;border-radius:10px;background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft);font-size:13px;line-height:1.45}
.verdict .vcode{display:grid;place-items:center;flex:none;width:26px;height:26px;border-radius:7px;background:var(--navy);color:#fff;font-size:12px;font-weight:800}
.verdict.is-research .vcode{background:var(--warn)}
.verdict.is-conflict{background:#FFF3EF;box-shadow:inset 0 0 0 1px rgba(212,65,59,.35);color:var(--coral-ink)}
.verdict.is-conflict .vcode{background:var(--coral-btn)}
.verdict b{font-weight:700}
.verdict .btn{margin-top:8px}
.check{display:flex;align-items:flex-start;gap:10px;margin-top:10px;font-size:13px;line-height:1.45;color:var(--ink-2);cursor:pointer}
.check input{flex:none;width:18px;height:18px;margin:1px 0 0;accent-color:var(--navy)}
.clear{display:flex;align-items:flex-start;gap:8px;margin-top:14px;padding:9px 12px;border-radius:10px;font-size:13px;font-weight:700;line-height:1.45}
.clear .ic{margin-top:2px}
.clear.is-ok{background:var(--ok-50);color:var(--ok)}
.clear.is-no{background:#FFF0EB;color:var(--coral-ink)}
.release{margin-top:14px;padding:16px 18px;border-radius:var(--r-md);background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft)}
.release-head{display:flex;align-items:flex-start;gap:12px}
.release-head h3{font-size:13.5px;font-weight:700}
.release-head p{margin-top:3px;font-size:12px;line-height:1.5;color:var(--ink-3)}
.switch{position:relative;flex:none;width:44px;height:26px;margin-inline-start:auto;border:0;border-radius:13px;background:#CFC7BC;transition:background-color .2s var(--ease)}
.switch::after{content:"";position:absolute;top:3px;inset-inline-start:3px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(24,23,47,.3);transition:transform .2s var(--ease)}
.switch[aria-checked="true"]{background:var(--ok)}
.switch[aria-checked="true"]::after{transform:translateX(18px)}
html[dir="rtl"] .switch[aria-checked="true"]::after{transform:translateX(-18px)}
.release-grid{display:grid;grid-template-columns:repeat(3, minmax(0, 1fr));gap:12px;margin-top:12px}
.release-grid .field{grid-column:auto}
.release-grid .hint{grid-column:1 / -1}

/* ---------- the sequence ---------- */
.sec-h{display:flex;flex-wrap:wrap;align-items:baseline;gap:4px 12px;margin-top:34px}
.sec-h h2{font:600 22px/1.2 var(--f-display);letter-spacing:-.01em}
.sec-h p{font-size:13px;color:var(--ink-3)}
.seq{position:relative;margin-top:18px}
.seq::before{content:"";position:absolute;top:14px;bottom:40px;inset-inline-start:15px;width:2px;border-radius:2px;background:var(--line)}
.step{position:relative;padding-inline-start:48px;padding-bottom:36px}
.step-node{position:absolute;top:0;inset-inline-start:0;display:grid;place-items:center;width:32px;height:32px;border-radius:50%;background:var(--surface);box-shadow:inset 0 0 0 2px var(--line), var(--e1);font-size:13px;font-weight:800;color:var(--ink-2);transition:background-color .2s var(--ease), color .2s var(--ease), box-shadow .2s var(--ease)}
.step.is-ready .step-node{background:var(--navy);color:#fff;box-shadow:0 0 0 4px var(--bg), var(--e1)}
.step-head{display:flex;align-items:flex-start;gap:12px;min-height:32px}
.step-title{flex:1;min-width:0}
.step-title h3{font-size:17px;font-weight:700;line-height:1.3}
.step-when{display:flex;align-items:center;gap:6px;margin-top:3px;font-size:12.5px;color:var(--ink-3)}
.step-when .ic{width:14px;height:14px}
.step-beat{display:flex;flex-wrap:wrap;align-items:center;gap:6px;margin-top:6px;font-size:12px;color:var(--ink-2)}
.step-beat .bk{display:inline-flex;align-items:center;height:20px;padding:0 7px;border-radius:5px;background:var(--peach-50);color:var(--coral-ink);font-size:10.5px;font-weight:800;letter-spacing:.04em;text-transform:uppercase}
.badge{display:inline-flex;align-items:center;gap:6px;flex:none;height:26px;padding:0 10px;border-radius:999px;font-size:12px;font-weight:700;white-space:nowrap}
.badge .ic{width:14px;height:14px;stroke-width:2.4}
.badge.is-ready{background:var(--ok-50);color:var(--ok)}
.badge.is-missing,.badge.is-long{background:#FFF0EB;color:var(--coral-ink)}
.subj{display:flex;align-items:center;gap:10px;margin-top:14px;padding:9px 8px 9px 14px;border-radius:var(--r-md);background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft)}
html[dir="rtl"] .subj{padding:9px 14px 9px 8px}
.subj-k{flex:none;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-3)}
.subj-v{flex:1;min-width:0;font-size:14px;font-weight:600}
.paper{margin-top:10px;padding:22px 24px;border-radius:var(--r-md);background:var(--surface);box-shadow:var(--e1), inset 0 0 0 1px var(--line-soft);font-size:15px;line-height:1.65;color:var(--ink)}
.paper p + p,.paper p + ul,.paper ul + p,.paper p + ol,.paper ol + p{margin-top:12px}
.paper li{position:relative;padding-inline-start:18px}
.paper li + li{margin-top:4px}
.paper ul li::before{content:"";position:absolute;inset-inline-start:3px;top:.68em;width:6px;height:6px;border-radius:50%;background:var(--coral)}
.paper ol{counter-reset:n}
.paper ol li{counter-increment:n;padding-inline-start:24px}
.paper ol li::before{content:counter(n) ".";position:absolute;inset-inline-start:0;font-weight:700;color:var(--coral-ink);font-variant-numeric:tabular-nums}
.paper .sign{color:var(--ink-2)}
.paper a,.bubble a{color:var(--coral-ink);text-decoration:underline;text-underline-offset:3px;word-break:break-all}
html[lang="ar"] .paper{font-size:16px;line-height:1.9}
.paper[dir="ltr"]{text-align:left}
.paper-edit{display:block;width:100%;min-height:280px;margin-top:10px;padding:20px 22px;border:1px solid var(--coral);border-radius:var(--r-md);background:#fff;font-size:14.5px;line-height:1.65;resize:vertical;box-shadow:0 0 0 3px rgba(230,77,68,.14)}
.paper-edit:focus{outline:0}
/* merged fields: filled values carry a highlighter mark, empty ones a dashed slot */
.v{border-radius:2px;background:linear-gradient(transparent 58%, rgba(247,208,181,.85) 0)}
.v.miss,.bubble .miss{padding:0 4px;border-radius:4px;background:#FFF3EF;color:var(--coral-ink);outline:1px dashed rgba(212,65,59,.55);outline-offset:-1px;font-weight:600}
.step-foot{display:flex;flex-wrap:wrap;align-items:center;gap:10px 16px;margin-top:12px}
.meter{display:flex;align-items:center;gap:10px;font-size:12px;color:var(--ink-3)}
.meter-bar{position:relative;flex:none;width:96px;height:6px;border-radius:3px;background:var(--line-soft)}
.meter-bar i{position:absolute;inset:0;overflow:hidden;border-radius:inherit;background:var(--navy);transform-origin:0 50%;transform:scaleX(var(--p, 0));transition:transform .3s var(--ease)}
.meter-bar b{position:absolute;top:-3px;bottom:-3px;width:2px;border-radius:1px;background:var(--coral);inset-inline-start:calc(var(--r, 1) * 100%)}
html[dir="rtl"] .meter-bar i{transform-origin:100% 50%}
.meter.is-over .meter-bar i{background:var(--coral-btn)}
.meter.is-over{color:var(--coral-ink);font-weight:600}
.meter.is-soft{color:var(--warn)}
.link-chip{display:inline-flex;align-items:center;gap:6px;min-width:0;font-size:12px;color:var(--ink-3)}
.link-chip code{max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:2px 6px;border-radius:5px;background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft);font:500 11.5px/1.4 ui-monospace, "Cascadia Code", Consolas, monospace;color:var(--ink-2)}
.link-chip .btn-icon{width:28px;height:28px}
.step-actions{display:flex;gap:8px;margin-inline-start:auto}
.edited-hint{margin-top:8px;font-size:12px;color:var(--warn)}
.why{display:flex;gap:8px;margin-top:12px;max-width:68ch;font-size:12.5px;line-height:1.55;color:var(--ink-3)}
.why .ic{margin-top:2px;color:var(--coral)}

/* ---------- ads and posts (C, D) ---------- */
.ads{display:grid;gap:26px;margin-top:18px}
.ad{display:grid;grid-template-columns:minmax(0, 330px) minmax(0, 1fr);gap:22px;align-items:start;padding:18px;border-radius:var(--r-lg);background:var(--surface);box-shadow:var(--e2), inset 0 0 0 1px var(--line-soft)}
.ad.is-wide{grid-template-columns:minmax(0, 1fr)}
.ad-k{grid-column:1 / -1;display:grid;gap:4px}
.ad-k h3{font-size:15px;font-weight:700}
.ad-k p{max-width:72ch;font-size:12.5px;line-height:1.5;color:var(--ink-3)}
.feed{overflow:hidden;border-radius:10px;background:#fff;box-shadow:0 0 0 1px var(--line), var(--e1);font-size:13px;line-height:1.5}
.feed-h{display:flex;align-items:center;gap:9px;padding:11px 12px 6px}
.feed-h .page-av{flex:none;width:34px;height:34px;border-radius:8px;box-shadow:0 0 0 1px var(--line-soft)}
.feed-h strong{display:block;font-size:13px}
.feed-h span{display:block;font-size:11px;color:var(--ink-3)}
.feed-t{padding:2px 12px 10px;white-space:pre-wrap}
.feed-t button{border:0;background:none;padding:0;color:var(--ink-3);font-weight:600}
.feed img{width:100%;height:auto;aspect-ratio:4 / 5;object-fit:cover;background:var(--panel)}
.feed-f{display:flex;align-items:center;gap:10px;padding:10px 12px;background:#F3F2EF}
.feed-f div{flex:1;min-width:0}
.feed-f strong{display:block;font-size:13px;line-height:1.3}
.feed-f span{display:block;font-size:11px;color:var(--ink-3)}
.feed-f .cta{flex:none;height:30px;padding:0 14px;border-radius:999px;box-shadow:inset 0 0 0 1.5px #0A66C2;color:#0A66C2;font-size:12.5px;font-weight:700;line-height:30px;white-space:nowrap}
.vtoggle{display:flex;gap:4px;margin-top:10px;width:max-content}
.fields{display:grid;gap:12px;min-width:0}
.fields-grid{grid-template-columns:repeat(auto-fit, minmax(210px, 1fr))}
.fld{padding:12px 14px;border-radius:var(--r-md);background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft);min-width:0}
.fld-h{display:flex;align-items:center;gap:8px}
.fld-h b{font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-3)}
.fld-h .btn-icon{margin-inline-start:auto;width:28px;height:28px}
.fld p{margin-top:6px;font-size:13.5px;line-height:1.55;white-space:pre-wrap;overflow-wrap:anywhere}
.fld p.mono{font:500 12px/1.5 ui-monospace, "Cascadia Code", Consolas, monospace;color:var(--ink-2)}
.fld .meter{margin-top:8px}
.carousel{display:grid;grid-template-columns:repeat(4, minmax(0, 1fr));gap:10px}
.card{overflow:hidden;border-radius:10px;background:#fff;box-shadow:0 0 0 1px var(--line), var(--e1)}
.card img{width:100%;height:auto;aspect-ratio:4 / 5;object-fit:cover;background:var(--panel)}
.card figcaption{padding:8px 10px 10px;font-size:12px;font-weight:600;line-height:1.4}
.posts{display:grid;gap:14px}
.post{padding:16px 18px;border-radius:var(--r-md);background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft)}
.post-h{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
.post-h h4{font-size:14px;font-weight:700}
.post-h .btn{margin-inline-start:auto}
.post-t{margin-top:10px;font-size:14px;line-height:1.65;white-space:pre-wrap;overflow-wrap:anywhere}
.post-t .hook{font-weight:700;background:linear-gradient(transparent 60%, rgba(247,208,181,.7) 0)}
.post .meter{margin-top:10px}

/* ---------- toolkit blocks ---------- */
.block{margin-top:22px;padding-top:30px;border-top:1px solid var(--line)}
.block > h2{font:600 22px/1.2 var(--f-display);letter-spacing:-.01em}
.block > .lede{max-width:66ch;margin-top:8px;font-size:14px;color:var(--ink-2)}
.replies{margin-top:16px;border-top:1px solid var(--line-soft)}
.reply{display:grid;grid-template-columns:minmax(0, .8fr) minmax(0, 1.6fr) auto;gap:6px 22px;padding:16px 0;border-bottom:1px solid var(--line-soft)}
.reply-q{font:600 15px/1.4 var(--f-display);color:var(--ink)}
.reply-a{font-size:14px;line-height:1.6;color:var(--ink)}
.reply-after{grid-column:2;display:flex;gap:6px;font-size:12px;color:var(--ink-3)}
.reply-after .ic{width:14px;height:14px;margin-top:2px}
.reply .btn-icon{grid-row:1;grid-column:3}
.qualify{margin-top:18px;padding:16px 18px;border-radius:var(--r-md);background:var(--panel);box-shadow:inset 0 0 0 1px var(--line-soft)}
.qualify h3{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700}
.qualify h3 .ic{color:var(--ok)}
.qualify ol{display:grid;gap:5px;margin-top:10px;counter-reset:q}
.qualify li{position:relative;padding-inline-start:26px;font-size:13.5px;line-height:1.5;color:var(--ink-2);counter-increment:q}
.qualify li::before{content:counter(q);position:absolute;inset-inline-start:0;top:1px;display:grid;place-items:center;width:18px;height:18px;border-radius:50%;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:10.5px;font-weight:700;color:var(--ink-3)}
.subjects{width:100%;margin-top:16px;border-collapse:collapse;font-size:14px}
.subjects th{padding:0 10px 10px;text-align:start;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-3)}
.subjects td{padding:11px 10px;border-top:1px solid var(--line-soft);vertical-align:middle}
.subjects td:first-child{font-weight:600}
.subjects .len{width:64px;font-size:12.5px;color:var(--ink-3);font-variant-numeric:tabular-nums}
.subjects .tagc{width:160px}
.subjects .act{width:44px}
.tg{display:inline-flex;align-items:center;height:22px;padding:0 8px;border-radius:999px;font-size:11px;font-weight:700;white-space:nowrap}
.tg-v1{background:var(--ok-50);color:var(--ok)}
.tg-v2{background:#E6EEF8;color:#24518A}
.tg-opt{background:var(--field);color:var(--ink-2)}
.tg-hold{background:var(--warn-50);color:var(--warn)}
.tg-off{background:#F1EEEA;color:var(--ink-3)}
.subjects tr.is-off td:first-child{color:var(--ink-3);text-decoration:line-through;text-decoration-color:rgba(184,53,46,.55);font-weight:500}
.subjects tr.is-hold td:first-child{color:var(--ink-2)}
.subjects .why-off{display:inline-block;width:100%;margin-top:2px;font-size:12px;font-weight:500;color:var(--ink-3)}
.play{display:grid;grid-template-columns:repeat(2, minmax(0, 1fr));gap:26px 34px;margin-top:20px}
.play h3{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:700}
.play h3 .ic{color:var(--coral)}
.play ul{display:grid;gap:7px;margin-top:10px}
.play li{position:relative;padding-inline-start:16px;font-size:13.5px;line-height:1.55;color:var(--ink-2)}
.play li::before{content:"";position:absolute;inset-inline-start:2px;top:.62em;width:5px;height:5px;border-radius:50%;background:var(--ink-3)}
.play .src{margin-top:8px;font-size:11.5px;color:var(--ink-3)}
.play .src b{color:var(--ink-2)}
.wide{grid-column:1 / -1}
.tbl{width:100%;margin-top:10px;border-collapse:collapse;font-size:13px}
.tbl th{padding:0 10px 8px;text-align:start;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-3)}
.tbl td{padding:10px;border-top:1px solid var(--line-soft);vertical-align:top;line-height:1.5;color:var(--ink-2)}
.tbl td:first-child{font-weight:700;color:var(--ink)}
.tbl tr.is-cur td{background:var(--peach-50)}

/* the close: an input */
.next{margin-top:22px;padding:24px 24px 26px;border-radius:var(--r-lg);background:var(--navy);color:#fff;box-shadow:var(--e3)}
.next :focus-visible{outline-color:var(--peach)}
.next h2{font:600 22px/1.2 var(--f-display)}
.next > p{max-width:64ch;margin-top:8px;font-size:14px;color:rgba(255,255,255,.78)}
.next-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}
.next .btn-ghost{background:rgba(255,255,255,.08);color:#fff;box-shadow:inset 0 0 0 1px rgba(255,255,255,.2)}
.next-form{margin-top:18px}
.next-form label{font-size:12.5px;font-weight:600;color:rgba(255,255,255,.78)}
.next-input{display:flex;gap:8px;margin-top:6px;max-width:460px}
.next-input input{flex:1;min-width:0;height:44px;padding:0 14px;border:1px solid rgba(255,255,255,.22);border-radius:var(--r-sm);background:rgba(255,255,255,.06);color:#fff;font-size:15px;caret-color:var(--peach)}
.next-input input:focus{outline:0;border-color:var(--peach);box-shadow:0 0 0 3px rgba(247,208,181,.22)}
.next-input .btn{height:44px;padding:0 18px}
@media (hover:hover) and (pointer:fine){ .next .btn-ghost:hover{background:rgba(255,255,255,.14);box-shadow:inset 0 0 0 1px rgba(255,255,255,.3)} }
.foot{margin-top:26px;padding-top:16px;border-top:1px solid var(--line);font-size:12px;line-height:1.6;color:var(--ink-3)}

/* ---------- inbox preview: the conversation writes itself ---------- */
.inbox{position:sticky;top:var(--bar-h);display:flex;flex-direction:column;height:calc(100dvh - var(--bar-h) - var(--status-h));border-inline-start:1px solid var(--line);background:var(--panel)}
.inbox-head{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--line);background:var(--surface)}
.av{display:grid;place-items:center;flex:none;width:38px;height:38px;border-radius:50%;background:var(--navy);color:#fff;font-size:14px;font-weight:700}
.av-me{background:var(--coral-btn)}
.inbox-who{flex:1;min-width:0;line-height:1.3}
.inbox-who strong{display:block;overflow:hidden;font-size:14px;text-overflow:ellipsis;white-space:nowrap}
.inbox-who span{display:block;overflow:hidden;font-size:12px;color:var(--ink-3);text-overflow:ellipsis;white-space:nowrap}
.inbox-k{flex:none;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-3)}
.inbox-close{display:none}
.thread{flex:1;overflow:auto;padding:16px 16px 24px;overscroll-behavior:contain}
.sys{display:flex;justify-content:center;margin:16px 0 10px}
.sys span{padding:3px 10px;border-radius:999px;background:var(--line-soft);font-size:11px;font-weight:600;color:var(--ink-2)}
.invite{padding:12px 14px;border-radius:var(--r-md);background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:13px;line-height:1.55}
.invite-k{display:flex;align-items:center;gap:6px;margin-bottom:6px;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--ink-3)}
.invite-k .ic{width:14px;height:14px}
.msg{display:flex;align-items:flex-end;gap:8px}
.msg .av{width:28px;height:28px;font-size:11px}
.bubble{flex:1;min-width:0;padding:11px 13px;border-radius:14px 14px 14px 4px;background:var(--surface);box-shadow:var(--e1), inset 0 0 0 1px var(--line-soft);font-size:13px;line-height:1.55}
html[dir="rtl"] .bubble{border-radius:14px 14px 4px 14px}
.bubble p + p{margin-top:8px}
html[lang="ar"] .bubble,html[lang="ar"] .invite{font-size:14px;line-height:1.8}
.thread .is-new{animation:land .42s var(--ease) both}
@keyframes land{from{opacity:0;transform:translate3d(0, 10px, 0) scale(.98)}to{opacity:1;transform:none}}
.inbox-foot{padding:12px 16px 14px;border-top:1px solid var(--line);background:var(--surface)}
.prog{display:grid;grid-template-columns:repeat(5, 1fr);gap:4px}
.prog i{height:4px;border-radius:2px;background:var(--line-soft);transition:background-color .3s var(--ease)}
.prog i.on{background:var(--navy)}
.inbox-state{margin-top:8px;font-size:12px;color:var(--ink-3)}
.inbox-done{display:none;margin-top:10px}
.inbox.is-complete .prog i.on{background:var(--ok)}
.inbox.is-complete .inbox-done{display:grid;gap:10px}
.inbox-done p{display:flex;gap:8px;font-size:13px;font-weight:600;line-height:1.45;color:var(--ok)}
.inbox-done p .ic{margin-top:2px}
.inbox-done .btn{width:100%}
.inbox-gate{margin-top:8px;font-size:12px;font-weight:600;line-height:1.45;color:var(--coral-ink)}
.scrim{position:fixed;inset:0;z-index:44;background:rgba(24,23,47,.42)}

/* ---------- status bar ---------- */
.status{position:fixed;inset-inline:0;bottom:0;z-index:45;display:flex;align-items:center;gap:14px;height:var(--status-h);padding:0 20px;background:var(--navy);color:rgba(255,255,255,.86);font-size:12.5px}
.status .s-dot{flex:none;width:8px;height:8px;border-radius:50%;background:var(--coral)}
.status.is-ok .s-dot{background:#6BC495}
.status b{color:#fff;font-weight:700}
.status [data-s-main]{flex:none;white-space:nowrap}
.status .s-gate{min-width:0;overflow:hidden;color:#F7B9A4;text-overflow:ellipsis;white-space:nowrap}
.status.is-ok .s-gate{color:#A9DFC0}
.status .s-next{margin-inline-start:auto;white-space:nowrap}
.toast{position:fixed;left:50%;bottom:calc(var(--status-h) + 16px);z-index:60;max-width:calc(100vw - 32px);padding:10px 16px;border-radius:10px;background:var(--ink);color:#fff;font-size:13px;font-weight:600;box-shadow:var(--e3);opacity:0;transform:translate(-50%, 8px);pointer-events:none;transition:opacity .18s var(--ease), transform .25s var(--ease)}
.toast.is-on{opacity:1;transform:translate(-50%, 0)}
.noscript{margin:20px;padding:16px;border-radius:var(--r-md);background:var(--warn-50);color:var(--warn);font-weight:600}

/* ---------- responsive ---------- */
@media (max-width:1279px){
  :root{--side-w:220px}
  .app{grid-template-columns:var(--side-w) minmax(0, 1fr)}
  .inbox-toggle{display:inline-flex}
  .inbox{position:fixed;top:var(--bar-h);bottom:var(--status-h);inset-inline-end:0;z-index:46;width:min(400px, 92vw);height:auto;box-shadow:var(--e3);transform:translateX(105%);visibility:hidden;transition:transform .3s var(--ease), visibility 0s linear .3s}
  html[dir="rtl"] .inbox{transform:translateX(-105%)}
  .inbox.is-open{transform:none;visibility:visible;transition:transform .3s var(--ease), visibility 0s}
  html[dir="rtl"] .inbox.is-open{transform:none}
  .inbox-close{display:inline-grid}
}
@media (max-width:1100px){ .bar-title span{display:none} .seg-camp .seg-name{display:none} }
@media (max-width:900px){
  :root{--bar-h:112px}
  .bar{flex-wrap:wrap;align-content:center;gap:8px 12px;padding:8px 16px}
  .bar-title{padding-inline-start:12px}
  .bar-ctl{display:contents}
  .bar-ctl > .seg-lang{order:3;margin-inline-start:auto}
  .inbox-toggle{order:4}
  .seg-camp{order:5;width:100%}
  .seg-camp button{flex:1;justify-content:center}
  .seg-camp .seg-name{display:inline}
  .app{grid-template-columns:minmax(0, 1fr)}
  .side{top:var(--bar-h);z-index:30;display:flex;gap:6px;height:auto;padding:8px 12px;overflow-x:auto;overflow-y:hidden;border:0;border-bottom:1px solid var(--line);background:rgba(240,237,233,.96);-webkit-backdrop-filter:blur(6px);backdrop-filter:blur(6px)}
  .side-h{display:none}
  .side-h + *{margin:0}
  .nav-steps,.nav-tools{display:flex;gap:6px}
  .nav-steps::before{display:none}
  .nav-steps a,.nav-tools a{grid-template-columns:auto auto;padding:6px 10px 6px 6px;white-space:nowrap;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line-soft)}
  html[dir="rtl"] .nav-steps a,html[dir="rtl"] .nav-tools a{padding:6px 6px 6px 10px}
  .nav-steps .dot{width:22px;height:22px}
  .nav-d{display:none}
  .nav-tools a{padding:6px 10px}
  html{scroll-padding-top:calc(var(--bar-h) + 70px)}
  .brief-grid{grid-template-columns:1fr 1fr}
  .beats{grid-template-columns:1fr 1fr}
  .ad{grid-template-columns:minmax(0, 1fr)}
  .ad .feed{max-width:360px}
}
@media (max-width:640px){
  .main{padding:20px 16px 32px}
  .brief{padding:18px}
  .brief h1{font-size:22px}
  .brief-grid{grid-template-columns:1fr}
  .pr-grid{grid-template-columns:repeat(2, minmax(0, 1fr))}
  .field,.field-3,.pr-grid[data-c] .field{grid-column:span 2}
  .groups{grid-template-columns:1fr}
  .group + .group{border-inline-start:0;border-top:1px solid var(--line)}
  .release-grid{grid-template-columns:1fr}
  .panel{padding:16px}
  .pr-actions{margin-inline-start:0;width:100%}
  .pr-actions .btn{flex:1}
  .seq::before{inset-inline-start:13px}
  .step{padding-inline-start:40px}
  .step-node{width:28px;height:28px;font-size:12px}
  .paper{padding:16px}
  .paper-edit{padding:14px}
  .step-actions{width:100%;margin:0}
  .step-actions .btn{flex:1}
  .step-actions .btn-primary{flex:2}
  .subj{flex-wrap:wrap}
  .ad{padding:14px}
  .carousel{grid-template-columns:1fr 1fr}
  .reply{grid-template-columns:1fr auto}
  .reply-q{grid-column:1}
  .reply-a,.reply-after{grid-column:1 / -1}
  .reply .btn-icon{grid-row:1;grid-column:2}
  .subjects .tagc,.subjects th:nth-child(3){display:none}
  .play{grid-template-columns:1fr}
  .tbl,.tbl tbody,.tbl tr,.tbl td{display:block;width:auto !important}
  .tbl thead{display:none}
  .tbl tr{padding:10px 0;border-top:1px solid var(--line-soft)}
  .tbl td{padding:2px 0;border:0}
  .seg button{padding:0 9px}
  .status{gap:10px;padding:0 14px;font-size:12px}
  .status .s-next{display:none}
  .next{padding:20px 18px 22px}
}
@media (max-width:420px){ .bar-title{display:none} .seg-camp .seg-name{display:none} }
@media (prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  .thread .is-new{animation:none}
  .inbox,.meter-bar i,.btn,.seg button,.toast,.step-node,.switch,.switch::after{transition:none}
}
</style>
<script src="assets/js/fq-ads.js?v=cdc94123"></script>
<script src="assets/js/f360-measurement.js?v=2098c26b" defer></script>
<script src="assets/js/fq-meta-events.js?v=9d59311c" defer></script>
<script src="assets/js/f360-formopen.js?v=5013e2fa" defer></script>
<script src="assets/js/fq-snap.js?v=35fb0392" async></script>
<script src="assets/js/linkedin-insight.js?v=16b728a3" defer></script>
<script src="assets/js/fandaqah-tiktok.js?v=8c021881" defer></script>
</head>
<body>
<a class="skip" href="#main" data-i18n="skip">Skip to the kit</a>
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
<!-- Lucide icons v0.469.0, ISC licence, https://lucide.dev (the icon family used across the campaign site) -->
<symbol id="i-copy" viewBox="0 0 24 24"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></symbol>
<symbol id="i-pencil-line" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/><path d="m15 5 3 3"/></symbol>
<symbol id="i-rotate-ccw" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></symbol>
<symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></symbol>
<symbol id="i-circle-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></symbol>
<symbol id="i-inbox" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></symbol>
<symbol id="i-user-plus" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></symbol>
<symbol id="i-reply" viewBox="0 0 24 24"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></symbol>
<symbol id="i-book-open" viewBox="0 0 24 24"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></symbol>
<symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
<symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></symbol>
<symbol id="i-link" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
<symbol id="i-route" viewBox="0 0 24 24"><circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/></symbol>
<symbol id="i-shield-check" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></symbol>
<symbol id="i-calendar-days" viewBox="0 0 24 24"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></symbol>
<symbol id="i-arrow-right" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></symbol>
<symbol id="i-list-checks" viewBox="0 0 24 24"><path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/></symbol>
<symbol id="i-circle-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></symbol>
<symbol id="i-type" viewBox="0 0 24 24"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" x2="15" y1="20" y2="20"/><line x1="12" x2="12" y1="4" y2="20"/></symbol>
<symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
<symbol id="i-megaphone" viewBox="0 0 24 24"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></symbol>
<symbol id="i-download" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></symbol>
<symbol id="i-badge-check" viewBox="0 0 24 24"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></symbol>
</svg>

<header class="bar">
  <a class="brand" href="./" data-i18n-aria="aria.site" aria-label="Fandaqah 360 campaign site"><img src="assets/img/brand-dark-340.webp" width="340" height="146" alt="Fandaqah"></a>
  <div class="bar-title"><strong data-i18n="kit.title">LinkedIn kit</strong><span data-i18n="kit.sub">Fandaqah 360 · Campaigns A to D · Internal</span></div>
  <div class="bar-ctl">
    <div class="seg seg-camp" role="group" data-i18n-aria="aria.camp" aria-label="Campaign">
      <button type="button" data-camp="A" aria-pressed="false"><span class="seg-code">A</span><span class="seg-name" data-i18n="camp.A">Existing</span></button>
      <button type="button" data-camp="B" aria-pressed="false"><span class="seg-code">B</span><span class="seg-name" data-i18n="camp.B">New hotels</span></button>
      <button type="button" data-camp="C" aria-pressed="true" class="is-primary"><span class="seg-code">C</span><span class="seg-name" data-i18n="camp.C">Opera PMS</span></button>
      <button type="button" data-camp="D" aria-pressed="false" class="is-primary"><span class="seg-code">D</span><span class="seg-name" data-i18n="camp.D">Groups</span></button>
    </div>
    <div class="seg seg-lang" role="group" data-i18n-aria="aria.lang" aria-label="Message language">
      <button type="button" data-lang="ar" lang="ar" aria-pressed="false">عربي</button>
      <button type="button" data-lang="en" lang="en" aria-pressed="true">EN</button>
    </div>
  </div>
  <button class="inbox-toggle" type="button" aria-controls="inbox" aria-expanded="false"><svg class="ic" aria-hidden="true"><use href="#i-inbox"/></svg><span data-i18n="inbox.toggle">Inbox</span><b class="count num" data-inbox-count>0</b></button>
</header>

<div class="app">
  <nav class="side thin-scroll" data-i18n-aria="aria.kit" aria-label="Kit">
    <p class="side-h" data-i18n="side.acct">Account</p>
    <ul class="nav-tools">
      <li><a href="#account" data-spy="account"><svg class="ic" aria-hidden="true"><use href="#i-route"/></svg><span class="nav-l" data-i18n="nav.account">Route and clear</span></a></li>
    </ul>
    <p class="side-h" data-i18n="side.seq">Sequence</p>
    <ol class="nav-steps" data-nav-steps></ol>
    <p class="side-h" data-i18n="side.tools">Toolkit</p>
    <ul class="nav-tools">
      <li data-only="CD"><a href="#ads" data-spy="ads"><svg class="ic" aria-hidden="true"><use href="#i-megaphone"/></svg><span class="nav-l" data-i18n="nav.ads">Ads and posts</span></a></li>
      <li><a href="#replies" data-spy="replies"><svg class="ic" aria-hidden="true"><use href="#i-reply"/></svg><span class="nav-l" data-i18n="nav.replies">Replies</span></a></li>
      <li><a href="#subjects" data-spy="subjects"><svg class="ic" aria-hidden="true"><use href="#i-type"/></svg><span class="nav-l" data-i18n="nav.subjects">Headlines</span></a></li>
      <li><a href="#playbook" data-spy="playbook"><svg class="ic" aria-hidden="true"><use href="#i-book-open"/></svg><span class="nav-l" data-i18n="nav.playbook">Playbook</span></a></li>
      <li><a href="#next" data-spy="next"><svg class="ic" aria-hidden="true"><use href="#i-user-plus"/></svg><span class="nav-l" data-i18n="nav.next">Next account</span></a></li>
    </ul>
  </nav>

  <main class="main" id="main">
    <div class="main-in">
      <section class="brief" aria-labelledby="brief-h" data-brief></section>

      <section class="panel" id="account" aria-labelledby="account-h">
        <div class="pr-head">
          <h2 id="account-h" data-i18n="acct.title">Account</h2>
          <span class="chip-demo" data-demo-chip hidden data-i18n="acct.demo">Demo account</span>
          <div class="pr-actions">
            <button class="btn btn-ghost btn-sm" type="button" data-load-demo><svg class="ic" aria-hidden="true"><use href="#i-user-plus"/></svg><span data-i18n="acct.loadDemo">Load demo</span></button>
            <button class="btn btn-ghost btn-sm" type="button" data-clear><svg class="ic" aria-hidden="true"><use href="#i-rotate-ccw"/></svg><span data-i18n="acct.clear">Clear</span></button>
          </div>
        </div>
        <form class="pr-grid" data-form autocomplete="off" onsubmit="return false">
          <div class="field" data-f="first"><label for="f-first" data-i18n="f.first">Contact’s first name</label><input id="f-first" name="first" maxlength="40" spellcheck="false"></div>
          <div class="field" data-f="company"><label for="f-company" data-company-label>Hotel name</label><input id="f-company" name="company" maxlength="70" spellcheck="false"></div>
          <div class="field" data-f="city"><label for="f-city" data-i18n="f.city">City</label><input id="f-city" name="city" maxlength="40" list="cities" spellcheck="false"></div>
          <div class="field" data-f="rep"><label for="f-rep" data-i18n="f.rep">Your name</label><input id="f-rep" name="rep" maxlength="50" spellcheck="false"></div>
          <div class="field" data-f="date"><label for="f-date" data-i18n="f.date">First message on</label><input id="f-date" name="date" type="date" required><p class="hint warn" data-weekend hidden></p></div>
          <div class="field" data-f="account" data-only="A"><label for="f-account"><span data-i18n="f.account">Account ID</span> <span class="opt" data-i18n="f.crmOnly">CRM only</span></label><input id="f-account" name="account" maxlength="30" spellcheck="false" dir="ltr"></div>
          <div class="field" data-f="plan" data-only="A"><label for="f-plan" data-i18n="f.plan">Current plan</label><input id="f-plan" name="plan" maxlength="50" list="plans" spellcheck="false"></div>
          <div class="field" data-f="renewal" data-only="A"><label for="f-renewal" data-i18n="f.renewal">Renewal date</label><input id="f-renewal" name="renewal" type="date"><p class="hint warn" data-renewal-warn hidden></p></div>
          <div class="field field-6" data-f="modules" data-only="A"><span class="lbl" id="mods-l"><span data-i18n="f.modules">Modules they don’t use yet</span> <span class="opt" data-i18n="f.modulesHint">pick up to three</span></span><div class="chips" role="group" aria-labelledby="mods-l" data-modules></div></div>
          <div class="field" data-f="pms" data-only="C"><label for="f-pms" data-i18n="f.pms">Installed PMS</label><input id="f-pms" name="pms" maxlength="50" list="pmsList" spellcheck="false"></div>
          <div class="field field-3" data-f="persona" data-only="C"><span class="lbl" id="persona-l" data-i18n="f.persona">Writing to</span><div class="mini-seg" role="group" aria-labelledby="persona-l"><button type="button" data-persona="GM" aria-pressed="true" data-i18n="f.personaGM">GM or owner</button><button type="button" data-persona="IT" aria-pressed="false" data-i18n="f.personaIT">IT manager or director</button><button type="button" data-persona="FIN" aria-pressed="false" data-i18n="f.personaFIN">Finance director</button></div></div>
          <div class="field" data-f="count" data-only="D"><label for="f-count" data-i18n="f.count">Properties in the group</label><select id="f-count" name="count"><option value="" data-i18n="f.countPick">Choose</option><option value="10" data-i18n="f.count10">About 10</option><option value="10-20" data-i18n="f.count20">10 to 20</option><option value="21-50" data-i18n="f.count50">21 to 50</option><option value="50+" data-i18n="f.countMore">More than 50</option></select></div>
          <div class="field field-6" data-f="note"><label for="f-note"><span data-i18n="f.note">Personal line</span> <span class="opt" data-i18n="f.opt">optional</span></label>
            <textarea id="f-note" name="note" rows="2" maxlength="160"></textarea>
            <p class="hint"><span data-note-hint data-i18n="f.noteHint">One sentence only you could write: a recent opening, a post, an award.</span><span class="count" data-note-count>0/160</span></p>
          </div>
        </form>
        <datalist id="cities"></datalist><datalist id="plans"></datalist><datalist id="pmsList"></datalist>

        <div class="groups">
          <div class="group" data-router>
            <h3><svg class="ic" aria-hidden="true"><use href="#i-route"/></svg><span data-i18n="route.title">Route the account</span></h3>
            <p class="sub" data-i18n="route.sub">One primary campaign per account (playbook p.1 and p.6).</p>
            <div class="q"><span id="q-ex" data-i18n="route.q1">Already a Fandaqah customer?</span><div class="mini-seg" role="group" aria-labelledby="q-ex" data-q="existing"><button type="button" data-v="yes" data-i18n="route.yes">Yes</button><button type="button" data-v="no" data-i18n="route.no">No</button></div></div>
            <div class="q"><span id="q-pr" data-i18n="route.q2">Properties under this owner or operator</span><div class="mini-seg" role="group" aria-labelledby="q-pr" data-q="props"><button type="button" data-v="1" data-i18n="route.p1">One</button><button type="button" data-v="2-9" data-i18n="route.p2">2 to 9</button><button type="button" data-v="10+" data-i18n="route.p3">About 10 or more</button></div></div>
            <div class="q"><span id="q-pms" data-i18n="route.q3">Installed PMS, as confirmed</span><div class="mini-seg" role="group" aria-labelledby="q-pms" data-q="pms"><button type="button" data-v="mandated" data-i18n="route.pm1">Mandated (Opera)</button><button type="button" data-v="other" data-i18n="route.pm2">Not mandated</button><button type="button" data-v="unknown" data-i18n="route.pm3">Unknown</button></div></div>
            <div class="verdict" data-verdict></div>
          </div>
          <div class="group" data-gate>
            <h3><svg class="ic" aria-hidden="true"><use href="#i-shield-check"/></svg><span data-i18n="gate.title">Clear to send</span></h3>
            <p class="sub" data-i18n="gate.sub">Release only records eligible for this channel (playbook p.6).</p>
            <div class="q"><label for="g-ver" data-i18n="gate.ver">Verification status</label>
              <select id="g-ver" class="sel" data-g="ver">
                <option value="verified" data-i18n="gate.v1">Verified identity and business route</option>
                <option value="shared" data-i18n="gate.v2">Shared hotel route only</option>
                <option value="unverified" data-i18n="gate.v3">Unverified</option>
                <option value="invalid" data-i18n="gate.v4">Invalid</option>
              </select></div>
            <label class="check"><input type="checkbox" data-g="perm"><span data-i18n="gate.perm">Marketing permission recorded, with LinkedIn as a permitted channel</span></label>
            <label class="check"><input type="checkbox" data-g="clean"><span data-i18n="gate.clean">Not opted out, not suppressed, no other campaign assigned</span></label>
            <label class="check"><input type="checkbox" data-g="extra"><span data-gate-extra></span></label>
            <p class="clear" data-clear-state></p>
          </div>
        </div>

        <div class="release" data-release data-only="AB">
          <div class="release-head">
            <div><h3 data-i18n="rel.title">Commercial release record</h3><p data-i18n="rel.sub">A and B only. Until it is signed, messages say “Ask about our launch pricing” and no price appears (playbook p.11).</p></div>
            <button class="switch" type="button" role="switch" aria-checked="false" data-rel-switch data-i18n-aria="rel.switch" aria-label="Commercial release confirmed"></button>
          </div>
          <div class="release-grid" data-rel-fields hidden>
            <div class="field"><label for="r-annual" data-i18n="rel.annual">SAR per year</label><input id="r-annual" data-rel="annual" inputmode="decimal" dir="ltr"></div>
            <div class="field"><label for="r-vat" data-i18n="rel.vat">VAT</label><select id="r-vat" data-rel="vat"><option value="plus" data-i18n="rel.vatPlus">Plus VAT</option><option value="incl" data-i18n="rel.vatIncl">VAT included</option></select></div>
            <p class="hint" data-i18n="rel.hint">Enter the figures exactly as the signed record states them. The approved launch price is SAR 3,495 a year excl. VAT, on a one-year subscription.</p>
          </div>
        </div>
      </section>

      <div class="sec-h" id="sequence"><h2 data-i18n="seq.title">Sequence</h2><p data-i18n="seq.sub">Five touches that follow the campaign’s storyboard beat by beat.</p></div>
      <div class="seq" data-seq></div>

      <section class="block" id="ads" aria-labelledby="ads-h" data-only="CD">
        <h2 id="ads-h" data-i18n="ads.title">Ads and posts</h2>
        <p class="lede" data-i18n="ads.lede">The same story at scale, on the approved creatives. Links carry the exact tags from the campaign’s link sheet.</p>
        <div class="ads" data-ads></div>
      </section>

      <section class="block" id="replies" aria-labelledby="replies-h">
        <h2 id="replies-h" data-i18n="rep.title">Replies</h2>
        <p class="lede" data-i18n="rep.lede">When they answer, the sequence stops. Reply the same day during staffed hours, then follow the note under each answer.</p>
        <ul class="replies" data-replies></ul>
        <div class="qualify" data-qualify></div>
      </section>

      <section class="block" id="subjects" aria-labelledby="subjects-h">
        <h2 id="subjects-h" data-i18n="sub.title">Headlines</h2>
        <p class="lede" data-i18n="sub.lede">The playbook’s headline options for this campaign. Use them as InMail subjects. For the first test, change only the headline.</p>
        <table class="subjects">
          <thead><tr><th scope="col" data-i18n="sub.colLine">Headline</th><th scope="col" data-i18n="sub.colLen">Length</th><th scope="col" data-i18n="sub.colTag">Status</th><th scope="col"><span class="sr-only" data-i18n="sub.colCopy">Copy</span></th></tr></thead>
          <tbody data-subjects></tbody>
        </table>
      </section>

      <section class="block" id="playbook" aria-labelledby="playbook-h">
        <h2 id="playbook-h" data-i18n="play.title">Playbook</h2>
        <p class="lede" data-i18n="play.lede">The rules behind every line above, from the Marketing Campaign Playbook of 20 October 2026. When in doubt, the playbook wins.</p>
        <div class="play" data-play></div>
      </section>

      <section class="next" id="next" aria-labelledby="next-h">
        <h2 id="next-h" data-i18n="next.title">Next account</h2>
        <p data-i18n="next.text">Paste the full sequence into the CRM against this account, then start the next one. Your name and the date stay; everything else clears, and the new account must be routed and cleared again.</p>
        <div class="next-row">
          <button class="btn btn-ghost" type="button" data-copy-all><svg class="ic" aria-hidden="true"><use href="#i-copy"/></svg><span data-i18n="next.copyAll">Copy the CRM record</span></button>
        </div>
        <form class="next-form" data-next-form>
          <label for="n-first" data-i18n="next.label">Next contact’s first name</label>
          <div class="next-input"><input id="n-first" maxlength="40" autocomplete="off" spellcheck="false"><button class="btn btn-primary" type="submit"><span data-i18n="next.start">Start</span><svg class="ic" aria-hidden="true"><use href="#i-arrow-right"/></svg></button></div>
        </form>
      </section>

      <footer class="foot"><p data-i18n="foot">Fandaqah 360 · LinkedIn kit for campaigns A to D · Built from the Marketing Campaign Playbook (20 October 2026) · Internal · Every link uses the one campaign URL with campaign tags.</p></footer>
    </div>
  </main>

  <aside class="inbox" id="inbox" aria-labelledby="inbox-h">
    <div class="inbox-head">
      <span class="av" data-av-them aria-hidden="true">?</span>
      <div class="inbox-who"><strong id="inbox-h" data-them-name>Contact</strong><span data-them-sub></span></div>
      <span class="inbox-k" data-i18n="inbox.k">Inbox preview</span>
      <button class="btn-icon inbox-close" type="button" data-inbox-close data-i18n-aria="inbox.close" aria-label="Close preview"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button>
    </div>
    <div class="thread thin-scroll" data-thread role="log" aria-live="off"></div>
    <div class="inbox-foot">
      <div class="prog" aria-hidden="true" data-prog><i></i><i></i><i></i><i></i><i></i></div>
      <p class="inbox-state" data-inbox-state></p>
      <p class="inbox-gate" data-inbox-gate hidden></p>
      <div class="inbox-done">
        <p><svg class="ic" aria-hidden="true"><use href="#i-circle-check"/></svg><span data-inbox-done></span></p>
        <button class="btn btn-primary" type="button" data-copy-all><svg class="ic" aria-hidden="true"><use href="#i-copy"/></svg><span data-i18n="next.copyAll">Copy the CRM record</span></button>
      </div>
    </div>
  </aside>
</div>
<div class="scrim" data-scrim hidden></div>

<div class="status" data-status>
  <span class="s-dot" aria-hidden="true"></span><span data-s-main></span><span class="s-gate" data-s-gate></span><span class="s-next" data-s-next></span>
</div>
<div class="toast" role="status" aria-live="polite" data-toast></div>
<noscript><p class="noscript">This kit needs JavaScript to route the account and merge it into the messages.</p></noscript>

<script>
(() => {
  /* ---------- interface strings: English is in the markup, Arabic lives here ---------- */
  const AR = {
    'skip':'تخطَّ إلى الحزمة',
    'aria.site':'موقع حملة فندقة 360','aria.camp':'الحملة','aria.lang':'لغة الرسائل','aria.kit':'الحزمة',
    'kit.title':'حزمة لينكدإن','kit.sub':'فندقة 360 · الحملات من A إلى D · للاستخدام الداخلي',
    'camp.A':'العملاء الحاليون','camp.B':'الفنادق الجديدة','camp.C':'أنظمة Opera','camp.D':'المجموعات',
    'inbox.toggle':'المعاينة','inbox.k':'معاينة الوارد','inbox.close':'إغلاق المعاينة',
    'side.acct':'الحساب','side.seq':'التسلسل','side.tools':'الأدوات',
    'nav.account':'التوجيه والتحقق','nav.ads':'الإعلانات والمنشورات','nav.replies':'الردود','nav.subjects':'العناوين','nav.playbook':'دليل الحملة','nav.next':'الحساب التالي',
    'acct.title':'الحساب','acct.demo':'حساب تجريبي','acct.loadDemo':'تحميل مثال','acct.clear':'مسح',
    'f.first':'الاسم الأول لجهة الاتصال','f.city':'المدينة','f.rep':'اسمك','f.date':'تاريخ أول رسالة',
    'f.account':'رقم الحساب','f.crmOnly':'لنظام العملاء فقط','f.plan':'الباقة الحالية','f.renewal':'تاريخ التجديد',
    'f.modules':'وحدات لا يستخدمونها بعد','f.modulesHint':'اختر حتى ثلاث',
    'f.pms':'نظام PMS المثبّت','f.persona':'الرسالة موجّهة إلى','f.personaGM':'المدير العام أو المالك','f.personaIT':'مدير التقنية','f.personaFIN':'المدير المالي',
    'f.count':'عدد منشآت المجموعة','f.countPick':'اختر','f.count10':'نحو 10','f.count20':'من 10 إلى 20','f.count50':'من 21 إلى 50','f.countMore':'أكثر من 50',
    'f.note':'سطر شخصي','f.opt':'اختياري','f.noteHint':'جملة واحدة لا يكتبها غيرك: افتتاح حديث، منشور، جائزة.',
    'route.title':'وجّه الحساب','route.sub':'حملة رئيسية واحدة لكل حساب (دليل الحملة ص 1 وص 6).',
    'route.q1':'عميل حالي لدى فندقة؟','route.yes':'نعم','route.no':'لا',
    'route.q2':'عدد المنشآت لدى هذا المالك أو المشغّل','route.p1':'منشأة واحدة','route.p2':'من 2 إلى 9','route.p3':'نحو 10 أو أكثر',
    'route.q3':'نظام PMS المثبّت كما تأكّد','route.pm1':'مفروض (Opera)','route.pm2':'غير مفروض','route.pm3':'غير معروف',
    'gate.title':'جاهزية الإرسال','gate.sub':'لا تُطلق إلا السجلات المؤهلة لهذه القناة (دليل الحملة ص 6).',
    'gate.ver':'حالة التحقق','gate.v1':'هوية موثَّقة ومسار عمل مؤكَّد','gate.v2':'رقم الفندق العام فقط','gate.v3':'غير موثَّق','gate.v4':'غير صالح',
    'gate.perm':'إذن تسويقي مسجَّل، ولينكدإن ضمن القنوات المسموحة','gate.clean':'لم يطلب الإيقاف، وغير مستبعد، ولا حملة أخرى مسندة إليه',
    'rel.title':'سجل الإطلاق التجاري','rel.sub':'للحملتين A وB فقط. قبل اعتماده تقول الرسائل «اسأل عن سعر الإطلاق» ولا يظهر أي سعر (دليل الحملة ص 11).',
    'rel.switch':'تم اعتماد الإطلاق التجاري','rel.annual':'ريال سنوياً','rel.vat':'ضريبة القيمة المضافة','rel.vatPlus':'غير شاملة الضريبة','rel.vatIncl':'شاملة الضريبة',
    'rel.hint':'أدخل الأرقام كما يذكرها السجل المعتمد حرفياً. سعر الإطلاق المعتمد 3,495 ريالاً سنوياً غير شاملة الضريبة، باشتراك لمدة سنة.',
    'seq.title':'التسلسل','seq.sub':'خمس رسائل تتبع قصة الحملة المصوّرة مشهداً بمشهد.',
    'ads.title':'الإعلانات والمنشورات','ads.lede':'القصة نفسها على نطاق أوسع، بالتصاميم المعتمدة، وبروابط تحمل وسوم ورقة روابط الحملة نفسها.',
    'rep.title':'الردود','rep.lede':'عندما يردّ العميل يتوقف التسلسل. ردّ في اليوم نفسه خلال ساعات العمل، ثم اتبع الملاحظة تحت كل رد.',
    'sub.title':'العناوين','sub.lede':'خيارات العناوين في دليل الحملة لهذه الحملة. استخدمها عناوين لرسائل InMail، وفي الاختبار الأول غيّر العنوان فقط.',
    'sub.colLine':'العنوان','sub.colLen':'الطول','sub.colTag':'الحالة','sub.colCopy':'نسخ',
    'play.title':'دليل الحملة','play.lede':'القواعد التي بُني عليها كل سطر أعلاه، من دليل الحملات التسويقية بتاريخ 20 أكتوبر 2026. وعند الشك، يُقدَّم الدليل.',
    'next.title':'الحساب التالي','next.text':'الصق التسلسل كاملاً في نظام إدارة العملاء على هذا الحساب، ثم ابدأ الحساب التالي. يبقى اسمك والتاريخ، ويُمسح ما عداهما، ويجب توجيه الحساب الجديد والتحقق منه من جديد.',
    'next.copyAll':'نسخ سجل نظام العملاء','next.label':'الاسم الأول لجهة الاتصال التالية','next.start':'ابدأ',
    'foot':'فندقة 360 · حزمة لينكدإن للحملات من A إلى D · مبنية على دليل الحملات التسويقية (20 أكتوبر 2026) · للاستخدام الداخلي · تستخدم كل الروابط رابط الحملة الموحّد مع وسوم الحملة.'
  };

  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const root = document.documentElement;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)');
  const esc = s => String(s).replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
  const L2 = (en, ar) => ({ en, ar });

  /* ---------- the four campaigns, from the playbook (pp. 1 to 5) and campaigns/<X>/ ---------- */
  const BASE = 'https://usamakhanksa.github.io/fandaqah-360/';
  const CAMP = {
    A: { campaign: 'A_existing_upgrade_2610', primary: false,
      name: L2('Existing customers', 'العملاء الحاليون'), short: L2('Existing', 'العملاء الحاليون'),
      buyer: L2('Owner, GM or finance manager who already knows Fandaqah.', 'المالك أو المدير العام أو المدير المالي الذي يعرف فندقة مسبقاً.'),
      concern: L2('Wants more value without disruption. Main objection: paying again for tools they may not need.', 'يريد قيمة أكبر دون إرباك. الاعتراض الأبرز: الدفع مجدداً مقابل أدوات قد لا يحتاجها.'),
      action: L2('Request an upgrade review', 'طلب مراجعة الترقية'), cta: L2('Check my upgrade offer', 'تحقّق من عرض الترقية'),
      li: L2('Supporting channel. Account manager messages to verified customers. The main channels are the account manager, permissioned email and WhatsApp; do not spend broadly to reach customers we can identify directly.', 'قناة داعمة: رسائل مدير الحساب للعملاء الموثَّقين. القنوات الرئيسية هي مدير الحساب والبريد وواتساب بإذن مسجَّل، ولا إنفاق واسع للوصول إلى عملاء نعرفهم مباشرة.'),
      session: L2('review', 'المراجعة'), company: L2('Hotel name', 'اسم الفندق'),
      extra: L2('Not already on this offer; no unresolved service complaint (complaints go to account care first)', 'ليس على العرض نفسه، ولا شكوى خدمة مفتوحة (تُحال الشكاوى إلى رعاية العملاء أولاً)'),
      beats: [L2('Already with Fandaqah? Get more value.', 'تستخدم فندقة؟ احصل على قيمة أكبر.'), L2('Connect more of your daily work.', 'اربط مزيداً من عملك اليومي.'), L2('See what your upgrade includes.', 'اكتشف ما تشمله ترقيتك.'), L2('Reserve your launch pricing.', 'احجز سعر الإطلاق.')] },
    B: { campaign: 'B_new_hotels_2610', primary: false,
      name: L2('New independent properties', 'المنشآت المستقلة الجديدة'), short: L2('New hotels', 'الفنادق الجديدة'),
      buyer: L2('Independent property owner or GM; finance and operations influence the choice.', 'مالك المنشأة المستقلة أو مديرها العام، مع تأثير المالية والتشغيل في القرار.'),
      concern: L2('Wants an easier way to run the property: simpler operations, less manual work, a clearer view and fewer vendors, at a predictable cost. Objections: migration effort, training and the cost of switching.', 'يريد طريقة أسهل لإدارة منشأته: تشغيلاً أبسط، وعملاً يدوياً أقل، ورؤية أوضح، وموردين أقل، بتكلفة متوقعة. الاعتراضات: جهد النقل، والتدريب، وتكلفة الانتقال.'),
      action: L2('A walkthrough of how the property could run simpler, from one platform', 'جولة توضح كيف يمكن أن تُدار المنشأة بشكل أبسط من منصة واحدة'), cta: L2('Explore a simpler way to run my hotel', 'اكتشف طريقة أبسط لإدارة فندقي'),
      li: L2('Supporting channel. Direct follow-up to verified, permitted contact records only. The main channels are Google Search and short Arabic social videos.', 'قناة داعمة: متابعة مباشرة لسجلات اتصال موثَّقة ومأذونة فقط. القنوات الرئيسية هي بحث Google ومقاطع اجتماعية عربية قصيرة.'),
      session: L2('walkthrough', 'الجولة'), company: L2('Hotel name', 'اسم الفندق'),
      extra: L2('A verified decision-maker, not only a directory or Ministry record', 'صاحب قرار موثَّق، لا مجرد سجل في دليل عام أو لدى الوزارة'),
      beats: [L2('Still running your hotel on separate systems?', 'ما زلت تدير فندقك بأنظمة متفرقة؟'), L2('Run more of your hotel from one platform, with fewer vendors to manage.', 'أدِر جزءاً أكبر من فندقك من منصة واحدة، مع موردين أقل.'), L2('Enter it once. See it everywhere.', 'أدخِل البيانات مرة واحدة، وشاهدها في كل مكان.'), L2('Explore a simpler way to run your hotel.', 'اكتشف طريقة أبسط لإدارة فندقك.')] },
    C: { campaign: 'C_opera_cost_review_2610', primary: true,
      name: L2('Opera and brand-mandated PMS', 'أنظمة Opera والأنظمة المفروضة من العلامة'), short: L2('Opera PMS', 'أنظمة Opera'),
      buyer: L2('GM or owner, IT manager or director, and finance director.', 'المدير العام أو المالك، ومدير التقنية، والمدير المالي.'),
      concern: L2('Want simpler operations, clearer visibility across departments and controlled technology costs, while keeping the required PMS and brand rules. Compatibility is confirmed before anything is promised.', 'يريدون تشغيلاً أبسط، ورؤية أوضح بين الأقسام، وتكاليف تقنية تحت السيطرة، مع الالتزام بالنظام المفروض وقواعد العلامة. ويُؤكَّد التوافق قبل أي وعد.'),
      action: L2('Request an operational and technology review', 'طلب مراجعة تشغيلية وتقنية'), cta: L2('Request an operational and technology review', 'اطلب مراجعة تشغيلية وتقنية'),
      li: L2('Primary channel. A small named-account campaign with professional targeting on LinkedIn. Share an operations overview with the GM or owner, a technical brief with IT and a cost comparison with finance.', 'قناة رئيسية: حملة صغيرة لحسابات محددة بالاسم مع استهداف مهني على لينكدإن. شارك نظرة تشغيلية مع المدير العام أو المالك، وموجزاً تقنياً مع التقنية، ومقارنة تكاليف مع المالية.'),
      session: L2('review', 'المراجعة'), company: L2('Hotel name', 'اسم الفندق'),
      extra: L2('Mandated PMS verified, not inferred from a brand name or star rating', 'النظام المفروض موثَّق، وليس مستنتجاً من اسم العلامة أو تصنيف النجوم'),
      beats: [L2('Running your hotel around Opera through too many systems?', 'تدير فندقك حول Opera بأنظمة كثيرة؟'), L2('One platform for HR, procurement, inventory, finance and compliance. Opera stays your PMS.', 'منصة واحدة للموارد البشرية والمشتريات والمخزون والمالية والامتثال، ويبقى Opera نظامك.'), L2('Fewer systems. Clearer visibility. Room to grow.', 'أنظمة أقل، ورؤية أوضح، ومساحة للنمو.'), L2('Request an operational and technology review.', 'اطلب مراجعة تشغيلية وتقنية.')] },
    D: { campaign: 'D_group_discovery_2610', primary: true,
      name: L2('Local chains and operators', 'السلاسل والمشغّلون المحليون'), short: L2('Groups', 'المجموعات'),
      buyer: L2('Group owner, CEO or COO, with IT and finance approval, at an operator with about 10 or more properties.', 'مالك المجموعة أو رئيسها التنفيذي أو مدير عملياتها، بموافقة التقنية والمالية، لدى مشغّل بنحو 10 منشآت أو أكثر.'),
      concern: L2('Wants centralised control, portfolio-wide visibility and consistent standards as the group grows, with one accountable technology partner.', 'يريد إدارة مركزية، ورؤية على مستوى المحفظة، ومعايير موحّدة مع نمو المجموعة، وشريكاً تقنياً واحداً مسؤولاً.'),
      action: L2('A group discovery workshop: how the group can centralise operations and improve portfolio-wide visibility', 'ورشة استكشافية للمجموعة: كيف تدير عملياتها مركزياً وتحسّن رؤيتها للمحفظة'), cta: L2('Explore centralised management for my group', 'استكشف الإدارة المركزية لمجموعتي'),
      li: L2('Primary channel. Personalised account outreach, LinkedIn content and a focused group-demo invitation. Coordinate every contact under one account owner.', 'قناة رئيسية: تواصل مخصّص مع الحساب، ومحتوى على لينكدإن، ودعوة مركّزة لعرض المجموعة. نسّق كل جهات الاتصال تحت مسؤول حساب واحد.'),
      session: L2('workshop', 'الورشة'), company: L2('Group name', 'اسم المجموعة'),
      extra: L2('Group-level decision-maker verified; all contacts sit under one account owner', 'صاحب قرار على مستوى المجموعة موثَّق، وكل جهات الاتصال تحت مسؤول حساب واحد'),
      beats: [L2('Many properties. Many systems. No single view.', 'منشآت كثيرة، وأنظمة كثيرة، ولا رؤية واحدة.'), L2('Centralised visibility across every property, from one management environment.', 'رؤية مركزية لكل منشآتك من بيئة إدارة واحدة.'), L2('Standardise operations across your entire portfolio.', 'وحّد العمليات عبر محفظتك الفندقية كاملة.'), L2('Grow the portfolio. Keep control.', 'توسّع في محفظتك، وحافظ على السيطرة.')] }
  };

  const MODULES = [
    ['accounting', 'accounting', 'Accounting', 'المحاسبة'], ['purchasing', 'purchasing', 'Purchasing', 'المشتريات'], ['housekeeping', 'housekeeping', 'Housekeeping', 'التدبير الفندقي'],
    ['inventory', 'inventory', 'Inventory', 'المخزون'], ['hr', 'HR', 'HR', 'الموارد البشرية'], ['pos', 'POS and restaurant management', 'POS', 'نقاط البيع والمطاعم'],
    ['maintenance', 'maintenance', 'Maintenance', 'الصيانة'], ['crm', 'CRM', 'CRM', 'إدارة علاقات العملاء'], ['channel', 'the channel manager', 'Channel manager', 'مدير القنوات'], ['booking', 'the booking engine', 'Booking engine', 'محرك الحجز']
  ];
  const COUNT = { '10': L2('about 10 properties', 'نحو 10 منشآت'), '10-20': L2('10 to 20 properties', 'من 10 إلى 20 منشأة'), '21-50': L2('21 to 50 properties', 'من 21 إلى 50 منشأة'), '50+': L2('more than 50 properties', 'أكثر من 50 منشأة') };
  const LISTS = {
    cities: L2(['Riyadh', 'Jeddah', 'Makkah', 'Madinah', 'Al Khobar', 'Dammam', 'Abha', 'Taif', 'AlUla', 'Tabuk', 'Al Ahsa', 'Jazan'], ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الخبر', 'الدمام', 'أبها', 'الطائف', 'العلا', 'تبوك', 'الأحساء', 'جازان']),
    plans: L2(['Basic Subscription', 'Compliance Package'], ['الاشتراك الأساسي', 'باقة الامتثال']),
    pmsList: L2(['Opera PMS', 'Opera Cloud', 'your brand-mandated PMS'], ['نظام Opera PMS', 'نظام Opera Cloud', 'نظام PMS المفروض من العلامة'])
  };
  const FIELD = {
    first: L2('First name', 'الاسم الأول'), city: L2('City', 'المدينة'), rep: L2('Your name', 'اسمك'), plan: L2('Current plan', 'الباقة الحالية'),
    renewal: L2('Renewal date', 'تاريخ التجديد'), modules: L2('Modules', 'الوحدات'), pms: L2('Installed PMS', 'نظام PMS'), count: L2('Property count', 'عدد المنشآت')
  };
  const fieldLabel = k => k === 'company' ? CAMP[S.track].company[S.lang] : FIELD[k][S.lang];
  const BRAND = L2('Fandaqah', 'فندقة');

  /* ---------- the sequences: each touch tells one beat of the campaign's 25-second storyboard ----------
     Tokens: {first} {company} {city} {rep} {note} {link} {plan} {renewal} {modules} {pms} {count} {session}.
     Blocks: {p}, {ul}, {ol}, {sign}; "if"/"unless": "release". C touches carry IT and FIN versions.
     Arabic uses the respectful plural so it reads correctly for any recipient. */
  const SIG = { sign: 1 };
  const STEPS = {
    A: [
      { id: 'note', offset: null, limit: 200, link: false,
        why: L2('A customer already knows you. Thank them and connect; the offer can wait for the message.', 'العميل يعرفكم مسبقاً، فابدأ بالشكر والتواصل، والعرض يأتي في الرسالة.'),
        en: [{ p: 'Hello {first}, I’m {rep}, your account contact at Fandaqah. Thank you for running {company} with us. I’d be glad to connect here as well.' }],
        ar: [{ p: 'مرحباً {first}، معكم {rep} من فريق حسابكم في فندقة. شكراً لثقتكم بنا في {company}، ويسعدني أن نتواصل هنا أيضاً.' }] },
      { id: 'msg1', offset: 0, limit: 1900, link: false, beat: 0,
        subject: L2('More value from the Fandaqah you already use', 'قيمة أكبر من فندقة التي تستخدمها اليوم'),
        why: L2('Renewal is the playbook’s trigger. The message uses their plan and renewal date, offers a review rather than a sale, and carries no link yet.', 'التجديد هو المحفّز في دليل الحملة. تستخدم الرسالة باقتهم وتاريخ تجديدهم، وتعرض مراجعة لا بيعاً، ولا رابط بعد.'),
        en: [{ p: 'Hello {first},' }, { p: 'Thank you for running {company} on Fandaqah. With your {plan} renewing on {renewal}, it’s a good moment to make sure you’re getting full value from it. {note}' },
          { p: 'I’d like to offer a short upgrade review: your current subscription, the modules your team could use next and your eligibility for our launch pricing.' },
          { p: 'Would a 20-minute call in the next few days suit you?' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'شكراً لثقتكم بفندقة في إدارة {company}. ومع اقتراب تجديد {plan} في {renewal}، فهذا وقت مناسب للتأكد من أنكم تحصلون على كامل قيمته. {note}' },
          { p: 'أودّ أن أعرض عليكم مراجعة ترقية قصيرة: اشتراككم الحالي، والوحدات التي قد يستفيد منها فريقكم تالياً، وأهليتكم لسعر الإطلاق.' },
          { p: 'هل يناسبكم اتصال مدته 20 دقيقة خلال الأيام القادمة؟' }, SIG] },
      { id: 'msg2', offset: 3, limit: 1900, link: true, beat: 1,
        subject: L2('A loyalty offer built around your hotel', 'عرض ولاء مصمَّم حول فندقك'),
        why: L2('The storyboard shows two modules the customer does not use. So does this message, then it adds the first link.', 'تعرض القصة المصوّرة وحدتين لا يستخدمهما العميل، وكذلك تفعل هذه الرسالة، ثم تضيف أول رابط.'),
        en: [{ p: 'Hello {first},' }, { p: 'One thing I noticed while preparing your review: {company}’s current setup doesn’t include {modules}. Fandaqah 360 brings them into the same system as the reservations your team already manages.' },
          { p: 'The review shows exactly what they would add and what the launch pricing covers:\n{link}' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'لاحظت أثناء تجهيز مراجعتكم أن إعداد {company} الحالي لا يشمل {modules}. وفندقة 360 تضعها في النظام نفسه مع الحجوزات التي يديرها فريقكم اليوم.' },
          { p: 'ستوضح المراجعة ما تضيفه هذه الوحدات بالتحديد، وما يشمله سعر الإطلاق:\n{link}' }, SIG] },
      { id: 'msg3', offset: 5, limit: 1900, link: true, beat: 2,
        subject: L2('See what your upgrade includes', 'اكتشف ما تشمله ترقيتك'),
        why: L2('One useful task in the live product, as the storyboard asks, and the handoff checks the playbook requires: term, modules, approved credit.', 'مهمة مفيدة واحدة في النظام الفعلي كما تطلب القصة المصوّرة، مع ما يشترطه الدليل قبل التسليم: المدة والوحدات وأي رصيد معتمد.'),
        en: [{ p: 'Hello {first},' }, { p: 'Rather than another list of features, I’d like to show you one task in the live product using {company}’s own workflow, for example {modules} alongside your front desk.' },
          { p: 'It takes 20 minutes, and we confirm your current term, the modules included and any approved credit before anything is proposed.\n{link}' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'بدلاً من قائمة أخرى بالمزايا، أودّ أن أريكم مهمة واحدة في النظام الفعلي وفق سير عمل {company} نفسه، مثل {modules} إلى جانب مكتب الاستقبال.' },
          { p: 'تستغرق 20 دقيقة، ونؤكّد خلالها مدة اشتراككم الحالية والوحدات المشمولة وأي رصيد ترقية معتمد قبل تقديم أي عرض.\n{link}' }, SIG] },
      { id: 'msg4', offset: 7, limit: 1900, link: true, beat: 3,
        subject: L2('Your upgrade offer', 'عرض الترقية الخاص بكم'), subjectRel: L2('Reserve your launch pricing', 'احجز سعر الإطلاق'),
        why: L2('The offer beat. Until the commercial release record is signed it speaks of eligibility; once signed, the launch-price line switches on, with the option to secure one more year at the same rate. Never a two-year commitment.', 'مشهد العرض. قبل اعتماد سجل الإطلاق التجاري يتحدث عن الأهلية، وبعد اعتماده يظهر سطر سعر الإطلاق مع خيار حجز سنة إضافية بالسعر نفسه، دون أي التزام لسنتين.'),
        en: [{ p: 'Hello {first},' }, { p: 'I’ll keep this short and leave it with you.' },
          { p: 'Your upgrade offer for {company} is ready to check whenever it suits you, including the modules you would add and your eligibility for our launch pricing:\n{link}', unless: 'release' },
          { p: 'Your upgrade offer for {company} is ready to check whenever it suits you, including the modules you would add, the launch price, and the option to secure an additional year at the same rate before the offer ends:\n{link}', if: 'release' },
          { p: 'If the timing isn’t right before your {renewal} renewal, just say and I’ll come back closer to the date. Either way, your account team is here for anything you need.' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'سأختصر وأترك الأمر لكم.' },
          { p: 'عرض الترقية الخاص بـ {company} جاهز للاطلاع متى ناسبكم، ويشمل الوحدات التي ستضيفونها وأهليتكم لسعر الإطلاق:\n{link}', unless: 'release' },
          { p: 'عرض الترقية الخاص بـ {company} جاهز للاطلاع متى ناسبكم، ويشمل الوحدات التي ستضيفونها، وسعر الإطلاق، وإمكانية حجز سنة إضافية بالسعر نفسه قبل انتهاء العرض:\n{link}', if: 'release' },
          { p: 'وإن لم يكن الوقت مناسباً قبل موعد التجديد في {renewal}، فأخبروني وسأعود إليكم قرب الموعد. وفي كل الأحوال، فريق حسابكم في خدمتكم.' }, SIG] }
    ],
    B: [
      { id: 'note', offset: null, limit: 200, link: false,
        why: L2('No pitch in the invitation, only the outcome owners care about: simpler days. The note earns the accept; the messages do the work.', 'لا عرض بيعي في الدعوة، بل النتيجة التي تهم الملاك: أيام عمل أبسط. الملاحظة تكسب القبول، والرسائل تتولّى الباقي.'),
        en: [{ p: 'Hello {first}, I work with independent hotels across Saudi Arabia on simpler day-to-day operations. I’d be glad to connect and follow how {company} grows in {city}.' }],
        ar: [{ p: 'مرحباً {first}، أعمل مع الفنادق المستقلة في المملكة على تشغيل يومي أبسط، ويسعدني التواصل معكم ومتابعة نمو {company} في {city}.' }] },
      { id: 'msg1', offset: 0, limit: 1900, link: false, beat: 0,
        subject: L2('How many systems does it take to run {company}?', 'كم نظاماً يحتاجه تشغيل {company}؟'),
        why: L2('Opens on the problem, not the product: separate systems, the same data typed twice, no single vendor to call. It ends on a question, because a question invites a reply and a link in a first message reads like a campaign.', 'تبدأ بالمشكلة لا بالمنتج: أنظمة متفرقة، وبيانات تُدخل مرتين، ولا مورّد واحد يُتصل به. وتنتهي بسؤال، لأن السؤال يدعو إلى الرد، والرابط في الرسالة الأولى يبدو كحملة إعلانية.'),
        en: [{ p: 'Hello {first},' }, { p: 'Thanks for connecting. {note}' },
          { p: 'Many independent hotels run their day across several systems: one for bookings, another for the accounts, a third at the restaurant till, and spreadsheets in between. The same guest gets typed in twice, month-end takes longer than it should, and when something goes wrong it isn’t always clear which vendor to call.' },
          { p: 'Is that close to how things work at {company}, or have you already simplified it?' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'شكراً لقبول الدعوة. {note}' },
          { p: 'كثير من الفنادق المستقلة تُدار يومياً عبر عدة أنظمة: نظام للحجوزات، وآخر للحسابات، وثالث لنقاط البيع في المطعم، وجداول بيانات بينها. فتُدخل بيانات النزيل نفسه مرتين، ويستغرق إقفال الشهر وقتاً أطول مما ينبغي، وعند حدوث مشكلة لا يتضح دائماً بأي مورّد يجب الاتصال.' },
          { p: 'هل يشبه هذا طريقة العمل في {company}، أم أنكم بسّطتموها بالفعل؟' }, SIG] },
      { id: 'msg2', offset: 3, limit: 1900, link: true, beat: 1,
        subject: L2('A simpler way to run {company}', 'طريقة أبسط لتشغيل {company}'),
        why: L2('The outcome before the product: three benefits the owner can picture (less manual work, a clearer view, fewer vendors), with Fandaqah 360 named only as the way to get them. No module list.', 'النتيجة قبل المنتج: ثلاث فوائد يتخيّلها المالك (عمل يدوي أقل، ورؤية أوضح، وموردون أقل)، وفندقة 360 تُذكر فقط كطريق إليها، دون قائمة وحدات.'),
        en: [{ p: 'Hello {first},' }, { p: 'The owners we work with usually want the same thing: to manage more of the property from one place, and to rely less on separate systems that don’t talk to each other. That is what Fandaqah 360 is for. Day to day, it means:' },
          { ul: ['Less manual work: a booking entered once flows through to housekeeping and the accounts', 'A clearer view of the hotel, from one set of reports', 'Fewer vendors and logins to manage, and one team to call for support'] },
          { p: 'It is built for Saudi hospitality, with supported regulatory workflows. A short overview for {company}:\n{link}' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'الملاك الذين نعمل معهم يريدون غالباً الشيء نفسه: أن يديروا جزءاً أكبر من المنشأة من مكان واحد، وأن يعتمدوا أقل على أنظمة منفصلة لا تتواصل فيما بينها. ولهذا وُجدت فندقة 360، وهذا ما تعنيه يومياً:' },
          { ul: ['عمل يدوي أقل: الحجز يُدخل مرة واحدة ويصل إلى التدبير الفندقي والحسابات', 'رؤية أوضح للفندق من مجموعة تقارير واحدة', 'موردون وحسابات دخول أقل، وفريق واحد تتواصلون معه للدعم'] },
          { p: 'وهي مصمَّمة لقطاع الضيافة السعودي مع دعم سير العمل التنظيمي. نظرة سريعة تناسب {company}:\n{link}' }, SIG] },
      { id: 'msg3', offset: 5, limit: 1900, link: true, beat: 2,
        subject: L2('Why change, and what it takes', 'لماذا التغيير، وما الذي يتطلبه'),
        why: L2('Answers the question every owner asks, “why change from what we have?”, with the hidden cost of the current setup, then removes the three objections owners raise: migration, training and commitment.', 'تجيب عن سؤال كل مالك: «لماذا نغيّر ما لدينا؟» بالتكلفة الخفية للوضع الحالي، ثم تزيل الاعتراضات الثلاثة التي يطرحها الملاك: النقل والتدريب والالتزام.'),
        en: [{ p: 'Hello {first},' }, { p: 'The honest answer to “why change?” is the time a team spends keeping separate systems in step. Most owners feel it at month-end, or when a guest’s bill doesn’t match what the front desk sees. On one platform, there is much less of it to do.' },
          { p: 'And the three questions owners usually ask about switching:' },
          { ol: ['Moving our data: we assess your current systems first and plan the move with your team.', 'Training: each department gets a training plan agreed before go-live.', 'Commitment: the launch price is for a one-year subscription, and you see the scope and terms in full before deciding.'] },
          { p: 'If it helps, we can look at how {company} runs today and show you what would be simpler:\n{link}' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'الإجابة الصادقة عن «لماذا التغيير؟» هي الوقت الذي يقضيه الفريق في إبقاء الأنظمة المنفصلة متطابقة. يشعر به أغلب الملاك عند إقفال الشهر، أو حين لا تطابق فاتورة النزيل ما يظهر في الاستقبال. وعلى منصة واحدة يقلّ هذا العمل كثيراً.' },
          { p: 'وهذه الأسئلة الثلاثة التي يطرحها الملاك عادةً عن الانتقال:' },
          { ol: ['نقل البيانات: نقيّم أنظمتكم الحالية أولاً ونخطّط للنقل مع فريقكم.', 'التدريب: يحصل كل قسم على خطة تدريب متفق عليها قبل التشغيل.', 'الالتزام: سعر الإطلاق لاشتراك مدته سنة، وتطّلعون على النطاق والشروط كاملة قبل القرار.'] },
          { p: 'وإن كان ذلك مفيداً، ننظر معكم في طريقة تشغيل {company} اليوم ونريكم ما يمكن أن يصبح أبسط:\n{link}' }, SIG] },
      { id: 'msg4', offset: 7, limit: 1900, link: true, beat: 3,
        subject: L2('An easier way to run {company}', 'طريقة أسهل لإدارة {company}'), subjectRel: L2('Simpler operations, at the launch price', 'تشغيل أبسط بسعر الإطلاق'),
        why: L2('The close leaves one impression: an easier way to run the property. The invitation is to explore that, not to “book a demo”. Then a referral ask, and the sequence stops.', 'الختام يترك انطباعاً واحداً: طريقة أسهل لإدارة المنشأة. والدعوة لاستكشاف ذلك، لا «لحجز عرض توضيحي». ثم طلب ترشيح، ويتوقف التسلسل.'),
        en: [{ p: 'Hello {first},' }, { p: 'I’ll leave it here so I don’t crowd your inbox.' },
          { p: 'If you’d like to explore whether {company} could run on fewer systems, with less manual work and a clearer view of the hotel, you can start here. We’ll also take you through our launch pricing:\n{link}', unless: 'release' },
          { p: 'If you’d like to explore whether {company} could run on fewer systems, with less manual work and a clearer view of the hotel, you can start here. The launch price applies to a one-year subscription while the offer lasts:\n{link}', if: 'release' },
          { p: 'And if someone else looks after operations or systems at {company}, I’d appreciate a name. Wishing you a strong season in {city}.' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'سأكتفي بهذا حتى لا أُثقل عليكم بالرسائل.' },
          { p: 'إن رغبتم في استكشاف ما إذا كان {company} يمكن أن يعمل بأنظمة أقل، وبعمل يدوي أقل، ورؤية أوضح للفندق، فيمكنكم البدء من هنا، وسنطلعكم أيضاً على سعر الإطلاق:\n{link}', unless: 'release' },
          { p: 'إن رغبتم في استكشاف ما إذا كان {company} يمكن أن يعمل بأنظمة أقل، وبعمل يدوي أقل، ورؤية أوضح للفندق، فيمكنكم البدء من هنا. ويسري سعر الإطلاق على اشتراك لمدة سنة طوال فترة العرض:\n{link}', if: 'release' },
          { p: 'وإن كان شخص آخر يتولّى التشغيل أو الأنظمة في {company}، فسأقدّر لكم الدلالة عليه. أتمنى لكم موسماً موفقاً في {city}.' }, SIG] }
    ],
    C: [
      { id: 'note', offset: null, limit: 200, link: false,
        why: L2('Three notes, one per role, each naming an outcome the role owns: simpler operations for the GM or owner, fewer systems for IT, a clear cost picture for finance. Opera is mentioned only as the PMS that stays.', 'ثلاث ملاحظات، واحدة لكل دور، تذكر كلٌّ منها نتيجة يملكها ذلك الدور: تشغيل أبسط للمدير العام أو المالك، وأنظمة أقل للتقنية، وصورة تكلفة واضحة للمالية. ولا يُذكر Opera إلا بوصفه النظام الذي يبقى.'),
        GM: { en: [{ p: 'Hello {first}, I work with Saudi hotels that keep {pms} and want simpler operations around it: fewer systems and a clearer view of the hotel. I’d be glad to connect.' }], ar: [{ p: 'مرحباً {first}، أعمل مع فنادق سعودية تعمل على {pms} وتريد تشغيلاً أبسط حوله: أنظمة أقل ورؤية أوضح للفندق. يسعدني التواصل معكم.' }] },
        IT: { en: [{ p: 'Hello {first}, I work with IT teams at Saudi hotels that run {pms}, on reducing the number of systems they support around it. I’d be glad to connect.' }], ar: [{ p: 'مرحباً {first}، أعمل مع فرق التقنية في الفنادق السعودية التي تعمل على {pms}، على تقليل عدد الأنظمة التي تدعمها حوله. يسعدني التواصل معكم.' }] },
        FIN: { en: [{ p: 'Hello {first}, I work with finance teams at Saudi hotels on what disconnected systems around {pms} really cost. I’d be glad to connect.' }], ar: [{ p: 'مرحباً {first}، أعمل مع الفرق المالية في الفنادق السعودية على التكلفة الحقيقية للأنظمة غير المترابطة حول {pms}. يسعدني التواصل معكم.' }] } },
      { id: 'msg1', offset: 0, limit: 1900, link: false, beat: 0,
        subject: { GM: L2('Simpler operations around your {pms}', 'تشغيل أبسط حول {pms}'), IT: L2('Fewer systems to support around {pms}', 'أنظمة أقل تدعمونها حول {pms}'), FIN: L2('What disconnected systems cost your hotel', 'ما تكلّفه الأنظمة غير المترابطة فندقكم') },
        why: L2('Problem first, value second, integration last. The message names the operational challenge (several systems, work done twice, no single view), states what changes, and only then confirms that Opera stays. No percentages, no link.', 'المشكلة أولاً، ثم القيمة، ثم التكامل أخيراً: تسمّي الرسالة التحدي التشغيلي (أنظمة متعددة، وعمل يُنجز مرتين، وغياب الصورة الواحدة)، وتذكر ما الذي يتغير، ثم تؤكد أن Opera يبقى. بلا نسب ولا رابط.'),
        GM: { en: [{ p: 'Hello {first},' }, { p: 'Thanks for connecting. {note}' },
            { p: 'Many hotels running {pms} manage HR, procurement, inventory, finance, compliance and reporting through several disconnected systems. The result is usually the same: work entered twice, costs spread across contracts, and no single view of how the hotel is really running.' },
            { p: 'Fandaqah brings those operations into one platform, while {pms} remains your core PMS. Nothing changes for your PMS or your brand standards.' },
            { p: 'Is simplifying the systems around {pms} at {company} on your agenda this year?' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'شكراً لقبول الدعوة. {note}' },
            { p: 'كثير من الفنادق التي تعمل على {pms} تدير الموارد البشرية والمشتريات والمخزون والمالية والامتثال والتقارير عبر أنظمة متعددة غير مترابطة. والنتيجة غالباً واحدة: بيانات تُدخل مرتين، وتكاليف موزعة على عقود كثيرة، وغياب صورة واحدة لأداء الفندق الفعلي.' },
            { p: 'فندقة تجمع هذه العمليات في منصة واحدة، ويبقى {pms} نظامكم الأساسي لإدارة الفندق، دون أي تغيير عليه أو على معايير علامتكم.' },
            { p: 'هل تبسيط الأنظمة العاملة حول {pms} في {company} ضمن أولوياتكم هذا العام؟' }, SIG] },
        IT: { en: [{ p: 'Hello {first},' }, { p: 'Thanks for connecting. {note}' },
            { p: 'Around {pms}, most hotels we speak with run separate tools for HR, procurement, inventory, finance and compliance. Each one is another interface to maintain, another vendor to manage and another place where data drifts out of step.' },
            { p: 'Fandaqah consolidates those operational systems into one platform and works alongside {pms} through approved interfaces, so the PMS stays the system of record for its scope.' },
            { p: 'Would fewer systems to support around {pms} be useful for {company}?' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'شكراً لقبول الدعوة. {note}' },
            { p: 'حول {pms} تعمل أغلب الفنادق التي نتحدث معها بأدوات منفصلة للموارد البشرية والمشتريات والمخزون والمالية والامتثال. وكل أداة تعني واجهة أخرى تحتاج صيانة، ومورّداً آخر تحتاج متابعته، وموضعاً آخر تختلف فيه البيانات.' },
            { p: 'فندقة توحّد هذه الأنظمة التشغيلية في منصة واحدة، وتعمل إلى جانب {pms} عبر واجهات معتمدة، ليبقى نظام السجل المعتمد في نطاقه.' },
            { p: 'هل يفيد {company} أن تقلّ الأنظمة التي تدعمونها حول {pms}؟' }, SIG] },
        FIN: { en: [{ p: 'Hello {first},' }, { p: 'Thanks for connecting. {note}' },
            { p: 'For hotels running {pms}, the real cost of technology rarely sits in one contract. HR, procurement, inventory, finance and compliance often run in separate systems, each with its own subscription, support and duplicated work, so the full picture is hard to see.' },
            { p: 'Fandaqah consolidates those operations into one platform while {pms} remains your core PMS, so activity and cost can be seen in one place.' },
            { p: 'Would a clear view of what {company} spends on the systems around {pms} be useful?' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'شكراً لقبول الدعوة. {note}' },
            { p: 'في الفنادق التي تعمل على {pms} نادراً ما تكون التكلفة الحقيقية للتقنية في عقد واحد؛ فالموارد البشرية والمشتريات والمخزون والمالية والامتثال تعمل غالباً بأنظمة منفصلة، لكلٍّ منها اشتراكه ودعمه وعمله المكرر، فيصعب رؤية الصورة كاملة.' },
            { p: 'فندقة توحّد هذه العمليات في منصة واحدة ويبقى {pms} نظامكم الأساسي، لتظهر الأنشطة والتكاليف في مكان واحد.' },
            { p: 'هل تفيدكم صورة واضحة لما ينفقه {company} على الأنظمة العاملة حول {pms}؟' }, SIG] } },
      { id: 'msg2', offset: 3, limit: 1900, link: true, beat: 1,
        subject: { GM: L2('What Fandaqah adds alongside {pms}', 'ما تضيفه فندقة إلى جانب {pms}'), IT: L2('One platform around {pms}: what stays, what we add', 'منصة واحدة حول {pms}: ما يبقى وما نضيفه'), FIN: L2('Your technology spend, line by line', 'إنفاقكم على التقنية، بنداً بنداً') },
        why: L2('The first objective, stated plainly: find what can be simplified, consolidated or improved without disrupting the PMS. Then the concrete list of what Fandaqah adds, so the prospect can picture it. The first tracked link appears here.', 'الهدف الأول بوضوح: معرفة ما يمكن تبسيطه أو توحيده أو تحسينه دون المساس بنظام PMS، ثم القائمة الملموسة لما تضيفه فندقة ليتخيّله العميل. وهنا يظهر أول رابط متتبَّع.'),
        GM: { en: [{ p: 'Hello {first},' }, { p: 'When we review hotels running {pms}, we usually find the same picture: separate systems for HR, procurement, inventory, finance and reporting, with the team bridging the gaps by hand.' },
            { p: 'Our first objective is simple: find where those processes can be simplified, consolidated or improved, without disrupting your PMS.' },
            { p: 'What Fandaqah adds, alongside {pms}:' },
            { ul: ['HR and staff records', 'CRM and guest relationships', 'Procurement and inventory', 'Finance and management reporting', 'Compliance workflows for ZATCA, Shomoos and NTMP'] },
            { p: 'You can see how it works here:\n{link}' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'عندما نراجع الفنادق التي تعمل على {pms} نجد في الغالب الصورة نفسها: أنظمة منفصلة للموارد البشرية والمشتريات والمخزون والمالية والتقارير، وفريق يسدّ الفجوات بينها يدوياً.' },
            { p: 'هدفنا الأول بسيط: معرفة ما يمكن تبسيطه أو توحيده أو تحسينه من هذه العمليات، دون أي مساس بنظامكم.' },
            { p: 'ما تضيفه فندقة إلى جانب {pms}:' },
            { ul: ['الموارد البشرية وسجلات الموظفين', 'إدارة علاقات العملاء والنزلاء', 'المشتريات والمخزون', 'المالية والتقارير الإدارية', 'مسارات الامتثال لهيئة الزكاة والضريبة والجمارك وشموس ومنصة الرصد السياحي'] },
            { p: 'يمكنكم الاطلاع على طريقة العمل من هنا:\n{link}' }, SIG] },
        IT: { en: [{ p: 'Hello {first},' }, { p: 'When we review {pms} environments, we often find several systems for HR, procurement, inventory, finance and reporting, each connected to the PMS, or not, in its own way.' },
            { p: 'Our first objective is simple: identify where they can be consolidated into one platform, without disrupting the PMS environment or its brand requirements.' },
            { ul: ['What stays: {pms} remains the system of record for its scope', 'What Fandaqah adds: HR, CRM, procurement, inventory, finance, compliance workflows and management reporting in one platform', 'How it connects: through supported, approved interfaces, confirmed for your installed version before anything is proposed'] },
            { p: 'The approach is here, and I can send the one-page technical brief:\n{link}' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'عندما نراجع بيئات {pms} نجد غالباً عدة أنظمة للموارد البشرية والمشتريات والمخزون والمالية والتقارير، يرتبط كلٌّ منها بالنظام بطريقته، أو لا يرتبط.' },
            { p: 'هدفنا الأول بسيط: تحديد ما يمكن توحيده منها في منصة واحدة، دون المساس ببيئة النظام أو متطلبات العلامة.' },
            { ul: ['ما يبقى: يظل {pms} نظام السجل المعتمد في نطاقه', 'ما تضيفه فندقة: الموارد البشرية وإدارة علاقات العملاء والمشتريات والمخزون والمالية ومسارات الامتثال والتقارير الإدارية في منصة واحدة', 'كيف يتم الربط: عبر واجهات مدعومة ومعتمدة، نؤكدها لإصداركم المثبّت قبل تقديم أي عرض'] },
            { p: 'طريقة العمل هنا، ويمكنني إرسال الموجز التقني في صفحة واحدة:\n{link}' }, SIG] },
        FIN: { en: [{ p: 'Hello {first},' }, { p: 'When we review hotels running {pms}, the cost picture usually spans many lines: separate subscriptions for HR, procurement, inventory, finance and compliance, plus interfaces, support and the time spent reconciling between them.' },
            { p: 'Our first objective is simple: show where those systems can be consolidated, and what that would mean for your total technology spend, without touching {pms}.' },
            { p: 'The comparison covers, line by line:' },
            { ul: ['Recurring subscriptions', 'Interfaces and connection fees', 'Setup and implementation', 'Support and maintenance', 'Usage charges such as SMS, payments and verification'] },
            { p: 'A saving only counts when both sides cover the same scope and period. More here:\n{link}' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'عندما نراجع الفنادق التي تعمل على {pms} تمتد صورة التكلفة عادةً على بنود كثيرة: اشتراكات منفصلة للموارد البشرية والمشتريات والمخزون والمالية والامتثال، إضافة إلى الواجهات والدعم والوقت المبذول في المطابقة بينها.' },
            { p: 'هدفنا الأول بسيط: أن نوضح ما يمكن توحيده من هذه الأنظمة، وما يعنيه ذلك لإجمالي إنفاقكم على التقنية، مع بقاء {pms} كما هو.' },
            { p: 'تغطي المقارنة، بنداً بنداً:' },
            { ul: ['الاشتراكات المتكررة', 'رسوم الواجهات والربط', 'الإعداد والتنفيذ', 'الدعم والصيانة', 'رسوم الاستخدام مثل الرسائل النصية والمدفوعات والتحقق'] },
            { p: 'ولا يُحتسب التوفير إلا عندما يغطي الطرفان النطاق والمدة نفسيهما. التفاصيل هنا:\n{link}' }, SIG] } },
      { id: 'msg3', offset: 5, limit: 1900, link: true, beat: 2,
        subject: { GM: L2('Before we recommend anything', 'قبل أن نوصي بأي شيء'), IT: L2('Before anyone promises compatibility', 'قبل أي وعد بالتوافق'), FIN: L2('A business case built on your numbers', 'حالة عمل مبنية على أرقامكم') },
        why: L2('The meeting request leads with what the prospect gets (less fragmentation, clearer visibility, room to grow) and keeps the playbook’s discipline: nothing is recommended or promised before the environment is assessed and compatibility confirmed.', 'طلب الاجتماع يبدأ بما يحصل عليه العميل (تشتّت أقل، ورؤية أوضح، ومساحة للنمو) مع الالتزام بانضباط الدليل: لا توصية ولا وعد قبل تقييم البيئة وتأكيد التوافق.'),
        GM: { en: [{ p: 'Hello {first},' }, { p: 'Before recommending anything, we look at how {company} runs today: which systems each department uses, where work is repeated and where visibility breaks down.' },
            { p: 'The result is a short map of opportunities to reduce system fragmentation, improve visibility across departments and support future growth, while respecting your existing {pms} and brand requirements.' },
            { p: 'It takes about 45 minutes with you, ideally with your IT and finance leads. Would next week suit you?\n{link}' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'قبل أن نوصي بأي شيء ننظر في طريقة عمل {company} اليوم: الأنظمة التي يستخدمها كل قسم، وأين يتكرر العمل، وأين تغيب الرؤية.' },
            { p: 'والنتيجة خريطة مختصرة لفرص الحدّ من تشتّت الأنظمة، وتحسين الرؤية بين الأقسام، ودعم نموكم المستقبلي، مع احترام {pms} الحالي ومتطلبات علامتكم.' },
            { p: 'يستغرق ذلك نحو 45 دقيقة معكم، ويُفضَّل حضور مسؤولَي التقنية والمالية. هل يناسبكم الأسبوع القادم؟\n{link}' }, SIG] },
        IT: { en: [{ p: 'Hello {first},' }, { p: 'Before recommending anything, we assess the environment around {pms}: the systems in use, how they connect today, and which interfaces your installed version and brand allow.' },
            { p: 'From there we show where consolidation reduces the number of systems your team supports, with every interface confirmed before it appears in a proposal.' },
            { p: 'A 45-minute technical meeting is usually enough to start. Would next week work?\n{link}' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'قبل أن نوصي بأي شيء نقيّم البيئة العاملة حول {pms}: الأنظمة المستخدمة، وكيف ترتبط اليوم، والواجهات التي يسمح بها إصداركم المثبّت ومتطلبات العلامة.' },
            { p: 'ومن هناك نوضح أين يقلّل التوحيد عدد الأنظمة التي يدعمها فريقكم، مع تأكيد كل واجهة قبل أن تظهر في أي عرض.' },
            { p: 'يكفي عادةً اجتماع تقني مدته 45 دقيقة للبدء. هل يناسبكم الأسبوع القادم؟\n{link}' }, SIG] },
        FIN: { en: [{ p: 'Hello {first},' }, { p: 'Before recommending anything, we assess how {company}’s operations and costs fit together today: which systems are in use, what each one costs and where work and spend overlap.' },
            { p: 'You receive a like-for-like view of your current and proposed scope, with setup, service and usage charges shown separately, so any business case stands on your numbers rather than ours.' },
            { p: 'Shall I set up 45 minutes with you and your IT lead next week?\n{link}' }, SIG],
          ar: [{ p: 'مرحباً {first}،' }, { p: 'قبل أن نوصي بأي شيء نقيّم كيف تتكامل عمليات {company} وتكاليفه اليوم: الأنظمة المستخدمة، وتكلفة كلٍّ منها، وأين يتداخل العمل والإنفاق.' },
            { p: 'وتحصلون على صورة مقارنة دقيقة بين نطاقكم الحالي والمقترح، تظهر فيها رسوم الإعداد والخدمة والاستخدام منفصلة، لتقوم أي حالة عمل على أرقامكم لا على أرقامنا.' },
            { p: 'هل أرتّب 45 دقيقة معكم ومع مسؤول التقنية لديكم الأسبوع القادم؟\n{link}' }, SIG] } },
      { id: 'msg4', offset: 7, limit: 1900, link: true, beat: 3,
        subject: L2('Request an operational and technology review', 'اطلب مراجعة تشغيلية وتقنية'),
        why: L2('The close sells the outcome in one line: see whether the systems can be simplified, consolidated or improved without replacing Opera. The CTA widens from cost alone to operations and technology, so the GM and owner have a reason to say yes, not only finance.', 'الختام يبيع النتيجة في سطر واحد: معرفة ما إذا كان يمكن تبسيط الأنظمة أو توحيدها أو تحسينها دون استبدال Opera. ويتسع الإجراء من التكلفة وحدها إلى التشغيل والتقنية، ليجد المدير العام والمالك سبباً للموافقة، لا المالية وحدها.'),
        en: [{ p: 'Hello {first},' }, { p: 'I’ll leave it here for now.' },
          { p: 'If it would help to see whether {company}’s systems around {pms} can be simplified, consolidated or improved, without replacing your PMS, you can request an operational and technology review here:\n{link}' },
          { p: 'It works best with operations, IT and finance in one conversation, so please feel free to forward this to whoever should join.' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'سأكتفي بهذا الآن.' },
          { p: 'إن كان من المفيد أن تعرفوا ما إذا كانت الأنظمة العاملة حول {pms} في {company} قابلة للتبسيط أو التوحيد أو التحسين، دون استبدال نظامكم، فيمكنكم طلب مراجعة تشغيلية وتقنية من هنا:\n{link}' },
          { p: 'وتنجح المراجعة أكثر بحضور التشغيل والتقنية والمالية في حديث واحد، فلا تترددوا في تحويل هذه الرسالة لمن يلزم.' }, SIG] }
    ],
    D: [
      { id: 'note', offset: null, limit: 200, link: false,
        why: L2('Group-level from the first line: centralised control and visibility across properties, never one hotel’s daily operations.', 'على مستوى المجموعة من السطر الأول: إدارة مركزية ورؤية عبر المنشآت، لا العمليات اليومية لفندق واحد.'),
        en: [{ p: 'Hello {first}, I work with Saudi hotel groups on centralised control and visibility across their properties. I’d be glad to connect with you and the {company} team.' }],
        ar: [{ p: 'مرحباً {first}، أعمل مع المجموعات الفندقية في المملكة على الإدارة المركزية والرؤية الموحّدة عبر منشآتها. يسعدني التواصل معكم ومع فريق {company}.' }] },
      { id: 'msg1', offset: 0, limit: 1900, link: false, beat: 0,
        subject: L2('How does {company} see the whole portfolio today?', 'كيف ترى {company} محفظتها كاملة اليوم؟'),
        why: L2('Opens on the portfolio problem, sized to the group: properties that grew their own systems, a group view built by hand, standards that drift. One executive question, no link.', 'تبدأ بمشكلة المحفظة بحجم المجموعة: منشآت طوّرت أنظمتها الخاصة، ورؤية للمجموعة تُجمع يدوياً، ومعايير تتباين. سؤال تنفيذي واحد، دون رابط.'),
        en: [{ p: 'Hello {first},' }, { p: 'Thanks for connecting. {note}' },
          { p: '{company} runs {count}. In many groups, each property has grown its own systems, processes and reports. The group view arrives as a spreadsheet someone compiles every month, and standards drift from one property to the next.' },
          { p: 'How does your leadership team get a single, reliable view of the portfolio today?' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'شكراً لقبول الدعوة. {note}' },
          { p: 'تدير {company} {count}، وفي كثير من المجموعات طوّرت كل منشأة أنظمتها وإجراءاتها وتقاريرها الخاصة، فتصل صورة المجموعة في جدول بيانات يجمعه أحدهم كل شهر، وتتباين المعايير من منشأة إلى أخرى.' },
          { p: 'كيف يحصل فريق القيادة لديكم اليوم على رؤية واحدة موثوقة للمحفظة كاملة؟' }, SIG] },
      { id: 'msg2', offset: 3, limit: 1900, link: true, beat: 1,
        subject: L2('One management environment for every property', 'بيئة إدارة واحدة لكل منشآتك'),
        why: L2('Executive outcomes, not modules: portfolio visibility, standard operations, governance, executive reporting and room to grow. The dedicated environment and branding come after, as the way it is delivered.', 'نتائج تنفيذية لا وحدات: رؤية للمحفظة، وعمليات موحّدة، وحوكمة، وتقارير للإدارة العليا، ومساحة للنمو. وتأتي البيئة المخصّصة والهوية بعد ذلك كطريقة للتنفيذ.'),
        en: [{ p: 'Hello {first},' }, { p: 'What we help groups build is one management environment for the whole portfolio, so leadership can:' },
          { ul: ['See every property, and the group as a whole, from one place', 'Standardise operations, permissions and approvals across properties', 'Report to the board from one set of figures, not monthly spreadsheets', 'Add new properties without adding new systems'] },
          { p: 'It is set up around {company}’s structure and branding, and delivered to an agreed scope with one accountable technology partner. An overview for groups:\n{link}' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'ما نساعد المجموعات على بنائه بيئة إدارة واحدة للمحفظة كاملة، تمكّن القيادة من:' },
          { ul: ['رؤية كل منشأة، والمجموعة ككل، من مكان واحد', 'توحيد العمليات والصلاحيات والاعتمادات عبر المنشآت', 'رفع التقارير لمجلس الإدارة من أرقام واحدة بدلاً من جداول شهرية', 'إضافة منشآت جديدة دون إضافة أنظمة جديدة'] },
          { p: 'وتُهيّأ حول هيكل {company} وهويتها، وتُنفَّذ وفق نطاق متفق عليه مع شريك تقني واحد مسؤول. نظرة عامة للمجموعات:\n{link}' }, SIG] },
      { id: 'msg3', offset: 5, limit: 1900, link: true, beat: 2,
        subject: L2('A working session on {company}’s operating model', 'جلسة عمل حول نموذج التشغيل في {company}'),
        why: L2('The focused group invitation the playbook asks for, framed as a strategy session rather than a demo: structure, reporting, governance and what to standardise first. The output is a documented brief and a scope.', 'دعوة المجموعة المركّزة التي يطلبها الدليل، بصيغة جلسة استراتيجية لا عرض توضيحي: الهيكل، والتقارير، والحوكمة، وما يُوحَّد أولاً. والنتيجة ملخص موثّق ونطاق.'),
        en: [{ p: 'Hello {first},' }, { p: 'I’d like to invite you and your leadership team to a 90-minute discovery workshop. It is a working session on {company}’s operating model, not a product demo. Together we map:' },
          { ul: ['How the portfolio is structured: managed, owned and franchised', 'What leadership needs to see, and how often', 'Who approves what, at group and property level', 'Which processes to standardise first, and how new properties join'] },
          { p: 'You leave with a documented group brief and a scope for the proposal. Owners or the CEO or COO usually join, with operations, IT and finance.\n{link}' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'أودّ دعوتكم مع فريق القيادة إلى ورشة استكشافية مدتها 90 دقيقة. إنها جلسة عمل حول نموذج التشغيل في {company}، لا عرض توضيحي للمنتج. نرسم فيها معاً:' },
          { ul: ['هيكل المحفظة: المنشآت المُدارة والمملوكة وبنظام الامتياز', 'ما تحتاج القيادة إلى رؤيته، وبأي وتيرة', 'من يعتمد ماذا على مستوى المجموعة والمنشأة', 'أي الإجراءات تُوحَّد أولاً، وكيف تنضم المنشآت الجديدة'] },
          { p: 'وتخرجون بملخص موثّق للمجموعة ونطاق للعرض. ويحضر عادةً المالك أو الرئيس التنفيذي أو مدير العمليات، مع التشغيل والتقنية والمالية.\n{link}' }, SIG] },
      { id: 'msg4', offset: 7, limit: 1900, link: true, beat: 3,
        subject: L2('Centralised operations for {company}', 'إدارة مركزية لعمليات {company}'),
        why: L2('Ends on the strategic invitation (explore how the group can centralise operations and see the whole portfolio), asks for the right person if needed, and promises one voice: every contact under one account owner.', 'تختم بالدعوة الاستراتيجية (استكشاف كيف تدير المجموعة عملياتها مركزياً وترى محفظتها كاملة)، وتطلب الشخص المناسب إن لزم، وتَعِد بصوت واحد: كل جهات الاتصال تحت مسؤول حساب واحد.'),
        en: [{ p: 'Hello {first},' }, { p: 'I’ll leave this with you. If you’d like to explore how {company} could centralise operations and see the whole portfolio from one place, the discovery workshop can be booked here:\n{link}' },
          { p: 'If these decisions sit with someone else in the group, I’d be grateful for an introduction. Every conversation stays under one account owner, so your team hears one voice from us.' }, SIG],
        ar: [{ p: 'مرحباً {first}،' }, { p: 'سأترك الأمر لكم. إن رغبتم في استكشاف كيف يمكن لـ {company} إدارة عملياتها مركزياً ورؤية المحفظة كاملة من مكان واحد، فيمكن حجز الورشة الاستكشافية من هنا:\n{link}' },
          { p: 'وإن كانت هذه القرارات لدى شخص آخر في المجموعة، فسأقدّر لكم التعريف به. ويبقى كل تواصل تحت مسؤول حساب واحد، ليسمع فريقكم صوتاً واحداً منا.' }, SIG] }
    ]
  };
  const NOTE_FALLBACK = { B: L2('I came across {company} while looking at independent properties in {city}.', 'اطّلعت على {company} أثناء متابعتي للمنشآت المستقلة في {city}.') };
  const STEP_LABEL = { note: L2('Connection note', 'ملاحظة طلب التواصل'), msg1: L2('First message', 'الرسالة الأولى'), msg2: L2('Follow-up 1', 'المتابعة الأولى'), msg3: L2('Follow-up 2', 'المتابعة الثانية'), msg4: L2('Last message', 'الرسالة الأخيرة') };

  /* ---------- sponsored content, carousel and posts (C, D), from campaigns/<X>/ad-copy.md and links.csv ---------- */
  const ADS = {
    C: [{ type: 'sponsored', id: 'sc', title: L2('Sponsored content · headline test', 'محتوى مموَّل · اختبار العنوان'),
      note: L2('Professional targeting: GM, owner, IT and finance titles at hotels in Saudi Arabia. First test: change only the creative’s headline (v1 against v2).', 'استهداف مهني: مسميات المدير العام والمالك والتقنية والمالية في فنادق المملكة. الاختبار الأول: غيّر عنوان التصميم فقط (v1 مقابل v2).'),
      img: 'book/media/C-opera-pms/feed/C-ad-{v}-{lang}.webp', alt: { v1: L2('Campaign C creative v1: Fewer systems around Opera. A clearer view of your hotel.', 'تصميم الحملة C v1: أنظمة أقل حول Opera، ورؤية أوضح لفندقك.'), v2: L2('Campaign C creative v2: Consolidate your hotel operations without replacing Opera.', 'تصميم الحملة C v2: وحّد عمليات فندقك دون استبدال Opera.') },
      intro: L2('Running Opera or a brand-mandated PMS? Bring HR, procurement, inventory, finance and compliance into one platform, and keep Opera as your PMS.', 'تعمل على Opera أو نظام PMS تفرضه العلامة؟ اجمع الموارد البشرية والمشتريات والمخزون والمالية والامتثال في منصة واحدة، ويبقى Opera نظامك.'),
      headline: L2('Request an operational and technology review', 'اطلب مراجعة تشغيلية وتقنية'), button: L2('Learn more', 'معرفة المزيد'), content: 'ad_{v}_{lang}',
      foot: L2('Every C creative carries the trademark line: Oracle and OPERA are trademarks of Oracle and/or its affiliates. No certification or endorsement implied.', 'يحمل كل تصميم للحملة C سطر العلامة التجارية: Oracle وOPERA علامتان تجاريتان لشركة Oracle و/أو الشركات التابعة لها، دون أي اعتماد أو تأييد ضمني.') }],
    D: [{ type: 'sponsored', id: 'sd', title: L2('Sponsored content · headline test', 'محتوى مموَّل · اختبار العنوان'),
      note: L2('Group-level titles: owner, CEO, COO, IT director, asset manager. First test: change only the creative’s headline (v1 against v2).', 'مسميات على مستوى المجموعة: المالك والرئيس التنفيذي ومدير العمليات ومدير التقنية ومدير الأصول. الاختبار الأول: غيّر عنوان التصميم فقط.'),
      img: 'book/media/D-hotel-groups/feed/D-ad-{v}-{lang}.webp', alt: { v1: L2('Campaign D creative v1: Centralised visibility across every property.', 'تصميم الحملة D v1: رؤية مركزية لكل منشآتك.'), v2: L2('Campaign D creative v2: Grow your portfolio. Keep control.', 'تصميم الحملة D v2: توسّع في محفظتك، وحافظ على السيطرة.') },
      intro: L2('Running 10 or more properties? See the whole portfolio from one management environment, standardise operations across every property and report to leadership from one set of figures.', 'تدير 10 منشآت أو أكثر؟ شاهد محفظتك كاملة من بيئة إدارة واحدة، ووحّد العمليات عبر كل منشأة، وارفع تقاريرك للقيادة من أرقام واحدة.'),
      headline: L2('Explore centralised management for my group', 'استكشف الإدارة المركزية لمجموعتي'), button: L2('Learn more', 'معرفة المزيد'), content: 'ad_{v}_{lang}',
      foot: L2('Any sample white-label screen is marked “Illustrative branding”.', 'تحمل أي شاشة تجريبية بهوية خاصة عبارة «هوية توضيحية».') },
      { type: 'carousel', id: 'cd', title: L2('Carousel · four cards', 'إعلان دوّار · أربع بطاقات'), note: L2('The reel’s four beats as cards. One link for the whole carousel.', 'مشاهد المقطع الأربعة في أربع بطاقات، برابط واحد للإعلان كله.'),
      img: 'book/media/D-hotel-groups/carousel/D-carousel-{n}-{lang}.webp', content: 'carousel_{lang}',
      cards: [L2('Many properties. Many systems. No single view.', 'منشآت كثيرة، وأنظمة كثيرة، ولا رؤية واحدة.'), L2('Centralised visibility across every property.', 'رؤية مركزية لكل منشآتك.'), L2('Standardise operations across your entire portfolio.', 'وحّد العمليات عبر محفظتك كاملة.'), L2('Grow the portfolio. Keep control.', 'توسّع في محفظتك، وحافظ على السيطرة.')] },
      { type: 'posts', id: 'pd', title: L2('LinkedIn content · three posts', 'محتوى لينكدإن · ثلاثة منشورات'), note: L2('For the company page or the account owner’s profile. The first line is the hook; keep it short. Tags use utm_medium=organic_social, a new value for the marketing lead to confirm.', 'لصفحة الشركة أو حساب مسؤول الحساب. السطر الأول هو الجاذب فاجعله قصيراً. تستخدم الوسوم utm_medium=organic_social، وهي قيمة جديدة يؤكّدها مسؤول التسويق.'),
      posts: [
        { t: L2('The problem', 'المشكلة'), en: 'Ten properties, ten ways of reporting.\n\nIn many hotel groups, each property added its own systems as it grew. The group view became a monthly spreadsheet that someone has to compile, and standards drifted from one property to the next.\n\nThe real question for group leadership isn’t which system each property uses. It’s whether the group can see the whole portfolio, and each property on its own, without waiting for that spreadsheet.\n\nThat’s what one management environment across every property is for.\n\n#HotelGroups #SaudiHospitality #Fandaqah', ar: 'عشر منشآت، وعشر طرق لإعداد التقارير.\n\nفي كثير من المجموعات الفندقية أضافت كل منشأة أنظمتها الخاصة مع نموها، فأصبحت صورة المجموعة جدول بيانات شهرياً ينتظر من يجمعه، وتباينت المعايير من منشأة إلى أخرى.\n\nالسؤال الحقيقي لقيادة المجموعة ليس أي نظام تستخدمه كل منشأة، بل هل ترى المجموعة محفظتها كاملة، وكل منشأة على حدة، دون انتظار ذلك الجدول؟\n\nولهذا وُجدت بيئة الإدارة الواحدة لكل المنشآت.\n\n#المجموعات_الفندقية #الضيافة_السعودية #فندقة' },
        { t: L2('The outcome', 'النتيجة'), en: 'Grow the portfolio. Keep control.\n\nThe hardest thing to keep as a hotel group grows is consistency: the same standards, the same approvals and figures leadership can trust, in every property.\n\nOne management environment gives the group centralised visibility, standard processes set once at group level, and executive reporting from one set of figures. New properties join the same environment instead of adding new systems.\n\nIt is delivered to an agreed scope, with your branding and one accountable technology partner.\n\nExplore centralised management for your group:\n{link}', ar: 'توسّع في محفظتك، وحافظ على السيطرة.\n\nأصعب ما يمكن الحفاظ عليه مع نمو المجموعة الفندقية هو الاتساق: المعايير نفسها، والاعتمادات نفسها، وأرقام تثق بها القيادة في كل منشأة.\n\nتمنح بيئة الإدارة الواحدة المجموعة رؤية مركزية، وإجراءات موحّدة تُضبط مرة واحدة على مستوى المجموعة، وتقارير للإدارة العليا من أرقام واحدة. وتنضم المنشآت الجديدة إلى البيئة نفسها بدلاً من إضافة أنظمة جديدة.\n\nوتُنفَّذ وفق نطاق متفق عليه، بهوية مجموعتك ومع شريك تقني واحد مسؤول.\n\nاستكشف الإدارة المركزية لمجموعتك:\n{link}' },
        { t: L2('The workshop', 'الورشة'), en: 'What happens in a 90-minute group discovery workshop. It is a working session on your operating model, not a product demo:\n\n1. How the portfolio is structured: managed, owned and franchised\n2. What leadership needs to see, and how often\n3. Who approves what, at group and property level\n4. Which processes to standardise first\n5. How new properties join, and how support and rollout work\n\nOwners or CEOs, operations, IT and finance in one room. The output is a documented group brief and a scope for the proposal.\n\n{link}', ar: 'ماذا يحدث في ورشة استكشافية للمجموعة مدتها 90 دقيقة؟ إنها جلسة عمل حول نموذج التشغيل لديكم، لا عرض توضيحي للمنتج:\n\n1. هيكل المحفظة: المنشآت المُدارة والمملوكة وبنظام الامتياز\n2. ما تحتاج القيادة إلى رؤيته، وبأي وتيرة\n3. من يعتمد ماذا على مستوى المجموعة والمنشأة\n4. أي الإجراءات تُوحَّد أولاً\n5. كيف تنضم المنشآت الجديدة، وكيف يعمل الدعم والإطلاق\n\nالمالك أو الرئيس التنفيذي، والتشغيل، والتقنية، والمالية في جلسة واحدة. والنتيجة ملخص موثّق للمجموعة ونطاق للعرض.\n\n{link}' }
      ] }]
  };
  const LIMITS = { intro: 600, introSoft: 150, headline: 200, headlineSoft: 70, post: 3000 };

  /* ---------- replies: shared answers plus each campaign's real objections ---------- */
  const REPLIES = [
    { id: 'yes', c: 'ABCD', q: L2('“Sounds interesting. Let’s talk.”', '«يبدو مثيراً للاهتمام، لنتحدث.»'),
      a: L2('Thank you, {first}. Which day this week or next suits you? I’ll send an invite for the {session}, and if there’s anything you’d like us to prepare for {company}, just tell me.', 'شكراً لكم. أي يوم هذا الأسبوع أو القادم يناسبكم؟ سأرسل دعوة لموعد {session}، وإن كان هناك ما تودّون أن نجهّزه لـ {company} فأخبروني.'),
      after: L2('Stop the sequence, ask the qualification questions below, book it and log it in the CRM.', 'أوقف التسلسل، واطرح أسئلة التأهيل أدناه، واحجز الموعد وسجّله في نظام إدارة العملاء.') },
    { id: 'later', c: 'ABCD', q: L2('“Not now. Maybe later.”', '«ليس الآن، ربما لاحقاً.»'),
      a: L2('Completely understood, {first}. When would be better to reconnect? I’ll note it and won’t follow up before then.', 'مفهوم تماماً. متى يناسبكم أن نتواصل مجدداً؟ سأدوّن ذلك ولن أتابع قبله.'),
      after: L2('Stop the sequence and record the agreed follow-up time in the CRM.', 'أوقف التسلسل وسجّل موعد المتابعة المتفق عليه في نظام إدارة العملاء.') },
    { id: 'tools', c: 'A', q: L2('“We don’t want to pay for tools we won’t use.”', '«لا نريد الدفع مقابل أدوات لن نستخدمها.»'),
      a: L2('That’s exactly what the review is for, {first}. We look at what {company} uses today and what would genuinely help next, and if nothing does, we’ll tell you so. Twenty minutes, no obligation.', 'وهذا بالضبط هدف المراجعة. ننظر فيما يستخدمه {company} اليوم وما قد يفيدكم فعلاً لاحقاً، وإن لم يكن هناك ما يفيد فسنخبركم بذلك. عشرون دقيقة دون أي التزام.'),
      after: L2('This is the playbook’s main A objection. Lead with value, never with the price.', 'هذا الاعتراض الأبرز في الحملة A حسب الدليل. ابدأ بالقيمة لا بالسعر.') },
    { id: 'credit', c: 'A', q: L2('“What happens to what we’ve already paid?”', '«ماذا عن المبلغ الذي دفعناه مسبقاً؟»'),
      a: L2('Fair question. In the review we confirm your current term and any approved credit or proration in writing, before anything is proposed.', 'سؤال مشروع. نؤكّد خلال المراجعة مدة اشتراككم الحالية وأي رصيد أو احتساب نسبي معتمد كتابياً قبل تقديم أي عرض.'),
      after: L2('Never promise that remaining subscription value will be refunded automatically.', 'لا تَعِد أبداً باسترداد القيمة المتبقية من الاشتراك تلقائياً.') },
    { id: 'system', c: 'B', q: L2('“We already have a system.”', '«لدينا نظام بالفعل.»'),
      a: L2('That makes sense, most properties do. The question is usually how many systems and vendors are involved, and how much gets typed twice between them. If a short look at how {company} runs would help, I can set it up. If not, no problem at all.', 'هذا متوقع، فأغلب المنشآت لديها نظام بالفعل. السؤال عادةً: كم نظاماً ومورّداً لديكم، وكم من البيانات يُدخل مرتين بينها؟ إن كانت نظرة قصيرة على طريقة تشغيل {company} مفيدة فسأرتّبها، وإن لم تكن فلا بأس إطلاقاً.'),
      after: L2('If they accept, book the walkthrough. If not, thank them and stop.', 'إن وافقوا فاحجز الجولة، وإن لم يوافقوا فاشكرهم وتوقّف.') },
    { id: 'why', c: 'B', q: L2('“Why would we change? What we have works.”', '«لماذا نغيّر؟ ما لدينا يعمل.»'),
      a: L2('It may well work, {first}. The question is what it costs to keep it working: data typed in twice, reports built by hand and several vendors to manage. The walkthrough puts {company}’s current setup beside a simpler one, so you can judge for yourself.', 'قد يكون كذلك فعلاً. والسؤال هو ما يكلّفه إبقاؤه يعمل: بيانات تُدخل مرتين، وتقارير تُعدّ يدوياً، وعدة موردين تتعاملون معهم. وتضع الجولة طريقة العمل الحالية في {company} بجانب طريقة أبسط، لتحكموا بأنفسكم.'),
      after: L2('Never criticise their current system or vendor. Compare effort and visibility, not brands.', 'لا تنتقد نظامهم أو مورّدهم الحالي أبداً. قارن الجهد والرؤية، لا العلامات.') },
    { id: 'switch', c: 'B', q: L2('“Switching sounds like a lot of work.”', '«الانتقال يبدو مجهوداً كبيراً.»'),
      a: L2('A fair concern. We start with a migration assessment of your current data and systems, agree training for each department and write down the support scope before anything is signed.', 'مخاوف مشروعة. نبدأ بتقييم لنقل بياناتكم وأنظمتكم الحالية، ونتفق على تدريب كل قسم، ونكتب نطاق الدعم قبل أي توقيع.'),
      after: L2('Never promise zero downtime, instant migration or regulatory approval without evidence.', 'لا تَعِد بانتقال فوري أو دون توقف أو باعتماد تنظيمي دون دليل.') },
    { id: 'term', c: 'AB', q: L2('“Do we have to commit for two years?”', '«هل علينا الالتزام لسنتين؟»'),
      a: L2('No. The launch price is for a one-year subscription, and the scope, fees and terms are confirmed in writing before anything is signed, so you can compare it properly.', 'لا. سعر الإطلاق لاشتراك مدته سنة، ويُؤكَّد النطاق والرسوم والشروط كتابياً قبل أي توقيع لتقارنوا براحتكم.'),
      after: L2('There is no two-year commitment. Mention the option to secure an additional year at the same rate only where the release record includes it (it does for A). Quote terms only from that record.', 'لا التزام لسنتين. لا تذكر خيار حجز سنة إضافية بالسعر نفسه إلا إن نصّ عليه سجل الإطلاق (وهو منصوص للحملة A)، ولا تذكر الشروط إلا من ذلك السجل.') },
    { id: 'keep', c: 'C', q: L2('“We have to keep Opera.”', '«يجب أن نبقى على Opera.»'),
      a: L2('Of course, and you won’t need to change it. {pms} stays your core PMS. What we look at is everything around it, such as HR, procurement, inventory, finance and compliance, and how many separate systems that work runs on today.', 'بالتأكيد، ولن تحتاجوا إلى تغييره. يبقى {pms} نظامكم الأساسي، وما ننظر فيه هو كل ما يحيط به، مثل الموارد البشرية والمشتريات والمخزون والمالية والامتثال، وعدد الأنظمة المنفصلة التي يعمل عليها ذلك اليوم.'),
      after: L2('Book the technical meeting; confirm product, version, deployment and interface access first.', 'احجز الاجتماع التقني، وأكّد أولاً المنتج والإصدار والنشر والوصول إلى الواجهات.') },
    { id: 'adds', c: 'C', q: L2('“So what exactly does Fandaqah add if we keep Opera?”', '«ما الذي تضيفه فندقة بالضبط إن بقينا على Opera؟»'),
      a: L2('One platform for the operations around your PMS: HR, CRM, procurement, inventory, finance, compliance workflows for ZATCA, Shomoos and NTMP, and management reporting. {pms} keeps its role, and every interface is confirmed for your version before anything is proposed.', 'منصة واحدة للعمليات المحيطة بنظامكم: الموارد البشرية، وإدارة علاقات العملاء، والمشتريات، والمخزون، والمالية، ومسارات الامتثال لهيئة الزكاة والضريبة والجمارك وشموس ومنصة الرصد السياحي، والتقارير الإدارية. ويبقى {pms} في دوره، ونؤكد كل واجهة لإصداركم قبل تقديم أي عرض.'),
      after: L2('Name only modules on the approved list. Never imply that Fandaqah replaces or modifies Opera.', 'لا تذكر إلا الوحدات المعتمدة، ولا توحِ أبداً بأن فندقة تستبدل Opera أو تعدّله.') },
    { id: 'already', c: 'C', q: L2('“We already have systems for HR and procurement.”', '«لدينا أنظمة للموارد البشرية والمشتريات بالفعل.»'),
      a: L2('Most hotels do. The useful question is how many there are, and what it takes to keep them connected to each other and to {pms}. The review shows where consolidation would help, and where it wouldn’t.', 'هذا حال أغلب الفنادق. والسؤال المفيد هو كم عددها، وما الذي يتطلبه إبقاؤها مترابطة فيما بينها ومع {pms}. وتوضح المراجعة أين يفيد التوحيد وأين لا يفيد.'),
      after: L2('Don’t argue system by system. Offer the review and let the map make the case.', 'لا تجادل نظاماً بنظام، بل اعرض المراجعة ودع الخريطة تقدّم الحجة.') },
    { id: 'cert', c: 'C', q: L2('“Are you certified by Oracle?”', '«هل أنتم معتمدون من Oracle؟»'),
      a: L2('No, and we don’t claim to be. What we do is confirm which interfaces are supported for your installed version in an integration assessment, and write the scope down before any proposal.', 'لا، ولا ندّعي ذلك. ما نفعله هو تأكيد الواجهات المدعومة لإصداركم المثبّت في تقييم للتكامل، وكتابة النطاق قبل أي عرض.'),
      after: L2('Never imply certification, partnership or endorsement. No third-party logos.', 'لا توحِ بأي اعتماد أو شراكة أو تأييد، ولا تستخدم شعارات الغير.') },
    { id: 'save', c: 'C', q: L2('“How much will we save?”', '«كم سنوفّر؟»'),
      a: L2('I can’t give you a number before we see your costs, and I wouldn’t trust anyone who did. The review builds a like-for-like comparison of your current and proposed scope, and that is where any saving shows.', 'لا أستطيع إعطاءكم رقماً قبل الاطلاع على تكاليفكم، ولن أثق بمن يفعل. تبني المراجعة مقارنة دقيقة بين نطاقكم الحالي والمقترح، وهناك يظهر أي توفير.'),
      after: L2('No “50% savings”. SAR 20,000 against SAR 10,000 is an illustration only, never a promise.', 'لا تقل «توفير 50%». مثال 20,000 مقابل 10,000 ريال للتوضيح فقط، وليس وعداً.') },
    { id: 'props', c: 'D', q: L2('“Each property chooses its own systems.”', '«كل منشأة تختار أنظمتها بنفسها.»'),
      a: L2('That’s common, and it’s one of the first things the workshop maps: which properties are managed, owned or franchised, who controls technology purchasing for each, and which standards the group wants to set centrally.', 'هذا شائع، وهو من أول ما ترسمه الورشة: أي المنشآت مُدارة أو مملوكة أو بنظام الامتياز، ومن يتحكم في شراء التقنية لكلٍّ منها، وأي المعايير تريد المجموعة ضبطها مركزياً.'),
      after: L2('Record the structure and the technology buyer against the group account.', 'سجّل الهيكل وصاحب قرار التقنية على حساب المجموعة.') },
    { id: 'reports', c: 'D', q: L2('“We already get reports from each property.”', '«تصلنا تقارير من كل منشأة بالفعل.»'),
      a: L2('Most groups do, {first}. The useful question is how long they take to put together, and whether they compare like for like. The workshop maps what leadership needs to see and how it could come from one place, without waiting for month-end.', 'هذا حال أغلب المجموعات. والسؤال المفيد هو كم يستغرق تجميعها، وهل تُقارن ببعضها على أساس واحد. وترسم الورشة ما تحتاج القيادة إلى رؤيته، وكيف يمكن أن يأتي من مكان واحد دون انتظار نهاية الشهر.'),
      after: L2('Talk about the decisions the group can’t make quickly today, not about features.', 'تحدّث عن القرارات التي لا تستطيع المجموعة اتخاذها بسرعة اليوم، لا عن الميزات.') },
    { id: 'own', c: 'D', q: L2('“Can we fully customise it and own it?”', '«هل يمكننا تخصيصها بالكامل وامتلاكها؟»'),
      a: L2('Branding, workflows and customisation are agreed to a defined scope in the proposal. Hosting, ownership and changes are set out in the contract, so everything is clear before you commit.', 'تُتفق الهوية ومسارات العمل والتخصيص ضمن نطاق محدد في العرض، وتُحدَّد الاستضافة والملكية والتعديلات في العقد، ليكون كل شيء واضحاً قبل الالتزام.'),
      after: L2('Never say “100% customised”. White labelling does not mean source-code ownership, unlimited changes or exclusivity.', 'لا تقل «مخصّصة 100%». الهوية الخاصة لا تعني ملكية الشيفرة المصدرية ولا تعديلات غير محدودة ولا حصرية.') },
    { id: 'price', c: 'ABCD', q: L2('“What does it cost?”', '«كم التكلفة؟»'), a: null, after: null },
    { id: 'stop', c: 'ABCD', q: L2('“Please don’t message me again.”', '«أرجو عدم مراسلتي مجدداً.»'),
      a: L2('Understood, {first}. I won’t message you again about this. Thank you for letting me know, and all the best to the team at {company}.', 'مفهوم تماماً، ولن أراسلكم مجدداً بهذا الخصوص. شكراً لإبلاغي، وكل التوفيق لفريق {company}.'),
      after: L2('Respect the opt-out immediately: suppress the contact in the CRM today, on every channel, and record the reason.', 'احترم طلب الإيقاف فوراً: استبعد جهة الاتصال في نظام إدارة العملاء اليوم على كل القنوات، وسجّل السبب.') }
  ];
  const PRICE = {
    AB: { off: L2('Good question. The price depends on the modules and the term, so I’d rather give you an exact figure than a range. We confirm it in the {session}, together with our launch pricing and what it includes.', 'سؤال في محلّه. يعتمد السعر على الوحدات ومدة الاشتراك، لذا أفضّل أن أعطيكم رقماً دقيقاً بدلاً من نطاق تقريبي. نؤكّده خلال {session} مع سعر الإطلاق وما يشمله.'),
      on: L2('The launch price is SAR {annual} a year{vat}, on a one-year subscription. In the {session} we confirm which modules it covers for {company}, and the full terms.', 'سعر الإطلاق {annual} ريال سنوياً{vat}، باشتراك مدته سنة. ونؤكّد خلال {session} الوحدات التي يشملها لـ {company} والشروط كاملة.'),
      afterOff: L2('No prices in writing until the commercial release record is signed.', 'لا أسعار مكتوبة قبل اعتماد سجل الإطلاق التجاري.'), afterOn: L2('Figures from the signed release record. Never copy them into C or D proposals.', 'الأرقام من سجل الإطلاق المعتمد، ولا تُنقل إلى عروض الحملتين C وD.') },
    CD: { off: L2('It depends on the scope, so it comes as a scoped proposal after the {session}, with setup, ongoing service and any agreed customisation priced separately.', 'يعتمد على النطاق، لذا يأتي في عرض محدد النطاق بعد {session}، تُسعَّر فيه رسوم الإعداد والخدمة المستمرة وأي تخصيص متفق عليه بشكل منفصل.'),
      afterOff: L2('C and D need a scoped proposal. Never quote the A/B package price for Opera integrations or a dedicated environment.', 'تحتاج الحملتان C وD إلى عرض محدد النطاق، ولا يُذكر سعر باقة A/B لتكاملات Opera أو لبيئة مخصّصة.') }
  };
  const QUALIFY = {
    A: L2(['Account ID and current term', 'Modules included today', 'Any approved credit or proration', 'Who decides: owner, GM or finance'], ['رقم الحساب ومدة الاشتراك الحالية', 'الوحدات المشمولة حالياً', 'أي رصيد أو احتساب نسبي معتمد', 'صاحب القرار: المالك أو المدير العام أو المالية']),
    B: L2(['Property type, rooms or units, city', 'Systems and vendors in use today, and what is painful', 'Where work is typed twice or reports are built by hand', 'Who decides and who influences', 'Timeline and what would make them switch'], ['نوع المنشأة وعدد الغرف أو الوحدات والمدينة', 'الأنظمة والموردون الحاليون وما يزعجهم فيها', 'أين تُدخل البيانات مرتين أو تُعدّ التقارير يدوياً', 'صاحب القرار ومن يؤثّر فيه', 'الإطار الزمني وما الذي يدفعهم للتغيير']),
    C: L2(['Systems in use today for HR, procurement, inventory, finance and reporting', 'Where work is repeated or visibility is missing', 'Installed PMS product and version', 'Deployment: cloud, on-premise or brand-hosted', 'Interface access, and who grants it', 'Required workflows and service scope', 'Brand or owner approval route', 'The GM or owner, IT and finance in one conversation'], ['الأنظمة المستخدمة اليوم للموارد البشرية والمشتريات والمخزون والمالية والتقارير', 'أين يتكرر العمل أو تغيب الرؤية', 'منتج PMS المثبّت وإصداره', 'طريقة النشر: سحابي أو محلي أو مستضاف لدى العلامة', 'الوصول إلى الواجهات ومن يمنحه', 'مسارات العمل المطلوبة ونطاق الخدمة', 'مسار اعتماد العلامة أو المالك', 'المدير العام أو المالك والتقنية والمالية في حديث واحد']),
    D: L2(['Managed, owned or franchised properties', 'Approximate property count and growth plans', 'How leadership gets the group view today', 'Processes the group wants standardised first', 'Who controls technology purchasing', 'Current systems per property', 'Decision-makers: owner or CEO, COO, IT, finance'], ['المنشآت المُدارة أو المملوكة أو بنظام الامتياز', 'العدد التقريبي للمنشآت وخطط النمو', 'كيف تحصل القيادة على رؤية المجموعة اليوم', 'الإجراءات التي تريد المجموعة توحيدها أولاً', 'من يتحكم في شراء التقنية', 'الأنظمة الحالية في كل منشأة', 'أصحاب القرار: المالك أو الرئيس التنفيذي، ومدير العمليات، والتقنية، والمالية'])
  };

  /* ---------- headline options (playbook pp. 2 to 5) with their status ---------- */
  const HEADLINES = {
    A: [{ tag: 'v1', ...L2('More value from the Fandaqah you already use.', 'قيمة أكبر من فندقة التي تستخدمها اليوم.') }, { tag: 'v2', ...L2('A loyalty offer built around your hotel.', 'عرض ولاء مصمَّم حول فندقك.') },
      { tag: 'opt', ...L2('Bring more of your hotel operations into one connected system.', 'اجمع مزيداً من عمليات فندقك في نظام واحد مترابط.') },
      { tag: 'hold', ...L2('Upgrade to Fandaqah 360 at the launch price.', 'قم بالترقية إلى فندقة 360 بسعر الإطلاق.') },
      { tag: 'hold', ...L2('More value for your hotel, at the launch price.', 'قيمة أكبر لفندقك بسعر الإطلاق.') }],
    B: [{ tag: 'v1', ...L2('An easier way to run your hotel.', 'طريقة أسهل لإدارة فندقك.') }, { tag: 'v2', ...L2('Too many systems for one hotel? Bring them into one place.', 'أنظمة كثيرة لفندق واحد؟ اجمعها في مكان واحد.') },
      { tag: 'opt', ...L2('Manage more of your hotel from a single platform.', 'أدِر جزءاً أكبر من فندقك من منصة واحدة.') }, { tag: 'opt', ...L2('Spend less time managing disconnected hotel systems.', 'وفّر الوقت الذي تقضيه في إدارة أنظمة فندقية متفرقة.') },
      { tag: 'opt', ...L2('Built for Saudi hospitality. Designed for your daily work.', 'مصمَّمة لقطاع الضيافة السعودي ولعملك اليومي.') }, { tag: 'opt', ...L2('Your hotel operations. One connected platform.', 'عمليات فندقك. منصة واحدة مترابطة.') },
      { tag: 'hold', ...L2('Choose Fandaqah 360 at the launch price.', 'اختر فندقة 360 بسعر الإطلاق.') }],
    C: [{ tag: 'v1', ...L2('Fewer systems around Opera. A clearer view of your hotel.', 'أنظمة أقل حول Opera، ورؤية أوضح لفندقك.') }, { tag: 'v2', ...L2('Consolidate your hotel operations without replacing Opera.', 'وحّد عمليات فندقك دون استبدال Opera.') },
      { tag: 'opt', ...L2('See where your hotel can reduce technology costs.', 'اكتشف أين يمكن لفندقك خفض تكاليف التقنية.') }, { tag: 'opt', ...L2('Enhance the efficiency of your Opera-based hotel operations.', 'ارفع كفاءة عمليات فندقك المبنية على Opera.') },
      { tag: 'opt', ...L2('Strengthen your Opera PMS with connected hotel operations.', 'عزّز نظام Opera PMS لديك بعمليات فندقية مترابطة.') }, { tag: 'opt', ...L2('More connected workflows around your Opera PMS.', 'مسارات عمل أكثر ترابطاً حول نظام Opera PMS.') },
      { tag: 'off', ...L2('50% lower technology costs', 'تكاليف تقنية أقل بنسبة 50%'), why: L2('Only with a documented, comparable cost case, scope and period (p.11).', 'فقط مع حالة تكلفة موثّقة وقابلة للمقارنة ونطاق ومدة واضحين (ص 11).') }],
    D: [{ tag: 'v1', ...L2('Centralised visibility across every property.', 'رؤية مركزية لكل منشآتك.') }, { tag: 'v2', ...L2('Grow your portfolio. Keep control.', 'توسّع في محفظتك، وحافظ على السيطرة.') },
      { tag: 'opt', ...L2('Standardise operations across your entire portfolio.', 'وحّد العمليات عبر محفظتك الفندقية كاملة.') }, { tag: 'opt', ...L2('Manage multiple properties through one unified operating environment.', 'أدِر منشآت متعددة من بيئة تشغيل واحدة موحّدة.') },
      { tag: 'opt', ...L2('A dedicated hotel technology platform built around your group.', 'منصة تقنية فندقية مخصّصة مبنية حول مجموعتك.') }, { tag: 'opt', ...L2('Your brand. Your policies. Your hotel technology.', 'هويتك. سياساتك. تقنيتك الفندقية.') },
      { tag: 'off', ...L2('100% customised, white label you own', 'مخصّصة 100% وبهوية تملكها'), why: L2('Say “customised to an agreed scope”; white labelling is not source-code ownership (p.5, p.11).', 'قل «مخصّصة ضمن نطاق متفق عليه»، فالهوية الخاصة ليست ملكية للشيفرة (ص 5 وص 11).') }]
  };

  /* ---------- playbook reference ---------- */
  const PLAY = {
    camps: { t: L2('Four campaigns, four buyers', 'أربع حملات، أربعة مشترين'), cols: L2(['Campaign', 'Buyer and concern', 'Desired action', 'LinkedIn role'], ['الحملة', 'المشتري واهتمامه', 'الإجراء المطلوب', 'دور لينكدإن']), src: L2('Playbook p.1 to p.5', 'دليل الحملة ص 1 إلى ص 5') },
    groups: [
      { icon: 'i-route', t: L2('Route every account to one campaign', 'وجّه كل حساب إلى حملة واحدة'), src: L2('p.1, p.6', 'ص 1، ص 6'), items: [
        L2('An existing customer takes A, always.', 'العميل الحالي يأخذ A دائماً.'), L2('A new local group of about 10 or more properties takes D.', 'المجموعة المحلية الجديدة بنحو 10 منشآت أو أكثر تأخذ D.'),
        L2('A property with a confirmed mandated PMS takes C. A brand name or star rating does not prove the PMS.', 'المنشأة بنظام مفروض مؤكَّد تأخذ C، واسم العلامة أو تصنيف النجوم ليس دليلاً على النظام.'),
        L2('Other independent properties take B. Unknown PMS, role or ownership goes to the research queue.', 'بقية المنشآت المستقلة تأخذ B، والنظام أو الدور أو الملكية غير المعروفة تذهب إلى قائمة البحث.'),
        L2('A group’s branded property may need a C demonstration inside the D discussion. Never send conflicting offers.', 'قد تحتاج منشأة بعلامة ضمن مجموعة إلى عرض C داخل نقاش D، ولا تُرسل عروضاً متعارضة أبداً.')] },
      { icon: 'i-shield-check', t: L2('Contact eligibility', 'أهلية جهة الاتصال'), src: L2('p.6', 'ص 6'), items: [
        L2('Statuses: verified identity and business route; shared hotel route only; unverified; invalid.', 'الحالات: هوية موثَّقة ومسار عمل مؤكَّد، رقم الفندق العام فقط، غير موثَّق، غير صالح.'),
        L2('Record marketing permission separately from contact accuracy, with the permitted channels.', 'سجّل الإذن التسويقي منفصلاً عن دقة البيانات، مع القنوات المسموحة.'),
        L2('A public directory or government registration is not marketing consent. Respect opt-outs immediately.', 'الدليل العام أو التسجيل الحكومي ليس إذناً تسويقياً، واحترم طلبات الإيقاف فوراً.'),
        L2('Start with a small verified sample, for example 20 to 30 eligible records per segment, then review before expanding.', 'ابدأ بعيّنة موثّقة صغيرة، مثل 20 إلى 30 سجلاً مؤهلاً لكل شريحة، ثم راجِع قبل التوسّع.')] },
      { icon: 'i-badge-check', t: L2('Keep the offers separate', 'افصل بين العروض'), src: L2('p.1, p.11', 'ص 1، ص 11'), items: [
        L2('A and B promote Fandaqah 360 at the launch price: SAR 3,495 a year excl. VAT, on a one-year subscription, then standard pricing. A customers can also secure one additional year at the same rate during the promotion and have the 30-day Golden Guarantee; B has a 30-day money-back guarantee. Never call it a two-year subscription or a two-year price lock.', 'تروّج A وB لفندقة 360 بسعر الإطلاق: 3,495 ريالاً سنوياً غير شاملة الضريبة، باشتراك مدته سنة، ثم الأسعار الاعتيادية. ويمكن لعملاء A حجز سنة إضافية بالسعر نفسه خلال العرض، مع الضمان الذهبي لمدة 30 يوماً، ولـ B ضمان استرداد المال خلال 30 يوماً. لا تصفه أبداً باشتراك لسنتين أو بتثبيت السعر لسنتين.'),
        L2('Until the commercial release record confirms VAT, scope, fees, eligibility, credits and the end date, say “Ask about our launch pricing”.', 'حتى يؤكّد سجل الإطلاق التجاري الضريبة والنطاق والرسوم والأهلية والأرصدة وتاريخ الانتهاء، قل «اسأل عن سعر الإطلاق».'),
        L2('C and D require a scoped proposal. Never copy A/B pricing into C or D.', 'تحتاج C وD إلى عرض محدد النطاق، ولا تُنقل أسعار A/B إليهما أبداً.')] },
      { icon: 'i-clock', t: L2('Respond and follow up', 'الرد والمتابعة'), src: L2('p.8', 'ص 8'), items: [
        L2('Proposed service target: respond within one business hour during staffed hours; acknowledge outside them.', 'هدف الخدمة المقترح: الرد خلال ساعة عمل واحدة في ساعات العمل، والإقرار بالاستلام خارجها.'),
        L2('At most three agreed follow-ups across seven business days, unless the person opts out or asks for another schedule. This kit sends them on working days 3, 5 and 7.', 'ثلاث متابعات متفق عليها كحد أقصى خلال سبعة أيام عمل، ما لم يطلب الشخص الإيقاف أو موعداً آخر. ترسلها هذه الحزمة في أيام العمل 3 و5 و7.'),
        L2('Ask the segment’s qualification questions; do not send the same generic demo to all four audiences.', 'اطرح أسئلة التأهيل الخاصة بالشريحة، ولا ترسل العرض العام نفسه للجماهير الأربعة.'),
        L2('Never promise an unsupported integration, unapproved price, unlimited customisation or guaranteed savings.', 'لا تَعِد أبداً بتكامل غير مدعوم أو سعر غير معتمد أو تخصيص غير محدود أو توفير مضمون.')] },
      { icon: 'i-type', t: L2('LinkedIn formats and limits', 'صيغ لينكدإن وحدودها'), src: L2('LinkedIn specifications', 'مواصفات لينكدإن'), items: [
        L2('Connection note: 200 characters works on every account (Premium allows 300).', 'ملاحظة التواصل: 200 حرف تعمل على كل الحسابات (المميز يسمح بـ 300).'),
        L2('InMail: subject up to 200 characters, body up to 1,900. Messages are plain text.', 'رسائل InMail: العنوان حتى 200 حرف والنص حتى 1,900، والرسائل نص عادي.'),
        L2('Sponsored content: intro text up to 600 (about 150 before “see more”), headline up to 200 (about 70 recommended), 4:5 images accepted.', 'المحتوى المموَّل: النص التمهيدي حتى 600 حرف (نحو 150 قبل «عرض المزيد»)، والعنوان حتى 200 (يُنصح بنحو 70)، وتُقبل صور 4:5.'),
        L2('No link in the note or the first message. Never put names, phones or emails in a link.', 'لا رابط في الملاحظة ولا في الرسالة الأولى، ولا أسماء أو هواتف أو بريد في أي رابط.')] },
      { icon: 'i-calendar-days', t: L2('Timing', 'التوقيت'), src: L2('Kit guidance', 'إرشاد الحزمة'), items: [
        L2('The Saudi working week runs Sunday to Thursday. Every date here skips Friday and Saturday.', 'أسبوع العمل في المملكة من الأحد إلى الخميس، وكل التواريخ هنا تتخطّى الجمعة والسبت.'),
        L2('Send in staffed hours, so replies can be answered within the service target.', 'أرسل في ساعات العمل ليُرد على الردود ضمن هدف الخدمة.'),
        L2('Pause sequences over Eid, Founding Day (22 February) and National Day (23 September).', 'أوقف التسلسلات خلال العيدين ويوم التأسيس (22 فبراير) واليوم الوطني (23 سبتمبر).')] }
    ],
    claims: { t: L2('Claims and the copy to use', 'الادعاءات والصياغة المعتمدة'), src: L2('p.11', 'ص 11'), cols: L2(['Claim', 'Copy to use'], ['الادعاء', 'الصياغة المعتمدة']), rows: [
      [L2('50% lower subscriptions or AMC', 'اشتراكات أو صيانة أقل بنسبة 50%'), L2('“See where your hotel can reduce technology costs.” A percentage only with a documented, comparable cost case.', '«اكتشف أين يمكن لفندقك خفض تكاليف التقنية.» ولا نسبة إلا بحالة تكلفة موثّقة وقابلة للمقارنة.')],
      [L2('100% Saudi compliant', 'متوافق 100% مع المتطلبات السعودية'), L2('“Built for Saudi hospitality and supported regulatory workflows.” Name only approved, supported obligations.', '«مصمَّمة لقطاع الضيافة السعودي مع دعم سير العمل التنظيمي.» واذكر فقط المتطلبات المعتمدة والمدعومة.')],
      [L2('Saudi made', 'صُنع في السعودية'), L2('“Built for Saudi hospitality” until the product lead confirms the origin statement.', '«مصمَّمة لقطاع الضيافة السعودي» حتى يؤكّد مسؤول المنتج بيان المنشأ.')],
      [L2('AI powered', 'مدعوم بالذكاء الاصطناعي'), L2('“See AI assist your hotel workflow” only beside a demonstrated feature, with inputs, review and availability stated.', '«شاهد الذكاء الاصطناعي يساعد سير عمل فندقك» فقط بجانب ميزة معروضة فعلياً مع ذكر المدخلات والمراجعة والتوفر.')],
      [L2('24/7 local support', 'دعم محلي على مدار الساعة'), L2('“Support for your hotel operations”, with the approved hours, unless staffing supports more.', '«دعم لعمليات فندقك» مع الساعات المعتمدة، ما لم يدعم التوظيف أكثر من ذلك.')],
      [L2('Dedicated and white labelled', 'مخصّصة وبهوية خاصة'), L2('“Dedicated deployment and branding to an agreed scope.”', '«نشر مخصّص وهوية خاصة وفق نطاق متفق عليه.»')]] },
    scores: { t: L2('Scorecard for this campaign', 'بطاقة قياس هذه الحملة'), src: L2('p.2 to p.5, p.10', 'ص 2 إلى ص 5، ص 10'),
      A: L2(['Completed upgrade reviews', 'Proposals', 'Paid upgrades', 'Collected cash'], ['مراجعات الترقية المكتملة', 'العروض المقدّمة', 'الترقيات المدفوعة', 'المبالغ المحصّلة']),
      B: L2(['Qualified property enquiries', 'Attended demos', 'Proposals', 'Paid subscriptions', 'Collected cash'], ['استفسارات المنشآت المؤهلة', 'العروض التوضيحية التي حُضرت', 'العروض المقدّمة', 'الاشتراكات المدفوعة', 'المبالغ المحصّلة']),
      C: L2(['Qualified operational and technology reviews', 'Technical meetings', 'Integration assessments', 'Scoped proposals', 'Signed business'], ['المراجعات التشغيلية والتقنية المؤهلة', 'الاجتماعات التقنية', 'تقييمات التكامل', 'العروض محددة النطاق', 'الأعمال الموقّعة']),
      D: L2(['Qualified group meetings', 'Workshops', 'Documented opportunities', 'Pilots', 'Signed group agreements'], ['اجتماعات المجموعات المؤهلة', 'الورش', 'الفرص الموثّقة', 'التجارب', 'اتفاقيات المجموعات الموقّعة']),
      qual: L2('A real Saudi property or group, a relevant business contact, a suitable need and an agreed next step.', 'منشأة أو مجموعة سعودية حقيقية، وجهة اتصال عمل مناسبة، وحاجة ملائمة، وخطوة تالية متفق عليها.'),
      qualX: { A: L2('For A, add the existing account ID.', 'وللحملة A أضف رقم الحساب الحالي.'), B: L2('Report upgrades separately from new customers.', 'افصل الترقيات عن العملاء الجدد في التقارير.'), C: L2('For C, add the confirmed mandated PMS.', 'وللحملة C أضف النظام المفروض المؤكَّد.'), D: L2('For D, add the group scope.', 'وللحملة D أضف نطاق المجموعة.') },
      sep: L2('Record invalid contacts, bounces and opt-outs separately. Never combine the four funnels into one result.', 'سجّل جهات الاتصال غير الصالحة والمرتدّة وطلبات الإيقاف منفصلة، ولا تدمج المسارات الأربعة في نتيجة واحدة.') },
    changes: { t: L2('What changed in this version', 'ما الذي تغيّر في هذه النسخة'), cols: L2(['Before', 'Now', 'Why'], ['قبل', 'الآن', 'السبب']), rows: [
      [L2('Campaigns A and B only; C and D sent elsewhere', 'الحملتان A وB فقط، وC وD في مكان آخر'), L2('All four, with C and D marked as LinkedIn’s primary campaigns', 'الحملات الأربع، مع تمييز C وD كحملات لينكدإن الرئيسية'), L2('The playbook puts LinkedIn in C (professional targeting) and D (LinkedIn content).', 'يضع الدليل لينكدإن في C (استهداف مهني) وD (محتوى لينكدإن).')],
      [L2('Campaign picked by hand', 'اختيار الحملة يدوياً'), L2('Routed by the playbook’s rules; conflicts flagged', 'توجيه بقواعد الدليل مع تنبيه عند التعارض'), L2('One primary campaign per account; no conflicting offers (p.1, p.6).', 'حملة رئيسية واحدة لكل حساب دون عروض متعارضة (ص 1، ص 6).')],
      [L2('No contact checks', 'لا تحقق من جهة الاتصال'), L2('Verification, permission and suppression before sending', 'التحقق والإذن والاستبعاد قبل الإرسال'), L2('Release only eligible records for the channel (p.6).', 'لا تُطلق إلا السجلات المؤهلة للقناة (ص 6).')],
      [L2('Generic A personalisation', 'تخصيص عام للحملة A'), L2('Current plan, renewal date and unused modules', 'الباقة الحالية وتاريخ التجديد والوحدات غير المستخدمة'), L2('Exactly what p.2 asks for.', 'وهو بالضبط ما تطلبه ص 2.')],
      [L2('Launch price written freely', 'سعر الإطلاق يُكتب دون ضوابط'), L2('Held behind the commercial release switch', 'معلّق خلف مفتاح الإطلاق التجاري'), L2('It is a playbook headline that waits for the release record (p.2, p.3, p.11).', 'إنه عنوان في الدليل ينتظر سجل الإطلاق (ص 2، ص 3، ص 11).')],
      [L2('Follow-ups on working days 3, 6 and 10', 'متابعات في أيام العمل 3 و6 و10'), L2('Working days 3, 5 and 7', 'أيام العمل 3 و5 و7'), L2('At most three follow-ups across seven business days (p.8).', 'ثلاث متابعات كحد أقصى خلال سبعة أيام عمل (ص 8).')],
      [L2('Messages written freely', 'رسائل مكتوبة بحرية'), L2('Each touch tells one beat of the campaign storyboard', 'كل رسالة تروي مشهداً من القصة المصوّرة للحملة'), L2('One story across reel, ads and messages (p.2 to p.5).', 'قصة واحدة عبر المقطع والإعلانات والرسائل (ص 2 إلى ص 5).')],
      [L2('“More than 800 properties”', '«أكثر من 800 منشأة»'), L2('Removed', 'حُذف'), L2('Not among the playbook’s approved claims; measured results need permission and evidence (p.7).', 'ليس ضمن الادعاءات المعتمدة في الدليل، والنتائج المقيسة تحتاج إذناً ودليلاً (ص 7).')],
      [L2('C led with method: technical review, integration assessment, scope', 'الحملة C تبدأ بالمنهج: مراجعة تقنية، وتقييم تكامل، ونطاق'), L2('C leads with the outcome: fewer systems, clearer visibility, Opera kept; the method follows', 'الحملة C تبدأ بالنتيجة: أنظمة أقل، ورؤية أوضح، ويبقى Opera؛ ثم يأتي المنهج'), L2('Reviewer feedback: prospects understood that Fandaqah works with Opera, not why it matters to them.', 'ملاحظات المراجعة: فهم العملاء أن فندقة تعمل مع Opera، لا لماذا يهمهم ذلك.')],
      [L2('Two C roles: IT and finance', 'دوران في الحملة C: التقنية والمالية'), L2('Three: GM or owner first, then IT and finance', 'ثلاثة أدوار: المدير العام أو المالك أولاً، ثم التقنية والمالية'), L2('Operational outcomes are what GMs and owners approve; the playbook already counts them in the C buying group.', 'النتائج التشغيلية هي ما يعتمده المدير العام والمالك، والدليل يعدّهما ضمن مجموعة الشراء في الحملة C.')],
      [L2('CTA “Request a technology cost review”', 'الإجراء «اطلب مراجعة تكاليف التقنية»'), L2('“Request an operational and technology review”', '«اطلب مراجعة تشغيلية وتقنية»'), L2('A cost-only CTA speaks to finance; operations and technology bring in the GM, the owner and IT.', 'الإجراء المقتصر على التكلفة يخاطب المالية فقط، والتشغيل والتقنية يضيفان المدير العام والمالك والتقنية.')],
      [L2('B led with the product and its modules', 'الحملة B تبدأ بالمنتج ووحداته'), L2('B leads with the owner’s day: fewer systems, less manual work, a clearer view and fewer vendors', 'الحملة B تبدأ بيوم المالك: أنظمة أقل، وعمل يدوي أقل، ورؤية أوضح، وموردون أقل'), L2('Reviewer feedback: every message should answer “why change from my current setup?” and leave one impression, an easier way to run the property.', 'ملاحظات المراجعة: يجب أن تجيب كل رسالة عن «لماذا أغيّر وضعي الحالي؟» وتترك انطباعاً واحداً: طريقة أسهل لإدارة المنشأة.')],
      [L2('B CTA “Book my hotel demo”', 'إجراء B «احجز عرضاً توضيحياً لفندقي»'), L2('“Explore a simpler way to run my hotel”', '«اكتشف طريقة أبسط لإدارة فندقي»'), L2('The outcome is more prominent than the product; the walkthrough is still how it happens.', 'النتيجة أبرز من المنتج، والجولة هي طريقة تحقيقها.')],
      [L2('D spoke about a dedicated platform and branding', 'الحملة D تتحدث عن منصة مخصّصة وهوية'), L2('D speaks to the portfolio: centralised visibility, standard operations, governance, executive reporting and growth', 'الحملة D تخاطب المحفظة: رؤية مركزية، وعمليات موحّدة، وحوكمة، وتقارير للإدارة العليا، ونمو'), L2('Reviewer feedback: group leaders buy control of the portfolio, not modules or single-property features.', 'ملاحظات المراجعة: قادة المجموعات يشترون السيطرة على المحفظة، لا الوحدات ولا ميزات المنشأة الواحدة.')],
      [L2('D CTA “Book a group discovery meeting”', 'إجراء D «احجز اجتماعاً استكشافياً للمجموعة»'), L2('“Explore centralised management for my group”', '«استكشف الإدارة المركزية لمجموعتي»'), L2('A strategic invitation for executives; the discovery workshop is how it happens.', 'دعوة استراتيجية للقيادة، والورشة الاستكشافية هي طريقة تحقيقها.')],
      [L2('A/B offer: a two-year prepaid subscription at the playbook’s working figure, SAR 3,349', 'عرض A/B: اشتراك مدفوع مسبقاً لسنتين بالرقم المبدئي في الدليل، 3,349 ريالاً'), L2('Launch price SAR 3,495 a year excl. VAT, on a one-year subscription; A can secure one more year at the same rate', 'سعر الإطلاق 3,495 ريالاً سنوياً غير شاملة الضريبة، باشتراك مدته سنة، ويمكن لعملاء A حجز سنة إضافية بالسعر نفسه'), L2('The approved offer. No two-year commitment anywhere; messages show the price only once the release switch is on.', 'العرض المعتمد، دون أي التزام لسنتين، ولا يظهر السعر في الرسائل إلا بعد تفعيل مفتاح الإطلاق.')],
      [L2('Draft: invented 40% saving and testimonial; “فندق 360”', 'المسودة: توفير 40% وشهادة مختلقان، و«فندق 360»'), L2('Removed; فندقة 360 throughout', 'حُذفت، و«فندقة 360» في كل المواضع'), L2('No invented reviews or savings (p.7); the brand is فندقة.', 'لا آراء أو توفير مختلق (ص 7)، والعلامة هي فندقة.')]] }
  };

  /* ---------- demo accounts, one per campaign, marked as demo ---------- */
  const DEMO = {
    A: { f: { en: { first: 'Faisal', company: 'Al Waha Suites', city: 'Al Khobar', rep: 'Noura', plan: 'Basic Subscription', note: '' }, ar: { first: 'فيصل', company: 'أجنحة الواحة', city: 'الخبر', rep: 'نورة', plan: 'الاشتراك الأساسي', note: '' } }, renewal: '2026-12-15', modules: ['accounting', 'purchasing'], account: 'FQ-10482', r: { existing: 'yes', props: '1', pms: 'other' } },
    B: { f: { en: { first: 'Faisal', company: 'Al Waha Suites', city: 'Al Khobar', rep: 'Noura', note: 'I saw your post about the new rooftop lounge. Congratulations.' }, ar: { first: 'فيصل', company: 'أجنحة الواحة', city: 'الخبر', rep: 'نورة', note: 'اطّلعت على منشوركم عن افتتاح الصالة الجديدة، مبارك لكم.' } }, r: { existing: 'no', props: '1', pms: 'other' } },
    C: { f: { en: { first: 'Reem', company: 'Najd Gate Hotel', city: 'Riyadh', rep: 'Noura', pms: 'Opera PMS', note: '' }, ar: { first: 'ريم', company: 'فندق بوابة نجد', city: 'الرياض', rep: 'نورة', pms: 'نظام Opera PMS', note: '' } }, r: { existing: 'no', props: '1', pms: 'mandated' } },
    D: { f: { en: { first: 'Abdulaziz', company: 'Sahil Hospitality Group', city: 'Jeddah', rep: 'Noura', note: '' }, ar: { first: 'عبدالعزيز', company: 'مجموعة ساحل للضيافة', city: 'جدة', rep: 'نورة', note: '' } }, count: '21-50', r: { existing: 'no', props: '10+', pms: 'unknown' } }
  };

  /* ---------- interface strings used by the logic ---------- */
  const T = {
    en: {
      ready: 'Ready', missing: 'Missing {n}', tooLong: 'Over the limit', chars: '{n} / {max} characters', words: '{n} words', soft: 'over the recommended {n}',
      copy: 'Copy message', copyNote: 'Copy note', copySubject: 'Copy subject', copyLink: 'Copy link', copyText: 'Copy', edit: 'Edit', done: 'Done', reset: 'Reset',
      copied: '{x} copied', copiedGate: '{x} copied. This account is not cleared to send yet.', copyFail: 'Copy failed. Select the text and copy it manually.', subject: 'Subject', link: 'Tracked link', noLink: 'No link in this touch',
      editedHint: 'Edited by hand: account changes no longer update this message. Reset to bring them back.',
      whenNote: 'Send with the connection request', navNote: 'With the invitation', whenFirst: '{date} · when they accept', whenAfter: '{date} · working day {n} after the first message',
      beat: 'Storyboard {t}', beatT: ['0–3 s', '3–10 s', '10–20 s', '20–25 s'],
      invite: 'Invitation with note', accepted: '{date} · after {first} accepts', on: '{date}',
      inboxState: '{n} of 5 touches in the conversation', inboxDone: 'Conversation complete: 5 touches, {a} to {b}.', inboxGate: 'Not cleared to send: {why}',
      sMain: '<b>{code} · {camp}</b> · {lang} · {r} of 5 ready', sNext: 'First message {date}', cleared: 'Cleared to send', notCleared: 'Not cleared: {why}',
      why: { conflict: 'routed to {r}, not {c}', research: 'research queue', ver: 'contact not verified', perm: 'permission not recorded', clean: 'suppression not checked', extra: 'campaign check open' },
      verdict: { A: 'Routes to <b>A · Existing customers</b>. Existing customers always take A.', B: 'Routes to <b>B · New independent properties</b>.', C: 'Routes to <b>C · Opera and brand PMS</b>: confirmed mandated PMS.', D: 'Routes to <b>D · Local chains</b>: about 10 or more properties.', R: '<b>Research queue.</b> Confirm the installed PMS, role or ownership before any campaign.' },
      conflict: 'This account routes to <b>{r}</b>. You are viewing <b>{c}</b>: sending it would be a conflicting offer.', useRouted: 'Switch to {r}',
      weekend: '{day} is a weekend day in Saudi Arabia, so the first message moves to {date}.', renewalPast: 'This renewal date has passed. Check the account.',
      noteDefault: 'Empty: Track B uses a default line. A line only you could write reads better.',
      lang: L2('English', 'Arabic'), them: 'Contact', role: { primary: 'LinkedIn primary', support: 'LinkedIn supporting' },
      brief: { buyer: 'Buyer', concern: 'Concern', action: 'Desired action', beats: 'Storyboard: the sequence tells these four beats' },
      crm: { head: 'Fandaqah 360 · LinkedIn record', camp: 'Primary campaign', account: 'Account ID', company: 'Property or group', contact: 'Contact', ver: 'Verification', perm: 'Permission', permY: 'recorded, LinkedIn permitted', permN: 'not recorded', owner: 'Sales owner', next: 'Next action', subject: 'Subject (InMail)', today: 'Recorded' },
      tag: { v1: 'Test v1 · control', v2: 'Test v2', opt: 'Playbook option', hold: 'Hold: needs release', off: 'Do not use' },
      ad: { creative: 'Creative', intro: 'Intro text', headline: 'Headline', button: 'Button', dest: 'Destination link', download: 'Download creative', promoted: 'Promoted', seeMore: '…see more', card: 'Card {n}', post: 'Post {n}', copyPost: 'Copy post', file: 'File' },
      qualify: 'Qualify before you book', scoreQ: 'Qualified enquiry', persona: { GM: 'GM / Owner', IT: 'IT', FIN: 'Finance' }
    },
    ar: {
      ready: 'جاهزة', missing: 'ناقص {n}', tooLong: 'أطول من الحد', chars: 'الأحرف: {n} / {max}', words: 'الكلمات: {n}', soft: 'أطول من المُوصى به {n}',
      copy: 'نسخ الرسالة', copyNote: 'نسخ الملاحظة', copySubject: 'نسخ العنوان', copyLink: 'نسخ الرابط', copyText: 'نسخ', edit: 'تعديل', done: 'تم', reset: 'استعادة',
      copied: 'تم نسخ {x}', copiedGate: 'تم نسخ {x}. هذا الحساب غير جاهز للإرسال بعد.', copyFail: 'تعذّر النسخ. حدّد النص وانسخه يدوياً.', subject: 'العنوان', link: 'رابط متتبَّع', noLink: 'لا رابط في هذه الرسالة',
      editedHint: 'عُدّلت يدوياً: لن تُحدِّث بيانات الحساب هذه الرسالة بعد الآن. استعد النص لإرجاعها.',
      whenNote: 'تُرسل مع طلب التواصل', navNote: 'مع الدعوة', whenFirst: '{date} · عند قبول الدعوة', whenAfter: '{date} · يوم العمل {n} بعد الرسالة الأولى',
      beat: 'القصة المصوّرة {t}', beatT: ['0–3 ث', '3–10 ث', '10–20 ث', '20–25 ث'],
      invite: 'دعوة مع ملاحظة', accepted: '{date} · بعد قبول {first}', on: '{date}',
      inboxState: '{n} من 5 رسائل في المحادثة', inboxDone: 'اكتملت المحادثة: 5 رسائل من {a} إلى {b}.', inboxGate: 'غير جاهز للإرسال: {why}',
      sMain: '<b>{code} · {camp}</b> · {lang} · {r} من 5 جاهزة', sNext: 'أول رسالة {date}', cleared: 'جاهز للإرسال', notCleared: 'غير جاهز: {why}',
      why: { conflict: 'موجّه إلى {r} لا {c}', research: 'قائمة البحث', ver: 'جهة الاتصال غير موثَّقة', perm: 'الإذن غير مسجَّل', clean: 'الاستبعاد غير مُتحقَّق منه', extra: 'تحقق الحملة غير مكتمل' },
      verdict: { A: 'يتجه إلى <b>A · العملاء الحاليون</b>. العميل الحالي يأخذ A دائماً.', B: 'يتجه إلى <b>B · المنشآت المستقلة الجديدة</b>.', C: 'يتجه إلى <b>C · أنظمة Opera</b>: نظام مفروض مؤكَّد.', D: 'يتجه إلى <b>D · السلاسل المحلية</b>: نحو 10 منشآت أو أكثر.', R: '<b>قائمة البحث.</b> أكّد نظام PMS أو الدور أو الملكية قبل أي حملة.' },
      conflict: 'هذا الحساب يتجه إلى <b>{r}</b> وأنت تعرض <b>{c}</b>: إرساله عرضٌ متعارض.', useRouted: 'انتقل إلى {r}',
      weekend: '{day} عطلة نهاية الأسبوع في المملكة، لذا تنتقل أول رسالة إلى {date}.', renewalPast: 'تاريخ التجديد هذا قد مضى، فتحقّق من الحساب.',
      noteDefault: 'فارغ: يستخدم المسار B سطراً افتراضياً، والسطر الذي لا يكتبه غيرك أفضل.',
      lang: L2('الإنجليزية', 'العربية'), them: 'جهة الاتصال', role: { primary: 'لينكدإن رئيسي', support: 'لينكدإن داعم' },
      brief: { buyer: 'المشتري', concern: 'الاهتمام', action: 'الإجراء المطلوب', beats: 'القصة المصوّرة: يروي التسلسل هذه المشاهد الأربعة' },
      crm: { head: 'فندقة 360 · سجل لينكدإن', camp: 'الحملة الرئيسية', account: 'رقم الحساب', company: 'المنشأة أو المجموعة', contact: 'جهة الاتصال', ver: 'التحقق', perm: 'الإذن', permY: 'مسجَّل ولينكدإن مسموح', permN: 'غير مسجَّل', owner: 'مسؤول المبيعات', next: 'الإجراء التالي', subject: 'العنوان (InMail)', today: 'تاريخ التسجيل' },
      tag: { v1: 'اختبار v1 · أساسي', v2: 'اختبار v2', opt: 'خيار في الدليل', hold: 'معلّق: يحتاج اعتماداً', off: 'لا تستخدم' },
      ad: { creative: 'التصميم', intro: 'النص التمهيدي', headline: 'العنوان', button: 'الزر', dest: 'رابط الوجهة', download: 'تنزيل التصميم', promoted: 'مُروَّج', seeMore: '…عرض المزيد', card: 'البطاقة {n}', post: 'المنشور {n}', copyPost: 'نسخ المنشور', file: 'الملف' },
      qualify: 'أسئلة التأهيل قبل الحجز', scoreQ: 'الاستفسار المؤهل', persona: { GM: 'المدير العام / المالك', IT: 'التقنية', FIN: 'المالية' }
    }
  };
  const VER = { verified: L2('Verified identity and business route', 'هوية موثَّقة ومسار عمل مؤكَّد'), shared: L2('Shared hotel route only', 'رقم الفندق العام فقط'), unverified: L2('Unverified', 'غير موثَّق'), invalid: L2('Invalid', 'غير صالح') };

  /* ---------- state ---------- */
  const blankF = () => ({ first: '', company: '', city: '', rep: '', note: '', date: '', account: '', plan: '', renewal: '', modules: [], pms: '', count: '' });
  const S = { track: 'C', lang: 'ar', persona: 'GM', f: blankF(), r: { existing: 'no', props: '1', pms: 'unknown' }, g: { ver: 'unverified', perm: false, clean: false, extra: false },
    rel: { on: false, annual: '3,495', vat: 'plus' }, edits: {}, editing: null, passed: new Set(['note']), demo: false, adv: { sc: 'v1', sd: 'v1' } };
  const t = (k, vars = {}) => String(T[S.lang][k]).replace(/\{(\w+)\}/g, (m, v) => (v in vars ? vars[v] : m));
  const steps = () => STEPS[S.track];
  const key = id => `${S.track}:${S.persona}:${S.lang}:${id}`;
  const sep = () => (S.lang === 'ar' ? '، ' : ', ');
  const route = () => S.r.existing === 'yes' ? 'A' : S.r.props === '10+' ? 'D' : S.r.pms === 'mandated' ? 'C' : S.r.pms === 'other' ? 'B' : 'R';
  const gateReasons = () => {
    const w = T[S.lang].why, out = [], r = route();
    if (r === 'R') out.push(w.research); else if (r !== S.track) out.push(w.conflict.replace('{r}', r).replace('{c}', S.track));
    if (S.g.ver !== 'verified') out.push(w.ver);
    if (!S.g.perm) out.push(w.perm);
    if (!S.g.clean) out.push(w.clean);
    if (!S.g.extra) out.push(w.extra);
    return out;
  };
  const cleared = () => gateReasons().length === 0;

  /* ---------- dates: the Saudi working week runs Sunday to Thursday ---------- */
  const pad = n => String(n).padStart(2, '0');
  const iso = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
  const parse = v => { const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(v || ''); return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null; };
  const today = () => new Date(new Date().setHours(0, 0, 0, 0));
  const weekend = d => d.getDay() === 5 || d.getDay() === 6;
  const nextWork = d => { const x = new Date(d); while (weekend(x)) x.setDate(x.getDate() + 1); return x; };
  const addWork = (d, n) => { const x = new Date(d); let k = 0; while (k < n) { x.setDate(x.getDate() + 1); if (!weekend(x)) k++; } return x; };
  const loc = () => (S.lang === 'ar' ? 'ar' : 'en-GB');
  const fmtDate = (d, long) => new Intl.DateTimeFormat(loc(), { weekday: long ? 'long' : 'short', day: 'numeric', month: 'short', calendar: 'gregory', numberingSystem: 'latn' }).format(d);
  const fmtDay = d => new Intl.DateTimeFormat(loc(), { day: 'numeric', month: 'long', calendar: 'gregory', numberingSystem: 'latn' }).format(d);
  const start = () => nextWork(parse(S.f.date) || new Date());
  const stepDate = st => (st.offset == null ? null : addWork(start(), st.offset));

  /* ---------- merge: one tokenizer feeds both the highlighted view and the plain text ---------- */
  const joinList = items => (items.length < 2 ? items.join('') : S.lang === 'ar' ? items.join(' و') : `${items.slice(0, -1).join(', ')} and ${items[items.length - 1]}`);
  const trackLink = c => {
    const q = new URLSearchParams({ utm_campaign: CAMP[S.track].campaign, utm_source: 'linkedin', utm_medium: c.medium || 'direct_outreach', utm_content: c.id });
    if (S.lang === 'ar') q.set('lang', 'ar');
    return `${BASE}?${q}`;
  };
  const seqLink = id => trackLink({ id: `li_${id}_${S.lang}` });
  const val = k => {
    if (k === 'modules') return joinList(S.f.modules.map(m => { const x = MODULES.find(y => y[0] === m); return x ? (S.lang === 'ar' ? x[3] : x[1]) : ''; }).filter(Boolean));
    if (k === 'renewal') { const d = parse(S.f.renewal); return d ? fmtDay(d) : ''; }
    if (k === 'count') return S.f.count && COUNT[S.f.count] ? COUNT[S.f.count][S.lang] : '';
    return String(S.f[k] || '').trim();
  };
  function tokens(str, ctx) {
    const out = [];
    str.split(/(\{\w+\})/).forEach(part => {
      const m = /^\{(\w+)\}$/.exec(part);
      if (!m) { if (part) out.push({ t: part }); return; }
      const k = m[1], L = S.lang;
      if (['first', 'company', 'city', 'rep', 'plan', 'renewal', 'modules', 'pms', 'count'].includes(k)) {
        const v = val(k);
        if (v) out.push({ v: k, val: v }); else { out.push({ miss: k, val: `[${fieldLabel(k)}]` }); ctx.missing.add(k); }
      }
      else if (k === 'note') { const v = S.f.note.trim(), fb = NOTE_FALLBACK[S.track]; out.push(...(v ? [{ v: 'note', val: v }] : fb ? tokens(fb[L], ctx) : [])); }
      else if (k === 'link') { const u = ctx.url || seqLink(ctx.id); out.push({ link: u }); ctx.link = u; }
      else if (k === 'session') out.push({ t: CAMP[S.track].session[L] });
      else if (k === 'annual' || k === 'total') out.push({ v: k, val: String(S.rel[k] || '').trim() });
      else if (k === 'vat') out.push({ t: S.rel.vat === 'incl' ? (L === 'ar' ? '، شاملة ضريبة القيمة المضافة' : ', including VAT') : (L === 'ar' ? '، غير شاملة ضريبة القيمة المضافة' : ', plus VAT') });
      else out.push({ t: part });
    });
    return out;
  }
  const visible = b => !(b.if === 'release' && !S.rel.on) && !(b.unless === 'release' && S.rel.on);
  const lineText = tk => tk.map(x => x.val != null ? x.val : x.link != null ? x.link : x.t).join('').replace(/[ \t]+$/gm, '').replace(/ {2,}/g, ' ').trim();
  const fill = (str, ctx) => lineText(tokens(str, ctx || { missing: new Set() }));
  function blocksText(blocks, ctx) {
    const parts = [];
    blocks.filter(visible).forEach(b => {
      if (b.p != null) { const s = lineText(tokens(b.p, ctx)); if (s) parts.push(s); }
      else if (b.ul) parts.push(b.ul.map(i => '• ' + lineText(tokens(i, ctx))).join('\n'));
      else if (b.ol) parts.push(b.ol.map((i, n) => `${n + 1}. ` + lineText(tokens(i, ctx))).join('\n'));
      else if (b.sign) parts.push(lineText(tokens('{rep}', ctx)) + '\n' + BRAND[S.lang]);
    });
    return parts.join('\n\n');
  }
  function tokensDom(tk, el) {
    tk.forEach(x => {
      if (x.miss) { const s = document.createElement('span'); s.className = 'v miss'; s.textContent = x.val; el.append(s); }
      else if (x.v) { if (!x.val) return; const s = document.createElement('bdi'); s.className = 'v'; s.textContent = x.val; el.append(s); }
      else if (x.link) { const a = document.createElement('a'); a.href = x.link; a.target = '_blank'; a.rel = 'noopener'; a.dir = 'ltr'; a.textContent = x.link; el.append(a); }
      else String(x.t).split('\n').forEach((seg, i) => { if (i) el.append(document.createElement('br')); el.append(seg); });
    });
  }
  const trimDom = el => { const last = el.lastChild; if (last && last.nodeType === 3) last.textContent = last.textContent.replace(/\s+$/, ''); };
  function blocksDom(blocks, ctx) {
    const f = document.createDocumentFragment();
    blocks.filter(visible).forEach(b => {
      if (b.p != null) { const tk = tokens(b.p, ctx); if (!lineText(tk)) return; const p = document.createElement('p'); tokensDom(tk, p); trimDom(p); f.append(p); }
      else if (b.ul || b.ol) { const l = document.createElement(b.ul ? 'ul' : 'ol'); (b.ul || b.ol).forEach(i => { const li = document.createElement('li'); tokensDom(tokens(i, ctx), li); l.append(li); }); f.append(l); }
      else if (b.sign) { const p = document.createElement('p'); p.className = 'sign'; tokensDom(tokens('{rep}', ctx), p); p.append(document.createElement('br'), BRAND[S.lang]); f.append(p); }
    });
    return f;
  }
  function plainDom(text) {
    const f = document.createDocumentFragment();
    text.split(/\n{2,}/).forEach(par => {
      if (!par.trim()) return;
      const p = document.createElement('p');
      par.split('\n').forEach((line, i) => {
        if (i) p.append(document.createElement('br'));
        line.split(/(https?:\/\/\S+|\[[^\]\n]{2,40}\])/).forEach(seg => {
          if (/^https?:\/\//.test(seg)) { const a = document.createElement('a'); a.href = seg; a.target = '_blank'; a.rel = 'noopener'; a.dir = 'ltr'; a.textContent = seg; p.append(a); }
          else if (/^\[[^\]]+\]$/.test(seg)) { const s = document.createElement('span'); s.className = 'miss'; s.textContent = seg; p.append(s); }
          else if (seg) p.append(seg);
        });
      });
      f.append(p);
    });
    return f;
  }
  const pick = obj => (obj && (obj.GM || obj.IT || obj.FIN) ? obj[S.persona] : obj);
  const stepBlocks = st => (st.GM || st.IT ? st[S.persona] : st)[S.lang];
  const stepSubject = st => { if (!st.subject) return ''; const s = S.rel.on && st.subjectRel ? st.subjectRel : pick(st.subject); return s[S.lang]; };

  function model(st) {
    const ctx = { id: st.id, missing: new Set(), link: null };
    const blocks = stepBlocks(st);
    const tplText = blocksText(blocks, ctx);
    const subject = st.subject ? lineText(tokens(stepSubject(st), ctx)) : '';
    const edited = S.edits[key(st.id)];
    const text = edited != null ? edited : tplText;
    const len = [...text].length;
    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
    const editMissing = edited != null && /\[[^\]\n]{2,40}\]/.test(edited);
    const missing = edited != null ? (editMissing ? ctx.missing : new Set()) : ctx.missing;
    const ready = missing.size === 0 && !editMissing && len <= st.limit;
    return { st, blocks, text, tplText, subject, edited: edited != null, len, words, missing, ready, over: len > st.limit, date: stepDate(st) };
  }

  /* ---------- the interface ---------- */
  const seqEl = $('[data-seq]'), navSteps = $('[data-nav-steps]'), thread = $('[data-thread]'), inbox = $('#inbox'), form = $('[data-form]'), toastEl = $('[data-toast]');
  const textNodes = $$('[data-i18n]'), ariaNodes = $$('[data-i18n-aria]');
  const EN = {}, EN_ARIA = new Map();
  textNodes.forEach(n => { if (!(n.dataset.i18n in EN)) EN[n.dataset.i18n] = n.innerHTML; });
  ariaNodes.forEach(n => EN_ARIA.set(n, n.getAttribute('aria-label')));
  const ic = id => `<svg class="ic" aria-hidden="true"><use href="#${id}"/></svg>`;
  const copyBtn = (act, label, extra = '') => `<button class="btn-icon" type="button" data-act="${act}" ${extra} aria-label="${esc(label)}" title="${esc(label)}">${ic('i-copy')}</button>`;
  const setVal = (el, v) => { if (el && el.value !== v) el.value = v; };

  function applyChrome() {
    const ar = S.lang === 'ar', C = CAMP[S.track];
    root.lang = S.lang; root.dir = ar ? 'rtl' : 'ltr';
    root.classList.remove('i18n-wait');
    textNodes.forEach(n => { const v = ar ? AR[n.dataset.i18n] : EN[n.dataset.i18n]; if (v != null) n.innerHTML = v; });
    ariaNodes.forEach(n => n.setAttribute('aria-label', ar ? (AR[n.dataset.i18nAria] || EN_ARIA.get(n)) : EN_ARIA.get(n)));
    $$('[data-lang]').forEach(b => b.setAttribute('aria-pressed', String(b.dataset.lang === S.lang)));
    $$('[data-camp]').forEach(b => { const k = b.dataset.camp; b.setAttribute('aria-pressed', String(k === S.track)); b.title = `${k} · ${CAMP[k].name[S.lang]} · ${T[S.lang].role[CAMP[k].primary ? 'primary' : 'support']}`; });
    Object.entries(LISTS).forEach(([id, l]) => { $('#' + id).innerHTML = l[S.lang].map(c => `<option value="${esc(c)}">`).join(''); });
    $$('[data-only]').forEach(el => { el.hidden = !el.dataset.only.includes(S.track); });
    form.dataset.c = S.track;
    $('[data-company-label]').textContent = C.company[S.lang];
    $('[data-gate-extra]').textContent = C.extra[S.lang];
    $('[data-modules]').innerHTML = MODULES.map(m => `<button type="button" data-mod="${m[0]}" aria-pressed="false">${esc(S.lang === 'ar' ? m[3] : m[2])}</button>`).join('');
    renderBrief();
  }

  function renderBrief() {
    const C = CAMP[S.track], L = S.lang, b = T[L].brief;
    $('[data-brief]').innerHTML = `
      <div class="brief-top"><span class="brief-code" aria-hidden="true">${S.track}</span><h1 id="brief-h">${esc(C.name[L])}</h1><span class="role ${C.primary ? 'is-primary' : ''}">${esc(T[L].role[C.primary ? 'primary' : 'support'])}</span></div>
      <dl class="brief-grid">
        <div><dt>${esc(b.buyer)}</dt><dd>${esc(C.buyer[L])}</dd></div>
        <div><dt>${esc(b.concern)}</dt><dd>${esc(C.concern[L])}</dd></div>
        <div><dt>${esc(b.action)}</dt><dd>${C.action[L].toLowerCase() === C.cta[L].toLowerCase() || L === 'ar' && C.action[L].replace('طلب', 'اطلب') === C.cta[L] ? '' : esc(C.action[L]) + '<br>'}<span class="cta-v">${ic('i-arrow-right')}${esc(C.cta[L])}</span></dd></div>
      </dl>
      <p class="brief-li">${esc(C.li[L])}</p>
      <p class="beats-k">${esc(b.beats)}</p>
      <ol class="beats">${C.beats.map((x, i) => `<li class="beat"><b>${esc(T[L].beatT[i])}</b>${esc(x[L])}</li>`).join('')}</ol>`;
  }

  function buildSequence() {
    S.editing = null;
    seqEl.innerHTML = ''; navSteps.innerHTML = '';
    const dir = S.lang === 'ar' ? 'rtl' : 'ltr';
    steps().forEach((st, i) => {
      const sec = document.createElement('section');
      sec.className = 'step'; sec.id = 's-' + st.id; sec.dataset.step = st.id; sec.setAttribute('aria-labelledby', 'h-' + st.id);
      const beat = st.beat != null ? `<p class="step-beat"><span class="bk">${esc(t('beat', { t: T[S.lang].beatT[st.beat] }))}</span><span>“${esc(CAMP[S.track].beats[st.beat][S.lang])}”</span></p>` : '';
      sec.innerHTML = `
        <div class="step-node num" aria-hidden="true">${i + 1}</div>
        <header class="step-head">
          <div class="step-title"><h3 id="h-${st.id}">${esc(STEP_LABEL[st.id][S.lang])}</h3><p class="step-when">${ic('i-clock')}<span data-when></span></p>${beat}</div>
          <span class="badge" data-badge></span>
        </header>
        ${st.subject ? `<div class="subj"><span class="subj-k">${esc(t('subject'))}</span><p class="subj-v" data-subject></p>${copyBtn('subject', t('copySubject'))}</div>` : ''}
        <div class="paper" data-paper lang="${S.lang}" dir="${dir}"></div>
        <div class="step-foot">
          <div class="meter" data-meter><span class="meter-bar" aria-hidden="true"><i></i></span><span class="num" data-meter-txt></span></div>
          <div class="link-chip" data-linkchip></div>
          <div class="step-actions">
            <button class="btn btn-ghost" type="button" data-act="reset" hidden>${ic('i-rotate-ccw')}<span>${esc(t('reset'))}</span></button>
            <button class="btn btn-ghost" type="button" data-act="edit">${ic('i-pencil-line')}<span>${esc(t('edit'))}</span></button>
            <button class="btn btn-primary" type="button" data-act="copy">${ic('i-copy')}<span>${esc(t(st.id === 'note' ? 'copyNote' : 'copy'))}</span></button>
          </div>
        </div>
        <p class="edited-hint" data-edited-hint hidden>${esc(t('editedHint'))}</p>
        <p class="why">${ic('i-route')}<span>${esc(st.why[S.lang])}</span></p>`;
      seqEl.append(sec);
      const li = document.createElement('li');
      li.innerHTML = `<a href="#s-${st.id}" data-spy="s-${st.id}"><span class="dot num">${i + 1}</span><span class="nav-l">${esc(STEP_LABEL[st.id][S.lang])}</span><span class="nav-d num" data-nav-date></span></a>`;
      navSteps.append(li);
    });
    observe();
  }

  const whenText = m => !m.date ? t('whenNote') : m.st.offset === 0 ? t('whenFirst', { date: fmtDate(m.date) }) : t('whenAfter', { date: fmtDate(m.date), n: m.st.offset });

  function update() {
    const models = steps().map(model);
    models.forEach((m, i) => {
      const sec = seqEl.children[i]; if (!sec) return;
      sec.classList.toggle('is-ready', m.ready);
      $('[data-when]', sec).textContent = whenText(m);
      const badge = $('[data-badge]', sec);
      badge.className = 'badge ' + (m.ready ? 'is-ready' : m.over ? 'is-long' : 'is-missing');
      badge.innerHTML = m.ready ? `${ic('i-check')}${esc(t('ready'))}` : m.over ? `${ic('i-circle-alert')}${esc(t('tooLong'))}`
        : `${ic('i-circle-alert')}${esc(t('missing', { n: [...m.missing].map(fieldLabel).join(sep()) }))}`;
      const subj = $('[data-subject]', sec);
      if (subj) { subj.textContent = ''; tokensDom(tokens(stepSubject(m.st), { id: m.st.id, missing: new Set() }), subj); }
      if (S.editing !== m.st.id) { const paper = $('[data-paper]', sec); paper.textContent = ''; paper.append(m.edited ? plainDom(m.text) : blocksDom(m.blocks, { id: m.st.id, missing: new Set() })); }
      const meter = $('[data-meter]', sec);
      meter.classList.toggle('is-over', m.over);
      meter.querySelector('i').style.setProperty('--p', Math.min(1, m.len / m.st.limit).toFixed(3));
      $('[data-meter-txt]', sec).textContent = `${t('chars', { n: m.len.toLocaleString('en'), max: m.st.limit.toLocaleString('en') })} · ${t('words', { n: m.words })}`;
      $('[data-linkchip]', sec).innerHTML = m.st.link
        ? `${ic('i-link')}<span>${esc(t('link'))}</span><code dir="ltr" title="${esc(seqLink(m.st.id))}">li_${m.st.id}_${S.lang}</code>${copyBtn('link', t('copyLink'))}`
        : `${ic('i-link')}<span>${esc(t('noLink'))}</span>`;
      $('[data-act="reset"]', sec).hidden = !m.edited;
      $('[data-edited-hint]', sec).hidden = !m.edited;
      const a = navSteps.children[i] && navSteps.children[i].firstElementChild;
      if (a) { a.classList.toggle('is-ready', m.ready); a.classList.toggle('is-missing', !m.ready); $('[data-nav-date]', a).textContent = m.date ? fmtDate(m.date) : t('navNote'); }
    });
    renderAccount(models); renderThread(models); renderStatus(models); renderReplies(); renderHeadlines();
    save();
    return models;
  }

  function renderAccount(models) {
    const need = new Set(); models.forEach(m => m.missing.forEach(k => need.add(k)));
    ['first', 'company', 'city', 'rep', 'plan', 'renewal', 'pms', 'count', 'modules'].forEach(k => { const el = $(`[data-f="${k}"]`); if (el) el.classList.toggle('is-empty', need.has(k)); });
    $('[data-note-count]').textContent = `${[...S.f.note].length}/160`;
    $('[data-note-hint]').textContent = !S.f.note.trim() && S.track === 'B' ? t('noteDefault') : (S.lang === 'ar' ? AR['f.noteHint'] : EN['f.noteHint']);
    const raw = parse(S.f.date), w = $('[data-weekend]');
    w.hidden = !(raw && weekend(raw));
    if (!w.hidden) w.textContent = t('weekend', { day: fmtDate(raw, true), date: fmtDate(start(), true) });
    const rn = parse(S.f.renewal), rw = $('[data-renewal-warn]');
    rw.hidden = !(rn && rn < today());
    if (!rw.hidden) rw.textContent = t('renewalPast');
    $$('[data-mod]').forEach(b => { const on = S.f.modules.includes(b.dataset.mod); b.setAttribute('aria-pressed', String(on)); b.disabled = !on && S.f.modules.length >= 3; });
    $$('[data-persona]').forEach(b => b.setAttribute('aria-pressed', String(b.dataset.persona === S.persona)));
    $$('[data-q]').forEach(g => $$('button', g).forEach(b => b.setAttribute('aria-pressed', String(S.r[g.dataset.q] === b.dataset.v))));
    // routing verdict: the playbook's rule, and a flag when the open campaign is not the routed one
    const r = route(), v = $('[data-verdict]'), L = S.lang;
    $$('[data-camp]').forEach(b => b.classList.toggle('is-routed', b.dataset.camp === r));
    if (r !== 'R' && r !== S.track) {
      v.className = 'verdict is-conflict';
      v.innerHTML = `<span class="vcode">${r}</span><div>${t('conflict', { r: `${r} · ${esc(CAMP[r].short[L])}`, c: `${S.track} · ${esc(CAMP[S.track].short[L])}` })}<br><button class="btn btn-ghost btn-sm" type="button" data-use-routed>${esc(t('useRouted', { r }))}</button></div>`;
    } else {
      v.className = 'verdict' + (r === 'R' ? ' is-research' : '');
      v.innerHTML = `<span class="vcode">${r === 'R' ? '?' : r}</span><div>${T[L].verdict[r]}</div>`;
    }
    // eligibility
    setVal($('[data-g="ver"]'), S.g.ver);
    ['perm', 'clean', 'extra'].forEach(k => { $(`[data-g="${k}"]`).checked = S.g[k]; });
    const why = gateReasons(), cs = $('[data-clear-state]');
    cs.className = 'clear ' + (why.length ? 'is-no' : 'is-ok');
    cs.innerHTML = why.length ? `${ic('i-circle-alert')}<span>${esc(t('notCleared', { why: why.join(sep()) }))}</span>` : `${ic('i-circle-check')}<span>${esc(t('cleared'))}</span>`;
    // release record
    $('[data-rel-switch]').setAttribute('aria-checked', String(S.rel.on));
    $('[data-rel-fields]').hidden = !S.rel.on;
    setVal($('#r-annual'), S.rel.annual); setVal($('#r-total'), S.rel.total); setVal($('#r-vat'), S.rel.vat);
    $('[data-demo-chip]').hidden = !S.demo;
  }

  /* ---------- signature: the conversation builds itself as the sequence is read ---------- */
  const shown = new Set();
  const initials = s => (s.trim().split(/\s+/).map(w => [...w][0] || '').join('').slice(0, 2) || '?').toUpperCase();
  function renderThread(models) {
    const first = S.f.first.trim(), them = first || t('them');
    $('[data-av-them]').textContent = initials(first || '?');
    $('[data-them-name]').textContent = them;
    $('[data-them-sub]').textContent = [S.f.company.trim(), S.track === 'C' ? T[S.lang].persona[S.persona] : S.f.city.trim()].filter(Boolean).join(' · ');
    thread.textContent = '';
    thread.setAttribute('dir', S.lang === 'ar' ? 'rtl' : 'ltr');
    let count = 0, lastDate = null;
    models.forEach(m => {
      if (!S.passed.has(m.st.id)) return;
      count++;
      const isNew = !shown.has(m.st.id) && !reduce.matches;
      shown.add(m.st.id);
      let node;
      if (m.st.id === 'note') {
        node = document.createElement('div'); node.className = 'invite';
        node.innerHTML = `<p class="invite-k">${ic('i-user-plus')}${esc(t('invite'))}</p>`;
        node.append(plainDom(m.text));
      } else {
        const sys = document.createElement('p'); sys.className = 'sys';
        sys.innerHTML = `<span>${esc(m.st.offset === 0 ? t('accepted', { date: fmtDate(m.date), first: them }) : t('on', { date: fmtDate(m.date, true) }))}</span>`;
        thread.append(sys);
        node = document.createElement('div'); node.className = 'msg';
        node.innerHTML = `<span class="av av-me" aria-hidden="true">${esc(initials(S.f.rep.trim() || 'F'))}</span>`;
        const b = document.createElement('div'); b.className = 'bubble'; b.append(plainDom(m.text)); node.append(b);
        lastDate = m.date;
      }
      if (isNew) node.classList.add('is-new');
      thread.append(node);
    });
    if (count) requestAnimationFrame(() => { thread.scrollTop = thread.scrollHeight; });
    $$('[data-prog] i').forEach((i, n) => i.classList.toggle('on', n < count));
    $('[data-inbox-count]').textContent = count;
    $('[data-inbox-state]').textContent = t('inboxState', { n: count });
    const why = gateReasons(), gate = $('[data-inbox-gate]');
    gate.hidden = !why.length; gate.textContent = why.length ? t('inboxGate', { why: why.join(sep()) }) : '';
    const complete = count === models.length;
    inbox.classList.toggle('is-complete', complete);
    if (complete) $('[data-inbox-done]').textContent = t('inboxDone', { a: fmtDate(models.find(m => m.date).date), b: fmtDate(lastDate) });
  }

  function renderStatus(models) {
    const r = models.filter(m => m.ready).length, ok = cleared(), why = gateReasons();
    $('[data-status]').classList.toggle('is-ok', ok && r === models.length);
    $('[data-s-main]').innerHTML = t('sMain', { code: S.track, camp: esc(CAMP[S.track].short[S.lang]), lang: esc(T[S.lang].lang[S.lang]), r });
    $('[data-s-gate]').textContent = ok ? t('cleared') : t('notCleared', { why: why.join(sep()) });
    $('[data-s-next]').textContent = t('sNext', { date: fmtDate(start(), true) });
  }

  /* ---------- ads and posts: rendered on campaign, language or variant change only ----------
     Template-built src/href values are single-quoted so tools/check-links.py does not read them as local files. */
  const meterHtml = (n, max, soft) => {
    const over = n > max, isSoft = !over && soft && n > soft;
    return `<div class="meter ${over ? 'is-over' : isSoft ? 'is-soft' : ''}"><span class="meter-bar" aria-hidden="true"><i style="--p:${Math.min(1, n / max).toFixed(3)}"></i>${soft ? `<b style="--r:${(soft / max).toFixed(3)}"></b>` : ''}</span><span class="num">${esc(t('chars', { n: n.toLocaleString('en'), max: max.toLocaleString('en') }))}${isSoft ? ' · ' + esc(t('soft', { n: soft })) : ''}</span></div>`;
  };
  const fld = (label, text, act, max, soft, attrs = '') => `<div class="fld"><div class="fld-h"><b>${esc(label)}</b>${copyBtn(act, label, attrs)}</div><p>${esc(text)}</p>${max ? meterHtml([...text].length, max, soft) : ''}</div>`;
  const linkFld = (label, url) => `<div class="fld"><div class="fld-h"><b>${esc(label)}</b>${copyBtn('ad-link', label, `data-url="${esc(url)}"`)}</div><p class="mono" dir="ltr">${esc(url)}</p></div>`;
  function renderAds() {
    const box = $('[data-ads]'); if (!ADS[S.track]) { box.innerHTML = ''; return; }
    const L = S.lang, A = T[L].ad;
    box.innerHTML = ADS[S.track].map(ad => {
      if (ad.type === 'sponsored') {
        const v = S.adv[ad.id], src = ad.img.replace('{v}', v).replace('{lang}', L), url = trackLink({ id: ad.content.replace('{v}', v).replace('{lang}', L), medium: 'paid_social' });
        const intro = ad.intro[L], cut = [...intro].length > LIMITS.introSoft ? [...intro].slice(0, LIMITS.introSoft).join('').replace(/\s+\S*$/, '') : null;
        return `<article class="ad" data-ad="${ad.id}">
          <div class="ad-k"><h3>${esc(ad.title[L])}</h3><p>${esc(ad.note[L])}</p></div>
          <div><div class="feed" lang="${L}"><div class="feed-h"><img class="page-av" src='assets/img/icon-192.png' width="34" height="34" alt=""><div><strong>Fandaqah</strong><span>${esc(A.promoted)}</span></div></div>
            <p class="feed-t">${cut ? `${esc(cut)} <button type="button" data-see>${esc(A.seeMore)}</button>` : esc(intro)}</p>
            <img src='${esc(src)}' width="1080" height="1350" alt="${esc(ad.alt[v][L])}" loading="lazy" decoding="async">
            <div class="feed-f"><div><strong>${esc(ad.headline[L])}</strong><span>fandaqah.com</span></div><span class="cta">${esc(ad.button[L])}</span></div></div>
            <div class="vtoggle mini-seg" role="group" aria-label="${esc(A.creative)}">${['v1', 'v2'].map(x => `<button type="button" data-adv="${x}" aria-pressed="${x === v}">${x}</button>`).join('')}</div></div>
          <div class="fields">
            ${fld(A.intro, intro, 'ad-intro', LIMITS.intro, LIMITS.introSoft)}
            ${fld(A.headline, ad.headline[L], 'ad-headline', LIMITS.headline, LIMITS.headlineSoft)}
            <div class="fld"><div class="fld-h"><b>${esc(A.button)}</b></div><p>${esc(ad.button[L])}</p></div>
            ${linkFld(A.dest, url)}
            <div class="fld"><div class="fld-h"><b>${esc(A.file)}</b><a class="btn-icon" href='${esc(src)}' download aria-label="${esc(A.download)}" title="${esc(A.download)}">${ic('i-download')}</a></div><p dir="ltr">${esc(src.split('/').pop())} · 1080 × 1350</p></div>
            <p class="hint">${esc(ad.foot[L])}</p>
          </div></article>`;
      }
      if (ad.type === 'carousel') {
        const url = trackLink({ id: ad.content.replace('{lang}', L), medium: 'paid_social' });
        return `<article class="ad is-wide" data-ad="${ad.id}">
          <div class="ad-k"><h3>${esc(ad.title[L])}</h3><p>${esc(ad.note[L])}</p></div>
          <div class="carousel">${ad.cards.map((c, i) => `<figure class="card"><img src='${esc(ad.img.replace("{n}", i + 1).replace("{lang}", L))}' width="1080" height="1350" alt="${esc(c[L])}" loading="lazy" decoding="async"><figcaption>${esc(c[L])}</figcaption></figure>`).join('')}</div>
          <div class="fields fields-grid">
            ${ad.cards.map((c, i) => fld(A.card.replace('{n}', i + 1), c[L], 'ad-card', 0, 0, `data-card="${i}"`)).join('')}
            ${linkFld(A.dest, url)}
          </div></article>`;
      }
      return `<article class="ad is-wide" data-ad="${ad.id}">
        <div class="ad-k"><h3>${esc(ad.title[L])}</h3><p>${esc(ad.note[L])}</p></div>
        <div class="posts">${ad.posts.map((p, i) => {
          const text = fill(p[L], { missing: new Set(), url: trackLink({ id: `post_${i + 1}_${L}`, medium: 'organic_social' }) });
          const first = text.split('\n')[0];
          return `<div class="post" data-post="${i}" lang="${L}"><div class="post-h"><h4>${esc(A.post.replace('{n}', i + 1))} · ${esc(p.t[L])}</h4><button class="btn btn-ghost btn-sm" type="button" data-act="post">${ic('i-copy')}<span>${esc(A.copyPost)}</span></button></div>
            <p class="post-t"><span class="hook">${esc(first)}</span>${esc(text.slice(first.length))}</p>${meterHtml([...text].length, LIMITS.post, 0)}</div>`;
        }).join('')}</div></article>`;
    }).join('');
  }

  /* ---------- replies, qualification, headlines, playbook ---------- */
  const priceSrc = () => ('AB'.includes(S.track) ? (S.rel.on ? { a: PRICE.AB.on, after: PRICE.AB.afterOn } : { a: PRICE.AB.off, after: PRICE.AB.afterOff }) : { a: PRICE.CD.off, after: PRICE.CD.afterOff });
  const replySrc = r => (r.id === 'price' ? priceSrc() : r);
  function renderReplies() {
    const ul = $('[data-replies]'); ul.textContent = '';
    REPLIES.filter(r => r.c.includes(S.track)).forEach(r => {
      const src = replySrc(r);
      const li = document.createElement('li'); li.className = 'reply'; li.dataset.reply = r.id;
      li.innerHTML = `<p class="reply-q">${esc(r.q[S.lang])}</p><p class="reply-a" lang="${S.lang}"></p>${copyBtn('reply', t('copyText'))}<p class="reply-after">${ic('i-arrow-right')}<span>${esc(src.after[S.lang])}</span></p>`;
      tokensDom(tokens(src.a[S.lang], { missing: new Set() }), $('.reply-a', li));
      ul.append(li);
    });
    $('[data-qualify]').innerHTML = `<h3>${ic('i-list-checks')}${esc(T[S.lang].qualify)}</h3><ol>${QUALIFY[S.track][S.lang].map(q => `<li>${esc(q)}</li>`).join('')}</ol>`;
  }
  function renderHeadlines() {
    const tb = $('[data-subjects]'); tb.textContent = '';
    HEADLINES[S.track].forEach((h, i) => {
      const text = h[S.lang], tr = document.createElement('tr'); tr.dataset.h = i;
      const released = h.tag === 'hold' && S.rel.on, tag = released ? 'opt' : h.tag;
      tr.className = h.tag === 'off' ? 'is-off' : h.tag === 'hold' && !released ? 'is-hold' : '';
      const usable = h.tag !== 'off' && !(h.tag === 'hold' && !S.rel.on);
      tr.innerHTML = `<td></td><td class="len num">${[...text].length}</td><td class="tagc"><span class="tg tg-${tag}">${esc(T[S.lang].tag[tag])}</span></td><td class="act">${usable ? copyBtn('headline', t('copySubject')) : ''}</td>`;
      const td = tr.firstElementChild; td.textContent = text;
      if (h.why) { const w = document.createElement('span'); w.className = 'why-off'; w.textContent = h.why[S.lang]; td.append(w); }
      tb.append(tr);
    });
  }
  function renderPlay() {
    const L = S.lang, P = PLAY, el = $('[data-play]');
    const list = items => `<ul>${items.map(i => `<li>${esc(i[L])}</li>`).join('')}</ul>`;
    const camps = `<div class="wide"><h3>${ic('i-users')}${esc(P.camps.t[L])}</h3><table class="tbl"><thead><tr>${P.camps.cols[L].map(c => `<th scope="col">${esc(c)}</th>`).join('')}</tr></thead><tbody>${'ABCD'.split('').map(k => { const C = CAMP[k]; return `<tr class="${k === S.track ? 'is-cur' : ''}"><td>${k} · ${esc(C.name[L])}</td><td>${esc(C.buyer[L])} ${esc(C.concern[L])}</td><td>${esc(C.action[L])}</td><td>${esc(T[L].role[C.primary ? 'primary' : 'support'])}</td></tr>`; }).join('')}</tbody></table><p class="src">${esc(P.camps.src[L])}</p></div>`;
    const groups = P.groups.map(g => `<div><h3>${ic(g.icon)}${esc(g.t[L])}</h3>${list(g.items)}<p class="src">${esc(g.src[L])}</p></div>`).join('');
    const sc = P.scores;
    const score = `<div class="wide"><h3>${ic('i-list-checks')}${esc(sc.t[L])} · ${S.track}</h3><ul>${sc[S.track][L].map(x => `<li>${esc(x)}</li>`).join('')}</ul><p class="src"><b>${esc(T[L].scoreQ)}:</b> ${esc(sc.qual[L])} ${esc(sc.qualX[S.track][L])}</p><p class="src">${esc(sc.sep[L])} · ${esc(sc.src[L])}</p></div>`;
    const claims = `<div class="wide"><h3>${ic('i-badge-check')}${esc(P.claims.t[L])}</h3><table class="tbl"><thead><tr>${P.claims.cols[L].map(c => `<th scope="col">${esc(c)}</th>`).join('')}</tr></thead><tbody>${P.claims.rows.map(r => `<tr><td>${esc(r[0][L])}</td><td>${esc(r[1][L])}</td></tr>`).join('')}</tbody></table><p class="src">${esc(P.claims.src[L])}</p></div>`;
    const changes = `<div class="wide"><h3>${ic('i-pencil-line')}${esc(P.changes.t[L])}</h3><table class="tbl"><thead><tr>${P.changes.cols[L].map(c => `<th scope="col">${esc(c)}</th>`).join('')}</tr></thead><tbody>${P.changes.rows.map(r => `<tr><td>${esc(r[0][L])}</td><td>${esc(r[1][L])}</td><td>${esc(r[2][L])}</td></tr>`).join('')}</tbody></table></div>`;
    el.innerHTML = camps + groups + score + claims + changes;
  }

  /* ---------- copy as plain text (works over http, https and file) ---------- */
  let toastTimer;
  function toast(msg) { toastEl.textContent = msg; toastEl.classList.add('is-on'); clearTimeout(toastTimer); toastTimer = setTimeout(() => toastEl.classList.remove('is-on'), 2200); }
  async function copy(text, label, btn, gated) {
    let ok = false;
    try { await navigator.clipboard.writeText(text); ok = true; } catch (e) {
      const ta = document.createElement('textarea'); ta.value = text; ta.setAttribute('readonly', ''); ta.style.cssText = 'position:fixed;top:-1000px;opacity:0';
      document.body.append(ta); ta.select();
      try { ok = document.execCommand('copy'); } catch (e2) { ok = false; }
      ta.remove();
    }
    toast(!ok ? t('copyFail') : gated && !cleared() ? t('copiedGate', { x: label }) : t('copied', { x: label }));
    if (ok && btn) { btn.classList.add('is-done'); setTimeout(() => btn.classList.remove('is-done'), 1400); }
    return ok;
  }
  function crmRecord() {
    const L = S.lang, c = T[L].crm, f = S.f, models = steps().map(model), out = [];
    const nextStep = models.find(m => m.date && m.date >= today());
    out.push(c.head);
    out.push(`${c.camp}: ${S.track} · ${CAMP[S.track].name[L]} (${CAMP[S.track].campaign})`);
    if (S.track === 'A') out.push(`${c.account}: ${f.account.trim()}`);
    out.push(`${c.company}: ${[f.company, f.city].map(s => s.trim()).filter(Boolean).join(' · ')}`);
    out.push(`${c.contact}: ${f.first.trim()}${S.track === 'C' ? ` (${T[L].persona[S.persona]})` : ''}`);
    out.push(`${c.ver}: ${VER[S.g.ver][L]} · ${c.perm}: ${S.g.perm ? c.permY : c.permN}`);
    out.push(`${c.owner}: ${f.rep.trim()} · ${c.today}: ${iso(new Date())}`);
    if (nextStep) out.push(`${c.next}: ${STEP_LABEL[nextStep.st.id][L]} · ${fmtDate(nextStep.date, true)}`);
    models.forEach((m, i) => {
      out.push('', '────────', `${i + 1}. ${STEP_LABEL[m.st.id][L]} · ${whenText(m)}`);
      if (m.subject) out.push(`${c.subject}: ${m.subject}`);
      out.push('', m.text);
    });
    return out.join('\n');
  }

  /* ---------- sequence actions ---------- */
  seqEl.addEventListener('click', e => {
    const btn = e.target.closest('[data-act]'); if (!btn) return;
    const sec = btn.closest('.step'), st = steps().find(s => s.id === sec.dataset.step), m = model(st), act = btn.dataset.act, label = STEP_LABEL[st.id][S.lang];
    if (act === 'copy') copy(S.editing === st.id ? $('.paper-edit', sec).value : m.text, label, btn, true);
    else if (act === 'subject') copy(m.subject, t('subject'), btn, true);
    else if (act === 'link') copy(seqLink(st.id), t('link'), btn);
    else if (act === 'reset') { delete S.edits[key(st.id)]; if (S.editing === st.id) closeEdit(sec, st, false); update(); }
    else if (act === 'edit') { if (S.editing === st.id) closeEdit(sec, st, true); else openEdit(sec, st, m); }
  });
  function openEdit(sec, st, m) {
    if (S.editing) { const o = $(`#s-${S.editing}`), ost = steps().find(s => s.id === S.editing); if (o && ost) closeEdit(o, ost, true); }
    S.editing = st.id;
    const paper = $('[data-paper]', sec), ta = document.createElement('textarea');
    ta.className = 'paper-edit'; ta.value = m.text; ta.dir = S.lang === 'ar' ? 'rtl' : 'ltr'; ta.lang = S.lang;
    ta.setAttribute('aria-label', STEP_LABEL[st.id][S.lang]);
    paper.hidden = true; paper.after(ta);
    ta.style.height = Math.max(280, paper.offsetHeight || 0) + 'px';
    ta.addEventListener('input', () => { S.edits[key(st.id)] = ta.value; update(); });
    const b = $('[data-act="edit"]', sec); b.querySelector('span').textContent = t('done'); b.querySelector('use').setAttribute('href', '#i-check');
    ta.focus();
  }
  function closeEdit(sec, st, keep) {
    const ta = $('.paper-edit', sec);
    if (ta && keep) { const k = key(st.id), saved = S.edits[k]; delete S.edits[k]; if (ta.value !== model(st).tplText) S.edits[k] = saved != null ? saved : ta.value; }
    if (ta) ta.remove();
    $('[data-paper]', sec).hidden = false;
    const b = $('[data-act="edit"]', sec); b.querySelector('span').textContent = t('edit'); b.querySelector('use').setAttribute('href', '#i-pencil-line');
    S.editing = null;
    update();
  }
  $('[data-replies]').addEventListener('click', e => {
    const b = e.target.closest('[data-act="reply"]'); if (!b) return;
    const r = REPLIES.find(x => x.id === b.closest('[data-reply]').dataset.reply);
    copy(fill(replySrc(r).a[S.lang]), r.q[S.lang], b, true);
  });
  $('[data-subjects]').addEventListener('click', e => {
    const b = e.target.closest('[data-act="headline"]'); if (!b) return;
    copy(HEADLINES[S.track][+b.closest('tr').dataset.h][S.lang], t('subject'), b);
  });
  $('[data-ads]').addEventListener('click', e => {
    const art = e.target.closest('[data-ad]'); if (!art) return;
    const ad = ADS[S.track].find(a => a.id === art.dataset.ad), L = S.lang, A = T[L].ad;
    const v = e.target.closest('[data-adv]'); if (v) { S.adv[ad.id] = v.dataset.adv; renderAds(); save(); return; }
    if (e.target.closest('[data-see]')) { $('.feed-t', art).textContent = ad.intro[L]; return; }
    const b = e.target.closest('[data-act]'); if (!b) return;
    const act = b.dataset.act;
    if (act === 'ad-intro') copy(ad.intro[L], A.intro, b);
    else if (act === 'ad-headline') copy(ad.headline[L], A.headline, b);
    else if (act === 'ad-link') copy(b.dataset.url, A.dest, b);
    else if (act === 'ad-card') copy(ad.cards[+b.dataset.card][L], A.card.replace('{n}', +b.dataset.card + 1), b);
    else if (act === 'post') { const i = +b.closest('[data-post]').dataset.post; copy(fill(ad.posts[i][L], { missing: new Set(), url: trackLink({ id: `post_${i + 1}_${L}`, medium: 'organic_social' }) }), A.post.replace('{n}', i + 1), b); }
  });
  $$('[data-copy-all]').forEach(b => b.addEventListener('click', () => copy(crmRecord(), S.lang === 'ar' ? AR['next.copyAll'] : EN['next.copyAll'], b)));

  /* ---------- account inputs ---------- */
  const inputs = { first: $('#f-first'), company: $('#f-company'), city: $('#f-city'), rep: $('#f-rep'), note: $('#f-note'), date: $('#f-date'), account: $('#f-account'), plan: $('#f-plan'), renewal: $('#f-renewal'), pms: $('#f-pms'), count: $('#f-count') };
  const writeForm = () => Object.entries(inputs).forEach(([k, el]) => { el.value = S.f[k] || ''; });
  const fieldKey = el => Object.keys(inputs).find(x => inputs[x] === el);
  form.addEventListener('input', e => { const k = fieldKey(e.target); if (!k) return; S.f[k] = e.target.value; S.demo = false; update(); });
  form.addEventListener('change', e => { const k = fieldKey(e.target); if (k && S.f[k] !== e.target.value) { S.f[k] = e.target.value; update(); } });
  form.addEventListener('click', e => {
    const m = e.target.closest('[data-mod]');
    if (m) { const id = m.dataset.mod, i = S.f.modules.indexOf(id); if (i > -1) S.f.modules.splice(i, 1); else if (S.f.modules.length < 3) S.f.modules.push(id); S.demo = false; update(); return; }
    const p = e.target.closest('[data-persona]');
    if (p && S.persona !== p.dataset.persona) { S.persona = p.dataset.persona; S.passed = new Set(['note']); shown.clear(); buildSequence(); update(); }
  });
  $('[data-router]').addEventListener('click', e => {
    if (e.target.closest('[data-use-routed]')) { switchCamp(route(), true); return; }
    const b = e.target.closest('[data-q] button'); if (!b) return;
    S.r[b.closest('[data-q]').dataset.q] = b.dataset.v; S.demo = false;
    const r = route();
    if (r !== 'R' && r !== S.track) switchCamp(r, true); else update();
  });
  $('[data-gate]').addEventListener('change', e => { const k = e.target.dataset.g; if (!k) return; S.g[k] = k === 'ver' ? e.target.value : e.target.checked; update(); });
  $('[data-rel-switch]').addEventListener('click', () => { S.rel.on = !S.rel.on; update(); });
  $$('[data-rel]').forEach(el => el.addEventListener(el.tagName === 'SELECT' ? 'change' : 'input', () => { S.rel[el.dataset.rel] = el.value; update(); }));

  function loadDemo() {
    const d = DEMO[S.track], keepDate = S.f.date;
    S.f = Object.assign(blankF(), d.f[S.lang], { date: keepDate, renewal: d.renewal || '', modules: [...(d.modules || [])], account: d.account || '', count: d.count || '' });
    S.r = { ...d.r }; S.g = { ver: 'verified', perm: true, clean: true, extra: true };
    S.demo = true; S.edits = {}; writeForm();
  }
  function switchCamp(c, keepFacts) {
    if (!c || c === 'R' || S.track === c) { update(); return; }
    S.track = c; S.passed = new Set(['note']); shown.clear();
    if (S.demo && !keepFacts) loadDemo();
    applyChrome(); renderPlay(); renderAds(); buildSequence(); update();
  }
  $('[data-load-demo]').addEventListener('click', () => { loadDemo(); buildSequence(); update(); });
  $('[data-clear]').addEventListener('click', () => {
    const keep = { rep: S.f.rep, date: S.f.date };
    S.f = Object.assign(blankF(), keep); S.r = { existing: 'no', props: '1', pms: 'unknown' }; S.g = { ver: 'unverified', perm: false, clean: false, extra: false };
    S.demo = false; S.edits = {}; writeForm(); buildSequence(); update(); inputs.first.focus();
  });

  /* ---------- the close: start the next account ---------- */
  $('[data-next-form]').addEventListener('submit', e => {
    e.preventDefault();
    const n = $('#n-first'), keep = { rep: S.f.rep, date: S.f.date };
    S.f = Object.assign(blankF(), keep, { first: n.value.trim() });
    S.r = { existing: 'no', props: '1', pms: 'unknown' }; S.g = { ver: 'unverified', perm: false, clean: false, extra: false };
    S.demo = false; S.edits = {}; S.passed = new Set(['note']); shown.clear();
    n.value = ''; writeForm(); buildSequence(); update();
    document.getElementById('account').scrollIntoView({ behavior: reduce.matches ? 'auto' : 'smooth', block: 'start' });
    setTimeout(() => (S.f.first ? inputs.company : inputs.first).focus({ preventScroll: true }), reduce.matches ? 0 : 450);
  });

  /* ---------- campaign and language ---------- */
  $$('[data-camp]').forEach(b => b.addEventListener('click', () => switchCamp(b.dataset.camp)));
  $$('[data-lang]').forEach(b => b.addEventListener('click', () => {
    if (S.lang === b.dataset.lang) return;
    S.lang = b.dataset.lang;
    if (S.demo) loadDemo();            // a demo account switches to the demo names in the new language
    shown.clear(); applyChrome(); renderPlay(); renderAds(); buildSequence(); update();
  }));

  /* ---------- scroll: passing a touch sends it into the inbox; the nav follows the reader ---------- */
  let passIO, spyIO;
  function observe() {
    passIO && passIO.disconnect(); spyIO && spyIO.disconnect();
    passIO = new IntersectionObserver(entries => {
      let changed = false;
      entries.forEach(en => {
        if (!en.isIntersecting) return;
        const idx = steps().findIndex(s => s.id === en.target.dataset.step);
        steps().slice(0, idx + 1).forEach(s => { if (!S.passed.has(s.id)) { S.passed.add(s.id); changed = true; } });
      });
      if (changed) renderThread(steps().map(model));
    }, { rootMargin: '0px 0px -38% 0px' });
    $$('.step', seqEl).forEach(s => passIO.observe(s));
    spyIO = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (!en.isIntersecting) return;
        const id = en.target.id;
        $$('[data-spy]').forEach(a => a.classList.toggle('is-active', a.dataset.spy === id));
        const act = $(`[data-spy="${id}"]`);
        if (act && matchMedia('(max-width: 900px)').matches) act.scrollIntoView({ block: 'nearest', inline: 'center' });
      });
    }, { rootMargin: '-35% 0px -60% 0px' });
    [document.getElementById('account'), ...$$('.step', seqEl), ...['ads', 'replies', 'subjects', 'playbook', 'next'].map(id => document.getElementById(id))].forEach(el => el && spyIO.observe(el));
  }

  /* ---------- inbox drawer on narrow screens ---------- */
  const toggle = $('.inbox-toggle'), scrim = $('[data-scrim]');
  const setInbox = open => {
    inbox.classList.toggle('is-open', open); toggle.setAttribute('aria-expanded', String(open)); scrim.hidden = !open;
    if (open) $('[data-inbox-close]').focus(); else if (document.activeElement && inbox.contains(document.activeElement)) toggle.focus();
  };
  toggle.addEventListener('click', () => setInbox(!inbox.classList.contains('is-open')));
  $('[data-inbox-close]').addEventListener('click', () => setInbox(false));
  scrim.addEventListener('click', () => setInbox(false));
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && inbox.classList.contains('is-open')) setInbox(false); });
  matchMedia('(min-width: 1280px)').addEventListener('change', e => { if (e.matches) setInbox(false); });

  /* ---------- per-viewer convenience: remember the account, campaign and language ---------- */
  const STORE = 'fq360-linkedin-kit-v2';
  let saveTimer;
  function save() { clearTimeout(saveTimer); saveTimer = setTimeout(() => { try { localStorage.setItem(STORE, JSON.stringify({ track: S.track, lang: S.lang, persona: S.persona, f: S.f, r: S.r, g: S.g, rel: S.rel, demo: S.demo, adv: S.adv })); } catch (e) {} }, 250); }
  let saved = null;
  try { saved = JSON.parse(localStorage.getItem(STORE) || 'null'); } catch (e) {}
  const qs = new URLSearchParams(location.search);
  if (saved && saved.f) {
    S.track = /^[ABCD]$/.test(saved.track) ? saved.track : 'C'; S.lang = saved.lang === 'en' ? 'en' : 'ar'; S.persona = ['GM', 'IT', 'FIN'].includes(saved.persona) ? saved.persona : 'GM';
    S.f = Object.assign(blankF(), saved.f); if (!Array.isArray(S.f.modules)) S.f.modules = [];
    Object.assign(S.r, saved.r || {}); Object.assign(S.g, saved.g || {}); Object.assign(S.rel, saved.rel || {}); Object.assign(S.adv, saved.adv || {}); S.demo = !!saved.demo;
  }
  if (qs.get('lang') === 'ar' || qs.get('lang') === 'en') S.lang = qs.get('lang');
  const qc = (qs.get('campaign') || qs.get('track') || '').toUpperCase();
  if (/^[ABCD]$/.test(qc)) S.track = qc;
  if (!saved || !saved.f || S.demo) loadDemo();
  const d = parse(S.f.date);
  if (!d || d < today()) S.f.date = iso(nextWork(new Date()));
  writeForm(); applyChrome(); renderPlay(); renderAds(); buildSequence(); update();
})();
</script>
<noscript><img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=11189657&fmt=gif"></noscript>
<noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id=992779690541049&amp;ev=PageView&amp;noscript=1"></noscript>
<script src="assets/js/lead-capture.js?v=2eadb810" defer></script>
</body>
</html>
