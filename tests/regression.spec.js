const { test, expect } = require('@playwright/test');
const { gotoAndWait, resetSiteState, stabilizePage } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => {
  await resetSiteState(page);
  await stabilizePage(page);
});

test('home page hero remains visually stable', async ({ page }) => {
  await gotoAndWait(page, '/index.php');
  await page.waitForTimeout(1000);

  await expect(page.locator('.hero')).toHaveScreenshot('home-hero.png', {
    timeout: 15000,
    maxDiffPixelRatio: page.viewportSize()?.width && page.viewportSize().width < 600 ? 0.05 : 0.02,
  });
});

test('shop page header and first product row remain visually stable', async ({ page }) => {
  await gotoAndWait(page, '/shop.php');
  await page.waitForTimeout(1000);

  await expect(page.locator('.shop-section')).toHaveScreenshot('shop-section.png', {
    maxDiffPixelRatio: 0.02,
  });
});

test('insurance hero and booking section remain visually stable', async ({ page }) => {
  await gotoAndWait(page, '/insurance.php');
  await page.waitForTimeout(1500);

  await expect(page.locator('.insurance-page-shell')).toHaveScreenshot('insurance-section.png', {
    timeout: 15000,
    maxDiffPixelRatio: 0.02,
  });
});
