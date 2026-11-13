import { test } from '@playwright/test';
import * as path from 'path';
import * as fs from 'fs';

const COMPARE_DIR = 'docs/branding/cms-compare';
const ARTIFACT_DIR = 'C:/Users/Ganesha Arta/.gemini/antigravity/brain/782a98a2-9419-452e-8733-d18d0dacdd19/screenshots';

test.describe('Capture CMS Stage 9 Screenshots', () => {
    test.beforeAll(() => {
        if (!fs.existsSync(COMPARE_DIR)) {
            fs.mkdirSync(COMPARE_DIR, { recursive: true });
        }
        if (!fs.existsSync(ARTIFACT_DIR)) {
            fs.mkdirSync(ARTIFACT_DIR, { recursive: true });
        }
    });

    test('Capture CMS Login and Editor Pages', async ({ page }) => {
        // 1. Capture Login Page
        await page.setViewportSize({ width: 1440, height: 900 });
        await page.goto('/admin/login');
        await page.waitForLoadState('networkidle');
        await page.screenshot({ path: path.join(COMPARE_DIR, 'cms-login-desktop.png') });
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'cms-login-desktop.png') });

        // Perform Login
        await page.fill('input[type="email"]', 'admin@dercobara.co.id');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ waitUntil: 'networkidle' });

        // 2. Capture Korin Berkat Beranda Editor - Desktop
        await page.goto('/admin/korin-berkat-energy/halaman/beranda');
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1000);
        await page.screenshot({ path: path.join(COMPARE_DIR, 'korin-berkat-editor-desktop.png') });
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'korin-berkat-editor-desktop.png') });

        // 3. Capture Korin Berkat Beranda Editor - Mobile
        await page.setViewportSize({ width: 375, height: 812 });
        await page.reload();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1000);
        await page.screenshot({ path: path.join(COMPARE_DIR, 'korin-berkat-editor-mobile.png') });
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'korin-berkat-editor-mobile.png') });

        // 4. Capture Derco Holding Beranda Editor - Desktop
        await page.setViewportSize({ width: 1440, height: 900 });
        await page.goto('/admin/derco-bara-energy/halaman/beranda');
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1000);
        await page.screenshot({ path: path.join(COMPARE_DIR, 'derco-editor-desktop.png') });
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'derco-editor-desktop.png') });

        // 5. Capture Derco Holding Beranda Editor - Mobile
        await page.setViewportSize({ width: 375, height: 812 });
        await page.reload();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1000);
        await page.screenshot({ path: path.join(COMPARE_DIR, 'derco-editor-mobile.png') });
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'derco-editor-mobile.png') });
    });
});

