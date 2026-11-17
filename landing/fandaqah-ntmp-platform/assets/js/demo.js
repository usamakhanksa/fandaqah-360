/* =====================================================================
   Product walkthrough player.
   Plays FQ_CONFIG.demoVideoUrl when set (YouTube or .mp4); otherwise
   runs a built-in animated walkthrough of the five NTMP steps.
   ===================================================================== */
(function () {
  "use strict";
  var SCENE_MS = 6500;
  var stage, built = false, lang = "en";
  var scene = 0, playing = false, timers = [], raf = 0, sceneStart = 0, elapsedAtPause = 0;
  var els = {};

  var ICONS = ["i-calendar", "i-send", "i-search", "i-refresh", "i-coins"];
  function icon(id) { return '<svg class="i"><use href="#' + id + '"/></svg>'; }
  function D() { return window.FQ_I18N.demo; }
  function ui(k) { return (window.FQ_I18N.ui[lang] || window.FQ_I18N.ui.en)[k]; }

  function videoEmbed(url) {
    var yt = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([\w-]{6,})/);
    if (yt) {
      return '<iframe class="dm-video" src="https://www.youtube-nocookie.com/embed/' + yt[1] +
        '?autoplay=1&rel=0" title="Fandaqah product demo" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>';
    }
    return '<video class="dm-video" src="' + url + '" controls autoplay playsinline></video>';
  }

  function build() {
    stage = document.getElementById("demoStage");
    var cfg = window.FQ_CONFIG || {};
    if (cfg.demoVideoUrl) { stage.innerHTML = videoEmbed(cfg.demoVideoUrl); built = "video"; return; }

    var side = '<img src="assets/img/logo-mark.svg" alt="">' + ICONS.map(function (id) { return "<span>" + icon(id) + "</span>"; }).join("");
    stage.innerHTML =
      '<div class="dm-screen" dir="ltr">' +
        '<div class="dm-side" aria-hidden="true">' + side + '</div>' +
        '<div class="dm-main">' +
          '<div class="dm-top"><span id="dmTopTitle">Front desk</span><small>Fandaqah PMS</small></div>' +

          // Scene 1: record booking
          '<div class="dm-scene" data-s="0">' +
            '<div class="dm-grid">' +
              '<div class="dm-field"><label>Guest name</label><div data-type="Abdullah Al-Harbi"></div></div>' +
              '<div class="dm-field"><label>Nationality</label><div data-type="Saudi Arabia"></div></div>' +
              '<div class="dm-field"><label>Room</label><div data-type="1204"></div></div>' +
              '<div class="dm-field"><label>Stay</label><div data-type="17 – 19 Nov 2026"></div></div>' +
            '</div>' +
            '<span class="dm-btn" data-press>Save booking</span>' +
            '<div class="dm-toast">' + icon("i-check") + 'Booking RSV-24817 saved</div>' +
          '</div>' +

          // Scene 2: check and send
          '<div class="dm-scene" data-s="1">' +
            '<ul class="dm-checks">' +
              '<li>' + icon("i-check") + 'Guest name</li>' +
              '<li>' + icon("i-check") + 'ID number</li>' +
              '<li>' + icon("i-check") + 'Nationality</li>' +
              '<li>' + icon("i-check") + 'Room and dates</li>' +
            '</ul>' +
            '<div class="dm-send"><p>' + icon("i-send") + '<span data-sendlabel>Ready to send</span></p><div class="dm-bar"><span></span></div></div>' +
          '</div>' +

          // Scene 3: review status
          '<div class="dm-scene" data-s="2">' +
            '<table class="dm-table"><thead><tr><th>Booking</th><th>Guest</th><th>Room</th><th>NTMP status</th></tr></thead><tbody>' +
              '<tr data-row="a"><td>RSV-24817</td><td>A. Al-Harbi</td><td>1204</td><td><span class="pill pill--pending">Pending</span></td></tr>' +
              '<tr><td>RSV-24818</td><td>M. Khan</td><td>0807</td><td><span class="pill pill--ok">Accepted</span></td></tr>' +
              '<tr data-row="b"><td>RSV-24821</td><td>S. Al-Qahtani</td><td>1510</td><td><span class="pill pill--pending">Pending</span></td></tr>' +
              '<tr><td>RSV-24823</td><td>L. Haddad</td><td>0312</td><td><span class="pill pill--ok">Accepted</span></td></tr>' +
            '</tbody></table>' +
          '</div>' +

          // Scene 4: correct and retry
          '<div class="dm-scene" data-s="3">' +
            '<div class="dm-err" data-err><b>RSV-24821 rejected</b><span>Guest nationality is required. Field: guest.nationality</span></div>' +
            '<div class="dm-grid">' +
              '<div class="dm-field"><label>Guest name</label><div class="is-good">Sara Al-Qahtani</div></div>' +
              '<div class="dm-field"><label>Nationality</label><div class="is-bad" data-type="Saudi Arabia"></div></div>' +
            '</div>' +
            '<span class="dm-btn" data-press>' + icon("i-refresh") + 'Fix and retry</span>' +
          '</div>' +

          // Scene 5: finance approval
          '<div class="dm-scene" data-s="4">' +
            '<div class="dm-journal"><div>' +
              '<table class="dm-table"><thead><tr><th>Account</th><th style="text-align:end">Debit</th><th style="text-align:end">Credit</th></tr></thead><tbody>' +
                '<tr><td>Room revenue</td><td></td><td style="text-align:end">1,850.00</td></tr>' +
                '<tr><td>VAT output 15%</td><td></td><td style="text-align:end">277.50</td></tr>' +
                '<tr><td>Guest ledger</td><td style="text-align:end">2,127.50</td><td></td></tr>' +
              '</tbody></table>' +
              '<span class="dm-btn" data-press>' + icon("i-check") + 'Approve entry</span>' +
            '</div><span class="dm-stamp">Approved</span></div>' +
          '</div>' +
        '</div>' +
      '</div>' +
      '<div class="dm-controls">' +
        '<button class="dm-play" type="button" id="dmPlay">' + icon("i-pause") + '</button>' +
        '<p class="dm-caption" id="dmCaption" aria-live="polite"></p>' +
        '<div class="dm-chips" id="dmChips"></div>' +
      '</div>';

    els.scenes = stage.querySelectorAll(".dm-scene");
    els.side = stage.querySelectorAll(".dm-side span");
    els.caption = stage.querySelector("#dmCaption");
    els.chips = stage.querySelector("#dmChips");
    els.play = stage.querySelector("#dmPlay");
    els.top = stage.querySelector("#dmTopTitle");
    els.play.addEventListener("click", function () { playing ? pause() : play(); });
    renderChips();
    built = true;
  }

  function renderChips() {
    els.chips.innerHTML = D()[lang].map(function (label, i) {
      return '<button type="button" data-chip="' + i + '"><i></i>' + (i + 1) + ". " + label + "</button>";
    }).join("");
    els.chips.querySelectorAll("button").forEach(function (b) {
      b.addEventListener("click", function () { goTo(+b.dataset.chip, true); });
    });
    updateChrome();
  }

  function later(fn, ms) { timers.push(setTimeout(fn, ms)); }
  function clearTimers() { timers.forEach(clearTimeout); timers = []; }

  function typeInto(el, text, start, speed) {
    speed = speed || 55;
    later(function () { el.classList.add("is-typing", "caret"); el.classList.remove("is-bad"); }, start);
    for (var i = 1; i <= text.length; i++) {
      (function (n) { later(function () { el.textContent = text.slice(0, n); }, start + n * speed); })(i);
    }
    later(function () { el.classList.remove("is-typing", "caret"); }, start + text.length * speed + 120);
    return start + text.length * speed;
  }
  function press(el, at) {
    later(function () { el.classList.add("is-press"); }, at);
    later(function () { el.classList.remove("is-press"); }, at + 220);
  }

  var TOPS = ["Front desk / New booking", "NTMP / Pre-send check", "NTMP / Booking status", "NTMP / Error details", "Finance / Journal JV-1187"];

  function resetScene(s) {
    s.querySelectorAll("[data-type]").forEach(function (f) { f.textContent = ""; f.className = f.dataset.bad === "1" ? "is-bad" : ""; });
    s.querySelectorAll(".is-on").forEach(function (n) { n.classList.remove("is-on"); });
    s.querySelectorAll(".is-hl,.is-ok,.is-fixed,.is-flying").forEach(function (n) { n.classList.remove("is-hl", "is-ok", "is-fixed", "is-flying"); });
    var bar = s.querySelector(".dm-bar span"); if (bar) bar.style.width = "0";
  }

  function runScene(i) {
    var s = els.scenes[i];
    resetScene(s);
    if (i === 3) { var nf = s.querySelector("[data-type]"); nf.dataset.bad = "1"; nf.className = "is-bad"; }
    var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (i === 0) {
      var t = 300;
      s.querySelectorAll("[data-type]").forEach(function (f) { t = typeInto(f, f.dataset.type, t, reduce ? 1 : 45) + 250; });
      press(s.querySelector("[data-press]"), t + 100);
      later(function () { s.querySelector(".dm-toast").classList.add("is-on"); }, t + 350);
    }
    if (i === 1) {
      s.querySelectorAll(".dm-checks li").forEach(function (li, n) { later(function () { li.classList.add("is-on"); }, 400 + n * 550); });
      var label = s.querySelector("[data-sendlabel]");
      label.textContent = "Ready to send";
      later(function () { label.textContent = "Sending to NTMP"; s.querySelector(".dm-send").classList.add("is-flying"); s.querySelector(".dm-bar span").style.width = "100%"; }, 2800);
      later(function () { label.textContent = "Sent. Waiting for confirmation"; }, 4600);
    }
    if (i === 2) {
      var a = s.querySelector('[data-row="a"]'), b = s.querySelector('[data-row="b"]');
      a.querySelector(".pill").className = "pill pill--pending"; a.querySelector(".pill").textContent = "Pending";
      b.querySelector(".pill").className = "pill pill--pending"; b.querySelector(".pill").textContent = "Pending";
      later(function () { a.classList.add("is-ok"); var p = a.querySelector(".pill"); p.className = "pill pill--ok"; p.textContent = "Accepted"; }, 1400);
      later(function () { b.classList.add("is-hl"); var p = b.querySelector(".pill"); p.className = "pill pill--err"; p.textContent = "Rejected"; }, 3000);
    }
    if (i === 3) {
      var err = s.querySelector("[data-err]");
      err.querySelector("b").textContent = "RSV-24821 rejected";
      err.querySelector("span").textContent = "Guest nationality is required. Field: guest.nationality";
      var f = s.querySelector("[data-type]");
      var end = typeInto(f, f.dataset.type, 1200, reduce ? 1 : 60);
      later(function () { f.className = "is-good"; }, end + 150);
      press(s.querySelector("[data-press]"), end + 600);
      later(function () { err.classList.add("is-fixed"); err.querySelector("b").textContent = "RSV-24821 accepted"; err.querySelector("span").textContent = "Retried at 07:44. Confirmed by NTMP."; }, end + 1300);
    }
    if (i === 4) {
      press(s.querySelector("[data-press]"), 2200);
      later(function () { s.querySelector(".dm-stamp").classList.add("is-on"); }, 2500);
    }
  }

  function updateChrome() {
    if (built !== true) return;
    els.scenes.forEach(function (s, n) { s.classList.toggle("is-on", n === scene); });
    els.side.forEach(function (s, n) { s.classList.toggle("is-on", n === scene); });
    els.top.textContent = TOPS[scene];
    els.caption.textContent = D().captions[lang][scene];
    els.chips.querySelectorAll("button").forEach(function (b, n) {
      b.classList.toggle("is-on", n === scene);
      b.classList.toggle("is-done", n < scene);
      b.setAttribute("aria-current", n === scene ? "step" : "false");
      if (n !== scene) b.querySelector("i").style.width = n < scene ? "100%" : "0";
    });
    els.play.innerHTML = icon(playing ? "i-pause" : "i-play");
    els.play.setAttribute("aria-label", ui(playing ? "demo.pause" : "demo.play"));
  }

  function tick() {
    var elapsed = performance.now() - sceneStart;
    var chip = els.chips.querySelectorAll("button")[scene];
    if (chip) chip.querySelector("i").style.width = Math.min(100, (elapsed / SCENE_MS) * 100) + "%";
    if (elapsed >= SCENE_MS) { goTo((scene + 1) % 5, false); return; }
    raf = requestAnimationFrame(tick);
  }

  function goTo(i, userInitiated) {
    clearTimers(); cancelAnimationFrame(raf);
    scene = i; sceneStart = performance.now();
    updateChrome(); runScene(i);
    if (userInitiated && !playing) playing = true;
    updateChrome();
    if (playing) raf = requestAnimationFrame(tick);
  }
  function play() { playing = true; goTo(scene, false); }
  function pause() { playing = false; clearTimers(); cancelAnimationFrame(raf); updateChrome(); }

  window.FQDemo = {
    open: function (l) {
      lang = l || lang;
      if (!built) build();
      if (built === "video") return;
      playing = true; goTo(0, false);
    },
    close: function () {
      if (built === "video") { stage.innerHTML = ""; built = false; return; }
      pause();
    },
    setLang: function (l) {
      lang = l;
      if (built === true) { renderChips(); updateChrome(); }
    }
  };
})();
