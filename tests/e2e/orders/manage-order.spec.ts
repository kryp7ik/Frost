import { test, expect, Page } from '@playwright/test';

/** Create a fresh order and return its ID. */
async function createOrder(page: Page): Promise<number> {
    await page.goto('/orders/create');
    await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });

    const match = page.url().match(/\/orders\/(\d+)\/show/);
    return parseInt(match?.[1] ?? '0', 10);
}

test.describe('Orders - Customer Management', () => {
    test('set customer by phone shows customer info', async ({ page }) => {
        // Get a real customer phone from the customers page
        await page.goto('/customers');
        await expect(page.getByTestId('customers-table-card')).toBeVisible({ timeout: 10_000 });
        const phoneCell = page.locator('tbody tr:first-child td:nth-child(2)');
        const phone = (await phoneCell.textContent())?.trim() ?? '';

        // Now create an order and set that customer
        const orderId = await createOrder(page);
        expect(orderId).toBeGreaterThan(0);

        await page.getByTestId('order-customer-phone').locator('input').fill(phone);
        await page.getByTestId('order-customer-submit').click();

        // After setting customer, the customer card should display their info (including "points")
        await expect(page.getByTestId('order-customer-card')).toContainText(/points/i, { timeout: 10_000 });
    });

    test('customer card shows "No customer attached" on fresh order', async ({ page }) => {
        await createOrder(page);

        await expect(page.getByTestId('order-customer-card')).toContainText('No customer attached');
    });
});

test.describe('Orders - Discount Management', () => {
    test('apply discount from dropdown', async ({ page }) => {
        await createOrder(page);

        // The discount dropdown should be populated with seeded discounts
        const discountSelect = page.getByTestId('order-discount-select');
        await expect(discountSelect).toBeVisible();

        // Open the dropdown and select the first option
        await discountSelect.click();
        const firstOption = page.getByRole('option').first();
        await expect(firstOption).toBeVisible({ timeout: 5_000 });
        const discountName = (await firstOption.textContent())?.trim() ?? '';
        await firstOption.click();

        // Click Apply
        await page.getByTestId('order-discount-submit').click();

        // The discount should now appear in the discounts list
        await expect(page.getByTestId('order-discounts-card')).toContainText(discountName, { timeout: 10_000 });
    });

    test('remove discount from order', async ({ page }) => {
        await createOrder(page);

        // Apply a discount first
        await page.getByTestId('order-discount-select').click();
        const firstOption = page.getByRole('option').first();
        await expect(firstOption).toBeVisible({ timeout: 5_000 });
        const discountName = (await firstOption.textContent())?.trim() ?? '';
        await firstOption.click();
        await page.getByTestId('order-discount-submit').click();
        await expect(page.getByTestId('order-discounts-card')).toContainText(discountName, { timeout: 10_000 });

        // Now remove it by clicking the close/delete button next to the discount
        const removeButton = page.getByTestId('order-discounts-card').locator('.mdi-close').first();
        await removeButton.click();

        // The discount name should no longer be in the list
        // (wait for the page to update after the Inertia redirect)
        await expect(page.getByTestId('order-discounts-card')).not.toContainText(discountName, { timeout: 10_000 });
    });
});

test.describe('Orders - Delete Order', () => {
    test('delete order with confirmation redirects to create page', async ({ page }) => {
        await createOrder(page);

        // Set up dialog handler to accept the confirmation
        page.on('dialog', (dialog) => dialog.accept());

        await page.getByTestId('order-delete').click();

        // After deletion, redirects to /orders/create which creates a new order
        await expect(page.getByTestId('order-items-card')).toBeVisible({ timeout: 10_000 });
    });

    test('dismiss delete confirmation keeps order open', async ({ page }) => {
        const orderId = await createOrder(page);

        // Set up dialog handler to dismiss the confirmation
        page.on('dialog', (dialog) => dialog.dismiss());

        await page.getByTestId('order-delete').click();

        // Should still be on the order page
        await expect(page.getByTestId('order-items-card')).toBeVisible();
        expect(page.url()).toContain(`/orders/${orderId}/show`);
    });
});
