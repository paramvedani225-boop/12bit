/* Homepage: loader, hero intro, horizontal projects scroll. */
(function () {
  const App = window.App;

  /* Project cards and floating gallery frames are rendered by index.php */
  const track = document.querySelector('.hp__track');

  /* ---------- Horizontal scroll (desktop only) ---------- */
  if (!App.reduced) {
    gsap.matchMedia().add('(min-width: 992px)', () => {
      const dist = () => Math.max(0, track.scrollWidth - window.innerWidth);
      // Lenis already smooths the scroll, so a short scrub keeps the slide tight and in sync
      gsap.to(track, {
        x: () => -dist(), ease: 'none',
        scrollTrigger: { trigger: '.hp', start: 'top top', end: () => '+=' + dist(), pin: true, pinSpacing: true, anticipatePin: 1, scrub: 0.4, invalidateOnRefresh: true }
      });
      // fade the cards in once when the section arrives (they move sideways, so per-card scroll reveals don't fit)
      gsap.from('.hp__item', { autoAlpha: 0, y: 40, duration: 1, stagger: .08, ease: 'expo.out', scrollTrigger: { trigger: '.hp', start: 'top 70%' } });
    });
  }

  /* ---------- Loader → hero intro ---------- */
  const loader = document.querySelector('.loader');
  const num = loader.querySelector('.loader__num');
  const circle = loader.querySelector('circle');
  App.onHeroProgress = p => {
    num.textContent = Math.round(p * 100);
    circle.style.strokeDashoffset = 345 * (1 - p);
  };
  const intro = () => {
    const tl = gsap.timeline();
    tl.to(loader, { clipPath: 'circle(0% at 50% 50%)', duration: 1.1, ease: 'expo.inOut' })
      .set(loader, { display: 'none' })
      .from('.hero-layer--intro .wi', { yPercent: 110, duration: 1.3, stagger: .06, ease: 'expo.out' }, '-=.4')
      .from('.hero-layer--intro .sub > *, .hero-meta', { autoAlpha: 0, y: 20, duration: 1, stagger: .1, ease: 'expo.out' }, '-=.9');
  };
  // split headline words for the intro (no scroll trigger)
  document.querySelectorAll('.hero-layer--intro .display-xl').forEach(el => {
    el.innerHTML = el.innerHTML.replace(/(<br>)|([^\s<>]+)(?![^<]*>)/g, (m, br, w) => br ? br : `<span class="w"><span class="wi">${w}</span></span>`);
  });
  gsap.set(loader, { clipPath: 'circle(150% at 50% 50%)' });
  Promise.race([App.heroReady, new Promise(r => setTimeout(r, 3500))]).then(() => {
    App.onHeroProgress(1);
    setTimeout(intro, 250);
  });
})();
