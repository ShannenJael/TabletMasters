const { test, expect } = require('@playwright/test');
const { publicPages } = require('./helpers/site');
const { resetSiteState, stabilizePage } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => {
  await resetSiteState(page);
  await stabilizePage(page);
});

for (const path of publicPages) {
  test(`${path} loads without application errors and fits the viewport`, async ({ page }) => {
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));

    const response = await page.goto(path, { waitUntil: 'load' });
    expect(response.status()).toBe(200);
    await expect(page).toHaveTitle(/Tablet Masters/i);
    await expect(page.locator('.nav .logo')).toBeVisible();
    await expect(page.locator('h1, .section-title').first()).toBeVisible();
    await expect(page.locator('body')).not.toContainText(/Fatal error:|Warning: (?:include|require|Undefined)|Parse error:/);
    expect(errors, `${path} has uncaught JavaScript errors`).toEqual([]);

    const dimensions = await page.evaluate(() => ({
      content: document.documentElement.scrollWidth,
      viewport: document.documentElement.clientWidth
    }));
    expect(dimensions.content, `${path} has horizontal overflow`).toBeLessThanOrEqual(dimensions.viewport + 1);
  });
}
