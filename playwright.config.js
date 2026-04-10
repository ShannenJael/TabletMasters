// @ts-check
const { defineConfig, devices } = require('@playwright/test');

const PORT = process.env.PORT || '3100';
const HOST = process.env.HOST || '127.0.0.1';
const baseURL = process.env.PLAYWRIGHT_BASE_URL || `http://${HOST}:${PORT}`;

module.exports = defineConfig({
  testDir: './tests',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 2 : (process.env.PLAYWRIGHT_BASE_URL ? 3 : undefined),
  timeout: 60000,
  reporter: process.env.CI ? [['html'], ['github']] : 'list',
  use: {
    baseURL,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    serviceWorkers: 'block'
  },
  expect: {
    toHaveScreenshot: {
      animations: 'disabled',
      caret: 'hide',
      scale: 'css'
    }
  },
  webServer: process.env.PLAYWRIGHT_BASE_URL ? undefined : {
    command: process.env.PLAYWRIGHT_WEB_SERVER_COMMAND || `php -S ${HOST}:${PORT} -t .`,
    url: baseURL,
    reuseExistingServer: !process.env.CI,
    timeout: 120 * 1000
  },
  projects: [
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        viewport: { width: 1440, height: 960 }
      }
    },
    {
      name: 'mobile-chrome',
      use: {
        ...devices['Pixel 7']
      }
    }
  ]
});
