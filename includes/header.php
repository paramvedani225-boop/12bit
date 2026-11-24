<?php
/* Shared <head>, header, mobile menu, cursor and transition overlay.
   Set before including: $page (nav key), $title, $description, optional $extraCss (array), $preload (array). */
$page        = $page ?? '';
$title       = $title ?? $SITE['studioName'];
$description = $description ?? '';
$extraCss    = $extraCss ?? [];
$preload     = $preload ?? [];
$NAV_SVC     = $DETAILS ?? require __DIR__ . '/service-details.php';   // taglines + hero images for the Services dropdown
?><!DOCTYPE html>
<html lang="en" class="no-js">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($description) ?>">
<?php foreach ($preload as $p): ?>
  <link rel="preload" as="image" href="<?= e($p) ?>">
<?php endforeach; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/vendor/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendor/lenis.css">
<?php foreach ($extraCss as $css): ?>
  <link rel="stylesheet" href="<?= e($css) ?>">
<?php endforeach; ?>
  <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
</head>
<body data-page="<?= e($page) ?>">
  <div class="pt" aria-hidden="true"></div>
  <div class="cursor" aria-hidden="true"></div><div class="cursor-ring" aria-hidden="true"></div>
  <header class="nav" id="nav">
    <a href="index.php" class="nav__brand" aria-label="<?= e($SITE['studioName']) ?> home"><i></i><span><?= e($SITE['studioName']) ?></span></a>
    <ul class="nav__links">
<?php foreach (nav_links() as [$href, $label, $key]): ?>
<?php if ($key === 'services'): ?>
      <li class="dd" id="svcDd">
        <a href="<?= $href ?>" class="<?= $key === $page ? 'active' : '' ?>"><?= e($label) ?></a>
        <button class="dd__caret" aria-label="Show all services" aria-expanded="false" aria-controls="svcPanel"><i></i></button>
        <div class="dd__panel" id="svcPanel">
          <div class="dd__inner">
<?php $n = 0; $di = 0; foreach ($DATA['services'] as $group => $items): ?>
            <div class="dd__col">
              <span class="dd__group" style="--i:<?= $di++ ?>"><?= e($group) ?></span>
              <ul>
<?php foreach ($items as $s): $n++; $sd = $NAV_SVC[$s['slug']] ?? []; ?>
                <li style="--i:<?= $di++ ?>"><a class="dd__item" href="service.php?id=<?= e(urlencode($s['slug'])) ?>"
                     data-n="<?= num2($n) ?>" data-tag="<?= e($sd['tagline'] ?? $s['desc']) ?>" data-img="<?= e($sd['hero'] ?? '') ?>"<?= !empty($sd['pos']) ? ' data-pos="' . e($sd['pos']) . '"' : '' ?>><span class="n"><?= num2($n) ?></span><span class="t"><?= e($s['name']) ?></span></a></li>
<?php endforeach; ?>
              </ul>
            </div>
<?php endforeach; ?>
            <aside class="dd__preview" style="--i:<?= $di ?>" aria-hidden="true">
              <div class="dd__media"><img alt="" loading="lazy"><span class="dd__num">01</span></div>
              <p class="dd__tag"></p>
              <a href="services.php" class="link-line dd__all" tabindex="-1">All services</a>
            </aside>
          </div>
        </div>
      </li>
<?php else: ?>
      <li><a href="<?= $href ?>" class="<?= $key === $page ? 'active' : '' ?>"><?= e($label) ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
    </ul>
    <a href="contact.php" class="btn-lux nav__cta"><span>Start a project</span></a>
    <button class="nav__toggle" aria-label="Open menu" aria-expanded="false"><span></span><span></span></button>
  </header>
  <nav class="mmenu" aria-label="Mobile">
<?php foreach (nav_links() as [$href, $label, $key]): ?>
<?php if ($key === 'services'): ?>
    <div class="mgroup">
      <div class="mgroup__head"><a class="mlink <?= $key === $page ? 'active' : '' ?>" href="<?= $href ?>"><?= e($label) ?></a><button class="mgroup__btn" aria-label="Show all services" aria-expanded="false" aria-controls="mSvc"><i></i></button></div>
      <div class="msub" id="mSvc"><div><ul>
<?php $n = 0; foreach ($DATA['services'] as $items): foreach ($items as $s): $n++; ?>
        <li style="--i:<?= $n ?>"><a href="service.php?id=<?= e(urlencode($s['slug'])) ?>" tabindex="-1"><span><?= num2($n) ?></span><?= e($s['name']) ?></a></li>
<?php endforeach; endforeach; ?>
      </ul></div></div>
    </div>
<?php else: ?>
    <a class="mlink <?= $key === $page ? 'active' : '' ?>" href="<?= $href ?>"><?= e($label) ?></a>
<?php endif; ?>
<?php endforeach; ?>
    <div class="mmenu__foot"><?= e($SITE['email']) ?><br><?= e($SITE['phone']) ?></div>
  </nav>
