# Reference Video Analysis

**File:** `assets/videos/reference-video.mp4` (original, untouched copy)

| Property | Value |
|---|---|
| Resolution | 1280 × 720 |
| Aspect ratio | 16:9 |
| Frame rate | 24 fps |
| Duration | 10.0 s |
| Total frames | 240 |
| Codec | H.264 + AAC audio |
| Hard cuts | **None** (FFmpeg scene detection found no cut above 0.08) — the whole film is one continuous camera move |
| Existing mark | A small four-point sparkle (✦) in the bottom-right corner of every frame (third-party generator mark). **Removed from the extracted frames** with FFmpeg `delogo` (the source video itself is untouched). |

## Overall creative direction

One unbroken **dolly push-in**: the camera travels along a track toward a floating vintage lens, passes *through* the glass, and emerges into a dark void of floating framed photographs that resolves into a single glowing warm orb. The story is "the lens is the doorway into the work".

- **Palette:** near-black (#020201) to warm charcoal (#25231F), brass/gold highlights (#77634E → #B8A88A at peak), cream photo mats (#E9E2D3). Matches the brief's palette almost exactly.
- **Light:** single volumetric beam from upper-right, floating dust particles, deep vignettes.
- **Texture:** dark veined marble, brushed brass, glass.
- **Motion:** slow, weighted, continuous; no cuts, no shake. Speed eases in during the lens approach and eases out on the orb.
- **Typography in video:** none. The empty centre in scenes 1 and 5 is natural space for headline / wordmark.

## Scene breakdown

### Scene 1 — The Hall (0.0 s – 2.0 s, frames 1–48)
- **Composition:** symmetric one-point perspective. Tall marble pillars on both sides, a light beam cutting diagonally from top-right, a camera dolly track running from the bottom edge toward a floating lens in the centre (lens ~15% of frame width).
- **Camera:** slow forward dolly along the track, perfectly centred.
- **Animation:** lens hovers still; dust particles drift in the beam.
- **Background:** dark marble, warm sepia light, strong vignette.
- **Transition:** continuous; track slides out under the bottom edge.
- **Text space:** upper and lower thirds are empty → headline area.

### Scene 2 — The Approach (2.0 s – 4.5 s, frames 49–108)
- **Composition:** lens grows from ~30% to ~100% of frame height; brass focus ring and aperture blades become readable.
- **Camera:** dolly push-in, accelerating slightly (ease-in).
- **Animation:** aperture blades slowly open.
- **Background:** hall falls into deep shadow; light beam now catches the brass rim.
- **Transition:** lens fills the frame → match-cut-feel into the glass (no actual cut).

### Scene 3 — Through the Glass (4.5 s – 6.3 s, frames 109–151)
- **Composition:** macro of concentric glass elements forming a tunnel; two gold liquid ribbons curl around the centre in an S-shape.
- **Camera:** zoom-through combined with a slow clockwise rotation.
- **Animation:** ribbons twist; small framed photos begin to appear reflected inside the glass.
- **Background:** black rings, brass reflections.
- **Transition:** **iris / zoom-through** — the glass rings expand past the frame edges, revealing what's behind.

### Scene 4 — The Gallery Void (6.3 s – 8.0 s, frames 152–192)
- **Composition:** dozens of floating framed black-and-white photographs (desert dunes, arches, sculptural still-lifes) at different depths; thin brass frames with cream mats; asymmetric scatter.
- **Camera:** continues forward; frames drift outward from centre (depth parallax).
- **Animation:** a faint translucent bubble/orb forms in the centre.
- **Background:** pure near-black void.
- **Transition:** frames dim as the orb grows brighter.

### Scene 5 — The Orb (8.0 s – 10.0 s, frames 193–240)
- **Composition:** single glowing warm-gold rounded orb in the exact centre, haloed by particles; remaining frames fade into darkness.
- **Camera:** slows almost to a stop (ease-out).
- **Animation:** orb pulses/morphs softly, particles shimmer.
- **Background:** black with soft radial glow.
- **Transition:** fade to near-black — ideal logo / wordmark reveal point.

## How the website uses this language

| Video element | Website translation |
|---|---|
| Continuous dolly push-in | Hero = pinned Canvas sequence scrubbed by scroll (all 240 frames on desktop, 120 on mobile) |
| Empty centre in Scene 1 | Headline "WE CREATE VISUAL STORIES." |
| Lens / aperture | Circular "iris" clip-path reveals for images and page transitions |
| Through the glass (Scene 3) | Copy line "Every story begins behind the lens." appears mid-sequence |
| Floating framed photos | Thin brass borders + cream mats on gallery cards, asymmetric grids, depth parallax |
| Orb (Scene 5) | Studio wordmark + CTAs reveal centred on the orb at the end of the sequence |
| Brass/charcoal palette | #090909 / #151515 / #F5F3EF / #969696 / #B8A88A |
| Slow, weighted motion | Long eases (`power3/expo.out`), 1–1.4 s durations, Lenis smooth scroll |

## Frame extraction decision
- 10 s × 24 fps = 240 frames; motion is slow and continuous, so every frame is kept on desktop for a fluid scrub: denoised, Lanczos-upscaled to 1920 px + CAS sharpen, WebP q85 (≈11 MB total).
- Mobile uses 12 fps → 120 frames, centre square crop at 1080×1080, WebP q82 (≈3.7 MB).
- Frames load progressively (every 8th frame first, then 4th, 2nd, all) so scrolling works before the full set has arrived.
- 1280 px is the source's maximum; frames are not upscaled at extraction. On larger screens they are drawn "cover"-fit without distortion.
