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
  $$('[data-toast]').forEach(function (t) { setTimeout(function () { t.remove(); }, 4000); });

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
