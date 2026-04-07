async function stabilizePage(page) {
  await page.route('**/cdnjs.cloudflare.com/ajax/libs/font-awesome/**', route =>
    route.fulfill({
      status: 200,
      contentType: 'text/css',
      body: ''
    })
  );

  await page.route('**/*.mp4', route => route.fulfill({ status: 204, body: '' }));
  await page.route('**/*.mov', route => route.fulfill({ status: 204, body: '' }));
}

async function resetSiteState(page) {
  await page.addInitScript(() => {
    localStorage.clear();
    sessionStorage.clear();
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
