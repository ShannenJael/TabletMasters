<?php
require_once __DIR__ . '/includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

tmRecordTwilioStatusCallback($_POST);

header('Content-Type: application/json');
echo json_encode(['received' => true]);
