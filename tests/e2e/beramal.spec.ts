import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

test.describe('PT Beramal Sukses Sejahtera — Calm Flow Theme (Fase 4)', () => {
    test('navigates from Holding to Beramal across all 6 pages + service/product detail in ID and EN', async ({
        page,
    }) => {
        await page.goto('/id');

        // Navigate from Holding to PT Beramal Sukses Sejahtera
        const beramalLink = page
            .locator('a[href="/id/bisnis-kami/beramal-sukses-sejahtera"]')
            .first();
        await expect(beramalLink).toBeVisible();
        await beramalLink.click();

        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera$/);
        await expect(page.locator('[data-theme="beramal"]')).toBeVisible();
        await expect(page.locator('h1')).toContainText('PT Beramal Sukses Sejahtera');

        // Verify floating capsule header navigation and CMS-configured "Layanan & Produk" label
        const mainNav = page.getByRole('navigation', { name: /Navigasi utama/i }).first();
        await expect(mainNav).toContainText('Layanan & Produk');

        // Visit Tentang Kami
        await mainNav.getByRole('link', { name: /Tentang Kami/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/tentang-kami$/);
        await expect(page.locator('h1')).toContainText('Tentang PT Beramal Sukses Sejahtera');

        // Visit Layanan & Produk (Products) and verify heading matches CMS title
        await mainNav.getByRole('link', { name: /Layanan & Produk/i }).click();
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/beramal-sukses-sejahtera\/produk$/);
        await expect(page.locator('h1')).toContainText('Layanan & Produk');

        // Open neutral service/product detail page
        await page
            .locator(
                'a[href="/id/bisnis-kami/beramal-sukses-sejahtera/produk/layanan-manajemen-fasilitas"]',
            )
            .first()
            .click();
        await expect(page).toHaveURL(
            /\/id\/bisnis-kami\/beramal-sukses-sejahtera\/produk\/layanan-manajemen-fasilitas$/,
        );
        await expect(page.locator('h1')).toContainText('Pengelolaan Fasilitas & Simpul Logistik');

        // Switch language to EN directly on Product Detail page
        await page
            .getByRole('navigation', { name: /Bahasa|Language/i })
            .first()
            .getByRole('link', { name: 'EN' })
            .click();
        await expect(page).toHaveURL(
            /\/en\/our-business\/beramal-sukses-sejahtera\/products\/layanan-manajemen-fasilitas$/,
        );
        await expect(page.locator('h1')).toContainText('Facility & Logistics Node Management');

        // Verify EN navigation shows "Services & Products" and visit remaining pages in EN
        const enNav = page.getByRole('navigation', { name: /Primary navigation/i }).first();
        await expect(enNav).toContainText('Services & Products');

        await enNav.getByRole('link', { name: /Services & Products/i }).click();
        await expect(page).toHaveURL(/\/en\/our-business\/beramal-sukses-sejahtera\/products$/);
        await expect(page.locator('h1')).toContainText('Services & Products');

        await enNav.getByRole('link', { name: /About Us/i }).click();
        await expect(page).toHaveURL(/\/en\/our-business\/beramal-sukses-sejahtera\/about-us$/);
        await expect(page.locator('h1')).toContainText('About PT Beramal Sukses Sejahtera');

        await enNav.getByRole('link', { name: /Operations/i }).click();
        await expect(page).toHaveURL(/\/en\/our-business\/beramal-sukses-sejahtera\/operations$/);
        await expect(page.locator('h1')).toContainText(
            'PT Beramal Sukses Sejahtera Workflow & Facilities',
        );

        await enNav.getByRole('link', { name: /Quality & Compliance/i }).click();
        await expect(page).toHaveURL(
            /\/en\/our-business\/beramal-sukses-sejahtera\/quality-and-compliance$/,
        );
        await expect(page.locator('h1')).toContainText(
            'Quality, Permits, and Regulatory Compliance',
        );

        await enNav.getByRole('link', { name: /Contact/i }).click();
        await expect(page).toHaveURL(/\/en\/our-business\/beramal-sukses-sejahtera\/contact$/);
        await expect(page.locator('h1')).toContainText('Contact PT Beramal Sukses Sejahtera');

        // Back link to Holding in top pill bar
        await page.locator('header a[href="/en"]').first().click();
        await expect(page).toHaveURL(/\/en$/);
        await expect(page.locator('[data-theme="holding"]')).toBeVisible();
    });

    test('supports full WAI-ARIA keyboard navigation on the Quality & Compliance Accordion', async ({
        page,
    }) => {
        await page.goto('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan');

        const permitTrigger = page.locator('#beramal-accordion-trigger-permit');
        const certificateTrigger = page.locator('#beramal-accordion-trigger-certificate');
        const reportTrigger = page.locator('#beramal-accordion-trigger-report');
        const permitPanel = page.locator('#beramal-accordion-panel-permit');
        const certificatePanel = page.locator('#beramal-accordion-panel-certificate');

        await expect(permitTrigger).toHaveAttribute('aria-expanded', 'true');
        await expect(permitPanel).toBeVisible();
        await expect(certificateTrigger).toHaveAttribute('aria-expanded', 'false');
        await expect(certificatePanel).toBeHidden();

        // Focus first trigger and toggle closed with Enter
        await permitTrigger.focus();
        await expect(permitTrigger).toBeFocused();
        await page.keyboard.press('Enter');
        await expect(permitTrigger).toHaveAttribute('aria-expanded', 'false');
        await expect(permitPanel).toBeHidden();

        // Toggle back open with Space
        await page.keyboard.press('Space');
        await expect(permitTrigger).toHaveAttribute('aria-expanded', 'true');
        await expect(permitPanel).toBeVisible();

        // ArrowDown moves focus to second trigger (certificate)
        await page.keyboard.press('ArrowDown');
        await expect(certificateTrigger).toBeFocused();

        // Space expands second accordion panel
        await page.keyboard.press('Space');
        await expect(certificateTrigger).toHaveAttribute('aria-expanded', 'true');
        await expect(certificatePanel).toBeVisible();

        // End moves focus to last trigger (report), Home moves focus back to first trigger (permit)
        await page.keyboard.press('End');
        await expect(reportTrigger).toBeFocused();

        await page.keyboard.press('Home');
        await expect(permitTrigger).toBeFocused();
    });

    test('passes axe WCAG 2.2 AA accessibility checks on all Beramal pages and Product Detail in ID and EN', async ({
        page,
    }) => {
        test.setTimeout(60000);
        await page.emulateMedia({ reducedMotion: 'reduce' });

        const urls = [
            '/id/bisnis-kami/beramal-sukses-sejahtera',
            '/id/bisnis-kami/beramal-sukses-sejahtera/tentang-kami',
            '/id/bisnis-kami/beramal-sukses-sejahtera/produk',
            '/id/bisnis-kami/beramal-sukses-sejahtera/produk/layanan-manajemen-fasilitas',
            '/id/bisnis-kami/beramal-sukses-sejahtera/operasi',
            '/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan',
            '/id/bisnis-kami/beramal-sukses-sejahtera/kontak',
            '/en/our-business/beramal-sukses-sejahtera',
            '/en/our-business/beramal-sukses-sejahtera/products',
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

