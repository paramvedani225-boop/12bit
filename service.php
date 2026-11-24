<?php
require __DIR__ . '/includes/bootstrap.php';

/* Service detail page — service.php?id=<slug>
   List + names: $DATA['services'] (includes/data.php) · page content: includes/service-details.php */
$DETAILS = require __DIR__ . '/includes/service-details.php';

/* flatten the grouped services list, remembering each one's group */
$all = [];
foreach ($DATA['services'] as $group => $items) {
  foreach ($items as $it) $all[] = $it + ['group' => $group];
}

$idx = null;
foreach ($all as $k => $it) {
  if ($it['slug'] === ($_GET['id'] ?? '')) { $idx = $k; break; }
}
if ($idx === null) {
  // unknown or missing id → go to the services overview (404 status for a wrong id)
  if (isset($_GET['id']) && $_GET['id'] !== '') http_response_code(404);
  else { header('Location: services.php', true, 302); exit; }
  $idx = 0;
}
$svc  = $all[$idx];
$d    = $DETAILS[$svc['slug']] ?? [];
$next = $all[($idx + 1) % count($all)];

/* gallery: a photo category, an explicit list of images, or nothing */
$gallery = [];
if (is_string($d['photos'] ?? null)) {
  $gallery = array_values(array_filter($DATA['photos'], fn($p) => $p['cat'] === $d['photos']));
} elseif (is_array($d['photos'] ?? null)) {
  $gallery = array_map('photo_by_src', $d['photos']);
}
$gSpans = pack_spans(array_column($gallery, 'shape'));

$enquire = 'contact.php?type=' . rawurlencode($svc['name']);

$page        = 'services';
$title       = $svc['name'] . ' — ' . $SITE['studioName'];
$description = $d['tagline'] ?? $svc['desc'];

require __DIR__ . '/includes/header.php';
?>
  <main>
<?php if (!empty($d['hero'])): ?>
    <section class="case-hero">
      <div class="media wm"><img src="<?= e($d['hero']) ?>" alt="<?= e($svc['name']) ?>" data-parallax="0.1" style="height:130%;position:relative;top:-15%<?= !empty($d['pos']) ? ';object-position:' . e($d['pos']) : '' ?>"></div>
      <div class="case-hero__copy">
        <span class="eyebrow"><a href="services.php" class="svc-crumb">Services</a> — <?= e($svc['group']) ?></span>
        <h1 class="display-xl mt-3" data-reveal="text"><?= e($svc['name']) ?></h1>
        <p class="lead-lux mt-3" data-reveal="fade"><?= e($d['tagline'] ?? $svc['desc']) ?></p>
      </div>
    </section>
<?php else:
  /* no photo hero yet → animated "viewfinder" hero (assets/js/vf-hero.js); last word of the name in gold */
  $words = preg_split('/\s+/', trim($svc['name']));
  $last  = array_pop($words);
  $hud   = array_slice($d['meta'] ?? [], 0, 1, true); ?>
    <section class="page-hero bf-hero svc-hero">
      <span class="svc-hero__num" aria-hidden="true"><?= num2($idx + 1) ?></span>
      <div class="bf-vf" aria-hidden="true">
        <span class="bf-vf__c bf-vf__c--tl"></span><span class="bf-vf__c bf-vf__c--tr"></span>
        <span class="bf-vf__c bf-vf__c--bl"></span><span class="bf-vf__c bf-vf__c--br"></span>
        <span class="bf-vf__hud bf-vf__hud--tl"><i class="bf-dot"></i><span class="bf-af">Focusing</span></span>
        <span class="bf-vf__hud bf-vf__hud--tr">Service <b><?= num2($idx + 1) ?></b> / <?= num2(count($all)) ?></span>
        <span class="bf-vf__hud bf-vf__hud--br"><?php foreach ($hud as $k => $v): ?><?= e($k) ?> &middot; <b><?= e($v) ?></b> &nbsp;|&nbsp; <?php endforeach; ?>ISO <b class="bf-iso">100</b></span>
      </div>
      <div class="container-lux bf-inner">
        <span class="eyebrow bf-eyebrow"><a href="services.php" class="svc-crumb">Services</a> — <?= e($svc['group']) ?></span>
        <h1 class="display-xl bf-title"><?= $words ? e(implode(' ', $words)) . ' ' : '' ?><span class="italic gold bf-gold"><?= e($last) ?></span></h1>
        <div class="page-hero__meta">
          <span class="bf-rule" aria-hidden="true"></span>
          <p class="lead-lux m-0 bf-lead"><?= e($d['tagline'] ?? $svc['desc']) ?></p>
          <a href="<?= e($enquire) ?>" class="btn-lux btn-lux--solid bf-cta"><span>Enquire now</span><span class="arr"></span></a>
        </div>
      </div>
    </section>
