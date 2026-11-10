def sRGB_to_linear(val):
    c = val / 255.0
    return c / 12.92 if c <= 0.04045 else ((c + 0.055) / 1.055) ** 2.4

def relative_luminance(r, g, b):
    return 0.2126 * sRGB_to_linear(r) + 0.7152 * sRGB_to_linear(g) + 0.0722 * sRGB_to_linear(b)

def contrast_ratio(rgb1, rgb2):
    l1 = relative_luminance(*rgb1)
    l2 = relative_luminance(*rgb2)
    lighter = max(l1, l2)
    darker = min(l1, l2)
    return (lighter + 0.05) / (darker + 0.05)

def hex_to_rgb(h):
    h = h.lstrip('#')
    return tuple(int(h[i:i+2], 16) for i in (0, 2, 4))

backgrounds = {
    'charcoal (#1A1D20)': hex_to_rgb('1A1D20'),
    'charcoal preview (#2A2E34)': hex_to_rgb('2A2E34'),
    'charcoal deep (#121416)': hex_to_rgb('121416'),
    'charcoal deep preview (#1C1F23)': hex_to_rgb('1C1F23'),
    'navy (#0A192F)': hex_to_rgb('0A192F'),
    'dark water (#0C3547)': hex_to_rgb('0C3547'),
    'white (#FFFFFF)': (255, 255, 255)
}

symbols = {
    'White (text in light variant)': (255, 255, 255),
    'Derco Red': (244, 6, 19),
    'Derco Green': (0, 167, 75),
    'Beramal Gold': (251, 172, 7),
    'Korin White (light variant)': (255, 255, 255),
}

print('=== CONTRAST RATIOS ===')
for sym_name, sym_rgb in symbols.items():
    print(f'\n{sym_name} {sym_rgb}:')
    for bg_name, bg_rgb in backgrounds.items():
        cr = contrast_ratio(sym_rgb, bg_rgb)
        status = 'PASS (>=3:1)' if cr >= 3.0 else 'FAIL (<3:1)'
        print(f'  vs {bg_name:30s}: {cr:6.2f} : 1 {status}')

