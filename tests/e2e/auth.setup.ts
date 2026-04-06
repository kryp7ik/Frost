import { test as setup, expect } from '@playwright/test';
import path from 'node:path';
import fs from 'node:fs';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const authFile = path.join(__dirname, '.auth', 'admin.json');

/**
 * Logs in once as the seeded admin user and persists the authenticated
 * browser context to tests/e2e/.auth/admin.json.
 *
 * Every other Playwright project (see playwright.config.ts) reuses that
 * storageState so individual tests start already authenticated.
 *
 * The credentials must match database/seeders/UserTableSeeder.php. They are
 * loaded from .env (TEST_USER_EMAIL / TEST_USER_PASSWORD) so secrets never
 * live in the test file.
 */
setup('authenticate as admin', async ({ page }) => {
    const email = process.env.TEST_USER_EMAIL;
    const password = process.env.TEST_USER_PASSWORD;

    if (!email || !password) {
        throw new Error(
            'TEST_USER_EMAIL and TEST_USER_PASSWORD must be set in .env. ' +
                'They should match the admin user created by database/seeders/UserTableSeeder.php.'
        );
    }

    await page.goto('/users/login');
    await expect(page.getByLabel(/email/i)).toBeVisible();

    await page.getByLabel(/email/i).fill(email);
    await page.getByLabel(/password/i).fill(password);
    await page.getByRole('button', { name: /log in|sign in/i }).click();

    // After login, Inertia navigates via SPA. Poll for the URL to stop
    // pointing at /users/login — this is the most reliable signal given that
    // some post-login targets are still rendered as legacy Blade pages that
    // do a full reload, while others are Inertia SPA transitions.
    await expect
        .poll(
            async () => new URL(page.url()).pathname,
            { timeout: 15_000, intervals: [250, 500, 1000] }
        )
        .not.toMatch(/\/users\/login/);

    // Ensure the parent dir exists and persist.
    fs.mkdirSync(path.dirname(authFile), { recursive: true });
    await page.context().storageState({ path: authFile });
});
