<?php
/**
 * Tablet Masters - Twilio SMS helper
 * Lightweight REST client for transactional SMS without requiring Composer.
 */

$tmTwilioConfigFile = __DIR__ . '/config.php';
if (file_exists($tmTwilioConfigFile)) {
    require_once $tmTwilioConfigFile;
}

function tmTwilioConfig(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'enabled' => tmConfigFlag('TM_SMS_ENABLED', true),
        'account_sid' => tmConfigValue('TWILIO_ACCOUNT_SID', 'TWILIO_ACCOUNT_SID'),
        'auth_token' => tmConfigValue('TWILIO_AUTH_TOKEN', 'TWILIO_AUTH_TOKEN'),
        'from_number' => tmConfigValue('TWILIO_FROM_NUMBER', 'TWILIO_FROM_NUMBER'),
        'messaging_service_sid' => tmConfigValue('TWILIO_MESSAGING_SERVICE_SID', 'TWILIO_MESSAGING_SERVICE_SID'),
        'support_alert_number' => tmConfigValue('TM_SUPPORT_ALERT_NUMBER', 'TM_SUPPORT_ALERT_NUMBER'),
        'status_callback_url' => tmConfigValue('TWILIO_STATUS_CALLBACK_URL', 'TWILIO_STATUS_CALLBACK_URL'),
        'default_country' => strtoupper(tmConfigValue('TM_SMS_DEFAULT_COUNTRY', 'TM_SMS_DEFAULT_COUNTRY') ?: 'PH'),
    ];

    return $config;
}

function tmTwilioIsReady(): bool
{
    $config = tmTwilioConfig();

    if (!$config['enabled']) {
        return false;
    }

    if ($config['account_sid'] === '' || $config['auth_token'] === '') {
        return false;
    }

    return $config['messaging_service_sid'] !== '' || $config['from_number'] !== '';
}

function tmTwilioSendMessage(string $to, string $body, array $options = []): array
{
    $config = tmTwilioConfig();
    $normalizedTo = tmNormalizePhoneNumber($to, $config['default_country']);

    if ($normalizedTo === null) {
        return tmTwilioLogResult([
            'ok' => false,
            'skipped' => true,
            'reason' => 'invalid_to',
            'to' => $to,
            'body' => $body,
        ]);
    }

    if (!tmTwilioIsReady()) {
        return tmTwilioLogResult([
            'ok' => false,
            'skipped' => true,
            'reason' => 'not_configured',
            'to' => $normalizedTo,
            'body' => $body,
        ]);
    }

    $postFields = [
        'To' => $normalizedTo,
        'Body' => tmSmsBody($body),
    ];

    $messagingServiceSid = trim((string)($options['messaging_service_sid'] ?? $config['messaging_service_sid']));
    $fromNumber = trim((string)($options['from'] ?? $config['from_number']));
    if ($messagingServiceSid !== '') {
        $postFields['MessagingServiceSid'] = $messagingServiceSid;
    } elseif ($fromNumber !== '') {
        $postFields['From'] = $fromNumber;
    }

    $statusCallback = trim((string)($options['status_callback'] ?? $config['status_callback_url']));
    if ($statusCallback !== '') {
        $postFields['StatusCallback'] = $statusCallback;
    }

    $endpoint = 'https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode($config['account_sid']) . '/Messages.json';
    $authHeader = 'Authorization: Basic ' . base64_encode($config['account_sid'] . ':' . $config['auth_token']);
    $payload = http_build_query($postFields);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' =>
                $authHeader . "\r\n" .
                "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($payload) . "\r\n",
            'content' => $payload,
            'ignore_errors' => true,
            'timeout' => 20,
        ],
    ]);

    $responseBody = @file_get_contents($endpoint, false, $context);
    $decoded = is_string($responseBody) ? json_decode($responseBody, true) : null;
    $statusCode = tmHttpStatusCode($http_response_header ?? []);
    $ok = $statusCode >= 200 && $statusCode < 300 && is_array($decoded) && !empty($decoded['sid']);

    return tmTwilioLogResult([
        'ok' => $ok,
        'skipped' => false,
        'status_code' => $statusCode,
        'to' => $normalizedTo,
        'body' => $postFields['Body'],
        'message_sid' => is_array($decoded) ? (string)($decoded['sid'] ?? '') : '',
        'twilio_status' => is_array($decoded) ? (string)($decoded['status'] ?? '') : '',
        'error_code' => is_array($decoded) ? (string)($decoded['code'] ?? '') : '',
        'error_message' => is_array($decoded) ? (string)($decoded['message'] ?? '') : '',
        'response' => is_array($decoded) ? $decoded : ['raw' => $responseBody],
    ]);
}

function tmNormalizePhoneNumber(string $phone, string $defaultCountry = 'PH'): ?string
{
    $phone = trim($phone);
    if ($phone === '') {
        return null;
    }

    $defaultCountry = strtoupper(trim($defaultCountry));
    $leadingPlus = strpos($phone, '+') === 0;
    $digits = preg_replace('/\D+/', '', $phone);
    if ($digits === '') {
        return null;
    }

    if ($leadingPlus) {
        return strlen($digits) >= 8 && strlen($digits) <= 15 ? '+' . $digits : null;
    }

    if ($defaultCountry === 'PH') {
        if (strpos($digits, '63') === 0 && strlen($digits) === 12) {
            return '+' . $digits;
        }
        if (strpos($digits, '09') === 0 && strlen($digits) === 11) {
            return '+63' . substr($digits, 1);
        }
        if (strpos($digits, '9') === 0 && strlen($digits) === 10) {
            return '+63' . $digits;
        }
    }

    if ($defaultCountry === 'US') {
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

function tmTwilioLogResult(array $result): array
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $line = [
        'time' => date('Y-m-d H:i:s'),
        'ok' => (bool)($result['ok'] ?? false),
        'skipped' => (bool)($result['skipped'] ?? false),
        'reason' => (string)($result['reason'] ?? ''),
        'status_code' => (int)($result['status_code'] ?? 0),
        'to' => (string)($result['to'] ?? ''),
        'message_sid' => (string)($result['message_sid'] ?? ''),
        'twilio_status' => (string)($result['twilio_status'] ?? ''),
        'error_code' => (string)($result['error_code'] ?? ''),
        'error_message' => (string)($result['error_message'] ?? ''),
    ];

    file_put_contents(
        $logDir . '/twilio_' . date('Y-m-d') . '.log',
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
