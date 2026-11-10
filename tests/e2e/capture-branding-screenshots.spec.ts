import { test } from '@playwright/test';
import * as path from 'path';
import * as fs from 'fs';

const ARTIFACT_DIR = 'C:/Users/Ganesha Arta/.gemini/antigravity/brain/9cab5f05-dbe7-473b-9247-530915b521e8/screenshots';
const DOCS_DIR = 'docs/branding/screenshots';

test.describe('Capture Branding Logo Screenshots', () => {
    test.beforeAll(() => {
        if (!fs.existsSync(ARTIFACT_DIR)) {
            fs.mkdirSync(ARTIFACT_DIR, { recursive: true });
        }
        if (!fs.existsSync(DOCS_DIR)) {
            fs.mkdirSync(DOCS_DIR, { recursive: true });
        }
    });

    const pages = [
        { name: 'holding', path: '/id' },
        { name: 'korin-berkat', path: '/id/bisnis-kami/korin-berkat-energy' },
        { name: 'korin-bara', path: '/id/bisnis-kami/korin-bara-energy' },
        { name: 'beramal', path: '/id/bisnis-kami/beramal-sukses-sejahtera' },
    ];

    for (const p of pages) {
        test(`Capture ${p.name} desktop header and footer`, async ({ page }) => {
            await page.setViewportSize({ width: 1280, height: 800 });
            await page.goto(p.path);
            await page.waitForTimeout(500);

            // Header screenshot
            const header = page.locator('header').first();
            if (await header.isVisible()) {
                const headerFile = `${p.name}-header-desktop.png`;
                await header.screenshot({ path: path.join(DOCS_DIR, headerFile) });
                await header.screenshot({ path: path.join(ARTIFACT_DIR, headerFile) });
            }

            // Scroll to footer and take footer screenshot
            await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
            await page.waitForTimeout(600);

            const footer = page.locator('footer').first();
            if (await footer.isVisible()) {
                const footerFile = `${p.name}-footer-desktop.png`;
                await footer.screenshot({ path: path.join(DOCS_DIR, footerFile) });
                await footer.screenshot({ path: path.join(ARTIFACT_DIR, footerFile) });
            }
        });

        test(`Capture ${p.name} mobile header and footer`, async ({ page }) => {
            await page.setViewportSize({ width: 375, height: 667 });
            await page.goto(p.path);
            await page.waitForTimeout(500);

            // Header screenshot
            const header = page.locator('header').first();
            if (await header.isVisible()) {
                const headerFile = `${p.name}-header-mobile.png`;
                await header.screenshot({ path: path.join(DOCS_DIR, headerFile) });
                await header.screenshot({ path: path.join(ARTIFACT_DIR, headerFile) });
            }

            // Scroll to footer and take footer screenshot
            await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
            await page.waitForTimeout(600);

            const footer = page.locator('footer').first();
            if (await footer.isVisible()) {
                const footerFile = `${p.name}-footer-mobile.png`;
                await footer.screenshot({ path: path.join(DOCS_DIR, footerFile) });
                await footer.screenshot({ path: path.join(ARTIFACT_DIR, footerFile) });
            }
        });
    }
});

