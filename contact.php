<?php
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/enquiry.php';

/* ---------- Form submission (AJAX → JSON, plain POST → page with message) ---------- */
$values = array_fill_keys(ENQUIRY_FIELDS, '');
$errors = [];
$status = null;   // ['ok'|'bad', message]

$type = $_GET['type'] ?? '';
if (in_array($type, enquiry_flat_types(), true)) $values['project_type'] = $type;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    [$values, $errors] = enquiry_validate($_POST);

    if (($_POST['website'] ?? '') !== '') {
        // honeypot filled → bot. Pretend success, store nothing.
        $status = ['ok', 'Thank you — your enquiry has been received.'];
        $values = array_fill_keys(ENQUIRY_FIELDS, '');
    } elseif ($errors) {
        $status = ['bad', 'Please correct the highlighted fields.'];
    } else {
        $logged = enquiry_log($values, $CONFIG['enquiry_log']);
        $mailed = enquiry_mail($values, $CONFIG['mail']);
        if ($logged || $mailed) {
            $status = ['ok', 'Thank you — your enquiry has been received. We will reply within two working days.'];
            $values = array_fill_keys(ENQUIRY_FIELDS, '');
        } else {
            $status = ['bad', 'Sorry, the enquiry could not be sent. Please try again or email ' . $SITE['email'] . '.'];
        }
    }

    if ($wantsJson) {
        http_response_code($status[0] === 'ok' ? 200 : ($errors ? 422 : 500));
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $status[0] === 'ok', 'message' => $status[1], 'errors' => $errors], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$page        = 'contact';
$title       = 'Contact — ' . $SITE['studioName'];
$description = 'Book a photoshoot — weddings, portraits, events and commercial photography.';
$opt         = enquiry_options();

/* small helpers for the form markup */
$fieldCls = fn($f) => isset($errors[$f]) ? ' has-error' : '';
$inputCls = fn($f) => isset($errors[$f]) ? ' class="is-invalid"' : '';
$val      = fn($f) => e($values[$f]);
$sel      = fn($f, $o) => $values[$f] === $o ? ' selected' : '';

require __DIR__ . '/includes/header.php';
?>
  <main>
    <section class="page-hero pb-4">
      <div class="container-lux">
        <span class="eyebrow" data-reveal="fade">Contact</span>
        <h1 class="display-xl" data-reveal="text">LET'S CREATE SOMETHING <span class="italic gold">EXTRAORDINARY.</span></h1>
      </div>
    </section>
    <section class="section pt-4">
      <div class="container-lux contact-grid">
        <div>
          <p class="lead-lux" data-reveal="fade">Tell us about your shoot — the date, the place and what you have in mind. We reply to every enquiry within two working days.</p>
          <dl class="contact-info" data-reveal="fade">
            <dt>Email</dt><dd><a href="mailto:<?= e($SITE['email']) ?>"><?= e($SITE['email']) ?></a></dd>
            <dt>Phone</dt><dd><a href="tel:<?= e(preg_replace('/\s+/', '', $SITE['phone'])) ?>"><?= e($SITE['phone']) ?></a></dd>
            <dt>Studio</dt><dd><?= e($SITE['city']) ?></dd>
          </dl>
        </div>
        <form class="form-lux" id="inquiry" method="post" action="contact.php" novalidate>
          <div class="row g-0 column-gap-4">
            <div class="col-md field<?= $fieldCls('name') ?>"><label for="f-name">Full Name <span class="req">*</span></label><input id="f-name" name="name" autocomplete="name" required minlength="2" maxlength="120" value="<?= $val('name') ?>"<?= $inputCls('name') ?>><div class="err">Please enter your full name.</div></div>
            <div class="col-md field<?= $fieldCls('email') ?>"><label for="f-email">Email <span class="req">*</span></label><input id="f-email" name="email" type="email" autocomplete="email" required value="<?= $val('email') ?>"<?= $inputCls('email') ?>><div class="err">Please enter a valid email address.</div></div>
          </div>
          <div class="row g-0 column-gap-4">
            <div class="col-md field<?= $fieldCls('phone') ?>"><label for="f-phone">Phone <span class="req">*</span></label><input id="f-phone" name="phone" type="tel" autocomplete="tel" required pattern="^[+]?[0-9\s\-()]{8,16}$" value="<?= $val('phone') ?>"<?= $inputCls('phone') ?>><div class="err">Please enter a valid phone number.</div></div>
            <div class="col-md field<?= $fieldCls('company') ?>"><label for="f-company">Company</label><input id="f-company" name="company" autocomplete="organization" maxlength="160" value="<?= $val('company') ?>"></div>
          </div>
          <div class="row g-0 column-gap-4">
            <div class="col-md field<?= $fieldCls('project_type') ?>"><label for="f-type">Service <span class="req">*</span></label>
              <select id="f-type" name="project_type" required<?= $inputCls('project_type') ?>>
                <option value="">Select…</option>
<?php foreach ($opt['project_type'] as $group => $items): ?>
<?php if ($group !== ''): ?>
                <optgroup label="<?= e($group) ?>">
<?php endif; ?>
<?php foreach ($items as $o): ?>
                  <option<?= $sel('project_type', $o) ?>><?= e($o) ?></option>
<?php endforeach; ?>
<?php if ($group !== ''): ?>
                </optgroup>
<?php endif; ?>
<?php endforeach; ?>
              </select><div class="err">Please choose a service.</div></div>
            <div class="col-md field<?= $fieldCls('budget') ?>"><label for="f-budget">Estimated Budget <span class="req">*</span></label>
              <select id="f-budget" name="budget" required<?= $inputCls('budget') ?>>
                <option value="">Select…</option>
<?php foreach ($opt['budget'] as $o): ?>
                <option<?= $sel('budget', $o) ?>><?= e($o) ?></option>
<?php endforeach; ?>
              </select><div class="err">Please choose a budget range.</div></div>
          </div>
          <div class="field<?= $fieldCls('timeline') ?>"><label for="f-timeline">When is the shoot? <span class="req">*</span></label>
            <select id="f-timeline" name="timeline" required<?= $inputCls('timeline') ?>>
              <option value="">Select…</option>
<?php foreach ($opt['timeline'] as $o): ?>
              <option<?= $sel('timeline', $o) ?>><?= e($o) ?></option>
<?php endforeach; ?>
            </select><div class="err">Please choose a timeline.</div></div>
          <div class="field<?= $fieldCls('details') ?>"><label for="f-details">Project Details <span class="req">*</span></label><textarea id="f-details" name="details" required minlength="20" maxlength="5000" placeholder="Date, location, number of people / products, references…"<?= $inputCls('details') ?>><?= $val('details') ?></textarea><div class="err">Please share a few details (at least 20 characters).</div></div>
          <input type="text" name="website" class="sr-only" tabindex="-1" autocomplete="off" aria-hidden="true">
          <button type="submit" class="btn-lux btn-lux--solid"><span>Send enquiry</span><span class="arr"></span></button>
          <div class="form-status<?= $status ? ' show ' . $status[0] : '' ?>" role="status" aria-live="polite"><?= $status ? e($status[1]) : '' ?></div>
        </form>
      </div>
    </section>
  </main>

<?php
$pageJs = ['assets/js/contact.js'];
require __DIR__ . '/includes/footer.php';
