import { test, expect, Page } from '@playwright/test';

/**
 * Create an order, add a product via API, pay it off, then return
 * the order ID. The order will be complete (closed).
 */
async function createCompletedOrder(page: Page): Promise<number> {
    await page.goto('/orders/create');
    await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

    const match = page.url().match(/\/orders\/(\d+)\/show/);
    const orderId = parseInt(match?.[1] ?? '0', 10);

    // Add a product via API (instance 1 = first seeded product, store 1)
    await page.evaluate(async (oid: number) => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        await fetch(`/orders/${oid}/add-product`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'text/html, application/xhtml+xml',
            },
            body: JSON.stringify({ products: [{ instance: 1, quantity: 1 }] }),
        });
    }, orderId);

    await page.reload();
    await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

    // Pay the full amount to close the order
    await page.getByTestId('order-payment-amount').locator('input').fill('999.99');
    await page.getByTestId('order-payment-submit').click();
    await expect(page.getByTestId('receipt-card')).toBeVisible({ timeout: 10_000 });

    return orderId;
}

test.describe('Orders - Closed Order', () => {
    test('completed order renders ShowClosed with order details', async ({ page }) => {
        const orderId = await createCompletedOrder(page);

        // Navigate to the order — should render ShowClosed since it's complete
        await page.goto(`/orders/${orderId}/show`);
        await expect(page.getByTestId('order-closed-card')).toBeVisible({ timeout: 10_000 });
    });

    test('closed order displays items and payments', async ({ page }) => {
        const orderId = await createCompletedOrder(page);

        await page.goto(`/orders/${orderId}/show`);
        await expect(page.getByTestId('order-closed-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByTestId('order-closed-card')).toContainText('Total');
        await expect(page.getByTestId('order-closed-card')).toContainText('Payments');
    });

    test('closed order heading shows "(Closed)" label', async ({ page }) => {
        const orderId = await createCompletedOrder(page);

        await page.goto(`/orders/${orderId}/show`);
        await expect(page.getByTestId('order-closed-card')).toBeVisible({ timeout: 10_000 });

        await expect(page.getByRole('heading', { name: /closed/i })).toBeVisible();
    });

    test('back button navigates to orders index', async ({ page }) => {
        const orderId = await createCompletedOrder(page);

        await page.goto(`/orders/${orderId}/show`);
        await expect(page.getByTestId('order-closed-card')).toBeVisible({ timeout: 10_000 });

        await page.getByRole('link', { name: 'Orders', exact: true }).click();
        await expect(page.getByTestId('orders-table-card')).toBeVisible({ timeout: 10_000 });
    });
});
