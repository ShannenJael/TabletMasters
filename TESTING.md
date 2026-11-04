# Tablet Masters Test Suite

This project now includes Playwright coverage for:

- browser testing of the main customer flows
- link testing for internal pages and assets
- visual regression testing with screenshots

## 1. Install prerequisites

You need both of these available on your machine:

- `PHP 8+`
- `Node.js 18+`

## 2. Install dependencies

```bash
npm install
npx playwright install
```

## 3. Run the test suite

```bash
npm test
```

Run a single category:

```bash
npm run test:browser
npm run test:links
npm run test:regression
```

Run with the local PHP server managed automatically by Playwright:

```bash
npm test
```

Playwright starts `tests/support/php-server.js`, which runs a small pool of
`php -S` processes behind a Node proxy (PHP's built-in server is single-threaded
and flaky on Windows under parallel browsers). It needs `php` on `PATH`, or set
`$env:PHP_BINARY="C:\path\to\php.exe"`. Static `/assets/**` files are served
straight from disk by the test helpers during local runs.

If you want to point the tests at a live site instead, skip the local web server and set:

```bash
$env:PLAYWRIGHT_BASE_URL="https://tablet-masters.com"
npm test
```

## 4. Create or refresh regression baselines

The first visual regression run will create snapshots. To update them intentionally:

```bash
npm run test:update-snapshots
```

## What the tests cover

- `tests/browser.spec.js`
  Checks core navigation, mobile menu behavior, shop search/filtering, cart behavior, and insurance form access.

- `tests/links.spec.js`
  Crawls the key pages, gathers same-origin links/assets, and verifies they return successful responses.

- `tests/regression.spec.js`
  Captures screenshot baselines for the home, shop, and insurance sections.

## Notes

- The suite blocks service workers during tests to reduce flakiness.
- External links are ignored by the link checker.
- Font Awesome and video assets are stubbed during tests to keep screenshots stable.
