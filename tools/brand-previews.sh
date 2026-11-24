#!/usr/bin/env bash
# Create SEPARATE watermarked copies of images you own (e.g. for social sharing / downloads).
# Originals are never modified. Output goes to assets/branded/.
# Usage: tools/brand-previews.sh "YOUR STUDIO NAME" assets/images/photo/*.webp
set -e
NAME="$1"; shift
mkdir -p assets/branded
for f in "$@"; do
  out="assets/branded/$(basename "${f%.*}")-branded.jpg"
  ffmpeg -v error -y -i "$f" -vf "drawtext=text='${NAME}':fontcolor=white@0.35:fontsize=h*0.025:x=w*0.02:y=h-th-h*0.03" -q:v 3 "$out"
  echo "→ $out"
done
