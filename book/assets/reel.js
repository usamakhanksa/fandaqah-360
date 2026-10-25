/* Fandaqah 360 reel: builds one 25-second reel (?c=A..D&lang=ar|en) as a GSAP timeline.
   ?render=1 keeps it paused and exposes window.REEL.seek(t) so tools/render-reels.mjs can capture exact frames.
   ?embed=1 hides the controls (for iframes). */
(() => {
  const D = window.BOOK_DATA, q = new URLSearchParams(location.search);
  const code = (q.get('c') || 'A').toUpperCase(), lang = q.get('lang') === 'ar' ? 'ar' : 'en';
  const render = q.get('render') === '1', embed = q.get('embed') === '1';
  const C = D.campaigns[code] || D.campaigns.A, T = C[lang], S = T.scenes, TAG = D.tags[lang];
  const root = document.documentElement;
  root.lang = lang; root.dir = lang === 'ar' ? 'rtl' : 'ltr';
  document.body.classList.toggle('render', render);
  document.body.classList.toggle('embed', embed);
  document.title = `${T.name} · Fandaqah 360 reel`;
  const A = '../assets/', dirX = lang === 'ar' ? -1 : 1;
  const ico = id => `<svg class="ic" aria-hidden="true"><use href="${A}icons.svg#${id}"/></svg>`;

  /* ---------- device mock-ups: same markup as the website ---------- */
  const side = (items, brand = 'fandaqah <b>360</b>') => `<div class="ui-side"><div class="ui-brand"><i></i>${brand}</div><ul>${items.map((t, n) => `<li${n ? '' : ' class="on"'}>${ico(t[0])}${t[1]}</li>`).join('')}</ul></div>`;
  const chart = `<div class="ui-card ui-chart"><svg viewBox="0 0 220 70" preserveAspectRatio="none"><g stroke="#EDE8E1" stroke-width="1"><path d="M0 17H220M0 35H220M0 53H220"/></g><path class="draw" pathLength="1" d="M4 58 L30 50 L56 54 L82 40 L108 46 L134 30 L160 36 L186 22 L216 12" fill="none" stroke="#E9584D" stroke-width="2" stroke-linejoin="round" style="stroke-dasharray:1;stroke-dashoffset:1"/></svg></div>`;
  const rows = `<div class="ui-grid" style="--n:2"><div class="ui-card ui-rows"><div class="ui-row"><i style="--c:#F2B9A0"></i><span></span><span></span></div><div class="ui-row"><i style="--c:#E9C9A6"></i><span></span><span></span></div></div><div class="ui-card ui-rows"><div class="ui-row"><i style="--c:#E9584D"></i><span></span><span></span></div><div class="ui-row"><i style="--c:#C9C2A0"></i><span></span><span></span></div></div></div>`;
  const pmsMap = hot => `<div class="pms-map"><div class="pms-core">Your existing PMS<small>Opera / mandated PMS</small></div><svg class="pms-lines" viewBox="0 0 200 22" preserveAspectRatio="none"><path class="draw" pathLength="1" d="M100 0V10M20 10H180M20 10V22M100 10V22M180 10V22" fill="none" stroke="#E9584D" stroke-width="1.4" style="stroke-dasharray:1;stroke-dashoffset:1"/></svg><div class="pms-nodes"><div${hot ? ' class="hot"' : ''}>${ico('i-users')}HR &amp; staff</div><div>${ico('i-shopping-cart')}Procurement &amp; inventory</div><div>${ico('i-chart-line')}Finance &amp; reporting</div></div></div>`;
  const DEV = {
    laptopA: () => `<div class="laptop" style="--w:430px"><div class="laptop-screen"><div class="ui">${side([['i-calendar-days', 'Reservations'], ['i-bed-double', 'Rooms'], ['i-sparkles', 'Housekeeping'], ['i-coins', 'Accounting']])}<div class="ui-main"><div class="ui-top"><span>&larr;</span></div><div class="ui-h">Reservations</div>${chart}${rows}</div></div></div><div class="laptop-base"></div></div>`,
    laptopB: () => `<div class="laptop" style="--w:430px"><div class="laptop-screen"><div class="ui">${side([['i-layout-dashboard', 'Dashboard'], ['i-calendar-days', 'Reservations'], ['i-bed-double', 'Rooms'], ['i-sparkles', 'Housekeeping'], ['i-coins', 'Accounting']])}<div class="ui-main"><div class="ui-top"><span>&larr;</span></div><div class="ui-h">Dashboard</div>${chart}${rows}</div></div></div><div class="laptop-base"></div></div>`,
    monitorC: (hot) => `<div class="monitor" style="--w:430px"><div class="monitor-screen"><div class="ui ui--dark">${side([['i-layout-dashboard', 'Overview'], ['i-share-2', 'Interfaces'], ['i-settings', 'Operations'], ['i-chart-line', 'Reports']])}<div class="ui-main">${pmsMap(hot)}</div></div></div><div class="monitor-stand"></div><div class="monitor-foot"></div></div>`,
    laptopD: () => `<div class="laptop" style="--w:440px"><div class="laptop-screen"><div class="ui">${side([['i-network', 'Group overview'], ['i-building-2', 'Properties'], ['i-chart-line', 'Reports'], ['i-key', 'Permissions']], 'Your <b>group</b>')}<div class="ui-main"><div class="ui-top"><span class="grp-brand">● Your brand</span></div><div class="ui-h">Group overview<small>Group and property-level views</small></div>${chart}<div class="ui-grid" style="--n:4">${['Riyadh', 'Jeddah', 'Al Khobar', 'Madinah'].map((c, n) => `<div class="ui-card grp-tile"><b>Property ${n + 1}</b><small>${c}</small><div class="grp-bar"><i style="--v:${[78, 64, 71, 58][n]}%"></i></div></div>`).join('')}</div></div></div></div><div class="laptop-base"></div></div>`
  };

  /* ---------- scene markup ---------- */
  const MOTIF_ICONS = { A: ['i-chart-column', 'i-file-text', 'i-clipboard-check'], B: ['i-calendar-days', 'i-coins', 'i-shopping-cart'], C: ['i-users', 'i-shopping-cart', 'i-coins'], D: ['i-building-2', 'i-building-2', 'i-building-2'] };
  const motif = T.motif.map((t, n) => `<div class="mcard"><span class="icon-circle">${ico(MOTIF_ICONS[code][n])}</span><span>${t}${code === 'C' ? '<small>SAR ••••</small>' : ''}</span></div>`).join('')
    + (code === 'B' ? `<svg class="mbreak" viewBox="0 0 940 440" preserveAspectRatio="none" aria-hidden="true"><g fill="none" stroke="#F48C78" stroke-width="4" stroke-dasharray="14 12"><path d="M380 70 C 560 60 600 120 620 170"/><path d="M560 250 C 480 300 440 330 420 370"/></g><g stroke="#F48C78" stroke-width="5" stroke-linecap="round"><path d="M500 92 l22 22 M522 92 l-22 22"/><path d="M478 300 l22 22 M500 300 l-22 22"/></g></svg>` : '')
    + (T.sample ? `<span class="msample">${T.sample}</span>` : '');
  const legal = T.legal ? `<p class="legal">${T.legal}</p>` : '';
  let proof = '';
  // more than three modules switch to the dense stack so the list still clears the caption and the Reels interface
  if (S[2].list) proof = `<div class="proof${S[2].list.length > 3 ? ' dense' : ''}">${S[2].list.map((t, n) => `<div class="pcard"><span class="icon-circle">${ico((S[2].icons || ['i-coins', 'i-shopping-cart', 'i-bed-double', 'i-wrench', 'i-package'])[n])}</span>${t}<span class="ok">${ico('i-check')}</span></div>`).join('')}</div>`;
  else if (S[2].flow) proof = `<div class="proof flow">${S[2].flow.map((t, n) => `${n ? '<i class="link"></i>' : ''}<div class="pcard"><span class="icon-circle">${ico(['i-calendar-days', 'i-bed-double', 'i-user', 'i-coins'][n])}</span>${t}</div>`).join('')}</div>`;
  else if (S[2].toast) proof = `<div class="dev">${DEV.monitorC(true)}</div><div class="toast">${ico('i-check')}${S[2].toast}</div><span class="r-tag" style="top:1240px">${TAG.ui}</span>${legal}`;
  else if (S[2].brand) proof = `<div class="brand">${S[2].brand.map((c, n) => `<div class="bcard" style="--hc:${['#5B8DB8', '#6BA37A', '#A97FB3'][n]};--bc:${['#5B8DB8', '#6BA37A', '#A97FB3'][n]}"><header><i></i>${c}</header><div class="bbody"><b>${lang === 'ar' ? 'المنشأة' : 'Property'} ${n + 1}</b><div class="bar"><i style="--v:${[78, 64, 71][n]}%"></i></div><div class="bar"><i style="--v:${[52, 70, 46][n]}%"></i></div></div></div>`).join('')}</div><div class="brand-chip"><i></i>${lang === 'ar' ? 'هوية مجموعتك' : 'Your group brand'}</div><span class="r-tag" style="top:1180px">${TAG.brand}</span>`;
  const checks = S[3].checks ? `<ul class="checks">${S[3].checks.map(t => `<li><span class="tick">${ico('i-check')}</span>${t}</li>`).join('')}</ul>` : '';

  const reel = document.createElement('div');
  reel.className = `reel theme-${C.theme}`;
  reel.innerHTML = `
    <div class="r-bars">${'<i><b></b></i>'.repeat(4)}</div>
    <header class="r-top"><span class="r-logo"><img src="${A}img/brand-dark-340.webp" alt=""><b>360</b></span><span class="r-chip">${T.chip}</span></header>
    <section class="r-s s1"><figure class="photo"><img src="${A}img/${C.photo}" style="object-position:${C.photoPos}" alt=""></figure><div class="shade"></div><div class="motif">${motif}</div><div class="copy"><h2>${S[0].h}</h2></div></section>
    <section class="r-s s2"><div class="floor"></div><span class="wm" aria-hidden="true">360</span><div class="copy"><h2>${S[1].h}</h2><p class="sub">${S[1].s}</p></div><div class="dev">${DEV[C.device]()}</div><span class="r-tag">${C.device === 'laptopD' ? TAG.brand : TAG.ui}</span>${code === 'C' ? legal : ''}</section>
    <section class="r-s s3"><div class="floor"></div><span class="wm" aria-hidden="true">360</span><div class="copy"><h2>${S[2].h}</h2></div>${proof}</section>
    <section class="r-s s4"><div class="floor"></div><span class="wm" aria-hidden="true">360</span><svg class="leaf" viewBox="0 0 220 320" aria-hidden="true"><use href="${A}icons.svg#art-leaves"/></svg><div class="copy"><h2>${S[3].h}</h2>${S[3].s ? `<p class="sub">${S[3].s}</p>` : ''}${checks}<span class="cta">${T.cta}${ico('i-arrow-right')}</span><span class="url">${TAG.url}</span></div>${legal}</section>
    <div class="r-caps">${S.map(s => `<p>${s.cap}</p>`).join('')}</div>`;
  const stage = document.createElement('div');
  stage.className = 'stage';
  stage.append(reel);
  document.body.prepend(stage);

  // headline words rise one by one; split on spaces only, so Arabic letter joining is never broken
  const words = el => {
    const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT), nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach(n => {
      const frag = document.createDocumentFragment();
      n.textContent.split(/(\s+)/).forEach(part => {
        if (!part) return;
        if (/^\s+$/.test(part)) { frag.append(part); return; }
        const w = document.createElement('span'); w.className = 'w'; w.textContent = part; frag.append(w);
      });
      n.replaceWith(frag);
    });
    return el.querySelectorAll('.w');
  };
  const $ = s => reel.querySelector(s), $$ = s => [...reel.querySelectorAll(s)];
  const sc = $$('.r-s'), caps = $$('.r-caps p'), bars = $$('.r-bars b');

  /* ---------- timeline: hook 0–3 · solution 3–10 · proof 10–20 · CTA 20–25 ---------- */
  gsap.defaults({ ease: 'power3.out' });
  const tl = gsap.timeline({ paused: true });
  const START = [0, 3, 10, 20], TOTAL = 25;
  bars.forEach((b, i) => tl.fromTo(b, { scaleX: 0 }, { scaleX: 1, duration: D.beats[i], ease: 'none' }, START[i]));
  const caption = (i, from, to) => tl.fromTo(caps[i], { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: .35 }, from).to(caps[i], { opacity: 0, duration: .25 }, to);
  const enter = (i, at) => {
    tl.set(sc[i], { visibility: 'visible' }, at);
    if (i) tl.fromTo(sc[i], { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: .7, ease: 'power4.inOut' }, at)
      .set(sc[i - 1], { visibility: 'hidden' }, at + .75);
  };
  const headline = (i, at) => tl.from(words(sc[i].querySelector('h2')), { y: 70, opacity: 0, duration: .7, stagger: .055 }, at);

  // 1 · hook
  enter(0, 0);
  tl.fromTo(sc[0].querySelector('.photo img'), { scale: 1.2 }, { scale: 1.04, duration: 3.8, ease: 'none' }, 0)
    .from('.r-top > *', { y: -30, opacity: 0, duration: .5, stagger: .1 }, .05)
    .from($$('.s1 .mcard'), { y: 90, opacity: 0, rotation: i => [-6, 5, -3][i], duration: .7, stagger: .16 }, .25);
  headline(0, .35);
  $$('.s1 .mcard').forEach((m, i) => tl.to(m, { x: (i % 2 ? -12 : 12), rotation: (i % 2 ? -2.5 : 2.5), duration: .32, yoyo: true, repeat: 3, ease: 'sine.inOut' }, 1.2 + i * .08));
  if ($('.mbreak')) tl.from('.mbreak path', { opacity: 0, duration: .3, stagger: .1 }, .9);
  if ($('.msample')) tl.from('.msample', { opacity: 0, duration: .3 }, .8);
  caption(0, .3, 2.75);

  // 2 · solution
  enter(1, 3);
  headline(1, 3.35);
  tl.from('.s2 .sub', { y: 30, opacity: 0, duration: .6 }, 3.9)
    .from('.s2 .dev', { y: 260, opacity: 0, duration: 1.1 }, 3.6)
    .to('.s2 .draw', { strokeDashoffset: 0, duration: 1.4, ease: 'power1.inOut', stagger: .2 }, 4.6)
    .from('.s2 .ui-row, .s2 .ui-side li', { opacity: 0, x: -14 * dirX, duration: .45, stagger: .06 }, 4.8)
    .from('.s2 .pms-core', { scale: .7, opacity: 0, duration: .6, ease: 'back.out(2)' }, 4.5)
    .from('.s2 .pms-nodes > div', { y: 20, opacity: 0, duration: .5, stagger: .15 }, 5.3)
    .from('.s2 .grp-bar i', { scaleX: 0, transformOrigin: '0 50%', duration: .8, stagger: .1 }, 5.1)
    .from('.s2 .r-tag', { opacity: 0, duration: .4 }, 5.6)
    .to('.s2 .dev', { y: -16, duration: 3.4, ease: 'sine.inOut' }, 6.4);
  caption(1, 3.35, 9.7);

  // 3 · proof
  enter(2, 10);
  headline(2, 10.35);
  tl.from('.s3 .pcard', { x: 80 * dirX, opacity: 0, duration: .7, stagger: .55 }, 11)
    .from('.s3 .ok', { scale: 0, duration: .5, ease: 'back.out(2.4)', stagger: .55 }, 11.55)
    .from('.s3 .link', { scaleY: 0, duration: .4, stagger: .55 }, 11.4)
    .from('.s3 .dev', { y: 160, opacity: 0, duration: 1 }, 10.7)
    .to('.s3 .draw', { strokeDashoffset: 0, duration: 1.2, ease: 'power1.inOut' }, 11.6)
    .from('.s3 .pms-nodes > div', { y: 20, opacity: 0, duration: .5, stagger: .15 }, 12)
    .fromTo('.s3 .pms-nodes .hot', { boxShadow: 'inset 0 0 0 1.5px rgba(242,112,95,0)' }, { boxShadow: 'inset 0 0 0 1.5px rgba(242,112,95,1)', duration: .4, yoyo: true, repeat: 5 }, 13)
    .from('.s3 .toast', { y: 60, opacity: 0, duration: .6, ease: 'back.out(1.6)' }, 14.2)
    .from('.s3 .bcard', { y: 90, opacity: 0, duration: .7, stagger: .25 }, 10.9)
    .to('.s3 .bcard header', { backgroundColor: '#282639', duration: .6, stagger: .3 }, 13)
    .to('.s3 .bcard .bar i', { backgroundColor: '#E9584D', duration: .6, stagger: .1 }, 13.4)
    .from('.s3 .brand-chip', { scale: .6, opacity: 0, duration: .6, ease: 'back.out(2)' }, 14.6)
    .from('.s3 .r-tag', { opacity: 0, duration: .4 }, 14.2);
  caption(2, 10.35, 19.7);

  // 4 · call to action
  enter(3, 20);
  tl.from('.s4 .leaf', { x: 120 * dirX, opacity: 0, duration: 1 }, 20.2);
  headline(3, 20.35);
  tl.from('.s4 .sub', { y: 30, opacity: 0, duration: .6 }, 20.9)
    .from('.s4 .checks li', { x: 40 * dirX, opacity: 0, duration: .45, stagger: .14 }, 21)
    .from('.s4 .cta', { scale: .75, opacity: 0, duration: .7, ease: 'back.out(2)' }, 21.4)
    .from('.s4 .url', { opacity: 0, duration: .5 }, 21.9)
    .to('.s4 .cta', { scale: 1.05, duration: .45, yoyo: true, repeat: 3, ease: 'sine.inOut' }, 22.4);
  caption(3, 20.35, 24.85);
  tl.set({}, {}, TOTAL);
  tl.seek(0);

  /* ---------- live view: fit to the window, simple controls ---------- */
  const fit = () => { if (!render) reel.style.transform = `scale(${Math.min(innerWidth / 1080, innerHeight / 1920) * (embed ? 1 : .94)})`; };
  addEventListener('resize', fit); fit();
  if (!render) {
    const ctrl = document.createElement('div');
    ctrl.className = 'ctrl';
    const other = lang === 'ar' ? 'en' : 'ar';
    ctrl.innerHTML = `<button type="button" data-a="toggle" aria-label="Play or pause">❚❚</button><button type="button" data-a="restart" aria-label="Restart">↺</button><button type="button" data-a="lang">${other === 'ar' ? 'عربي' : 'EN'}</button>`;
    document.body.append(ctrl);
    const btn = ctrl.querySelector('[data-a="toggle"]');
    ctrl.addEventListener('click', e => {
      const a = e.target.closest('button')?.dataset.a;
      if (a === 'toggle') { tl.paused(!tl.paused()); btn.textContent = tl.paused() ? '▶' : '❚❚'; }
      if (a === 'restart') { tl.restart(); btn.textContent = '❚❚'; }
      if (a === 'lang') { q.set('lang', other); location.search = q.toString(); }
    });
    tl.eventCallback('onComplete', () => gsap.delayedCall(1.2, () => tl.restart()));
  }
  const ready = Promise.all([document.fonts.ready, ...[...reel.querySelectorAll('img')].map(im => im.complete ? 0 : new Promise(r => { im.onload = im.onerror = r; }))]);
  window.REEL = { duration: TOTAL, seek: t => { tl.seek(t, false); }, timeline: tl };
  // renderers that run in an isolated world (no access to window.REEL) drive the reel through the DOM:
  // set body[data-seek], dispatch "reel:seek", wait for body[data-frame] to match
  document.addEventListener('reel:seek', () => { const t = +document.body.dataset.seek || 0; tl.seek(t, false); document.body.dataset.frame = String(t); });
  ready.then(() => { document.body.dataset.ready = '1'; if (!render && q.get('autoplay') !== '0') tl.play(0); });
})();
