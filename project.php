<?php
require __DIR__ . '/includes/bootstrap.php';

/* Case study page — rendered from $DATA['projects'] via ?id=<slug> */
$projects = $DATA['projects'];
$idx = null;
foreach ($projects as $k => $pr) {
  if ($pr['slug'] === ($_GET['id'] ?? '')) { $idx = $k; break; }
}
if ($idx === null) {
  // unknown / missing id → first project when no id given, 404 for a wrong id
  if (isset($_GET['id']) && $_GET['id'] !== '') http_response_code(404);
  $idx = 0;
}
$p    = $projects[$idx];
$next = $projects[($idx + 1) % count($projects)];

$page        = 'project';
$title       = $p['title'] . ' — ' . $SITE['studioName'];
$description = $p['short'];

require __DIR__ . '/includes/header.php';
?>
  <main id="case">
    <section class="case-hero">
      <div class="media <?= wm_class($p) ?>"><img src="<?= e($p['cover']) ?>" alt="<?= e($p['title']) ?>" data-parallax="0.1" style="height:130%;position:relative;top:-15%<?= !empty($p['pos']) ? ';object-position:' . e($p['pos']) : '' ?>"></div>
      <div class="case-hero__copy">
        <span class="eyebrow"><?= e($p['type']) ?> — <?= e($p['category']) ?></span>
        <h1 class="display-xl mt-3" data-reveal="text"><?= e($p['title']) ?></h1>
        <p class="lead-lux mt-3" data-reveal="fade"><?= e($p['short']) ?></p>
      </div>
    </section>
    <section class="section--tight">
      <div class="container-lux">
        <div class="case-meta" data-reveal="fade">
          <div><span class="label">Client</span><strong><?= e($p['client']) ?></strong></div>
          <div><span class="label">Year</span><strong><?= e($p['year']) ?></strong></div>
          <div><span class="label">Discipline</span><strong><?= e($p['category']) ?></strong></div>
          <div><span class="label">Role</span><strong><?= e($p['role']) ?></strong></div>
        </div>
      </div>
    </section>
    <section class="section pt-4">
      <div class="container-lux case-body">
        <div><span class="eyebrow">Overview</span></div>
        <div>
          <p class="statement" data-reveal="text"><?= e($p['overview']) ?></p>
          <div class="row mt-5 gx-0 gy-4 column-gap-5 flex-md-nowrap">
            <div class="col-md" data-reveal="fade"><h4>The challenge</h4><p class="muted"><?= e($p['challenge']) ?></p></div>
            <div class="col-md" data-reveal="fade"><h4>Our approach</h4><p class="muted"><?= e($p['approach']) ?></p></div>
          </div>
        </div>
      </div>
    </section>
    <section class="pb-5">
      <div class="container-lux case-gallery case-gallery--packed">
<?php $gal = array_map('photo_by_src', $p['gallery']); $gSpans = pack_spans(array_column($gal, 'shape'), 12);
      foreach ($gal as $gi => $g): $src = $g['src']; ?>
        <div class="frame" style="grid-column: span <?= $gSpans[$gi] ?>"><div class="media <?= ratio_class($g['shape']) ?> <?= wm_class($p, true) ?>" data-reveal="clip"><img src="<?= e($src) ?>" alt="<?= e($g['title'] ?? $p['title']) ?> — <?= e($p['title']) ?>" loading="lazy"<?= img_pos($g) ?>></div></div>
<?php endforeach; ?>
      </div>
    </section>
    <a class="next-proj" href="project.php?id=<?= e(urlencode($next['slug'])) ?>">
      <span class="label">Next project</span>
      <div class="display-l mt-3"><?= e($next['title']) ?></div>
      <span class="link-line mt-4"><?= e($next['category']) ?></span>
    </a>
  </main>

<?php
require __DIR__ . '/includes/footer.php';
