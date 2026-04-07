import { test, expect } from '@playwright/test';

test.describe('Admin - Discounts', () => {
    test('index lists discounts in a data table', async ({ page }) => {
        await page.goto('/admin/store/discounts');

        await expect(page.getByTestId('discounts-table-card')).toBeVisible();
        await expect(page.getByRole('heading', { name: /discounts/i })).toBeVisible();
    });

    test('new-discount dialog opens and accepts input', async ({ page }) => {
        await page.goto('/admin/store/discounts');

        await page.getByTestId('discounts-create-button').click();
        await expect(page.getByTestId('discount-dialog')).toBeVisible();

        const unique = Date.now();
        const name = `Test Discount ${unique}`;
        await page.getByTestId('discount-name').locator('input').fill(name);
        await page.getByTestId('discount-amount').locator('input').fill('10');
        await page.getByTestId('discount-submit').click();

        await expect(page.locator('tbody')).toContainText(name, { timeout: 10_000 });
    });
});
