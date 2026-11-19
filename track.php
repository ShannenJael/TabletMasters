<?php
$currentPage = '';
require_once __DIR__ . '/includes/shipments.php';

header('Cache-Control: private, no-store');
header('Referrer-Policy: no-referrer');

$posted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
$number = strtoupper(tmShipmentFormValue($posted ? $_POST : $_GET, 'order'));
$email = $posted ? tmShipmentFormValue($_POST, 'email') : '';
$validNumber = tmValidOrderNumber($number);
$error = '';
$shipment = null;

if ($posted) {
    if (!$validNumber || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter your order number and the email address you used at checkout.';
    } else {
        try {
            $orders = tmShipmentOrders(tmReadOrderRecords());
            $orderEmail = (string)($orders[$number]['email'] ?? '');
            if ($orderEmail !== '' && strcasecmp($orderEmail, $email) === 0) {
                $shipment = tmReadShipment($number);
            } else {
                $error = 'We couldn’t find that order. Check your order number and checkout email.';
            }
        } catch (Throwable $exception) {
            error_log('Shipment lookup could not read the order or shipment records.');
            http_response_code(503);
            $error = 'Shipment tracking is temporarily unavailable. Please try again or contact support.';
        }
    }
}

$trackingUrl = $shipment ? tmShipmentTrackingUrl($shipment) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Track Shipment - Tablet Masters</title>
  <meta name="description" content="Check your Tablet Masters shipment status and find your carrier tracking number." />
  <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/assets/css/style.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
</head>
<body>
<?php include __DIR__ . '/includes/nav.php'; ?>

<main class="policy-section shipment-page">
  <header class="policy-hero">
    <h1 class="section-title">Track Shipment</h1>
    <p class="policy-intro">Enter your order number and checkout email to check your shipment.</p>
  </header>

  <?php if ($shipment): ?>
  <section class="policy-card shipment-result" aria-labelledby="shipment-status">
    <p class="shipment-reference">Order <?= htmlspecialchars($number) ?></p>
    <h2 id="shipment-status"><?= $shipment['revision'] === 0 ? 'Order received' : htmlspecialchars(TM_SHIPMENT_STATUSES[$shipment['status']]) ?></h2>
    <?php if ($shipment['tracking'] === ''): ?>
    <p>Tracking details will appear once they’re available.</p>
    <?php endif; ?>
    <?php if ($shipment['tracking'] !== ''): ?>
    <dl class="shipment-details">
      <div>
        <dt>Carrier</dt>
        <dd><?= htmlspecialchars(TM_SHIPMENT_CARRIERS[$shipment['carrier']][0]) ?></dd>
      </div>
      <div>
        <dt>Tracking number</dt>
        <dd><?= htmlspecialchars($shipment['tracking']) ?></dd>
      </div>
    </dl>
    <?php endif; ?>
    <?php if ($trackingUrl): ?>
    <a class="btn-outline" href="<?= htmlspecialchars($trackingUrl) ?>" target="_blank" rel="noopener noreferrer">Track with carrier</a>
    <?php endif; ?>
    <?php if ($shipment['updated_at'] !== ''): ?>
    <p class="policy-note">Updated <time datetime="<?= htmlspecialchars($shipment['updated_at']) ?>"><?= gmdate('M j, Y', strtotime($shipment['updated_at'])) ?></time></p>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <form class="policy-card shipment-form" action="track.php" method="post">
    <?php if ($error !== ''): ?>
    <p class="shipment-feedback" role="alert"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <div class="shipment-fields">
      <label for="shipment-order">Order number
        <input class="repair-input" id="shipment-order" name="order" maxlength="27" autocapitalize="characters"
          value="<?= $validNumber ? htmlspecialchars($number) : '' ?>" aria-describedby="order-number-help" required />
      </label>
      <label for="shipment-email">Checkout email
        <input class="repair-input" id="shipment-email" name="email" type="email" autocomplete="email"
          maxlength="254" value="<?= htmlspecialchars(substr($email, 0, 254)) ?>" required />
      </label>
    </div>
    <p id="order-number-help">Find your order number on the order confirmation page or in a shipment update.</p>
    <button class="btn-primary" type="submit">Track shipment</button>
  </form>

  <p class="policy-note shipment-help">Need help finding your order? <a href="support.php">Contact support</a>.</p>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
