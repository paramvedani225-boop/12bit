<?php
require __DIR__ . '/includes/bootstrap.php';

$page        = 'photography';
$title       = 'Photography Portfolio — ' . $SITE['studioName'];
$description = 'Wedding, pre-wedding and studio portrait photography portfolio.';
$extraCss    = ['assets/vendor/swiper-bundle.min.css'];

$cats    = array_merge(['All'], $DATA['photoCategories']);
$current = in_array($_GET['cat'] ?? '', $cats, true) ? $_GET['cat'] : 'All';
$list    = $current === 'All' ? $DATA['photos'] : array_values(array_filter($DATA['photos'], fn($p) => $p['cat'] === $current));

/* grid spans packed by image shape (see pack_spans in includes/bootstrap.php) */
$spans = pack_spans(array_column($list, 'shape'));
$ratio = ['wide' => 'media--3x2', 'tall' => 'media--4x5', 'square' => 'media--1x1'];

require __DIR__ . '/includes/header.php';
?>
  <main>
    <section class="page-hero">
      <div class="container-lux">
        <span class="eyebrow" data-reveal="fade">Photography Portfolio</span>
        <h1 class="display-xl" data-reveal="text">Light, held <span class="italic gold">still.</span></h1>
        <div class="page-hero__meta">
          <p class="lead-lux m-0" data-reveal="fade">Weddings, pre-wedding stories and studio portraits — composed, cinematic and deliberate. Select a category or open any frame full-screen.</p>
          <span class="label" data-reveal="fade">Scroll ↓</span>
        </div>
      </div>
    </section>
    <section class="pb-5">
      <div class="container-lux">
        <div class="filters" role="toolbar" aria-label="Filter by category">
<?php foreach ($cats as $c):
  $n = $c === 'All' ? count($DATA['photos']) : count(array_filter($DATA['photos'], fn($p) => $p['cat'] === $c)); ?>
          <a class="filter-btn <?= $c === $current ? 'active' : '' ?>" data-cat="<?= e($c) ?>" href="photography.php<?= $c === 'All' ? '' : '?cat=' . e(urlencode($c)) ?>"><?= e($c) ?><sup><?= $n ?></sup></a>
<?php endforeach; ?>
        </div>
        <div class="pgrid">
<?php foreach ($list as $i => $p): ?>
          <figure class="pcard hover-zoom span-<?= $spans[$i] ?> <?= $i % 4 === 1 ? 'offset' : '' ?> m-0" data-index="<?= $i ?>" data-cursor="view" tabindex="0" role="button" aria-label="Open <?= e($p['title']) ?>">
            <div class="frame"><div class="media <?= $ratio[$p['shape']] ?> <?= wm_class($p, true) ?>" data-reveal="clip"><img src="<?= e($p['src']) ?>" alt="<?= e($p['title'] . ' — ' . $p['cat']) ?> photography" loading="lazy"<?= img_pos($p) ?>></div></div>
            <figcaption class="pcard__cap"><div><h3><?= e($p['title']) ?></h3><p><?= e($p['desc']) ?></p></div><span class="label"><?= e($p['cat']) ?></span></figcaption>
          </figure>
<?php endforeach; ?>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container-lux text-center">
        <span class="eyebrow">Commission us</span>
        <h2 class="display-l mt-3 mb-4" data-reveal="text">Have a story to <span class="italic gold">tell?</span></h2>
        <a href="contact.php" class="btn-lux btn-lux--solid"><span>Book a shoot</span><span class="arr"></span></a>
      </div>
    </section>
  </main>

  <div class="lightbox" aria-hidden="true" role="dialog" aria-label="Image viewer">
    <div class="lightbox__bar"><span class="label lb-count"></span><button class="lightbox__close" aria-label="Close">&times;</button></div>
    <div class="swiper"><div class="swiper-wrapper"></div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div>
  </div>

<?php
$extraJs    = ['assets/vendor/swiper-bundle.min.js'];
$pageJs     = ['assets/js/photography.js'];
$exposeData = true;
require __DIR__ . '/includes/footer.php';
