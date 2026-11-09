<?php
/**
 * Tablet Masters — Order Success Page
 */
$currentPage = 'shop';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/accessories-data.php';
require_once __DIR__ . '/includes/stripe-security.php';
require_once __DIR__ . '/includes/stripe-client.php';

header('Cache-Control: private, no-store');

$sessionId  = $_GET['session_id'] ?? null;
$type       = $_GET['type'] ?? '';
$subscribed = ($type === 'subscription');
$order      = null;
$plan = '';
$insurancePlan = 'none';
$registrationNumber = '';
$recommendedAccessories = [];

if ($sessionId !== null && !validStripeSessionId($sessionId)) {
    http_response_code(400);
    exit('Invalid session_id');
}

// URL parameters are only hints for the pending screen. Confirmation, order
// type and plan must all come from the matching, completed and paid session.
$subscriptionVerified = false;
$purchaseVerified = false;

if ($sessionId) {
    $session = tmStripeRequest('GET', 'checkout/sessions/' . rawurlencode($sessionId) . '?expand[]=line_items');
    if ($session
        && ($session['id'] ?? '') === $sessionId
        && stripeCheckoutIsPaid($session)
    ) {
        $metadata = is_array($session['metadata'] ?? null) ? $session['metadata'] : [];
        $checkoutType = $metadata['checkout_type'] ?? ($session['mode'] === 'subscription' ? 'subscription' : 'purchase');
        $subscribed = $checkoutType === 'subscription';
        $plan = is_string($metadata['plan'] ?? null) ? $metadata['plan'] : '';
        $subscriptionVerified = $subscribed && $session['mode'] === 'subscription' && in_array($plan, ['basic', 'protected'], true);
        $purchaseVerified = $checkoutType === 'purchase';
        if ($purchaseVerified) $order = $session;
        $insurancePlan = $metadata['insurance_plan'] ?? ($metadata['plan'] ?? 'none');
        $registrationNumber = is_string($metadata['registration_number'] ?? null) ? $metadata['registration_number'] : '';

        $tabletNames = [];
        foreach (($session['line_items']['data'] ?? []) as $lineItem) {
            $description = trim((string)($lineItem['description'] ?? ''));
            if ($description !== '' && stripos($description, 'Case Placeholder') === false && stripos($description, 'Screen Cover Placeholder') === false) {
                $tabletNames[] = $description;
            }
        }

        if ($purchaseVerified && $tabletNames) {
            $recommendedAccessories = tm_find_accessories_for_tablets($tabletNames);
        }
    }
}