<?php if (!empty($d['includes'])): ?>
    <div class="svc-ticker" aria-hidden="true"><div class="svc-ticker__track">
<?php for ($r = 0; $r < 2; $r++): foreach ($d['includes'] as $item): ?>
      <span><?= e($item) ?></span><i>✦</i>
<?php endforeach; endfor; ?>
    </div></div>
<?php endif; ?>
<?php endif; ?>

<?php if (!empty($d['meta'])): ?>
    <section class="section--tight">
      <div class="container-lux">
        <div class="case-meta" data-reveal="fade">
<?php foreach ($d['meta'] as $label => $value): ?>
          <div><span class="label"><?= e($label) ?></span><strong><?= e($value) ?></strong></div>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

    <section class="section pt-4">
      <div class="container-lux case-body">
        <div><span class="eyebrow">Overview</span></div>
        <div>
          <p class="statement" data-reveal="text"><?= e($d['intro'] ?? $svc['desc']) ?></p>
          <div class="row mt-5 gx-0 gy-4 column-gap-5 flex-md-nowrap">
<?php if (!empty($d['includes'])): ?>
            <div class="col-md" data-reveal="fade"><h4>What's included</h4>
              <ul class="svc-points">
<?php foreach ($d['includes'] as $item): ?>
                <li><?= e($item) ?></li>
<?php endforeach; ?>
              </ul>
            </div>
<?php endif; ?>
<?php if (!empty($d['deliver'])): ?>
            <div class="col-md" data-reveal="fade"><h4>What you receive</h4>
              <ul class="svc-points">
<?php foreach ($d['deliver'] as $item): ?>
                <li><?= e($item) ?></li>
<?php endforeach; ?>
              </ul>
            </div>
<?php endif; ?>
          </div>
          <a href="<?= e($enquire) ?>" class="btn-lux btn-lux--solid mt-5" data-reveal="fade"><span>Check availability</span><span class="arr"></span></a>
        </div>
      </div>
    </section>

<?php if ($gallery): ?>
    <section class="section bg-2">
      <div class="container-lux">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
          <div><span class="eyebrow">Recent work</span><h2 class="display-l mt-3" data-reveal="text">From the <span class="italic gold">portfolio.</span></h2></div>
<?php if (is_string($d['photos'])): ?>
          <a href="photography.php?cat=<?= e(urlencode($d['photos'])) ?>" class="link-line">View all <?= e(strtolower($d['photos'])) ?> photos</a>
<?php else: ?>
          <a href="photography.php" class="link-line">View the full gallery</a>
<?php endif; ?>
        </div>
        <div class="pgrid">
<?php foreach ($gallery as $i => $g): ?>
          <figure class="pcard span-<?= $gSpans[$i] ?> <?= $i % 4 === 1 ? 'offset' : '' ?> m-0">
            <div class="frame"><div class="media <?= ratio_class($g['shape']) ?> <?= wm_class($g, true) ?>" data-reveal="clip"><img src="<?= e($g['src']) ?>" alt="<?= e(($g['title'] ?? $svc['name']) . ' — ' . $svc['name']) ?>" loading="lazy"<?= img_pos($g) ?>></div></div>
