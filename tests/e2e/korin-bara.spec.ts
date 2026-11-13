import { execFileSync } from 'node:child_process';
import AxeBuilder from '@axe-core/playwright';
import { expect, test } from '@playwright/test';

function ensureKorinBaraPublished(): void {
    execFileSync(
        'php',
        [
            'artisan',
            'tinker',
            `--execute=\\App\\Domain\\Company\\Models\\Company::where('slug', 'korin-bara-energy')->update(['status' => 'published']);`,
        ],
        { stdio: 'pipe' },
    );
}

function createDraftTestCompany(): void {
    execFileSync(
        'php',
        [
            'artisan',
            'tinker',
            `--execute=$holding = \\App\\Domain\\Company\\Models\\Company::where('slug', 'derco-bara-energy')->first(); $c = \\App\\Domain\\Company\\Models\\Company::updateOrCreate(['slug' => 'draft-exploration-energy'], ['parent_id' => $holding->id, 'name' => 'PT Draft Exploration Energy', 'theme' => \\App\\Domain\\Company\\Enums\\CompanyTheme::KorinBara, 'status' => \\App\\Domain\\Company\\Enums\\CompanyStatus::Draft]); app(\\App\\Domain\\Company\\Actions\\CreateCompanyPages::class)->execute($c);`,
        ],
        { stdio: 'pipe' },
    );
}

function removeDraftTestCompany(): void {
    execFileSync(
        'php',
        [
            'artisan',
            'tinker',
            `--execute=\\App\\Domain\\Company\\Models\\Company::where('slug', 'draft-exploration-energy')->delete(); \\App\\Domain\\Company\\Models\\Company::where('slug', 'korin-bara-energy')->update(['status' => 'published']);`,
        ],
        { stdio: 'pipe' },
    );
}

test.describe('PT Korin Bara Energy — Redesign & Public Verification', () => {
    test.beforeEach(() => {
        ensureKorinBaraPublished();
    });

    test.afterAll(() => {
        removeDraftTestCompany();
    });

    test('renders published PT Korin Bara Energy on Holding (3 cards) and allows public visitor navigation without preview banner', async ({
        page,
    }) => {
        await page.goto('/id');
        await expect(page.locator('[data-theme="holding"]')).toBeVisible();

        // Holding Home shows all 3 subsidiaries including PT Korin Bara Energy
        const korinBaraLink = page
            .locator('a[href="/id/bisnis-kami/korin-bara-energy"]')
            .first();
        await expect(korinBaraLink).toBeVisible();
        await korinBaraLink.click();

        // Public visitor lands on PT Korin Bara Energy with new corporate theme and no preview banner
        await expect(page).toHaveURL(/\/id\/bisnis-kami\/korin-bara-energy$/);
        await expect(page.locator('[data-theme="korin-bara"]')).toBeVisible();
        await expect(page.getByTestId('preview-banner')).toHaveCount(0);
        await expect(page.locator('h1')).toContainText('PT Korin Bara Energy');

        // Sitemap XML includes published korin-bara-energy
        const sitemapResponse = await page.goto('/sitemap.xml');
        expect(sitemapResponse?.status()).toBe(200);
        const sitemapText = await page.content();
        expect(sitemapText).toContain('korin-bara-energy');
    });

    test('hides draft company from public visitors (404, absent from Holding & sitemap) (FR-VIS)', async ({
        page,
    }) => {
        createDraftTestCompany();

        await page.goto('/id');
        await expect(page.locator('[data-theme="holding"]')).toBeVisible();

        // Draft company does not appear on Holding Home
        await expect(page.locator('a[href*="/draft-exploration-energy"]')).toHaveCount(0);

        // Direct navigation to draft company returns 404 for public visitors
        const response = await page.goto('/id/bisnis-kami/draft-exploration-energy');
        expect(response?.status()).toBe(404);

        // Guest attempting ?preview=1 also receives 404
        const previewGuestResponse = await page.goto('/id/bisnis-kami/draft-exploration-energy?preview=1');
        expect(previewGuestResponse?.status()).toBe(404);

        // Sitemap XML does not include draft company
        const sitemapResponse = await page.goto('/sitemap.xml');
        expect(sitemapResponse?.status()).toBe(200);
        const sitemapText = await page.content();
        expect(sitemapText).not.toContain('draft-exploration-energy');
    });

    test('verifies horizontal sticky header, menu order, inside page banner, and Axe WCAG 2.2 AA', async ({
        page,
    }) => {
        test.setTimeout(90000);
        await page.emulateMedia({ reducedMotion: 'reduce' });

        const publicPages = [
            {
                url: '/id/bisnis-kami/korin-bara-energy',
                h1: 'PT Korin Bara Energy',
                isHome: true,
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/tentang-kami',
                h1: 'Tentang Kami',
                isHome: false,
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/operasi',
                h1: 'Operasi',
                isHome: false,
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/produk',
                h1: 'Produk',
                isHome: false,
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200',
                h1: 'Batu Bara Termal',
                isHome: false,
            },
            {
                url: '/id/bisnis-kami/korin-bara-energy/kontak',
                h1: 'Kontak',
                isHome: false,
            },
        ];

        for (const item of publicPages) {
            const response = await page.goto(item.url);
            expect(response?.status()).toBe(200);

            await expect(page.locator('[data-theme="korin-bara"]')).toBeVisible();
            await expect(page.getByTestId('preview-banner')).toHaveCount(0);
            await expect(page.locator('h1')).toHaveCount(1);
            await expect(page.locator('h1')).toContainText(item.h1);

            // Verify horizontal header is visible
            await expect(page.locator('header')).toBeVisible();
            const mainNav = page.locator('header nav').first();
            await expect(mainNav).toBeVisible();

            // Verify menu links exist (Beranda, Tentang Kami, Operasi, Produk - Kontak unified into Hubungi Kami)
            const menuLinks = mainNav.locator('a');
            const menuTexts = await menuLinks.allTextContents();
            expect(menuTexts).toEqual(
                expect.arrayContaining(['Beranda', 'Tentang Kami', 'Operasi', 'Produk'])
            );
            expect(menuTexts).not.toContain('Kontak');

            // Verify Holding link and Hubungi Kami button in header
            await expect(page.locator('header').getByText('PT Derco Bara Energy')).toBeVisible();
            await expect(page.locator('header').getByRole('link', { name: /Hubungi Kami|Contact Us/i })).toBeVisible();

            // Verify inside page banner and breadcrumb on non-home pages
            if (!item.isHome) {
                await expect(page.getByTestId('korin-bara-page-banner')).toBeVisible();
                await expect(page.locator('nav[aria-label*="remah roti"], nav[aria-label*="Breadcrumb"]')).toBeVisible();
            }

            // Verify specific page elements
            if (item.url.endsWith('/produk')) {
                await expect(page.getByTestId('korin-bara-products')).toBeVisible();
            }
            if (item.url.endsWith('/operasi')) {
                await expect(page.getByTestId('korin-bara-operations')).toBeVisible();
            }

            const results = await new AxeBuilder({ page })
                .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'])
                .analyze();

            expect(results.violations, `Axe violations on ${item.url}`).toEqual([]);
        }
    });
});
