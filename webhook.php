<?php
/**
 * Tablet Masters — Stripe Webhook Handler v2
 * Captures payments, creates orders, sends emails, logs activity
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/emails.php';

$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

// Log all webhooks
$logDir = __DIR__ . '/data/logs';
if (!is_dir($logDir)) mkdir($logDir, 0755, true);
$logFile = $logDir . '/webhook_' . date('Y-m-d') . '.log';
header('Content-Type: application/json');

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sigHeader,
        STRIPE_WEBHOOK_SECRET
    );
} catch (\UnexpectedValueException $e) {
    file_put_contents($logDir . '/webhook_errors.log',
        date('Y-m-d H:i:s') . " | Invalid payload: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    file_put_contents($logDir . '/webhook_errors.log',
        date('Y-m-d H:i:s') . " | Invalid signature: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

file_put_contents($logFile, date('H:i:s') . " | " . ($event->type ?? 'unknown') . "\n", FILE_APPEND);

http_response_code(200);

$eventType = $event->type ?? '';
$eventData = isset($event->data) ? $event->data : null;

// ── Handle: checkout.session.completed ──
if ($eventType === 'checkout.session.completed' && $eventData && isset($eventData->object)) {
    $session = $eventData->object;

    $stripeSessionId = $session->id ?? null;
    $paymentIntent   = $session->payment_intent ?? null;
    $customerEmail   = $session->customer_details->email ?? null;
    $customerName    = $session->customer_details->name ?? null;
    $customerPhone   = $session->customer_details->phone ?? null;
    $totalAmount     = $session->amount_total ?? 0;
    $currency        = $session->currency ?? 'usd';

    // Shipping address
    $shipping = $session->shipping_details ?? ($session->customer_details ?? null);
    $shippingAddr = '';
    if ($shipping && isset($shipping->address)) {
        $a = $shipping->address;
        $shippingAddr = json_encode([
            'name'    => $shipping->name ?? $customerName,
            'line1'   => $a->line1 ?? '',
            'line2'   => $a->line2 ?? '',
            'city'    => $a->city ?? '',
            'state'   => $a->state ?? '',
            'zip'     => $a->postal_code ?? '',
            'country' => $a->country ?? '',
        ]);
    }

    // Fetch line items from Stripe
    $lineItems = [];
    try {
        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
        $items  = $stripe->checkout->sessions->allLineItems($stripeSessionId, ['limit' => 20]);
        foreach ($items->data as $item) {
            $lineItems[] = [
                'name'     => $item->description,
                'quantity' => $item->quantity,
                'price'    => $item->amount_total,
            ];
        }
    } catch (\Exception $e) {
        file_put_contents($logDir . '/webhook_errors.log',
            date('Y-m-d H:i:s') . " | Line items fetch: " . $e->getMessage() . "\n", FILE_APPEND);
    }

    // Save to database
    try {
        $db = getDB();
        $orderNumber = generateOrderNumber($db);

        $stmt = $db->prepare("
            INSERT OR IGNORE INTO orders
            (order_number, stripe_session_id, stripe_payment_intent, customer_email, customer_name,
             customer_phone, shipping_address, items, subtotal, total, currency, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'paid')
        ");
        $stmt->execute([
            $orderNumber, $stripeSessionId, $paymentIntent,
            $customerEmail, $customerName, $customerPhone,
            $shippingAddr, json_encode($lineItems),
            $totalAmount, $totalAmount, $currency,
        ]);

        $orderId = $db->lastInsertId();

        if ($orderId > 0) {
            // Log activity
            logActivity($db, $orderId, 'order_created', "Payment received via Stripe ({$paymentIntent})");

            // Update/create customer record
            upsertCustomer($db, $customerEmail, $customerName, $totalAmount);

            // Fetch order for email
            $order = $db->prepare("SELECT * FROM orders WHERE id = ?");
            $order->execute([$orderId]);
            $order = $order->fetch();

            // Send customer confirmation email
            sendOrderConfirmation($order, $lineItems);
            logActivity($db, $orderId, 'email_sent', 'Order confirmation sent to ' . $customerEmail);

            // Send admin notification
            sendAdminNotification($order, $lineItems);

            file_put_contents($logFile,
                date('H:i:s') . " | ✅ Order {$orderNumber} | {$customerEmail} | \$" . number_format($totalAmount / 100, 2) . "\n", FILE_APPEND);
        }

    } catch (\Exception $e) {
        file_put_contents($logDir . '/webhook_errors.log',
            date('Y-m-d H:i:s') . " | DB Error: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

echo json_encode(['received' => true]);
