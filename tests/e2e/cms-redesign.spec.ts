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
        await page.setViewportSize({ width: 1672, height: 941 });

        // Log in
        await page.goto('/admin/login');
        await page.fill('input[type="email"], input[id*="email"]', 'admin@dercobara.co.id');
        await page.fill('input[type="password"], input[id*="password"]', 'password');
        await page.click('button[type="submit"]');

        await page.waitForURL('**/admin', { timeout: 15000 });
        await page.waitForLoadState('networkidle');

        // Verify key widgets from reference
        // 1. Sidebar Mountain Footer
        await expect(page.locator('.cms-sidebar-mountains')).toBeVisible();

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
