/* Fandaqah feature pages: the hotel clock, the held board and the lateral rail.
   Scroll is the hotel's clock: 06:00 at the top of the page, each hour lands on its own entry, 23:59 at the
   day report. The clock regrades the page ground, drives the board through the day's sample data and stamps
   the report with the hours the reader was present for. The engine (scrollcraft.js) owns the pinned acts and
   publishes --sc-p; this file only reads layout and writes its own properties. */
(() => {
  const dataEl = document.getElementById('fx-data');
  if (!dataEl) return;
  const D = JSON.parse(dataEl.textContent);
  const root = document.documentElement;
  // the engine drives the pinned acts (hero, rail) and the flow reveals inside <main>; the site chrome stays its own
  if (window.ScrollCraft) window.ScrollCraft.mount(document.getElementById('main'));
  const $ = (s, c = document) => c.querySelector(s), $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const phone = matchMedia('(max-width: 860px)');
  const DAY_START = 6 * 60, DAY_END = 23 * 60 + 59;
  const toMin = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
  const fmt = m => { m = Math.max(0, Math.min(DAY_END, Math.round(m))); return String(Math.floor(m / 60)).padStart(2, '0') + ':' + String(m % 60).padStart(2, '0'); };

  const hours = $$('.fx-hour'), report = $('#report'), live = $('.fx-board--live');
  const boards = $$('.fx-board'), times = $$('.fx-board-time');
  const clockTime = $('.fx-clock-time'), handH = $('.fx-hand-h'), handM = $('.fx-hand-m');
  const reportLines = $$('.fx-report-lines li'), menuLinks = $$('.fx-clock-menu a');

  /* ---------- the light of the day: ground and beam colours keyed to minutes ---------- */
  const SKY = [[360, [246, 237, 226]], [540, [244, 239, 232]], [780, [242, 240, 235]], [1020, [246, 235, 224]],
               [1170, [244, 229, 218]], [1320, [238, 232, 234]], [DAY_END, [234, 229, 233]]];
  const BEAM = [[360, [255, 206, 158, .78]], [780, [255, 246, 228, .55]], [1080, [255, 190, 150, .7]], [DAY_END, [190, 196, 236, .45]]];
  function mix(stops, m) {
    let i = 1;
    while (i < stops.length - 1 && m > stops[i][0]) i++;
    const [m0, a] = stops[i - 1], [m1, b] = stops[i];
    const t = Math.max(0, Math.min(1, (m - m0) / (m1 - m0)));
    return a.map((v, k) => v + (b[k] - v) * t);
  }
  const rgb = c => `rgb(${Math.round(c[0])} ${Math.round(c[1])} ${Math.round(c[2])}${c.length > 3 ? ' / ' + c[3].toFixed(2) : ''})`;

  /* ---------- clock anchors: the scroll position at which each hour is "now" ---------- */
  let anchors = [], focusRatio = .5;
  function layout() {
    focusRatio = phone.matches ? .62 : .5;
    const vh = innerHeight, y0 = scrollY, focus = vh * focusRatio;
    anchors = [[0, DAY_START]];
    hours.forEach((h, i) => anchors.push([h.getBoundingClientRect().top + y0 - focus, Math.max(DAY_START, toMin(D.times[i]))]));
    anchors.push([report.getBoundingClientRect().top + y0 - vh * .55, DAY_END]);
    for (let i = 1; i < anchors.length; i++) if (anchors[i][0] <= anchors[i - 1][0]) anchors[i][0] = anchors[i - 1][0] + 1;
    const rail = $('.fx-rail');
    if (rail) rail.style.setProperty('--travel', Math.max(0, rail.scrollWidth - innerWidth).toFixed(0));
    frame();
  }
  function minuteAt(y) {
    for (let i = 1; i < anchors.length; i++) {
      const [y1, m1] = anchors[i], [ya, ma] = anchors[i - 1];
      if (y <= y1) return ma + (m1 - ma) * Math.max(0, Math.min(1, (y - ya) / (y1 - ya)));
    }
    return DAY_END;
  }

  /* ---------- the board: initial rows + every patch up to the active hour ---------- */
  const initial = D.rows.slice();
  function stateAt(step) {
    const s = initial.slice();
    for (let i = 0; i <= step; i++) Object.entries(D.steps[i] || {}).forEach(([row, k]) => { s[+row] = k; });
    return s;
  }
  function paint(board, state, animate, stagger) {
    const changed = [];
    $$('.fx-board-rows li', board).forEach((li, i) => {
      const k = state[i];
      if (li.dataset.state === k) return;
      changed.push([li, k]);
    });
    changed.forEach(([li, k], n) => {
      const apply = () => {
        li.dataset.state = k;
        li.className = 'tone-' + D.states[k].tone;
        li.querySelector('em').textContent = D.states[k].label;
        if (animate) { void li.offsetWidth; li.classList.add('is-flip'); }
      };
      stagger && animate ? setTimeout(apply, n * 150) : apply();
    });
    const total = () => D.count.forEach(k => {
      const el = board.querySelector(`[data-count="${k}"] b`);
      if (el) el.textContent = state.filter(x => x === k).length;
    });
    stagger && animate ? setTimeout(total, changed.length * 150) : total();
  }

  let active = -2, lastMin = -1;
  const logged = new Set();
  function setStep(a) {
    const forward = a > active;
    active = a;
    hours.forEach((h, i) => h.classList.toggle('is-active', i === a));
    root.classList.toggle('fx-rush', a === D.peak);
    menuLinks.forEach((l, i) => l.classList.toggle('is-now', i === a));
    if (!live) return;
    const rush = a === D.peak && forward;
    paint(live, stateAt(a), !reduce, rush);
    live.classList.toggle('is-rush', a === D.peak);
    const log = live.querySelector('.fx-board-log');
    if (log) log.textContent = a >= 0 ? D.logs[a] : '';
    if (a >= 0 && !logged.has(a)) {
      logged.add(a);
      if (reportLines[a]) reportLines[a].classList.add('is-logged');
    }
  }

  /* ---------- one frame: clock, light, active hour ---------- */
  function frame() {
    const y = scrollY, m = Math.round(minuteAt(y));
    if (m !== lastMin) {
      lastMin = m;
      const t = fmt(m);
      if (clockTime) clockTime.textContent = t;
      times.forEach(el => { el.textContent = t; });
      if (handH) handH.style.transform = `rotate(${((m / 60) % 12) * 30}deg)`;
      if (handM) handM.style.transform = `rotate(${(m % 60) * 6}deg)`;
      root.style.setProperty('--sky', rgb(mix(SKY, m)));
      root.style.setProperty('--beam', rgb(mix(BEAM, m)));
    }
    const focus = innerHeight * focusRatio;
    let a = -1;
    hours.forEach((h, i) => { if (h.getBoundingClientRect().top <= focus) a = i; });
    if (a !== active) setStep(a);
  }
  let ticking = false;
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(() => { frame(); ticking = false; }); } }, { passive: true });
  let lastW = innerWidth;
  addEventListener('resize', () => { if (phone.matches && innerWidth === lastW) return; lastW = innerWidth; layout(); }, { passive: true });
  addEventListener('load', layout);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(layout);
  layout();
  boards.forEach(b => b.classList.add('is-ready'));

  /* ---------- the clock is also the page's map; at night the report takes over ---------- */
  const clock = $('.fx-clock'), btn = $('.fx-clock-btn'), menu = $('#fx-clock-menu');
  if (clock && 'IntersectionObserver' in window) {
    new IntersectionObserver(([e]) => {
      clock.classList.toggle('is-docked', e.isIntersecting);
      if (e.isIntersecting && menu && !menu.hidden) { menu.hidden = true; btn.setAttribute('aria-expanded', 'false'); }
    }, { threshold: .25 }).observe(report);
  }
  if (btn && menu) {
    const setOpen = open => { menu.hidden = !open; btn.setAttribute('aria-expanded', String(open)); };
    btn.addEventListener('click', () => { setOpen(menu.hidden); if (!menu.hidden) (menu.querySelector('.is-now') || menuLinks[0]).focus(); });
    menu.addEventListener('click', e => { if (e.target.closest('a')) setOpen(false); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !menu.hidden) { setOpen(false); btn.focus(); } });
    document.addEventListener('click', e => { if (!menu.hidden && !e.target.closest('.fx-clock')) setOpen(false); });
  }
})();
