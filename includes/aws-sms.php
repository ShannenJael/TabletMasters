<?php
/**
 * Tablet Masters - AWS End User Messaging (Pinpoint SMS/Voice v2) SMS helper
 * Lightweight SigV4-signed REST client for transactional SMS without requiring Composer.
 */

require_once __DIR__ . '/aws-sigv4.php';

$tmSmsConfigFile = __DIR__ . '/config.php';
if (file_exists($tmSmsConfigFile)) {
    require_once $tmSmsConfigFile;
}

function tmSmsConfig(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'enabled' => tmConfigFlag('TM_SMS_ENABLED', true),
        'region' => tmConfigValue('AWS_REGION', 'AWS_REGION') ?: 'us-east-2',
        'access_key' => tmConfigValue('AWS_ACCESS_KEY_ID', 'AWS_ACCESS_KEY_ID'),
        'secret_key' => tmConfigValue('AWS_SECRET_ACCESS_KEY', 'AWS_SECRET_ACCESS_KEY'),
        'origination_number' => tmConfigValue('AWS_SMS_ORIGINATION_NUMBER', 'AWS_SMS_ORIGINATION_NUMBER'),
        'configuration_set' => tmConfigValue('AWS_SMS_CONFIGURATION_SET', 'AWS_SMS_CONFIGURATION_SET'),
        'support_alert_number' => tmConfigValue('TM_SUPPORT_ALERT_NUMBER', 'TM_SUPPORT_ALERT_NUMBER'),
        'default_country' => strtoupper(tmConfigValue('TM_SMS_DEFAULT_COUNTRY', 'TM_SMS_DEFAULT_COUNTRY') ?: 'US'),
    ];

    return $config;
}

function tmSmsIsReady(): bool
{
    $config = tmSmsConfig();

    if (!$config['enabled']) {
        return false;
    }

    if ($config['access_key'] === '' || $config['secret_key'] === '') {
        return false;
    }

    return $config['origination_number'] !== '';
}

function tmSendSmsMessage(string $to, string $body, array $options = []): array
{
    $config = tmSmsConfig();
    $normalizedTo = tmNormalizePhoneNumber($to, $config['default_country']);

    if ($normalizedTo === null) {
        return tmSmsLogResult([
            'ok' => false,
            'skipped' => true,
            'reason' => 'invalid_to',
            'to' => $to,
            'body' => $body,
        ]);
    }

    if (!tmSmsIsReady()) {
        return tmSmsLogResult([
            'ok' => false,
            'skipped' => true,
            'reason' => 'not_configured',
            'to' => $normalizedTo,
            'body' => $body,
        ]);
    }

    $originationIdentity = trim((string)($options['from'] ?? $config['origination_number']));

    $requestFields = [
        'DestinationPhoneNumber' => $normalizedTo,
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
        return tmSmsLogResult([
            'ok' => false,
            'skipped' => true,
            'reason' => 'invalid_payload',
            'to' => $normalizedTo,
            'body' => $body,
        ]);
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

    return tmSmsLogResult([
        'ok' => $ok,
        'skipped' => false,
        'status_code' => $statusCode,
        'to' => $normalizedTo,
        'body' => $requestFields['MessageBody'],
        'message_uuid' => $messageId,
        'message_sid' => $messageId,
        'provider_status' => '',
        'error_code' => is_array($decoded) ? (string)($decoded['__type'] ?? '') : '',
        'error_message' => is_array($decoded) ? (string)($decoded['message'] ?? $decoded['Message'] ?? '') : '',
        'response' => is_array($decoded) ? $decoded : ['raw' => $responseBody],
    ]);
}

function tmNormalizePhoneNumber(string $phone, string $defaultCountry = 'US'): ?string
{
    $phone = trim($phone);
    if ($phone === '') {
        return null;
    }

    $leadingPlus = strpos($phone, '+') === 0;
    $digits = preg_replace('/\D+/', '', $phone);
    if ($digits === '') {
        return null;
    }

    if ($leadingPlus) {
        return strlen($digits) >= 8 && strlen($digits) <= 15 ? '+' . $digits : null;
    }

    if (strtoupper(trim($defaultCountry)) === 'US') {
        if (strlen($digits) === 10) {
            return '+1' . $digits;
        }
        if (strlen($digits) === 11 && strpos($digits, '1') === 0) {
            return '+' . $digits;
        }
    }

    if (strlen($digits) >= 8 && strlen($digits) <= 15) {
        return '+' . $digits;
    }

    return null;
}

function tmSmsBody(string $body): string
{
    $body = trim(preg_replace('/\s+/', ' ', $body));
    if (function_exists('mb_substr')) {
        return mb_substr($body, 0, 1200);
    }

    return substr($body, 0, 1200);
}

function tmSmsLogResult(array $result): array
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $line = [
        'time' => date('Y-m-d H:i:s'),
        'provider' => 'aws',
        'ok' => (bool)($result['ok'] ?? false),
        'skipped' => (bool)($result['skipped'] ?? false),
        'reason' => (string)($result['reason'] ?? ''),
        'status_code' => (int)($result['status_code'] ?? 0),
        'to' => (string)($result['to'] ?? ''),
        'message_uuid' => (string)($result['message_uuid'] ?? ''),
        'provider_status' => (string)($result['provider_status'] ?? ''),
        'error_code' => (string)($result['error_code'] ?? ''),
        'error_message' => (string)($result['error_message'] ?? ''),
    ];

    file_put_contents(
        $logDir . '/sms_' . date('Y-m-d') . '.log',
        json_encode($line, JSON_UNESCAPED_SLASHES) . PHP_EOL,
        FILE_APPEND
    );

    return $result;
}

function tmEnvFlag(string $name, bool $default = false): bool
{
    $value = getenv($name);
    if ($value === false || $value === '') {
        return $default;
    }

    return in_array(strtolower((string)$value), ['1', 'true', 'yes', 'on'], true);
}

function tmConfigFlag(string $name, bool $default = false): bool
{
    if (defined($name)) {
        $value = constant($name);
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower((string)$value), ['1', 'true', 'yes', 'on'], true);
    }

    return tmEnvFlag($name, $default);
}

function tmConfigValue(string $constantName, string $envName): string
{
    if (defined($constantName)) {
        return trim((string)constant($constantName));
    }

    $value = getenv($envName);
    return $value === false ? '' : trim((string)$value);
}

function tmHttpStatusCode(array $headers): int
{
    foreach ($headers as $header) {
        if (preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $matches)) {
            return (int)$matches[1];
        }
    }

    return 0;
}
