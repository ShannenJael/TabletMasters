<?php

require_once __DIR__ . '/stripe-security.php';

const TM_SHIPMENT_STATUSES = [
    'preparing' => 'Preparing order',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
];

const TM_SHIPMENT_CARRIERS = [
    'usps' => ['USPS', 'https://tools.usps.com/go/TrackConfirmAction?tLabels='],
    'ups' => ['UPS', 'https://www.ups.com/track?tracknum='],
    'fedex' => ['FedEx', 'https://www.fedex.com/fedextrack/?trknbr='],
    'dhl' => ['DHL', 'https://www.dhl.com/us-en/home/tracking.html?tracking-id='],
    'other' => ['Other carrier', null],
];

function tmOrderNumber(string $sessionId): string
{
    return 'TM-' . strtoupper(substr(hash('sha256', $sessionId), 0, 24));
}

function tmValidOrderNumber(string $number): bool
{
    return preg_match('/^TM-[A-F0-9]{24}$/D', $number) === 1;
}

function tmShipmentFormValue(array $input, string $key): string
{
    return is_string($input[$key] ?? null) ? trim($input[$key]) : '';
}

function tmReadOrderRecords(): array
{
    $file = __DIR__ . '/../data/logs/orders.json';
    if (!is_file($file)) return [];

    $handle = @fopen($file, 'r');
    if ($handle === false) throw new RuntimeException('Unable to open order records');
    try {
        if (!flock($handle, LOCK_SH)) throw new RuntimeException('Unable to read order records');
        $contents = stream_get_contents($handle);
        if ($contents === '') return [];
        $orders = json_decode((string)$contents, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($orders) || !array_is_list($orders)) throw new RuntimeException('Invalid order records');
        return $orders;
    } finally {
        fclose($handle);
    }
}

function tmShipmentOrders(array $orders): array
{
    $purchases = [];
    foreach ($orders as $order) {
        if (!is_array($order) || !validStripeSessionId($order['session_id'] ?? null)
            || !in_array($order['payment_status'] ?? '', ['paid', 'no_payment_required', 'completed'], true)) continue;

        $type = $order['checkout_type'] ?? (($order['mode'] ?? '') === 'payment' ? 'purchase' : '');
        if ($type === 'purchase') $purchases[tmOrderNumber($order['session_id'])] = $order;
    }
    return $purchases;
}

function tmShipmentPath(string $number): string
{
    if (!tmValidOrderNumber($number)) throw new InvalidArgumentException('Invalid order number');
    return __DIR__ . '/../data/logs/shipments/' . $number . '.json';
}

