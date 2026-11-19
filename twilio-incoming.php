<?php
require_once __DIR__ . '/includes/notifications.php';

$payload = tmTwilioWebhookPayload('incoming_url');
$from = tmNormalizePhoneNumber($payload['From'] ?? '');
if ($from === null || ($payload['To'] ?? '') !== tmTwilioConfig()['from_number'] || ($payload['MessageSid'] ?? '') === '') {
    http_response_code(400);
    exit;
}

$isNew = tmRecordInboundSms($payload, 'twilio');
$body = trim($payload['Body'] ?? '');
$isKeyword = isset($payload['OptOutType']) || in_array(strtoupper($body), [
    'STOP', 'STOPALL', 'UNSUBSCRIBE', 'CANCEL', 'END', 'QUIT', 'REVOKE', 'OPTOUT',
    'START', 'UNSTOP', 'HELP', 'INFO',
], true);

header('Content-Type: text/xml; charset=UTF-8');
if (!$isNew || $isKeyword) {
    echo '<Response/>';
    exit;
}

$sms = tmSmsConfig();
$alertNumber = tmNormalizePhoneNumber($sms['support_alert_number'], $sms['default_country']);
if ($alertNumber !== null && $alertNumber !== $from) {
    tmSendSmsMessage($alertNumber, "Tablet Masters reply from {$from}: " . ($body !== '' ? $body : '[empty message]'));
}

echo '<Response><Message>Thanks for messaging Tablet Masters. Our team received your message and will follow up as soon as possible.</Message></Response>';
