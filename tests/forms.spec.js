const { test, expect } = require('@playwright/test');
const { stabilizePage, gotoAndWait } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => stabilizePage(page));

for (const [path, selector, endpoint] of [
  ['/insurance.php', '#repair-form', 'send-repair.php'],
  ['/schools.php', '.school-form-grid', 'send-school-quote.php'],
  ['/healthcare-hospitals.php', '.healthcare-form', 'send-healthcare-quote.php'],
  ['/business-conferences.php', '.conference-page-form', 'send-conference-quote.php'],
  ['/register.php?source=external&plan=protected', '#reg-form', '/save-device.php']
]) {
  test(`${path} validates required fields before submitting`, async ({ page }) => {
    await gotoAndWait(page, path);
    const form = page.locator(selector);
    await expect(form).toHaveAttribute('method', /post/i);
    await expect(form).toHaveAttribute('action', endpoint);
    expect(await form.evaluate(element => element.checkValidity())).toBe(false);
    let submissions = 0;
    await page.route(`**/${endpoint.replace(/^\//, '')}`, async route => {
      if (route.request().method() === 'POST') submissions++;
      await route.abort();
    });
    await form.locator('[type="submit"]').click();
    expect(submissions).toBe(0);
    expect(await form.locator(':invalid').count()).toBeGreaterThan(0);
  });
}

test('registration plan links preserve source and selected protection', async ({ page }) => {
  await gotoAndWait(page, '/plans.php');
  await page.getByRole('link', { name: 'Register for Protected', exact: true }).click();
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('input[name="purchase_source"][value="external"]')).toBeChecked();
  await expect(page.locator('input[name="plan"][value="protected"]')).toBeChecked();
  await expect(page.locator('#reg-plan-group')).toBeVisible();
});
