import { test, expect } from '@playwright/test';

test.describe('Orders - Create Flow', () => {
    test('"New Order" nav link creates order and redirects to ShowOpen', async ({ page }) => {
        await page.goto('/orders');

        // Click the navbar "New Order" link
        await page.goto('/orders/create');

        // Should redirect directly to /orders/{id}/show (ShowOpen)
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });
        expect(page.url()).toMatch(/\/orders\/\d+\/show/);
    });

    test('ShowOpen renders all management cards', async ({ page }) => {
        await page.goto('/orders/create');
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByTestId('order-discounts-card')).toBeVisible();
        await expect(page.getByTestId('order-customer-card')).toBeVisible();
        await expect(page.getByTestId('order-total-card')).toBeVisible();
        await expect(page.getByTestId('order-payment-card')).toBeVisible();
        await expect(page.getByTestId('order-delete')).toBeVisible();
    });

    test('new order shows "No items yet" empty state', async ({ page }) => {
        await page.goto('/orders/create');
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByTestId('order-items-card')).toContainText('No items yet');
    });

    test('new order shows $0.00 total', async ({ page }) => {
        await page.goto('/orders/create');
        await expect(page.getByTestId('order-total-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByTestId('order-total-card')).toContainText('$0.00');
    });

    test('add product form is visible on new order', async ({ page }) => {
        await page.goto('/orders/create');
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByTestId('order-add-product-select')).toBeVisible();
        await expect(page.getByTestId('order-add-product-qty')).toBeVisible();
        await expect(page.getByTestId('order-add-product-submit')).toBeVisible();
    });

    test('add liquid form is visible on new order', async ({ page }) => {
        await page.goto('/orders/create');
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByTestId('order-add-liquid-recipe')).toBeVisible();
        await expect(page.getByTestId('order-add-liquid-size')).toBeVisible();
        await expect(page.getByTestId('order-add-liquid-submit')).toBeVisible();
    });
});
