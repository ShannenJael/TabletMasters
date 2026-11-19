<?php
require_once __DIR__ . '/bootstrap.php';
tmAdminRequireLogin('inventory.php?next=shipments');
require_once __DIR__ . '/../includes/shipments.php';

header('Referrer-Policy: no-referrer');
$_SESSION['tm_shipment_csrf'] ??= bin2hex(random_bytes(32));
$csrf = $_SESSION['tm_shipment_csrf'];
$posted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
$number = strtoupper(tmShipmentFormValue($posted ? $_POST : $_GET, 'order'));

if ($posted && !hash_equals($csrf, tmShipmentFormValue($_POST, 'csrf'))) {
    http_response_code(403);
    exit('Reload the shipment page and try again.');
}

$orders = [];
$order = null;
$shipment = null;
$error = '';
$notice = $_SESSION['tm_shipment_notice'] ?? '';
unset($_SESSION['tm_shipment_notice']);

try {
    $orders = tmShipmentOrders(tmReadOrderRecords());
    $order = $orders[$number] ?? null;
    if ($number !== '' && !$order) {
        http_response_code(404);
        $error = 'That paid purchase could not be found.';
    } elseif ($order) {
        $shipment = tmReadShipment($number);
        if ($posted) {
            $_SESSION['tm_shipment_notice'] = tmSaveShipment(
                $number, $order, tmShipmentFields($_POST), tmShipmentFormValue($_POST, 'revision'),
                ($_POST['notify'] ?? '') === '1'
            );
            header('Location: shipments.php?order=' . rawurlencode($number), true, 303);
            exit;
        }
    }
} catch (InvalidArgumentException $exception) {
    $error = $exception->getMessage();
} catch (Throwable $exception) {
    error_log('Unable to read or update shipment records.');
    http_response_code(503);
    $error = 'Shipment records are temporarily unavailable. Reload the page to check the latest status.';
}

function shipmentText(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shipments Admin - Tablet Masters</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>" />
</head>
<body>
<main class="policy-section shipment-page">
  <p class="shipment-help"><a href="orders.php">Orders Dashboard</a> · <a href="index.php">Management Console</a></p>
  <header class="policy-hero">
    <h1 class="section-title"><?= $order ? 'Manage Shipment' : 'Shipments' ?></h1>
  </header>

  <?php if ($error !== ''): ?>
  <p class="policy-card shipment-feedback" role="alert"><?= shipmentText($error) ?></p>
  <?php endif; ?>
  <?php if ($notice !== ''): ?>
  <p class="policy-card shipment-feedback" role="status"><?= shipmentText($notice) ?></p>
  <?php endif; ?>

  <?php if ($order && $shipment): ?>
  <form class="policy-card shipment-form" method="post" action="shipments.php">
    <h2><?= shipmentText($number) ?></h2>
    <p><?= shipmentText((string)($order['email'] ?? '')) ?></p>
    <input type="hidden" name="order" value="<?= shipmentText($number) ?>" />
    <input type="hidden" name="csrf" value="<?= shipmentText($csrf) ?>" />
    <input type="hidden" name="revision" value="<?= (int)$shipment['revision'] ?>" />

    <label for="shipment-status-input">Shipment status
      <select class="repair-input" id="shipment-status-input" name="status">
        <?php foreach (TM_SHIPMENT_STATUSES as $value => $label): ?>
        <option value="<?= shipmentText($value) ?>" <?= $shipment['status'] === $value ? 'selected' : '' ?>><?= shipmentText($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="shipment-fields">
      <label for="shipment-carrier">Carrier
        <select class="repair-input" id="shipment-carrier" name="carrier">
          <option value="">Choose a carrier</option>
          <?php foreach (TM_SHIPMENT_CARRIERS as $value => [$label]): ?>
          <option value="<?= shipmentText($value) ?>" <?= $shipment['carrier'] === $value ? 'selected' : '' ?>><?= shipmentText($label) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label for="shipment-tracking">Tracking number
        <input class="repair-input" id="shipment-tracking" name="tracking" maxlength="70" value="<?= shipmentText($shipment['tracking']) ?>" />
      </label>
    </div>
    <p>Sending an update emails the checkout address and texts customers who opted in.</p>
    <div class="shipment-actions">
      <button class="btn-outline" type="submit">Save shipment</button>
      <button class="btn-primary" type="submit" name="notify" value="1">Save and notify customer</button>
    </div>
  </form>
  <p class="policy-note shipment-help"><a href="shipments.php">Back to shipments</a></p>
  <?php elseif ($orders): ?>
  <section class="policy-card shipment-result">
    <h2>Recent purchases</h2>
    <ul class="policy-list shipment-orders">
      <?php foreach (array_slice(array_reverse($orders, true), 0, 25, true) as $ref => $purchase): ?>
      <li><a href="shipments.php?order=<?= shipmentText($ref) ?>"><?= shipmentText($ref) ?></a>
        <span><?= shipmentText((string)($purchase['email'] ?? '')) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <p class="policy-note"><a href="orders.php">Search all orders</a></p>
  </section>
  <?php elseif ($error === ''): ?>
  <p class="policy-card">Paid purchases will appear here once checkout is complete.</p>
  <?php endif; ?>
</main>
</body>
</html>
