<?php
/**
 * Tablet Masters - AWS SNS webhook for SMS delivery status events
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
$expectedTopicArn = defined('AWS_SNS_STATUS_TOPIC_ARN') ? (string)AWS_SNS_STATUS_TOPIC_ARN : '';
if ($expectedTopicArn === '') {
    http_response_code(503);
    echo json_encode(['error' => 'SMS status topic not configured']);
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

tmRecordSmsStatusCallback([
    'message_uuid' => (string)($message['messageId'] ?? ''),
    'status' => (string)($message['messageStatus'] ?? ($message['eventType'] ?? '')),
    'to' => (string)($message['destinationPhoneNumber'] ?? ''),
    'from' => (string)($message['originationPhoneNumber'] ?? ''),
    'error_message' => (string)($message['messageStatusDescription'] ?? ''),
]);

echo json_encode(['received' => true]);
