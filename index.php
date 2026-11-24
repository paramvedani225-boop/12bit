<?php
require __DIR__ . '/includes/bootstrap.php';

$page        = 'home';
$title       = $SITE['studioName'] . ' — Cinematic Photography Studio';
$description = 'Cinematic wedding, portrait, fashion, product and commercial photography.';
$preload     = [$SITE['hero']['poster']];

/* Floating gallery positions (echo of scene 4 of the hero video) */
$spots = [
  ['l' => 2, 't' => 4, 'w' => 20, 'p' => .35], ['l' => 27, 't' => 0, 'w' => 15, 'p' => .6], ['l' => 72, 't' => 3, 'w' => 22, 'p' => .3],
  ['l' => 5, 't' => 52, 'w' => 17, 'p' => .5], ['l' => 78, 't' => 50, 'w' => 17, 'p' => .7], ['l' => 24, 't' => 74, 'w' => 14, 'p' => .25],
  ['l' => 58, 't' => 76, 'w' => 19, 'p' => .45], ['l' => 50, 't' => 6, 'w' => 12, 'p' => .8],
];

require __DIR__ . '/includes/header.php';
?>

  <!-- Loader -->
  <div class="loader" role="status" aria-label="Loading">
    <div class="loader__ring">
      <svg viewBox="0 0 112 112" width="112" height="112"><circle cx="56" cy="56" r="55"/></svg>
      <div class="loader__num">0</div>
    </div>
    <div class="loader__brand"><?= e($SITE['studioName']) ?></div>
  </div>

  <main>
    <!-- HERO: scroll-controlled cinematic sequence -->
    <section class="hero-seq" aria-label="Intro">
      <div class="hero-sticky">
        <img class="hero-poster" src="<?= e($SITE['hero']['poster']) ?>" alt="" aria-hidden="true">
        <canvas class="hero-canvas" aria-hidden="true"></canvas>
        <div class="hero-shade"></div>
        <canvas class="hero-wm" aria-hidden="true"></canvas>

        <!-- Scene 1: the hall -->
        <div class="hero-layer hero-layer--intro">
          <span class="eyebrow mb-4">Cinematic Photography Studio</span>
          <h1 class="display-xl">WE CREATE<br><span class="italic gold">VISUAL</span> STORIES.</h1>
          <div class="sub">
            <p class="label m-0" style="color:var(--text)">Weddings. Portraits. Fashion. Commercial.</p>
            <div class="d-flex gap-2 flex-wrap">
              <a href="photography.php" class="btn-lux btn-lux--solid"><span>Explore our work</span><span class="arr"></span></a>
              <a href="contact.php" class="btn-lux"><span>Start a project</span></a>
            </div>
          </div>
        </div>
        <!-- Scene 3: through the glass -->
        <div class="hero-layer hero-layer--mid">
          <span class="eyebrow mb-3">Chapter 01</span>
          <p class="display-m m-0">Every story begins<br><span class="italic gold">behind the lens.</span></p>
        </div>
        <!-- Scene 4: floating gallery -->
        <div class="hero-layer hero-layer--mid2">
          <span class="eyebrow mb-3">Chapter 02</span>
          <p class="display-m m-0" style="max-width:12ch">Every frame,<br><span class="italic gold">a memory.</span></p>
        </div>
        <!-- Scene 5: the orb → brand reveal -->
        <div class="hero-layer hero-layer--end">
          <div class="brand-reveal"><?= e($SITE['studioName']) ?></div>
          <p class="label mt-2">Weddings · Portraits · Fashion · Commercial</p>
          <div class="ctas">
            <a href="photography.php" class="btn-lux btn-lux--solid"><span>Explore our work</span><span class="arr"></span></a>
            <a href="contact.php" class="btn-lux"><span>Start a project</span></a>
          </div>
        </div>

        <div class="hero-meta">
          <span style="width:140px"></span>
          <div class="scroll-ind"><i></i>Scroll</div>
          <div class="d-flex align-items-center gap-3"><div class="hero-progress"><span></span></div></div>
        </div>
      </div>
    </section>

    <!-- Intro -->
    <section class="section section--intro">
      <div class="container-lux">
        <div class="intro-grid">
          <div><span class="eyebrow" data-reveal="fade">(01) The Studio</span></div>
          <div>
            <p class="statement" data-reveal="text">An independent studio crafting <em>cinematic</em> photography for people and brands who refuse to be ordinary.</p>
            <p class="lead-lux mt-4" data-reveal="fade">We work where light, composition and story meet — from a single product still to a three-day wedding.</p>
            <a href="about.php" class="link-line mt-4" data-reveal="fade">About the studio</a>
          </div>
        </div>
        <div class="stats">
          <div class="stat"><div class="num" data-count="12" data-suffix="+">0</div><span class="label">Years behind the lens</span></div>
          <div class="stat"><div class="num" data-count="340" data-suffix="+">0</div><span class="label">Projects delivered</span></div>
          <div class="stat"><div class="num" data-count="85">0</div><span class="label">Happy clients</span></div>
          <div class="stat"><div class="num" data-count="18">0</div><span class="label">Awards & features</span></div>
        </div>
      </div>
    </section>

    <!-- Selected projects (horizontal scroll) -->
    <section class="hp section--tight bg-2" aria-label="Selected projects">
      <div class="container-lux">
        <div class="hp__head">
          <div><span class="eyebrow">(02) Selected Projects</span><h2 class="display-l mt-3" data-reveal="text">Selected <span class="italic gold">work.</span></h2></div>
          <p class="lead-lux m-0">Weddings, pre-wedding stories and studio portraits from recent commissions.</p>
        </div>
      </div>
      <div class="hp__track">
