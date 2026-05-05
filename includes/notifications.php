<?php
/**
 * Tablet Masters - SMS notification workflows
 */

require_once __DIR__ . '/twilio.php';

function tmNotifyOrderCompleted(array $session, array $lineItems = []): array
{
    $results = [];

    $sessionId = (string)($session['id'] ?? '');
    $customerName = tmFirstName((string)($session['customer_details']['name'] ?? ''));
    $customerPhone = (string)($session['customer_details']['phone'] ?? '');
    $customerEmail = (string)($session['customer_details']['email'] ?? ($session['customer_email'] ?? ''));
    $amount = tmFormatCurrency((int)($session['amount_total'] ?? 0), (string)($session['currency'] ?? 'usd'));
    $metadata = is_array($session['metadata'] ?? null) ? $session['metadata'] : [];
    $checkoutType = (string)($metadata['checkout_type'] ?? ((($session['mode'] ?? '') === 'subscription') ? 'subscription' : 'purchase'));
    $plan = (string)($metadata['plan'] ?? ($metadata['insurance_plan'] ?? ''));
    $itemSummary = tmItemSummary($lineItems);

    if ($customerPhone !== '') {
        if ($checkoutType === 'subscription') {
            $body = "Tablet Masters: Hi {$customerName}, your {$plan} protection signup is active. If you still need to register your tablet, visit tablet-masters.com/register.php. Reply if you need help.";
            $results['customer'] = tmSendNotificationOnce('customer-subscription:' . $sessionId, $customerPhone, $body);
        } else {
            $planText = ($plan !== '' && $plan !== 'none') ? " Coverage plan: {$plan}." : '';
            $body = "Tablet Masters: Hi {$customerName}, we received your order for {$amount}. {$itemSummary}{$planText} Reply here if you need help with delivery, setup, or accessories.";
            $results['customer'] = tmSendNotificationOnce('customer-purchase:' . $sessionId, $customerPhone, $body);
        }
    }

    $alertNumber = tmTwilioConfig()['support_alert_number'];
    if ($alertNumber !== '') {
        $typeLabel = $checkoutType === 'subscription' ? 'insurance signup' : 'purchase';
        $body = "Tablet Masters alert: New {$typeLabel}. {$customerName} {$customerEmail} {$amount}. {$itemSummary}";
        if ($plan !== '' && $plan !== 'none') {
            $body .= " Plan: {$plan}.";
        }
        $results['staff'] = tmSendNotificationOnce('staff-order:' . $sessionId, $alertNumber, $body);
    }

    return $results;
}

function tmNotifyDeviceRegistration(array $registration): array
{
    $results = [];

    $registrationNumber = (string)($registration['registration_number'] ?? '');
    $phone = (string)($registration['customer_phone'] ?? '');
    $name = tmFirstName((string)($registration['customer_name'] ?? ''));
    $brand = (string)($registration['device_brand'] ?? '');
    $model = (string)($registration['device_model'] ?? '');
    $plan = (string)($registration['plan'] ?? 'none');
    $email = (string)($registration['customer_email'] ?? '');

    if ($phone !== '') {
        $planText = ($plan !== '' && $plan !== 'none')
            ? " Plan selected: {$plan}."
            : " No protection plan is attached yet.";
        $body = "Tablet Masters: Hi {$name}, your {$brand} {$model} is now registered. Registration #: {$registrationNumber}.{$planText} Reply here if you need support.";
        $results['customer'] = tmSendNotificationOnce('customer-registration:' . $registrationNumber, $phone, $body);
    }

    $alertNumber = tmTwilioConfig()['support_alert_number'];
    if ($alertNumber !== '') {
        $body = "Tablet Masters alert: Device registration {$registrationNumber}. {$name} {$email}. {$brand} {$model}.";
        if ($plan !== '' && $plan !== 'none') {
            $body .= " Plan: {$plan}.";
        }
        $results['staff'] = tmSendNotificationOnce('staff-registration:' . $registrationNumber, $alertNumber, $body);
    }

    return $results;
}

function tmSendNotificationOnce(string $key, string $to, string $body): array
{
    $ledger = tmNotificationLedgerPath();
    $entries = file_exists($ledger)
        ? (json_decode((string)file_get_contents($ledger), true) ?: [])
        : [];

    if (isset($entries[$key])) {
        return [
            'ok' => true,
            'skipped' => true,
            'reason' => 'duplicate',
            'key' => $key,
            'previous' => $entries[$key],
        ];
    }

    $result = tmTwilioSendMessage($to, $body);
    $entries[$key] = [
        'sent_at' => date('Y-m-d H:i:s'),
        'ok' => (bool)($result['ok'] ?? false),
        'skipped' => (bool)($result['skipped'] ?? false),
        'to' => (string)($result['to'] ?? $to),
        'message_sid' => (string)($result['message_sid'] ?? ''),
        'reason' => (string)($result['reason'] ?? ''),
    ];
    file_put_contents($ledger, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return $result;
}

function tmRecordInboundSms(array $payload): void
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $inboxFile = $logDir . '/twilio_inbox.json';
    $messages = file_exists($inboxFile)
        ? (json_decode((string)file_get_contents($inboxFile), true) ?: [])
        : [];

    $messages[] = [
        'message_sid' => (string)($payload['MessageSid'] ?? ''),
        'from' => tmNormalizePhoneNumber((string)($payload['From'] ?? '')) ?: (string)($payload['From'] ?? ''),
        'to' => tmNormalizePhoneNumber((string)($payload['To'] ?? '')) ?: (string)($payload['To'] ?? ''),
        'body' => trim((string)($payload['Body'] ?? '')),
        'received_at' => date('Y-m-d H:i:s'),
    ];

    file_put_contents($inboxFile, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function tmRecordTwilioStatusCallback(array $payload): void
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $statusFile = $logDir . '/twilio_status_callbacks.json';
    $rows = file_exists($statusFile)
        ? (json_decode((string)file_get_contents($statusFile), true) ?: [])
        : [];

    $rows[] = [
        'message_sid' => (string)($payload['MessageSid'] ?? ''),
        'message_status' => (string)($payload['MessageStatus'] ?? ''),
        'to' => (string)($payload['To'] ?? ''),
        'error_code' => (string)($payload['ErrorCode'] ?? ''),
        'error_message' => (string)($payload['ErrorMessage'] ?? ''),
        'recorded_at' => date('Y-m-d H:i:s'),
    ];

    file_put_contents($statusFile, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function tmNotificationLedgerPath(): string
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    return $logDir . '/twilio_notifications.json';
}

function tmFormatCurrency(int $amountCents, string $currency): string
{
    $currency = strtoupper($currency ?: 'USD');
    $symbol = $currency === 'USD' ? '$' : $currency . ' ';
    return $symbol . number_format($amountCents / 100, 2);
}

function tmItemSummary(array $lineItems): string
{
    $names = [];
    foreach ($lineItems as $item) {
        $name = trim((string)($item['name'] ?? ''));
        if ($name === '') {
            continue;
        }
        $quantity = max(1, (int)($item['quantity'] ?? 1));
        $names[] = $name . ($quantity > 1 ? ' x' . $quantity : '');
    }

    if (!$names) {
        return 'We have your order details.';
    }

    return 'Items: ' . implode(', ', array_slice($names, 0, 3)) . '.';
}

function tmFirstName(string $name): string
{
    $name = trim($name);
    if ($name === '') {
        return 'there';
    }

    $parts = preg_split('/\s+/', $name);
    return $parts[0] ?: 'there';
}
