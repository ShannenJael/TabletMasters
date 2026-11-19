<?php
/**
 * Tablet Masters - SMS notification workflows
 */

require_once __DIR__ . '/sms.php';

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

    if ($customerPhone !== '' && ($metadata['sms_opt_in'] ?? '0') === '1') {
        if ($checkoutType === 'subscription') {
            $body = "Tablet Masters: Hi {$customerName}, your {$plan} protection signup is active. If you still need to register your tablet, visit tablet-masters.com/register.php. Reply if you need help.";
            $results['customer'] = tmSendNotificationOnce('customer-subscription:' . $sessionId, $customerPhone, $body);
        } else {
            $planText = ($plan !== '' && $plan !== 'none') ? " Coverage plan: {$plan}." : '';
            $body = "Tablet Masters: Hi {$customerName}, we received your order for {$amount}. {$itemSummary}{$planText} Reply here if you need help with delivery, setup, or accessories.";
            $results['customer'] = tmSendNotificationOnce('customer-purchase:' . $sessionId, $customerPhone, $body);
        }
    }

    $alertNumber = tmSmsConfig()['support_alert_number'];
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

    if ($phone !== '' && (string)($registration['sms_opt_in'] ?? '0') === '1') {
        $planText = ($plan !== '' && $plan !== 'none')
            ? " Plan selected: {$plan}."
            : " No protection plan is attached yet.";
        $body = "Tablet Masters: Hi {$name}, your {$brand} {$model} is now registered. Registration #: {$registrationNumber}.{$planText} Reply HELP for help or STOP to unsubscribe.";
        $results['customer'] = tmSendNotificationOnce('customer-registration:' . $registrationNumber, $phone, $body);
    }

    $alertNumber = tmSmsConfig()['support_alert_number'];
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

    $result = tmSendSmsMessage($to, $body);
    $entries[$key] = [
        'sent_at' => date('Y-m-d H:i:s'),
        'ok' => (bool)($result['ok'] ?? false),
        'skipped' => (bool)($result['skipped'] ?? false),
        'to' => (string)($result['to'] ?? $to),
        'message_id' => (string)($result['message_uuid'] ?? $result['message_sid'] ?? ''),
        'reason' => (string)($result['reason'] ?? ''),
    ];
    file_put_contents($ledger, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return $result;
}

function tmRecordInboundSms(array $payload, string $provider = 'aws'): bool
{
    $messageId = tmSmsPayloadValue($payload, ['MessageUUID', 'message_uuid', 'MessageSid', 'SmsSid', 'message_sid']);
    $from = tmSmsPayloadValue($payload, ['From', 'from', 'from_number']);
    $to = tmSmsPayloadValue($payload, ['To', 'to', 'to_number']);
    $body = tmSmsPayloadValue($payload, ['Text', 'Body', 'text', 'body']);

    return tmAppendSmsEvent('sms_inbox.json', [
        'provider' => $provider,
        'message_id' => $messageId,
        'from' => tmNormalizePhoneNumber($from) ?: $from,
        'to' => tmNormalizePhoneNumber($to) ?: $to,
        'body' => trim($body),
        'received_at' => date('Y-m-d H:i:s'),
    ], $messageId !== '' ? ['provider', 'message_id'] : []);
}

function tmRecordSmsStatusCallback(array $payload, string $provider = 'aws'): void
{
    tmAppendSmsEvent('sms_status_callbacks.json', [
        'provider' => $provider,
        'message_id' => tmSmsPayloadValue($payload, ['MessageUUID', 'message_uuid', 'MessageSid', 'message_sid']),
        'message_status' => tmSmsPayloadValue($payload, ['Status', 'MessageStatus', 'message_state', 'status']),
        'to' => tmSmsPayloadValue($payload, ['To', 'to', 'to_number']),
        'from' => tmSmsPayloadValue($payload, ['From', 'from', 'from_number']),
        'error_code' => tmSmsPayloadValue($payload, ['ErrorCode', 'error_code']),
        'error_message' => tmSmsPayloadValue($payload, ['ErrorMessage', 'error_message']),
        'recorded_at' => date('Y-m-d H:i:s'),
    ], $provider === 'twilio' ? ['provider', 'message_id', 'message_status', 'error_code'] : []);
}

function tmAppendSmsEvent(string $filename, array $row, array $deduplicateBy): bool
{
    $directory = __DIR__ . '/../data/logs';
    if (!is_dir($directory) && !@mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Could not create SMS log directory');
    }

    $path = $directory . '/' . $filename;
    $lock = fopen($path . '.lock', 'c');
    if ($lock === false) {
        throw new RuntimeException('Could not open SMS log lock');
    }

    $temporary = false;
    try {
        if (!flock($lock, LOCK_EX)) {
            throw new RuntimeException('Could not lock SMS log');
        }

        $rows = is_file($path) ? json_decode((string)file_get_contents($path), true, 512, JSON_THROW_ON_ERROR) : [];
        if ($deduplicateBy !== []) {
            $key = array_intersect_key($row, array_flip($deduplicateBy));
            foreach ($rows as $record) {
                if (array_intersect_key($record, $key) == $key) {
                    return false;
                }
            }
        }

        $rows[] = $row;
        $json = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
        $temporary = tempnam($directory, 'sms-');
        if ($temporary === false || file_put_contents($temporary, $json) !== strlen($json) || !rename($temporary, $path)) {
            throw new RuntimeException('Could not save SMS log');
        }
        $temporary = false;

        return true;
    } finally {
        if ($temporary !== false) {
            unlink($temporary);
        }
        fclose($lock);
    }
}

function tmNotificationLedgerPath(): string
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    return $logDir . '/sms_notifications.json';
}

function tmSmsPayloadValue(array $payload, array $keys): string
{
    foreach ($keys as $key) {
        if (isset($payload[$key]) && trim((string)$payload[$key]) !== '') {
            return trim((string)$payload[$key]);
        }
    }

    return '';
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
