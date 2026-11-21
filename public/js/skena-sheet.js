/* SKENA – perilaku layout lembar & dashboard (drawer, menu profil, carousel, mode peran). */
(function () {
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  // Drawer: Esc menutup, fokus kembali ke tombol pemicu
  var drawer = $('#drawer'), scrim = $('#scrim'), openBtn = $('#openDrawer');
  function setDrawer(open) {
    if (!drawer) return;
    drawer.classList.toggle('open', open);
    scrim.classList.toggle('open', open);
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    if (openBtn) openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open) { var first = $('nav a', drawer); if (first) first.focus(); }
    else if (openBtn) openBtn.focus();
  }
  if (drawer) {
    openBtn.addEventListener('click', function () { setDrawer(true); });
    $('#closeDrawer').addEventListener('click', function () { setDrawer(false); });
    scrim.addEventListener('click', function () { setDrawer(false); });
  }

  // Menu profil
  var pb = $('#profileBtn'), pm = $('#profileMenu');
  function setMenu(open) {
    if (!pm) return;
    pm.classList.toggle('open', open);
    pb.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  if (pb) {
    pb.addEventListener('click', function (e) { e.stopPropagation(); setMenu(!pm.classList.contains('open')); });
    pm.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', function () { setMenu(false); });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (drawer && drawer.classList.contains('open')) setDrawer(false);
    if (pm && pm.classList.contains('open')) { setMenu(false); pb.focus(); }
  });

  // Toast hilang sendiri
  $$('[data-toast]:not([data-sticky])').forEach(function (t) { setTimeout(function () { t.remove(); }, 4000); });
  $$('[data-toast-close]').forEach(function (b) { b.addEventListener('click', function () { b.closest('[data-toast]').remove(); }); });

  // Tab kategori
  $$('#tabs .tab').forEach(function (t) {
    t.addEventListener('click', function () {
      $$('#tabs .tab').forEach(function (x) { x.setAttribute('aria-pressed', 'false'); });
      t.setAttribute('aria-pressed', 'true');
    });
  });

  // Hati wishlist (tampilan saja; PBI-07 di Sprint 2)
  $$('.heart').forEach(function (h) {
    h.addEventListener('click', function () {
      h.setAttribute('aria-pressed', h.getAttribute('aria-pressed') === 'true' ? 'false' : 'true');
    });
  });

  // Carousel
  var rail = $('#rail');
  if (rail) {
    var step = function () { var c = $('.card', rail); return c ? c.getBoundingClientRect().width + 20 : 300; };
    $('#next').addEventListener('click', function () { rail.scrollBy({ left: step(), behavior: 'smooth' }); });
    $('#prev').addEventListener('click', function () { rail.scrollBy({ left: -step(), behavior: 'smooth' }); });
  }

  // Ganti mode peran (Pencari / Penyedia Jasa)
  var modeData = $('#roleModes');
  if (modeData) {
    var data = JSON.parse(modeData.textContent);
    $$('#roleSeg button').forEach(function (b) {
      b.addEventListener('click', function () {
        $$('#roleSeg button').forEach(function (x) { x.setAttribute('aria-pressed', 'false'); });
        b.setAttribute('aria-pressed', 'true');
        var d = data[b.dataset.mode];
        Object.keys(d).forEach(function (k) { var el = $('[data-k="' + k + '"]'); if (el) el.textContent = d[k]; });
      });
    });
  }
})();

/* SKENA – validasi form (skill "forms") & konfirmasi keluar (TIM-B tb-3).
   Validasi: tidak ada error saat mengetik di field yang belum disentuh; cek saat blur
   pertama; error hilang begitu field difokuskan/diketik; tombol submit tidak pernah
   dinonaktifkan — klik dengan isian salah memfokuskan error pertama. */
