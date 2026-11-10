import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

test.describe('PT Korin Berkat Energy — Revision 2 Editorial Theme', () => {
    test('verifies Korin Berkat 5-item navigation, direct spec tables, hidden beranda headers, and 404 compliance in ID and EN', async ({
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

        // 1. Verify numbered navigation items 01..05 in header (06 does NOT exist)
        const mainNav = page.getByRole('navigation', { name: /Navigasi utama/i }).first();
        await expect(mainNav).toContainText('01');
        await expect(mainNav).toContainText('05');
        await expect(mainNav).not.toContainText('06');
        await expect(mainNav).not.toContainText('Mutu & Kepatuhan');

        // 2. Beranda: Verify section titles after hero are visually hidden (sr-only)
        const srOnlyHeadings = page.locator('section h2.sr-only');
        expect(await srOnlyHeadings.count()).toBeGreaterThanOrEqual(2);

        // Verify no download button or arrow links on Home product summary
        const homeProductSection = page.locator('section').filter({ hasText: /GAR 4\.200/i }).first();
        await expect(homeProductSection.locator('a[download]')).toHaveCount(0);
        await expect(homeProductSection.locator('svg.lucide-arrow-right')).toHaveCount(0);

        // Verify CertificationList is removed from Beranda
        await expect(page.locator('text=Sertifikasi, Izin Usaha & Standar K3LH')).toHaveCount(0);

        // 3. Visit 02 Tentang Kami
        await mainNav.getByRole('link', { name: /Tentang Kami/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy\/tentang-kami$/);
        await expect(page.locator('h1')).toContainText('Tentang PT Korin Berkat Energy');

        // Verify Timeline is removed
        await expect(page.locator('text=Tahapan Operasional')).toHaveCount(0);
        await expect(page.locator('text=Pembentukan Tim & Izin Operasi')).toHaveCount(0);

        // Verify ValuesGrid rendered with 4 items titled "Nilai Perusahaan"
        await expect(page.locator('text=Integritas Pasokan [PLACEHOLDER]')).toBeVisible();
        await expect(page.locator('text=Ketepatan Spesifikasi [PLACEHOLDER]')).toBeVisible();
        await expect(page.locator('text=Keandalan Pengapalan [PLACEHOLDER]')).toBeVisible();
        await expect(page.locator('text=Kepatuhan Berkelanjutan [PLACEHOLDER]')).toBeVisible();

        // 4. Visit 03 Produk: displays full specifications table directly (no link to detail, no download, no arrow)
        await mainNav.getByRole('link', { name: /Produk/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy\/produk$/);
        await expect(page.locator('h1')).toContainText('Katalog Produk PT Korin Berkat Energy');

        // Full specs table directly visible
        await expect(page.locator('text=Nilai Kalori (GAR)').first()).toBeVisible();
        await expect(page.locator('text=Total Moisture (ARB)').first()).toBeVisible();
        await expect(page.locator('text=Kadar Abu / Ash (ADB)').first()).toBeVisible();
        await expect(page.locator('a[download]')).toHaveCount(0);
        await expect(page.locator('svg.lucide-arrow-right')).toHaveCount(0);

        // 5. Visit 04 Operasi
        await mainNav.getByRole('link', { name: /Operasi/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy\/operasi$/);
        await expect(page.locator('h1')).toContainText('Jaringan Operasi PT Korin Berkat Energy');

        // 6. Visit 05 Kontak
        await mainNav.getByRole('link', { name: /Kontak/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-berkat-energy\/kontak$/);
        await expect(page.locator('h1')).toContainText('Kontak PT Korin Berkat Energy');

        // 7. Verify Compliance returns 404 in both ID and EN
        const idComplianceResp = await page.goto('/id/bisnis-kami/korin-berkat-energy/mutu-dan-kepatuhan');
        expect(idComplianceResp?.status()).toBe(404);

        const enComplianceResp = await page.goto('/en/our-business/korin-berkat-energy/quality-and-compliance');
        expect(enComplianceResp?.status()).toBe(404);

        // 8. Test English navigation
        await page.goto('/en/our-business/korin-berkat-energy');
        const enNav = page.getByRole('navigation', { name: /Primary navigation/i }).first();
        await expect(enNav).toContainText('01');
        await expect(enNav).toContainText('05');
        await expect(enNav).not.toContainText('06');
        await expect(enNav).not.toContainText('Compliance');

        // Back link to Holding in top thin navy bar
        await page.locator('header a[href="/en"]').first().click();
        await expect(page).toHaveURL(/\/en$/);
        await expect(page.locator('[data-theme="holding"]')).toBeVisible();
    });

    test('passes axe WCAG 2.2 AA accessibility checks on all active Korin Berkat pages', async ({
        page,
    }) => {
        await page.emulateMedia({ reducedMotion: 'reduce' });

        const urls = [
            '/id/bisnis-kami/korin-berkat-energy',
            '/id/bisnis-kami/korin-berkat-energy/tentang-kami',
            '/id/bisnis-kami/korin-berkat-energy/produk',
            '/id/bisnis-kami/korin-berkat-energy/operasi',
            '/id/bisnis-kami/korin-berkat-energy/kontak',
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
