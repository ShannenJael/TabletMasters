const { test, expect } = require('@playwright/test');
const { stabilizePage } = require('./helpers/test-helpers');

test.use({ serviceWorkers: 'allow' });

test('offline caching keeps public pages and excludes private pages and queries', async ({ page, context }) => {
  await stabilizePage(page);
  await page.goto('/index.php', { waitUntil: 'domcontentloaded' });
  await page.evaluate(async () => {
    await navigator.serviceWorker.register('/sw.js');
    await navigator.serviceWorker.ready;
    if (!navigator.serviceWorker.controller) {
      await new Promise(resolve => navigator.serviceWorker.addEventListener('controllerchange', resolve, { once: true }));
    }
  });
  await page.goto('/index.php', { waitUntil: 'domcontentloaded' });
  await page.evaluate(async () => {
    await fetch('/register.php?email=cache-test%40example.test');
    await fetch('/admin/orders.php');
  });
  const keys = await page.evaluate(async () => {
    const names = await caches.keys();
    const entries = await Promise.all(names.map(async name => (await (await caches.open(name)).keys()).map(request => request.url)));
    return entries.flat();
  });
  expect(keys.some(url => url.endsWith('/index.php'))).toBeTruthy();
  expect(keys.filter(url => /\/admin\/|\/register\.php|\/success\.php|email=/.test(url))).toEqual([]);
  await context.setOffline(true);
  await page.reload({ waitUntil: 'domcontentloaded' });
  await expect(page.locator('.hero-title')).toContainText('TABLET');
  await context.setOffline(false);
});
