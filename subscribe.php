<?php
/**
 * Tablet Masters — Insurance Plan Subscription
 * Creates a Stripe Checkout Session for Basic or Protected plan subscriptions.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/stripe-client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /plans.php');
    exit;
}

$plan = is_string($_POST['plan'] ?? null) ? trim($_POST['plan']) : '';
$emailInput = $_POST['email'] ?? '';
$email = is_string($emailInput) ? filter_var(trim($emailInput), FILTER_VALIDATE_EMAIL) : false;

$allowedPlans = [
    'basic'     => defined('STRIPE_PRICE_BASIC') ? STRIPE_PRICE_BASIC : '',
    'protected' => defined('STRIPE_PRICE_PROTECTED') ? STRIPE_PRICE_PROTECTED : '',
];

if (!isset($allowedPlans[$plan])) {
    header('Location: /plans.php?error=invalid_plan');
    exit;
}
if (!is_string($emailInput) || (trim($emailInput) !== '' && !$email)) {
    header('Location: /plans.php?error=invalid_email');
    exit;
}

$priceId = $allowedPlans[$plan];
if ($priceId === '') {
    header('Location: /plans.php?error=stripe');
    exit;
}

$payload = [
    'mode'       => 'subscription',
    'line_items' => [
        ['price' => $priceId, 'quantity' => 1],
    ],
    'success_url' => SITE_URL . '/success.php?session_id={CHECKOUT_SESSION_ID}&type=subscription&plan=' . urlencode($plan),
    'cancel_url'  => SITE_URL . '/plans.php?cancelled=1',
    'billing_address_collection' => 'required',
    'phone_number_collection' => [
        'enabled' => 'true',
    ],
    'metadata' => [
        'checkout_type' => 'subscription',
        'plan' => $plan,
        'sms_opt_in' => ($_POST['sms_opt_in'] ?? '0') === '1' ? '1' : '0',
    ],
];

if ($email) {
    $payload['customer_email'] = $email;
}

$registrationNumber = $_POST['registration_number'] ?? '';
if ($registrationNumber !== '') {
    if (!is_string($registrationNumber) || !preg_match('/^REG-[A-F0-9]{8}$/', $registrationNumber) || !$email) {
        header('Location: /plans.php?error=invalid_registration');
        exit;
    }
    require_once __DIR__ . '/includes/database.php';
    try {
        $statement = getDB()->prepare('SELECT customer_email, plan, plan_status, purchase_source FROM devices WHERE registration_number = ?');
        $statement->execute([$registrationNumber]);
        $registration = $statement->fetch();
        if (!$registration || strcasecmp($registration['customer_email'], $email) !== 0
            || $registration['plan'] !== $plan || $registration['plan_status'] !== 'pending_payment'
            || $registration['purchase_source'] !== 'external') {
            header('Location: /plans.php?error=invalid_registration');
            exit;
        }
        $payload['metadata']['registration_number'] = $registrationNumber;
        $payload['metadata']['registration_email'] = $registration['customer_email'];
    } catch (Throwable $error) {
        error_log('subscribe.php: Unable to verify device registration.');
        header('Location: /plans.php?error=stripe');
        exit;
    }
}

$response = tmStripeRequest('POST', 'checkout/sessions', $payload);

if (!$response || !is_string($response['url'] ?? null) || !filter_var($response['url'], FILTER_VALIDATE_URL)) {
    error_log('subscribe.php: Stripe session creation failed.');
    header('Location: /plans.php?error=stripe');
    exit;
}

header('Location: ' . $response['url']);
exit;
