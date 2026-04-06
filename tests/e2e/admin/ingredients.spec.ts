import { test, expect } from '@playwright/test';

test.describe('Admin - Ingredients', () => {
    test('index lists ingredients in a data table', async ({ page }) => {
        await page.goto('/admin/store/ingredients');

        await expect(page.getByTestId('ingredients-table-card')).toBeVisible();
        await expect(page.getByRole('heading', { name: /ingredients/i })).toBeVisible();
    });

    test('new-ingredient dialog opens and accepts input', async ({ page }) => {
        await page.goto('/admin/store/ingredients');

        await page.getByTestId('ingredients-create-button').click();
        await expect(page.getByTestId('ingredient-dialog')).toBeVisible();

        const unique = Date.now();
        const name = `Test Ingredient ${unique}`;
        await page.getByTestId('ingredient-name').locator('input').fill(name);
        await page.getByTestId('ingredient-vendor').locator('input').fill('Acme Supply');
        await page.getByTestId('ingredient-submit').click();

        // Back on the table, new ingredient should appear.
        await expect(page.locator('tbody')).toContainText(name, { timeout: 10_000 });
    });
});
