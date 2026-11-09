# Tablet Masters Test Suite

This project now includes Playwright coverage for:

- browser testing of the main customer flows
- link testing for internal pages and assets
- visual regression testing with screenshots

## 1. Install prerequisites

You need both of these available on your machine:

- `PHP 8.1+`
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
npm run test:payment
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

## Payment tests

Run `npm run test:payment` to exercise desktop Chromium and mobile Chrome against
a disposable copy of the PHP application. This command always uses loopback;
`PLAYWRIGHT_BASE_URL` cannot point the payment suite at a deployed site.
The regular `npm test` suite excludes these tests because they require this
separate application and simulator.

The payment server generates fake Stripe credentials, intercepts server-side
Stripe HTTPS calls, disables email transport and SMS, and copies only the seed
inventory into a new data directory. It never reads a real `includes/config.php`
or copies customer databases/logs. Each test has its own simulated Stripe state.
The hosted checkout page, card approval/decline, and signed webhook delivery are
simulations; the site's PHP endpoints, browser forms, redirects, storage, and
webhook signature checks execute for real.

Requirements are Node.js, Playwright Chromium, and PHP 8.1+ with `pdo_sqlite` for
device-registration coverage. Set `PHP_BINARY` if PHP is not on PATH. For a
Windows PHP distribution with an `ext/php_pdo_sqlite.dll` next to the interpreter,
the harness loads that extension automatically. Otherwise enable `pdo_sqlite`
in your PHP configuration.

```powershell
$env:PHP_BINARY = 'C:\path\to\php.exe'
npm run test:payment
npx playwright show-report playwright-report/payment
```

Coverage includes purchases, accessories, both protection plans, standalone
subscriptions, cart totals, cancellation/retry, declined cards, provider errors,
malformed inputs, server-authoritative pricing, stock validation, pending and
verified receipts, SMS consent, webhook signatures, delayed payments, and
concurrent duplicate delivery. Failure screenshots, videos, and traces are saved
under `test-results/payment`; the HTML report is under `playwright-report/payment`.
Registration tests follow the Plans page into device registration, check that
coverage stays pending until payment, verify activation by the signed webhook,
and retry cancelled signups without duplicating device records. The suite also
checks paginated order items and recovery through the browser Back button.
Disposable `test-results/payment-app-*` directories may be removed after a run.

## Full-site and deployment checks

The public-site suite covers all 17 public pages on desktop and mobile, customer
navigation and cart interactions, form constraints, malformed request handling,
internal links/assets, screenshot baselines, admin login boundaries, and offline
cache privacy. Email/quote/repair forms are tested with invalid data or blocked
submission; the live suite does not send customer or staff messages.

Local admin checks use a test-only password injected into the PHP test process.
For authenticated live checks, set `PLAYWRIGHT_ADMIN_PASSWORD` from your private
credential store. The application requires `TM_ADMIN_PASSWORD_HASH` in its private
config/environment (or an explicitly configured legacy `ADMIN_PASSWORD`). There
is no built-in production password. Anonymous financial-dashboard access is denied.

An optional `PLAYWRIGHT_STRIPE_SANDBOX=1` check opens a real hosted Checkout
session without submitting payment. Enable it only after confirming the server
uses a Stripe test key. It is skipped in ordinary local/live runs.

`deploy-production.ps1` creates a manifest from tracked runtime files, uploads a
release to the account's private `.tabletmasters-deployments` directory, checks
SHA-256 hashes and server PHP syntax, backs up changed files, and replaces those
files atomically. Live inventory, databases, logs, and payment/SMS credentials
are excluded. Failed application/hash checks restore the previous files. If no
private admin credential exists, deployment creates a unique password, stores
only its hash in private config, and downloads the password to the ignored local
deployment artifact directory. Existing private admin credentials are preserved.

The deployment transaction has four isolated checks:

```powershell
python tests/deployment_test.py -v
.\deploy-production.ps1 -SshKey 'C:\path\to\id_ed25519_antipolo'
```

The standalone Python checks need PHP on PATH. Access-rule checks run only on
the deployed Apache host; PHP's built-in local server does not apply `.htaccess`.

For a real Stripe sandbox smoke test, copy `includes/config.example.php` to the
ignored `includes/config.php`, supply a sandbox secret key, matching USD monthly
price IDs ($8 Basic and $12 Protected), your local `SITE_URL`, and the signing
secret from `stripe listen --forward-to localhost:3100/webhook.php`. PHP needs
HTTPS/OpenSSL support for real Stripe calls. Forward both
`checkout.session.completed` and `checkout.session.async_payment_succeeded`.
Use Stripe's test card `4242 4242 4242 4242` with a future expiry and any CVC.
Confirm the webhook and order record as well as the success page. Stripe's
[fulfillment guide](https://docs.stripe.com/checkout/fulfillment) describes the
payment-status and delayed-payment requirements. Automated simulator results do
not verify account credentials, actual Stripe Checkout, 3-D Secure, renewal
invoices, refunds, tax/shipping configuration, or delivery of real email/SMS.
