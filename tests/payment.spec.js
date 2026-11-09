const { test: base, expect } = require('@playwright/test');
const crypto = require('crypto');
const inventory = require('../data/inventory.json');
const { stabilizePage, gotoAndWait } = require('./helpers/test-helpers');

const product = inventory.find(item => item.price > 0 && item.stock > 1 && !item.placeholder);
const test = base.extend({
  caseId: async ({}, use) => use(crypto.randomUUID()),
  request: async ({ playwright, baseURL, caseId }, use) => {
    const request = await playwright.request.newContext({ baseURL, extraHTTPHeaders: { 'x-payment-test-case': caseId } });
    await use(request);
    await request.dispose();
  },
  page: async ({ page, caseId }, use) => {
    await page.setExtraHTTPHeaders({ 'x-payment-test-case': caseId });
    await stabilizePage(page);
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await use(page);
    expect(errors, 'payment pages should not throw JavaScript errors').toEqual([]);
  }
});

async function control(request, data) {
  const response = data ? await request.post('/__payment/control', { data }) : await request.get('/__payment/control');
  expect(response.ok()).toBeTruthy();
  return response.json();
}
async function addTablet(page) {
  await gotoAndWait(page, '/shop.php');
  await page.locator(`.product-card[data-id="${product.id}"] .add-btn`).click();
  await expect(page.locator('#cart-drawer')).toHaveClass(/open/);
}
async function registerForPlan(page, caseId) {
  await gotoAndWait(page, '/plans.php');
  await page.getByRole('link', { name: 'Register for Basic', exact: true }).click();
  const form = page.locator('#reg-form');
  await form.locator('[name="name"]').fill('Registration Tester');
  await form.locator('[name="email"]').fill(`buyer+${caseId}@example.test`);
  await form.locator('[name="brand"]').selectOption('Apple');
  await form.locator('[name="model"]').fill('iPad test device');
  await form.locator('[name="serial_number"]').fill('PAY' + caseId.replace(/-/g, ''));
  await form.getByRole('button', { name: 'Register Tablet and Continue' }).click();
  await expect(page.getByRole('heading', { name: 'Simulated secure checkout' })).toBeVisible();
}
function session(id, overrides = {}) {
  return {
    id, mode: 'payment', status: 'complete', payment_status: 'paid', amount_total: 12345, currency: 'usd',
    customer_details: { email: 'buyer@example.test', name: 'Payment Tester', phone: '+12025550123' },
    metadata: { checkout_type: 'purchase', insurance_plan: 'none', sms_opt_in: '0' },
    line_items: { data: [{ description: product.name, quantity: 1, amount_total: 12345 }], has_more: false }, ...overrides
  };
}
async function webhook(request, value, { type = 'checkout.session.completed', timestamp = Math.floor(Date.now() / 1000), secret = 'whsec_local_payment_tests_only', raw } = {}) {
  const payload = raw ?? JSON.stringify({ id: 'evt_test_' + crypto.randomBytes(8).toString('hex'), type, data: { object: value } });
  const signature = crypto.createHmac('sha256', secret).update(`${timestamp}.${payload}`).digest('hex');
  return request.post('/webhook.php', { data: payload, headers: { 'content-type': 'application/json', 'stripe-signature': `t=${timestamp},v1=${signature}` } });
}

