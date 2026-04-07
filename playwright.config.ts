import { defineConfig, devices } from '@playwright/test';
import 'dotenv/config';

/**
 * Playwright E2E config for Frost.
 *
 * Tests log in once via tests/e2e/auth.setup.ts using the seeded admin
 * credentials from database/seeders/UserTableSeeder.php (wired into .env as
 * TEST_USER_EMAIL / TEST_USER_PASSWORD) and persist the session to
 * tests/e2e/.auth/admin.json. All other projects reuse that storageState.
 */
export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: false,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 2 : 0,
    workers: process.env.CI ? 1 : 1,
    reporter: [['list'], ['html', { open: 'never', outputFolder: 'tests/e2e/.report' }]],
    timeout: 30_000,
    expect: { timeout: 10_000 },
    use: {
        baseURL: process.env.PLAYWRIGHT_BASE_URL || 'http://localhost:8000',
        trace: 'on-first-retry',
        screenshot: 'only-on-failure',
        video: 'retain-on-failure',
        ignoreHTTPSErrors: true,
        actionTimeout: 10_000,
        navigationTimeout: 15_000,
    },
    projects: [
        {
            name: 'setup',
            testMatch: /auth\.setup\.ts/,
        },
        {
            name: 'chromium',
            use: {
                ...devices['Desktop Chrome'],
                storageState: 'tests/e2e/.auth/admin.json',
            },
            dependencies: ['setup'],
        },
    ],
});
