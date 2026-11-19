<?php
/**
 * Tablet Masters - AWS End User Messaging (Pinpoint SMS/Voice v2) SMS helper
 * Lightweight SigV4-signed REST client for transactional SMS without requiring Composer.
 */

require_once __DIR__ . '/aws-sigv4.php';

function tmAwsSmsConfig(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'region' => tmConfigValue('AWS_REGION', 'AWS_REGION') ?: 'us-east-2',
        'access_key' => tmConfigValue('AWS_ACCESS_KEY_ID', 'AWS_ACCESS_KEY_ID'),
        'secret_key' => tmConfigValue('AWS_SECRET_ACCESS_KEY', 'AWS_SECRET_ACCESS_KEY'),
        'origination_number' => tmConfigValue('AWS_SMS_ORIGINATION_NUMBER', 'AWS_SMS_ORIGINATION_NUMBER'),
        'configuration_set' => tmConfigValue('AWS_SMS_CONFIGURATION_SET', 'AWS_SMS_CONFIGURATION_SET'),
    ];

    return $config;
}

function tmAwsSendSmsMessage(string $to, string $body, array $options): array
{
    $config = tmAwsSmsConfig();
    if ($config['access_key'] === '' || $config['secret_key'] === '' || $config['origination_number'] === '') {
        return ['ok' => false, 'skipped' => true, 'reason' => 'not_configured', 'to' => $to];
    }

    $originationIdentity = trim((string)($options['from'] ?? $config['origination_number']));

    $requestFields = [
        'DestinationPhoneNumber' => $to,
        'MessageBody' => tmSmsBody($body),
        'MessageType' => (string)($options['message_type'] ?? 'TRANSACTIONAL'),
        'OriginationIdentity' => $originationIdentity,
    ];

    $configurationSet = trim((string)($options['configuration_set'] ?? $config['configuration_set']));
    if ($configurationSet !== '') {
        $requestFields['ConfigurationSetName'] = $configurationSet;
    }

    $payload = json_encode($requestFields, JSON_UNESCAPED_SLASHES);
    if (!is_string($payload)) {
        return [
            'ok' => false,
            'skipped' => true,
            'reason' => 'invalid_payload',
            'to' => $to,
            'body' => $body,
        ];
    }

    $host = "sms-voice.{$config['region']}.amazonaws.com";
    $headers = tmAwsSigV4Sign(
        'POST',
        $host,
        '/',
        '',
        [
            'content-type' => 'application/x-amz-json-1.0',
            'x-amz-target' => 'PinpointSMSVoiceV2.SendTextMessage',
        ],
        $payload,
        'sms-voice',
        $config['region'],
        $config['access_key'],
        $config['secret_key']
    );

    $headerLines = '';
    foreach ($headers as $name => $value) {
        $headerLines .= $name . ': ' . $value . "\r\n";
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headerLines,
            'content' => $payload,
            'ignore_errors' => true,
            'timeout' => 20,
        ],
    ]);

    $endpoint = "https://{$host}/";
    $responseBody = @file_get_contents($endpoint, false, $context);
    $decoded = is_string($responseBody) ? json_decode($responseBody, true) : null;
    $statusCode = tmHttpStatusCode($http_response_header ?? []);
    $messageId = is_array($decoded) ? (string)($decoded['MessageId'] ?? '') : '';
    $ok = $statusCode >= 200 && $statusCode < 300 && $messageId !== '';

    return [
        'ok' => $ok,
        'skipped' => false,
        'status_code' => $statusCode,
        'to' => $to,
        'body' => $requestFields['MessageBody'],
        'message_uuid' => $messageId,
        'message_sid' => $messageId,
        'provider_status' => '',
        'error_code' => is_array($decoded) ? (string)($decoded['__type'] ?? '') : '',
        'error_message' => is_array($decoded) ? (string)($decoded['message'] ?? $decoded['Message'] ?? '') : '',
        'response' => is_array($decoded) ? $decoded : ['raw' => $responseBody],
    ];
}
