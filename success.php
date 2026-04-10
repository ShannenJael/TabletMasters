<?php
/**
 * Tablet Masters — Order Success Page
 */
$currentPage = 'shop';
require_once __DIR__ . '/includes/config.php';

$sessionId  = $_GET['session_id'] ?? null;
$type       = $_GET['type'] ?? '';
$plan       = $_GET['plan'] ?? '';
$subscribed = ($type === 'subscription');
$order      = null;

if ($sessionId && !$subscribed) {
    $session = stripeGet('checkout/sessions/' . urlencode($sessionId) . '?expand[]=line_items');
    if ($session && isset($session['id'])) {
        $order = $session;

        // If customer selected an insurance plan, redirect them to subscribe
        $insurancePlan = $session['metadata']['insurance_plan'] ?? '';
        if (!empty($insurancePlan) && $insurancePlan !== 'none') {
            $email = $session['customer_details']['email'] ?? '';
            echo '<form id="sub-form" method="POST" action="/subscribe.php">';
            echo '<input type="hidden" name="plan" value="' . htmlspecialchars($insurancePlan) . '">';
            echo '<input type="hidden" name="email" value="' . htmlspecialchars($email) . '">';
            echo '</form>';
            echo '<script>
localStorage.removeItem("tm_cart");
setTimeout(function(){ document.getElementById("sub-form").submit(); }, 1500);
</script>';
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
  <title><?= $subscribed ? 'Protection Active' : 'Order Confirmed' ?> — Tablet Masters</title>
  <link rel="stylesheet" href="assets/css/style.css" />
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

    <?php if ($subscribed): ?>
      <div class="success-icon">🛡️</div>
      <h1 class="success-title">Protection Active!</h1>
      <p class="success-desc">
        Your <strong><?= htmlspecialchars($planLabels[$plan] ?? 'protection plan') ?></strong> is now active.
        You're covered — we'll send your plan details by email.
      </p>
      <p class="success-note">
        Please go to the <a href="register.php">registration page</a> to register your device.
      </p>
      <div class="success-actions">
        <a class="btn-primary" href="shop.php">Continue Shopping</a>
        <a class="btn-outline" href="insurance.php">View Coverage</a>
      </div>

    <?php else: ?>
      <div class="success-icon">✅</div>
      <h1 class="success-title">Order Confirmed!</h1>

      <?php if ($order && ($order['payment_status'] ?? '') === 'paid'): ?>
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
        $insurancePlan = $order['metadata']['insurance_plan'] ?? '';
        if (!empty($insurancePlan) && $insurancePlan !== 'none'):
        ?>
        <p class="success-note" style="color:var(--blue)">
          <i class="fas fa-shield-alt"></i> Setting up your protection plan — please wait...
        </p>
        <?php else: ?>
        <p class="success-note">
          A confirmation email has been sent. We'll follow up with shipping details within 24 hours.
        </p>
        <?php endif; ?>
      <?php else: ?>
        <p class="success-desc">
          Thank you for your order! You'll receive a confirmation email shortly.
        </p>
      <?php endif; ?>

      <div class="success-actions">
        <a class="btn-primary" href="shop.php">Continue Shopping</a>
        <a class="btn-outline" href="index.php">Back to Home</a>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script>
localStorage.removeItem('tm_cart');
</script>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>

<?php
// ── Stripe API helper ─────────────────────────────────────────────────────────

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
