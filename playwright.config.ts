import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: false,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 2 : 0,
    workers: 1,
    reporter: 'list',
    use: {
        baseURL: process.env.APP_URL || 'http://127.0.0.1:8000',
        trace: 'on-first-retry',
    },
    webServer: [
        {
            command: 'node bootstrap/ssr/ssr.js',
            port: 13714,
            reuseExistingServer: false,
            timeout: 15000,
        },
        {
            command: 'php artisan serve --host=127.0.0.1 --port=8000',
            url: 'http://127.0.0.1:8000',
            reuseExistingServer: !process.env.CI,
            timeout: 30000,
        },
    ],
    projects: [
        {
            name: 'chromium',
            use: {
                ...devices['Desktop Chrome'],
                channel: process.env.CI ? undefined : 'chrome',
            },
        },
    ],
});

