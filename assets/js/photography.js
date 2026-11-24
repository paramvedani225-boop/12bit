/* Photography portfolio: category filter, asymmetric grid, Swiper lightbox.
   The first grid is rendered by photography.php; this only re-renders on filter change. */
(function () {
  const D = window.DATA, App = window.App;
  const grid = document.querySelector('.pgrid');
  const filters = document.querySelector('.filters');
  // Row packer — must match pack_spans() in includes/bootstrap.php
  const PAIRS = { 'wide-wide': [[7, 5], [5, 7]], 'wide-tall': [[8, 4]], 'tall-wide': [[4, 8]], 'wide-square': [[7, 5]], 'square-wide': [[5, 7]] };
  function packSpans(shapes, singleWide = 8) {
    const out = [];
    for (let i = 0, row = 0; i < shapes.length; i += 2, row++) {
      if (i === shapes.length - 1) { out.push(shapes[i] === 'wide' ? singleWide : 6); break; }
      const opts = PAIRS[shapes[i] + '-' + shapes[i + 1]] || [[6, 6]];
      out.push(...opts[row % opts.length]);
    }
    return out;
  }
  const ratio = { wide: 'media--3x2', tall: 'media--4x5', square: 'media--1x1' };
  const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const byCat = cat => (cat === 'All' ? D.photos : D.photos.filter(p => p.cat === cat));

  const active = filters.querySelector('.filter-btn.active');
  let list = byCat(active ? active.dataset.cat : 'All');

  function render(cat) {
    list = byCat(cat);
    const spans = packSpans(list.map(p => p.shape));
    filters.querySelectorAll('.filter-btn').forEach(b => b.classList.toggle('active', b.dataset.cat === cat));
    grid.innerHTML = list.map((p, i) => `
      <figure class="pcard hover-zoom span-${spans[i]} ${i % 4 === 1 ? 'offset' : ''} m-0" data-index="${i}" data-cursor="view" tabindex="0" role="button" aria-label="Open ${esc(p.title)}">
        <div class="frame"><div class="media ${ratio[p.shape]} ${App.wmClass(p, true)}" data-reveal="clip"><img src="${esc(p.src)}" alt="${esc(p.title)} — ${esc(p.cat)} photography" loading="lazy"${p.pos ? ` style="object-position:${esc(p.pos)}"` : ''}></div></div>
        <figcaption class="pcard__cap"><div><h3>${esc(p.title)}</h3><p>${esc(p.desc)}</p></div><span class="label">${esc(p.cat)}</span></figcaption>
      </figure>`).join('');
    App.reveal(grid);
    ScrollTrigger.refresh();
  }

  filters.addEventListener('click', e => {
    const b = e.target.closest('.filter-btn');
    if (!b) return;
    e.preventDefault();
    if (b.classList.contains('active')) return;
    history.replaceState(null, '', b.getAttribute('href'));
    gsap.to(grid, { autoAlpha: 0, y: 20, duration: .35, ease: 'power2.in', onComplete: () => {
      render(b.dataset.cat);
      gsap.to(grid, { autoAlpha: 1, y: 0, duration: .6, ease: 'expo.out' });
    } });
  });

  /* ---------- Lightbox ---------- */
  const lb = document.querySelector('.lightbox');
  const wrap = lb.querySelector('.swiper-wrapper');
  let swiper = null;
  function open(index) {
    wrap.innerHTML = list.map(p => `
      <div class="swiper-slide">
        <div class="lb-media ${App.wmClass(p)}"><img src="${esc(p.src)}" alt="${esc(p.title)}"></div>
        <div class="lb-cap"><span class="label">${esc(p.cat)}</span><h3 class="display-s mt-2">${esc(p.title)}</h3><p class="muted m-0">${esc(p.desc)}</p></div>
      </div>`).join('');
    if (swiper) swiper.destroy(true, true);
    swiper = new Swiper(lb.querySelector('.swiper'), {
      initialSlide: index, speed: 900, keyboard: { enabled: true }, grabCursor: true,
      navigation: { nextEl: lb.querySelector('.swiper-button-next'), prevEl: lb.querySelector('.swiper-button-prev') },
      pagination: { el: lb.querySelector('.lb-count'), type: 'fraction' }
    });
    lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
    App.lenis && App.lenis.stop();
    lb.querySelector('.lightbox__close').focus();
  }
  function close() { lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true'); App.lenis && App.lenis.start(); }
  grid.addEventListener('click', e => { const c = e.target.closest('.pcard'); if (c) open(+c.dataset.index); });
  grid.addEventListener('keydown', e => { const c = e.target.closest('.pcard'); if (c && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); open(+c.dataset.index); } });
  lb.querySelector('.lightbox__close').addEventListener('click', close);
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && lb.classList.contains('open')) close(); });
})();
