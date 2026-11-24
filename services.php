<?php
require __DIR__ . '/includes/bootstrap.php';

$page        = 'services';
$title       = 'Photography Services — ' . $SITE['studioName'];
$description = 'Wedding, pre-wedding, portrait, family, event, commercial, product, fashion, food and architecture photography.';

$groupTitles = [
  'People & Weddings'   => 'People &amp; <span class="italic gold">Weddings</span>',
  'Brands & Commercial' => 'Brands &amp; <span class="italic gold">Commercial</span>',
  'Add-ons'             => 'Add-<span class="italic gold">ons</span>',
];

require __DIR__ . '/includes/header.php';
?>
  <main>
    <section class="page-hero">
      <div class="container-lux">
        <span class="eyebrow" data-reveal="fade">Services</span>
        <h1 class="display-xl" data-reveal="text">What we <span class="italic gold">photograph.</span></h1>
        <div class="page-hero__meta">
          <p class="lead-lux m-0" data-reveal="fade">From your wedding day to your next product launch — every shoot is planned, lit and edited with the same cinematic care.</p>
        </div>
      </div>
    </section>
    <section class="pb-5">
      <div class="container-lux">
<?php foreach ($DATA['services'] as $group => $items): ?>
        <div class="svc-group">
          <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4"><h2 class="display-m" data-reveal="text"><?= $groupTitles[$group] ?? e($group) ?></h2><span class="label"><?= num2(count($items)) ?> services</span></div>
<?php foreach ($items as $i => $s): ?>
          <div class="svc-row">
            <span class="n"><?= num2($i + 1) ?></span>
            <h3><a href="service.php?id=<?= e(urlencode($s['slug'])) ?>"><?= e($s['name']) ?></a></h3>
            <p><?= e($s['desc']) ?></p>
            <div class="svc-row__links"><a href="service.php?id=<?= e(urlencode($s['slug'])) ?>" class="link-line">Details</a><a href="contact.php?type=<?= e(rawurlencode($s['name'])) ?>" class="link-line">Enquire</a></div>
          </div>
<?php endforeach; ?>
        </div>
<?php endforeach; ?>
      </div>
    </section>
    <section class="section bg-2">
      <div class="container-lux">
        <span class="eyebrow">How a shoot works</span>
        <h2 class="display-l mt-3 mb-5" data-reveal="text">From first call to <span class="italic gold">final frame.</span></h2>
        <div class="method">
          <div class="method__step" data-reveal="fade"><span class="n">01</span><h4>Consult</h4><p>A short call to understand the occasion or brief, the people, the places and your budget.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".1"><span class="n">02</span><h4>Plan</h4><p>Moodboard, shot list, locations and timings — so the day itself feels relaxed.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".2"><span class="n">03</span><h4>Shoot</h4><p>We work quietly and with intent, guiding when needed and observing when it matters.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".3"><span class="n">04</span><h4>Deliver</h4><p>Hand-edited, colour-graded images in a private online gallery, plus albums and prints if you wish.</p></div>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container-lux about-split">
        <div class="frame"><div class="media media--4x5 wm" data-reveal="iris"><img src="assets/images/gallery/wedding/serene-south-asian-bridal-portrait.webp" alt="Bridal portrait by the window" loading="lazy" style="object-position:center 25%"></div></div>
        <div>
          <span class="eyebrow">Book a shoot</span>
          <h2 class="display-l mt-3" data-reveal="text">One studio. <span class="italic gold">Every frame.</span></h2>
          <p class="lead-lux mt-4" data-reveal="fade">Tell us the date, the place and what matters most to you. We'll reply with availability and a tailored quote within two working days.</p>
          <a href="contact.php" class="btn-lux btn-lux--solid mt-4"><span>Check availability</span><span class="arr"></span></a>
        </div>
      </div>
    </section>
  </main>

<?php
require __DIR__ . '/includes/footer.php';
