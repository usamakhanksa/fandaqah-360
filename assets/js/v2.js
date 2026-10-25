/* Campaign pages v2 (A: existing customers, C: Opera / mandated PMS): hero depth, the C cost lens,
   the A upgrade tray and the header's section highlight. Loaded after site.js, which owns language,
   attribution and the CRM form. The engine (scrollcraft.js) owns the pinned acts and publishes --sc-p. */
(() => {
  const $ = (s, c = document) => c.querySelector(s), $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (window.ScrollCraft) window.ScrollCraft.mount(document.getElementById('main'));

  /* ---------- hero depth: one progress value, every plane reads it at its own rate ---------- */
  const hero = $('.pc-hero, .pa-hero');

  /* ---------- C: the PMS map comes online once ---------- */
  const hub = $('.pc-hub');
  if (hub) setTimeout(() => hub.classList.add('is-on'), reduce ? 0 : 450);

  /* ---------- C: the cost lens. Each line the reader passes is placed on the review card ---------- */
  const cost = $('.pc-cost'), lines = cost ? $$('.pc-lines li', cost) : [], rows = cost ? $$('.pc-review-rows li', cost) : [];
  const stage = cost && $('[data-sc-stage]', cost);
  let lensStep = -2, pinned = false;
  const measure = () => { pinned = !!stage && getComputedStyle(stage).position === 'sticky'; };
  function lens() {
    if (!cost) return;
    let step;
    if (reduce) step = lines.length;
    else if (pinned) {
      // pinned: the act's own progress walks the five lines, with a short lead-in and a held end
      const travel = cost.offsetHeight - innerHeight;
      const p = travel > 0 ? Math.min(1, Math.max(0, -cost.getBoundingClientRect().top / travel)) : 1;
      step = Math.floor(p * (lines.length + 1) - .5);
    } else {
      // in natural flow: a line lights when it reaches the reader
      step = -1;
      lines.forEach((li, i) => { if (li.getBoundingClientRect().top < innerHeight * .72) step = i; });
    }
    step = Math.max(-1, Math.min(lines.length, step));
    if (step === lensStep) return;
    lensStep = step;
    lines.forEach((li, i) => { li.classList.toggle('is-on', i <= step); li.classList.toggle('is-now', i === step); });
    rows.forEach((r, i) => r.classList.toggle('is-on', i <= step));
  }

  /* ---------- header: the link for the section in view ---------- */
  const navLinks = $$('.site-head .nav a[href^="#"]');
  const sections = navLinks.map(a => document.querySelector(a.getAttribute('href'))).filter(Boolean);
  let current = null;
  function spy() {
    let cur = sections[0] || null;
    sections.forEach(s => { if (s.getBoundingClientRect().top < innerHeight * .38) cur = s; });
    if (cur === current) return;
    current = cur;
    navLinks.forEach(a => (cur && a.getAttribute('href') === '#' + cur.id) ? a.setAttribute('aria-current', 'true') : a.removeAttribute('aria-current'));
  }

  function frame() {
    if (hero && !reduce) hero.style.setProperty('--hp', Math.min(1, Math.max(0, scrollY / hero.offsetHeight)).toFixed(3));
    lens();
    spy();
  }
  let ticking = false;
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(() => { frame(); ticking = false; }); } }, { passive: true });
  addEventListener('resize', () => { measure(); lensStep = -2; frame(); }, { passive: true });
  measure(); frame();

  /* ---------- A: the upgrade tray. Picks light the hero chips and travel with the reader to the form ---------- */
  const tray = $('.pa-tray');
  if (tray) {
    const list = $('.pa-tray-list', tray), chips = $$('.pa-chips [data-chip]');
    const nameOf = mod => { const b = $(`.pa-pick[data-module="${mod}"]`); const card = b && b.closest('.pa-card'); return card ? ($('.pa-card-k', card) || $('h3', card)).textContent : mod; };
    let picked = $$('.pa-pick[aria-pressed="true"]').map(b => b.dataset.module);
    let pastHero = false, atForm = false, hideTimer = 0;
    const sync = () => {
      const show = picked.length > 0 && pastHero && !atForm;
      clearTimeout(hideTimer);
      if (show) { tray.hidden = false; requestAnimationFrame(() => tray.classList.add('is-on')); }
      else { tray.classList.remove('is-on'); hideTimer = setTimeout(() => { tray.hidden = true; }, 260); }
    };
    const render = () => {
      chips.forEach(c => c.classList.toggle('is-on', picked.includes(c.dataset.chip)));
      list.replaceChildren(...picked.map(m => { const s = document.createElement('span'); s.textContent = nameOf(m); return s; }));
      sync();
    };
    document.addEventListener('fq:modules', e => { picked = e.detail; render(); });
    const heroEl = $('.pa-hero'), contact = $('#contact');
    if (heroEl) new IntersectionObserver(([e]) => { pastHero = !e.isIntersecting && e.boundingClientRect.top < 0; sync(); }).observe(heroEl);
    if (contact) new IntersectionObserver(([e]) => { atForm = e.isIntersecting; sync(); }, { threshold: .05 }).observe(contact);
    render();
  }
})();
