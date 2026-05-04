const { test, expect } = require('@playwright/test');
const { gotoAndWait, resetSiteState, stabilizePage } = require('./helpers/test-helpers');

const ADMIN_PASSWORD = 'tm-admin-2026!';

test.beforeEach(async ({ page }) => {
  await resetSiteState(page);
  await stabilizePage(page);
});

test('management console loads and links to admin tools', async ({ page }) => {
  await gotoAndWait(page, '/admin/index.php');

  await expect(page).toHaveTitle(/Management Console/i);
  await expect(page.getByText('Management Console').first()).toBeVisible();
  await expect(page.getByRole('link', { name: /Open Orders/i })).toHaveAttribute('href', 'orders.php');
  await expect(page.getByRole('link', { name: /Open Inventory/i })).toHaveAttribute('href', 'inventory.php');
  await expect(page.getByRole('link', { name: /Open Bluehost/i })).toHaveAttribute('href', /bluehost\.com/);
});

test('orders admin page shows insurance totals', async ({ page }) => {
  await gotoAndWait(page, '/admin/orders.php');

  await expect(page).toHaveTitle(/Financials Admin/i);
  await expect(page.getByText('Insurance Orders')).toBeVisible();
  await expect(page.getByText('Insurance Revenue')).toBeVisible();
  await expect(page.getByRole('link', { name: /Management Console/i })).toBeVisible();
});

test('inventory admin requires password and allows access after login', async ({ page }) => {
  await gotoAndWait(page, '/admin/inventory.php');

  await expect(page.getByText(/Inventory Admin/i)).toBeVisible();
  await expect(page.getByLabel(/Password/i)).toBeVisible();

  await page.getByLabel(/Password/i).fill(ADMIN_PASSWORD);
  await page.getByRole('button', { name: /Sign In/i }).click();

  await expect(page).toHaveTitle(/Inventory Admin/i);
  await expect(page.getByRole('heading', { name: /Inventory Grid/i })).toBeVisible();
  await expect(page.getByRole('button', { name: /Add Item/i })).toBeVisible();
  await expect(page.getByRole('button', { name: /Cases/i })).toBeVisible();
  await expect(page.locator('thead')).toContainText(/Type/i);
  await expect(page.locator('thead')).toContainText(/Match Key/i);
  await expect(page.getByRole('link', { name: /Management Console/i })).toBeVisible();
});