<?php if (!empty($g['title'])): ?>
            <figcaption class="pcard__cap"><div><h3><?= e($g['title']) ?></h3></div></figcaption>
<?php endif; ?>
          </figure>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

<?php if (!$gallery && !empty($d['shots'])): ?>
    <section class="section bg-2">
      <div class="container-lux">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
          <div><span class="eyebrow">Shot list</span><h2 class="display-l mt-3" data-reveal="text">What we'll <span class="italic gold">capture.</span></h2></div>
          <span class="label"><?= num2(count($d['shots'])) ?> frames</span>
        </div>
        <div class="shotlist">
<?php foreach ($d['shots'] as $i => [$shotTitle, $shotLine]): ?>
          <article class="shot" data-reveal="fade" data-delay="<?= ($i % 4) / 10 ?>">
            <div class="shot__frame">
              <i class="shot__c shot__c--tl"></i><i class="shot__c shot__c--tr"></i><i class="shot__c shot__c--bl"></i><i class="shot__c shot__c--br"></i>
              <span class="shot__n"><?= num2($i + 1) ?></span>
              <span class="shot__hud">Frame <?= num2($i + 1) ?> · RAW</span>
              <h3 class="shot__t"><?= e($shotTitle) ?></h3>
            </div>
            <p><?= e($shotLine) ?></p>
          </article>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

    <section class="section<?= ($gallery || !empty($d['shots'])) ? '' : ' bg-2' ?>">
      <div class="container-lux">
        <span class="eyebrow">How it works</span>
        <h2 class="display-l mt-3 mb-5" data-reveal="text">From first call to <span class="italic gold">final frame.</span></h2>
        <div class="method">
          <div class="method__step" data-reveal="fade"><span class="n">01</span><h4>Consult</h4><p>A short call about your date, place, people and budget. We reply with availability and a tailored quote.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".1"><span class="n">02</span><h4>Plan</h4><p>Moodboard, shot list, locations and timings — shared with you before the day.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".2"><span class="n">03</span><h4>Shoot</h4><p>We work calmly and with intent, guiding when needed and observing when it matters.</p></div>
          <div class="method__step" data-reveal="fade" data-delay=".3"><span class="n">04</span><h4>Deliver</h4><p>Hand-edited images in a private online gallery, plus albums and prints if you wish.</p></div>
        </div>
      </div>
    </section>

<?php if (!empty($d['faq'])): ?>
    <section class="section pt-0">
      <div class="container-lux case-body">
        <div><span class="eyebrow">Questions</span><h2 class="display-m mt-3" data-reveal="text">Good to <span class="italic gold">know.</span></h2></div>
        <div class="faq">
<?php foreach ($d['faq'] as [$q, $a]): ?>
          <details class="faq__item" data-reveal="fade">
            <summary><?= e($q) ?></summary>
            <p><?= e($a) ?></p>
          </details>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

    <section class="section bg-2">
      <div class="container-lux text-center">
        <span class="eyebrow">Book <?= e($svc['name']) ?></span>
        <h2 class="display-l mt-3 mb-4" data-reveal="text">Let's plan your <span class="italic gold">shoot.</span></h2>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <a href="<?= e($enquire) ?>" class="btn-lux btn-lux--solid"><span>Enquire now</span><span class="arr"></span></a>
          <a href="services.php" class="btn-lux"><span>All services</span></a>
        </div>
      </div>
    </section>

    <a class="next-proj" href="service.php?id=<?= e(urlencode($next['slug'])) ?>">
      <span class="label">Next service</span>
      <div class="display-l mt-3"><?= e($next['name']) ?></div>
      <span class="link-line mt-4"><?= e($next['group']) ?></span>
    </a>
  </main>

<?php
if (empty($d['hero'])) $pageJs = ['assets/js/vf-hero.js'];
require __DIR__ . '/includes/footer.php';
