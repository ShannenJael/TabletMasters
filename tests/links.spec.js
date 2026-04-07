const { test, expect } = require('@playwright/test');
const {
  internalPagesForLinkScan,
  isSameOriginUrl,
  normalizePath
} = require('./helpers/site');
const { gotoAndWait, stabilizePage } = require('./helpers/test-helpers');

test.beforeEach(async ({ page }) => {
  await stabilizePage(page);
});

test('core internal links resolve successfully', async ({ page, request, baseURL }) => {
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

      discovered.add(normalizePath(candidate, baseURL));
    }
  }

  for (const path of [...discovered]) {
    const response = await request.get(path);
    expect.soft(response.ok(), `${path} should return a successful response`).toBeTruthy();
  }
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
