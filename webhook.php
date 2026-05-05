<?php
/**
 * Tablet Masters — Stripe Webhook Handler
 * Verifies Stripe signatures and logs completed checkout events.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/notifications.php';

$payload   = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

$logDir  = __DIR__ . '/data/logs';
if (!is_dir($logDir)) mkdir($logDir, 0755, true);
$logFile = $logDir . '/webhook_' . date('Y-m-d') . '.log';

header('Content-Type: application/json');

// ── Signature verification ────────────────────────────────────────────────────

if (!verifyStripeSignature($payload, $sigHeader, STRIPE_WEBHOOK_SECRET)) {
    file_put_contents($logDir . '/webhook_errors.log',
        date('Y-m-d H:i:s') . " | Invalid signature\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

$event = json_decode($payload, true);
if (!$event) {
    file_put_contents($logDir . '/webhook_errors.log',
        date('Y-m-d H:i:s') . " | Invalid JSON payload\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$eventType = $event['type'] ?? 'unknown';
file_put_contents($logFile, date('H:i:s') . " | {$eventType}\n", FILE_APPEND);

http_response_code(200);

// ── Handle: checkout.session.completed ───────────────────────────────────────

if ($eventType === 'checkout.session.completed') {
    $session = $event['data']['object'] ?? [];

    $stripeSessionId = $session['id']                        ?? null;
    $paymentIntent   = $session['payment_intent']            ?? null;
    $customerEmail   = $session['customer_details']['email'] ?? null;
    $customerName    = $session['customer_details']['name']  ?? null;
    $customerPhone   = $session['customer_details']['phone'] ?? null;
    $totalAmount     = $session['amount_total']              ?? 0;
    $currency        = $session['currency']                  ?? 'usd';
    $paymentStatus   = $session['payment_status']            ?? 'completed';
    $mode            = $session['mode']                      ?? '';
    $metadata        = is_array($session['metadata'] ?? null) ? $session['metadata'] : [];
    $checkoutType    = $metadata['checkout_type']            ?? ($mode === 'subscription' ? 'subscription' : 'purchase');
    $plan            = $metadata['plan']                     ?? ($metadata['insurance_plan'] ?? '');
    $stripeCreated   = (int)($session['created']             ?? time());

    // Fetch line items via direct API call
    $lineItems = [];
    if ($stripeSessionId) {
        $itemsResponse = stripeGet('checkout/sessions/' . urlencode($stripeSessionId) . '/line_items?limit=20');
        if ($itemsResponse && isset($itemsResponse['data'])) {
            foreach ($itemsResponse['data'] as $item) {
                $lineItems[] = [
                    'name'     => $item['description'] ?? ($item['price']['product']['name'] ?? 'Item'),
                    'quantity' => $item['quantity'] ?? 1,
                    'price'    => $item['amount_total'] ?? 0,
                ];
            }
        }
    }

    // Log order summary
    $summary = sprintf(
        "%s | ✅ session=%s pi=%s email=%s total=$%s items=%d\n",
        date('H:i:s'),
        $stripeSessionId,
        $paymentIntent,
        $customerEmail,
        number_format($totalAmount / 100, 2),
        count($lineItems)
    );
    file_put_contents($logFile, $summary, FILE_APPEND);
    $notificationResults = tmNotifyOrderCompleted($session, $lineItems);

    // Persist a lightweight order record as JSON
    $orderFile = $logDir . '/orders.json';
    $orders    = [];
    if (file_exists($orderFile)) {
        $orders = json_decode(file_get_contents($orderFile), true) ?: [];
    }
    // Deduplicate by session id
    $exists = false;
    foreach ($orders as $o) {
        if (($o['session_id'] ?? '') === $stripeSessionId) { $exists = true; break; }
    }
    if (!$exists) {
        $orders[] = [
            'session_id'    => $stripeSessionId,
            'payment_intent'=> $paymentIntent,
            'email'         => $customerEmail,
            'name'          => $customerName,
            'phone'         => $customerPhone,
            'total'         => $totalAmount,
            'currency'      => $currency,
            'payment_status'=> $paymentStatus,
            'mode'          => $mode,
            'checkout_type' => $checkoutType,
            'plan'          => $plan,
            'items'         => $lineItems,
            'notifications' => $notificationResults,
            'stripe_created'=> $stripeCreated,
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        file_put_contents($orderFile, json_encode($orders, JSON_PRETTY_PRINT));
    }
}

echo json_encode(['received' => true]);

// ── Helpers ───────────────────────────────────────────────────────────────────

function verifyStripeSignature(string $payload, string $sigHeader, string $secret): bool {
    if (!$sigHeader || !$secret) return false;

    $parts = [];
    foreach (explode(',', $sigHeader) as $part) {
        $kv = explode('=', $part, 2);
        if (count($kv) === 2) {
            $parts[$kv[0]][] = $kv[1];
        }
    }

    $timestamp  = $parts['t'][0]  ?? '';
    $signatures = $parts['v1']    ?? [];

    if (!$timestamp || empty($signatures)) return false;

    $signedPayload = "{$timestamp}.{$payload}";
    $expected      = hash_hmac('sha256', $signedPayload, $secret);

    foreach ($signatures as $sig) {
        if (hash_equals($expected, $sig)) return true;
    }
    return false;
}

function stripeGet(string $endpoint): ?array {
    $url    = 'https://api.stripe.com/v1/' . $endpoint;
    $secret = STRIPE_SECRET_KEY;

    $ctx = stream_context_create([
        'http' => [
            'method'        => 'GET',
            'header'        => "Authorization: Bearer {$secret}\r\n",
            'ignore_errors' => true,
        ],
    ]);

    $result = file_get_contents($url, false, $ctx);
    if ($result === false) return null;

    return json_decode($result, true);
}
