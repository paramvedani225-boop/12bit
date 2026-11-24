<?php
/* Contact form handling: validation, CSV log, email. Used by contact.php. */

const ENQUIRY_FIELDS = ['name', 'email', 'phone', 'company', 'project_type', 'budget', 'timeline', 'details'];

/** Allowed <select> values (contact.php builds its options from this). */
function enquiry_options(): array
{
    return [
        // service groups come from $DATA['services'] (includes/data.php) + a catch-all
        'project_type' => array_merge(
            array_map(fn($items) => array_column($items, 'name'), $GLOBALS['DATA']['services']),
            ['' => ['Other / Multiple']]
        ),
        'budget'   => ['Under ₹25,000', '₹25,000–₹50,000', '₹50,000–₹1,00,000', '₹1,00,000–₹3,00,000', '₹3,00,000+'],
        'timeline' => ['As soon as possible', 'Within 1 month', '1–3 months', '3+ months', 'Flexible'],
    ];
}

function enquiry_flat_types(): array
{
    return array_merge(...array_values(enquiry_options()['project_type']));
}

/** @return array{0: array, 1: array} [clean values, errors keyed by field] */
function enquiry_validate(array $in): array
{
    $v = [];
    foreach (ENQUIRY_FIELDS as $f) {
        $v[$f] = trim(str_replace("\0", '', (string) ($in[$f] ?? '')));
    }
    $opt = enquiry_options();
    $len = fn($s) => mb_strlen($s, 'UTF-8');
    $err = [];

    if ($len($v['name']) < 2 || $len($v['name']) > 120)                  $err['name'] = 'Please enter your full name.';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL))                   $err['email'] = 'Please enter a valid email address.';
    if (!preg_match('/^[+]?[0-9\s\-()]{8,16}$/', $v['phone']))              $err['phone'] = 'Please enter a valid phone number.';
    if ($len($v['company']) > 160)                                         $err['company'] = 'Company name is too long.';
    if (!in_array($v['project_type'], enquiry_flat_types(), true))         $err['project_type'] = 'Please choose a service.';
    if (!in_array($v['budget'], $opt['budget'], true))                     $err['budget'] = 'Please choose a budget range.';
    if (!in_array($v['timeline'], $opt['timeline'], true))                 $err['timeline'] = 'Please choose a timeline.';
    if ($len($v['details']) < 20 || $len($v['details']) > 5000)            $err['details'] = 'Please share a few details (at least 20 characters).';

    return [$v, $err];
}

/** Append to the CSV log. */
function enquiry_log(array $v, string $file): bool
{
    $dir = dirname($file);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) return false;
    $new = !is_file($file);
    $fh = @fopen($file, 'ab');
    if (!$fh) return false;
    flock($fh, LOCK_EX);
    if ($new) fputcsv($fh, array_merge(['received_at', 'ip'], ENQUIRY_FIELDS));
    // prefix values that a spreadsheet would treat as formulas
    $safe = array_map(fn($s) => preg_match('/^[=+\-@\t\r]/', $s) ? "'" . $s : $s, array_values($v));
    $ok = fputcsv($fh, array_merge([date('c'), $_SERVER['REMOTE_ADDR'] ?? ''], $safe)) !== false;
    flock($fh, LOCK_UN);
    fclose($fh);
    return $ok;
}

/** Send the enquiry by email with PHP mail(). */
function enquiry_mail(array $v, array $mail): bool
{
    $clean = fn($s) => str_replace(["\r", "\n"], ' ', $s);
    $body  = "New enquiry from the website\n\n";
    foreach (['name' => 'Name', 'email' => 'Email', 'phone' => 'Phone', 'company' => 'Company', 'project_type' => 'Service', 'budget' => 'Budget', 'timeline' => 'Timeline'] as $k => $label) {
        $body .= str_pad($label . ':', 15) . ($v[$k] !== '' ? $v[$k] : '—') . "\n";
    }
    $body .= "\nProject details:\n" . $v['details'] . "\n";

    $headers = [
        'From: ' . $mail['from'],
        'Reply-To: ' . $clean($v['name']) . ' <' . $v['email'] . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
    ];
    $subject = '=?UTF-8?B?' . base64_encode($mail['subject'] . ' — ' . $clean($v['name'])) . '?=';
    return @mail($mail['to'], $subject, $body, implode("\r\n", $headers));
}
