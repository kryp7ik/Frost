import { test, expect } from '@playwright/test';

/**
 * Loaded-at-all smoke coverage. For every migrated Inertia page we visit
 * the URL and assert a page-specific data-testid is visible. This is not
 * full interaction coverage but is a reliable guard against a page's
 * controller or Vue entry regressing in a 500 or white-screen way.
 */
const routes: Array<{ url: string; testid: string; name: string }> = [
    { url: '/', testid: 'announcements-card', name: 'Dashboard' },
    { url: '/account/edit', testid: 'account-edit-card', name: 'Account edit' },
    { url: '/account/two-factor', testid: '', name: 'Two-factor (no assertion)' },
    { url: '/announcements', testid: 'announcements-list', name: 'Announcements list' },
    { url: '/announcements/create', testid: 'announcement-form-card', name: 'Announcement create' },
    { url: '/customers', testid: 'customers-table-card', name: 'Customers' },
    { url: '/orders', testid: 'orders-table-card', name: 'Orders list' },
    { url: '/orders/create', testid: 'order-create-card', name: 'Order create' },
    { url: '/schedule', testid: 'schedule-card', name: 'Schedule home' },
    { url: '/admin', testid: 'admin-card-products', name: 'Admin home' },
    { url: '/admin/roles', testid: 'roles-table-card', name: 'Roles index' },
    { url: '/admin/roles/create', testid: 'role-form-card', name: 'Role create' },
    { url: '/admin/users', testid: 'users-table-card', name: 'Users index' },
    { url: '/admin/users/create', testid: 'user-form-card', name: 'User create' },
    { url: '/admin/store/products/index', testid: 'products-table-card', name: 'Products index' },
    { url: '/admin/store/products/redline', testid: 'redline-card', name: 'Products redline' },
    { url: '/admin/store/products/create', testid: 'product-form-card', name: 'Product create' },
    { url: '/admin/store/ingredients', testid: 'ingredients-table-card', name: 'Ingredients' },
    { url: '/admin/store/recipes', testid: 'recipes-table-card', name: 'Recipes' },
    { url: '/admin/store/recipes/create', testid: 'recipe-form-card', name: 'Recipe create' },
    { url: '/admin/store/discounts', testid: 'discounts-table-card', name: 'Discounts' },
    { url: '/admin/store/shipments', testid: 'shipments-table-card', name: 'Shipments' },
    { url: '/admin/store/shipments/create', testid: 'shipment-form-card', name: 'Shipment create' },
    { url: '/admin/store/transfers', testid: 'transfers-table-card', name: 'Transfers' },
    { url: '/admin/store/transfers/create', testid: 'transfer-form-card', name: 'Transfer create' },
    { url: '/admin/store/inventory/create', testid: 'inventory-card', name: 'Inventory count' },
    { url: '/admin/store/report/sales', testid: 'sales-filters-card', name: 'Sales report' },
    { url: '/admin/store/report/inventory', testid: 'inventory-filters-card', name: 'Inventory report' },
    { url: '/admin/store/touch', testid: 'touch-pending-card', name: 'Touch' },
];

test.describe('Page smoke tests', () => {
    for (const route of routes) {
        test(`${route.name} loads (${route.url})`, async ({ page }) => {
            const response = await page.goto(route.url);
            expect(response?.status(), `${route.url} returned ${response?.status()}`).toBeLessThan(400);

            if (route.testid) {
                await expect(
                    page.getByTestId(route.testid),
                    `${route.url} expected testid=${route.testid}`
                ).toBeVisible({ timeout: 10_000 });
            }
        });
    }
});
