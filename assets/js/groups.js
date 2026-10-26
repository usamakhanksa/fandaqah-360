/* Campaign D (hotel groups): the co-branded dossier. Loaded after site.js (language, attribution, CRM form) and v2.js
   (engine mount, header section highlight). Owns: hero depth, the group identity (brand studio), the pinned platform's
   scope walk, the two-step request and the welcome screen that mirrors it. */
(() => {
  const $ = (s, c = document) => c.querySelector(s), $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const root = document.documentElement, reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const KEY = 'fq360-group-id';

  /* ---------- the group identity: a name and a colour, worn wherever the group appears ---------- */
  const kit = $('#pd-kit'), nameIn = $('#pd-gname'), company = $('#f-company');
  let id = { n: '', c: '' };
  try { id = Object.assign(id, JSON.parse(localStorage.getItem(KEY)) || {}); } catch (e) {}
  const initials = s => s.trim().split(/\s+/).filter(Boolean).slice(0, 2).map(w => [...w][0]).join('').toUpperCase();
  const slug = s => (s.normalize('NFKD').replace(/[^\w\s-]/g, '').trim().toLowerCase().replace(/[\s_]+/g, '-').replace(/-+/g, '-').slice(0, 24)) || 'group';
  function wear(flash) {
    // the studio name wins; until one is set, the request form's group name stands in
    const name = (id.n || (company && company.value) || '').trim();
    root.classList.toggle('has-group', !!name);
    $$('.g-cus').forEach(el => { el.textContent = name; });
    $$('.g-mono').forEach(el => { el.textContent = initials(name); });
    $$('.g-slug').forEach(el => { el.textContent = slug(name); });
    if (id.c) root.style.setProperty('--gb', id.c); else root.style.removeProperty('--gb');
    if (flash && !reduce) { const w = $('.pd-window'); if (w) { w.classList.remove('pd-flash'); void w.offsetWidth; w.classList.add('pd-flash'); } }
  }
  const save = () => { try { (id.n || id.c) ? localStorage.setItem(KEY, JSON.stringify(id)) : localStorage.removeItem(KEY); } catch (e) {} };
  if (kit) {
    nameIn.value = id.n;
    if (id.c) { const r = $(`input[name="gcolour"][value="${id.c}"]`, kit); if (r) r.checked = true; }
    nameIn.addEventListener('input', () => { id.n = nameIn.value.slice(0, 32); save(); wear(); syncPortal(); });
    kit.addEventListener('change', e => { if (e.target.name === 'gcolour') { id.c = e.target.value === '#282639' ? '' : e.target.value; save(); wear(true); } });
    kit.addEventListener('submit', e => e.preventDefault());
    kit.addEventListener('reset', () => setTimeout(() => { id = { n: '', c: '' }; save(); wear(true); syncPortal(); }));
  }

  /* ---------- hero depth: one scroll value and the pointer, each plane at its own rate ---------- */
  const hero = $('.pd-hero'), art = $('.pd-art');
  if (art && !reduce && matchMedia('(hover: hover) and (pointer: fine)').matches) {
    art.addEventListener('pointermove', e => {
      const r = art.getBoundingClientRect();
      art.style.setProperty('--mx', ((e.clientX - r.left) / r.width - .5).toFixed(3));
      art.style.setProperty('--my', ((e.clientY - r.top) / r.height - .5).toFixed(3));
    });
    art.addEventListener('pointerleave', () => { art.style.setProperty('--mx', 0); art.style.setProperty('--my', 0); });
  }

  /* ---------- the platform (peak): scroll walks one dashboard from the group into each property ---------- */
  const act = $('.pd-platform'), stage = act && $('[data-sc-stage]', act), dash = $('.pd-dash');
  const tabs = $$('.pd-tabs [role="tab"]'), panel = $('#pd-panel');
  // all values illustrative, as labelled on the page
  const SCOPES = [
    { props: 12, of: 12, occ: 76, rep: 9, repOf: 12, bars: [64, 69, 72, 76, 83, 91, 77], flow: 1 },
    { props: 1, of: 12, occ: 81, rep: 1, repOf: 1, bars: [72, 75, 79, 80, 86, 93, 84], flow: 3 },
    { props: 1, of: 12, occ: 74, rep: 1, repOf: 1, bars: [60, 66, 70, 74, 79, 88, 73], flow: 2 },
    { props: 1, of: 12, occ: 72, rep: 0, repOf: 1, bars: [58, 63, 69, 71, 77, 85, 80], flow: 0 }
  ];
  let scope = -1;
  const pinned = () => !!stage && getComputedStyle(stage).position === 'sticky' && act.offsetHeight > innerHeight * 1.5;
  function tween(el, to) {
    const from = +el.textContent || 0;
    if (reduce || from === to) { el.textContent = to; return; }
    const t0 = performance.now();
    const step = now => { const k = Math.min(1, (now - t0) / 500); el.textContent = Math.round(from + (to - from) * (1 - Math.pow(1 - k, 3))); if (k < 1) requestAnimationFrame(step); };
    requestAnimationFrame(step);
  }
  function setScope(i) {
    if (!dash || i === scope) return;
    scope = i;
    const d = SCOPES[i];
    dash.dataset.scope = i;
    tabs.forEach((t, k) => { t.setAttribute('aria-selected', String(k === i)); t.tabIndex = k === i ? 0 : -1; });
    panel.setAttribute('aria-labelledby', tabs[i].id);
    tween($('[data-m="props"]', dash), d.props);
    tween($('[data-m="occ"]', dash), d.occ);
    tween($('[data-m="rep"]', dash), d.rep);
    $('[data-m="repOf"]', dash).textContent = '/ ' + d.repOf;
    $('[data-m="props"]', dash).nextElementSibling.textContent = '/ ' + d.of;
    // bars read from a 40% floor so the week's shape is visible, not just its level
    $$('.pd-bars i', dash).forEach((b, k) => b.style.setProperty('--v', Math.max(.08, (d.bars[k] - 40) / 60).toFixed(3)));
    $$('.pd-flow li', dash).forEach((li, k) => { li.classList.toggle('is-done', k < d.flow); li.classList.toggle('is-now', k === d.flow); });
  }
  // a scope chosen by hand: inside the pin, scroll to where that scope sits; in flow, just show it
  function go(i) {
    if (pinned()) {
      const top = act.getBoundingClientRect().top + scrollY, travel = act.offsetHeight - innerHeight;
      scrollTo({ top: top + travel * (i + .5) / SCOPES.length, behavior: reduce ? 'auto' : 'smooth' });
    } else setScope(i);
  }
  tabs.forEach((t, i) => t.addEventListener('click', () => go(i)));
  $('.pd-tabs')?.addEventListener('keydown', e => {
    const k = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[e.key];
    if (!k) return;
    e.preventDefault();
    const dir = root.dir === 'rtl' && (e.key === 'ArrowRight' || e.key === 'ArrowLeft') ? -k : k;
    const n = (Math.max(0, scope) + dir + SCOPES.length) % SCOPES.length;
    tabs[n].focus(); go(n);
  });
  // the hero's pins open their property in the platform
  $$('.pd-pin').forEach(p => p.addEventListener('click', e => { e.preventDefault(); const i = +p.dataset.scope; if (pinned()) go(i); else { setScope(i); act.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth' }); } }));

  function frame() {
    if (hero && !reduce) hero.style.setProperty('--hp', Math.min(1, Math.max(0, scrollY / hero.offsetHeight)).toFixed(3));
    if (act && pinned()) {
      const travel = act.offsetHeight - innerHeight;
      const p = travel > 0 ? Math.min(1, Math.max(0, -act.getBoundingClientRect().top / travel)) : 0;
      dash.style.setProperty('--pd-rail', p.toFixed(3));
      setScope(Math.min(SCOPES.length - 1, Math.floor(p * SCOPES.length)));
    } else if (dash) dash.style.setProperty('--pd-rail', ((Math.max(0, scope)) / (SCOPES.length - 1)).toFixed(3));
  }
  let ticking = false;
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(() => { frame(); ticking = false; }); } }, { passive: true });
  addEventListener('resize', () => frame(), { passive: true });
  setScope(0); frame();

  /* ---------- the request: two steps, and the welcome screen that greets the group as it is filled ---------- */
  const form = $('#lead-form'), done = $('#lead-done'), portal = $('.pd-portal'), steps = $$('.pd-steps li');
  if (!form) return;
  const val = n => (form.elements[n] && form.elements[n].value.trim()) || '';
  const optText = n => { const el = form.elements[n]; return el && el.value ? el.options[el.selectedIndex].text : ''; };
  function showStep(n, focus) {
    form.dataset.step = n;
    steps.forEach((li, k) => { li.classList.toggle('is-on', k === n - 1); li.classList.toggle('is-done', k < n - 1); });
    if (focus) $(n === 1 ? '#f-company' : '#f-name').focus();
  }
  function groupOk() {
    const ok = val('company').length >= 2, err = $('#f-company-err');
    company.setAttribute('aria-invalid', String(!ok)); err.hidden = ok;
    return ok;
  }
  $('.pd-next', form).addEventListener('click', () => { if (groupOk()) showStep(2, true); else company.focus(); });
  $('.pd-back', form).addEventListener('click', () => showStep(1, true));
  // Enter on step one moves on rather than submitting half a request
  $('.pd-step[data-step="1"]', form).addEventListener('keydown', e => { if (e.key === 'Enter' && e.target.tagName === 'INPUT') { e.preventDefault(); $('.pd-next', form).click(); } });
  // runs before site.js's submit handler: a missing group name sends the reader back to step one, where site.js can focus it
  form.addEventListener('submit', () => { if (val('company').length < 2) showStep(1); }, true);

  const written = {};
  const put = (k, text, quiet) => {
    if (written[k] === text) return;
    const dd = $(`[data-w="${k}"]`, portal), first = !written[k] && text;
    written[k] = text; $('.v', dd).textContent = text; dd.classList.toggle('is-set', !!text);
    if (first && !quiet && !reduce) { dd.parentElement.classList.remove('is-new'); void dd.offsetWidth; dd.parentElement.classList.add('is-new'); }
  };
  function syncPortal(quiet) {
    if (!portal) return;
    const name = val('name');
    portal.classList.toggle('is-named', !!name);
    $('.w-name', portal).textContent = name;
    put('city', val('city'), quiet); put('count', optText('property_count'), quiet); put('structure', optText('ownership'), quiet);
    if (portal.dataset.state !== '2') portal.dataset.state = val('company').length >= 2 && name.length >= 2 && val('phone') && val('email') ? '1' : '0';
  }
  ['input', 'change'].forEach(t => form.addEventListener(t, e => { if (e.target === company && !id.n) wear(); syncPortal(); }));
  $$('[data-lang]').forEach(b => b.addEventListener('click', () => setTimeout(() => { Object.keys(written).forEach(k => delete written[k]); syncPortal(true); })));
  new MutationObserver(() => {
    if (done.hidden) return;
    steps.forEach(li => { li.classList.remove('is-on'); li.classList.add('is-done'); });
    portal.dataset.state = '2';
    $('.pd-portal-ref', portal).textContent = $('.ref b', done).textContent;
  }).observe(done, { attributes: true, attributeFilter: ['hidden'] });

  showStep(1); wear(); syncPortal(true);
})();
