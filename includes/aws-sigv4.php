<?php
/**
 * Tablet Masters - AWS Signature Version 4 request signer
 *
 * Minimal, dependency-free SigV4 implementation for signing a single JSON
 * POST request (used by includes/aws-sms.php). Not a general-purpose SDK.
 */

function tmAwsSigV4Sign(
    string $method,
    string $host,
    string $path,
    string $queryString,
    array $headers,
    string $payload,
    string $service,
    string $region,
    string $accessKey,
    string $secretKey,
    string $sessionToken = ''
): array {
    $amzDate = gmdate('Ymd\THis\Z');
    $dateStamp = gmdate('Ymd');

    $headers['host'] = $host;
    $headers['x-amz-date'] = $amzDate;
    if ($sessionToken !== '') {
        $headers['x-amz-security-token'] = $sessionToken;
    }

    $normalizedHeaders = [];
    foreach ($headers as $name => $value) {
        $normalizedHeaders[strtolower($name)] = trim($value);
    }
    ksort($normalizedHeaders);

    $canonicalHeaders = '';
    foreach ($normalizedHeaders as $name => $value) {
        $canonicalHeaders .= $name . ':' . $value . "\n";
    }
    $signedHeaders = implode(';', array_keys($normalizedHeaders));

    $payloadHash = hash('sha256', $payload);

    $canonicalRequest = implode("\n", [
        $method,
        $path,
        $queryString,
        $canonicalHeaders,
        $signedHeaders,
        $payloadHash,
    ]);

    $credentialScope = "{$dateStamp}/{$region}/{$service}/aws4_request";
    $stringToSign = implode("\n", [
        'AWS4-HMAC-SHA256',
        $amzDate,
        $credentialScope,
        hash('sha256', $canonicalRequest),
    ]);

    $kDate = hash_hmac('sha256', $dateStamp, 'AWS4' . $secretKey, true);
    $kRegion = hash_hmac('sha256', $region, $kDate, true);
    $kService = hash_hmac('sha256', $service, $kRegion, true);
    $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
    $signature = hash_hmac('sha256', $stringToSign, $kSigning);

    $authorization = "AWS4-HMAC-SHA256 Credential={$accessKey}/{$credentialScope}, "
        . "SignedHeaders={$signedHeaders}, Signature={$signature}";

    $normalizedHeaders['authorization'] = $authorization;

    return $normalizedHeaders;
}
