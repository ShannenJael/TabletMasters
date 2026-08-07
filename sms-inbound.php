<?php
/**
 * Tablet Masters - AWS SNS webhook for inbound customer SMS replies
 */

require_once __DIR__ . '/includes/notifications.php';
require_once __DIR__ . '/includes/aws-sns.php';

header('Content-Type: application/json');

$envelope = tmAwsSnsReadEnvelope();
if ($envelope === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

if (!tmAwsSnsVerifySignature($envelope)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

$type = (string)($envelope['Type'] ?? '');
$expectedTopicArn = defined('AWS_SNS_INBOUND_TOPIC_ARN') ? (string)AWS_SNS_INBOUND_TOPIC_ARN : '';
if ($expectedTopicArn === '') {
    http_response_code(503);
    echo json_encode(['error' => 'SMS inbound topic not configured']);
    exit;
}

if ($type === 'SubscriptionConfirmation' || $type === 'UnsubscribeConfirmation') {
    tmAwsSnsConfirmSubscription($envelope, $expectedTopicArn);
    echo json_encode(['received' => true]);
    exit;
}

if ($type !== 'Notification' || (string)($envelope['TopicArn'] ?? '') !== $expectedTopicArn) {
    http_response_code(400);
    echo json_encode(['error' => 'Unsupported message']);
    exit;
}

$message = json_decode((string)($envelope['Message'] ?? ''), true);
if (!is_array($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid message body']);
    exit;
}

$fromRaw = (string)($message['originationNumber'] ?? '');
$toRaw = (string)($message['destinationNumber'] ?? '');
$body = (string)($message['messageBody'] ?? '');
$from = tmNormalizePhoneNumber($fromRaw) ?: $fromRaw;
$to = tmNormalizePhoneNumber($toRaw) ?: $toRaw;

tmRecordInboundSms([
    'from' => $from,
    'to' => $to,
    'body' => $body,
    'message_uuid' => (string)($message['inboundMessageId'] ?? ''),
]);

$alertNumber = tmSmsConfig()['support_alert_number'];
if ($alertNumber !== '' && $from !== '' && $from !== tmNormalizePhoneNumber($alertNumber)) {
    $forward = "Tablet Masters reply from {$from}: " . ($body !== '' ? $body : '[empty message]');
    tmSendSmsMessage($alertNumber, $forward);
}

if ($from !== '') {
    $reply = 'Thanks for messaging Tablet Masters. Our team received your message and will follow up as soon as possible.';
    tmSendSmsMessage($from, $reply);
}

echo json_encode(['received' => true]);
