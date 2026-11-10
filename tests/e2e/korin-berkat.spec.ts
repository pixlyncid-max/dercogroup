import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

test.describe('PT Korin Berkat Energy — Editorial Index Theme (Fase 3)', () => {
    test('navigates from Holding to Korin Berkat across all 6 pages + product detail in ID and EN', async ({
        page,
    }) => {
        await page.goto('/id');

        // Navigate from Holding to PT Korin Berkat Energy
        const berkatLink = page.locator('a[href="/id/bisnis-kami/korin-berkat-energy"]').first();
        await expect(berkatLink).toBeVisible();
        await berkatLink.click();
        await page.waitForLoadState('networkidle');

        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy$/);
        await expect(page.locator('[data-theme="korin-berkat"]')).toBeVisible();
        await expect(page.locator('h1')).toContainText('PT Korin Berkat Energy');

        // Verify numbered navigation items 01..06 in header
        const mainNav = page.getByRole('navigation', { name: /Navigasi utama/i }).first();
        await expect(mainNav).toContainText('01');
        await expect(mainNav).toContainText('06');

        // Visit 02 Tentang Kami
        await mainNav.getByRole('link', { name: /Tentang Kami/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy\/tentang-kami$/);
        await expect(page.locator('h1')).toContainText('Tentang PT Korin Berkat Energy');

        // Visit 03 Produk and open a Product Detail page
        await mainNav.getByRole('link', { name: /Produk/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy\/produk$/);
        await expect(page.locator('h1')).toContainText('Katalog Produk PT Korin Berkat Energy');

        await page
            .locator('a[href="/id/bisnis-kami/korin-berkat-energy/produk/thermal-coal-gar-4200"]')
            .first()
            .click();
        await expect(page).toHaveURL(
            /\/id\/bisnis-kami\/korin-berkat-energy\/produk\/thermal-coal-gar-4200$/,
        );
        await expect(page.locator('h1')).toContainText('Batu Bara Termal GAR 4.200');
        await expect(page.locator('table')).toContainText('4.200');

        // Switch language to EN directly on Product Detail page
        await page
            .getByRole('navigation', { name: /Bahasa|Language/i })
            .first()
            .getByRole('link', { name: 'EN' })
            .click();
        await expect(page).toHaveURL(
            /\/en\/our-business\/korin-berkat-energy\/products\/thermal-coal-gar-4200$/,
        );
        await expect(page.locator('h1')).toContainText('Thermal Coal GAR 4,200');

        // Visit 04 Operations, 05 Quality & Compliance, and 06 Contact in EN
        const enNav = page.getByRole('navigation', { name: /Primary navigation/i }).first();
        await enNav.getByRole('link', { name: /Operations/i }).click();
        await expect(page).toHaveURL(/\/en\/our-business\/korin-berkat-energy\/operations$/);
        await expect(page.locator('h1')).toContainText('PT Korin Berkat Energy Operational Network');

        await enNav.getByRole('link', { name: /Quality & Compliance/i }).click();
        await expect(page).toHaveURL(
            /\/en\/our-business\/korin-berkat-energy\/quality-and-compliance$/,
        );
        await expect(page.locator('h1')).toContainText(
            'Quality, Permits, and Regulatory Compliance',
        );

        await enNav.getByRole('link', { name: /Contact/i }).click();
        await expect(page).toHaveURL(/\/en\/our-business\/korin-berkat-energy\/contact$/);
        await expect(page.locator('h1')).toContainText('Contact PT Korin Berkat Energy');

        // Back link to Holding in top thin navy bar
        await page.locator('header a[href="/en"]').first().click();
        await expect(page).toHaveURL(/\/en$/);
        await expect(page.locator('[data-theme="holding"]')).toBeVisible();
    });

    test('passes axe WCAG 2.2 AA accessibility checks on Korin Berkat pages and Product Detail', async ({
        page,
    }) => {
        await page.emulateMedia({ reducedMotion: 'reduce' });

        const urls = [
            '/id/bisnis-kami/korin-berkat-energy',
            '/id/bisnis-kami/korin-berkat-energy/tentang-kami',
            '/id/bisnis-kami/korin-berkat-energy/produk',
            '/id/bisnis-kami/korin-berkat-energy/produk/thermal-coal-gar-4200',
            '/id/bisnis-kami/korin-berkat-energy/operasi',
            '/id/bisnis-kami/korin-berkat-energy/mutu-dan-kepatuhan',
            '/id/bisnis-kami/korin-berkat-energy/kontak',
        ];

        for (const url of urls) {
            await page.goto(url);
            await expect(page.locator('h1')).toHaveCount(1);

            const results = await new AxeBuilder({ page })
                .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'])
                .analyze();

            expect(results.violations, `Axe violations on ${url}`).toEqual([]);
        }
    });
});
