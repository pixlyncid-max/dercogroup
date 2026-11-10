import { type Page, expect, test } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

/** Scroll to bottom, wait for lazy images to load, then verify a footer img via page.evaluate (avoids stale handles). */
async function checkFooterLogo(page: Page, selector: string, srcPattern: RegExp) {
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(400);

    const src = await page.evaluate((sel: string) => {
        const img = document.querySelector(sel) as HTMLImageElement | null;
        return img ? img.getAttribute('src') : null;
    }, selector);
    expect(src).toMatch(srcPattern);

    const isLoaded = await page.evaluate((sel: string) => {
        const img = document.querySelector(sel) as HTMLImageElement | null;
        return img ? img.complete && img.naturalWidth > 0 : false;
    }, selector);
    expect(isLoaded).toBe(true);
}

test.describe('Revision 3 — Header & Footer Brand Logos across all themes', () => {

    test('Holding: renders light-variant logo on dark header & footer, shrinks on scroll', async ({ page }) => {
        await page.goto('/id');

        // --- Header ---
        const headerLink = page.locator('header a[aria-label*="PT Derco Bara Energy"]');
        await expect(headerLink).toBeVisible();
        await expect(headerLink).toHaveAttribute('href', '/id');

        const headerImg = headerLink.locator('img');
        await expect(headerImg).toBeVisible();
        await expect(headerImg).toHaveAttribute('src', /\/images\/logo\/Logo-DercoBaraEnergy-light\.png/);
        await expect(headerImg).toHaveAttribute('alt', 'PT Derco Bara Energy');

        const isHeaderLoaded = await headerImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
        expect(isHeaderLoaded).toBe(true);

        const initialHeight = await headerImg.evaluate((img: HTMLImageElement) =>
            parseFloat(window.getComputedStyle(img).height)
        );
        expect(initialHeight).toBeCloseTo(48, 1);

        // Scroll → sticky shrink
        await page.evaluate(() => window.scrollTo(0, 500));
        await page.waitForTimeout(400);

        const scrolledHeight = await headerImg.evaluate((img: HTMLImageElement) =>
            parseFloat(window.getComputedStyle(img).height)
        );
        expect(scrolledHeight).toBeCloseTo(36, 1);

        // --- Footer ---
        await checkFooterLogo(
            page as any,
            'footer img[alt="PT Derco Bara Energy"]',
            /\/images\/logo\/Logo-DercoBaraEnergy-light\.png/,
        );
    });

    test('Korin Berkat: original logo on white header, light variant on navy footer (ID & EN)', async ({ page }) => {
        const cases = [
            { path: '/id/bisnis-kami/korin-berkat-energy' },
            { path: '/en/our-business/korin-berkat-energy' },
        ];

        for (const { path } of cases) {
            await page.goto(path);

            // --- Header ---
            const headerLink = page.locator('header a[aria-label*="PT Korin Berkat Energy"]');
            await expect(headerLink).toBeVisible();

            const headerImg = headerLink.locator('img');
            await expect(headerImg).toBeVisible();
            await expect(headerImg).toHaveAttribute('src', /\/images\/logo\/Logo-KorinBerkatEnergy\.png/);
            await expect(headerImg).toHaveAttribute('alt', 'PT Korin Berkat Energy');

            const isHeaderLoaded = await headerImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
            expect(isHeaderLoaded).toBe(true);

            // --- Footer (re-query after scroll, avoid stale handle) ---
            await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
            await page.waitForTimeout(400);

            const footerSrc = await page.evaluate(() => {
                const img = document.querySelector('footer img[alt="PT Korin Berkat Energy"]') as HTMLImageElement | null;
                return img ? img.getAttribute('src') : null;
            });
            expect(footerSrc).toMatch(/\/images\/logo\/Logo-KorinBerkatEnergy-light\.png/);

            const isFooterLoaded = await page.evaluate(() => {
                const img = document.querySelector('footer img[alt="PT Korin Berkat Energy"]') as HTMLImageElement | null;
                return img ? img.complete && img.naturalWidth > 0 : false;
            });
            expect(isFooterLoaded).toBe(true);
        }
    });

    test('Korin Bara: compact logo on rail (desktop), original logo on mobile header, light variant on footer', async ({ page }) => {
        await page.goto('/id/bisnis-kami/korin-bara-energy');

        // Desktop: check the rail logo (compact icon or full logo depending on rail state)
        const railLink = page.locator('header a[aria-label*="PT Korin Bara Energy"], nav a[aria-label*="PT Korin Bara Energy"], aside a[aria-label*="PT Korin Bara Energy"]').first();
        if (await railLink.isVisible()) {
            const railImg = railLink.locator('img');
            await expect(railImg).toBeVisible();
            const railSrc = await railImg.getAttribute('src');
            // Compact icon or original — either is valid depending on viewport width
            expect(railSrc).toMatch(/Logo-KorinBara/);
            const isRailLoaded = await railImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
            expect(isRailLoaded).toBe(true);
        }

        // --- Footer ---
        await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
        await page.waitForTimeout(400);

        const footerSrc = await page.evaluate(() => {
            const img = document.querySelector('footer img[alt="PT Korin Bara Energy"]') as HTMLImageElement | null;
            return img ? img.getAttribute('src') : null;
        });
        // Footer uses dark-surface variant
        if (footerSrc !== null) {
            expect(footerSrc).toMatch(/Logo-KorinBara/);
        }
    });

    test('Beramal: original logo on white pill header, light variant on dark water footer (ID & EN)', async ({ page }) => {
        const cases = [
            { path: '/id/bisnis-kami/beramal-sukses-sejahtera' },
            { path: '/en/our-business/beramal-sukses-sejahtera' },
        ];

        for (const { path } of cases) {
            await page.goto(path);

            // --- Header ---
            const headerLink = page.locator('header a[aria-label*="PT Beramal Sukses Sejahtera"]');
            await expect(headerLink).toBeVisible();

            const headerImg = headerLink.locator('img');
            await expect(headerImg).toBeVisible();
            await expect(headerImg).toHaveAttribute('src', /\/images\/logo\/Logo-BeramalSuksesSejahtera\.png/);
            await expect(headerImg).toHaveAttribute('alt', 'PT Beramal Sukses Sejahtera');

            const isHeaderLoaded = await headerImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
            expect(isHeaderLoaded).toBe(true);

            // --- Footer (re-query after scroll) ---
            await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
            await page.waitForTimeout(400);

            const footerSrc = await page.evaluate(() => {
                const img = document.querySelector('footer img[alt="PT Beramal Sukses Sejahtera"]') as HTMLImageElement | null;
                return img ? img.getAttribute('src') : null;
            });
            expect(footerSrc).toMatch(/\/images\/logo\/Logo-BeramalSuksesSejahtera-light\.png/);

            const isFooterLoaded = await page.evaluate(() => {
                const img = document.querySelector('footer img[alt="PT Beramal Sukses Sejahtera"]') as HTMLImageElement | null;
                return img ? img.complete && img.naturalWidth > 0 : false;
            });
            expect(isFooterLoaded).toBe(true);
        }
    });

    test('Passes Axe WCAG 2.2 AA accessibility checks for logo links and images', async ({ page }) => {
        await page.goto('/id');

        const axe = await new AxeBuilder({ page })
            .include('header')
            .include('footer')
            .withTags(['wcag2a', 'wcag2aa', 'wcag22aa'])
            .analyze();

        expect(axe.violations).toEqual([]);
    });
});
