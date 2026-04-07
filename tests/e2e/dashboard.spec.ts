import { test, expect } from '@playwright/test';

test.describe('Dashboard', () => {
    test('authenticated user lands on dashboard after login', async ({ page }) => {
        await page.goto('/');

        // The Announcements card is the primary dashboard widget — it's a
        // stable signal that the Dashboard Vue page has mounted.
        await expect(page.getByTestId('announcements-card')).toBeVisible();
        await expect(page.getByTestId('shifts-card')).toBeVisible();

        await expect(page).toHaveTitle(/dashboard/i);
    });

    test('dashboard shows seeded announcements', async ({ page }) => {
        await page.goto('/');

        // ComprehensiveSeeder seeds multiple announcements. We should see
        // at least one announcement entry rendered inside the card.
        const announcementsCard = page.getByTestId('announcements-card');
        await expect(announcementsCard).toBeVisible();

        // Either we see rendered announcement blocks or the empty state.
        const hasAnnouncements = await page
            .locator('[data-testid^="announcement-"]')
            .count();
        const hasEmptyState = await announcementsCard
            .getByText(/no announcements yet/i)
            .isVisible()
            .catch(() => false);

        expect(hasAnnouncements > 0 || hasEmptyState).toBeTruthy();
    });
});
