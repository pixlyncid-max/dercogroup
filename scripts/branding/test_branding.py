import os
import unittest
from PIL import Image

LOGO_DIR = os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), 'public', 'images', 'logo')

class TestBrandingScript(unittest.TestCase):

    def test_dimensions_and_mode_match(self):
        pairs = [
            ('Logo-DercoBaraEnergy.png', 'Logo-DercoBaraEnergy-light.png'),
            ('Logo-BeramalSuksesSejahtera.png', 'Logo-BeramalSuksesSejahtera-light.png'),
            ('Logo-KorinBerkatEnergy.png', 'Logo-KorinBerkatEnergy-light.png'),
        ]
        for orig, light in pairs:
            orig_p = os.path.join(LOGO_DIR, orig)
            light_p = os.path.join(LOGO_DIR, light)

            self.assertTrue(os.path.exists(orig_p), f"Original {orig} missing")
            self.assertTrue(os.path.exists(light_p), f"Light variant {light} missing")

            img_orig = Image.open(orig_p)
            img_light = Image.open(light_p)

            self.assertEqual(img_orig.size, img_light.size, f"Size mismatch for {orig}")
            self.assertEqual(img_light.mode, 'RGBA', f"Mode is not RGBA for {light}")

    def test_colored_pixels_preserved(self):
        # In Derco, red pixel (244, 6, 19) in symbol should remain (244, 6, 19, 255)
        orig_p = os.path.join(LOGO_DIR, 'Logo-DercoBaraEnergy.png')
        light_p = os.path.join(LOGO_DIR, 'Logo-DercoBaraEnergy-light.png')

        img_orig = Image.open(orig_p).convert('RGBA')
        img_light = Image.open(light_p).convert('RGBA')

        w, h = img_orig.size
        pix_orig = img_orig.load()
        pix_light = img_light.load()

        tested_colored = 0
        for y in range(h):
            for x in range(w):
                r, g, b, a = pix_orig[x, y]
                # If red symbol pixel
                if a == 255 and r > 200 and g < 20 and b < 30:
                    self.assertEqual(pix_light[x, y], (r, g, b, a), f"Red pixel changed at ({x},{y})")
                    tested_colored += 1
                    if tested_colored >= 50:
                        break
            if tested_colored >= 50:
                break
        self.assertGreaterEqual(tested_colored, 50, "Did not find enough colored test pixels")

    def test_dark_pixels_became_white(self):
        # In Beramal, dark text pixels should become pure white with preserved alpha
        orig_p = os.path.join(LOGO_DIR, 'Logo-BeramalSuksesSejahtera.png')
        light_p = os.path.join(LOGO_DIR, 'Logo-BeramalSuksesSejahtera-light.png')

        img_orig = Image.open(orig_p).convert('RGBA')
        img_light = Image.open(light_p).convert('RGBA')

        w, h = img_orig.size
        pix_orig = img_orig.load()
        pix_light = img_light.load()

        tested_dark = 0
        for y in range(h):
            for x in range(w):
                r, g, b, a = pix_orig[x, y]
                if a == 255 and max(r, g, b) < 10:
                    self.assertEqual(pix_light[x, y][:3], (255, 255, 255), f"Black pixel not white at ({x},{y})")
                    self.assertEqual(pix_light[x, y][3], a, f"Alpha mismatch at ({x},{y})")
                    tested_dark += 1
                    if tested_dark >= 50:
                        break
            if tested_dark >= 50:
                break
        self.assertGreaterEqual(tested_dark, 50)

    def test_compact_symbol_extracted(self):
        compact_p = os.path.join(LOGO_DIR, 'Logo-KorinBaraEnergy-compact.png')
        self.assertTrue(os.path.exists(compact_p))
        img = Image.open(compact_p)
        self.assertEqual(img.mode, 'RGBA')
        self.assertGreater(img.size[0], 100)
        self.assertGreater(img.size[1], 50)


if __name__ == '__main__':
    unittest.main()

