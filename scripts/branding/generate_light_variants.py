#!/usr/bin/env python3
"""
scripts/branding/generate_light_variants.py

Generates light variants (for dark surfaces) and compact icons from original logos.
- Preserves transparent background.
- Preserves colored symbols (hue/saturation).
- Converts dark neutral pixels (black/gray text and anti-aliased edges) to white,
  preserving original alpha channel for smooth anti-aliasing.
- Extracts compact symbol for narrow surfaces (e.g., Korin Bara 88px vertical rail).
"""

import argparse
import colorsys
import os
import sys
from PIL import Image

LOGO_DIR = os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), 'public', 'images', 'logo')

# Per-logo conversion settings
CONFIG = {
    'Logo-DercoBaraEnergy.png': {
        'sat_threshold': 0.25,
        'val_threshold': 0.50,
        'has_light_variant': True,
        'extract_compact': False,
    },
    'Logo-BeramalSuksesSejahtera.png': {
        'sat_threshold': 0.25,
        'val_threshold': 0.50,
        'has_light_variant': True,
        'extract_compact': False,
    },
    'Logo-KorinBerkatEnergy.png': {
        # Entire Korin logo (symbol & text) is monochromatic black / dark navy.
        # Turning all dark elements into white yields crisp white logo for dark surfaces.
        'sat_threshold': 0.99, # Treat low-value navy edge pixels as dark neutral
        'val_threshold': 0.40,
        'has_light_variant': True,
        'extract_compact': True,
        'compact_name': 'Logo-KorinBaraEnergy-compact.png',
        # Symbol is cleanly separated between Y=50 and Y=286
        'compact_crop_box': (20, 50, 526, 287), # (left, top, right, bottom)
    },
}


def process_light_variant(input_path, output_path, config, force=False):
    if os.path.exists(output_path) and not force:
        print(f"  [SKIP] {os.path.basename(output_path)} already exists (use --force to overwrite)")
        return False

    img = Image.open(input_path).convert('RGBA')
    width, height = img.size
    pixels = img.load()

    out_img = Image.new('RGBA', (width, height), (0, 0, 0, 0))
    out_pixels = out_img.load()

    sat_th = config['sat_threshold']
    val_th = config['val_threshold']

    modified_count = 0
    preserved_count = 0

    for y in range(height):
        for x in range(width):
            r, g, b, a = pixels[x, y]
            if a == 0:
                out_pixels[x, y] = (0, 0, 0, 0)
                continue

            # Normalized RGB
            r_norm, g_norm, b_norm = r / 255.0, g / 255.0, b / 255.0
            h, s, v = colorsys.rgb_to_hsv(r_norm, g_norm, b_norm)

            # Check if this pixel is dark neutral
            is_dark_neutral = (s < sat_th and v < val_th) or (v < 0.20)

            if is_dark_neutral:
                # Turn RGB into pure white, keeping exact alpha for anti-aliasing
                out_pixels[x, y] = (255, 255, 255, a)
                modified_count += 1
            else:
                # Keep original color
                out_pixels[x, y] = (r, g, b, a)
                preserved_count += 1

    out_img.save(output_path, 'PNG', optimize=True)
    print(f"  [OK] Generated {os.path.basename(output_path)} ({width}x{height}) - modified: {modified_count}, preserved: {preserved_count}")
    return True


def extract_compact_symbol(input_path, output_path, config, force=False):
    if not config.get('extract_compact'):
        return False

    if os.path.exists(output_path) and not force:
        print(f"  [SKIP] {os.path.basename(output_path)} already exists (use --force to overwrite)")
        return False

    img = Image.open(input_path).convert('RGBA')
    crop_box = config['compact_crop_box']
    symbol_img = img.crop(crop_box)

    # Trim any 1-2px transparent padding around bounding box
    bbox = symbol_img.getbbox()
    if bbox:
        symbol_img = symbol_img.crop(bbox)

    symbol_img.save(output_path, 'PNG', optimize=True)
    print(f"  [OK] Extracted compact symbol {os.path.basename(output_path)} ({symbol_img.size[0]}x{symbol_img.size[1]})")
    return True


def main():
    parser = argparse.ArgumentParser(description="Generate light variants and compact symbols for logos.")
    parser.add_argument('--force', action='store_true', help="Force regenerate existing variant files.")
    args = parser.parse_args()

    print(f"Processing logos in: {LOGO_DIR}")
    for filename, config in CONFIG.items():
        src_path = os.path.join(LOGO_DIR, filename)
        if not os.path.exists(src_path):
            print(f"  [WARN] Source file not found: {filename}")
            continue

        base_name, _ = os.path.splitext(filename)

        # 1. Generate light variant: {base_name}-light.png
        if config.get('has_light_variant'):
            light_path = os.path.join(LOGO_DIR, f"{base_name}-light.png")
            process_light_variant(src_path, light_path, config, force=args.force)

        # 2. Extract compact symbol if specified
        if config.get('extract_compact'):
            compact_name = config.get('compact_name', f"{base_name}-compact.png")
            compact_path = os.path.join(LOGO_DIR, compact_name)
            extract_compact_symbol(src_path, compact_path, config, force=args.force)


if __name__ == '__main__':
    main()

