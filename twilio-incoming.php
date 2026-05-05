<?php
require_once __DIR__ . '/includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Method Not Allowed';
    exit;
}

tmRecordInboundSms($_POST);

$from = tmNormalizePhoneNumber((string)($_POST['From'] ?? '')) ?: (string)($_POST['From'] ?? '');
$alertNumber = tmTwilioConfig()['support_alert_number'];
if ($alertNumber !== '' && $from !== '' && $from !== tmNormalizePhoneNumber($alertNumber)) {
    $body = trim((string)($_POST['Body'] ?? ''));
    $forward = "Tablet Masters reply from {$from}: " . ($body !== '' ? $body : '[empty message]');
    tmTwilioSendMessage($alertNumber, $forward);
}

header('Content-Type: text/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<Response><Message>Thanks for messaging Tablet Masters. Our team received your message and will follow up as soon as possible.</Message></Response>';