test('purchase completes through the cart, hosted checkout, webhook, and receipt', async ({ page, request }) => {
  await addTablet(page);
  await page.locator('#checkout-btn').click();
  await expect(page.getByRole('heading', { name: 'Simulated secure checkout' })).toBeVisible();
  const before = await control(request);
  expect(before.posts[0]['line_items[0][price_data][unit_amount]']).toBe(String(Math.round(product.price * 100)));
  expect(before.posts[0].mode).toBe('payment');
  await page.getByLabel('Card number').fill('4242424242424242');
  await page.getByRole('button', { name: 'Pay', exact: true }).click();
  await expect(page.locator('.success-title')).toHaveText('Order Confirmed!');
  await expect(page.locator('.success-details')).toContainText(`$${product.price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
  expect(await page.evaluate(() => localStorage.getItem('tm_cart'))).toBeNull();
  await expect(page.locator('#cart-badge')).toHaveText('0');
  const after = await control(request);
  expect(after.orders.filter(order => order.session_id === Object.keys(before.sessions)[0])).toHaveLength(1);
});

test('cancelling preserves the cart, protection plan, and SMS preference', async ({ page }) => {
  await addTablet(page);
  await page.locator('.upsell-plan:has(input[value="protected"])').click();
  await page.locator('#cart-sms-opt-in').check();
  await page.locator('#checkout-btn').click();
  await page.getByRole('link', { name: 'Cancel checkout' }).click();
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('[data-checkout-notice="cancelled"]')).toBeVisible();
  await page.locator('.nav-cart').click();
  await expect(page.locator('#cart-badge')).toHaveText('1');
  await expect(page.locator('input[name="insurance_plan"][value="protected"]')).toBeChecked();
  await expect(page.locator('#cart-sms-opt-in')).toBeChecked();
  await expect(page.locator('#checkout-btn')).toBeEnabled();
});

test('an unverified receipt preserves the cart and does not confirm payment', async ({ page }) => {
  await addTablet(page);
  await gotoAndWait(page, '/success.php');
  await expect(page.locator('.success-title')).toHaveText('Confirming Your Order');
  expect(JSON.parse(await page.evaluate(() => localStorage.getItem('tm_cart')))).toHaveLength(1);
  await expect(page.locator('#cart-badge')).toHaveText('1');
});

test('an unpaid completed subscription does not activate protection', async ({ page, request }) => {
  const id = 'cs_test_unpaidsub';
  await control(request, { sessions: { [id]: session(id, { mode: 'subscription', payment_status: 'unpaid', metadata: { checkout_type: 'subscription', plan: 'basic' } }) } });
  await gotoAndWait(page, `/success.php?session_id=${id}&type=subscription&plan=protected`);
  await expect(page.locator('.success-title')).toHaveText('Confirming Your Plan');
});

test('malformed cart fields fail gracefully without calling Stripe', async ({ request }) => {
  const response = await request.post('/checkout.php', { form: { 'cart[]': 'not-json' }, maxRedirects: 0 });
  expect(response.status()).toBe(302);
  expect(response.headers().location).toBe('/shop.php?error=invalid_cart');
  expect((await control(request)).posts).toHaveLength(0);
});

test('unknown cart items cannot be silently dropped from a purchase', async ({ request }) => {
  const response = await request.post('/checkout.php', { form: { cart: JSON.stringify([{ id: product.id, qty: 1 }, { id: 'missing', qty: 1 }]) }, maxRedirects: 0 });
  expect(response.headers().location).toBe('/shop.php?error=invalid_cart');
  expect((await control(request)).posts).toHaveLength(0);
});

test('unpaid webhook events do not create orders or notifications', async ({ request, caseId }) => {
  const id = 'cs_test_unpaid' + caseId.replace(/-/g, '');
  const value = session(id, { payment_status: 'unpaid' });
  await control(request, { sessions: { [id]: value } });
  expect((await webhook(request, value)).status()).toBe(200);
  const state = await control(request);
  expect(state.orders.filter(order => order.session_id === id)).toHaveLength(0);
  expect(state.notifications['customer-purchase:' + id]).toBeUndefined();
});

test('a delayed successful payment creates its order', async ({ request, caseId }) => {
  const id = 'cs_test_async' + caseId.replace(/-/g, '');
  const value = session(id);
  await control(request, { sessions: { [id]: value } });
  expect((await webhook(request, value, { type: 'checkout.session.async_payment_succeeded' })).status()).toBe(200);
  expect((await control(request)).orders.filter(order => order.session_id === id)).toHaveLength(1);
});

for (const [plan, monthly] of [['basic', 8], ['protected', 12]]) {
  test(`${plan} protection is included in today's total and billed as a subscription`, async ({ page, request }) => {
    await addTablet(page);
    await page.locator(`.upsell-plan:has(input[value="${plan}"])`).click();
    await expect(page.locator('#cart-total')).toHaveText(`$${(product.price + monthly).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
    await page.locator('#checkout-btn').click();
    await expect(page.getByRole('heading', { name: 'Simulated secure checkout' })).toBeVisible();
    const state = await control(request);
    expect(state.posts[0].mode).toBe('subscription');
    expect(state.posts[0]['line_items[1][price]']).toBe(`price_test_${plan}`);
    expect(state.posts[0]['metadata[checkout_type]']).toBe('purchase');
    expect(Object.values(state.sessions)[0].amount_total).toBe(Math.round((product.price + monthly) * 100));
    await page.getByLabel('Card number').fill('4242424242424242');
    await page.getByRole('button', { name: 'Pay', exact: true }).click();
    await expect(page.locator('.success-title')).toHaveText('Order Confirmed!');
    await expect(page.locator('.success-note')).toContainText('will continue monthly');
    expect(await page.evaluate(() => localStorage.getItem('tm_cart'))).toBeNull();
  });
}

test('an accessory can be purchased at the catalog price', async ({ page, request }) => {
  await gotoAndWait(page, '/accessories.php?brand=Apple');
  await page.getByLabel(/search accessories/i).fill('2026');
  await page.locator('.accessory-card:visible .accessory-offer-action').first().click();
  const price = await page.locator('.cart-item-price').innerText();
  await page.locator('#checkout-btn').click();
  await expect(page.getByRole('heading', { name: 'Simulated secure checkout' })).toBeVisible();
  const state = await control(request);
  expect(Object.values(state.sessions)[0].amount_total).toBe(Math.round(Number(price.replace(/[^\d.]/g, '')) * 100));
  await page.getByLabel('Card number').fill('4242424242424242');
  await page.getByRole('button', { name: 'Pay', exact: true }).click();
  await expect(page.locator('.success-title')).toHaveText('Order Confirmed!');
});

test('a declined card leaves the order unpaid and allows a successful retry', async ({ page, request }) => {
  await addTablet(page);
  await page.locator('#checkout-btn').click();
  await page.getByLabel('Card number').fill('4000000000000002');
  await page.getByRole('button', { name: 'Pay', exact: true }).click();
  await expect(page.getByRole('alert')).toContainText('declined');
  let state = await control(request);
  const id = Object.keys(state.sessions)[0];
  expect(state.sessions[id].payment_status).toBe('unpaid');
  expect(state.orders.filter(order => order.session_id === id)).toHaveLength(0);
  expect(JSON.parse(await page.evaluate(() => localStorage.getItem('tm_cart')))).toHaveLength(1);
  await page.getByLabel('Card number').fill('4242424242424242');
  await page.getByRole('button', { name: 'Pay', exact: true }).click();
  await expect(page.locator('.success-title')).toHaveText('Order Confirmed!');
  state = await control(request);
  expect(state.orders.filter(order => order.session_id === id)).toHaveLength(1);
});

test('a standalone plan uses verified metadata and preserves an unrelated shopping cart', async ({ page, request }) => {
  await addTablet(page);
  const response = await request.post('/subscribe.php', { form: { plan: 'basic', email: 'plan@example.test', sms_opt_in: '1' }, maxRedirects: 0 });
  expect(response.status()).toBe(302);
  await gotoAndWait(page, response.headers().location);
  const state = await control(request);
  expect(state.posts[0]['customer_email']).toBe('plan@example.test');
  expect(state.posts[0]['metadata[sms_opt_in]']).toBe('1');
  await page.getByLabel('Card number').fill('4242424242424242');
  await page.getByRole('button', { name: 'Pay', exact: true }).click();
  await expect(page.locator('.success-title')).toHaveText('Protection Active!');
  await gotoAndWait(page, page.url().replace('type=subscription', 'type=purchase').replace('plan=basic', 'plan=protected'));
  await expect(page.locator('.success-title')).toHaveText('Protection Active!');
  await expect(page.locator('.success-desc')).toContainText('Basic Protection');
  await expect(page.locator('.success-desc')).not.toContainText('Protected Plan');
  await expect(page.locator('#cart-badge')).toHaveText('1');
});

test('a Stripe outage returns an actionable notice and preserves checkout choices', async ({ page, request }) => {
  await control(request, { postError: 'http' });
  await addTablet(page);
  await page.locator('.upsell-plan:has(input[value="basic"])').click();
  await page.locator('#checkout-btn').click();
  await expect(page.locator('[data-checkout-notice="stripe"]')).toContainText('Please try again');
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('body')).not.toContainText('Warning:');
  await page.locator('.nav-cart').click();
  await expect(page.locator('#cart-badge')).toHaveText('1');
  await expect(page.locator('input[name="insurance_plan"][value="basic"]')).toBeChecked();
  await expect(page.locator('#checkout-btn')).toBeEnabled();
});

for (const postError of ['malformed', 'scalar']) {
  test(`an invalid ${postError} Stripe response fails gracefully`, async ({ request }) => {
    await control(request, { postError });
    const response = await request.post('/checkout.php', { form: { cart: JSON.stringify([{ id: product.id, qty: 1 }]) }, maxRedirects: 0 });
    expect(response.status()).toBe(302);
    expect(response.headers().location).toBe('/shop.php?error=stripe');
    expect(await response.text()).not.toMatch(/Warning|Fatal error/);
  });
}

const invalidCarts = [
  ['invalid JSON', '{', 'invalid_cart'],
  ['empty list', '[]', 'empty_cart'],
  ['object instead of list', '{"id":54,"qty":1}', 'invalid_cart'],
  ['scalar row', '[1]', 'invalid_cart'],
  ['nested identifier', JSON.stringify([{ id: [], qty: 1 }]), 'invalid_cart'],
  ...[0, -1, 1.5, '1.5', true, [], 1000].map(qty => [`quantity ${JSON.stringify(qty)}`, JSON.stringify([{ id: product.id, qty }]), 'invalid_cart']),
  ['missing quantity', JSON.stringify([{ id: product.id }]), 'invalid_cart'],
  ['too many rows', JSON.stringify(Array.from({ length: 101 }, () => ({ id: product.id, qty: 1 }))), 'invalid_cart']
];
for (const [label, cart, error] of invalidCarts) {
  test(`checkout rejects ${label} before contacting Stripe`, async ({ request }) => {
    const response = await request.post('/checkout.php', { form: { cart }, maxRedirects: 0 });
    expect(response.status()).toBe(302);
    expect(response.headers().location).toBe(`/shop.php?error=${error}`);
    expect((await control(request)).posts).toHaveLength(0);
  });
}

test('prices come from the catalog and duplicate rows are consolidated', async ({ request }) => {
  const cart = [{ id: product.id, qty: 1, price: 0.01, name: 'Forged product' }, { id: product.id, qty: 1 }];
  const response = await request.post('/checkout.php', { form: { cart: JSON.stringify(cart) }, maxRedirects: 0 });
  expect(response.status()).toBe(302);
  const post = (await control(request)).posts[0];
  expect(post['line_items[0][price_data][unit_amount]']).toBe(String(Math.round(product.price * 100)));
  expect(post['line_items[0][price_data][product_data][name]']).toBe(product.name);
  expect(post['line_items[0][quantity]']).toBe('2');
  expect(post['line_items[1][quantity]']).toBeUndefined();
});

test('splitting quantities across rows cannot bypass stock limits', async ({ request }) => {
  const response = await request.post('/checkout.php', { form: { cart: JSON.stringify([{ id: product.id, qty: product.stock }, { id: product.id, qty: 1 }]) }, maxRedirects: 0 });
  expect(response.headers().location).toBe('/shop.php?error=insufficient_stock');
  expect((await control(request)).posts).toHaveLength(0);
});

test('missing protection pricing cannot silently remove coverage from a purchase', async ({ request }) => {
  const response = await request.post('/checkout.php', { headers: { 'x-payment-test-price': 'missing' }, form: { insurance_plan: 'basic', cart: JSON.stringify([{ id: product.id, qty: 1 }]) }, maxRedirects: 0 });
  expect(response.headers().location).toBe('/shop.php?error=stripe');
  expect((await control(request)).posts).toHaveLength(0);
});

for (const [label, form, error] of [
  ['unknown plan', { plan: 'enterprise' }, 'invalid_plan'],
  ['array plan', { 'plan[]': 'basic' }, 'invalid_plan'],
  ['invalid email', { plan: 'basic', email: 'not-an-email' }, 'invalid_email'],
  ['array email', { plan: 'basic', 'email[]': 'buyer@example.test' }, 'invalid_email']
]) {
  test(`subscription rejects ${label}`, async ({ request }) => {
    const response = await request.post('/subscribe.php', { form, maxRedirects: 0 });
    expect(response.status()).toBe(302);
    expect(response.headers().location).toBe(`/plans.php?error=${error}`);
    expect((await control(request)).posts).toHaveLength(0);
  });
}

test('GET requests cannot initiate payments', async ({ request }) => {
  for (const [endpoint, location] of [['checkout.php', '/shop.php'], ['subscribe.php', '/plans.php']]) {
    const response = await request.get('/' + endpoint, { maxRedirects: 0 });
    expect(response.status()).toBe(302);
    expect(response.headers().location).toBe(location);
  }
  expect((await control(request)).posts).toHaveLength(0);
});

for (const [label, overrides] of [
  ['unpaid', { payment_status: 'unpaid' }],
  ['still open', { status: 'open' }],
  ['mismatched session', { id: 'cs_test_another' }]
]) {
  test(`a ${label} receipt cannot confirm an order or erase its cart`, async ({ page, request }) => {
    const id = 'cs_test_receipt';
    await control(request, { sessions: { [id]: session(id, overrides) } });
    await addTablet(page);
    await gotoAndWait(page, `/success.php?session_id=${id}`);
    await expect(page.locator('.success-title')).toHaveText('Confirming Your Order');
    await expect(page.locator('#cart-badge')).toHaveText('1');
    await expect(page.locator('.success-details')).toHaveCount(0);
  });
}

test('invalid and array session identifiers return HTTP 400', async ({ request }) => {
  for (const query of ['session_id=bad', 'session_id[]=cs_test_array', 'session_id=cs_test_x%2F..%2Fsecret']) {
    expect((await request.get('/success.php?' + query)).status()).toBe(400);
  }
});

test('a verified no-payment-required subscription confirms its plan', async ({ page, request }) => {
  const id = 'cs_test_trial';
  await control(request, { sessions: { [id]: session(id, { mode: 'subscription', payment_status: 'no_payment_required', metadata: { checkout_type: 'subscription', plan: 'protected' } }) } });
  await gotoAndWait(page, `/success.php?session_id=${id}&type=subscription&plan=basic`);
  await expect(page.locator('.success-title')).toHaveText('Protection Active!');
  await expect(page.locator('.success-desc')).toContainText('Protected Plan');
});

test('webhooks reject invalid signatures, stale events, and malformed signed payloads', async ({ request }) => {
  expect((await request.get('/webhook.php')).status()).toBe(405);
  expect((await request.post('/webhook.php', { data: '{}' })).status()).toBe(400);
  expect((await webhook(request, {}, { secret: 'wrong-secret' })).status()).toBe(400);
  expect((await webhook(request, {}, { timestamp: Math.floor(Date.now() / 1000) - 600 })).status()).toBe(400);
  expect((await webhook(request, {}, { timestamp: Math.floor(Date.now() / 1000) + 600 })).status()).toBe(400);
  for (const raw of ['not-json', 'true', '[]']) {
    expect((await webhook(request, {}, { raw })).status()).toBe(400);
  }
  expect((await webhook(request, { id: 'not-a-session' })).status()).toBe(400);
  expect((await webhook(request, 'not-an-object')).status()).toBe(400);
});

test('concurrent webhook retries record each session exactly once and respect SMS consent', async ({ request, caseId }) => {
  const prefix = caseId.replace(/-/g, '');
  const sessions = Object.fromEntries(Array.from({ length: 3 }, (_, index) => {
    const id = `cs_test_${prefix}${index}`;
    return [id, session(id, { metadata: { checkout_type: 'purchase', insurance_plan: 'none', sms_opt_in: index === 0 ? '0' : '1' } })];
  }));
  await control(request, { sessions });
  const responses = await Promise.all(Object.values(sessions).flatMap(value => [webhook(request, value), webhook(request, value)]));
  for (const response of responses) expect(response.status()).toBe(200);
  const state = await control(request);
  for (const [index, id] of Object.keys(sessions).entries()) {
    expect(state.orders.filter(order => order.session_id === id)).toHaveLength(1);
    const notification = state.notifications['customer-purchase:' + id];
    if (index === 0) expect(notification).toBeUndefined();
    else expect(notification).toMatchObject({ skipped: true, reason: 'not_configured' });
  }
});

test('a temporary line-item API failure can be retried without losing the order', async ({ request, caseId }) => {
  const id = 'cs_test_retry' + caseId.replace(/-/g, '');
  const value = session(id);
  expect((await webhook(request, value)).status()).toBe(502);
  expect((await control(request)).orders.filter(order => order.session_id === id)).toHaveLength(0);
  await control(request, { sessions: { [id]: value } });
  expect((await webhook(request, value)).status()).toBe(200);
  expect((await control(request)).orders.filter(order => order.session_id === id)).toHaveLength(1);
});

test('registered device coverage becomes active only after its payment webhook', async ({ page, request, caseId }) => {
  await registerForPlan(page, caseId);
  const devices = async () => (await request.get('/__payment-devices.php', { params: { email: `buyer+${caseId}@example.test` } })).json();
  expect(await devices()).toMatchObject([{ plan: 'basic', plan_status: 'pending_payment' }]);
  await page.getByLabel('Card number').fill('4242424242424242');
  await page.getByRole('button', { name: 'Pay', exact: true }).click();
  await expect(page.locator('.success-title')).toHaveText('Protection Active!');
  expect(await devices()).toMatchObject([{ plan: 'basic', plan_status: 'active' }]);
  await expect(page.locator('.success-note')).toContainText('is linked to this plan');
});

test('a cancelled device registration can resume payment without creating another device', async ({ page, request, caseId }) => {
  await registerForPlan(page, caseId);
  await page.getByRole('link', { name: 'Cancel checkout' }).click();
  await expect(page.locator('[data-checkout-notice="cancelled"]')).toBeVisible();
  await registerForPlan(page, caseId);
  const response = await request.get('/__payment-devices.php', { params: { email: `buyer+${caseId}@example.test` } });
  const devices = await response.json();
  expect(devices).toHaveLength(1);
  expect(devices[0].plan_status).toBe('pending_payment');
  const wrongEmail = await request.post('/subscribe.php', { form: { plan: 'basic', email: 'wrong@example.test', registration_number: devices[0].registration_number }, maxRedirects: 0 });
  expect(wrongEmail.headers().location).toBe('/plans.php?error=invalid_registration');
});

test('a missing subscription price returns to plans without contacting Stripe', async ({ request }) => {
  const response = await request.post('/subscribe.php', { headers: { 'x-payment-test-price': 'missing' }, form: { plan: 'basic' }, maxRedirects: 0 });
  expect(response.headers().location).toBe('/plans.php?error=stripe');
  expect((await control(request)).posts).toHaveLength(0);
});

test('webhooks save every line item across multiple API pages', async ({ request, caseId }) => {
  const id = 'cs_test_pages' + caseId.replace(/-/g, '');
  const items = Array.from({ length: 25 }, (_, index) => ({ id: `li_test_${index}`, description: `Item ${index}`, quantity: 1, amount_total: 100 }));
  const value = session(id, { line_items: { data: items }, pageSize: 10 });
  await control(request, { sessions: { [id]: value } });
  expect((await webhook(request, value)).status()).toBe(200);
  const order = (await control(request)).orders.find(order => order.session_id === id);
  expect(order.items).toHaveLength(25);
  expect(order.items[24].name).toBe('Item 24');
});

test('invalid line-item pagination stays retryable without creating an incomplete order', async ({ request, caseId }) => {
  const id = 'cs_test_badpages' + caseId.replace(/-/g, '');
  const value = session(id, { paginationError: true });
  await control(request, { sessions: { [id]: value } });
  expect((await webhook(request, value)).status()).toBe(502);
  expect((await control(request)).orders.filter(order => order.session_id === id)).toHaveLength(0);
});

test('using the browser back button restores an enabled checkout button', async ({ page }) => {
  await addTablet(page);
  await page.locator('#checkout-btn').click();
  await expect(page.getByRole('heading', { name: 'Simulated secure checkout' })).toBeVisible();
  await page.goBack({ waitUntil: 'domcontentloaded' });
  if (!await page.locator('#cart-drawer').evaluate(element => element.classList.contains('open'))) {
    await page.locator('.nav-cart').click();
  }
  await expect(page.locator('#checkout-btn')).toBeEnabled();
  await expect(page.locator('#checkout-btn')).toHaveText('Checkout');
  await expect(page.locator('#cart-badge')).toHaveText('1');
});
