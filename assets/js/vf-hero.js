/* Viewfinder hero (.bf-hero) — used on About and on service pages without a photo hero.
   Intro: frame corners close in, HUD appears, title pulls into focus, focus locks.
   Scroll: frame opens out. Pointer (desktop): reticle follows, title drifts.
   Optional parts: .bf-iso (ISO count-down), .bf-gold (underline), .bf-rule, .bf-lead, .bf-cta, .svc-hero__num. */
(function () {
  const App = window.App || {};

  function splitChars(el) {
    [...el.childNodes].forEach(n => {
      if (n.nodeType === 1) return splitChars(n);
      if (n.nodeType !== 3) return;
      const frag = document.createDocumentFragment();
      n.textContent.split(/(\s+)/).forEach(part => {
        if (!part) return;
        if (/^\s+$/.test(part)) return frag.append(' ');
        const w = document.createElement('span'); w.className = 'bf-word';
        [...part].forEach(c => { const s = document.createElement('span'); s.className = 'bf-ch'; s.textContent = c; w.append(s); });
        frag.append(w);
      });
      n.replaceWith(frag);
    });
  }

  function init(hero) {
    const $ = s => hero.querySelector(s), $$ = s => hero.querySelectorAll(s);
    const title = $('.bf-title');
    const af = $('.bf-af');
    const lock = () => { hero.classList.add('is-locked'); if (af) af.textContent = 'Focus locked'; };

    if (title) { title.setAttribute('aria-label', title.textContent.replace(/\s+/g, ' ').trim()); splitChars(title); }
    if (App.reduced) return lock();

    const isoEl = $('.bf-iso'), iso = { v: 3200 };
    const has = s => !!$(s);

    /* ---------- Intro ---------- */
    const tl = gsap.timeline({ defaults: { ease: 'expo.out' }, delay: .25 });
    tl.from($$('.bf-vf__c'), {
        opacity: 0, duration: 1.2, ease: 'expo.inOut',
        x: i => (i % 2 ? 60 : -60), y: i => (i < 2 ? -40 : 40)
      });
    if (has('.svc-hero__num')) tl.from($('.svc-hero__num'), { autoAlpha: 0, scale: 1.15, xPercent: 8, duration: 1.8 }, '<');
    tl.from($$('.bf-vf__hud'), { autoAlpha: 0, y: 8, duration: .8, stagger: .15 }, '-=.6');
    if (has('.bf-reticle')) tl.from($('.bf-reticle'), { autoAlpha: 0, scale: 2.2, duration: 1.1, ease: 'expo.inOut' }, '<');
    if (has('.bf-eyebrow')) tl.from($('.bf-eyebrow'), { autoAlpha: 0, x: -30, duration: 1 }, '-=.9');
    if (title) tl.from(title.querySelectorAll('.bf-ch'), {
        yPercent: 70, opacity: 0, filter: 'blur(14px)', scale: 1.15, duration: 1.4, stagger: .04
      }, '-=.7');
    if (isoEl) tl.to(iso, { v: 100, duration: 1.4, ease: 'power3.out', onUpdate: () => (isoEl.textContent = Math.round(iso.v)) }, '<');
    if (has('.bf-reticle')) tl.to($('.bf-reticle'), { scale: .85, duration: .18, yoyo: true, repeat: 1, ease: 'power2.inOut', onComplete: lock }, '-=.5');
    else tl.call(lock, null, '-=.5');
    if (has('.bf-gold')) tl.fromTo($('.bf-gold'), { '--bf-u': 0 }, { '--bf-u': 1, duration: 1.1, ease: 'expo.inOut' }, '<');
    if (has('.bf-rule')) tl.from($('.bf-rule'), { scaleX: 0, duration: 1.4, ease: 'expo.inOut' }, '-=.9');
    const tail = [...$$('.bf-lead, .bf-cta')];
    if (tail.length) tl.from(tail, { autoAlpha: 0, y: 30, duration: 1.2, stagger: .12 }, '-=1');

    /* ---------- Scroll: the frame opens up ---------- */
    const st = { trigger: hero, start: 'top top', end: 'bottom top', scrub: true };
    if (title) gsap.to(title, { yPercent: -35, opacity: .15, ease: 'none', scrollTrigger: st });
    [['tl', -1, -1], ['tr', 1, -1], ['bl', -1, 1], ['br', 1, 1]].forEach(([c, sx, sy]) => {
      const el = $('.bf-vf__c--' + c);
      if (el) gsap.to(el, { x: sx * 40, y: sy * 40, ease: 'none', scrollTrigger: st });
    });
    const fade = [...$$('.bf-vf__hud, .bf-reticle')];
    if (fade.length) gsap.to(fade, { opacity: 0, ease: 'none', scrollTrigger: { ...st, end: 'center top' } });
    if (has('.svc-hero__num')) gsap.to($('.svc-hero__num'), { yPercent: 25, ease: 'none', scrollTrigger: st });

    /* ---------- Pointer (desktop only) ---------- */
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      const ret = $('.bf-reticle');
      const rx = ret && gsap.quickTo(ret, 'x', { duration: .9, ease: 'power3' }), ry = ret && gsap.quickTo(ret, 'y', { duration: .9, ease: 'power3' });
      const tx = title && gsap.quickTo(title, 'x', { duration: 1.2, ease: 'power3' });
      const move = (nx, ny) => { if (ret) { rx(nx * 60); ry(ny * 40); } if (tx) tx(nx * -14); };
      hero.addEventListener('mousemove', e => {
        const r = hero.getBoundingClientRect();
        move((e.clientX - r.left) / r.width - .5, (e.clientY - r.top) / r.height - .5);
      });
      hero.addEventListener('mouseleave', () => move(0, 0));
    }
  }

  document.querySelectorAll('.bf-hero').forEach(init);
})();

/* Shot-list cards: soft light follows the pointer */
document.querySelectorAll('.shot__frame').forEach(f => {
  f.addEventListener('mousemove', e => {
    const r = f.getBoundingClientRect();
    f.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
    f.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
  });
});
