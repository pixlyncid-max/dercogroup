import { test } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

const DOCS_DIR = 'docs/branding/korin-bara-redesign';
const ARTIFACT_DIR = 'C:/Users/Ganesha Arta/.gemini/antigravity/brain/782a98a2-9419-452e-8733-d18d0dacdd19/screenshots/korin-bara-redesign';

test.describe('Capture Korin Bara Redesign Multi-Resolution Screenshots', () => {
    test.beforeAll(() => {
        if (!fs.existsSync(DOCS_DIR)) {
            fs.mkdirSync(DOCS_DIR, { recursive: true });
        }
        if (!fs.existsSync(ARTIFACT_DIR)) {
            fs.mkdirSync(ARTIFACT_DIR, { recursive: true });
        }
    });

    const viewports = [
        { name: '360px', width: 360, height: 740 },
        { name: '768px', width: 768, height: 1024 },
        { name: '1280px', width: 1280, height: 900 },
        { name: '1440px', width: 1440, height: 960 },
    ];

    const pages = [
        {
            key: 'home-id',
            url: '/id/bisnis-kami/korin-bara-energy',
        },
        {
            key: 'home-en',
            url: '/en/our-business/korin-bara-energy',
        },
        {
            key: 'about-id',
            url: '/id/bisnis-kami/korin-bara-energy/tentang-kami',
        },
        {
            key: 'about-en',
            url: '/en/our-business/korin-bara-energy/about-us',
        },
        {
            key: 'operations-id',
            url: '/id/bisnis-kami/korin-bara-energy/operasi',
        },
        {
            key: 'operations-en',
            url: '/en/our-business/korin-bara-energy/operations',
        },
        {
            key: 'products-id',
            url: '/id/bisnis-kami/korin-bara-energy/produk',
        },
        {
            key: 'products-en',
            url: '/en/our-business/korin-bara-energy/products',
        },
        {
            key: 'contact-id',
            url: '/id/bisnis-kami/korin-bara-energy/kontak',
        },
        {
            key: 'contact-en',
            url: '/en/our-business/korin-bara-energy/contact',
        },
    ];

    for (const vp of viewports) {
        for (const p of pages) {
            test(`Capture ${p.key} at ${vp.name}`, async ({ page }) => {
                await page.setViewportSize({ width: vp.width, height: vp.height });
                await page.emulateMedia({ reducedMotion: 'reduce' });
                await page.goto(p.url, { waitUntil: 'networkidle' });
                await page.waitForTimeout(400);

                const filename = `${p.key}-${vp.name}.png`;
                const docsPath = path.join(DOCS_DIR, filename);
                const artifactPath = path.join(ARTIFACT_DIR, filename);

                await page.screenshot({ path: docsPath, fullPage: true });
                try {
                    await page.screenshot({ path: artifactPath, fullPage: true });
                } catch {
                    // Ignore artifact copy if path is unavailable
                }
            });
        }
    }
});
