<?php
/**
 * Tablet Masters — Save Device Registration
 * Validates input, saves to devices table, triggers plan checkout if selected.
 */

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/emails.php';
require_once __DIR__ . '/includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /register.php');
    exit;
}

function clean($v) { return trim(strip_tags($v ?? '')); }
function buildRegisterRedirect(array $params = []): string {
    $base = [
        'email' => clean($_POST['email'] ?? ''),
        'order' => clean($_POST['order_id'] ?? ''),
        'model' => clean($_POST['model'] ?? ''),
        'plan'  => clean($_POST['plan'] ?? 'none'),
        'source'=> clean($_POST['purchase_source'] ?? 'tablet-masters'),
    ];

    $query = array_merge($base, $params);
    $query = array_filter($query, static function ($value) {
        return $value !== '' && $value !== null;
    });

    return '/register.php' . ($query ? '?' . http_build_query($query) : '');
}

$name          = clean($_POST['name']          ?? '');
$email         = filter_var(clean($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone         = clean($_POST['phone']         ?? '');
$brand         = clean($_POST['brand']         ?? '');
$brand_other   = clean($_POST['brand_other']   ?? '');
$brand         = ($brand === 'Other' && $brand_other !== '') ? $brand_other : $brand;
$model         = clean($_POST['model']         ?? '');
$serial        = strtoupper(preg_replace('/\s+/', '', clean($_POST['serial_number'] ?? '')));
$purchase_date = clean($_POST['purchase_date'] ?? '');
$source        = clean($_POST['purchase_source'] ?? 'tablet-masters');
$plan          = clean($_POST['plan']          ?? 'none');
$order_id      = clean($_POST['order_id']      ?? '');

// Validate required fields
if (!$name || !$email || !$brand || !$model || !$serial) {
    header('Location: ' . buildRegisterRedirect(['error' => 1]));
    exit;
}

if ($purchase_date !== '') {
    $purchaseDateValue = DateTime::createFromFormat('Y-m-d', $purchase_date);
    $purchaseDateErrors = DateTime::getLastErrors();
    if ($purchaseDateErrors === false) {
        $purchaseDateErrors = ['warning_count' => 0, 'error_count' => 0];
    }
    $today = new DateTime('today');

    $isValidPurchaseDate = $purchaseDateValue instanceof DateTime
        && $purchaseDateErrors['warning_count'] === 0
        && $purchaseDateErrors['error_count'] === 0
        && $purchaseDateValue->format('Y-m-d') === $purchase_date
        && $purchaseDateValue <= $today;

    if (!$isValidPurchaseDate) {
        header('Location: ' . buildRegisterRedirect(['invalid_purchase_date' => 1]));
        exit;
    }
}

// Source must be known
if (!in_array($source, ['tablet-masters', 'external'])) {
    $source = 'tablet-masters';
}

// Plan only applies to external
if ($source === 'tablet-masters') {
    $plan = 'none';
}

// Determine initial plan_status
if ($source === 'tablet-masters') {
    $plan_status = 'pending'; // will link when subscription created
} elseif ($plan !== 'none') {
    $plan_status = 'pending_payment';
} else {
    $plan_status = 'uninsured';
}

try {
    $db = getDB();

    // Check for duplicate serial number
    $dup = $db->prepare("SELECT id FROM devices WHERE serial_number = ?");
    $dup->execute([$serial]);
    if ($dup->fetch()) {
        header('Location: ' . buildRegisterRedirect(['duplicate' => 1]));
        exit;
    }

    // Generate registration number
    do {
        $regNumber = 'REG-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        $chk = $db->prepare("SELECT id FROM devices WHERE registration_number = ?");
        $chk->execute([$regNumber]);
    } while ($chk->fetch());

    $stmt = $db->prepare("
        INSERT INTO devices
          (registration_number, customer_email, customer_name, customer_phone,
           device_brand, device_model, serial_number, purchase_date,
           purchase_source, order_id, plan, plan_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $regNumber, $email, $name, $phone,
        $brand, $model, $serial, $purchase_date,
        $source, $order_id, $plan, $plan_status,
    ]);

    // Send confirmation email
    sendDeviceRegistrationEmail($email, $name, $brand, $model, $serial, $regNumber, $plan, $source);
    tmNotifyDeviceRegistration([
        'registration_number' => $regNumber,
        'customer_email' => $email,
        'customer_name' => $name,
        'customer_phone' => $phone,
        'device_brand' => $brand,
        'device_model' => $model,
        'plan' => $plan,
    ]);

    // If external + plan selected, auto-submit to subscribe.php (Stripe checkout)
    if ($source === 'external' && $plan !== 'none') {
        $safeEmail = htmlspecialchars($email, ENT_QUOTES);
        $safePlan  = htmlspecialchars($plan,  ENT_QUOTES);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Redirecting&hellip;</title></head><body>';
        echo '<p style="font-family:sans-serif;color:#888;padding:40px;text-align:center">Setting up your plan&hellip;</p>';
        echo '<form id="pf" method="POST" action="/subscribe.php">';
        echo '<input type="hidden" name="plan"  value="' . $safePlan  . '">';
        echo '<input type="hidden" name="email" value="' . $safeEmail . '">';
        echo '</form>';
        echo '<script>document.getElementById("pf").submit();</script>';
        echo '</body></html>';
        exit;
    }

    header('Location: /register.php?success=1');
    exit;

} catch (Exception $e) {
    if (strpos($e->getMessage(), 'UNIQUE') !== false) {
        header('Location: ' . buildRegisterRedirect(['duplicate' => 1]));
    } else {
        error_log('save-device.php error: ' . $e->getMessage());
        header('Location: ' . buildRegisterRedirect(['error' => 1]));
    }
    exit;
}
