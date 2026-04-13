const { test, expect } = require('@playwright/test');
const { gotoAndWait, resetSiteState, stabilizePage } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => {
  await resetSiteState(page);
  await stabilizePage(page);
});

test('homepage hero and primary navigation work', async ({ page }) => {
  await gotoAndWait(page, '/index.php');

  await expect(page).toHaveTitle(/Tablet Masters/i);
  await expect(page.locator('.hero-title')).toContainText('TABLET');
  await expect(page.locator('.hero-actions .btn-primary')).toHaveAttribute('href', 'shop.php');

  await page.getByRole('link', { name: /shop tablets/i }).first().click();
  await expect(page).toHaveURL(/shop\.php/);

  await page.getByRole('link', { name: /plans & pricing/i }).first().click();
  await expect(page).toHaveURL(/plans\.php/);
});

test('mobile navigation opens and routes to pages', async ({ page, isMobile }) => {
  test.skip(!isMobile, 'This check is only meaningful on the mobile project.');

  await gotoAndWait(page, '/index.php');

  const mobileNav = page.locator('#mobile-nav');
  await expect(mobileNav).not.toHaveClass(/open/);

  await page.getByRole('button', { name: /menu/i }).click();
  await expect(mobileNav).toHaveClass(/open/);

  await mobileNav.getByRole('link', { name: /^About$/i }).click();
  await expect(page).toHaveURL(/about\.php/);
  await expect(page.locator('.section-title')).toContainText('WHY TABLET MASTERS');
});

test('shop filtering, search, and cart interactions behave correctly', async ({ page }) => {
  await gotoAndWait(page, '/shop.php?brand=Samsung');

  await expect(page.locator('.brand-tab.active')).toHaveText('Samsung');
  await expect(page.locator('.product-card[data-brand="Samsung"]').first()).toBeVisible();

  await page.getByLabel(/search tablets/i).fill('Surface');
  await expect(page.locator('.product-card:visible')).toHaveCount(0);
  await expect(page.locator('#shop-empty-state')).toBeVisible();

  await page.getByLabel(/search tablets/i).fill('');
  await page.getByRole('button', { name: 'All' }).click();
  await expect(page.locator('.product-card:visible').first()).toBeVisible();

  await page.locator('.product-card:visible .add-btn').first().click();
  await expect(page.locator('#cart-badge')).toHaveText('1');

  // Cart drawer may auto-open after add — close it first, then reopen via nav button
  const drawer = page.locator('#cart-drawer');
  if (await drawer.evaluate(el => el.classList.contains('open'))) {
    await page.locator('.cart-close').click();
    await expect(drawer).not.toHaveClass(/open/);
  }

  await page.locator('.nav-cart').click();
  await expect(drawer).toHaveClass(/open/);
  await expect(page.locator('#cart-items')).toContainText(/iPad|Galaxy|Surface|Fire/i);
});

test('insurance page repair form is reachable from CTA', async ({ page }) => {
  await gotoAndWait(page, '/insurance.php');

  await expect(page.locator('.insurance-hero .section-title')).toContainText(/INSURANCE\s*& REPAIR/i);

  await page.getByRole('link', { name: /book a repair/i }).first().click();
  await expect(page.locator('#repair-form')).toBeInViewport();
  await expect(page.locator('#repair-form [name="email"]')).toBeVisible();
  await expect(page.locator('#repair-form [name="device"]')).toBeVisible();
});
