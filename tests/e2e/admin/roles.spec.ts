import { test, expect } from '@playwright/test';

test.describe('Admin - Roles', () => {
    test('index lists seeded roles', async ({ page }) => {
        await page.goto('/admin/roles');

        await expect(page.getByTestId('roles-table-card')).toBeVisible();
        // Manager and admin roles are created by UserTableSeeder.
        await expect(page.locator('tbody')).toContainText(/manager/i);
        await expect(page.locator('tbody')).toContainText(/admin/i);
    });

    test('create form renders fields', async ({ page }) => {
        await page.goto('/admin/roles/create');

        await expect(page.getByTestId('role-form-card')).toBeVisible();
        await expect(page.getByTestId('role-name')).toBeVisible();
        await expect(page.getByTestId('role-display-name')).toBeVisible();
    });
});