<?php foreach ($DATA['projects'] as $i => $p): ?>
        <a class="hp__item hover-zoom" href="project.php?id=<?= e(urlencode($p['slug'])) ?>" data-cursor="view">
          <div class="frame">
            <div class="media media--8x5 <?= wm_class($p) ?>"><img src="<?= e($p['cover']) ?>" alt="<?= e($p['title'] . ' — ' . $p['category']) ?>" decoding="async"<?= img_pos($p) ?>></div>
          </div>
          <div class="proj-meta">
            <div><span class="label"><?= e($p['category']) ?> · <?= e($p['year']) ?></span><h3 class="mt-2"><?= e($p['title']) ?></h3><p><?= e($p['short']) ?></p></div>
            <span class="proj-num"><?= num2($i + 1) ?></span>
          </div>
          <span class="link-line mt-3">View project</span>
        </a>
<?php endforeach; ?>
      </div>
    </section>

    <!-- Floating gallery (echo of scene 4) -->
    <section class="section" aria-label="Gallery">
      <div class="container-lux">
        <div class="float-gal">
<?php foreach (array_slice($DATA['photos'], 0, count($spots)) as $i => $ph): $s = $spots[$i]; ?>
          <a href="photography.php" class="frame hover-zoom" style="left:<?= $s['l'] ?>%;top:<?= $s['t'] ?>%;width:<?= $s['w'] ?>%" data-parallax="<?= $s['p'] ?>" data-cursor="view">
            <div class="media media--4x5 <?= wm_class($ph, true) ?>"><img src="<?= e($ph['src']) ?>" alt="<?= e($ph['title'] . ' — ' . $ph['cat']) ?>" loading="lazy"<?= img_pos($ph) ?>></div></a>
<?php endforeach; ?>
          <div class="center-copy">
            <div class="orb" aria-hidden="true"></div>
            <span class="eyebrow">(03) The Gallery</span>
            <h2 class="display-l mt-3" data-reveal="text">Beyond the <span class="italic gold">frame.</span></h2>
            <a href="photography.php" class="btn-lux mt-4"><span>View the gallery</span><span class="arr"></span></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Services teaser -->
    <section class="section bg-2">
      <div class="container-lux">
        <span class="eyebrow">(04) Services</span>
        <h2 class="display-l mt-3 mb-5" data-reveal="text">Every story. <span class="italic gold">One eye.</span></h2>
        <div class="svc-split">
<?php foreach (['People & Weddings', 'Brands & Commercial'] as $group): ?>
          <div class="svc-col">
            <h3 class="display-s"><?= e($group) ?></h3>
            <ul class="svc-list">
<?php foreach ($DATA['services'][$group] as $i => $s): ?>
              <li><a href="service.php?id=<?= e(urlencode($s['slug'])) ?>"><?= e($s['name']) ?> <small><?= num2($i + 1) ?></small></a></li>
<?php endforeach; ?>
            </ul>
            <a href="services.php" class="link-line">All <?= e(strtolower($group)) ?> services</a>
          </div>
<?php endforeach; ?>
        </div>
      </div>
    </section>

    <div class="marquee" aria-hidden="true">
      <div class="marquee__inner">
        <span>Weddings</span><span class="fill">✦</span><span>Portraits</span><span class="fill">✦</span><span>Fashion</span><span class="fill">✦</span><span>Commercial</span><span class="fill">✦</span>
        <span>Weddings</span><span class="fill">✦</span><span>Portraits</span><span class="fill">✦</span><span>Fashion</span><span class="fill">✦</span><span>Commercial</span><span class="fill">✦</span>
      </div>
    </div>
  </main>

<?php
$pageJs = ['assets/js/hero-sequence.js', 'assets/js/home.js'];
require __DIR__ . '/includes/footer.php';
