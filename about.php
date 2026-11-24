<?php
require __DIR__ . '/includes/bootstrap.php';

$page        = 'about';
$title       = 'About the Studio — ' . $SITE['studioName'];
$description = 'Beyond the frame — our studio, philosophy, process and team.';

require __DIR__ . '/includes/header.php';
?>
  <main>
    <section class="page-hero bf-hero" id="bfHero">
      <div class="bf-vf" aria-hidden="true">
        <span class="bf-vf__c bf-vf__c--tl"></span><span class="bf-vf__c bf-vf__c--tr"></span>
        <span class="bf-vf__c bf-vf__c--bl"></span><span class="bf-vf__c bf-vf__c--br"></span>
        <span class="bf-vf__hud bf-vf__hud--tl"><i class="bf-dot"></i><span class="bf-af">Focusing</span></span>
        <span class="bf-vf__hud bf-vf__hud--br">f/2.8 &middot; 1/250 &middot; ISO <b class="bf-iso">100</b></span>
        <span class="bf-reticle"><i></i></span>
      </div>
      <div class="container-lux bf-inner">
        <span class="eyebrow bf-eyebrow">About the Studio</span>
        <h1 class="display-xl bf-title">BEYOND THE <span class="italic gold bf-gold">FRAME.</span></h1>
        <div class="page-hero__meta">
          <span class="bf-rule" aria-hidden="true"></span>
          <p class="lead-lux m-0 bf-lead">We turn moments, people and products into photographs with light, intent and story.</p>
        </div>
      </div>
    </section>

    <section class="pb-5">
      <div class="container-lux">
        <div class="frame"><div class="media media--16x9 wm" data-reveal="iris"><img src="assets/images/gallery/wedding/golden-palace-wedding-celebration.webp" alt="Couple at an evening palace wedding celebration" data-parallax="0.1" style="height:130%;position:relative;top:-15%"></div></div>
      </div>
    </section>

    <section class="section">
      <div class="container-lux about-split">
        <div>
          <span class="eyebrow">The Studio</span>
          <p class="statement mt-4" data-reveal="text">A small, senior team of photographers working as <em>one creative eye.</em></p>
        </div>
        <div>
          <p class="lead-lux" data-reveal="fade">Founded on the belief that every photograph should be made with intent, the studio photographs weddings, people and brands with the same cinematic approach — planned light, honest moments and a consistent, timeless grade.</p>
          <p class="muted mt-4" data-reveal="fade">Replace this paragraph with your studio's story: where you started, who you work with, and what makes your eye different.</p>
        </div>
      </div>
    </section>

    <section class="section bg-2">
      <div class="container-lux about-split">
        <div class="frame"><div class="media media--4x5 wm" data-reveal="clip"><img src="assets/images/gallery/studio/confident-warm-toned-studio-portrait.webp" alt="Warm studio portrait" loading="lazy" style="object-position:center 30%"></div></div>
        <div>
          <span class="eyebrow">Philosophy</span>
          <h2 class="display-m mt-4" data-reveal="text">Restraint is a <span class="italic gold">luxury.</span></h2>
          <p class="lead-lux mt-4" data-reveal="fade">One light. One idea. Nothing that doesn't earn its place in the frame. We photograph for longevity, not trends — work that still feels right in ten years.</p>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container-lux">
        <span class="eyebrow">Methodology</span>
        <h2 class="display-l mt-3 mb-5" data-reveal="text">How we <span class="italic gold">work.</span></h2>
        <div class="method">
          <div class="method__step" data-reveal="fade"><span class="n">01</span><h4>Discover</h4><p>We listen first — the occasion or brief, the people, references and constraints.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".1"><span class="n">02</span><h4>Direct</h4><p>Moodboards, shot lists, locations and timings that set a clear direction.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".2"><span class="n">03</span><h4>Create</h4><p>Shoot days, natural direction and meticulous editing and retouching.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".3"><span class="n">04</span><h4>Deliver</h4><p>Edited galleries, albums and prints — ready to share and to keep.</p></div>
        </div>
        <div class="stats">
          <div class="stat"><div class="num" data-count="12" data-suffix="+">0</div><span class="label">Years of experience</span></div>
          <div class="stat"><div class="num" data-count="340" data-suffix="+">0</div><span class="label">Projects delivered</span></div>
          <div class="stat"><div class="num" data-count="85">0</div><span class="label">Happy clients</span></div>
          <div class="stat"><div class="num" data-count="18">0</div><span class="label">Awards & features</span></div>
        </div>
      </div>
    </section>

    <section class="section--tight">
      <div class="container-lux">
        <div class="frame"><div class="media media--16x9 wm" data-reveal="clip"><img src="assets/images/gallery/wedding/golden-hour-indian-wedding-baraat.webp" alt="Groom arriving at a colourful baraat" loading="lazy"></div></div>
      </div>
    </section>

    <section class="section">
      <div class="container-lux">
        <span class="eyebrow">The Team</span>
        <h2 class="display-l mt-3 mb-5" data-reveal="text">The people <span class="italic gold">behind the lens.</span></h2>
        <div class="team" id="team">
<?php foreach ($DATA['team'] as $m):
  $mono = implode('', array_map(fn($w) => mb_substr($w, 0, 1), preg_split('/\s+/', trim($m['name'])))); ?>
          <div class="team__card" data-reveal="fade">
            <div class="mono"><?= e($mono) ?></div>
            <h4><?= e($m['name']) ?></h4><span class="label"><?= e($m['role']) ?></span>
          </div>
<?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

<?php
$pageJs = ['assets/js/vf-hero.js'];
require __DIR__ . '/includes/footer.php';
