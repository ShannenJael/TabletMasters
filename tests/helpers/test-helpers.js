const fs = require('fs');
const path = require('path');

const repoRoot = path.resolve(__dirname, '..', '..');
const isLocalRun = !process.env.PLAYWRIGHT_BASE_URL;
const staticTypes = {
  '.css': 'text/css',
  '.js': 'text/javascript',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.webp': 'image/webp',
  '.avif': 'image/avif',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.json': 'application/json'
};

// PHP's built-in server handles one request at a time, so streaming large
// images to one browser stalls page/CSS loads in another. For local runs,
// serve static assets straight from disk and leave PHP only the .php pages.
async function serveStaticAssetsFromDisk(page) {
  if (!isLocalRun) return;
  await page.route(/\/assets\//, async route => {
    const url = new URL(route.request().url());
    const filePath = path.join(repoRoot, decodeURIComponent(url.pathname));
    if (!filePath.startsWith(repoRoot) || !fs.existsSync(filePath) || fs.statSync(filePath).isDirectory()) {
      return route.fallback();
    }
    const contentType = staticTypes[path.extname(filePath).toLowerCase()] || 'application/octet-stream';
    return route.fulfill({ status: 200, contentType, body: fs.readFileSync(filePath) });
  });
}

async function stabilizePage(page) {
  await serveStaticAssetsFromDisk(page);
  await page.route('**/cdnjs.cloudflare.com/ajax/libs/font-awesome/**', route =>
    route.fulfill({
      status: 200,
      contentType: 'text/css',
      body: ''
    })
  );
  await page.route('**/fonts.googleapis.com/**', route =>
    route.fulfill({
      status: 200,
      contentType: 'text/css',
      body: ''
    })
  );
  await page.route('**/fonts.cdnfonts.com/**', route =>
    route.fulfill({
      status: 200,
      contentType: 'text/css',
      body: ''
    })
  );
  await page.route('**/fonts.gstatic.com/**', route => route.fulfill({ status: 204, body: '' }));
  await page.route('**/*.woff', route => route.fulfill({ status: 204, body: '' }));
  await page.route('**/*.woff2', route => route.fulfill({ status: 204, body: '' }));
  await page.route('**/*.ttf', route => route.fulfill({ status: 204, body: '' }));

  // Third-party YouTube embeds throw their own network errors inside the
  // iframe; keep them out of page-error checks and off the network.
  await page.route(/^https:\/\/(?:www\.)?youtube(?:-nocookie)?\.com\//, route =>
    route.fulfill({ status: 200, contentType: 'text/html', body: '<!doctype html><title>video</title>' })
  );

  await page.route('**/*.mp4', route => route.fulfill({ status: 204, body: '' }));
  await page.route('**/*.mov', route => route.fulfill({ status: 204, body: '' }));
}

async function resetSiteState(page) {
  await page.addInitScript(() => {
    localStorage.clear();
    sessionStorage.clear();
    if (window === window.top && 'serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistrations().then(registrations => {
        registrations.forEach(registration => registration.unregister());
      }).catch(() => {});
    }
  });
}

async function gotoAndWait(page, path) {
  await page.goto(path, { waitUntil: 'domcontentloaded' });
  await page.waitForFunction(() => document.readyState !== 'loading', null, { timeout: 10000 }).catch(() => {});
}

async function waitForImages(page, selector) {
  await page.waitForFunction(sel => {
    const root = document.querySelector(sel);
    if (!root) return false;
    return [...root.querySelectorAll('img')]
      .filter(img => img.offsetParent !== null)
      .every(img => img.complete);
  }, selector, { timeout: 15000 }).catch(() => {});
}

module.exports = {
  gotoAndWait,
  waitForImages,
  resetSiteState,
  stabilizePage
};