$planLabels = [
    'basic'     => 'Basic Protection — $8/mo',
    'protected' => 'Protected Plan — $12/mo',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $subscribed ? ($subscriptionVerified ? 'Protection Active' : 'Confirming Your Plan') : ($purchaseVerified ? 'Order Confirmed' : 'Confirming Your Order') ?> — Tablet Masters</title>
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/assets/css/style.css'); ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#3B82F6" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<div class="success-section">
  <div class="success-card">

    <?php if ($subscribed && !$subscriptionVerified): ?>
      <div class="success-icon">⏳</div>
      <h1 class="success-title">Confirming Your Plan</h1>
      <p class="success-desc">
        We couldn't confirm a completed plan signup from this link. If you just finished checkout,
        check your payment receipt or contact support before trying again.
      </p>
      <div class="success-actions">
        <a class="btn-primary" href="plans.php">View Plans</a>
        <a class="btn-outline" href="support.php">Contact Support</a>
      </div>

    <?php elseif ($subscribed): ?>
      <div class="success-icon">🛡️</div>
      <h1 class="success-title">Protection Active!</h1>
      <p class="success-desc">
        Your <strong><?= htmlspecialchars($planLabels[$plan] ?? 'protection plan') ?></strong> is now active.
        Keep your payment receipt and plan details for your records.
      </p>
      <p class="success-note">
        <?php if ($registrationNumber !== ''): ?>
        Your tablet registration <strong><?= htmlspecialchars($registrationNumber) ?></strong> is linked to this plan.
        <?php else: ?>
        Please go to the <a href="register.php">registration page</a> to register your device.
        <?php endif; ?>
      </p>
      <div class="success-actions">
        <a class="btn-primary" href="shop.php">Continue Shopping</a>
        <a class="btn-outline" href="insurance.php">View Coverage</a>
      </div>

    <?php elseif (!$purchaseVerified): ?>
      <div class="success-icon">⏳</div>
      <h1 class="success-title">Confirming Your Order</h1>
      <p class="success-desc">
        We couldn't confirm a completed payment from this link. Your cart is saved.
        If you just paid, check your confirmation email or contact support before trying again.
      </p>
      <div class="success-actions">
        <a class="btn-primary" href="shop.php">Return to Shop</a>
        <a class="btn-outline" href="support.php">Contact Support</a>
      </div>

    <?php else: ?>
      <div class="success-icon">✅</div>
      <h1 class="success-title">Order Confirmed!</h1>

        <p class="success-desc">
          Thank you for your purchase! Your order has been received and is being processed.
        </p>
        <div class="success-details">
          <div class="success-detail-row">
            <span>Order Total</span>
            <span><strong>$<?= number_format(($order['amount_total'] ?? 0) / 100, 2) ?></strong></span>
          </div>
          <div class="success-detail-row">
            <span>Email</span>
            <span><?= htmlspecialchars($order['customer_details']['email'] ?? 'N/A') ?></span>
          </div>
        </div>
        <?php
        $insurancePlan = $order['metadata']['insurance_plan'] ?? ($order['metadata']['plan'] ?? $insurancePlan);
        if (is_string($insurancePlan) && in_array($insurancePlan, ['basic', 'protected'], true)):
        ?>
        <p class="success-note" style="color:var(--blue)">
          <i class="fas fa-shield-alt"></i> Your <?= htmlspecialchars($planLabels[$insurancePlan] ?? 'protection plan') ?> is included with this checkout and will continue monthly through Stripe.
        </p>
        <?php else: ?>
        <p class="success-note">
          Keep your payment receipt for your records. Contact support if you need help with shipping.
        </p>
        <?php endif; ?>

      <div class="success-actions">
        <a class="btn-primary" href="shop.php">Continue Shopping</a>
        <a class="btn-outline" href="index.php">Back to Home</a>
      </div>

      <?php if (!empty($recommendedAccessories)): ?>
      <div class="success-accessories">
        <div class="section-label">// Finish The Setup</div>
        <h2 class="success-accessories-title">Would you like a case for your tablet?</h2>
        <p class="success-accessories-copy">
          Protective cases and screen covers are available for the tablet families in this order.
          Review the matching options below if you want to protect the device right away.
        </p>
        <div class="success-accessory-grid">
          <?php foreach (array_slice($recommendedAccessories, 0, 3) as $accessory): ?>
          <article class="success-accessory-card">
            <div class="success-accessory-kicker"><?= htmlspecialchars($accessory['brand']) ?></div>
            <h3><?= htmlspecialchars($accessory['tablet_name']) ?></h3>
            <p><?= htmlspecialchars($accessory['case_name']) ?> and <?= htmlspecialchars($accessory['screen_name']) ?> are available on the accessories page.</p>
            <div class="success-accessory-prices">
              <span>Case from $<?= number_format($accessory['case_price'], 2) ?></span>
              <span>Screen cover from $<?= number_format($accessory['screen_price'], 2) ?></span>
            </div>
            <a class="btn-outline full" href="accessories.php?brand=<?= urlencode($accessory['brand']) ?>&tablet=<?= urlencode($accessory['tablet_name']) ?>">View Matching Accessories</a>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <div class="success-accessories success-accessories-simple">
        <div class="section-label">// Finish The Setup</div>
        <h2 class="success-accessories-title">Would you like a case for your tablet?</h2>
        <p class="success-accessories-copy">
          Browse the new accessories page for matching protective cases and screen covers across Apple, Samsung,
          Microsoft, and Amazon tablets.
        </p>
        <div class="success-actions success-actions-secondary">
          <a class="btn-outline" href="accessories.php">Browse Accessories</a>
        </div>
      </div>
      <?php endif; ?>
    <?php endif; ?>

  </div>
</div>

<?php if ($purchaseVerified): ?>
<script>
localStorage.removeItem('tm_cart');
localStorage.removeItem('tm_checkout_preferences');
</script>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
