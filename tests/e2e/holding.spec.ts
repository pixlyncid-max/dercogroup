import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

test.describe('Phase 2 Holding Theme — Navigation, Language Switch, and Accessibility', () => {
    test('navigates across all 4 holding pages and switches language to translated slugs', async ({
        page,
    }) => {
        await page.goto('/id');

        // Verify Holding Home displays published subsidiaries and hides draft Korin Bara
        await expect(
            page.getByRole('heading', { level: 3, name: 'PT Korin Berkat Energy' }),
        ).toBeVisible();
        await expect(
            page.getByRole('heading', { level: 3, name: 'PT Beramal Sukses Sejahtera' }),
        ).toBeVisible();
        await expect(page.getByText('PT Korin Bara Energy')).toHaveCount(0);

        // Navigate to Tentang Kami
        await page
            .getByRole('navigation', { name: 'Navigasi utama' })
            .getByRole('link', { name: 'Tentang Kami' })
            .click();
        await expect(page).toHaveURL(/\/id\/tentang-kami$/);
        await expect(page.getByRole('heading', { level: 1 })).toContainText(
            'Tentang PT Derco Bara Energy',
        );

        // Switch language from /id/tentang-kami to /en/about-us
        await page
            .getByRole('navigation', { name: 'Bahasa' })
            .first()
            .getByRole('link', { name: 'EN' })
            .click();
        await expect(page).toHaveURL(/\/en\/about-us$/);
        await expect(page.getByRole('heading', { level: 1 })).toContainText(
            'About PT Derco Bara Energy',
        );

        // Navigate to Our Business in English
        await page
            .getByRole('navigation', { name: 'Primary navigation' })
            .getByRole('link', { name: 'Our Business' })
            .click();
        await expect(page).toHaveURL(/\/en\/our-business$/);
        await expect(page.getByRole('heading', { level: 1 })).toContainText('Our Business');

        // Navigate to Contact in English and switch back to Indonesian (/id/kontak)
        await page
            .getByRole('navigation', { name: 'Primary navigation' })
            .getByRole('link', { name: 'Contact' })
            .click();
        await expect(page).toHaveURL(/\/en\/contact$/);

        await page
            .getByRole('navigation', { name: 'Language' })
            .first()
            .getByRole('link', { name: 'ID' })
            .click();
        await expect(page).toHaveURL(/\/id\/kontak$/);
        await expect(page.getByRole('heading', { level: 1 })).toContainText(
            'Hubungi PT Derco Bara Energy',
        );
    });

    test('passes WCAG 2.2 AA automated axe checks on the Holding home page and renders cleanly across viewports', async ({
        page,
    }, testInfo) => {
        await page.emulateMedia({ reducedMotion: 'reduce' });
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto('/id', { waitUntil: 'networkidle' });

        await page.evaluate(async () => {
            const images = Array.from(document.querySelectorAll('img'));
            for (const img of images) {
                img.loading = 'eager';
                img.scrollIntoView({ behavior: 'instant', block: 'center' });
                const start = Date.now();
                while ((!img.complete || img.naturalWidth === 0) && Date.now() - start < 5000) {
                    await new Promise((r) => setTimeout(r, 50));
                }
            }
            window.scrollTo(0, 0);
        });
        await page.waitForTimeout(300);

        const accessibilityScanResults = await new AxeBuilder({ page })
            .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'])
            .analyze();

        expect(accessibilityScanResults.violations).toEqual([]);

        await page.screenshot({
            path: testInfo.outputPath('holding-home-1280.png'),
            fullPage: true,
        });

        for (const width of [360, 768, 1920]) {
            await page.setViewportSize({ width, height: 900 });
            await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
        }
    });

    test('submits contact form with valid inputs and receives instant success message', async ({
        page,
    }) => {
        await page.goto('/id/kontak', { waitUntil: 'networkidle' });
        await expect(page.locator('h1')).toContainText('Hubungi PT Derco Bara Energy');

        const nameInput = page.locator('input[name="name"]');
        const emailInput = page.locator('input[name="email"]');
        const phoneInput = page.locator('input[name="phone"]');
        const orgInput = page.locator('input[name="organization"]');
        const subjectInput = page.locator('input[name="subject"]');
        const messageInput = page.locator('textarea[name="message"]');

        await nameInput.fill('Budi Santoso');
        await emailInput.fill('budi@mitra-energi.co.id');
        await phoneInput.fill('+6281234567890');
        await orgInput.fill('PT Mitra Perkasa Energi');
        await subjectInput.fill('Permintaan Spesifikasi GAR 4200');
        await messageInput.fill(
            'Halo tim Derco, kami berminat menjajaki kontrak pasokan batu bara GAR 4200 untuk kebutuhan kuartal mendatang.',
        );

        await expect(nameInput).toHaveValue('Budi Santoso');
        await expect(messageInput).toHaveValue(
            'Halo tim Derco, kami berminat menjajaki kontrak pasokan batu bara GAR 4200 untuk kebutuhan kuartal mendatang.',
        );

        await page.locator('button[type="submit"]').click();

        const statusMessage = page.getByRole('status');
        await expect(statusMessage).toBeVisible({ timeout: 10000 });
        await expect(statusMessage).toContainText('Pesan Anda Telah Diterima');
    });
});

