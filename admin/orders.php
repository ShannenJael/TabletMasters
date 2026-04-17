<?php
require_once __DIR__ . '/bootstrap.php';

$auth = tmAdminHandleAuth('orders.php', true);
$loggedIn = $auth['loggedIn'];
$adminAuthenticated = $auth['isAuthenticated'];

$configFile = __DIR__ . '/../includes/config.php';
if (file_exists($configFile)) {
    require_once $configFile;
}

$stripeMode = 'missing';
if (defined('STRIPE_SECRET_KEY') && STRIPE_SECRET_KEY !== '') {
    if (strpos(STRIPE_SECRET_KEY, 'sk_live_') === 0) {
        $stripeMode = 'live';
    } elseif (strpos(STRIPE_SECRET_KEY, 'sk_test_') === 0) {
        $stripeMode = 'test';
    } else {
        $stripeMode = 'unknown';
    }
}

$logDir = __DIR__ . '/../data/logs';
$orderFile = $logDir . '/orders.json';
$orders = [];

if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true) ?: [];
}

if (!is_array($orders)) {
    $orders = [];
}

$stripeOrders = [];
if ($stripeMode === 'live') {
    $stripeOrders = tmFetchStripeOrders(25);
}

if (count($stripeOrders) > 0) {
    $mergedOrders = [];
    foreach (array_merge($orders, $stripeOrders) as $order) {
        $sessionId = (string)($order['session_id'] ?? '');
        if ($sessionId === '') {
            $mergedOrders[] = $order;
            continue;
        }
        $mergedOrders[$sessionId] = $order;
    }
    $orders = array_values($mergedOrders);
}

$normalizedOrders = [];
foreach ($orders as $order) {
    $sessionId = (string)($order['session_id'] ?? '');
    $paymentIntent = (string)($order['payment_intent'] ?? '');
    $email = (string)($order['email'] ?? '');
    $name = (string)($order['name'] ?? '');
    $currency = strtoupper((string)($order['currency'] ?? 'USD'));
    $amountCents = (int)($order['total'] ?? 0);
    $mode = (string)($order['mode'] ?? '');
    $paymentStatus = (string)($order['payment_status'] ?? 'completed');
    $checkoutType = (string)($order['checkout_type'] ?? '');
    $plan = (string)($order['plan'] ?? '');
    $items = is_array($order['items'] ?? null) ? $order['items'] : [];

    if ($checkoutType === '') {
        if ($mode === 'subscription' || $plan !== '') {
            $checkoutType = 'subscription';
        } else {
            $checkoutType = 'purchase';
        }
    }

    $timestamp = 0;
    if (!empty($order['stripe_created'])) {
        $timestamp = (int)$order['stripe_created'];
    }
    if ($timestamp <= 0 && !empty($order['created_at'])) {
        $parsed = strtotime((string)$order['created_at']);
        $timestamp = $parsed ?: 0;
    }

    $itemCount = 0;
    $itemSummary = [];
    foreach ($items as $item) {
        $quantity = max(1, (int)($item['quantity'] ?? 1));
        $itemCount += $quantity;
        $itemName = trim((string)($item['name'] ?? 'Item'));
        $itemSummary[] = $itemName . ($quantity > 1 ? ' x' . $quantity : '');
    }

    $normalizedOrders[] = [
        'session_id' => $sessionId,
        'payment_intent' => $paymentIntent,
        'email' => $email,
        'name' => $name,
        'currency' => $currency ?: 'USD',
        'total_cents' => $amountCents,
        'mode' => $mode,
        'payment_status' => $paymentStatus,
        'checkout_type' => $checkoutType,
        'plan' => $plan,
        'item_count' => $itemCount,
        'items' => $items,
        'item_summary' => implode(', ', $itemSummary),
        'sort_ts' => $timestamp,
        'created_label' => $timestamp > 0 ? date('M j, Y g:i A', $timestamp) : ((string)($order['created_at'] ?? 'Unknown')),
    ];
}

usort($normalizedOrders, function ($a, $b) {
    return ($b['sort_ts'] <=> $a['sort_ts']) ?: strcmp($b['session_id'], $a['session_id']);
});

