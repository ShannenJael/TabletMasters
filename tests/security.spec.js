const { test, expect } = require('@playwright/test');

test('deployment denies access to private files and source directories', async ({ request }) => {
  test.skip(!process.env.PLAYWRIGHT_BASE_URL, 'Access rules require the deployed Apache server.');
  for (const path of [
    '/.git/HEAD', '/includes/config.php', '/includes/config.php.bak.20260715195359',
    '/data/tablet-masters.sqlite', '/data/logs/orders.json', '/tests/admin.spec.js',
    '/scripts/deploy-release.py'
  ]) {
    const response = await request.head(path, { maxRedirects: 0 });
    expect([401, 403, 404, 406], `${path} must not be publicly served`).toContain(response.status());
  }
});
