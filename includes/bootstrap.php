<?php
/* Loaded first by every page: config, content data and small template helpers. */
declare(strict_types=1);

$CONFIG = require __DIR__ . '/config.php';
$SITE   = $CONFIG['public'];
$DATA   = require __DIR__ . '/data.php';
date_default_timezone_set($CONFIG['timezone'] ?? 'UTC');

/** HTML-escape. */
function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Watermark classes for an item ('' when the studio doesn't own it). */
function wm_class(array $item, bool $small = false): string
{
    if (($item['owned'] ?? true) === false) return '';
    return $small ? 'wm wm--sm' : 'wm';
}

/** Two-digit index: 1 → "01". */
function num2(int $n): string
{
    return str_pad((string) $n, 2, '0', STR_PAD_LEFT);
}

/** Main navigation: [href, label, page key]. */
function nav_links(): array
{
    return [
        ['index.php', 'Home', 'home'],
        ['photography.php', 'Photography', 'photography'],
        ['about.php', 'Studio', 'about'],
        ['services.php', 'Services', 'services'],
        ['contact.php', 'Contact', 'contact'],
    ];
}

/** JSON for embedding in <script> (safe against </script> injection). */
function js_json($v): string
{
    return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/** Asset URL with a file-modified-time version, so browsers pick up CSS/JS changes immediately. */
function asset(string $path): string
{
    $file = __DIR__ . '/../' . $path;
    return is_file($file) ? $path . '?v=' . filemtime($file) : $path;
}

/** style="object-position:…" for cropped images whose item has a 'pos' (keeps faces in frame). */
function img_pos(array $item): string
{
    return !empty($item['pos']) ? ' style="object-position:' . e($item['pos']) . '"' : '';
}

/** Find a photo in $DATA['photos'] by its src (used for shape/pos of case-study gallery images). */
function photo_by_src(string $src): array
{
    foreach ($GLOBALS['DATA']['photos'] as $p) if ($p['src'] === $src) return $p;
    return ['src' => $src, 'shape' => 'wide'];
}

/** Aspect-ratio class for a photo shape. */
function ratio_class(string $shape): string
{
    return ['wide' => 'media--3x2', 'tall' => 'media--4x5', 'square' => 'media--1x1'][$shape] ?? 'media--3x2';
}

/**
 * Row packer for 12-column photo grids: pairs consecutive photos and gives each pair
 * spans that add up to 12, chosen by shape so heights stay close (no gaps in rows).
 * Must match packSpans() in assets/js/photography.js.
 * @param string[] $shapes  'wide' | 'tall' | 'square'
 * @param int $singleWide   span for a wide photo left alone at the end (others get 6)
 */
function pack_spans(array $shapes, int $singleWide = 8): array
{
    $pairs = [
        'wide-wide' => [[7, 5], [5, 7]], 'wide-tall' => [[8, 4]], 'tall-wide' => [[4, 8]],
        'wide-square' => [[7, 5]], 'square-wide' => [[5, 7]],
    ];
    $out = []; $row = 0; $n = count($shapes);
    for ($i = 0; $i < $n; $i += 2, $row++) {
        if ($i === $n - 1) { $out[] = $shapes[$i] === 'wide' ? $singleWide : 6; break; }
        $opts = $pairs[$shapes[$i] . '-' . $shapes[$i + 1]] ?? [[6, 6]];
        [$a, $b] = $opts[$row % count($opts)];
        $out[] = $a; $out[] = $b;
    }
    return $out;
}
