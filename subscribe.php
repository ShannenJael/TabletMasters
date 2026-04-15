<?php
/**
 * Tablet Masters — Insurance Plan Subscription
 * Creates a Stripe Checkout Session for Basic or Protected plan subscriptions.
 */

require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /plans.php');
    exit;
}

$plan  = trim($_POST['plan']  ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

$allowedPlans = [
    'basic'     => STRIPE_PRICE_BASIC,
    'protected' => STRIPE_PRICE_PROTECTED,
];

if (!isset($allowedPlans[$plan])) {
    header('Location: /plans.php?error=invalid_plan');
    exit;
}

$priceId = $allowedPlans[$plan];

$payload = [
    'mode'       => 'subscription',
    'line_items' => [
        ['price' => $priceId, 'quantity' => 1],
    ],
    'success_url' => SITE_URL . '/success.php?session_id={CHECKOUT_SESSION_ID}&type=subscription&plan=' . urlencode($plan),
    'cancel_url'  => SITE_URL . '/plans.php?cancelled=1',
    'billing_address_collection' => 'required',
    'metadata' => [
        'checkout_type' => 'subscription',
        'plan' => $plan,
    ],
];

if ($email) {
    $payload['customer_email'] = $email;
}

$response = stripePost('checkout/sessions', $payload);

if (!$response || empty($response['url'])) {
    error_log('subscribe.php: Stripe session creation failed. Response: ' . json_encode($response));
    header('Location: /plans.php?error=stripe');
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
