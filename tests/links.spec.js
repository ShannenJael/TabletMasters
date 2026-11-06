const { test, expect } = require('@playwright/test');
const {
  internalPagesForLinkScan,
  isSameOriginUrl,
  normalizePath
} = require('./helpers/site');
const { gotoAndWait, stabilizePage } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => {
  await stabilizePage(page);
  await page.route(/\.(?:png|jpe?g|gif|webp|avif|svg|ico)(?:\?.*)?$/i, route => route.fulfill({ status: 204, body: '' }));
  await page.route(/\.(?:css)(?:\?.*)?$/i, route => route.fulfill({ status: 200, contentType: 'text/css', body: '' }));
  await page.route(/\.(?:js)(?:\?.*)?$/i, route => route.fulfill({ status: 200, contentType: 'text/javascript', body: '' }));
});

test.setTimeout(180000);

test('public pages and their internal links and assets resolve successfully', async ({ page, request, baseURL }) => {
  const discovered = new Set(['/index.php']);

  for (const currentPage of internalPagesForLinkScan) {
    await gotoAndWait(page, currentPage);

    const urls = await page.locator('a[href], link[href], script[src], img[src], source[src]').evaluateAll(
      elements => elements
        .map(element => element.getAttribute('href') || element.getAttribute('src'))
        .filter(Boolean)
    );

    for (const candidate of urls) {
      if (!candidate || candidate.startsWith('#') || candidate.startsWith('mailto:') || candidate.startsWith('tel:') || candidate.startsWith('javascript:')) {
        continue;
      }

      if (!isSameOriginUrl(candidate, baseURL)) {
        continue;
      }

      const normalized = normalizePath(candidate, baseURL);
      discovered.add(normalized);
    }
  }

  const paths = [...discovered];
  // Check real HTTP responses even though assets are stubbed in the browser.
  // HEAD avoids downloading large images and videos just to check their status.
  await Promise.all(Array.from({ length: 4 }, async () => {
    while (paths.length) {
      const path = paths.shift();
      const response = await request.head(path, { timeout: 20000 });
      expect.soft(response.ok(), `${path} returned HTTP ${response.status()}`).toBeTruthy();
      await response.dispose();
    }
  }));
});

test('navigation links avoid javascript URLs and empty hrefs', async ({ page }) => {
  for (const currentPage of internalPagesForLinkScan) {
    await gotoAndWait(page, currentPage);

    const invalidLinks = await page.locator('a[href]').evaluateAll(elements =>
      elements
        .map(element => ({
          text: (element.textContent || '').trim(),
          href: element.getAttribute('href') || ''
        }))
        .filter(link => link.href === '' || /^javascript:/i.test(link.href))
    );

    expect(invalidLinks, `${currentPage} contains invalid links`).toEqual([]);
  }
});
