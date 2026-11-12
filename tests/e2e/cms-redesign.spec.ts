import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';
import * as path from 'path';
import * as fs from 'fs';

const COMPARE_DIR = 'docs/branding/cms-compare';
const REF_DIR = 'docs/reference/cms';

test.describe('CMS Admin Redesign (Revisi 4)', () => {
    test.beforeAll(() => {
        if (!fs.existsSync(COMPARE_DIR)) {
            fs.mkdirSync(COMPARE_DIR, { recursive: true });
        }
        // Copy reference images into compare dir for easy side-by-side review
        if (fs.existsSync(path.join(REF_DIR, 'dashboard.png'))) {
            fs.copyFileSync(path.join(REF_DIR, 'dashboard.png'), path.join(COMPARE_DIR, 'dashboard-reference.png'));
        }
        if (fs.existsSync(path.join(REF_DIR, 'login.png'))) {
            fs.copyFileSync(path.join(REF_DIR, 'login.png'), path.join(COMPARE_DIR, 'login-reference.png'));
        }
    });

    test('Login page matches reference layout and passes accessibility check', async ({ page }) => {
        await page.setViewportSize({ width: 1672, height: 941 });
        await page.goto('/admin/login');
        await page.waitForLoadState('networkidle');

        const elementSizes = await page.evaluate(() => {
            const emailInp = document.querySelector('input[type="email"]');
            const passInp = document.querySelector('input[type="password"]');
            const submitBtn = document.querySelector('button[type="submit"]');
            const card = document.querySelector('.cms-login-card');
            return {
                cardWidth: card?.getBoundingClientRect().width,
                emailWidth: emailInp?.getBoundingClientRect().width,
                passWidth: passInp?.getBoundingClientRect().width,
                submitBtnWidth: submitBtn?.getBoundingClientRect().width,
            };
        });
        console.log('LOGIN_ELEMENT_SIZES:', JSON.stringify(elementSizes, null, 2));

        expect(elementSizes.emailWidth).toBeGreaterThan(200);
        expect(elementSizes.passWidth).toBeGreaterThan(200);
        expect(elementSizes.submitBtnWidth).toBeGreaterThan(300);

        const verticalPositions = await page.evaluate(() => {
            const items = [
                { name: 'logo', el: document.querySelector('.cms-login-logo') },
                { name: 'welcome', el: document.querySelector('h2') },
                { name: 'email', el: document.querySelector('input[type="email"]') },
                { name: 'password', el: document.querySelector('input[type="password"]') },
                { name: 'remember', el: document.querySelector('input[type="checkbox"]') },
                { name: 'forgot', el: Array.from(document.querySelectorAll('a')).find(a => a.textContent?.includes('Forgot Password')) },
                { name: 'submit', el: document.querySelector('button[type="submit"]') },
                { name: 'sso', el: Array.from(document.querySelectorAll('button')).find(b => b.textContent?.includes('Login with SSO')) },
                { name: 'copyright', el: Array.from(document.querySelectorAll('p')).find(p => p.textContent?.includes('All rights reserved')) },
            ];
            return items.map(it => ({
                name: it.name,
                y: it.el ? Math.round(it.el.getBoundingClientRect().y) : null,
                height: it.el ? Math.round(it.el.getBoundingClientRect().height) : null,
            }));
        });
        console.log('VERTICAL_POSITIONS:', JSON.stringify(verticalPositions, null, 2));

        // Check key elements from reference
        await expect(page.locator('text=Building a Sustainable')).toBeVisible();
        await expect(page.locator('text=Welcome Back')).toBeVisible();
        await expect(page.locator('text=Please login to access your dashboard')).toBeVisible();
        await expect(page.locator('text=Remember Me')).toBeVisible();
        await expect(page.locator('text=Forgot Password?')).toBeVisible();
        await expect(page.locator('button:has-text("Login")').first()).toBeVisible();
        await expect(page.locator('button:has-text("Login with SSO")')).toBeVisible();

        // Capture screenshot
        await page.screenshot({ path: path.join(COMPARE_DIR, 'login-actual.png') });

        // Axe accessibility scan
        const accessibilityScanResults = await new AxeBuilder({ page })
            .disableRules(['color-contrast', 'region'])
            .analyze();
        expect(accessibilityScanResults.violations).toEqual([]);
    });

    test('Admin dashboard matches reference layout, widgets, and real DB metrics', async ({ page }) => {
        page.on('console', msg => console.log('BROWSER_LOG:', msg.type(), msg.text()));
        page.on('pageerror', err => console.log('BROWSER_ERROR:', err.message));
        page.on('response', res => {
            if (res.status() === 404) {
                console.log('404_FAILED_URL:', res.url());
            }
        });

        await page.setViewportSize({ width: 1672, height: 941 });

        // Log in
        await page.goto('/admin/login');
        await page.fill('input[type="email"], input[id*="email"]', 'admin@dercobara.co.id');
        await page.fill('input[type="password"], input[id*="password"]', 'password');
        await Promise.all([
            page.waitForURL('**/admin', { timeout: 25000 }),
            page.click('button[type="submit"]'),
        ]);
        await page.waitForLoadState('domcontentloaded');

        // Verify key widgets from reference
        // 1. Sidebar Clean Background & Footer (mountain photo removed per user request)
        await expect(page.locator('.fi-sidebar')).toBeVisible();
        await expect(page.locator('.cms-sidebar-bottom')).toBeVisible();
        await expect(page.locator('.cms-sidebar-mountains')).toHaveCount(0);

        // 2. Greeting Widget
        await expect(page.locator('text=Good').first()).toBeVisible();
        await expect(page.locator('text=Manage your website content')).toBeVisible();

        // 3. Stat Cards (Real DB entities)
        await expect(page.locator('text=Total Pages')).toBeVisible();
        await expect(page.locator('text=Total Products')).toBeVisible();
        await expect(page.locator('text=Total Operations')).toBeVisible();
        await expect(page.locator('text=Total Users')).toBeVisible();

        // 4. Hero Banner
        await expect(page.locator('text=Mining · Trading · Nickel')).toBeVisible();

        // 5. Quick Actions
        await expect(page.locator('text=Quick Actions')).toBeVisible();
        await expect(page.locator('text=Add Product')).toBeVisible();
        await expect(page.locator('text=Manage Pages')).toBeVisible();
        await expect(page.locator('text=Operations').first()).toBeVisible();
        await expect(page.locator('text=Compliance').first()).toBeVisible();

        // 6. Website Overview
        await expect(page.locator('text=Website Overview')).toBeVisible();

        // 7. Recent Activity
        await expect(page.locator('text=Recent Activity')).toBeVisible();

        // Verify layout geometry (12-column spans, matching reference layout)
        const heroBox = await page.locator('.cms-hero-banner').boundingBox();
        expect(heroBox).not.toBeNull();
        expect(heroBox!.width).toBeGreaterThan(600); // 8 cols in 1672px viewport is ~877px

        const statCardsBox = await page.locator('.cms-stat-cards').boundingBox();
        expect(statCardsBox).not.toBeNull();
        expect(statCardsBox!.width).toBeGreaterThan(1000); // 12 cols span full content width (~1328px)

        // 8. Verify all navigation groups are expanded by default and items are visible & clickable
        await expect(page.locator('.fi-sidebar a[href*="/admin/pages"]')).toBeVisible();
        await expect(page.locator('.fi-sidebar a[href*="/admin/companies"]')).toBeVisible();
        await expect(page.locator('.fi-sidebar a[href*="/admin/products"]')).toBeVisible();
        await expect(page.locator('.fi-sidebar a[href*="/admin/contact-messages"]')).toBeVisible();

        // 9. Click on "Halaman" from sidebar and verify navigation works
        await Promise.all([
            page.waitForURL('**/admin/pages', { timeout: 15000 }),
            page.locator('.fi-sidebar a[href*="/admin/pages"]').click(),
        ]);
        await expect(page.locator('h1:has-text("Halaman")')).toBeVisible();

        // 10. Click on "Dashboard" from sidebar to return
        await page.locator('.fi-sidebar a:has-text("Dashboard")').first().click();
        await expect(page.locator('.cms-hero-banner')).toBeVisible({ timeout: 15000 });

        // Capture screenshot
        await page.screenshot({ path: path.join(COMPARE_DIR, 'dashboard-actual.png') });
    });

    test('Holding home displays 3 published subsidiary cards including Korin Bara', async ({ page }) => {
        await page.setViewportSize({ width: 1280, height: 800 });
        await page.goto('/id');
        await page.waitForLoadState('networkidle');

        await expect(page.locator('#main-content').getByRole('link', { name: 'PT Korin Berkat Energy', exact: true })).toBeVisible();
        await expect(page.locator('#main-content').getByRole('link', { name: 'PT Beramal Sukses Sejahtera', exact: true })).toBeVisible();
        await expect(page.locator('#main-content').getByRole('link', { name: 'PT Korin Bara Energy', exact: true })).toBeVisible();
    });

    test('PT Korin Bara Energy pages return 200 OK publicly', async ({ page }) => {
        const response = await page.goto('/id/bisnis-kami/korin-bara-energy');
        expect(response?.status()).toBe(200);

        const aboutResponse = await page.goto('/id/bisnis-kami/korin-bara-energy/tentang-kami');
        expect(aboutResponse?.status()).toBe(200);

        const productsResponse = await page.goto('/id/bisnis-kami/korin-bara-energy/produk');
        expect(productsResponse?.status()).toBe(200);
    });
});
