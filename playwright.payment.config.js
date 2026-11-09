const { defineConfig } = require('@playwright/test');
const base = require('./playwright.config');

// Payment tests always use an isolated local application and a fake Stripe API.
const port = Number(process.env.PAYMENT_TEST_PORT || 3200);
module.exports = defineConfig({
  ...base,
  testIgnore: [],
  testMatch: 'payment.spec.js',
  workers: 2,
  outputDir: 'test-results/payment',
  reporter: [['list'], ['html', { outputFolder: 'playwright-report/payment', open: 'never' }]],
  use: {
    ...base.use,
    baseURL: `http://127.0.0.1:${port}`,
    trace: 'retain-on-failure'
  },
  webServer: {
    command: 'node tests/support/payment-server.js',
    url: `http://127.0.0.1:${port}`,
    reuseExistingServer: false,
    timeout: 120000,
    env: { HOST: '127.0.0.1', PORT: String(port) }
  }
});
