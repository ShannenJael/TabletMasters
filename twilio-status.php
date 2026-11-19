<?php
require_once __DIR__ . '/includes/notifications.php';

$payload = tmTwilioWebhookPayload('status_url');
if (($payload['MessageSid'] ?? '') === '' || ($payload['MessageStatus'] ?? '') === '') {
    http_response_code(400);
    exit;
}

tmRecordSmsStatusCallback($payload, 'twilio');
http_response_code(204);
