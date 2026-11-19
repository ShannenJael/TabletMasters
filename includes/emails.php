<?php

function tmCleanEmailHeader(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

function tmSendShipmentEmail(string $email, string $number, string $status, string $link, string $tracking, ?string $carrierUrl): bool
{
    $from = tmCleanEmailHeader(defined('TM_FROM_EMAIL') ? TM_FROM_EMAIL : 'Tabletmasters@clouddogg.com');
    if (!function_exists('mail') || !filter_var($from, FILTER_VALIDATE_EMAIL)) return false;

    $message = "Tablet Masters order {$number}\nShipment status: {$status}\n";
    if ($tracking !== '') $message .= "Tracking number: {$tracking}\n";
    if ($carrierUrl !== null) $message .= "Carrier tracking: {$carrierUrl}\n";
    $message .= "\nCheck your shipment using your checkout email and order number:\n{$link}\n";

    return @mail($email, "Shipment update for {$number} - Tablet Masters", $message, "From: {$from}\r\nReply-To: {$from}");
}

function sendDeviceRegistrationEmail(
    string $email,
    string $name,
    string $brand,
    string $model,
    string $serial,
    string $registrationNumber,
    string $plan,
    string $source
): void {
    $adminEmail = defined('TM_ADMIN_EMAIL') ? TM_ADMIN_EMAIL : 'Tabletmasters@clouddogg.com';
    $fromEmail = defined('TM_FROM_EMAIL') ? TM_FROM_EMAIL : 'Tabletmasters@clouddogg.com';

    $safeCustomerEmail = tmCleanEmailHeader($email);
    $safeCustomerName = tmCleanEmailHeader($name);
    $safeAdminEmail = tmCleanEmailHeader($adminEmail);
    $safeFromEmail = tmCleanEmailHeader($fromEmail);

    $planLabel = $plan === 'none' ? 'No protection plan selected' : ucfirst($plan);
    $sourceLabel = $source === 'external' ? 'External purchase' : 'Tablet Masters purchase';

    $adminSubject = "New Device Registration [{$registrationNumber}]";
    $adminMessage = "New device registration submitted via tablet-masters.com\n\n"
        . "Registration #: {$registrationNumber}\n"
        . "Name:           {$name}\n"
        . "Email:          {$email}\n"
        . "Device:         {$brand} {$model}\n"
        . "Serial:         {$serial}\n"
        . "Purchase:       {$sourceLabel}\n"
        . "Plan:           {$planLabel}\n";

    $adminHeaders = "From: {$safeFromEmail}\r\n"
        . "Reply-To: {$safeCustomerEmail}\r\n"
        . "X-Mailer: PHP/" . phpversion();

    mail($safeAdminEmail, $adminSubject, $adminMessage, $adminHeaders);

    $customerSubject = "Device Registration Confirmed [{$registrationNumber}] - Tablet Masters";
    $customerMessage = "Hi {$name},\n\n"
        . "Your Tablet Masters device registration has been received.\n\n"
        . "Registration #: {$registrationNumber}\n"
        . "Device:         {$brand} {$model}\n"
        . "Serial:         {$serial}\n"
        . "Purchase:       {$sourceLabel}\n"
        . "Plan:           {$planLabel}\n\n"
        . "Keep this registration number for future support or repair requests.\n\n"
        . "- The Tablet Masters Team\n"
        . "https://tablet-masters.com";

    $customerHeaders = "From: {$safeFromEmail}\r\n"
        . "Reply-To: {$safeFromEmail}\r\n"
        . "X-Mailer: PHP/" . phpversion();

    mail($safeCustomerEmail, $customerSubject, $customerMessage, $customerHeaders);
}
