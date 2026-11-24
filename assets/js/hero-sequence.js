/* ==========================================================
   HERO — scroll-controlled frame sequence on <canvas>
   - original frames are never modified
   - studio watermark lives on a separate overlay canvas (no flicker)
   ========================================================== */
(function () {
  const S = window.SITE, H = S.hero, App = window.App;
  const section = document.querySelector('.hero-seq');
  if (!section) return;

  const canvas = section.querySelector('.hero-canvas');
  const ctx = canvas.getContext('2d');
  const wmCanvas = section.querySelector('.hero-wm');
  const wmCtx = wmCanvas.getContext('2d');
  const poster = section.querySelector('.hero-poster');
  const bar = section.querySelector('.hero-progress span');
  const counter = section.querySelector('.hero-frame-count');

  const set = window.innerWidth < H.mobileBreakpoint ? H.mobile : H.desktop;
  const count = set.count;
  const src = i => set.path + String(i + 1).padStart(H.pad, '0') + H.ext;
  const frames = new Array(count);   // decoded Image objects (cache)
  let current = -1, target = 0, rafId = 0, dpr = 1, cw = 0, ch = 0;

  /* ---------- sizing ---------- */
  function resize() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    cw = canvas.clientWidth; ch = canvas.clientHeight;
    [canvas, wmCanvas].forEach(c => { c.width = Math.round(cw * dpr); c.height = Math.round(ch * dpr); });
    current = -1; draw(target);
    drawWatermark();
  }

  /* ---------- drawing: cover-fit, aspect ratio preserved ---------- */
  function nearestLoaded(i) {
    if (frames[i]) return frames[i];
    for (let d = 1; d < count; d++) {
      if (frames[i - d]) return frames[i - d];
      if (frames[i + d]) return frames[i + d];
    }
    return null;
  }
  function draw(i) {
    const img = nearestLoaded(i);
    if (!img) return;
    const iw = img.naturalWidth, ih = img.naturalHeight;
    const scale = Math.max(canvas.width / iw, canvas.height / ih);
    const w = iw * scale, h = ih * scale;
    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';
    ctx.drawImage(img, (canvas.width - w) / 2, (canvas.height - h) / 2, w, h);
    if (poster && !poster.hidden) poster.hidden = true;
    current = i;
  }
  function requestDraw(i) {
    target = i;
    if (rafId) return;
    rafId = requestAnimationFrame(() => { rafId = 0; if (target !== current) draw(target); });
  }

  /* ---------- watermark overlay (drawn once per resize) ---------- */
  const logoImg = new Image();
  function drawWatermark() {
    const wm = S.watermark || {};
    wmCtx.clearRect(0, 0, wmCanvas.width, wmCanvas.height);
    if (wm.enabled === false) return;
    const cs = getComputedStyle(document.documentElement);
    const opacity = parseFloat(cs.getPropertyValue('--wm-opacity')) || .3;
    const color = cs.getPropertyValue('--wm-color').trim() || '#F5F3EF';
    const size = Math.max(9, Math.min(12, cw * 0.0075)) * dpr;
    const x = Math.max(14, Math.min(26, cw * 0.016)) * dpr;
    const y = wmCanvas.height - Math.max(12, Math.min(22, cw * 0.014)) * dpr;
    wmCtx.globalAlpha = opacity;
    if (wm.mode === 'logo' && wm.logo) {
      const draw = () => { const w = Math.max(60, Math.min(110, cw * .07)) * dpr; const h = w * (logoImg.naturalHeight / logoImg.naturalWidth || .25); wmCtx.drawImage(logoImg, x, y - h, w, h); };
      if (logoImg.complete && logoImg.naturalWidth) draw(); else { logoImg.onload = draw; logoImg.src = wm.logo; }
    } else {
      wmCtx.fillStyle = color;
      wmCtx.font = `600 ${size}px Manrope, Arial, sans-serif`;
      if ('letterSpacing' in wmCtx) wmCtx.letterSpacing = `${size * .28}px`;
      wmCtx.textBaseline = 'alphabetic';
      wmCtx.fillText(S.studioName.toUpperCase(), x, y - size * 1.6);
      wmCtx.fillText(S.tagline.toUpperCase(), x, y);
    }
    wmCtx.globalAlpha = 1;
  }

  /* ---------- progressive preloading: every 8th → 4th → 2nd → all ---------- */
  function loadFrame(i) {
    return new Promise(res => {
      if (frames[i]) return res();
      const img = new Image();
      img.decoding = 'async';
      img.src = src(i);
      const done = () => { frames[i] = img; if (Math.abs(i - target) < 4) requestDraw(target); res(); };
      (img.decode ? img.decode() : new Promise((r, j) => { img.onload = r; img.onerror = j; })).then(done).catch(() => res());
    });
  }
  async function loadPass(step, onProgress) {
    const list = [];
    for (let i = 0; i < count; i += step) if (!frames[i]) list.push(i);
    let n = 0;
    const queue = list.slice();
    const worker = async () => { while (queue.length) { await loadFrame(queue.shift()); onProgress && onProgress(++n / list.length); } };
    await Promise.all(Array.from({ length: 8 }, worker));
  }

  App.heroReady = (async function () {
    await loadFrame(0); requestDraw(0);
    await loadPass(8, p => App.onHeroProgress && App.onHeroProgress(p));
    // remaining passes continue in the background
    (async () => { await loadPass(4); await loadPass(2); await loadPass(1); })();
  })();

  /* ---------- scroll binding ---------- */
  ScrollTrigger.create({
    trigger: section, start: 'top top', end: 'bottom bottom', scrub: true,
    onUpdate: self => {
      const i = Math.min(count - 1, Math.round(self.progress * (count - 1)));
      requestDraw(i);
      if (bar) bar.style.transform = `scaleX(${self.progress})`;
      if (counter) counter.textContent = String(i + 1).padStart(3, '0') + ' / ' + count;
    }
  });

  /* Text layers mapped to the video's scenes (progress 0 → 1) */
  const tl = gsap.timeline({ scrollTrigger: { trigger: section, start: 'top top', end: 'bottom bottom', scrub: .6 } });
  tl.to('.hero-layer--intro', { autoAlpha: 0, y: -80, duration: .12, ease: 'none' }, .04)       // Scene 1 → 2
    .to('.scroll-ind', { autoAlpha: 0, duration: .05 }, .02)
    .fromTo('.hero-layer--mid', { autoAlpha: 0, y: 40 }, { autoAlpha: 1, y: 0, duration: .07 }, .44)   // Scene 3: through the glass
    .to('.hero-layer--mid', { autoAlpha: 0, y: -40, duration: .07 }, .58)
    .fromTo('.hero-layer--mid2', { autoAlpha: 0, x: -40 }, { autoAlpha: 1, x: 0, duration: .06 }, .66) // Scene 4: floating gallery
    .to('.hero-layer--mid2', { autoAlpha: 0, duration: .05 }, .79)
    .fromTo('.hero-layer--end', { autoAlpha: 0, scale: .96 }, { autoAlpha: 1, scale: 1, duration: .1 }, .86) // Scene 5: orb → brand
    .to({}, { duration: .04 }, .96);

  window.addEventListener('resize', () => { clearTimeout(resize._t); resize._t = setTimeout(resize, 120); });
  resize();
})();
