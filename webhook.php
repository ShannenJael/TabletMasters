<?php
/**
 * Tablet Masters — Stripe Webhook Handler
 * Verifies Stripe signatures and logs completed checkout events.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/notifications.php';
require_once __DIR__ . '/includes/stripe-security.php';
require_once __DIR__ . '/includes/stripe-client.php';

header('Content-Type: application/json');
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$payload   = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

$logDir  = __DIR__ . '/data/logs';
if (!is_dir($logDir) && !@mkdir($logDir, 0755, true) && !is_dir($logDir)) {
    http_response_code(500);
    echo json_encode(['error' => 'Order storage unavailable']);
    exit;
}
$logFile = $logDir . '/webhook_' . date('Y-m-d') . '.log';

// ── Signature verification ────────────────────────────────────────────────────

if (!verifyStripeSignature($payload, $sigHeader, STRIPE_WEBHOOK_SECRET)) {
    file_put_contents($logDir . '/webhook_errors.log',
        date('Y-m-d H:i:s') . " | Invalid signature\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

$event = json_decode($payload, true);
if (!is_array($event) || !is_string($event['type'] ?? null)) {
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

if (in_array($eventType, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
    $session = $event['data']['object'] ?? [];
    if (!is_array($session)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid session']);
        exit;
    }

    $stripeSessionId = $session['id']                        ?? null;
    $paymentIntent   = $session['payment_intent']            ?? null;
    $customerEmail   = $session['customer_details']['email'] ?? null;
    $customerName    = $session['customer_details']['name']  ?? null;
    $customerPhone   = $session['customer_details']['phone'] ?? null;
    $totalAmount     = $session['amount_total']              ?? 0;
    $currency        = $session['currency']                  ?? 'usd';
    $paymentStatus   = $session['payment_status']            ?? 'unpaid';
    $mode            = $session['mode']                      ?? '';
    $metadata        = is_array($session['metadata'] ?? null) ? $session['metadata'] : [];
    $checkoutType    = $metadata['checkout_type']            ?? ($mode === 'subscription' ? 'subscription' : 'purchase');
    $plan            = $metadata['plan']                     ?? ($metadata['insurance_plan'] ?? '');
    $stripeCreated   = (int)($session['created']             ?? time());

    if (!validStripeSessionId($stripeSessionId)) {
        file_put_contents($logDir . '/webhook_errors.log',
            date('Y-m-d H:i:s') . " | Invalid checkout session ID\n", FILE_APPEND);
        http_response_code(400);
        echo json_encode(['error' => 'Invalid session_id']);
        exit;
    }

    // Checkout can finish while a bank payment is still processing. Only the
    // paid completion (or its later async success event) may fulfill the order.
    if (!stripeCheckoutIsPaid($session)) {
        echo json_encode(['received' => true]);
        exit;
    }

    // Keep the entire read/check/write transaction under one lock, including
    // notifications, so simultaneous retries cannot fulfill an order twice.
    $orderFile = $logDir . '/orders.json';
    $orderHandle = @fopen($orderFile, 'c+');
    if (!$orderHandle || !flock($orderHandle, LOCK_EX)) {
        http_response_code(500);
        echo json_encode(['error' => 'Order storage unavailable']);
        exit;
    }
    $stored = stream_get_contents($orderHandle);
    $orders = $stored === '' ? [] : json_decode($stored, true);
    if (!is_array($orders) || !array_is_list($orders)) {
        http_response_code(500);
        echo json_encode(['error' => 'Order storage invalid']);
        exit;
    }
    foreach ($orders as $existing) {
        if (($existing['session_id'] ?? '') === $stripeSessionId
            && in_array($existing['payment_status'] ?? '', ['paid', 'no_payment_required'], true)) {
            echo json_encode(['received' => true]);
            exit;
        }
    }

    // Fetch line items via direct API call
    $lineItems = [];
    $cursor = '';
    do {
        $endpoint = 'checkout/sessions/' . rawurlencode($stripeSessionId) . '/line_items?limit=100';
        if ($cursor !== '') $endpoint .= '&starting_after=' . rawurlencode($cursor);
        $itemsResponse = tmStripeRequest('GET', $endpoint);
        if (!$itemsResponse || !is_array($itemsResponse['data'] ?? null)) {
            http_response_code(502);
            echo json_encode(['error' => 'Unable to retrieve order items']);
            exit;
        }
        foreach ($itemsResponse['data'] as $item) {
            $lineItems[] = [
                'name'     => $item['description'] ?? ($item['price']['product']['name'] ?? 'Item'),
                'quantity' => $item['quantity'] ?? 1,
                'price'    => $item['amount_total'] ?? 0,
            ];
        }
        $hasMore = !empty($itemsResponse['has_more']);
        $lastItem = end($itemsResponse['data']);
        $nextCursor = is_array($lastItem) ? ($lastItem['id'] ?? '') : '';
        if ($hasMore && (!is_string($nextCursor) || $nextCursor === '' || $nextCursor === $cursor)) {
            http_response_code(502);
            echo json_encode(['error' => 'Invalid order item pagination']);
            exit;
        }
        $cursor = $nextCursor;
    } while ($hasMore);

    // Activation belongs to the signed paid webhook, not the browser redirect.
    // Keep failures retryable so a transient database error cannot lose coverage.
    if ($checkoutType === 'subscription' && isset($metadata['registration_number'])) {
        require_once __DIR__ . '/includes/database.php';
        try {
            $activate = getDB()->prepare("UPDATE devices SET plan_status = 'active', order_id = ?, updated_at = CURRENT_TIMESTAMP
                WHERE registration_number = ? AND customer_email = ? AND plan = ? AND purchase_source = 'external'
                  AND (plan_status = 'pending_payment' OR (plan_status = 'active' AND order_id = ?))");
            $activate->execute([$stripeSessionId, $metadata['registration_number'], $metadata['registration_email'] ?? '', $plan, $stripeSessionId]);
            if ($activate->rowCount() !== 1) throw new RuntimeException('Registration did not match');
        } catch (Throwable $error) {
            error_log('webhook.php: Unable to activate registered device.');
            http_response_code(500);
            echo json_encode(['error' => 'Unable to activate registered device']);
            exit;
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
    // Replace any legacy unpaid record; unpaid checkouts are no longer stored.
    $orders = array_values(array_filter($orders, static function ($order) use ($stripeSessionId) {
        return ($order['session_id'] ?? '') !== $stripeSessionId;
    }));
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
    $encoded = json_encode($orders, JSON_PRETTY_PRINT);
    rewind($orderHandle);
    if ($encoded === false || !ftruncate($orderHandle, 0) || fwrite($orderHandle, $encoded) !== strlen($encoded) || !fflush($orderHandle)) {
        http_response_code(500);
        echo json_encode(['error' => 'Unable to save order']);
        exit;
    }
    flock($orderHandle, LOCK_UN);
    fclose($orderHandle);
}

echo json_encode(['received' => true]);

// ── Helpers ───────────────────────────────────────────────────────────────────

function verifyStripeSignature(string $payload, string $sigHeader, string $secret): bool {
    if (!$sigHeader || !$secret) return false;

    $parts = [];
    foreach (explode(',', $sigHeader) as $part) {
        $kv = explode('=', $part, 2);
        if (count($kv) === 2) {
            $parts[trim($kv[0])][] = trim($kv[1]);
        }
    }

    $timestamp  = $parts['t'][0]  ?? '';
    $signatures = $parts['v1']    ?? [];

    if (!$timestamp || empty($signatures)) return false;

    // Reject replays of old signed events (Stripe's recommended 5-minute window).
    if (!ctype_digit($timestamp) || abs(time() - (int)$timestamp) > 300) return false;

    $signedPayload = "{$timestamp}.{$payload}";
    $expected      = hash_hmac('sha256', $signedPayload, $secret);

    foreach ($signatures as $sig) {
        if (hash_equals($expected, $sig)) return true;
    }
    return false;
}
