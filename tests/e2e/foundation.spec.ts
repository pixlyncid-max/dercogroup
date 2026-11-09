import { expect, test } from '@playwright/test';

test.describe('Phase 0 Foundation — Bilingual Routing & SSR', () => {
    test('renders Indonesian (/id) and English (/en) pages with distinct localized text', async ({
        page,
    }) => {
        await page.goto('/id');
        await expect(page.locator('html')).toHaveAttribute('lang', 'id');
        await expect(page.getByRole('heading', { level: 1 })).toHaveText('PT Derco Bara Energy');
        await expect(
            page.getByText('GRUP PERTAMBANGAN & INFRASTRUKTUR ENERGI'),
        ).toBeVisible();

        await page.goto('/en');
        await expect(page.locator('html')).toHaveAttribute('lang', 'en');
        await expect(page.getByRole('heading', { level: 1 })).toHaveText('PT Derco Bara Energy');
        await expect(
            page.getByText('MINING & ENERGY INFRASTRUCTURE GROUP'),
        ).toBeVisible();
    });
});

