// A disposable copy of the real PHP app, backed by a loopback Stripe simulator.
// No real config, database, webhook secret, mail, or SMS credentials are copied.
const fs = require('fs');
const path = require('path');
const http = require('http');
const crypto = require('crypto');
const { spawn } = require('child_process');

const repo = path.resolve(__dirname, '..', '..');
const results = path.join(repo, 'test-results');
fs.mkdirSync(results, { recursive: true });
const root = fs.mkdtempSync(path.join(results, 'payment-app-'));
for (const entry of fs.readdirSync(repo)) {
  if (/\.php$/.test(entry) || ['manifest.json', 'sw.js'].includes(entry)) {
    fs.copyFileSync(path.join(repo, entry), path.join(root, entry));
  }
}
for (const dir of ['includes', 'assets', 'public']) {
  if (fs.existsSync(path.join(repo, dir))) {
    fs.cpSync(path.join(repo, dir), path.join(root, dir), {
      recursive: true,
      filter: source => source !== path.join(repo, 'includes', 'config.php')
    });
  }
}
fs.mkdirSync(path.join(root, 'data'));
fs.copyFileSync(path.join(repo, 'data', 'inventory.json'), path.join(root, 'data', 'inventory.json'));
const port = Number(process.env.PORT || 3200);
const appPort = port + 10;
const mockPort = port + 20;
const origin = `http://127.0.0.1:${port}`;
const secret = 'whsec_local_payment_tests_only';
fs.writeFileSync(path.join(root, 'includes', 'config.php'), `<?php
define('SITE_URL', '${origin}');
define('STRIPE_SECRET_KEY', 'sk_test_fixture_' . preg_replace('/[^a-zA-Z0-9-]/', '', $_SERVER['HTTP_X_PAYMENT_TEST_CASE'] ?? 'default'));
define('STRIPE_WEBHOOK_SECRET', '${secret}');
define('STRIPE_PRICE_BASIC', ($_SERVER['HTTP_X_PAYMENT_TEST_PRICE'] ?? '') === 'missing' ? '' : 'price_test_basic');
define('STRIPE_PRICE_PROTECTED', 'price_test_protected');
define('TM_SMS_ENABLED', false);
define('TM_SUPPORT_ALERT_NUMBER', '');
`);
fs.writeFileSync(path.join(root, '__payment-devices.php'), `<?php
require __DIR__ . '/includes/database.php';
header('Content-Type: application/json');
$statement = getDB()->prepare('SELECT registration_number, customer_email, plan, plan_status, order_id FROM devices WHERE customer_email = ?');
$statement->execute([$_GET['email'] ?? '']);
echo json_encode($statement->fetchAll());
`);

const cases = new Map();
function stateFor(id) {
  if (!cases.has(id)) cases.set(id, { posts: [], sessions: {}, postError: null });
  return cases.get(id);
}
function json(res, value, status = 200) {
  res.writeHead(status, { 'content-type': 'application/json' });
  res.end(JSON.stringify(value));
}
async function body(req) {
  let data = '';
  for await (const chunk of req) data += chunk;
  return data;
}
async function readLog(name, fallback) {
  // Windows denies reads while PHP holds its exclusive order-file lock.
  for (let attempt = 0; attempt < 100; attempt++) {
    try { return JSON.parse(fs.readFileSync(path.join(root, 'data', 'logs', name), 'utf8')); }
    catch (error) {
      if (error.code === 'ENOENT') return fallback;
      if (error.code !== 'EBUSY' && !(error instanceof SyntaxError)) throw error;
      await new Promise(resolve => setTimeout(resolve, 20));
    }
  }
  throw new Error(`Timed out reading test log: ${name}`);
}
function lineItems(params) {
  const indexes = [...new Set([...params.keys()].map(key => /^line_items\[(\d+)\]/.exec(key)?.[1]).filter(Boolean))];
  return indexes.map(index => {
    const key = `line_items[${index}]`;
    const price = params.get(`${key}[price]`);
    const amount = price === 'price_test_basic' ? 800 : price === 'price_test_protected' ? 1200 : Number(params.get(`${key}[price_data][unit_amount]`));
    const quantity = Number(params.get(`${key}[quantity]`));
    return {
      description: params.get(`${key}[price_data][product_data][name]`) || (price === 'price_test_basic' ? 'Basic Protection' : 'Protected Plan'),
      quantity, amount_total: amount * quantity, currency: 'usd', price: { id: price }
    };
  });
}

const mock = http.createServer(async (req, res) => {
  const caseId = (req.headers.authorization || '').replace('Bearer sk_test_fixture_', '');
  const state = stateFor(caseId);
  const url = new URL(req.url, origin);
  if (req.method === 'POST' && url.pathname === '/v1/checkout/sessions') {
    const params = new URLSearchParams(await body(req));
    state.posts.push(Object.fromEntries(params));
    if (state.postError) {
      if (state.postError === 'malformed') { res.end('not-json'); return; }
      if (state.postError === 'scalar') { json(res, 'not-an-object'); return; }
      json(res, { error: { message: 'Simulated Stripe outage' } }, 503);
      return;
    }
    const id = 'cs_test_' + crypto.randomBytes(12).toString('hex');
    const metadata = Object.fromEntries([...params].filter(([key]) => key.startsWith('metadata[')).map(([key, value]) => [key.slice(9, -1), value]));
    const items = lineItems(params);
    const session = {
      id, object: 'checkout.session', mode: params.get('mode'), status: 'open', payment_status: 'unpaid',
      metadata, line_items: { data: items, has_more: false }, amount_total: items.reduce((sum, item) => sum + item.amount_total, 0),
      currency: 'usd', created: Math.floor(Date.now() / 1000),
      customer_details: { name: 'Payment Tester', email: params.get('customer_email') || 'buyer@example.test', phone: '+12025550123' },
      success_url: params.get('success_url'), cancel_url: params.get('cancel_url'),
      url: `${origin}/__payment/checkout/${id}`
    };
    state.sessions[id] = session;
    json(res, session);
    return;
  }
  const match = /^\/v1\/checkout\/sessions\/(cs_[\w]+)(\/line_items)?$/.exec(url.pathname);
  if (match) {
    const session = state.sessions[match[1]];
    if (!session) { json(res, { error: { message: 'No such session' } }, 404); return; }
    if (match[2]) {
      if (session.paginationError) { json(res, { data: [], has_more: true }); return; }
      const items = session.line_items?.data || [];
      const cursor = url.searchParams.get('starting_after');
      const start = cursor ? items.findIndex(item => item.id === cursor) + 1 : 0;
      const limit = Math.min(Number(url.searchParams.get('limit') || 100), session.pageSize || 100);
      json(res, { data: items.slice(start, start + limit), has_more: start + limit < items.length });
    } else json(res, session);
    return;
  }
  json(res, { error: 'Unimplemented mock endpoint' }, 404);
});

