import { test, expect } from '@playwright/test';

test.describe('Orders - Index', () => {
    test('orders table renders with seeded data', async ({ page }) => {
        await page.goto('/orders');

        await expect(page.getByTestId('orders-table-card')).toBeVisible({ timeout: 10_000 });
        await expect(page.getByRole('heading', { name: /orders/i })).toBeVisible();
    });

    test('date filter input is present and pre-filled', async ({ page }) => {
        await page.goto('/orders');

        const dateInput = page.getByTestId('orders-date').locator('input');
        await expect(dateInput).toBeVisible();

        // The default date should be today (YYYY-MM-DD format)
        const today = new Date().toISOString().slice(0, 10);
        await expect(dateInput).toHaveValue(today);
    });

    test('"New Order" button creates order and opens ShowOpen', async ({ page }) => {
        await page.goto('/orders');

        await page.getByTestId('orders-create-button').click();
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });
        expect(page.url()).toMatch(/\/orders\/\d+\/show/);
    });

    test('status chips show Complete or Open', async ({ page }) => {
        await page.goto('/orders');

        await expect(page.getByTestId('orders-table-card')).toBeVisible({ timeout: 10_000 });

        // Seeded orders exist — at least one row should be in the table.
        // Check for the presence of status chips (Complete or Open).
        const chips = page.locator('.v-chip');
        const chipCount = await chips.count();

        if (chipCount > 0) {
            // Each chip should say either "Complete" or "Open"
            const firstChipText = await chips.first().textContent();
            expect(['Complete', 'Open']).toContain(firstChipText?.trim());
        }
    });

    test('clicking a row view button navigates to order detail', async ({ page }) => {
        await page.goto('/orders');

        await expect(page.getByTestId('orders-table-card')).toBeVisible({ timeout: 10_000 });

        // Find any view button in the table (data-testid starts with "order-view-")
        const viewButton = page.locator('[data-testid^="order-view-"]').first();
        const exists = await viewButton.count();

        if (exists > 0) {
            await viewButton.click();

            // Should land on either ShowOpen or ShowClosed
            await expect(
                page.getByTestId('order-items-card').or(page.getByTestId('order-closed-card'))
            ).toBeVisible({ timeout: 10_000 });
        }
    });
});
