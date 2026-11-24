#!/usr/bin/env bash
# Re-extract the hero frame sequence from a video WITHOUT modifying the source.
# Usage: tools/extract-frames.sh [assets/videos/reference-video.mp4]
# Optional: FFMPEG=/path/to/ffmpeg tools/extract-frames.sh
# After running, update hero desktop/mobile counts in includes/config.php
set -e
SRC="${1:-assets/videos/reference-video.mp4}"
FFMPEG="${FFMPEG:-ffmpeg}"
OUT=assets/frames/hero
rm -rf "$OUT/desktop" "$OUT/mobile"
mkdir -p "$OUT/desktop" "$OUT/mobile"

# Removes the generator's sparkle mark (bottom-right, ~1136-1184 x 576-624 in the 1280x720 source)
DELOGO="delogo=x=1128:y=568:w=64:h=64"
# Light denoise of compression noise before upscaling, so the sharpen doesn't amplify it
CLEAN="$DELOGO,hqdn3d=1.5:1.5:3:3"

# Desktop: every frame, upscaled to 1920px (Lanczos) + contrast-adaptive sharpen, WebP q85
"$FFMPEG" -v error -y -i "$SRC" -an -vf "$CLEAN,scale=1920:-2:flags=lanczos,cas=0.5" \
  -c:v libwebp -quality 85 -compression_level 6 "$OUT/desktop/frame_%04d.webp"

# Mobile (portrait screens only see the centre): 12 fps, centre square crop, 1080x1080, WebP q82
"$FFMPEG" -v error -y -i "$SRC" -an -vf "$CLEAN,fps=12,crop=720:720:280:0,scale=1080:1080:flags=lanczos,cas=0.5" \
  -c:v libwebp -quality 82 -compression_level 6 "$OUT/mobile/frame_%04d.webp"

# Poster (first frame) — WebP + JPG fallback
"$FFMPEG" -v error -y -i "$SRC" -frames:v 1 -vf "$DELOGO,scale=1920:-2:flags=lanczos,cas=0.5" -c:v libwebp -quality 85 assets/images/hero-poster.webp
"$FFMPEG" -v error -y -i "$SRC" -frames:v 1 -vf "$DELOGO,scale=1920:-2:flags=lanczos,cas=0.5" -q:v 3 assets/images/hero-poster.jpg

echo "desktop: $(ls $OUT/desktop | wc -l) frames, mobile: $(ls $OUT/mobile | wc -l) frames"