$query = trim((string)($_GET['q'] ?? ''));
$typeFilter = trim((string)($_GET['type'] ?? 'all'));

$visibleOrders = array_values(array_filter($normalizedOrders, function ($order) use ($query, $typeFilter) {
    if ($typeFilter !== 'all' && $order['checkout_type'] !== $typeFilter) {
        return false;
    }

    if ($query === '') {
        return true;
    }

    $haystack = strtolower(implode(' ', [
        $order['session_id'],
        $order['payment_intent'],
        $order['email'],
        $order['name'],
        $order['item_summary'],
        $order['plan'],
    ]));

    return strpos($haystack, strtolower($query)) !== false;
}));

$paidOrders = array_values(array_filter($normalizedOrders, function ($order) {
    return $order['payment_status'] === 'paid' || $order['payment_status'] === 'completed' || $order['payment_status'] === '';
}));

$totalRevenueCents = array_sum(array_map(function ($order) {
    return (int)$order['total_cents'];
}, $paidOrders));

$visiblePaidOrders = array_values(array_filter($visibleOrders, function ($order) {
    return $order['payment_status'] === 'paid' || $order['payment_status'] === 'completed' || $order['payment_status'] === '';
}));

$visibleRevenueCents = array_sum(array_map(function ($order) {
    return (int)$order['total_cents'];
}, $visiblePaidOrders));

$purchaseCount = count(array_filter($normalizedOrders, function ($order) {
    return $order['checkout_type'] === 'purchase';
}));

$subscriptionCount = count(array_filter($normalizedOrders, function ($order) {
    return $order['checkout_type'] === 'subscription';
}));

$insuranceOrders = array_values(array_filter($normalizedOrders, function ($order) {
    return ($order['plan'] ?? '') !== '' && ($order['plan'] ?? '') !== 'none';
}));

$paidInsuranceOrders = array_values(array_filter($insuranceOrders, function ($order) {
    return $order['payment_status'] === 'paid' || $order['payment_status'] === 'completed' || $order['payment_status'] === '';
}));

$insuranceRevenueCents = array_sum(array_map(function ($order) {
    return (int)$order['total_cents'];
}, $paidInsuranceOrders));

$visibleInsuranceOrders = array_values(array_filter($visibleOrders, function ($order) {
    return ($order['plan'] ?? '') !== '' && ($order['plan'] ?? '') !== 'none';
}));

$visiblePaidInsuranceOrders = array_values(array_filter($visibleInsuranceOrders, function ($order) {
    return $order['payment_status'] === 'paid' || $order['payment_status'] === 'completed' || $order['payment_status'] === '';
}));

$visibleInsuranceRevenueCents = array_sum(array_map(function ($order) {
    return (int)$order['total_cents'];
}, $visiblePaidInsuranceOrders));

$averageOrderCents = count($paidOrders) > 0 ? (int)round($totalRevenueCents / count($paidOrders)) : 0;
$latestOrder = $normalizedOrders[0] ?? null;

$webhookFiles = glob($logDir . '/webhook_*.log') ?: [];
usort($webhookFiles, function ($a, $b) {
    return filemtime($b) <=> filemtime($a);
});
$latestWebhookFile = $webhookFiles[0] ?? null;

function moneyFormat(int $amountCents, string $currency = 'USD'): string
{
    $symbol = strtoupper($currency) === 'USD' ? '$' : strtoupper($currency) . ' ';
    return $symbol . number_format($amountCents / 100, 2);
}

