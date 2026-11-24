<?php
/* Shared footer + scripts.
   Set before including: optional $extraJs (vendor scripts before site JS), $pageJs (page scripts after main.js),
   $exposeData (true → window.DATA is printed for pages whose JS re-renders content). */
$extraJs    = $extraJs ?? [];
$pageJs     = $pageJs ?? [];
$exposeData = $exposeData ?? false;
$links      = nav_links();
?>
  <footer class="footer">
    <div class="container-lux">
      <div class="footer__cta">
        <h2 class="display-l" data-reveal="text">Let's create something <span class="italic gold">extraordinary.</span></h2>
        <a href="contact.php" class="btn-lux btn-lux--solid"><span>Start a project</span><span class="arr"></span></a>
      </div>
      <div class="footer__cols">
        <div><div class="nav__brand mb-3"><i></i><span><?= e($SITE['studioName']) ?></span></div><p class="muted" style="max-width:34ch"><?= e($SITE['tagline']) ?>. <?= e($SITE['city']) ?>.</p></div>
        <div><h5>Explore</h5><ul>
<?php foreach ($links as [$href, $label]): ?>
          <li><a href="<?= $href ?>"><?= e($label) ?></a></li>
<?php endforeach; ?>
        </ul></div>
        <div><h5>Contact</h5><ul>
          <li><a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a></li>
          <li><a href="tel:<?= e(preg_replace('/\s+/', '', $SITE['phone'])) ?>"><?= e($SITE['phone']) ?></a></li>
          <li class="muted"><?= e($SITE['city']) ?></li>
        </ul></div>
        <div><h5>Follow</h5><ul>
          <li><a href="<?= e($SITE['social']['instagram']) ?>">Instagram</a></li>
          <li><a href="<?= e($SITE['social']['behance']) ?>">Behance</a></li>
          <li><a href="<?= e($SITE['social']['linkedin']) ?>">LinkedIn</a></li>
        </ul></div>
      </div>
      <div class="footer__base"><span>© <?= date('Y') ?> <?= e($SITE['studioName']) ?>. All rights reserved.</span><span>All imagery © <?= e($SITE['studioName']) ?> unless otherwise credited.</span></div>
    </div>
  </footer>

  <script src="assets/vendor/gsap.min.js"></script>
  <script src="assets/vendor/ScrollTrigger.min.js"></script>
  <script src="assets/vendor/lenis.min.js"></script>
<?php foreach ($extraJs as $js): ?>
  <script src="<?= e($js) ?>"></script>
<?php endforeach; ?>
  <script>
    window.SITE = <?= js_json($SITE) ?>;
<?php if ($exposeData): ?>
    window.DATA = <?= js_json($DATA) ?>;
<?php endif; ?>
  </script>
  <script src="<?= e(asset('assets/js/main.js')) ?>"></script>
<?php foreach ($pageJs as $js): ?>
  <script src="<?= e(asset($js)) ?>"></script>
<?php endforeach; ?>
</body>
</html>
