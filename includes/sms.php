<?php
/**
 * Tablet Masters - SMS configuration, provider selection, and shared logging.
 */

require_once __DIR__ . '/aws-sms.php';
require_once __DIR__ . '/twilio.php';

$configFile = __DIR__ . '/config.php';
if (is_file($configFile)) {
    require_once $configFile;
}

function tmSmsConfig(): array
{
    $provider = strtolower(tmConfigValue('TM_SMS_PROVIDER', 'TM_SMS_PROVIDER'));
    if ($provider === '') {
        $twilio = tmTwilioConfig();
        $hasTwilioCredentials = $twilio['account_sid'] !== '' || $twilio['auth_token'] !== '';
        $provider = !$hasTwilioCredentials && tmAwsSmsConfig()['origination_number'] !== '' ? 'aws' : 'twilio';
    }

    return [
        'enabled' => tmConfigFlag('TM_SMS_ENABLED', true),
        'provider' => $provider,
        'support_alert_number' => tmConfigValue('TM_SUPPORT_ALERT_NUMBER', 'TM_SUPPORT_ALERT_NUMBER'),
        'default_country' => strtoupper(tmConfigValue('TM_SMS_DEFAULT_COUNTRY', 'TM_SMS_DEFAULT_COUNTRY') ?: 'US'),
    ];
}

function tmSendSmsMessage(string $to, string $body, array $options = []): array
{
    $config = tmSmsConfig();
    $provider = $config['provider'];
    $normalizedTo = tmNormalizePhoneNumber($to, $config['default_country']);
    if ($normalizedTo === null) {
        return tmSmsLogResult($provider, ['ok' => false, 'skipped' => true, 'reason' => 'invalid_to', 'to' => $to]);
    }

    if (!$config['enabled']) {
        $result = ['ok' => false, 'skipped' => true, 'reason' => 'not_configured', 'to' => $normalizedTo];
    } elseif ($provider === 'twilio') {
        $result = tmTwilioSendMessage($normalizedTo, $body);
    } elseif ($provider === 'aws') {
        $result = tmAwsSendSmsMessage($normalizedTo, $body, $options);
    } else {
        $result = ['ok' => false, 'skipped' => true, 'reason' => 'invalid_provider', 'to' => $normalizedTo];
    }

    return tmSmsLogResult($provider, $result);
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

function tmSmsLogResult(string $provider, array $result): array
{
    $logDir = __DIR__ . '/../data/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $line = [
        'time' => date('Y-m-d H:i:s'),
        'provider' => $provider,
        'ok' => (bool)($result['ok'] ?? false),
        'skipped' => (bool)($result['skipped'] ?? false),
        'reason' => (string)($result['reason'] ?? ''),
        'status_code' => (int)($result['status_code'] ?? 0),
        'to' => (string)($result['to'] ?? ''),
        'message_uuid' => (string)($result['message_uuid'] ?? $result['message_sid'] ?? ''),
        'provider_status' => (string)($result['provider_status'] ?? ''),
        'error_code' => (string)($result['error_code'] ?? ''),
        'error_message' => (string)($result['error_message'] ?? ''),
    ];

    file_put_contents(
        $logDir . '/sms_' . date('Y-m-d') . '.log',
        json_encode($line, JSON_UNESCAPED_SLASHES) . PHP_EOL,
        FILE_APPEND | LOCK_EX
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