function tmFetchStripeOrders(int $limit = 25): array
{
    $response = tmStripeGet('checkout/sessions?limit=' . max(1, min($limit, 100)));
    if (!is_array($response) || !isset($response['data']) || !is_array($response['data'])) {
        return [];
    }

    $orders = [];
    foreach ($response['data'] as $session) {
        $sessionId = (string)($session['id'] ?? '');
        $items = [];
        if ($sessionId !== '') {
            $itemsResponse = tmStripeGet('checkout/sessions/' . urlencode($sessionId) . '/line_items?limit=10');
            if (is_array($itemsResponse) && isset($itemsResponse['data']) && is_array($itemsResponse['data'])) {
                foreach ($itemsResponse['data'] as $item) {
                    $items[] = [
                        'name' => (string)($item['description'] ?? 'Item'),
                        'quantity' => (int)($item['quantity'] ?? 1),
                        'price' => (int)($item['amount_total'] ?? 0),
                    ];
                }
            }
        }

        $metadata = is_array($session['metadata'] ?? null) ? $session['metadata'] : [];
        $orders[] = [
            'session_id' => $sessionId,
            'payment_intent' => (string)($session['payment_intent'] ?? ''),
            'email' => (string)($session['customer_details']['email'] ?? ($session['customer_email'] ?? '')),
            'name' => (string)($session['customer_details']['name'] ?? ''),
            'total' => (int)($session['amount_total'] ?? 0),
            'currency' => (string)($session['currency'] ?? 'usd'),
            'payment_status' => (string)($session['payment_status'] ?? 'completed'),
            'mode' => (string)($session['mode'] ?? ''),
            'checkout_type' => (string)($metadata['checkout_type'] ?? ((($session['mode'] ?? '') === 'subscription') ? 'subscription' : 'purchase')),
            'plan' => (string)($metadata['plan'] ?? ($metadata['insurance_plan'] ?? '')),
            'items' => $items,
            'stripe_created' => (int)($session['created'] ?? 0),
            'created_at' => !empty($session['created']) ? date('Y-m-d H:i:s', (int)$session['created']) : '',
        ];
    }

    return $orders;
}