async function sendWebhook(caseId, session) {
  const payload = JSON.stringify({ id: 'evt_test_' + crypto.randomBytes(8).toString('hex'), type: 'checkout.session.completed', data: { object: session } });
  const timestamp = Math.floor(Date.now() / 1000);
  const signature = crypto.createHmac('sha256', secret).update(`${timestamp}.${payload}`).digest('hex');
  return fetch(`${origin}/webhook.php`, { method: 'POST', body: payload, headers: {
    'content-type': 'application/json', 'x-payment-test-case': caseId, 'stripe-signature': `t=${timestamp},v1=${signature}`
  } });
}

const server = http.createServer(async (req, res) => {
  const caseId = req.headers['x-payment-test-case'] || 'default';
  const state = stateFor(caseId);
  const url = new URL(req.url, origin);
  if (url.pathname === '/__payment/control') {
    if (req.method === 'POST') Object.assign(state, JSON.parse(await body(req)));
    const [orders, notifications] = await Promise.all([readLog('orders.json', []), readLog('sms_notifications.json', {})]);
    json(res, { ...state, orders, notifications });
    return;
  }
  const match = /^\/__payment\/checkout\/(cs_[\w]+)$/.exec(url.pathname);
  if (match) {
    const session = state.sessions[match[1]];
    if (!session) { res.writeHead(404); res.end('Missing simulated session'); return; }
    if (req.method === 'POST') {
      const params = new URLSearchParams(await body(req));
      if (params.get('card')?.replace(/\s/g, '') !== '4000000000000002') {
        session.status = 'complete';
        session.payment_status = 'paid';
        const webhook = await sendWebhook(caseId, session);
        if (!webhook.ok) { res.writeHead(502); res.end('Simulated webhook failed'); return; }
        res.writeHead(303, { location: session.success_url.replace('{CHECKOUT_SESSION_ID}', session.id) });
        res.end();
        return;
      }
    }
    res.writeHead(200, { 'content-type': 'text/html; charset=utf-8' });
    res.end(`<!doctype html><meta name="viewport" content="width=device-width, initial-scale=1"><title>Simulated secure checkout</title>
      <h1>Simulated secure checkout</h1><p>USD ${(session.amount_total / 100).toFixed(2)}</p>
      ${req.method === 'POST' ? '<p role="alert">Your card was declined. Try another card.</p>' : ''}
      <form method="POST"><label>Card number <input name="card" required></label><button>Pay</button></form>
      <a href="${session.cancel_url}">Cancel checkout</a>`);
    return;
  }
  const upstream = http.request({ hostname: '127.0.0.1', port: appPort, path: req.url, method: req.method, headers: { ...req.headers, connection: 'close' } }, upstreamRes => {
    res.writeHead(upstreamRes.statusCode, upstreamRes.headers);
    upstreamRes.pipe(res);
  });
  upstream.on('error', error => { if (!res.headersSent) res.writeHead(502); res.end(error.message); });
  req.pipe(upstream);
});

const extraArgs = ['-d', 'disable_functions=mail', '-d', `auto_prepend_file=${path.join(__dirname, 'payment-prepend.php')}`];
const php = process.env.PHP_BINARY;
if (php && fs.existsSync(path.join(path.dirname(php), 'ext', 'php_pdo_sqlite.dll'))) {
  extraArgs.push('-d', `extension_dir=${path.join(path.dirname(php), 'ext')}`, '-d', 'extension=pdo_sqlite');
}
const app = spawn(process.execPath, [path.join(__dirname, 'php-server.js')], {
  stdio: ['ignore', 'ignore', 'inherit'],
  env: { ...process.env, PORT: String(appPort), PHP_DOC_ROOT: root, PHP_EXTRA_ARGS: JSON.stringify(extraArgs), PAYMENT_MOCK_URL: `http://127.0.0.1:${mockPort}` }
});
app.on('error', error => { console.error(error); process.exit(1); });
app.on('exit', code => process.exit(code || 0));
mock.listen(mockPort, '127.0.0.1');
// Wait for the PHP pool before advertising readiness to Playwright.
const ready = setInterval(async () => {
  try {
    const response = await fetch(`http://127.0.0.1:${appPort}/shop.php`);
    if (!response.ok) return;
    clearInterval(ready);
    server.listen(port, '127.0.0.1', () => console.log(`Isolated payment app: ${origin}`));
  } catch {}
}, 250);
function shutdown() { clearInterval(ready); server.close(); mock.close(); app.kill(); }
for (const signal of ['SIGINT', 'SIGTERM', 'SIGHUP']) process.on(signal, shutdown);
