<?php
/**
 * Tablet Masters - AWS SNS HTTPS endpoint helpers
 * Verifies SNS message signatures and handles subscription confirmation.
 */

function tmAwsSnsReadEnvelope(): ?array
{
    $raw = file_get_contents('php://input');
    if (!is_string($raw) || $raw === '') {
        return null;
    }

    $envelope = json_decode($raw, true);
    return is_array($envelope) ? $envelope : null;
}

function tmAwsSnsUrlIsTrusted(string $url): bool
{
    return (bool)preg_match('#^https://sns\.[a-zA-Z0-9-]+\.amazonaws\.com/#', $url);
}

function tmAwsSnsBuildStringToSign(array $envelope): ?string
{
    $type = (string)($envelope['Type'] ?? '');

    if ($type === 'Notification') {
        $fields = ['Message', 'MessageId', 'Subject', 'Timestamp', 'TopicArn', 'Type'];
    } elseif ($type === 'SubscriptionConfirmation' || $type === 'UnsubscribeConfirmation') {
        $fields = ['Message', 'MessageId', 'SubscribeURL', 'Timestamp', 'Token', 'TopicArn', 'Type'];
    } else {
        return null;
    }

    $lines = [];
    foreach ($fields as $field) {
        if ($field === 'Subject' && !array_key_exists('Subject', $envelope)) {
            continue;
        }
        if (!array_key_exists($field, $envelope)) {
            return null;
        }
        $lines[] = $field;
        $lines[] = (string)$envelope[$field];
    }

    return implode("\n", $lines) . "\n";
}

function tmAwsSnsVerifySignature(array $envelope): bool
{
    $signature = (string)($envelope['Signature'] ?? '');
    $signingCertUrl = (string)($envelope['SigningCertURL'] ?? '');
    $signatureVersion = (string)($envelope['SignatureVersion'] ?? '1');

    if ($signature === '' || $signingCertUrl === '') {
        return false;
    }

    if (!tmAwsSnsUrlIsTrusted($signingCertUrl)) {
        return false;
    }

    $stringToSign = tmAwsSnsBuildStringToSign($envelope);
    if ($stringToSign === null) {
        return false;
    }

    $certContext = stream_context_create(['http' => ['timeout' => 10]]);
    $certPem = @file_get_contents($signingCertUrl, false, $certContext);
    if (!is_string($certPem) || $certPem === '') {
        return false;
    }

    $publicKey = openssl_pkey_get_public($certPem);
    if ($publicKey === false) {
        return false;
    }

    $algorithm = $signatureVersion === '2' ? OPENSSL_ALGO_SHA256 : OPENSSL_ALGO_SHA1;
    $decodedSignature = base64_decode($signature, true);
    if ($decodedSignature === false) {
        return false;
    }

    $result = openssl_verify($stringToSign, $decodedSignature, $publicKey, $algorithm);

    return $result === 1;
}

function tmAwsSnsConfirmSubscription(array $envelope, string $expectedTopicArn): bool
{
    $topicArn = (string)($envelope['TopicArn'] ?? '');
    $subscribeUrl = (string)($envelope['SubscribeURL'] ?? '');

    if ($topicArn === '' || $expectedTopicArn === '' || $topicArn !== $expectedTopicArn) {
        return false;
    }

    if ($subscribeUrl === '' || !tmAwsSnsUrlIsTrusted($subscribeUrl)) {
        return false;
    }

    $context = stream_context_create(['http' => ['timeout' => 10]]);
    $result = @file_get_contents($subscribeUrl, false, $context);

    return $result !== false;
}
