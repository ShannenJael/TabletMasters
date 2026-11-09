<?php
/** Shared, bounded Stripe transport. Credentials come only from server config. */
function tmStripeRequest(string $method, string $endpoint, ?array $data = null): ?array
{
    if (!defined('STRIPE_SECRET_KEY') || STRIPE_SECRET_KEY === '') return null;

    $headers = "Authorization: Bearer " . STRIPE_SECRET_KEY . "\r\nAccept: application/json\r\n";
    $options = [
        'method' => $method,
        'timeout' => 15,
        'ignore_errors' => false,
        'follow_location' => 0,
    ];
    if ($data !== null) {
        $options['content'] = http_build_query($data);
        $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
    }
    $options['header'] = $headers;
    $result = @file_get_contents('https://api.stripe.com/v1/' . $endpoint, false, stream_context_create(['http' => $options]));
    if ($result === false) return null;
    $response = json_decode($result, true);
    return is_array($response) && !isset($response['error']) ? $response : null;
}
