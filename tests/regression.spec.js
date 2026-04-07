const { test, expect } = require('@playwright/test');
const { gotoAndWait, resetSiteState, stabilizePage } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => {
  await resetSiteState(page);
  await stabilizePage(page);
});

test('home page hero remains visually stable', async ({ page }) => {
  await gotoAndWait(page, '/index.php');

  await expect(page.locator('.hero')).toHaveScreenshot('home-hero.png');
});

test('shop page header and first product row remain visually stable', async ({ page }) => {
  await gotoAndWait(page, '/shop.php');

  await expect(page.locator('.shop-section')).toHaveScreenshot('shop-section.png');
});

test('insurance hero and booking section remain visually stable', async ({ page }) => {
  await gotoAndWait(page, '/insurance.php');

  await expect(page.locator('.ins-section')).toHaveScreenshot('insurance-section.png');
});
