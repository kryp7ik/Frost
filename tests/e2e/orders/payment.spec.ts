import { test, expect, Page } from '@playwright/test';

/**
 * Create a fresh order, add a product via API, then reload so the page
 * reflects the updated order state. Returns the order ID.
 */
async function createOrderWithProduct(page: Page): Promise<number> {
    // Create the order — GET /orders/create now directly creates and redirects
    await page.goto('/orders/create');
    await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

    const match = page.url().match(/\/orders\/(\d+)\/show/);
    const orderId = parseInt(match?.[1] ?? '0', 10);

    // Add a product instance via API.
    // Instance ID 1 is the first seeded product instance (store 1).
    // We use page.evaluate so the fetch runs inside the browser context
    // with full cookie + CSRF token support.
    const status = await page.evaluate(async (oid: number) => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        const res = await fetch(`/orders/${oid}/add-product`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'text/html, application/xhtml+xml',
            },
            body: JSON.stringify({
                products: [{ instance: 1, quantity: 1 }],
            }),
        });
        return res.status;
    }, orderId);

    // The endpoint redirects (302), fetch follows it — any 2xx/3xx is success
    expect(status).toBeLessThan(400);

    // Reload page to see updated order with the product attached
    await page.reload();
    await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

    return orderId;
}

test.describe('Orders - Payment Flow', () => {
    test('cash payment on order with product redirects to receipt', async ({ page }) => {
        const orderId = await createOrderWithProduct(page);

        // The order should now have at least one product listed
        await expect(page.getByTestId('order-items-card')).not.toContainText('No items yet');

        // Read the order total from the total card
        const totalText = await page.getByTestId('order-total-card').textContent();
        const totalMatch = totalText?.match(/Order Total\s*\$(\d+\.\d{2})/);
        const orderTotal = totalMatch ? totalMatch[1] : '50.00';

        // Fill in a cash payment for the full amount
        await page.getByTestId('order-payment-type').click();
        await page.getByRole('option', { name: 'Cash' }).click();
        await page.getByTestId('order-payment-amount').locator('input').fill(orderTotal);
        await page.getByTestId('order-payment-submit').click();

        // Should redirect to the receipt page
        await expect(page.getByTestId('receipt-card')).toBeVisible({ timeout: 10_000 });
        expect(page.url()).toContain(`/orders/${orderId}/receipt`);
    });

    test('overpayment shows change due on receipt', async ({ page }) => {
        await createOrderWithProduct(page);

        // Overpay by a large amount
        await page.getByTestId('order-payment-type').click();
        await page.getByRole('option', { name: 'Cash' }).click();
        await page.getByTestId('order-payment-amount').locator('input').fill('999.99');
        await page.getByTestId('order-payment-submit').click();

        // Receipt should show change due alert
        await expect(page.getByTestId('receipt-card')).toBeVisible({ timeout: 10_000 });
        await expect(page.getByTestId('receipt-change')).toBeVisible();
        await expect(page.getByTestId('receipt-change')).toContainText('Change due');
    });

    test('receipt page has "New Order" link', async ({ page }) => {
        await createOrderWithProduct(page);

        // Pay the order
        await page.getByTestId('order-payment-amount').locator('input').fill('999.99');
        await page.getByTestId('order-payment-submit').click();
        await expect(page.getByTestId('receipt-card')).toBeVisible({ timeout: 10_000 });

        // Click "New Order" link — now directly creates a new order
        await page.getByRole('link', { name: 'New Order', exact: true }).click();
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });
        expect(page.url()).toMatch(/\/orders\/\d+\/show/);
    });

    test('payment on empty order does not redirect to receipt', async ({ page }) => {
        // Create order without adding products
        await page.goto('/orders/create');
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

        // Try to pay on the empty order
        await page.getByTestId('order-payment-amount').locator('input').fill('20.00');
        await page.getByTestId('order-payment-submit').click();

        // Should stay on the ShowOpen page (order can't be completed without items)
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

        // Should NOT have navigated to receipt
        expect(page.url()).not.toContain('/receipt');
    });
});