function tmReadShipment(string $number): array
{
    $path = tmShipmentPath($number);
    if (is_file($path)) {
        return json_decode((string)@file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }
    return [
        'status' => 'preparing', 'carrier' => '', 'tracking' => '',
        'revision' => 0, 'updated_at' => '', 'email_sent' => false, 'sms_sent' => false,
    ];
}

function tmShipmentTrackingUrl(array $shipment): ?string
{
    $base = TM_SHIPMENT_CARRIERS[$shipment['carrier']][1] ?? null;
    return $base && $shipment['tracking'] !== '' ? $base . rawurlencode($shipment['tracking']) : null;
}

function tmShipmentFields(array $input): array
{
    $status = tmShipmentFormValue($input, 'status');
    $carrier = tmShipmentFormValue($input, 'carrier');
    $tracking = preg_replace('/\s+/', '', tmShipmentFormValue($input, 'tracking'));
    if (!isset(TM_SHIPMENT_STATUSES[$status])) throw new InvalidArgumentException('Choose a shipment status.');
    if ($carrier !== '' && !isset(TM_SHIPMENT_CARRIERS[$carrier])) {
        throw new InvalidArgumentException('Choose a supported carrier.');
    }
    if (($carrier === '') !== ($tracking === '') || ($status !== 'preparing' && $tracking === '')) {
        throw new InvalidArgumentException('Enter both a carrier and a tracking number for this shipment.');
    }
    if ($tracking !== '' && !preg_match('/^[a-zA-Z0-9-]{4,70}$/D', $tracking)) {
        throw new InvalidArgumentException('Use 4 to 70 letters, numbers, or hyphens for the tracking number.');
    }
    return ['status' => $status, 'carrier' => $carrier, 'tracking' => $tracking];
}

function tmWriteShipment(string $path, array $shipment): void
{
    $json = json_encode($shipment, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    $temporary = tempnam(dirname($path), 'shipment-');
    if ($temporary === false) throw new RuntimeException('Unable to stage shipment update');
    try {
        if (file_put_contents($temporary, $json) !== strlen($json) || !rename($temporary, $path)) {
            throw new RuntimeException('Unable to save shipment update');
        }
    } finally {
        if (is_file($temporary)) unlink($temporary);
    }
}

function tmSaveShipment(string $number, array $order, array $fields, string $revision, bool $notify): string
{
    $path = tmShipmentPath($number);
    $directory = dirname($path);
    if (!is_dir($directory) && !@mkdir($directory, 0750, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create shipment directory');
    }

    $lock = fopen($path . '.lock', 'c');
    if ($lock === false) throw new RuntimeException('Unable to open shipment lock');
    try {
        if (!flock($lock, LOCK_EX)) throw new RuntimeException('Unable to lock shipment');
        // PHP may have cached the result of an earlier read in this request.
        clearstatcache(true, $path);
        $shipment = tmReadShipment($number);
        if ((string)$shipment['revision'] !== $revision) {
            throw new InvalidArgumentException('This shipment changed. Review the latest details and try again.');
        }
        if ($shipment['revision'] === 0 || array_intersect_key($shipment, $fields) !== $fields) {
            $shipment = $fields + [
                'revision' => $shipment['revision'] + 1,
                'updated_at' => gmdate('Y-m-d\TH:i:s\Z'),
                'email_sent' => false,
                'sms_sent' => false,
            ];
            tmWriteShipment($path, $shipment);
        }
        if (!$notify) return 'Shipment saved.';

        return 'Shipment saved. ' . tmSendShipmentUpdates($path, $number, $order, $shipment);
    } finally {
        fclose($lock);
    }
}

function tmSendShipmentUpdates(string $path, string $number, array $order, array $shipment): string
{
    require_once __DIR__ . '/emails.php';
    require_once __DIR__ . '/sms.php';

    $status = TM_SHIPMENT_STATUSES[$shipment['status']];
    $site = defined('SITE_URL') ? rtrim(SITE_URL, '/') : 'https://tablet-masters.com';
    $link = $site . '/track.php?order=' . rawurlencode($number);
    $email = (string)($order['email'] ?? '');
    $phone = (string)($order['phone'] ?? '');
    $results = [];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($shipment['email_sent']) {
            $results[] = 'Email already sent for this update.';
        } elseif (tmSendShipmentEmail($email, $number, $status, $link, $shipment['tracking'], tmShipmentTrackingUrl($shipment))) {
            $shipment['email_sent'] = true;
            tmWriteShipment($path, $shipment);
            $results[] = 'Email submitted.';
        } else {
            $results[] = 'Email could not be sent. Try sending the update again.';
        }
    }

    if ($phone !== '' && ($order['sms_opt_in'] ?? '0') === '1') {
        if ($shipment['sms_sent']) {
            $results[] = 'Text already sent for this update.';
        } else {
            $message = "Tablet Masters: Order {$number} is marked {$status}. Track your shipment at {$link}. Reply HELP for help or STOP to unsubscribe.";
            $sent = tmSendSmsMessage($phone, $message);
            if (!empty($sent['ok'])) {
                $shipment['sms_sent'] = true;
                tmWriteShipment($path, $shipment);
                $results[] = 'Text submitted.';
            } else {
                $results[] = 'Text could not be sent. Try sending the update again.';
            }
        }
    }

    return $results ? implode(' ', $results) : 'No eligible contact method is available for this order.';
}
