const { test, expect } = require('@playwright/test');
const { gotoAndWait, resetSiteState, stabilizePage } = require('./helpers/test-helpers');

const ADMIN_PASSWORD = process.env.PLAYWRIGHT_ADMIN_PASSWORD
  || (!process.env.PLAYWRIGHT_BASE_URL ? (process.env.TM_ADMIN_PASSWORD || 'playwright-admin-fixture-only') : '');

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
  test.skip(!ADMIN_PASSWORD, 'Set PLAYWRIGHT_ADMIN_PASSWORD for authenticated live checks.');
  await gotoAndWait(page, '/admin/orders.php');
  await expect(page).toHaveURL(/inventory\.php\?next=orders/);
  await page.getByLabel(/Password/i).fill(ADMIN_PASSWORD);
  await page.getByRole('button', { name: /Sign In/i }).click();
  await page.waitForLoadState('domcontentloaded');

  await expect(page).toHaveTitle(/Financials Admin/i);
  await expect(page.getByText('Insurance Orders')).toBeVisible();
  await expect(page.getByText('Insurance Revenue')).toBeVisible();
  await expect(page.getByRole('link', { name: /Management Console/i })).toBeVisible();
});

test('inventory admin requires password and allows access after login', async ({ page }) => {
  test.skip(!ADMIN_PASSWORD, 'Set PLAYWRIGHT_ADMIN_PASSWORD for authenticated live checks.');
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

test('anonymous visitors cannot read orders or save inventory', async ({ request }) => {
  const orders = await request.get('/admin/orders.php', { maxRedirects: 0 });
  expect(orders.status()).toBe(302);
  expect(orders.headers().location).toBe('inventory.php?next=orders');
  const save = await request.post('/admin/save-inventory.php', { form: { inventory: '[]' }, maxRedirects: 0 });
  expect(save.status()).toBe(302);
  expect(await save.text()).not.toMatch(/customer_email|payment_intent/);
});

test('invalid admin credentials do not open the inventory', async ({ page }) => {
  await gotoAndWait(page, '/admin/inventory.php');
  await page.getByLabel(/Password/i).fill('deliberately-incorrect-password');
  await page.getByRole('button', { name: /Sign In/i }).click();
  await expect(page.getByText('Incorrect password.')).toBeVisible();
  await expect(page.getByRole('heading', { name: /Inventory Grid/i })).toHaveCount(0);
});
