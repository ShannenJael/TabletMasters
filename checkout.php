<?php
/**
 * Tablet Masters — Cart Checkout
 * Creates a Stripe Checkout Session for tablet purchases and redirects the customer.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/accessories-data.php';
require_once __DIR__ . '/includes/stripe-client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /shop.php');
    exit;
}

$cartRaw = $_POST['cart'] ?? '';
$cart = is_string($cartRaw) ? json_decode($cartRaw, true) : null;
$insurancePlan = $_POST['insurance_plan'] ?? 'none';
$smsOptIn = ($_POST['sms_opt_in'] ?? '0') === '1' ? '1' : '0';

if ($cartRaw === '' || $cart === []) {
    header('Location: /shop.php?error=empty_cart');
    exit;
}
if (!is_array($cart) || !array_is_list($cart) || count($cart) > 100) {
    header('Location: /shop.php?error=invalid_cart');
    exit;
}

$insurancePriceMap = [
    'basic' => defined('STRIPE_PRICE_BASIC') ? STRIPE_PRICE_BASIC : '',
    'protected' => defined('STRIPE_PRICE_PROTECTED') ? STRIPE_PRICE_PROTECTED : '',
];

if (!is_string($insurancePlan) || !in_array($insurancePlan, ['none', 'basic', 'protected'], true)) {
    header('Location: /shop.php?error=invalid_plan');
    exit;
}
if ($insurancePlan !== 'none' && empty($insurancePriceMap[$insurancePlan])) {
    header('Location: /shop.php?error=stripe');
    exit;
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

// Refuse to sell more units than are in stock. Quantities are totalled per
// product so splitting one product across several cart rows can't bypass it.
// Accessories have no stock field and are not limited.
$requestedQty = [];
foreach ($cart as $item) {
    $idValue = is_array($item) ? ($item['id'] ?? null) : null;
    $qtyValue = is_array($item) ? ($item['qty'] ?? null) : null;
    $id = is_int($idValue) || is_string($idValue) ? (string)$idValue : '';
    $qty = (is_int($qtyValue) || is_string($qtyValue))
        ? filter_var($qtyValue, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 999]]) : false;
    if ($id === '' || $qty === false || !isset($productMap[$id])
        || (float)$productMap[$id]['price'] <= 0 || !empty($productMap[$id]['placeholder'])) {
        header('Location: /shop.php?error=invalid_cart');
        exit;
    }
    $requestedQty[$id] = ($requestedQty[$id] ?? 0) + $qty;
}
if (count($requestedQty) > ($insurancePlan === 'none' ? 100 : 20)) {
    header('Location: /shop.php?error=invalid_cart');
    exit;
}
foreach ($requestedQty as $id => $qty) {
    if ($qty > 999) {
        header('Location: /shop.php?error=invalid_cart');
        exit;
    }
    if (array_key_exists('stock', $productMap[$id]) && $qty > (int)$productMap[$id]['stock']) {
        header('Location: /shop.php?error=insufficient_stock');
        exit;
    }
}

// Build Stripe line items
$lineItems = [];
foreach ($requestedQty as $id => $qty) {
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

$response = tmStripeRequest('POST', 'checkout/sessions', $payload);

if (!$response || !is_string($response['url'] ?? null) || !filter_var($response['url'], FILTER_VALIDATE_URL)) {
    error_log('checkout.php: Stripe session creation failed.');
    header('Location: /shop.php?error=stripe');
    exit;
}

header('Location: ' . $response['url']);
exit;