(function () {
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  function boxOf(el) { return el.closest('.fld'); }
  function message(el) {
    var v = el.validity, d = el.dataset;
    if (el.disabled) return '';
    if (v.valueMissing) return d.msgRequired || 'Isian ini wajib diisi.';
    if (v.typeMismatch) return d.msgType || 'Periksa lagi format isian ini.';
    if (v.tooShort) return d.msgShort || ('Minimal ' + el.minLength + ' karakter.');
    if (v.patternMismatch) return d.msgPattern || 'Periksa lagi format isian ini.';
    if (!v.valid) return d.msgInvalid || el.validationMessage;
    if (d.match) {
      var other = document.querySelector(d.match);
      if (other && el.value !== other.value) return d.msgMatch || 'Isian belum sama.';
    }
    return '';
  }
  function show(el, msg) {
    var box = boxOf(el); if (!box) return;
    var err = box.querySelector('.err'), hint = box.querySelector('.hint');
    el.setAttribute('aria-invalid', msg ? 'true' : 'false');
    if (err) { err.hidden = !msg; var t = err.querySelector('[data-err-text]') || err; if (msg) t.textContent = msg; }
    if (hint) hint.hidden = !!msg;
  }

  function wire(form) {
    if (form.dataset.wired) return; form.dataset.wired = '1';
    form.noValidate = true;
    var fields = function () {
      return $$('input[name],select[name],textarea[name]', form).filter(function (el) { return el.type !== 'hidden' && boxOf(el); });
    };
    form.addEventListener('blur', function (e) {
      var el = e.target; if (!el.name || !boxOf(el) || el.type === 'hidden') return;
      show(el, message(el));
    }, true);
    form.addEventListener('focus', function (e) {
      var el = e.target; if (el.getAttribute && el.getAttribute('aria-invalid') === 'true') show(el, '');
    }, true);
    form.addEventListener('input', function (e) {
      var el = e.target; if (el.getAttribute && el.getAttribute('aria-invalid') === 'true') show(el, '');
    }, true);
    form.addEventListener('submit', function (e) {
      var first = null;
      fields().forEach(function (el) {
        var msg = message(el);
        show(el, msg);
        if (msg && !first) first = el;
      });
      if (first) {
        e.preventDefault();
        first.scrollIntoView({ block: 'center', behavior: 'smooth' });
        first.focus({ preventScroll: true });
        show(first, message(first)); // fokus menyembunyikan error; tampilkan lagi untuk field pertama
        return;
      }
      var btn = form.querySelector('[data-loading]');
      if (btn) { btn.setAttribute('aria-busy', 'true'); btn.textContent = btn.dataset.loading; }
    });
  }
  $$('form[data-validate]').forEach(wire);

  // Tampilkan/sembunyikan kata sandi
  $$('[data-toggle-pw]').forEach(function (b) {
    b.addEventListener('click', function () {
      var input = document.getElementById(b.dataset.togglePw);
      var on = input.type === 'password';
      input.type = on ? 'text' : 'password';
      b.setAttribute('aria-pressed', on ? 'true' : 'false');
      b.setAttribute('aria-label', on ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
  });

  // Indikator kekuatan kata sandi (TIM-A)
  $$('[data-strength-for]').forEach(function (meter) {
    var input = document.getElementById(meter.dataset.strengthFor);
    var bars = $$('i', meter), label = meter.querySelector('b');
    var cols = ['#E2E8F0', '#EF4444', '#F59E0B', '#3F749C', '#10B981'];
    var txt = ['#64748B', '#B91C1C', '#B45309', '#346284', '#047857'];
    var names = ['Belum diisi', 'Lemah', 'Sedang', 'Kuat', 'Sangat kuat'];
    function upd() {
      var v = input.value, s = 0;
      if (v.length >= 8) s++; if (/\d/.test(v)) s++; if (/[A-Z]/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++;
      if (!v) s = 0;
      bars.forEach(function (b, i) { b.style.background = i < s ? cols[s] : cols[0]; });
      label.textContent = names[s]; label.style.color = txt[s];
    }
    input.addEventListener('input', upd); upd();
  });

  // Konfirmasi keluar
  var dlg = document.getElementById('logoutDialog');
  if (dlg) {
    var pending = null, opener = null;
    var cancel = dlg.querySelector('[data-cancel]'), ok = dlg.querySelector('[data-ok]');
    var close = function () { dlg.hidden = true; document.body.style.overflow = ''; pending = null; if (opener && opener.focus) opener.focus(); };
    document.addEventListener('submit', function (e) {
      var f = e.target;
      if (!f.matches('form[data-confirm-logout]') || f.dataset.ok) return;
      e.preventDefault();
      pending = f; opener = f.querySelector('button') || document.activeElement;
      var pm = document.getElementById('profileMenu'); if (pm) pm.classList.remove('open');
      var d = document.getElementById('drawer');
      if (d && d.classList.contains('open')) { d.classList.remove('open'); document.getElementById('scrim').classList.remove('open'); opener = document.getElementById('openDrawer'); }
      dlg.hidden = false; document.body.style.overflow = 'hidden';
      cancel.focus();
    }, true);
    cancel.addEventListener('click', close);
    ok.addEventListener('click', function () { if (!pending) return; pending.dataset.ok = '1'; ok.setAttribute('aria-busy', 'true'); ok.textContent = 'Keluar…'; pending.submit(); });
    dlg.addEventListener('click', function (e) { if (e.target === dlg) close(); });
    dlg.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { e.stopPropagation(); close(); }
      if (e.key === 'Tab') { e.preventDefault(); (document.activeElement === cancel ? ok : cancel).focus(); }
    });
  }
})();

/* Dropdown lonceng notifikasi (Header.dc.html: bellOpen) */
(function () {
  var b = document.getElementById('bellBtn'), m = document.getElementById('bellMenu');
  var p = document.getElementById('profileMenu'), pb = document.getElementById('profileBtn');
  if (!b) return;
  function set(open) { m.classList.toggle('open', open); b.setAttribute('aria-expanded', open ? 'true' : 'false'); }
  b.addEventListener('click', function (e) { e.stopPropagation(); if (p) { p.classList.remove('open'); pb.setAttribute('aria-expanded', 'false'); } set(!m.classList.contains('open')); });
  m.addEventListener('click', function (e) { e.stopPropagation(); });
  if (pb) pb.addEventListener('click', function () { set(false); });
  document.addEventListener('click', function () { set(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && m.classList.contains('open')) { set(false); b.focus(); } });
})();

/* Header lengket: tandai saat halaman sudah di-scroll (untuk garis + bayangan + tinggi ringkas) */
(function () {
  var bar = document.querySelector('.topbar');
  if (!bar) return;
  var ticking = false;
  var sheet = document.querySelector('.sheet');
  // Menempel = bagian atas lembar sudah lewat tepi atas layar
  var sub = document.querySelector('.subbar');
  function update() {
    var stuck = (sheet ? sheet.getBoundingClientRect().top : 0) < -1;
    bar.classList.toggle('stuck', stuck);
    // Baris filter menempel tepat di bawah header: bayangan pindah ke baris filter
    var subStuck = !!sub && stuck && sub.getBoundingClientRect().top <= bar.getBoundingClientRect().bottom + 0.5;
    if (sub) sub.classList.toggle('stuck', subStuck);
    bar.classList.toggle('joined', subStuck);
    ticking = false;
  }
  window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
  update();
})();