function tmStripeGet(string $endpoint): ?array
{
    if (!defined('STRIPE_SECRET_KEY') || STRIPE_SECRET_KEY === '') {
        return null;
    }

    $url = 'https://api.stripe.com/v1/' . ltrim($endpoint, '/');
    $ctx = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Bearer " . STRIPE_SECRET_KEY . "\r\n",
            'ignore_errors' => true,
        ],
    ]);

    $result = @file_get_contents($url, false, $ctx);
    if ($result === false) {
        return null;
    }

    $decoded = json_decode($result, true);
    return is_array($decoded) ? $decoded : null;
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Financials Admin - Tablet Masters</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #070a10;
      --surface: #0d1117;
      --card: #111827;
      --border: #1e2a3a;
      --blue: #3b82f6;
      --blue-dim: #1d4ed8;
      --green: #22c55e;
      --red: #ef4444;
      --yellow: #f59e0b;
      --text: #e2e8f0;
      --muted: #64748b;
      --display: 'Arial Black', sans-serif;
    }
    body {
      background: radial-gradient(circle at top, rgba(59, 130, 246, 0.18), transparent 32%), var(--bg);
      color: var(--text);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
    }
    a { color: inherit; text-decoration: none; }
    .login-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .login-card,
    .panel,
    .stat-card,
    .hero-card {
      background: rgba(17, 24, 39, 0.96);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.24);
    }
    .login-card {
      width: 100%;
      max-width: 380px;
      padding: 40px;
    }
    .login-card h1 {
      font-family: var(--display);
      font-size: 22px;
      letter-spacing: 2px;
      margin-bottom: 8px;
    }
    .login-card p {
      color: var(--muted);
      margin-bottom: 28px;
    }
    .login-card label,
    .field-label {
      display: block;
      margin-bottom: 8px;
      color: var(--muted);
      font-size: 11px;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .login-error,
    .notice {
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 18px;
      font-size: 14px;
    }
    .login-error {
      background: rgba(239, 68, 68, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.28);
      color: #fca5a5;
    }
    .notice {
      background: rgba(59, 130, 246, 0.12);
      border: 1px solid rgba(59, 130, 246, 0.28);
      color: #bfdbfe;
    }
    input[type="password"],
    input[type="search"],
    select {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: #0b1220;
      color: var(--text);
      padding: 12px 14px;
      outline: none;
    }
    input:focus,
    select:focus {
      border-color: var(--blue);
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border-radius: 999px;
      border: 1px solid var(--border);
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
    }
    .btn:hover { transform: translateY(-1px); }
    .btn-primary {
      background: linear-gradient(135deg, var(--blue), var(--blue-dim));
      border-color: transparent;
      color: #fff;
    }
    .btn-ghost {
      background: transparent;
      color: var(--text);
    }
    .admin-header {
      position: sticky;
      top: 0;
      z-index: 100;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      padding: 16px 28px;
      background: rgba(7, 10, 16, 0.9);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(30, 42, 58, 0.8);
    }
    .admin-brand {
      font-family: var(--display);
      letter-spacing: 2px;
      font-size: 15px;
    }
    .admin-brand span { color: var(--blue); }
    .admin-nav,
    .admin-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .nav-link {
      color: var(--muted);
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 13px;
      border: 1px solid transparent;
    }
    .nav-link.active,
    .nav-link:hover {
      color: #fff;
      border-color: rgba(59, 130, 246, 0.3);
      background: rgba(59, 130, 246, 0.14);
    }
    .admin-main {
      max-width: 1440px;
      margin: 0 auto;
      padding: 32px 24px 48px;
    }
    .hero-grid {
      display: grid;
      grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
      gap: 20px;
      margin-bottom: 24px;
    }
    .hero-card {
      padding: 28px;
    }
    .hero-eyebrow {
      color: #93c5fd;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 11px;
      margin-bottom: 12px;
    }
    .hero-title {
      font-family: var(--display);
      font-size: 28px;
      line-height: 1.15;
      margin-bottom: 12px;
    }
    .hero-copy,
    .meta-copy {
      color: #cbd5e1;
      line-height: 1.6;
    }
    .chip-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 18px;
    }
    .chip {
      border: 1px solid rgba(59, 130, 246, 0.18);
      background: rgba(59, 130, 246, 0.08);
      color: #dbeafe;
      border-radius: 999px;
      padding: 8px 12px;
      font-size: 12px;
    }
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }
    .stat-card {
      padding: 22px;
    }
    .stat-label {
      color: var(--muted);
      font-size: 11px;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }
    .stat-value {
      font-family: var(--display);
      font-size: 28px;
      margin-bottom: 8px;
    }
    .stat-copy {
      color: #cbd5e1;
      font-size: 13px;
      line-height: 1.5;
    }
    .filters {
      display: grid;
      grid-template-columns: minmax(0, 1.5fr) 220px auto;
      gap: 14px;
      align-items: end;
      margin-bottom: 18px;
    }
    .panel {
      overflow: hidden;
    }
    .panel-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      padding: 20px 22px;
      border-bottom: 1px solid var(--border);
      background: rgba(13, 17, 23, 0.9);
    }
    .panel-title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 4px;
    }
    .table-wrap {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead th {
      text-align: left;
      padding: 14px 18px;
      font-size: 11px;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--muted);
      border-bottom: 1px solid var(--border);
      background: rgba(13, 17, 23, 0.9);
      white-space: nowrap;
    }
    tbody td {
      padding: 16px 18px;
      border-bottom: 1px solid rgba(30, 42, 58, 0.85);
      vertical-align: top;
    }
    tbody tr:hover {
      background: rgba(59, 130, 246, 0.05);
    }
    .money {
      font-weight: 700;
      color: #bbf7d0;
    }
    .muted {
      color: var(--muted);
      font-size: 12px;
      margin-top: 4px;
      line-height: 1.5;
    }
    .badge {
      display: inline-flex;
      align-items: center;
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 12px;
      font-weight: 600;
      border: 1px solid transparent;
      text-transform: capitalize;
      white-space: nowrap;
    }
    .badge.purchase {
      background: rgba(59, 130, 246, 0.12);
      color: #bfdbfe;
      border-color: rgba(59, 130, 246, 0.24);
    }
    .badge.subscription {
      background: rgba(34, 197, 94, 0.12);
      color: #bbf7d0;
      border-color: rgba(34, 197, 94, 0.24);
    }
    .badge.status {
      background: rgba(245, 158, 11, 0.12);
      color: #fde68a;
      border-color: rgba(245, 158, 11, 0.24);
    }
    .empty-state {
      padding: 46px 24px;
      text-align: center;
      color: #cbd5e1;
      line-height: 1.6;
    }
    .empty-state strong {
      display: block;
      font-size: 18px;
      margin-bottom: 8px;
      color: #fff;
    }
    .footer-meta {
      margin-top: 16px;
      color: var(--muted);
      font-size: 12px;
    }
    @media (max-width: 1100px) {
      .hero-grid,
      .stats-row,
      .filters {
        grid-template-columns: 1fr;
      }
    }
    @media (max-width: 720px) {
      .admin-header {
        padding: 14px 16px;
      }
      .admin-main {
        padding: 20px 16px 40px;
      }
      .hero-card,
      .stat-card {
        padding: 20px;
      }
      thead th,
      tbody td {
        padding: 14px 12px;
      }
    }
  </style>
