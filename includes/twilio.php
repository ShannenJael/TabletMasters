<?php
/**
 * Tablet Masters - Twilio Messaging client and form webhook validation.
 */

function tmTwilioConfig(): array
{
    return [
        'account_sid' => tmConfigValue('TWILIO_ACCOUNT_SID', 'TWILIO_ACCOUNT_SID'),
        'auth_token' => tmConfigValue('TWILIO_AUTH_TOKEN', 'TWILIO_AUTH_TOKEN'),
        'from_number' => tmConfigValue('TWILIO_FROM_NUMBER', 'TWILIO_FROM_NUMBER') ?: '+18504679868',
        'messaging_service_sid' => tmConfigValue('TWILIO_MESSAGING_SERVICE_SID', 'TWILIO_MESSAGING_SERVICE_SID'),
        'incoming_url' => tmConfigValue('TWILIO_INCOMING_WEBHOOK_URL', 'TWILIO_INCOMING_WEBHOOK_URL')
            ?: 'https://tablet-masters.com/twilio-incoming.php',
        'status_url' => tmConfigValue('TWILIO_STATUS_CALLBACK_URL', 'TWILIO_STATUS_CALLBACK_URL')
            ?: 'https://tablet-masters.com/twilio-status.php',
    ];
}

function tmTwilioSendMessage(string $to, string $body): array
{
    $config = tmTwilioConfig();
    if ($config['account_sid'] === '' || $config['auth_token'] === '') {
        return ['ok' => false, 'skipped' => true, 'reason' => 'not_configured', 'to' => $to];
    }

    $from = $config['from_number'];
    if (!preg_match('/^\+[1-9][0-9]{7,14}$/D', $from) || $to === $from) {
        return ['ok' => false, 'skipped' => true, 'reason' => 'invalid_sender_or_recipient', 'to' => $to];
    }

    $fields = [
        'To' => $to,
        'From' => $from,
        'Body' => tmSmsBody($body),
        'StatusCallback' => $config['status_url'],
    ];
    if ($config['messaging_service_sid'] !== '') {
        $fields['MessagingServiceSid'] = $config['messaging_service_sid'];
    }

    $credentials = base64_encode($config['account_sid'] . ':' . $config['auth_token']);
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => [
                'Authorization: Basic ' . $credentials,
                'Content-Type: application/x-www-form-urlencoded',
            ],
            'content' => http_build_query($fields),
            'ignore_errors' => true,
            'follow_location' => 0,
            'timeout' => 8,
        ],
    ]);
    $endpoint = 'https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode($config['account_sid']) . '/Messages.json';
    $response = @file_get_contents($endpoint, false, $context);
    $decoded = is_string($response) ? json_decode($response, true) : null;
    $status = tmHttpStatusCode($http_response_header ?? []);
    $sid = (string)($decoded['sid'] ?? '');
    $providerStatus = (string)($decoded['status'] ?? '');

    return [
        'ok' => $status >= 200 && $status < 300 && $sid !== '' && !in_array($providerStatus, ['failed', 'undelivered'], true),
        'status_code' => $status,
        'to' => $to,
        'message_sid' => $sid,
        'provider_status' => $providerStatus,
        'error_code' => (string)($decoded['code'] ?? $decoded['error_code'] ?? ''),
        'error_message' => (string)($decoded['message'] ?? $decoded['error_message'] ?? ''),
    ];
}

function tmTwilioWebhookPayload(string $callback): array
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        exit;
    }

    $sms = tmSmsConfig();
    $twilio = tmTwilioConfig();
    if (!$sms['enabled'] || $sms['provider'] !== 'twilio' || $twilio['account_sid'] === '' || $twilio['auth_token'] === '') {
        http_response_code(503);
        exit;
    }

    // Twilio signs the configured public URL and every decoded form field.
    $fields = $_POST;
    ksort($fields, SORT_STRING);
    $signedData = $twilio[$callback];
    foreach ($fields as $key => $value) {
        if (!is_string($value)) {
            http_response_code(400);
            exit;
        }
        $signedData .= $key . $value;
    }

    $expected = base64_encode(hash_hmac('sha1', $signedData, $twilio['auth_token'], true));
    if (!hash_equals($expected, $_SERVER['HTTP_X_TWILIO_SIGNATURE'] ?? '')
        || ($fields['AccountSid'] ?? '') !== $twilio['account_sid']) {
        http_response_code(403);
        exit;
    }

    return $fields;
}
