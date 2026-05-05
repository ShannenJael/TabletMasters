<?php
/**
 * Tablet Masters — Cart Checkout
 * Creates a Stripe Checkout Session for tablet purchases and redirects the customer.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/accessories-data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /shop.php');
    exit;
}

$cartRaw = $_POST['cart'] ?? '';
$cart    = json_decode($cartRaw, true);
$insurancePlan = trim((string)($_POST['insurance_plan'] ?? 'none'));
$smsOptIn = ($_POST['sms_opt_in'] ?? '0') === '1' ? '1' : '0';

if (!is_array($cart) || count($cart) === 0) {
    header('Location: /shop.php?error=empty_cart');
    exit;
}

$insurancePriceMap = [
    'basic' => defined('STRIPE_PRICE_BASIC') ? STRIPE_PRICE_BASIC : '',
    'protected' => defined('STRIPE_PRICE_PROTECTED') ? STRIPE_PRICE_PROTECTED : '',
];

if (!isset($insurancePriceMap[$insurancePlan]) && $insurancePlan !== 'none') {
    $insurancePlan = 'none';
}

// Load inventory to get product names and prices
$inventoryFile = __DIR__ . '/data/inventory.json';
$inventory     = file_exists($inventoryFile)
    ? (json_decode(file_get_contents($inventoryFile), true) ?: [])
    : [];

$productMap = [];
foreach ($inventory as $product) {
    $productMap[(string)$product['id']] = $product;
}

foreach (tm_build_accessory_products() as $product) {
    $productMap[(string)$product['id']] = $product;
}

// Build Stripe line items
$lineItems = [];
foreach ($cart as $item) {
    $id  = (string)($item['id']  ?? '');
    $qty = (int)($item['qty'] ?? 1);

    if ($id === '' || $qty < 1 || !isset($productMap[$id])) continue;

    $product = $productMap[$id];
    $priceInCents = (int)round($product['price'] * 100);
    if ($priceInCents <= 0) continue;

    $lineItems[] = [
        'price_data' => [
            'currency'     => 'usd',
            'unit_amount'  => $priceInCents,
            'product_data' => [
                'name' => $product['name'],
            ],
        ],
        'quantity' => $qty,
    ];
}

if (count($lineItems) === 0) {
    header('Location: /shop.php?error=invalid_cart');
    exit;
}

// Build Stripe API payload
$mode = 'payment';
$payload = [
    'mode'                => $mode,
    'line_items'          => $lineItems,
    'success_url'         => SITE_URL . '/success.php?session_id={CHECKOUT_SESSION_ID}&type=purchase',
    'cancel_url'          => SITE_URL . '/shop.php?cancelled=1',
    'billing_address_collection' => 'required',
    'phone_number_collection' => [
        'enabled' => 'true',
    ],
    'metadata' => [
        'checkout_type' => 'purchase',
        'insurance_plan' => $insurancePlan,
        'sms_opt_in'    => $smsOptIn,
    ],
];

if ($insurancePlan !== 'none' && !empty($insurancePriceMap[$insurancePlan])) {
    $payload['mode'] = 'subscription';
    $payload['line_items'][] = [
        'price' => $insurancePriceMap[$insurancePlan],
        'quantity' => 1,
    ];
    $payload['metadata']['plan'] = $insurancePlan;
}

$response = stripePost('checkout/sessions', $payload);

if (!$response || empty($response['url'])) {
    error_log('checkout.php: Stripe session creation failed. Response: ' . json_encode($response));
    header('Location: /shop.php?error=stripe');
    exit;
}

header('Location: ' . $response['url']);
exit;

// ── Stripe API helper ─────────────────────────────────────────────────────────

function stripePost(string $endpoint, array $data): ?array {
    $url     = 'https://api.stripe.com/v1/' . $endpoint;
    $secret  = STRIPE_SECRET_KEY;
    $payload = http_build_query(flattenForStripe($data));

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  =>
                "Authorization: Bearer {$secret}\r\n" .
                "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($payload) . "\r\n",
            'content'         => $payload,
            'ignore_errors'   => true,
        ],
    ]);

    $result = file_get_contents($url, false, $ctx);
    if ($result === false) return null;

    return json_decode($result, true);
}

function flattenForStripe(array $data, string $prefix = ''): array {
    $flat = [];
    foreach ($data as $key => $value) {
        $fullKey = $prefix ? "{$prefix}[{$key}]" : $key;
        if (is_array($value)) {
            $flat = array_merge($flat, flattenForStripe($value, $fullKey));
        } else {
            $flat[$fullKey] = $value;
        }
    }
    return $flat;
}
