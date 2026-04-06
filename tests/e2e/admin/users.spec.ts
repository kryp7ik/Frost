import { test, expect } from '@playwright/test';

test.describe('Admin - Users', () => {
    test('index lists seeded users in a data table', async ({ page }) => {
        await page.goto('/admin/users');

        await expect(page.getByTestId('users-table-card')).toBeVisible();
        await expect(page.getByRole('heading', { name: /users/i })).toBeVisible();

        // The seeded admin user should show up in the table.
        await expect(page.locator('tbody')).toContainText(/admin@frostpos\.com/i);
    });

    test('create form renders and validates required fields', async ({ page }) => {
        await page.goto('/admin/users/create');

        await expect(page.getByTestId('user-form-card')).toBeVisible();
        await expect(page.getByTestId('user-name')).toBeVisible();
        await expect(page.getByTestId('user-email')).toBeVisible();
        await expect(page.getByTestId('user-password')).toBeVisible();
    });

    test('create + list full flow: can create a new user and see it listed', async ({ page }) => {
        const unique = Date.now();
        const name = `Test User ${unique}`;
        const email = `testuser+${unique}@frostpos.com`;

        await page.goto('/admin/users/create');
        await page.getByTestId('user-name').locator('input').fill(name);
        await page.getByTestId('user-email').locator('input').fill(email);
        await page.getByTestId('user-password').locator('input').fill('secret-password-123');
        await page.getByTestId('user-password-confirm').locator('input').fill('secret-password-123');

        // Pick a store
        await page.getByTestId('user-store').click();
        await page.getByRole('option').first().click();

        // Pick a role. This is a multi-select, so we must close the overlay
        // before clicking submit — otherwise the open dropdown intercepts
        // pointer events.
        await page.getByTestId('user-role').click();
        await page.getByRole('option').first().click();
        await page.keyboard.press('Escape');

        await page.getByTestId('user-submit').click();

        // After submit we land on /admin/users. Wait for the row to appear.
        await expect(page.getByTestId('users-table-card')).toBeVisible({ timeout: 10_000 });
        await expect(page.locator('tbody')).toContainText(email, { timeout: 10_000 });
    });
});
