/* Shared behaviour: watermark, smooth scroll, cursor, nav, transitions, reveals. */
(function () {
  const S = window.SITE;
  const html = document.documentElement;
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  html.classList.remove('no-js');
  if (reduced) html.classList.add('reduced');
  gsap.registerPlugin(ScrollTrigger);

  const App = (window.App = { reduced, lenis: null });

  /* ---------- Watermark config → CSS ---------- */
  const wm = S.watermark || {};
  html.style.setProperty('--wm-text', JSON.stringify(S.studioName));
  html.style.setProperty('--wm-sub', JSON.stringify(S.tagline));
  if (wm.logo) html.style.setProperty('--wm-logo', `url("${wm.logo}")`);
  html.classList.toggle('wm-off', wm.enabled === false);
  html.classList.toggle('wm-logo', wm.mode === 'logo');

  /* ---------- Smooth scroll (Lenis) ---------- */
  if (!reduced && window.Lenis) {
    const lenis = new Lenis({ lerp: 0.12, wheelMultiplier: 1, smoothWheel: true });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(t => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
    App.lenis = lenis;
  }

  /* ---------- Nav: hide on scroll down, show on scroll up ---------- */
  const nav = document.getElementById('nav');
  let lastY = 0;
  ScrollTrigger.create({
    start: 0, end: 'max',
    onUpdate: self => {
      const y = self.scroll();
      nav.classList.toggle('is-scrolled', y > 40);
      nav.classList.toggle('is-hidden', y > 300 && y > lastY && !document.body.classList.contains('menu-open'));
      lastY = y;
    }
  });

  /* ---------- Mobile menu ---------- */
  const toggle = document.querySelector('.nav__toggle');
  toggle.addEventListener('click', () => {
    const open = document.body.classList.toggle('menu-open');
    toggle.setAttribute('aria-expanded', open);
    if (App.lenis) open ? App.lenis.stop() : App.lenis.start();
  });

  /* ---------- Services dropdown (desktop) ---------- */
  const dd = document.getElementById('svcDd');
  if (dd) {
    const caret = dd.querySelector('.dd__caret');
    const items = [...dd.querySelectorAll('.dd__item')];
    const pv = dd.querySelector('.dd__preview');
    const media = pv.querySelector('.dd__media'), img = media.querySelector('img');
    const num = pv.querySelector('.dd__num'), tag = pv.querySelector('.dd__tag');
    let timer, swapT, cur;

    const show = a => {
      if (a === cur) return;
      cur && cur.classList.remove('is-cur');
      cur = a; a.classList.add('is-cur');
      pv.classList.add('is-swap');
      media.classList.remove('has-img');
      clearTimeout(swapT);
      swapT = setTimeout(() => {
        num.textContent = a.dataset.n;
        tag.textContent = a.dataset.tag;
        if (a.dataset.img) {
          img.style.objectPosition = a.dataset.pos || '';
          const go = () => cur === a && media.classList.add('has-img');
          if (img.getAttribute('src') !== a.dataset.img) { img.onload = go; img.src = a.dataset.img; } else go();
        }
        pv.classList.remove('is-swap');
      }, reduced ? 0 : 180);
    };
    const open = () => {
      clearTimeout(timer);
      if (dd.classList.contains('is-open')) return;
      dd.classList.add('is-open'); caret.setAttribute('aria-expanded', 'true');
      if (!cur) show(dd.querySelector('.dd__item.is-active') || items[0]);
    };
    const close = (delay = 0) => {
      clearTimeout(timer);
      timer = setTimeout(() => { dd.classList.remove('is-open'); caret.setAttribute('aria-expanded', 'false'); }, delay);
    };

    // highlight the current service when on a service page
    const id = new URLSearchParams(location.search).get('id');
    if (id) items.forEach(a => a.href.endsWith('id=' + encodeURIComponent(id)) && a.classList.add('is-active'));

    if (window.matchMedia('(hover: hover)').matches) {
      dd.addEventListener('mouseenter', () => open());
      dd.addEventListener('mouseleave', () => close(220));
    }
    caret.addEventListener('click', e => { e.preventDefault(); dd.classList.contains('is-open') ? close() : open(); });
    dd.addEventListener('focusin', open);
    dd.addEventListener('focusout', e => { if (!dd.contains(e.relatedTarget)) close(120); });
    items.forEach(a => { a.addEventListener('mouseenter', () => show(a)); a.addEventListener('focus', () => show(a)); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && dd.classList.contains('is-open')) { close(); caret.focus(); } });
    document.addEventListener('click', e => { if (!dd.contains(e.target)) close(); });
    window.addEventListener('scroll', () => { if (nav.classList.contains('is-hidden')) close(); }, { passive: true });
  }

  /* ---------- Services sub-list (mobile menu) ---------- */
  document.querySelectorAll('.mgroup').forEach(g => {
    const btn = g.querySelector('.mgroup__btn');
    btn.addEventListener('click', () => {
      const on = g.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', on);
      g.querySelectorAll('.msub a').forEach(a => (on ? a.removeAttribute('tabindex') : a.setAttribute('tabindex', '-1')));
    });
  });

  /* ---------- Custom cursor ---------- */
  const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (fine && !reduced) {
    document.body.classList.add('has-cursor');
    const dot = document.querySelector('.cursor'), ring = document.querySelector('.cursor-ring');
    const dx = gsap.quickTo(dot, 'x', { duration: .12 }), dy = gsap.quickTo(dot, 'y', { duration: .12 });
    const rx = gsap.quickTo(ring, 'x', { duration: .5, ease: 'power3' }), ry = gsap.quickTo(ring, 'y', { duration: .5, ease: 'power3' });
    window.addEventListener('mousemove', e => { document.body.classList.add('cursor-on'); dx(e.clientX); dy(e.clientY); rx(e.clientX); ry(e.clientY); });
    document.addEventListener('mouseover', e => {
      const v = e.target.closest('[data-cursor="view"]');
      const h = e.target.closest('a, button, select, input, textarea, label');
      ring.classList.toggle('is-view', !!v);
      ring.classList.toggle('is-hover', !v && !!h);
      ring.textContent = v ? 'View' : '';
    });
  }

  /* ---------- Page transitions (iris) ---------- */
  const pt = document.querySelector('.pt');
  let fromTransition = false;
  try { fromTransition = sessionStorage.getItem('pt') === '1'; sessionStorage.removeItem('pt'); } catch (e) {}
  if (fromTransition && !reduced) {
    gsap.fromTo(pt, { clipPath: 'circle(150% at 50% 50%)' }, { clipPath: 'circle(0% at 50% 50%)', duration: 1.1, ease: 'expo.inOut', delay: .1 });
  }
  document.addEventListener('click', e => {
    if (e.defaultPrevented) return;   // e.g. portfolio filters handle their own clicks
    const a = e.target.closest('a[href]');
    if (!a || e.metaKey || e.ctrlKey || e.shiftKey || a.target === '_blank') return;
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || /^https?:/.test(href)) return;
    if (reduced) return;
    e.preventDefault();
    try { sessionStorage.setItem('pt', '1'); } catch (err) {}
    gsap.to(pt, { clipPath: 'circle(150% at 50% 50%)', duration: .9, ease: 'expo.inOut', onComplete: () => (window.location.href = href) });
  });
  window.addEventListener('pageshow', e => { if (e.persisted) gsap.set(pt, { clipPath: 'circle(0% at 50% 50%)' }); });

  /* ---------- Reveal system (call App.reveal(root) for dynamic content) ---------- */
  function splitWords(el) {
    [...el.childNodes].forEach(n => {
      if (n.nodeType === 3) {
        const frag = document.createDocumentFragment();
        n.textContent.split(/(\s+)/).forEach(p => {
          if (!p) return;
          if (/^\s+$/.test(p)) { frag.append(' '); return; }
          const w = document.createElement('span'); w.className = 'w';
          const i = document.createElement('span'); i.className = 'wi'; i.textContent = p;
          w.append(i); frag.append(w);
        });
        n.replaceWith(frag);
      } else if (n.nodeType === 1 && n.tagName !== 'BR') splitWords(n);
    });
  }

  App.reveal = function (root = document) {
    root.querySelectorAll('[data-reveal]:not(.rv)').forEach(el => {
      el.classList.add('rv');
      if (reduced) return;
      const type = el.dataset.reveal;
      const st = { trigger: el, start: 'top 88%' };
      if (type === 'text') {
        splitWords(el);
        gsap.from(el.querySelectorAll('.wi'), { yPercent: 110, duration: 1.2, stagger: .035, ease: 'expo.out', scrollTrigger: st });
      } else if (type === 'iris') {
        gsap.to(el, { clipPath: 'circle(75% at 50% 50%)', duration: 1.6, ease: 'expo.inOut', scrollTrigger: st });
        const img = el.querySelector('img');
        if (img) gsap.from(img, { scale: 1.3, duration: 2, ease: 'expo.out', scrollTrigger: st });
      } else if (type === 'clip') {
        gsap.to(el, { clipPath: 'inset(0% 0 0 0)', duration: 1.4, ease: 'expo.inOut', scrollTrigger: st });
        const img = el.querySelector('img');
        if (img) gsap.from(img, { scale: 1.25, duration: 2, ease: 'expo.out', scrollTrigger: st });
      } else {
        gsap.from(el, { y: 40, autoAlpha: 0, duration: 1.2, ease: 'expo.out', delay: parseFloat(el.dataset.delay || 0), scrollTrigger: st });
      }
    });

    if (!reduced) root.querySelectorAll('[data-parallax]:not(.px)').forEach(el => {
      el.classList.add('px');
      gsap.fromTo(el, { yPercent: -parseFloat(el.dataset.parallax) * 50 }, {
        yPercent: parseFloat(el.dataset.parallax) * 50, ease: 'none',
        scrollTrigger: { trigger: el.closest('section') || el, start: 'top bottom', end: 'bottom top', scrub: true }
      });
    });

    root.querySelectorAll('[data-count]:not(.ct)').forEach(el => {
      el.classList.add('ct');
      const end = parseFloat(el.dataset.count), suf = el.dataset.suffix || '';
      const obj = { v: reduced ? end : 0 };
      const draw = () => (el.innerHTML = Math.round(obj.v) + (suf ? `<sup>${suf}</sup>` : ''));
      draw();
      if (!reduced) gsap.to(obj, { v: end, duration: 2.2, ease: 'power3.out', onUpdate: draw, scrollTrigger: { trigger: el, start: 'top 90%' } });
    });
  };

  /* Watermark helper for templates */
  App.wmClass = (item, small) => (item.owned === false ? '' : small ? 'wm wm--sm' : 'wm');

  document.addEventListener('DOMContentLoaded', () => {
    App.reveal();
    window.addEventListener('load', () => ScrollTrigger.refresh());
  });
})();