</head>
<body>

<header class="admin-header">
  <div class="admin-brand">TABLET <span>MASTERS</span> ADMIN</div>
  <nav class="admin-nav">
    <a class="nav-link" href="index.php">Management Console</a>
    <a class="nav-link" href="inventory.php">Inventory</a>
    <a class="nav-link active" href="orders.php">Financials</a>
  </nav>
  <div class="admin-actions">
    <a href="https://dashboard.stripe.com/payments" target="_blank" rel="noreferrer" class="btn btn-ghost"><i class="fas fa-arrow-up-right-from-square"></i> Stripe</a>
    <?php if ($adminAuthenticated): ?>
    <form method="POST" style="display:inline">
      <button type="submit" name="logout" class="btn btn-ghost">Sign Out</button>
    </form>
    <?php endif; ?>
  </div>
</header>

<main class="admin-main">
  <section class="hero-grid">
    <div class="hero-card">
      <div class="hero-eyebrow"><i class="fas fa-chart-line"></i> Revenue Desk</div>
      <div class="hero-title">Track incoming orders, totals, and transaction history in one place.</div>
      <p class="hero-copy">
        This dashboard reads the Stripe webhook order log saved on your server so you can quickly answer
        what sold, who ordered, and how much revenue has come in without jumping straight into code.
      </p>
      <div class="chip-row">
        <span class="chip"><?= count($normalizedOrders) ?> recorded transactions</span>
        <span class="chip"><?= moneyFormat($totalRevenueCents) ?> total revenue</span>
        <span class="chip"><?= $purchaseCount ?> purchases</span>
        <span class="chip"><?= $subscriptionCount ?> subscriptions</span>
        <span class="chip"><?= count($insuranceOrders) ?> insured orders</span>
        <span class="chip">Stripe <?= h(strtoupper($stripeMode)) ?></span>
      </div>
    </div>
    <aside class="hero-card">
      <div class="hero-eyebrow"><i class="fas fa-circle-info"></i> Data Source</div>
      <p class="meta-copy">Orders shown here come from `data/logs/orders.json`, which is written by `webhook.php` after Stripe sends `checkout.session.completed` events.</p>
      <div class="footer-meta">
        Latest order:
        <?= $latestOrder ? h($latestOrder['created_label']) : 'No orders recorded yet' ?><br />
        Latest webhook log:
        <?= $latestWebhookFile ? h(basename($latestWebhookFile)) : 'No webhook log files yet' ?>
      </div>
    </aside>
  </section>

  <section class="stats-row">
    <div class="stat-card">
      <div class="stat-label">Total Revenue</div>
      <div class="stat-value"><?= moneyFormat($totalRevenueCents) ?></div>
      <div class="stat-copy">Sum of all recorded paid or completed checkout sessions.</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Average Order</div>
      <div class="stat-value"><?= moneyFormat($averageOrderCents) ?></div>
      <div class="stat-copy">Average value across completed transactions in the log.</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Visible Revenue</div>
      <div class="stat-value"><?= moneyFormat($visibleRevenueCents) ?></div>
      <div class="stat-copy">Total for the orders currently visible after your filters.</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Transactions</div>
      <div class="stat-value"><?= count($visibleOrders) ?></div>
      <div class="stat-copy">Orders matching the current search and type filter.</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Insurance Orders</div>
      <div class="stat-value"><?= count($insuranceOrders) ?></div>
      <div class="stat-copy">Completed purchases or subscriptions that include a protection plan.</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Insurance Revenue</div>
      <div class="stat-value"><?= moneyFormat($insuranceRevenueCents) ?></div>
      <div class="stat-copy">Total paid revenue from orders that include a Basic or Protected insurance plan.</div>
    </div>
  </section>

  <?php if (!is_dir($logDir) || !file_exists($orderFile)): ?>
  <div class="notice">
    <i class="fas fa-info-circle"></i>
    <?php if ($stripeMode === 'test'): ?>
    This production server is still using Stripe test keys, so test transactions are hidden here. Switch `includes/config.php` to live Stripe keys and webhook secrets before taking real customer payments.
    <?php elseif ($stripeMode !== 'live'): ?>
    Stripe is not configured for live production orders yet. Add your live Stripe keys in `includes/config.php` so real customer transactions can appear here.
    <?php elseif (count($stripeOrders) > 0): ?>
    This server has no local `orders.json` ledger yet, so this page is currently showing live transactions pulled directly from Stripe.
    <?php else: ?>
    This server has no `orders.json` file yet. The table below is ready, and transactions will start appearing here once Stripe webhook completions are received by `webhook.php`.
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <form method="GET" class="filters">
    <div>
      <label class="field-label" for="q">Search Transactions</label>
      <input type="search" id="q" name="q" value="<?= h($query) ?>" placeholder="Search by email, name, session ID, payment intent, or item" />
    </div>
    <div>
      <label class="field-label" for="type">Transaction Type</label>
      <select id="type" name="type">
        <option value="all" <?= $typeFilter === 'all' ? 'selected' : '' ?>>All transactions</option>
        <option value="purchase" <?= $typeFilter === 'purchase' ? 'selected' : '' ?>>Purchases</option>
        <option value="subscription" <?= $typeFilter === 'subscription' ? 'selected' : '' ?>>Subscriptions</option>
      </select>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filters</button>
      <a href="orders.php" class="btn btn-ghost"><i class="fas fa-rotate-left"></i> Reset</a>
    </div>
  </form>

  <section class="panel">
    <div class="panel-head">
      <div>
        <div class="panel-title">Transactions</div>
        <div class="meta-copy">Recent Stripe-backed orders and subscriptions captured by the webhook log.</div>
      </div>
      <div class="chip-row" style="margin-top:0;">
        <span class="chip"><?= count($visibleOrders) ?> shown</span>
        <span class="chip"><?= count($visibleInsuranceOrders) ?> insured shown</span>
        <span class="chip"><?= moneyFormat($visibleInsuranceRevenueCents) ?> insured shown revenue</span>
      </div>
    </div>

    <?php if (count($visibleOrders) === 0): ?>
    <div class="empty-state">
      <strong>No transactions match the current view.</strong>
      Try resetting the search or wait for the next Stripe webhook event to populate the ledger.
    </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Items</th>
            <th>Reference</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($visibleOrders as $order): ?>
          <tr>
            <td>
              <strong><?= h($order['created_label']) ?></strong>
              <div class="muted"><?= h($order['currency']) ?></div>
            </td>
            <td>
              <strong><?= h($order['name'] !== '' ? $order['name'] : 'Unknown customer') ?></strong>
              <div class="muted"><?= h($order['email'] !== '' ? $order['email'] : 'No email captured') ?></div>
            </td>
            <td>
              <span class="badge <?= h($order['checkout_type']) ?>"><?= h($order['checkout_type']) ?></span>
              <div class="muted">
                <?php if ($order['plan'] !== ''): ?>
                Plan: <?= h($order['plan']) ?><br />
                <?php endif; ?>
                <span class="badge status"><?= h($order['payment_status'] !== '' ? $order['payment_status'] : 'completed') ?></span>
              </div>
            </td>
            <td>
              <div class="money"><?= moneyFormat((int)$order['total_cents'], $order['currency']) ?></div>
              <div class="muted"><?= (int)$order['item_count'] ?> item<?= (int)$order['item_count'] === 1 ? '' : 's' ?></div>
            </td>
            <td>
              <strong><?= h($order['item_summary'] !== '' ? $order['item_summary'] : 'Line items unavailable') ?></strong>
            </td>
            <td>
              <strong><?= h($order['session_id'] !== '' ? $order['session_id'] : 'Missing session ID') ?></strong>
              <div class="muted">PI: <?= h($order['payment_intent'] !== '' ? $order['payment_intent'] : 'n/a') ?></div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </section>
</main>
</body>
</html>
