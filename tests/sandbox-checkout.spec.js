const { test, expect } = require('@playwright/test');
const { stabilizePage, gotoAndWait } = require('./helpers/test-helpers');

test('configured Stripe sandbox opens hosted checkout without submitting payment', async ({ page }) => {
  test.skip(process.env.PLAYWRIGHT_STRIPE_SANDBOX !== '1', 'Enable only after independently confirming the server uses a Stripe test key.');
  await stabilizePage(page);
  await gotoAndWait(page, '/shop.php');
  await page.locator('.product-card .add-btn').first().click();
  await expect(page.locator('#cart-drawer')).toHaveClass(/open/);
  await page.locator('#checkout-btn').click();
  await expect(page).toHaveURL(/^https:\/\/checkout\.stripe\.com\//, { timeout: 30000 });
  await page.waitForLoadState('domcontentloaded');
  // Stop before entering customer/card information or triggering payment.
});
