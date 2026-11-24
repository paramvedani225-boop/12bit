# Cinematic Photography Studio — Website

PHP multi-page site (PHP 8+, no framework): PHP renders every page, and the JS only adds animation and interactivity. Uses HTML5, CSS3, Bootstrap 5 (grid/utilities), GSAP 3 + ScrollTrigger, Lenis, Swiper and Canvas. All libraries are bundled in `assets/vendor/` (works offline, except Google Fonts).

## Run locally
- XAMPP: open `http://localhost/12-bit-stories-website/12-bit-stories-website/`
- or: `php -S localhost:8000` in this folder (the built-in server ignores `.htaccess`)

## Pages
| File | Page |
|---|---|
| `index.php` | Homepage — loader, 240-frame scroll hero, intro + stats, horizontal selected projects, floating gallery, services teaser |
| `photography.php` | 8 categories (`?cat=Wedding`), asymmetric grid, hover zoom, Swiper full-screen lightbox |
| `project.php?id=<slug>` | Case study from `$DATA['projects']` (unknown id → 404 status) |
| `about.php` | Beyond the Frame, philosophy, methodology, stats, team |
| `services.php` | Photography services (People & Weddings, Brands & Commercial, Add-ons), each with Enquire CTA (`contact.php?type=...`) |
| `contact.php` | Inquiry form — server-side validation, email + CSV log |

Old `*.html` URLs redirect (301) to the `.php` pages via `.htaccess`; the removed `design.php` / `design.html` redirect to `photography.php`.

## Where to edit
- **`includes/config.php`** — studio name, tagline, contact details, watermark, frame paths (`public` → also exposed to JS as `window.SITE`), plus server-only mail settings.
- **`includes/data.php`** — photos, services, case studies, team. Replace the placeholder images here. The `services` list drives the services page, the homepage teaser and the contact form's "Service" dropdown.
- **`includes/header.php` / `includes/footer.php`** — shared head, nav, mobile menu, footer and scripts.
- **`assets/css/style.css`** — design tokens at the top (`:root`).

## Contact form
`contact.php` validates on the server (same rules as the browser), then:
1. appends the enquiry to `storage/enquiries.csv` (blocked from the web by `storage/.htaccess`), and
2. emails it with PHP `mail()` to `mail.to` in `includes/config.php`.

It reports success if at least one of those worked. On local XAMPP, `mail()` normally isn't configured, so check the CSV. On the live server, set `mail.from` to an address on your domain. With JS the form submits via AJAX (JSON reply). Without JS it posts normally and the page re-renders with errors or a thank-you message. It includes a `website` honeypot field.

## Watermark system
- Global: `watermark.enabled` in `includes/config.php` (true/false) and `mode` (`'text'` or `'logo'`, logo = `assets/brand/logo.svg` — swap in your SVG/PNG).
- Styling: CSS variables `--wm-color`, `--wm-opacity`, `--wm-size`, `--wm-top/right/bottom/left`, `--wm-logo-w` in `style.css`.
- Applied as a CSS pseudo-element (`.wm`, `.wm--sm`) — `pointer-events: none`, source files never modified.
- Per image: set `'owned' => false` in `includes/data.php` and no watermark is added (for content you can't brand).
- Hero: drawn on a separate overlay canvas above the frame canvas → fixed position, no flicker, frames untouched.
- Branded copies for download/sharing: `tools/brand-previews.sh "STUDIO NAME" assets/images/photo/*.webp` → `assets/branded/` (originals untouched).
- Default position is bottom-left. (The generator's ✦ mark has been removed from the hero frames; see `tools/extract-frames.sh`.)

## Hero frame sequence
- Source: `assets/videos/reference-video.mp4` (unchanged copy).
- Desktop 240 frames (1920px WebP), mobile 120 frames (1080×1080 centre crop). Progressive loading: every 8th frame → 4th → 2nd → all.
- New video? Run `tools/extract-frames.sh path/to/video.mp4` and update the counts in `includes/config.php`.

## Photos
- Website images live in `assets/images/gallery/{wedding,pre-wedding,studio}/` as optimised WebP (originals stay in `assets/images/wedding`, `pre-wedding`, `studio_photography`).
- To add a photo: convert it to WebP, put it in the right gallery folder, then add a line to `photos` in `includes/data.php` with `shape` (`wide` / `tall` / `square`) and, for portraits, `pos` (e.g. `'center 30%'`) so faces stay in frame when cropped.
- Grid rows are packed automatically by shape (`pack_spans()` in `includes/bootstrap.php`).
- Case studies (`projects`) reference photos by path; their gallery uses each photo's own shape.
