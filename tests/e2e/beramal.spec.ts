import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

test.describe('PT Beramal Sukses Sejahtera — Revision 2 Calm Flow Theme', () => {
    test('navigates from Holding to Beramal across 5 pages, verifies single text contact link, hidden beranda headers, and 404 compliance', async ({
        page,
    }) => {
        await page.goto('/id');

        // Navigate from Holding to PT Beramal Sukses Sejahtera
        const beramalLink = page
            .locator('a[href="/id/bisnis-kami/beramal-sukses-sejahtera"]')
            .first();
        await expect(beramalLink).toBeVisible();
        await beramalLink.click();
        await page.waitForLoadState('networkidle');

        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera$/);
        await expect(page.locator('[data-theme="beramal"]')).toBeVisible();
        await expect(page.locator('h1')).toContainText('PT Beramal Sukses Sejahtera');

        // 1. Verify center pill navigation contains 4 items and excludes Mutu & Kepatuhan
        const centerNav = page.getByRole('navigation', { name: /Navigasi utama/i }).first();
        await expect(centerNav).toContainText('Beranda');
        await expect(centerNav).toContainText('Tentang Kami');
        await expect(centerNav).toContainText('Layanan & Produk');
        await expect(centerNav).toContainText('Operasi');
        await expect(centerNav).not.toContainText('Mutu & Kepatuhan');
        await expect(centerNav).not.toContainText('Kepatuhan');

        // 2. Verify right slot has single text link "Hubungi Kami" (not a button, not pill)
        const headerContactLink = page
            .locator('header')
            .getByRole('link', { name: /Hubungi Kami/i })
            .first();
        await expect(headerContactLink).toBeVisible();
        await expect(headerContactLink).not.toHaveClass(/bg-accent/);

        // 3. Beranda: Verify section titles after hero are visually hidden (sr-only)
        const srOnlyHeadings = page.locator('section h2.sr-only');
        expect(await srOnlyHeadings.count()).toBeGreaterThanOrEqual(2);

        // Verify no download button or arrow icon on Beranda products block
        const homeProductSection = page.locator('section').filter({ hasText: /Layanan Utama/i }).first();
        await expect(homeProductSection.locator('a[download]')).toHaveCount(0);
        await expect(homeProductSection.locator('svg.lucide-arrow-right')).toHaveCount(0);

        // Verify CertificationList and OperationList are removed from Beranda
        await expect(page.locator('text=Sertifikasi, Izin Usaha & Standar K3LH')).toHaveCount(0);
        await expect(page.locator('text=Alur Kerja & Titik Fasilitas Operasional')).toHaveCount(0);

        // 4. Visit Tentang Kami
        await centerNav.getByRole('link', { name: /Tentang Kami/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/tentang-kami$/);
        await expect(page.locator('h1')).toContainText('Tentang PT Beramal Sukses Sejahtera');

        // Verify Timeline is removed
        await expect(page.locator('text=Tahapan Operasional')).toHaveCount(0);
        await expect(page.locator('text=Pembentukan Tim & Izin Operasi')).toHaveCount(0);

        // 5. Visit Layanan & Produk
        await centerNav.getByRole('link', { name: /Layanan & Produk/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/produk$/);
        await expect(page.locator('h1')).toContainText('Layanan & Produk');

        // 6. Visit Operasi
        await centerNav.getByRole('link', { name: /Operasi/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/operasi$/);
        await expect(page.locator('h1')).toContainText(
            'Alur Kerja & Fasilitas PT Beramal Sukses Sejahtera',
        );

        // 7. Click "Hubungi Kami" right-slot text link
        await headerContactLink.click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/hubungi-kami$/);
        await expect(page.locator('h1')).toContainText('Hubungi PT Beramal Sukses Sejahtera');
        // When on contact page, the text link has aria-current="page"
        await expect(
            page.locator('header').getByRole('link', { name: /Hubungi Kami/i }).first(),
        ).toHaveAttribute('aria-current', 'page');

        // 8. Test 301 redirect in browser from legacy contact slug
        await page.goto('/id/bisnis-kami/beramal-sukses-sejahtera/kontak');
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/hubungi-kami$/);

        // 9. Compliance URL returns 404
        const idComplianceResp = await page.goto('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan');
        expect(idComplianceResp?.status()).toBe(404);

        const enComplianceResp = await page.goto('/en/our-business/beramal-sukses-sejahtera/quality-and-compliance');
        expect(enComplianceResp?.status()).toBe(404);

        // 10. Switch language to EN
        await page.goto('/en/our-business/beramal-sukses-sejahtera');
        const enCenterNav = page.getByRole('navigation', { name: /Primary navigation/i }).first();
        await expect(enCenterNav).toContainText('Services & Products');
        await expect(enCenterNav).not.toContainText('Compliance');

        const enContactLink = page.locator('header').getByRole('link', { name: /Contact Us/i }).first();
        await expect(enContactLink).toBeVisible();

        // Test 301 redirect for EN contact
        await page.goto('/en/our-business/beramal-sukses-sejahtera/contact');
        await expect(page).toHaveURL(/\/en\/our-business\/beramal-sukses-sejahtera\/contact-us$/);
    });

    test('passes axe WCAG 2.2 AA accessibility checks on all active Beramal pages', async ({
        page,
    }) => {
        await page.emulateMedia({ reducedMotion: 'reduce' });

        const urls = [
            '/id/bisnis-kami/beramal-sukses-sejahtera',
            '/id/bisnis-kami/beramal-sukses-sejahtera/tentang-kami',
            '/id/bisnis-kami/beramal-sukses-sejahtera/produk',
            '/id/bisnis-kami/beramal-sukses-sejahtera/operasi',
            '/id/bisnis-kami/beramal-sukses-sejahtera/hubungi-kami',
        ];

        for (const url of urls) {
            await page.goto(url);
            await expect(page.locator('h1')).toHaveCount(1);

            const results = await new AxeBuilder({ page })
                .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'])
                .disableRules(['color-contrast'])
                .analyze();

            expect(results.violations).toEqual([]);
        }
    });
});
