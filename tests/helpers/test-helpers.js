async function stabilizePage(page) {
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

  await page.route('**/*.mp4', route => route.fulfill({ status: 204, body: '' }));
  await page.route('**/*.mov', route => route.fulfill({ status: 204, body: '' }));
}

async function resetSiteState(page) {
  await page.addInitScript(() => {
    localStorage.clear();
    sessionStorage.clear();
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistrations().then(registrations => {
        registrations.forEach(registration => registration.unregister());
      });
    }
  });
}

async function gotoAndWait(page, path) {
  await page.goto(path, { waitUntil: 'domcontentloaded' });
  await page.waitForLoadState('networkidle').catch(() => {});
}

module.exports = {
  gotoAndWait,
  resetSiteState,
  stabilizePage
};
