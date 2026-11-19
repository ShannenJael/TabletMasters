<?php
require_once __DIR__ . '/includes/shipments.php';

$number = strtoupper(tmShipmentFormValue($_GET, 'order'));
$location = '/track.php';
if (tmValidOrderNumber($number)) $location .= '?order=' . rawurlencode($number);

header('Cache-Control: private, no-store');
header('Referrer-Policy: no-referrer');
header('Location: ' . $location, true, 308);
exit;
