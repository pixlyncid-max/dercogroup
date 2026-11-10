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

        // Navigate to Contact in English and switch back to Indonesian (/id/hubungi-kami)
        await page.getByRole('link', { name: 'Contact Us' }).first().click();
        await expect(page).toHaveURL(/\/en\/contact-us$/);

        await page
            .getByRole('navigation', { name: 'Language' })
            .first()
            .getByRole('link', { name: 'ID' })
            .click();
        await expect(page).toHaveURL(/\/id\/hubungi-kami$/);
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
        await page.goto('/id/hubungi-kami', { waitUntil: 'networkidle' });
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

    test('Revision 1: Holding header, navigation, and HeroCarousel structure', async ({
        page,
    }, testInfo) => {
        // Desktop Viewport
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto('/id', { waitUntil: 'networkidle' });

        // Header check: Middle nav contains exactly 3 items: Beranda, Tentang Kami, Bisnis Kami
        const nav = page.getByRole('navigation', { name: 'Navigasi utama' });
        const middleLinks = nav.getByRole('link');
        await expect(middleLinks).toHaveCount(3);
        await expect(middleLinks.nth(0)).toHaveText(/beranda/i);
        await expect(middleLinks.nth(1)).toHaveText(/tentang kami/i);
        await expect(middleLinks.nth(2)).toHaveText(/bisnis kami/i);

        // Header check: Exactly one Hubungi Kami link in header, located on the right (not a button)
        const header = page.locator('header');
        const headerContactLink = header.getByRole('link', { name: 'HUBUNGI KAMI' });
        await expect(headerContactLink).toBeVisible();
        await expect(header.getByRole('button', { name: /hubungi kami/i })).toHaveCount(0);

        // Capture Desktop Header Screenshot
        await header.screenshot({
            path: testInfo.outputPath('holding-header-desktop.png'),
        });

        // Mobile Viewport check
        await page.setViewportSize({ width: 375, height: 667 });
        const mobileMenuButton = page.getByRole('button', { name: /buka menu/i });
        await expect(mobileMenuButton).toBeVisible();
        await mobileMenuButton.click();

        // Mobile drawer: Hubungi Kami is the last link
        const mobileDrawer = page.getByRole('dialog', { name: /navigasi utama/i });
        await expect(mobileDrawer).toBeVisible();
        const drawerLinks = mobileDrawer.getByRole('link');
        const lastDrawerLink = drawerLinks.last();
        await expect(lastDrawerLink).toHaveText(/hubungi kami/i);

        // Capture Mobile Header & Drawer Screenshot
        await page.screenshot({
            path: testInfo.outputPath('holding-header-mobile.png'),
        });

        // Close mobile drawer and switch back to desktop
        await page.getByRole('button', { name: /tutup menu/i }).click();
        await page.setViewportSize({ width: 1280, height: 900 });

        // Check Bisnis Kami on Beranda: Real cards rendered with "Lihat Selengkapnya"
        const subsidiaryCards = page.locator('article');
        await expect(subsidiaryCards.first()).toBeVisible();
        await expect(page.getByRole('link', { name: /lihat selengkapnya/i }).first()).toBeVisible();

        // Check Tentang Kami has HeroCarousel with tablist pagination
        await page.goto('/id/tentang-kami', { waitUntil: 'networkidle' });
        const carousel = page.getByRole('region', { name: /hero image carousel/i });
        await expect(carousel).toBeVisible();
        const dots = carousel.getByRole('tab');
        await expect(dots).toHaveCount(3);
        await expect(dots.nth(0)).toHaveAttribute('aria-selected', 'true');

        // Check Bisnis Kami has HeroCarousel
        await page.goto('/id/bisnis-kami', { waitUntil: 'networkidle' });
        const businessCarousel = page.getByRole('region', { name: /hero image carousel/i });
        await expect(businessCarousel).toBeVisible();
        await expect(businessCarousel.getByRole('tab')).toHaveCount(3);
    });
});

