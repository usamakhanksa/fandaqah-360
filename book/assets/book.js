/* Fandaqah 360 campaign book: builds the chapters from data.js + media/manifest.js and animates them with GSAP. */
(() => {
  const D = window.BOOK_DATA, M = window.BOOK_MEDIA || {}, I18N = window.BOOK_I18N || { ar: {} };
  const root = document.documentElement;
  const $ = (s, c = document) => c.querySelector(s), $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const G = window.gsap, ST = window.ScrollTrigger;
  if (G && ST) G.registerPlugin(ST);
  const CODES = ['A', 'B', 'C', 'D'];
  const ico = id => `<svg class="ic" aria-hidden="true"><use href="../assets/icons.svg#${id}"/></svg>`;
  const strip = html => html.replace(/<[^>]+>/g, '');

  /* ---------- strings for the generated parts ---------- */
  const TXT = {
    en: {
      chapter: 'Chapter', buyer: 'Buyer', action: 'Desired action', cta: 'Call to action', landing: 'Open the landing page',
      reels: 'Reels', reelsMeta: '25 s · 1080×1920 · MP4 · captions burned in', download: 'Download MP4', live: 'Live GSAP version',
      arabic: 'Arabic', english: 'English', pending: 'Reel is being rendered', play: 'Play or pause the reel',
      storyboard: 'Storyboard', beats: ['0–3 s · Hook', '3–10 s · Solution', '10–20 s · Proof', '20–25 s · Call to action'],
      book: 'Banner book', bookMeta: n => `${n} pages · tap a page to turn it`, prev: 'Previous page', next: 'Next page', zoom: 'View full size',
      designed: 'Designed banner', studio: 'Studio creative', coverNote: 'Designed banners and studio creatives, Arabic and English.',
      rail: 'Carousel and stories', carousel: 'Carousel', stories: 'Stories', close: 'Close', save: 'Download',
      copied: 'Link copied', stats: ['campaigns', 'reels', 'designed banners', 'studio creatives'],
      tocReels: n => `${n} reels`, tocBanners: n => `${n} designed banners`
    },
    ar: {
      chapter: 'الفصل', buyer: 'المشتري', action: 'الإجراء المطلوب', cta: 'دعوة الإجراء', landing: 'افتح صفحة الهبوط',
      reels: 'الريلز', reelsMeta: '25 ثانية · 1080×1920 · MP4 · ترجمة مدمجة', download: 'تحميل MP4', live: 'نسخة GSAP الحية',
      arabic: 'العربية', english: 'الإنجليزية', pending: 'جارٍ تجهيز الريل', play: 'تشغيل الريل أو إيقافه',
      storyboard: 'القصة المصورة', beats: ['0–3 ث · الجذب', '3–10 ث · الحل', '10–20 ث · الإثبات', '20–25 ث · دعوة الإجراء'],
      book: 'كتاب البنرات', bookMeta: n => `${n} صفحة · اضغط على الصفحة لتقليبها`, prev: 'الصفحة السابقة', next: 'الصفحة التالية', zoom: 'عرض بالحجم الكامل',
      designed: 'بنر مصمَّم', studio: 'تصميم الاستوديو', coverNote: 'البنرات المصمَّمة وتصاميم الاستوديو، بالعربية والإنجليزية.',
      rail: 'الكاروسيل والقصص', carousel: 'كاروسيل', stories: 'قصص', close: 'إغلاق', save: 'تحميل',
      copied: 'تم نسخ الرابط', stats: ['حملات', 'ريلز', 'بنراً مصمَّماً', 'تصميماً من الاستوديو'],
      tocReels: n => `${n} ريلز`, tocBanners: n => (n >= 3 && n <= 10 ? `${n} بنرات مصمَّمة` : `${n} بنراً مصمَّماً`)
    }
  };

  /* ---------- language (shared preference with the campaign site) ---------- */
  const textNodes = $$('[data-i18n]'), EN = {};
  textNodes.forEach(n => { if (!(n.dataset.i18n in EN)) EN[n.dataset.i18n] = n.innerHTML; });
  const META_EN = { title: document.title, desc: $('meta[name="description"]').content };
  let lang;
  const qs = new URLSearchParams(location.search);
  const initial = () => {
    const u = qs.get('lang'); if (u === 'ar' || u === 'en') return u;
    try { const s = localStorage.getItem('fq360-lang'); if (s) return s; } catch (e) {}
    return (navigator.languages || [navigator.language || '']).some(l => /^ar\b/i.test(l)) ? 'ar' : 'en';
  };

  /* ---------- builders ---------- */
  const media = c => M[c] || { banners: [], feed: [], story: [], carousel: [], reels: {} };
  const byLang = (list, l) => [...list.filter(x => x.lang === l), ...list.filter(x => x.lang !== l)];

  function renderCover() {
    const T = TXT[lang];
    const counts = [CODES.length, CODES.reduce((n, c) => n + Object.keys(media(c).reels).length, 0),
      CODES.reduce((n, c) => n + media(c).banners.length, 0), CODES.reduce((n, c) => n + media(c).feed.length + media(c).story.length + media(c).carousel.length, 0)];
    $('.bk-stats').innerHTML = counts.map((n, i) => `<li><b data-count="${n}">${n}</b><span>${T.stats[i]}</span></li>`).join('');
    // the fan: one designed banner per campaign plus two dark variants, in the reader's language
    const picks = [];
    CODES.forEach(c => { const b = byLang(media(c).banners, lang)[0]; if (b) picks.push(b); });
    ['B', 'C'].forEach(c => { const b = byLang(media(c).banners, lang)[1]; if (b) picks.push(b); });
    $('.bk-fan').innerHTML = picks.slice(0, 6).map(b => `<figure><img src="${b.src}" width="${b.w}" height="${b.h}" alt=""></figure>`).join('');
  }

  function renderToc() {
    const T = TXT[lang];
    $('.bk-toc').innerHTML = CODES.map(c => {
      const X = D.campaigns[c][lang], m = media(c);
      return `<li><a href="#ch-${c}"><span class="code">${c}</span><b>${X.name}</b><p>${X.promise}</p><small>${T.tocReels(Object.keys(m.reels).length)} · ${T.tocBanners(m.banners.length)}</small></a></li>`;
    }).join('');
  }

  const imgPage = (item, kind) => `<img src="${item.src}" width="${item.w}" height="${item.h}" alt="${(item.title || '').replace(/"/g, '&quot;')}" loading="lazy"><span class="page-tag">${TXT[lang][kind]} · ${item.lang.toUpperCase()}</span><button class="page-zoom" type="button" data-zoom="${item.src}" data-title="${(item.title || item.name || '').replace(/"/g, '&quot;')}" aria-label="${TXT[lang].zoom}">${ico('i-search')}</button>`;

  function renderChapters() {
    const T = TXT[lang];
    $('#chapters').innerHTML = CODES.map(c => {
      const C = D.campaigns[c], X = C[lang], m = media(c);
      const phone = l => {
        const r = m.reels[l];
        const body = r ? `<video muted playsinline loop preload="none" poster="${r.poster}"><source src="${r.mp4}" type="video/mp4"></video><button class="bk-play" type="button" aria-label="${T.play}"><span>${ico('i-play')}</span></button>`
          : `<div class="bk-missing">${T.pending}</div>`;
        return `<figure class="bk-phone" data-lang="${l}"><div class="bk-phone-frame">${body}</div><figcaption><b>${l === 'ar' ? T.arabic : T.english}</b><span>${r ? `<a href="${r.mp4}" download>${T.download} (${r.mb} MB)</a>` : ''}<a href="reel.html?c=${c}&lang=${l}" target="_blank" rel="noopener">${T.live}</a></span></figcaption></figure>`;
      };
      const beats = X.scenes.map((s, i) => `<li><b>${T.beats[i]}</b><strong>${strip(s.h)}</strong><p>${s.cap}</p></li>`).join('');
      const pages = [`<div class="page-title"><span class="code">${c}</span><b>${X.name}</b><p>${T.book} · ${T.coverNote}</p></div>`,
        ...byLang(m.banners, lang).map(b => imgPage(b, 'designed')), ...byLang(m.feed, lang).map(f => imgPage(f, 'studio'))];
      if (pages.length % 2 === 0) pages.push('<div class="page-title" style="background:#FBF9F6"></div>');
      pages.push(`<div class="page-title end"><span class="code">${c}</span><b>${X.cta}</b><p>fandaqah.com · ${C.route.replace('/', '')}</p></div>`);
      PAGES[c] = pages;
      const rail = [...byLang(m.carousel, lang).map(x => `<button type="button" data-zoom="${x.src}" data-title="${x.name}"><img src="${x.src}" width="${x.w}" height="${x.h}" alt="${T.carousel} ${x.name}" loading="lazy"></button>`),
        `<span class="bk-rail-label">${T.stories}</span>`, ...byLang(m.story, lang).map(x => `<button class="story" type="button" data-zoom="${x.src}" data-title="${x.name}"><img src="${x.src}" width="${x.w}" height="${x.h}" alt="${T.stories} ${x.name}" loading="lazy"></button>`)].join('');
      return `<section class="bk-ch theme-${C.theme}" id="ch-${c}" aria-labelledby="ch-${c}-t" data-code="${c}">
        <div class="wrap bk-ch-open"><span class="bk-letter" aria-hidden="true">${c}</span><div class="bk-ch-copy">
          <p class="bk-eyebrow dark">${T.chapter} ${c} · ${X.name}</p><h2 id="ch-${c}-t">${X.promise}</h2>
          <dl class="bk-meta"><div><dt>${T.buyer}</dt><dd>${X.buyer}</dd></div><div><dt>${T.action}</dt><dd>${X.action}</dd></div><div><dt>${T.cta}</dt><dd>${X.cta}</dd></div></dl>
          <a class="bk-link" href="../${C.route}?lang=${lang}" target="_blank" rel="noopener">${T.landing}${ico('i-arrow-up-right')}</a></div></div>
        <div class="wrap"><h3 class="bk-sub">${T.reels}<small>${T.reelsMeta}</small></h3>
          <div class="bk-reels-in">${phone(lang)}${phone(lang === 'ar' ? 'en' : 'ar')}<ol class="bk-beats" aria-label="${T.storyboard}">${beats}</ol></div></div>
        <div class="wrap bk-flip-wrap"><h3 class="bk-sub">${T.book}<small>${T.bookMeta(pages.length)}</small></h3>
          <div class="bk-flip" tabindex="0" role="group" aria-roledescription="book" aria-label="${T.book} ${c}" data-code="${c}"></div>
          <div class="bk-flip-ctrl"><button type="button" data-turn="prev" aria-label="${T.prev}">${ico('i-arrow-right')}</button><span class="bk-count" aria-live="polite"></span><button type="button" data-turn="next" aria-label="${T.next}">${ico('i-arrow-right')}</button></div></div>
        <div class="wrap"><h3 class="bk-sub">${T.rail}<small>${T.carousel} 1080×1350 · ${T.stories} 1080×1920</small></h3><div class="bk-rail">${rail}</div></div>
      </section>`;
    }).join('');
    // the "previous" arrow points backwards in reading direction
    $$('[data-turn="prev"] .ic').forEach(i => { i.style.transform = lang === 'ar' ? 'none' : 'scaleX(-1)'; });
  }

  /* ---------- the banner book: 3D page turns, right-to-left in Arabic ---------- */
  const books = [], PAGES = {};
  function Flipbook(el) {
    const pages = PAGES[el.dataset.code], single = matchMedia('(max-width: 860px)').matches, rtl = lang === 'ar';
    const count = el.parentElement.querySelector('.bk-count'), prevBtn = el.parentElement.querySelector('[data-turn="prev"]'), nextBtn = el.parentElement.querySelector('[data-turn="next"]');
    const sign = rtl ? 180 : -180, dur = reduce ? 0 : .9;
    el.classList.toggle('is-single', single);
    let leaves, cur = 0;
    const turn = (leaf, deg, d, done) => {
      if (G) G.to(leaf, { rotationY: deg, duration: d, ease: 'power2.inOut', onComplete: done });
      else { leaf.style.transform = `rotateY(${deg}deg)`; done && done(); }
      if (G && d) leaf.querySelectorAll('.face').forEach(f => G.fromTo(f, { '--shade': 0 }, { '--shade': .6, duration: d / 2, yoyo: true, repeat: 1, ease: 'sine.inOut' }));
    };
    if (!single) {
      const L = Math.ceil(pages.length / 2);
      el.innerHTML = Array.from({ length: L }, (_, i) => `<div class="leaf" style="z-index:${L - i}"><div class="face front">${pages[2 * i]}</div><div class="face back">${pages[2 * i + 1] || ''}</div></div>`).join('');
      leaves = $$('.leaf', el);
      const sync = () => {
        el.classList.toggle('is-closed', cur === 0); el.classList.toggle('is-ended', cur === L);
        count.textContent = cur === 0 ? `1 / ${pages.length}` : cur === L ? `${pages.length} / ${pages.length}` : `${2 * cur}–${2 * cur + 1} / ${pages.length}`;
        prevBtn.disabled = cur === 0; nextBtn.disabled = cur === L;
      };
      this.next = () => { if (cur >= L) return; const leaf = leaves[cur], k = ++cur; leaf.style.zIndex = L + 10; turn(leaf, sign, dur, () => { leaf.style.zIndex = k; }); sync(); };
      this.prev = () => { if (cur <= 0) return; const k = --cur, leaf = leaves[k]; leaf.style.zIndex = L + 10; turn(leaf, 0, dur, () => { leaf.style.zIndex = L - k; }); sync(); };
      el.onclick = e => { if (e.target.closest('.page-zoom')) return; const i = leaves.indexOf(e.target.closest('.leaf')); if (i < 0) return; i >= cur ? this.next() : this.prev(); };
      sync();
    } else {
      el.innerHTML = pages.map((p, i) => `<div class="leaf" style="z-index:${pages.length - i}"><div class="face front">${p}</div></div>`).join('');
      leaves = $$('.leaf', el);
      const sync = () => { count.textContent = `${cur + 1} / ${pages.length}`; prevBtn.disabled = cur === 0; nextBtn.disabled = cur === pages.length - 1; };
      this.next = () => { if (cur >= pages.length - 1) return; const leaf = leaves[cur++]; turn(leaf, sign / 2, dur * .6, () => { leaf.style.visibility = 'hidden'; }); sync(); };
      this.prev = () => { if (cur <= 0) return; const leaf = leaves[--cur]; leaf.style.visibility = 'visible'; turn(leaf, 0, dur * .6); sync(); };
      el.onclick = e => { if (!e.target.closest('.page-zoom')) this.next(); };
      sync();
    }
    prevBtn.onclick = () => this.prev(); nextBtn.onclick = () => this.next();
    el.onkeydown = e => {
      if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
      e.preventDefault();
      (e.key === 'ArrowRight') !== rtl ? this.next() : this.prev();
    };
  }

  /* ---------- reels: play while on screen ---------- */
  function initReels() {
    $$('.bk-phone').forEach(fig => {
      const v = $('video', fig), btn = $('.bk-play', fig);
      if (!v) return;
      btn.addEventListener('click', () => (v.paused ? v.play().catch(() => {}) : v.pause()));
      v.addEventListener('play', () => fig.classList.add('is-playing'));
      v.addEventListener('pause', () => fig.classList.remove('is-playing'));
      if (ST && !reduce) ST.create({ trigger: fig, start: 'top 80%', end: 'bottom 20%', onToggle: self => (self.isActive ? v.play().catch(() => {}) : v.pause()) });
    });
  }

  /* ---------- lightbox ---------- */
  const lb = $('.bk-lb');
  let lbFrom = null;
  const closeLb = () => { lb.hidden = true; document.body.style.overflow = ''; if (lbFrom) lbFrom.focus(); };
  document.addEventListener('click', e => {
    const z = e.target.closest('[data-zoom]');
    if (z) {
      e.stopPropagation(); lbFrom = z;
      $('img', lb).src = z.dataset.zoom; $('img', lb).alt = z.dataset.title || '';
      $('.bk-lb-title', lb).textContent = z.dataset.title || '';
      $('.bk-lb-save', lb).href = z.dataset.zoom; $('.bk-lb-save', lb).setAttribute('download', z.dataset.zoom.split('/').pop());
      lb.hidden = false; document.body.style.overflow = 'hidden'; $('.bk-lb-close', lb).focus();
    }
    if (e.target === lb || e.target.closest('.bk-lb-close')) closeLb();
  }, true);
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !lb.hidden) closeLb(); });

  /* ---------- copy the book link ---------- */
  $$('[data-copy]').forEach(b => b.addEventListener('click', async () => {
    const url = location.origin + location.pathname;
    try { await navigator.clipboard.writeText(url); } catch (e) { const i = $('.bk-copy input'); i.select(); document.execCommand('copy'); }
    const label = b.querySelector('span') || b, was = label.textContent;
    label.textContent = TXT[lang].copied; setTimeout(() => { label.textContent = was; }, 1800);
  }));
  $('.bk-copy input').value = location.origin + location.pathname;

  /* ---------- motion ---------- */
  let ctx;
  function animate(first) {
    if (!G || !ST) return;
    ctx && ctx.revert();
    ctx = G.context(() => {
      const fan = $$('.bk-fan figure'), n = fan.length, spread = i => (i - (n - 1) / 2);
      const rest = i => ({ rotation: spread(i) * 9, x: spread(i) * 54, y: Math.abs(spread(i)) * 16 });
      if (reduce) { fan.forEach((f, i) => G.set(f, rest(i))); return; }
      // cover: words rise, numbers count, the deck deals into a fan
      if (first) {
        G.from('.bk-head', { y: -20, opacity: 0, duration: .6 });
        G.from('.bk-cover .bk-eyebrow, .bk-lead, .bk-cta', { y: 30, opacity: 0, duration: .8, stagger: .12, delay: .15 });
        const h1 = $('.bk-h1');
        h1.querySelectorAll('.w').length || [...h1.childNodes].forEach(function split(node) {
          if (node.nodeType === 3) { const f = document.createDocumentFragment(); node.textContent.split(/(\s+)/).forEach(p => { if (!p) return; if (/^\s+$/.test(p)) f.append(p); else { const s = document.createElement('span'); s.className = 'w'; s.textContent = p; f.append(s); } }); node.replaceWith(f); }
          else [...node.childNodes].forEach(split);
        });
        G.from('.bk-h1 .w', { y: 60, opacity: 0, duration: .8, stagger: .06, delay: .25 });
        $$('.bk-stats b').forEach(b => { const o = { v: 0 }; G.to(o, { v: +b.dataset.count, duration: 1.6, delay: .6, ease: 'power2.out', onUpdate: () => { b.textContent = Math.round(o.v); } }); });
      }
      fan.forEach((f, i) => G.fromTo(f, { rotation: 0, x: 0, y: 140, opacity: 0, scale: .9 }, { ...rest(i), opacity: 1, scale: 1, duration: 1.1, ease: 'power3.out', delay: (first ? .5 : 0) + i * .09 }));
      fan.forEach((f, i) => G.to(f, { y: rest(i).y - 260 - i * 30, rotation: rest(i).rotation * 1.8, opacity: .15, ease: 'none', scrollTrigger: { trigger: '.bk-cover', start: 'top top', end: 'bottom top', scrub: true } }));
      if (matchMedia('(hover: hover) and (pointer: fine)').matches) {
        const rx = G.quickTo('.bk-fan', 'rotationX', { duration: .6 }), ry = G.quickTo('.bk-fan', 'rotationY', { duration: .6 });
        $('.bk-cover').onpointermove = e => { rx((e.clientY / innerHeight - .5) * -10); ry((e.clientX / innerWidth - .5) * 14); };
      }
      // header turns solid after the cover; chapter nav follows the reader
      ST.create({ trigger: '#contents', start: 'top 70px', end: 'max', onToggle: s => $('.bk-head').classList.toggle('is-solid', s.isActive) });
      G.from('.bk-toc li', { y: 50, opacity: 0, duration: .8, stagger: .1, scrollTrigger: { trigger: '.bk-toc', start: 'top 85%' } });
      $$('.bk-ch').forEach(ch => {
        ST.create({ trigger: ch, start: 'top 50%', end: 'bottom 50%', onToggle: s => { if (s.isActive) $$('.bk-nav a').forEach(a => a.classList.toggle('is-on', a.getAttribute('href') === '#' + ch.id)); } });
        G.fromTo($('.bk-letter', ch), { scale: .6, opacity: 0, yPercent: 25 }, { scale: 1, opacity: 1, yPercent: 0, ease: 'none', scrollTrigger: { trigger: ch, start: 'top 90%', end: 'top 25%', scrub: true } });
        G.from($$('.bk-ch-copy > *', ch), { y: 40, opacity: 0, duration: .8, stagger: .1, scrollTrigger: { trigger: ch, start: 'top 70%' } });
        G.from($$('.bk-phone', ch), { y: 90, opacity: 0, rotation: (i) => (i ? 4 : -4), duration: 1, stagger: .15, scrollTrigger: { trigger: $('.bk-reels-in', ch), start: 'top 80%' } });
        G.from($$('.bk-beats li', ch), { x: 40, opacity: 0, duration: .7, stagger: .1, scrollTrigger: { trigger: $('.bk-beats', ch), start: 'top 85%' } });
        G.from($('.bk-flip', ch), { rotationX: 22, y: 80, opacity: 0, duration: 1.1, transformPerspective: 1600, scrollTrigger: { trigger: $('.bk-flip', ch), start: 'top 85%' } });
        G.from($$('.bk-rail > *', ch), { y: 40, opacity: 0, duration: .6, stagger: .05, scrollTrigger: { trigger: $('.bk-rail', ch), start: 'top 90%' } });
      });
      G.from('.bk-routes a', { y: 30, opacity: 0, duration: .7, stagger: .08, scrollTrigger: { trigger: '.bk-routes', start: 'top 90%' } });
    });
  }

  /* ---------- render everything for a language ---------- */
  function setLang(l, persist) {
    lang = l === 'ar' ? 'ar' : 'en';
    root.lang = lang; root.dir = lang === 'ar' ? 'rtl' : 'ltr';
    textNodes.forEach(n => { const v = lang === 'ar' ? I18N.ar[n.dataset.i18n] : EN[n.dataset.i18n]; if (v != null) n.innerHTML = v; });
    const meta = lang === 'ar' && I18N.meta ? I18N.meta : META_EN;
    document.title = meta.title; $('meta[name="description"]').content = meta.desc;
    $$('[data-lang]').forEach(b => b.setAttribute('aria-pressed', String(b.dataset.lang === lang)));
    if (persist) { try { localStorage.setItem('fq360-lang', lang); } catch (e) {} }
    renderCover(); renderToc(); renderChapters();
    books.length = 0; $$('.bk-flip').forEach(el => books.push(new Flipbook(el)));
    initReels();
    animate(!persist);
    if (ST) ST.refresh();
  }
  $$('[data-lang]').forEach(b => b.addEventListener('click', () => { if (b.dataset.lang !== lang) setLang(b.dataset.lang, true); }));
  let resizeTimer, wasSingle = matchMedia('(max-width: 860px)').matches;
  addEventListener('resize', () => { clearTimeout(resizeTimer); resizeTimer = setTimeout(() => { const s = matchMedia('(max-width: 860px)').matches; if (s !== wasSingle) { wasSingle = s; books.length = 0; $$('.bk-flip').forEach(el => books.push(new Flipbook(el))); } }, 200); });
  setLang(initial(), false);
})();
