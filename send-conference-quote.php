<?php
/**
 * send-conference-quote.php
 * Handles business conference quote requests.
 */

require_once __DIR__ . '/includes/database.php';

$toEmail = 'service@tablet-masters.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: business-conferences.php#conference-quote');
    exit;
}

function clean($val) {
    return trim(strip_tags((string)($val ?? '')));
}

function buildConferenceRedirect(array $params = []): string {
    $base = [
        'company' => clean($_POST['company_name'] ?? ''),
        'contact' => clean($_POST['contact_name'] ?? ''),
        'email' => clean($_POST['email'] ?? ''),
        'phone' => clean($_POST['phone'] ?? ''),
        'event' => clean($_POST['event_name'] ?? ''),
        'location' => clean($_POST['event_location'] ?? ''),
        'dates' => clean($_POST['event_dates'] ?? ''),
        'count' => clean($_POST['tablet_count'] ?? ''),
        'package' => clean($_POST['package'] ?? 'managed'),
        'use_case' => clean($_POST['use_case'] ?? ''),
        'support_level' => clean($_POST['support_level'] ?? 'remote'),
    ];

    $query = array_merge($base, $params);
    $query = array_filter($query, static function ($value) {
        return $value !== '' && $value !== null;
    });

    return '/business-conferences.php' . ($query ? '?' . http_build_query($query) : '') . '#conference-quote';
}

$companyName = clean($_POST['company_name'] ?? '');
$contactName = clean($_POST['contact_name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$eventName = clean($_POST['event_name'] ?? '');
$eventLocation = clean($_POST['event_location'] ?? '');
$eventDates = clean($_POST['event_dates'] ?? '');
$tabletCount = clean($_POST['tablet_count'] ?? '');
$package = clean($_POST['package'] ?? 'managed');
$supportLevel = clean($_POST['support_level'] ?? 'remote');
$useCase = clean($_POST['use_case'] ?? '');
$appRequirements = clean($_POST['app_requirements'] ?? '');
$notes = clean($_POST['notes'] ?? '');

$allowedPackages = ['starter', 'managed', 'enterprise'];
$allowedSupportLevels = ['remote', 'onsite', 'not-sure'];

if (
    $companyName === '' ||
    $contactName === '' ||
    $eventName === '' ||
    $eventLocation === '' ||
    $eventDates === '' ||
    $useCase === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: ' . buildConferenceRedirect(['err' => 1]));
    exit;
}

if (!ctype_digit($tabletCount) || (int)$tabletCount < 1) {
    header('Location: ' . buildConferenceRedirect(['err' => 1]));
    exit;
}

if (!in_array($package, $allowedPackages, true)) {
    $package = 'managed';
}

if (!in_array($supportLevel, $allowedSupportLevels, true)) {
    $supportLevel = 'remote';
}

$packageLabels = [
    'starter' => 'Starter',
    'managed' => 'Managed Event',
    'enterprise' => 'Enterprise Conference',
];

$supportLabels = [
    'remote' => 'Remote support',
    'onsite' => 'On-site coordination',
    'not-sure' => 'Not sure yet',
];

$ref = 'CONF-' . strtoupper(substr(md5($email . time()), 0, 8));

try {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS conference_quotes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ref TEXT UNIQUE,
        company_name TEXT,
        contact_name TEXT,
        email TEXT,
        phone TEXT,
        event_name TEXT,
        event_location TEXT,
        event_dates TEXT,
        tablet_count INTEGER,
        package TEXT,
        use_case TEXT,
        support_level TEXT,
        app_requirements TEXT,
        notes TEXT,
        status TEXT DEFAULT 'new',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $db->prepare("
        INSERT INTO conference_quotes
          (ref, company_name, contact_name, email, phone, event_name, event_location, event_dates,
           tablet_count, package, use_case, support_level, app_requirements, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $ref,
        $companyName,
        $contactName,
        $email,
        $phone,
        $eventName,
        $eventLocation,
        $eventDates,
        (int)$tabletCount,
        $package,
        $useCase,
        $supportLevel,
        $appRequirements,
        $notes,
    ]);
} catch (\Exception $e) {
    // If the database layer is unavailable, still try to email the lead.
}

$adminSubject = "New Conference Quote [{$ref}] - {$eventName}";
$adminMessage = "New business conference quote request submitted via tablet-masters.com\n\n"
    . "Quote Ref:        {$ref}\n"
    . "Company:          {$companyName}\n"
    . "Contact:          {$contactName}\n"
    . "Email:            {$email}\n"
    . "Phone:            {$phone}\n"
    . "Event:            {$eventName}\n"
    . "Location:         {$eventLocation}\n"
    . "Dates:            {$eventDates}\n"
    . "Tablet Count:     {$tabletCount}\n"
    . "Package:          " . $packageLabels[$package] . "\n"
    . "Support Level:    " . $supportLabels[$supportLevel] . "\n"
    . "Primary Use Case: {$useCase}\n\n"
    . "App Requirements:\n" . ($appRequirements ?: 'None provided') . "\n\n"
    . "Additional Notes:\n" . ($notes ?: 'None provided') . "\n";

$adminHeaders = "From: service@tablet-masters.com\r\n";
$adminHeaders .= "Reply-To: {$email}\r\n";
$adminHeaders .= "X-Mailer: PHP/" . phpversion();

mail($toEmail, $adminSubject, $adminMessage, $adminHeaders);

$customerSubject = "Conference Quote Request Received [{$ref}] - Tablet Masters";
$customerMessage = "Hi {$contactName},\n\n"
    . "Thanks for contacting Tablet Masters about conference tablet support.\n\n"
    . "Here is the summary we received:\n"
    . "Company:          {$companyName}\n"
    . "Event:            {$eventName}\n"
    . "Location:         {$eventLocation}\n"
    . "Dates:            {$eventDates}\n"
    . "Tablet Count:     {$tabletCount}\n"
    . "Package:          " . $packageLabels[$package] . "\n"
    . "Support Level:    " . $supportLabels[$supportLevel] . "\n"
    . "Primary Use Case: {$useCase}\n\n"
    . "A Tablet Masters team member will review the request and follow up with next steps.\n\n"
    . "- The Tablet Masters Team\n"
    . "https://tablet-masters.com";

$customerHeaders = "From: service@tablet-masters.com\r\n";
$customerHeaders .= "Reply-To: service@tablet-masters.com\r\n";
$customerHeaders .= "X-Mailer: PHP/" . phpversion();

mail($email, $customerSubject, $customerMessage, $customerHeaders);

header('Location: /business-conferences.php?sent=1#conference-quote');
exit;
