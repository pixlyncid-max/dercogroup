import { execFileSync } from 'node:child_process';
import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

function setKorinBaraStatus(status: 'draft' | 'published'): void {
    execFileSync(
        'php',
        [
            'artisan',
            'tinker',
            `--execute=\\App\\Domain\\Company\\Models\\Company::where('slug', 'korin-bara-energy')->update(['status' => '${status}']);`,
        ],
        { stdio: 'pipe' },
    );
}

test.describe('PT Korin Bara Energy — Field Rail Theme & Draft Visibility (Fase 5)', () => {
    test('hides draft PT Korin Bara Energy from public visitors (404 on direct URL, absent from Holding & sitemap)', async ({
        page,
    }) => {
        setKorinBaraStatus('draft');

        await page.goto('/id');
        await expect(page.locator('[data-theme="holding"]')).toBeVisible();

        // Only 2 published subsidiaries appear on Holding Home and Footer
        await expect(page.locator('a[href*="/korin-berkat-energy"]').first()).toBeVisible();
        await expect(page.locator('a[href*="/beramal-sukses-sejahtera"]').first()).toBeVisible();
        await expect(page.locator('a[href*="/korin-bara-energy"]')).toHaveCount(0);

        // Direct navigation to draft company returns 404 for public visitors
        const response = await page.goto('/id/bisnis-kami/korin-bara-energy');
        expect(response?.status()).toBe(404);

        // Guest attempting ?preview=1 also receives 404
        const previewGuestResponse = await page.goto('/id/bisnis-kami/korin-bara-energy?preview=1');
        expect(previewGuestResponse?.status()).toBe(404);

        // Sitemap XML does not include draft company
        const sitemapResponse = await page.goto('/sitemap.xml');
        expect(sitemapResponse?.status()).toBe(200);
        const sitemapText = await page.content();
        expect(sitemapText).not.toContain('korin-bara-energy');
    });

    test('allows authenticated Admin to preview all 6 Korin Bara pages + product detail with ?preview=1 and passes Axe WCAG 2.2 AA', async ({
        page,
    }) => {
        test.setTimeout(90000);
        setKorinBaraStatus('draft');
        await page.emulateMedia({ reducedMotion: 'reduce' });

        // Authenticate as Super Admin via Filament login
        await page.goto('/admin/login');
        await page.locator('[id="form.email"]').fill('admin@dercobara.co.id');
        await page.locator('[id="form.password"]').fill('password');
        await page.locator('[id="form.password"]').press('Enter');
        await expect(page).toHaveURL(/\/admin$/);

        const previewPages = [
            {
                url: '/id/bisnis-kami/korin-bara-energy?preview=1',
                h1: 'PT Korin Bara Energy',
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/tentang-kami?preview=1',
                h1: 'Tentang PT Korin Bara Energy',
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/produk?preview=1',
                h1: 'Katalog Produk PT Korin Bara Energy',
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200?preview=1',
                h1: 'Batu Bara Termal GAR 4.200',
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/operasi?preview=1',
                h1: 'Jaringan Operasi PT Korin Bara Energy',
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/mutu-dan-kepatuhan?preview=1',
                h1: 'Mutu, Izin, dan Kepatuhan Regulasi',
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/kontak?preview=1',
                h1: 'Kontak PT Korin Bara Energy',
            },
        ];

        for (const item of previewPages) {
            const response = await page.goto(item.url);
            expect(response?.status()).toBe(200);
            expect(response?.headers()['x-robots-tag']).toBe('noindex, nofollow');

            await expect(page.locator('[data-theme="korin-bara"]')).toBeVisible();
            await expect(page.getByTestId('preview-banner')).toBeVisible();
            await expect(page.locator('meta[name="robots"]')).toHaveAttribute(
                'content',
                'noindex, nofollow',
            );
            await expect(page.locator('h1')).toHaveCount(1);
            await expect(page.locator('h1')).toContainText(item.h1);

            if (item.url.includes('/operasi')) {
                await expect(page.getByTestId('korin-bara-process-strip')).toBeVisible();
            }

            const results = await new AxeBuilder({ page })
                .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'])
                .analyze();

            expect(results.violations, `Axe violations on ${item.url}`).toEqual([]);
        }
    });

    test('dynamically displays PT Korin Bara Energy on Holding (3 cards) and allows public navigation when status is changed to published', async ({
        page,
    }) => {
        try {
            setKorinBaraStatus('published');

            await page.goto('/id');
            await expect(page.locator('[data-theme="holding"]')).toBeVisible();

            // Holding Home now shows all 3 subsidiaries including PT Korin Bara Energy
            const korinBaraLink = page
                .locator('a[href="/id/bisnis-kami/korin-bara-energy"]')
                .first();
            await expect(korinBaraLink).toBeVisible();
            await korinBaraLink.click();

            // Public visitor lands on PT Korin Bara Energy with Field Rail theme and no preview banner
            await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-bara-energy$/);
            await expect(page.locator('[data-theme="korin-bara"]')).toBeVisible();
            await expect(page.getByTestId('preview-banner')).toHaveCount(0);
            await expect(page.locator('h1')).toContainText('PT Korin Bara Energy');
        } finally {
            setKorinBaraStatus('draft');
        }
    });
});

