/* =====================================================================
   Fandaqah NTMP landing page — behaviour
   ===================================================================== */
(function () {
  "use strict";

  var CFG = window.FQ_CONFIG || {};
  var I18N = window.FQ_I18N;
  var doc = document.documentElement;
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var store = {
    get: function (k) { try { return JSON.parse(localStorage.getItem(k)); } catch (e) { return null; } },
    set: function (k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} },
    del: function (k) { try { localStorage.removeItem(k); } catch (e) {} }
  };
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var lang = "en";

  function t(key) { return (I18N.ui[lang] && I18N.ui[lang][key]) || I18N.ui.en[key] || key; }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]; }); }

  /* ---------------------------------------------------------------
     Toasts
     --------------------------------------------------------------- */
  function toast(msg, iconId) {
    var box = $("#toasts");
    var el = document.createElement("div");
    el.className = "toast";
    el.innerHTML = '<svg class="i"><use href="#' + (iconId || "i-check") + '"/></svg><span>' + esc(msg) + "</span>";
    box.appendChild(el);
    setTimeout(function () { el.classList.add("is-out"); setTimeout(function () { el.remove(); }, 320); }, 3200);
  }

  /* ---------------------------------------------------------------
     Config wiring: links, socials, WhatsApp
     --------------------------------------------------------------- */
  function waLink(text) {
    var n = (CFG.contact && CFG.contact.whatsapp || "").replace(/\D/g, "");
    if (!n) return "";
    return "https://wa.me/" + n + (text ? "?text=" + encodeURIComponent(text) : "");
  }
  function applyConfig() {
    $$("[data-link]").forEach(function (a) {
      var url = CFG.links && CFG.links[a.dataset.link];
      if (url) { a.href = url; a.target = "_blank"; a.rel = "noopener"; }
    });
    var social = CFG.social || {};
    var labels = { linkedin: "LinkedIn", youtube: "YouTube", x: "X", instagram: "Instagram", facebook: "Facebook" };
    var order = ["linkedin", "youtube", "x", "instagram"];
    $("#socialLinks").innerHTML = order.filter(function (k) { return social[k]; }).map(function (k) {
      return '<a href="' + esc(social[k]) + '" target="_blank" rel="noopener" aria-label="Fandaqah on ' + labels[k] + '"><svg class="i"><use href="#s-' + k + '"/></svg></a>';
    }).join("");
    var fab = $("#fabWa");
    if (waLink()) { fab.hidden = false; updateWaFab(); }
    $("#year").textContent = new Date().getFullYear();
  }
  function updateWaFab() {
    var g = CFG.contact && CFG.contact.whatsappGreeting;
    $("#fabWa").href = waLink(g ? g[lang] : "");
  }

  /* ---------------------------------------------------------------
     Language
     --------------------------------------------------------------- */
  var originals = new Map();
  function captureOriginals() {
    $$("[data-i18n]").forEach(function (el) { originals.set(el, { text: el.textContent }); });
    $$("[data-i18n-html]").forEach(function (el) { originals.set(el, { html: el.innerHTML }); });
    $$("[data-i18n-attr]").forEach(function (el) {
      var o = originals.get(el) || {};
      o.attrs = {};
      el.dataset.i18nAttr.split(";").forEach(function (pair) {
        var name = pair.split(":")[0];
        o.attrs[name] = el.getAttribute(name);
      });
      originals.set(el, o);
    });
  }
  function setLang(next, announce) {
    lang = next === "ar" ? "ar" : "en";
    var ar = I18N.ar;
    doc.lang = lang; doc.dir = lang === "ar" ? "rtl" : "ltr";
    $$("[data-i18n]").forEach(function (el) {
      var k = el.dataset.i18n;
      el.textContent = lang === "ar" && ar[k] ? ar[k] : originals.get(el).text;
    });
    $$("[data-i18n-html]").forEach(function (el) {
      var k = el.dataset.i18nHtml;
      el.innerHTML = lang === "ar" && ar[k] ? ar[k] : originals.get(el).html;
    });
    $$("[data-i18n-attr]").forEach(function (el) {
      var o = originals.get(el);
      el.dataset.i18nAttr.split(";").forEach(function (pair) {
        var bits = pair.split(":"), name = bits[0], k = bits[1];
        el.setAttribute(name, lang === "ar" && ar[k] ? ar[k] : o.attrs[name]);
      });
    });
    $$("[data-lang]").forEach(function (b) { b.setAttribute("aria-pressed", String(b.dataset.lang === lang)); });
    document.title = lang === "ar" ? "الربط مع منصة الرصد السياحي للفنادق السعودية | فندقة" : "NTMP Integration for Saudi Hotels | Fandaqah";
    store.set("fq_lang", lang);
    // Re-render dynamic parts
    renderStep(activeStep, false);
    renderReadinessLabels();
    renderSync();
    buildSearchIndex();
    updateWaFab();
    if (window.FQDemo) window.FQDemo.setLang(lang);
    $$(".field__err").forEach(function (e) { var f = e.closest(".field, .consent"); if (f) validateField(f); });
    if (announce) toast(t("toast.lang"), "i-globe");
  }
  function initLang() {
    var q = new URLSearchParams(location.search).get("lang");
    var saved = store.get("fq_lang");
    var pref = CFG.defaultLanguage || "en";
    if (pref === "auto") pref = (navigator.language || "en").toLowerCase().indexOf("ar") === 0 ? "ar" : "en";
    setLang(q || saved || pref, false);
    $$("[data-lang]").forEach(function (b) {
      b.addEventListener("click", function () { if (b.dataset.lang !== lang) setLang(b.dataset.lang, true); });
    });
  }

  /* ---------------------------------------------------------------
     Header, mega menus, drawer
     --------------------------------------------------------------- */
  function initHeader() {
    var header = $(".site-header"), fabTop = $("#fabTop");
    var onScroll = function () {
      var y = window.scrollY;
      header.classList.toggle("is-scrolled", y > 8);
      fabTop.classList.toggle("is-visible", y > 700);
    };
    window.addEventListener("scroll", onScroll, { passive: true }); onScroll();
    fabTop.addEventListener("click", function () { window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" }); });

    var menus = $$(".has-menu");
    var closeAll = function (except) {
      menus.forEach(function (m) { if (m !== except) { m.classList.remove("is-open"); $(".nav-link", m).setAttribute("aria-expanded", "false"); } });
    };
    menus.forEach(function (m) {
      var btn = $(".nav-link", m), timer;
      var open = function () { clearTimeout(timer); closeAll(m); m.classList.add("is-open"); btn.setAttribute("aria-expanded", "true"); };
      var close = function () { m.classList.remove("is-open"); btn.setAttribute("aria-expanded", "false"); };
      btn.addEventListener("click", function () { m.classList.contains("is-open") ? close() : open(); });
      m.addEventListener("pointerenter", function (e) { if (e.pointerType === "mouse") open(); });
      m.addEventListener("pointerleave", function (e) { if (e.pointerType === "mouse") timer = setTimeout(close, 160); });
      m.addEventListener("keydown", function (e) { if (e.key === "Escape") { close(); btn.focus(); } });
      $$(".mega a", m).forEach(function (a) { a.addEventListener("click", close); });
    });
    document.addEventListener("click", function (e) { if (!e.target.closest(".has-menu")) closeAll(); });

    // Drawer
    var drawer = $("#drawer"), menuBtn = $(".menu-btn");
    menuBtn.addEventListener("click", function () { openLayer(drawer, menuBtn); menuBtn.setAttribute("aria-expanded", "true"); });
    $$(".drawer__nav a, .drawer [data-action]", drawer).forEach(function (a) { a.addEventListener("click", function () { closeLayer(drawer); }); });
    drawer.addEventListener("click", function (e) { if (e.target === drawer) closeLayer(drawer); });
  }

  /* ---------------------------------------------------------------
     Layers: modals, search, drawer (focus trap + scroll lock)
     --------------------------------------------------------------- */
  var layerStack = [];
  function focusables(root) {
    return $$('a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])', root)
      .filter(function (el) { return el.offsetParent !== null || el === document.activeElement; });
  }
  function openLayer(el, opener) {
    el.hidden = false;
    layerStack.push({ el: el, opener: opener || document.activeElement });
    document.body.style.overflow = "hidden";
    var f = focusables(el);
    var target = $("input", el) || f[0];
    setTimeout(function () { target && target.focus(); }, 30);
  }
  function closeLayer(el) {
    var i = layerStack.findIndex(function (l) { return l.el === el; });
    if (i < 0) return;
    var item = layerStack.splice(i, 1)[0];
    el.hidden = true;
    if (el.id === "demo" && window.FQDemo) window.FQDemo.close();
    if (el.id === "drawer") $(".menu-btn").setAttribute("aria-expanded", "false");
    if (!layerStack.length) document.body.style.overflow = "";
    if (item.opener && item.opener.focus) item.opener.focus();
  }
  function initLayers() {
    document.addEventListener("keydown", function (e) {
      var top = layerStack[layerStack.length - 1];
      if (!top) return;
      if (e.key === "Escape") { e.preventDefault(); closeLayer(top.el); return; }
      if (e.key === "Tab") {
        var f = focusables(top.el); if (!f.length) return;
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
    $$("[data-close]").forEach(function (b) { b.addEventListener("click", function () { closeLayer(document.getElementById(b.dataset.close)); }); });
    $$(".modal, .search").forEach(function (m) { m.addEventListener("mousedown", function (e) { if (e.target === m) closeLayer(m); }); });
  }

  /* ---------------------------------------------------------------
     Actions (CTAs everywhere)
     --------------------------------------------------------------- */
  function goToForm(focusFirst) {
    var el = $("#start");
    el.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "start" });
    if (focusFirst) setTimeout(function () { var i = $('#leadForm .fstep:not([hidden]) input, #leadForm .fstep:not([hidden]) select'); i && i.focus({ preventScroll: true }); }, reduceMotion ? 0 : 650);
  }
  function initActions() {
    document.addEventListener("click", function (e) {
      var a = e.target.closest("[data-action]");
      if (!a) return;
      var act = a.dataset.action;
      if (act === "demo") { e.preventDefault(); openLayer($("#demo"), a); window.FQDemo.open(lang); }
      if (act === "book" || act === "demo-request") { e.preventDefault(); if (act === "demo-request") tickChip("booking"); goToForm(true); }
      if (act === "book-score") { e.preventDefault(); attachScore(); goToForm(true); }
      if (act === "talk") {
        var g = CFG.contact && CFG.contact.whatsappGreeting;
        var link = waLink(g ? g[lang] : "");
        if (link) { e.preventDefault(); window.open(link, "_blank", "noopener"); } else { e.preventDefault(); goToForm(true); }
      }
      if (act === "search") { e.preventDefault(); openSearch(a); }
    });
  }
  function tickChip(val) { var c = $('#leadForm input[name="focus"][value="' + val + '"]'); if (c && !c.checked) { c.checked = true; saveDraft(); } }

  /* ---------------------------------------------------------------
     Hero: entrance + live NTMP sync card
     --------------------------------------------------------------- */
  var sync = { i: 0, phase: 0, timer: 0, running: false };
  var BOOKINGS = [
    { ref: "RSV-24817", room: "1204", nights: 2, flow: "ok" },
    { ref: "RSV-24821", room: "1510", nights: 3, flow: "fix" },
    { ref: "RSV-24823", room: "0312", nights: 1, flow: "ok" },
    { ref: "RSV-24826", room: "0905", nights: 2, flow: "ok" }
  ];
  // phase: 0 recorded, 1 checking, 2 sending, 3 accepted | rejected, 4 fixing, 5 accepted-after-fix
  function renderSync() {
    var b = BOOKINGS[sync.i];
    if (!b || !$("#syncRef")) return;
    $("#syncRef").textContent = b.ref;
    $("#syncMeta").textContent = t("sync.room") + " " + b.room + (lang === "ar" ? "، " : ", ") + b.nights + " " + (b.nights === 1 ? t("sync.night") : t("sync.nights"));
    var p = sync.phase, rejected = b.flow === "fix" && (p === 3);
    var steps = $$("#syncTrack li");
    steps.forEach(function (li, n) {
      li.className = "";
      var doneUpTo = p === 0 ? 0 : p === 1 ? 1 : p === 2 ? 2 : 3;
      if (n <= doneUpTo) li.classList.add("is-done");
      if (n === 3 && p >= 3) li.classList.add(rejected ? "is-err" : (p === 4 ? "is-done" : "is-ok"));
      if (n === 3 && p === 4) { li.classList.remove("is-done", "is-ok", "is-err"); }
    });
    var pill = $("#syncPill"), msg = $("#syncMsg");
    var map = {
      0: ["pending", "sync.recorded", ""], 1: ["pending", "sync.checking", ""], 2: ["info", "sync.sending", ""],
      3: rejected ? ["err", "sync.rejected", "sync.msg.err"] : ["ok", "sync.accepted", "sync.msg.ok"],
      4: ["pending", "sync.fixing", "sync.msg.fix"], 5: ["ok", "sync.accepted", "sync.msg.ok"]
    }[p];
    pill.className = "pill pill--" + map[0];
    pill.textContent = t(map[1]);
    msg.textContent = map[2] ? t(map[2]) : "";
    var now = new Date();
    try { $("#syncTime").textContent = now.toLocaleTimeString(lang === "ar" ? "ar-SA-u-nu-latn" : "en-GB", { hour: "2-digit", minute: "2-digit", timeZone: "Asia/Riyadh" }); }
    catch (e) { $("#syncTime").textContent = now.toTimeString().slice(0, 5); }
  }
  function syncStep() {
    var b = BOOKINGS[sync.i], delay;
    if (sync.phase === 3 && b.flow === "ok") { nextBooking(); return; }
    if (sync.phase === 5) { nextBooking(); return; }
    if (sync.phase === 3 && b.flow === "fix") { sync.phase = 4; delay = 1700; }
    else if (sync.phase === 4) { sync.phase = 5; delay = 3200; }
    else { sync.phase += 1; delay = sync.phase === 3 ? (b.flow === "fix" ? 2200 : 3400) : 1000; }
    renderSync();
    sync.timer = setTimeout(syncStep, delay);
  }
  function nextBooking() {
    sync.i = (sync.i + 1) % BOOKINGS.length; sync.phase = 0;
    var card = $("#syncCard"); card.classList.remove("is-swap"); void card.offsetWidth; card.classList.add("is-swap");
    renderSync();
    sync.timer = setTimeout(syncStep, 900);
  }
  function startSync() { if (sync.running) return; sync.running = true; sync.timer = setTimeout(syncStep, 900); }
  function stopSync() { sync.running = false; clearTimeout(sync.timer); }
  function initHero() {
    var hero = $(".hero");
    var img = $(".hero__photo img");
    var go = function () { requestAnimationFrame(function () { hero.classList.add("is-loaded"); }); };
    if (img.complete) go(); else { img.addEventListener("load", go); setTimeout(go, 1200); }
    renderSync();
    setTimeout(function () {
      if ("IntersectionObserver" in window) {
        new IntersectionObserver(function (en) { en[0].isIntersecting && !document.hidden ? startSync() : stopSync(); }, { threshold: .2 }).observe(hero);
      } else startSync();
    }, reduceMotion ? 0 : 1600);
    document.addEventListener("visibilitychange", function () { document.hidden ? stopSync() : startSync(); });
  }

  /* ---------------------------------------------------------------
     How it works: tabs
     --------------------------------------------------------------- */
  var activeStep = 0;
  function renderStep(i, animate) {
    activeStep = i;
    var data = I18N.steps[lang][i];
    var tabs = $$(".step");
    tabs.forEach(function (tab, n) {
      var on = n === i;
      tab.classList.toggle("is-active", on);
      tab.classList.toggle("is-past", n < i);
      tab.setAttribute("aria-selected", String(on));
      tab.tabIndex = on ? 0 : -1;
    });
    var panel = $("#stepPanel");
    panel.setAttribute("aria-labelledby", "tab-" + i);
    $("#stepCount").textContent = (i + 1) + " / 5";
    $("#stepTitle").textContent = data.t;
    $("#stepText").textContent = data.d;
    $("#stepSees").setAttribute("aria-label", t("sees"));
    $("#stepSees").innerHTML = data.s.map(function (s) { return '<li><svg class="i"><use href="#i-check"/></svg><span>' + esc(s) + "</span></li>"; }).join("");
    var fill = $("#stepsFill");
    if (fill.dataset.armed === "1") { fill.style.width = (i / 4 * 100) + "%"; fill.parentElement.style.setProperty("--fill", (i / 4 * 100) + "%"); }
    if (animate) { panel.classList.remove("is-swap"); void panel.offsetWidth; panel.classList.add("is-swap"); }
  }
  function initSteps() {
    var tabs = $$(".step");
    tabs.forEach(function (tab, n) {
      tab.addEventListener("click", function () { renderStep(n, true); });
      tab.addEventListener("keydown", function (e) {
        var k = e.key, next = null;
        if (k === "ArrowRight" || k === "ArrowDown") next = (n + 1) % 5;
        if (k === "ArrowLeft" || k === "ArrowUp") next = (n + 4) % 5;
        if (k === "Home") next = 0; if (k === "End") next = 4;
        if (next !== null) { e.preventDefault(); renderStep(next, true); tabs[next].focus(); }
      });
    });
    $$("[data-step-nav]").forEach(function (b) {
      b.addEventListener("click", function () { var d = +b.dataset.stepNav; renderStep((activeStep + d + 5) % 5, true); });
    });
    var fill = $("#stepsFill");
    var arm = function () { fill.dataset.armed = "1"; renderStep(activeStep, false); };
    if ("IntersectionObserver" in window) {
      var io = new IntersectionObserver(function (en) { if (en[0].isIntersecting) { arm(); io.disconnect(); } }, { threshold: .5 });
      io.observe($(".steps"));
    } else arm();
  }

  /* ---------------------------------------------------------------
     Showcase lightbox
     --------------------------------------------------------------- */
  var lbIndex = 0;
  function showScreen(i) {
    var screens = $$(".screen");
    lbIndex = (i + screens.length) % screens.length;
    var fig = screens[lbIndex];
    $("#lbTitle").textContent = $("figcaption", fig).textContent + "  (" + (lbIndex + 1) + " " + t("lb.of") + " " + screens.length + ")";
    var clone = $(".ui", fig).cloneNode(true);
    $("#lbBody").innerHTML = ""; $("#lbBody").appendChild(clone);
  }
  function initShowcase() {
    $$(".screen").forEach(function (fig, i) {
      var open = function () { showScreen(i); openLayer($("#lightbox"), fig); };
      fig.addEventListener("click", open);
      fig.addEventListener("keydown", function (e) { if (e.key === "Enter" || e.key === " ") { e.preventDefault(); open(); } });
    });
    $$("[data-lb]").forEach(function (b) { b.addEventListener("click", function () { showScreen(lbIndex + (+b.dataset.lb)); }); });
    $("#lightbox").addEventListener("keydown", function (e) {
      var dir = lang === "ar" ? -1 : 1;
      if (e.key === "ArrowRight") showScreen(lbIndex + dir);
      if (e.key === "ArrowLeft") showScreen(lbIndex - dir);
    });
  }

  /* ---------------------------------------------------------------
     Readiness check
     --------------------------------------------------------------- */
  var answers = store.get("fq_ready") || {};
  var VAL = { yes: 1, unsure: .4, no: 0 };
  function initReadiness() {
    var list = $("#readyQs");
    list.innerHTML = I18N.readiness.map(function (q, i) {
      var opts = ["yes", "unsure", "no"].map(function (v) {
        return '<label><input type="radio" name="rq' + i + '" value="' + v + '"' + (answers[i] === v ? " checked" : "") + '><span data-rq-label="' + v + '"></span></label>';
      }).join("");
      return '<li class="rq" data-q="' + i + '"><span class="rq__n">' + (i + 1) + '</span><span class="rq__q" id="rq' + i + 'q"></span><span class="seg" role="radiogroup" aria-labelledby="rq' + i + 'q">' + opts + "</span></li>";
    }).join("");
    list.addEventListener("change", function (e) {
      var li = e.target.closest(".rq"); if (!li) return;
      answers[li.dataset.q] = e.target.value;
      store.set("fq_ready", answers);
      updateReadiness();
    });
    renderReadinessLabels();
  }
  function renderReadinessLabels() {
    $$(".rq").forEach(function (li, i) {
      $(".rq__q", li).textContent = I18N.readiness[i][lang];
      $$("[data-rq-label]", li).forEach(function (s) { s.textContent = t("ready." + s.dataset.rqLabel); });
    });
    updateReadiness();
  }
  function readinessScore() {
    var keys = Object.keys(answers);
    if (!keys.length) return null;
    var total = I18N.readiness.length, sum = 0;
    keys.forEach(function (k) { sum += VAL[answers[k]] || 0; });
    return { pct: Math.round(sum / total * 100), answered: keys.length, total: total };
  }
  function updateReadiness() {
    if (!$("#readyQs")) return;
    $$(".rq").forEach(function (li, i) {
      li.classList.remove("is-yes", "is-no", "is-unsure", "is-answered");
      if (answers[i]) li.classList.add("is-" + answers[i], "is-answered");
    });
    var s = readinessScore(), circ = 314.16, gauge = $("#gaugeVal");
    var pct = s ? s.pct : 0;
    gauge.style.strokeDashoffset = circ - circ * pct / 100;
    gauge.style.stroke = pct >= 75 ? "var(--ok)" : pct >= 45 ? "#E8964D" : "var(--coral)";
    animateNumber($("#gaugeNum"), pct);
    var level = $("#readyLevel");
    if (!s) { level.textContent = lang === "ar" ? I18N.ar["ready.empty"] : originals.get(level).text; }
    else level.textContent = t(pct >= 75 ? "ready.level.high" : pct >= 45 ? "ready.level.mid" : "ready.level.low");
    var todo = $("#readyTodo");
    if (!s) { todo.innerHTML = ""; return; }
    var gaps = I18N.readiness.map(function (q, i) { return answers[i] && answers[i] !== "yes" ? q.fix[lang] : null; }).filter(Boolean).slice(0, 3);
    if (!gaps.length && s.answered === s.total) todo.innerHTML = '<li class="is-good">' + esc(t("ready.allgood")) + "</li>";
    else todo.innerHTML = gaps.map(function (g, n) { return '<li style="animation-delay:' + (n * 70) + 'ms">' + esc(g) + "</li>"; }).join("");
  }
  function animateNumber(el, to) {
    var from = +el.textContent || 0;
    if (reduceMotion || from === to) { el.textContent = to; return; }
    var start = performance.now(), dur = 700;
    (function step(now) {
      var p = Math.min(1, (now - start) / dur), e = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(from + (to - from) * e);
      if (p < 1) requestAnimationFrame(step);
    })(start);
  }
  function attachScore() {
    var s = readinessScore();
    if (!s) return;
    $('#leadForm input[name="readinessScore"]').value = s.pct + "% (" + s.answered + "/" + s.total + ")";
    toast(t("toast.score"), "i-gauge");
    saveDraft();
  }

  /* ---------------------------------------------------------------
     Lead form
     --------------------------------------------------------------- */
  var form, FREE = /@(gmail|hotmail|outlook|live|yahoo|icloud|aol|proton(mail)?)\./i;
  function fieldMsg(field, msg, isHint) {
    var old = $(".field__err", field); if (old) old.remove();
    if (!msg) return;
    var p = document.createElement("span");
    p.className = "field__err";
    if (isHint) { p.style.color = "var(--muted)"; p.style.fontWeight = "500"; }
    p.textContent = msg;
    field.appendChild(p);
  }
  function normalizePhone(v) {
    var d = v.replace(/\D/g, "");
    if (d.indexOf("966") === 0) d = d.slice(3);
    if (d.indexOf("0") === 0) d = d.slice(1);
    return d;
  }
  function validateField(field) {
    var input = $("input, select, textarea", field);
    if (!input) return true;
    var name = input.name, v = (input.value || "").trim(), err = "", hint = "";
    if (field.classList.contains("consent")) {
      var ok = input.checked;
      field.classList.toggle("is-invalid", !ok);
      fieldMsg(field, ok ? "" : t("err.consent"));
      return ok;
    }
    if (input.required && !v) err = input.tagName === "SELECT" ? t("err.select") : t("err.required");
    else if (name === "fullName" && v && v.length < 3) err = t("err.name");
    else if (name === "email" && v && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) err = t("err.email");
    else if (name === "whatsapp" && v && !/^5\d{8}$/.test(normalizePhone(v))) err = t("err.phone");
    if (!err && name === "email" && FREE.test(v)) hint = t("err.emailFree");
    field.classList.toggle("is-invalid", !!err);
    field.classList.toggle("is-valid", !err && !!v && input.required);
    fieldMsg(field, err || hint, !err && !!hint);
    input.setAttribute("aria-invalid", err ? "true" : "false");
    return !err;
  }
  function validateStep(n) {
    var panel = $('[data-fstep-panel="' + n + '"]', form);
    var fields = $$(".field, .consent", panel).filter(function (f) { var i = $("input, select, textarea", f); return i && (i.required); });
    var firstBad = null;
    fields.forEach(function (f) { if (!validateField(f) && !firstBad) firstBad = f; });
    if (firstBad) { $("input, select, textarea", firstBad).focus(); toast(t("err.fixFields"), "i-info"); }
    return !firstBad;
  }
  function gotoFormStep(n) {
    $$(".fstep", form).forEach(function (p) {
      var on = +p.dataset.fstepPanel === n;
      p.hidden = !on; p.classList.toggle("is-active", on);
    });
    $$(".form-steps li", form).forEach(function (li) {
      var s = +li.dataset.fstep;
      li.classList.toggle("is-current", s === n); li.classList.toggle("is-done", s < n);
      li.setAttribute("aria-current", s === n ? "step" : "false");
    });
    var first = $('[data-fstep-panel="' + n + '"] input, [data-fstep-panel="' + n + '"] select', form);
    first && first.focus({ preventScroll: true });
    var top = form.getBoundingClientRect().top;
    if (top < 0 || top > window.innerHeight * .6) form.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "start" });
  }
  function formData() {
    var d = {};
    $$("input, select, textarea", form).forEach(function (el) {
      if (!el.name) return;
      if (el.type === "checkbox" && el.name === "focus") { (d.focus = d.focus || []); if (el.checked) d.focus.push(el.value); }
      else if (el.type === "checkbox") d[el.name] = el.checked;
      else d[el.name] = el.value.trim();
    });
    return d;
  }
  var draftTimer;
  function saveDraft() {
    clearTimeout(draftTimer);
    draftTimer = setTimeout(function () { var d = formData(); delete d.consent; store.set("fq_draft", d); }, 300);
  }
  function restoreDraft() {
    var d = store.get("fq_draft"); if (!d) return;
    var any = false;
    Object.keys(d).forEach(function (k) {
      if (k === "focus") { (d.focus || []).forEach(function (v) { var c = $('input[name="focus"][value="' + v + '"]', form); if (c) { c.checked = true; any = true; } }); return; }
      var el = form.elements[k];
      if (el && d[k] && el.type !== "checkbox") { el.value = d[k]; any = any || el.type !== "hidden"; }
    });
    if (any) setTimeout(function () { toast(t("toast.draft"), "i-info"); }, 2200);
  }
  function makeRef() {
    var d = new Date();
    var ymd = String(d.getFullYear()).slice(2) + String(d.getMonth() + 1).padStart(2, "0") + String(d.getDate()).padStart(2, "0");
    return "FQ-" + ymd + "-" + Math.random().toString(36).slice(2, 6).toUpperCase();
  }
  function leadSummary(d, ref) {
    var L = lang === "ar";
    var phone = "+966 " + normalizePhone(d.whatsapp);
    var lines = L ? [
      I18N.ui.ar["lead.subject"] + " (" + ref + ")",
      "الاسم: " + d.fullName, "المنشأة: " + d.property + " / " + d.city, "عدد الغرف: " + d.roomCount,
      "واتساب: " + phone, "البريد: " + d.email
    ] : [
      I18N.ui.en["lead.subject"] + " (" + ref + ")",
      "Name: " + d.fullName, "Property: " + d.property + ", " + d.city, "Rooms: " + d.roomCount,
      "WhatsApp: " + phone, "Email: " + d.email
    ];
    if (d.readinessScore) lines.push((L ? "نتيجة الجاهزية: " : "Readiness score: ") + d.readinessScore);
    if (d.notes) lines.push((L ? "ملاحظات: " : "Notes: ") + d.notes);
    return lines.join("\n");
  }
  function showDone(d, ref, delivered) {
    $$(".fstep", form).forEach(function (p) { p.hidden = true; });
    $(".form-steps", form).hidden = true; $(".form-note", form).hidden = true;
    var done = $(".form-done", form); done.hidden = false;
    $("#leadRef").textContent = ref;
    var text = $('[data-i18n="done.text"]', done);
    if (!delivered && waLink()) {
      text.textContent = lang === "ar" ? "خطوة أخيرة: أرسل طلبك عبر واتساب ليصل إلى فريقنا مباشرة." : "One last step: send your request on WhatsApp so it reaches our team directly.";
    }
    var summary = leadSummary(d, ref), acts = [];
    if (waLink()) acts.push('<a class="btn btn--wa" target="_blank" rel="noopener" href="' + waLink(summary) + '"><svg class="i"><use href="#i-whatsapp"/></svg><span>' + esc(t("done.wa")) + "</span></a>");
    if (CFG.contact && CFG.contact.email) acts.push('<a class="btn btn--ghost" href="mailto:' + esc(CFG.contact.email) + "?subject=" + encodeURIComponent(t("lead.subject") + " " + ref) + "&body=" + encodeURIComponent(summary) + '"><svg class="i"><use href="#i-mail"/></svg><span>' + esc(t("done.mail")) + "</span></a>");
    acts.push('<button class="btn btn--ghost" type="button" data-copyref><svg class="i"><use href="#i-check"/></svg><span>' + esc(t("done.copy")) + "</span></button>");
    $("#doneActions").innerHTML = acts.join("");
    var cp = $("[data-copyref]", form);
    cp.addEventListener("click", function () {
      (navigator.clipboard ? navigator.clipboard.writeText(ref) : Promise.reject()).then(function () { toast(t("toast.copied")); }, function () { toast(ref); });
    });
    done.focus();
  }
  function initForm() {
    form = $("#leadForm");
    restoreDraft();
    form.addEventListener("input", function (e) {
      var f = e.target.closest(".field, .consent");
      if (e.target.name === "whatsapp") {
        var d = normalizePhone(e.target.value).slice(0, 9);
        var pretty = d.replace(/^(\d{2})(\d{0,3})(\d{0,4}).*/, function (_, a, b, c) { return [a, b, c].filter(Boolean).join(" "); });
        if (e.target.value !== pretty && e.inputType !== "deleteContentBackward") e.target.value = pretty;
      }
      if (f && f.classList.contains("is-invalid")) validateField(f);
      saveDraft();
    });
    form.addEventListener("change", function (e) { var f = e.target.closest(".field, .consent"); if (f && e.target.required) validateField(f); saveDraft(); });
    form.addEventListener("focusout", function (e) {
      var f = e.target.closest(".field"); if (f && e.target.value && e.target.required) validateField(f);
    });
    $("[data-fnext]", form).addEventListener("click", function () { if (validateStep(1)) gotoFormStep(2); });
    $("[data-fback]", form).addEventListener("click", function () { gotoFormStep(1); });
    $("[data-freset]", form).addEventListener("click", function () {
      form.reset(); store.del("fq_draft");
      $$(".field, .consent", form).forEach(function (f) { f.classList.remove("is-invalid", "is-valid"); fieldMsg(f, ""); });
      $(".form-done", form).hidden = true; $(".form-steps", form).hidden = false; $(".form-note", form).hidden = false;
      var dt = $('[data-i18n="done.text"]', form); dt.textContent = lang === "ar" ? I18N.ar["done.text"] : originals.get(dt).text;
      gotoFormStep(1);
    });
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!validateStep(1)) { gotoFormStep(1); validateStep(1); return; }
      if (!validateStep(2)) return;
      var d = formData(), ref = makeRef();
      d.whatsapp = "+966" + normalizePhone(d.whatsapp);
      d.reference = ref; d.language = lang; d.submittedAt = new Date().toISOString(); d.page = location.href; d.source = "ntmp-landing";
      var btn = $('button[type="submit"]', form);
      btn.classList.add("is-loading"); btn.disabled = true;
      var endpoint = CFG.form && CFG.form.endpoint;
      var finish = function (delivered) {
        btn.classList.remove("is-loading"); btn.disabled = false;
        store.del("fq_draft");
        if (delivered && CFG.form.redirectOnSuccess) { location.href = CFG.form.redirectOnSuccess; return; }
        showDone(d, ref, delivered);
      };
      if (!endpoint) { setTimeout(function () { finish(false); }, reduceMotion ? 0 : 700); return; }
      var headers = Object.assign({ "Content-Type": "application/json", "Accept": "application/json" }, CFG.form.headers || {});
      fetch(endpoint, { method: "POST", headers: headers, body: JSON.stringify(d) })
        .then(function (r) { if (!r.ok) throw new Error(r.status); finish(true); })
        .catch(function () { btn.classList.remove("is-loading"); btn.disabled = false; toast(t("err.send"), "i-info"); });
    });
  }

  /* ---------------------------------------------------------------
     FAQ accordion
     --------------------------------------------------------------- */
  function initFaq() {
    var list = $("#faqList");
    list.innerHTML = I18N.faq.map(function (f, i) {
      return '<div class="qa" data-qa="' + i + '">' +
        '<button class="qa__btn" type="button" aria-expanded="false" aria-controls="qa' + i + '" id="qab' + i + '">' +
          '<span class="qa__en" lang="en" dir="ltr">' + esc(f.q) + '</span>' +
          '<span class="qa__ar" lang="ar" dir="rtl">' + esc(f.qa) + '</span>' +
          '<span class="qa__ico" aria-hidden="true"><svg class="i"><use href="#i-plus"/></svg></span>' +
        '</button>' +
        '<div class="qa__panel" id="qa' + i + '" role="region" aria-labelledby="qab' + i + '"><div class="qa__inner"><div>' +
          '<p lang="en" dir="ltr">' + esc(f.a) + '</p><p lang="ar" dir="rtl">' + esc(f.aa) + '</p>' +
        '</div></div></div></div>';
    }).join("");
    list.addEventListener("click", function (e) {
      var b = e.target.closest(".qa__btn"); if (!b) return;
      var item = b.parentElement, open = !item.classList.contains("is-open");
      item.classList.toggle("is-open", open);
      b.setAttribute("aria-expanded", String(open));
    });
  }
  function openFaq(i) {
    var item = $('[data-qa="' + i + '"]');
    if (!item) return;
    if (!item.classList.contains("is-open")) $(".qa__btn", item).click();
    item.scrollIntoView({ behavior: reduceMotion ? "auto" : "smooth", block: "center" });
    setTimeout(function () { $(".qa__btn", item).focus({ preventScroll: true }); }, 500);
  }

  /* ---------------------------------------------------------------
     Search
     --------------------------------------------------------------- */
  var index = [], selected = 0;
  function buildSearchIndex() {
    var A = lang === "ar";
    var sec = function (id, en, ar, kw) { return { kind: "section", title: A ? ar : en, sub: "", hay: (en + " " + ar + " " + kw).toLowerCase(), run: function () { $(id).scrollIntoView({ behavior: "smooth" }); } }; };
    index = [
      sec("#how", "How Fandaqah works", "كيف تعمل فندقة", "steps process booking send status retry finance خطوات"),
      sec("#features", "Booking, error and finance features", "الحجوزات والأخطاء والمالية", "occupancy errors retries journal arabic english reports"),
      sec("#showcase", "Product screens", "شاشات النظام", "booking status list logs financial approval screens"),
      sec("#readiness", "NTMP readiness check", "فحص الجاهزية", "score self check license credentials ready نتيجة"),
      sec("#start", "Book a free readiness review", "احجز مراجعة مجانية", "contact form request demo review call تواصل")
    ];
    I18N.faq.forEach(function (f, i) {
      index.push({ kind: "faq", title: A ? f.qa : f.q, sub: (A ? f.aa : f.a).slice(0, 90) + "…", hay: (f.q + " " + f.qa + " " + f.a + " " + f.aa).toLowerCase(), run: function () { openFaq(i); } });
    });
    index.push({ kind: "action", title: A ? "شاهد العرض التوضيحي" : "Watch the product demo", sub: "", hay: "demo video walkthrough watch play عرض", run: function () { openLayer($("#demo")); window.FQDemo.open(lang); } });
    if (waLink()) index.push({ kind: "action", title: A ? "تواصل عبر واتساب" : "Chat with us on WhatsApp", sub: "+" + CFG.contact.whatsapp, hay: "whatsapp chat phone contact واتساب", run: function () { window.open(waLink(), "_blank", "noopener"); } });
    index.push({ kind: "action", title: A ? "Switch to English" : "التبديل إلى العربية", sub: "", hay: "language arabic english عربي لغة", run: function () { setLang(A ? "en" : "ar", true); } });
  }
  function renderResults(q) {
    var box = $("#searchResults");
    q = q.trim().toLowerCase();
    var res = q ? index.filter(function (it) { return q.split(/\s+/).every(function (w) { return it.hay.indexOf(w) > -1 || it.title.toLowerCase().indexOf(w) > -1; }); }) : index;
    selected = 0;
    if (!res.length) { box.innerHTML = '<li class="search__empty">' + esc(t("search.none")) + "</li>"; box._res = []; return; }
    box._res = res.slice(0, 8);
    box.innerHTML = box._res.map(function (it, i) {
      return '<li role="option" aria-selected="' + (i === 0) + '"><button type="button" data-i="' + i + '"><span class="sr-kind">' + esc(t("kind." + it.kind)) + '</span><span><strong>' + esc(it.title) + "</strong>" + (it.sub ? "<small>" + esc(it.sub) + "</small>" : "") + "</span></button></li>";
    }).join("");
  }
  function runResult(i) {
    var it = $("#searchResults")._res[i]; if (!it) return;
    closeLayer($("#search")); setTimeout(it.run, 60);
  }
  function openSearch(opener) { $("#searchInput").value = ""; renderResults(""); openLayer($("#search"), opener); }
  function initSearch() {
    var input = $("#searchInput"), box = $("#searchResults");
    input.addEventListener("input", function () { renderResults(input.value); });
    input.addEventListener("keydown", function (e) {
      var n = (box._res || []).length; if (!n) return;
      if (e.key === "ArrowDown" || e.key === "ArrowUp") {
        e.preventDefault(); selected = (selected + (e.key === "ArrowDown" ? 1 : -1) + n) % n;
        $$("li", box).forEach(function (li, i) { li.setAttribute("aria-selected", String(i === selected)); if (i === selected) li.scrollIntoView({ block: "nearest" }); });
      }
      if (e.key === "Enter") { e.preventDefault(); runResult(selected); }
    });
    box.addEventListener("click", function (e) { var b = e.target.closest("button[data-i]"); if (b) runResult(+b.dataset.i); });
    document.addEventListener("keydown", function (e) {
      var typing = /input|textarea|select/i.test(document.activeElement.tagName);
      if (((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === "k") || (e.key === "/" && !typing && !layerStack.length)) { e.preventDefault(); if ($("#search").hidden) openSearch(); }
    });
  }

  /* ---------------------------------------------------------------
     Scroll reveal
     --------------------------------------------------------------- */
  function initReveal() {
    var els = $$("[data-reveal]");
    if (!("IntersectionObserver" in window) || reduceMotion) { els.forEach(function (e) { e.classList.add("is-in"); }); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add("is-in"); io.unobserve(en.target); } });
    }, { threshold: .12, rootMargin: "0px 0px -40px 0px" });
    els.forEach(function (e) { io.observe(e); });
  }

  /* ---------------------------------------------------------------
     Boot
     --------------------------------------------------------------- */
  function boot() {
    captureOriginals();
    applyConfig();
    initLayers();
    initHeader();
    initActions();
    initSteps();
    initReadiness();
    initFaq();
    initForm();
    initShowcase();
    initSearch();
    initLang();
    initHero();
    initReveal();
  }
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", boot); else boot();
})();
