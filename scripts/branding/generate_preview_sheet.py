#!/usr/bin/env python3
"""
scripts/branding/generate_preview_sheet.py
Creates docs/branding/logo-preview.png displaying each logo against target backgrounds
at actual display heights and at large preview scale.
"""

import os
from PIL import Image, ImageDraw, ImageFont

LOGO_DIR = os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), 'public', 'images', 'logo')
DOCS_DIR = os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), 'docs', 'branding')
os.makedirs(DOCS_DIR, exist_ok=True)

BACKGROUNDS = [
    ('Charcoal (Holding Header)', (26, 29, 32)),       # #1A1D20
    ('Charcoal Deep (Holding Footer)', (18, 20, 22)),   # #121416
    ('Navy (Korin Footer)', (10, 25, 47)),             # #0A192F
    ('Dark Water (Beramal Footer)', (12, 53, 71)),     # #0C3547
    ('White (Light Surface)', (255, 255, 255)),        # #FFFFFF
]

LOGOS = [
    ('Derco Original (Light Surface)', 'Logo-DercoBaraEnergy.png', 48),
    ('Derco Light (Dark Surface)', 'Logo-DercoBaraEnergy-light.png', 48),
    ('Korin Berkat Original', 'Logo-KorinBerkatEnergy.png', 40),
    ('Korin Berkat Light', 'Logo-KorinBerkatEnergy-light.png', 40),
    ('Korin Bara Compact', 'Logo-KorinBaraEnergy-compact.png', 36),
    ('Beramal Original', 'Logo-BeramalSuksesSejahtera.png', 36),
    ('Beramal Light', 'Logo-BeramalSuksesSejahtera-light.png', 36),
]


def scale_logo_to_height(img, target_h):
    w, h = img.size
    aspect = w / h
    target_w = int(round(target_h * aspect))
    return img.resize((target_w, target_h), Image.Resampling.LANCZOS)


def main():
    col_width = 320
    row_height = 110
    header_height = 60
    padding = 20

    sheet_w = padding * 2 + len(BACKGROUNDS) * col_width
    sheet_h = padding * 2 + header_height + len(LOGOS) * row_height

    sheet = Image.new('RGB', (sheet_w, sheet_h), (240, 242, 245))
    draw = ImageDraw.Draw(sheet)

    # Column headers
    for c_idx, (bg_name, bg_color) in enumerate(BACKGROUNDS):
        x0 = padding + c_idx * col_width
        y0 = padding
        x1 = x0 + col_width - 10
        y1 = y0 + header_height - 10

        draw.rectangle([x0, y0, x1, y1], fill=(220, 225, 230))
        draw.text((x0 + 10, y0 + 15), bg_name, fill=(20, 20, 20))

    # Rows
    for r_idx, (logo_label, filename, target_h) in enumerate(LOGOS):
        p = os.path.join(LOGO_DIR, filename)
        if not os.path.exists(p):
            continue
        logo_img = Image.open(p).convert('RGBA')

        y_top = padding + header_height + r_idx * row_height

        for c_idx, (bg_name, bg_color) in enumerate(BACKGROUNDS):
            x_left = padding + c_idx * col_width
            x_right = x_left + col_width - 10
            y_bottom = y_top + row_height - 10

            # Draw background swatch
            draw.rectangle([x_left, y_top, x_right, y_bottom], fill=bg_color)
            # Subtle border around white swatches
            if bg_color == (255, 255, 255):
                draw.rectangle([x_left, y_top, x_right, y_bottom], outline=(200, 200, 200), width=1)

            # Paste scaled logo
            scaled = scale_logo_to_height(logo_img, target_h)
            pos_x = x_left + 15
            pos_y = y_top + (row_height - 10 - target_h) // 2
            sheet.paste(scaled, (pos_x, pos_y), scaled)

            # Draw tiny label
            text_color = (180, 180, 180) if sum(bg_color) < 400 else (100, 100, 100)
            draw.text((x_left + 10, y_top + 5), f"{logo_label} ({target_h}px)", fill=text_color)

    out_file = os.path.join(DOCS_DIR, 'logo-preview.png')
    sheet.save(out_file, 'PNG')
    print(f"Preview sheet generated: {out_file} ({sheet_w}x{sheet_h})")


if __name__ == '__main__':
    main()

