<?php
/**
 * send-healthcare-quote.php
 * Handles healthcare and hospital service inquiries.
 */

require_once __DIR__ . '/includes/database.php';

$toEmail = 'service@tablet-masters.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: healthcare-hospitals.php#healthcare-quote');
    exit;
}

function clean($val) {
    return trim(strip_tags((string)($val ?? '')));
}

function buildHealthcareRedirect(array $params = []): string {
    $base = [
        'organization' => clean($_POST['organization_name'] ?? ''),
        'contact' => clean($_POST['contact_name'] ?? ''),
        'email' => clean($_POST['email'] ?? ''),
        'phone' => clean($_POST['phone'] ?? ''),
        'location' => clean($_POST['location'] ?? ''),
        'units' => clean($_POST['units_departments'] ?? ''),
        'devices' => clean($_POST['device_count'] ?? ''),
        'program' => clean($_POST['program'] ?? 'operations'),
        'use_case' => clean($_POST['use_case'] ?? ''),
        'support_level' => clean($_POST['support_level'] ?? 'managed'),
    ];

    $query = array_merge($base, $params);
    $query = array_filter($query, static function ($value) {
        return $value !== '' && $value !== null;
    });

    return '/healthcare-hospitals.php' . ($query ? '?' . http_build_query($query) : '') . '#healthcare-quote';
}

$organizationName = clean($_POST['organization_name'] ?? '');
$contactName = clean($_POST['contact_name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$location = clean($_POST['location'] ?? '');
$unitsDepartments = clean($_POST['units_departments'] ?? '');
$deviceCount = clean($_POST['device_count'] ?? '');
$program = clean($_POST['program'] ?? 'operations');
$useCase = clean($_POST['use_case'] ?? '');
$supportLevel = clean($_POST['support_level'] ?? 'managed');
$requirements = clean($_POST['requirements'] ?? '');
$notes = clean($_POST['notes'] ?? '');

$allowedPrograms = ['operations', 'patient', 'enterprise'];
$allowedSupportLevels = ['managed', 'deployment', 'not-sure'];

if (
    $organizationName === '' ||
    $contactName === '' ||
    $location === '' ||
    $unitsDepartments === '' ||
    $useCase === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: ' . buildHealthcareRedirect(['err' => 1]));
    exit;
}

if (!ctype_digit($deviceCount) || (int)$deviceCount < 1) {
    header('Location: ' . buildHealthcareRedirect(['err' => 1]));
    exit;
}

if (!in_array($program, $allowedPrograms, true)) {
    $program = 'operations';
}

if (!in_array($supportLevel, $allowedSupportLevels, true)) {
    $supportLevel = 'managed';
}

$programLabels = [
    'operations' => 'Hospital Operations',
    'patient' => 'Patient Experience',
    'enterprise' => 'Enterprise Health Systems',
];

$supportLabels = [
    'managed' => 'Ongoing managed support',
    'deployment' => 'Deployment help only',
    'not-sure' => 'Not sure yet',
];

$ref = 'HLTH-' . strtoupper(substr(md5($email . time()), 0, 8));

try {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS healthcare_inquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ref TEXT UNIQUE,
        organization_name TEXT,
        contact_name TEXT,
        email TEXT,
        phone TEXT,
        location TEXT,
        units_departments TEXT,
        device_count INTEGER,
        program TEXT,
        use_case TEXT,
        support_level TEXT,
        requirements TEXT,
        notes TEXT,
        status TEXT DEFAULT 'new',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $db->prepare("
        INSERT INTO healthcare_inquiries
          (ref, organization_name, contact_name, email, phone, location, units_departments, device_count,
           program, use_case, support_level, requirements, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $ref,
        $organizationName,
        $contactName,
        $email,
        $phone,
        $location,
        $unitsDepartments,
        (int)$deviceCount,
        $program,
        $useCase,
        $supportLevel,
        $requirements,
        $notes,
    ]);
} catch (\Exception $e) {
    // If database storage is unavailable, still try to email the inquiry.
}

$adminSubject = "New Healthcare Inquiry [{$ref}] - {$organizationName}";
$adminMessage = "New healthcare service inquiry submitted via tablet-masters.com\n\n"
    . "Inquiry Ref:      {$ref}\n"
    . "Organization:     {$organizationName}\n"
    . "Contact:          {$contactName}\n"
    . "Email:            {$email}\n"
    . "Phone:            {$phone}\n"
    . "Location:         {$location}\n"
    . "Units/Departments: {$unitsDepartments}\n"
    . "Device Count:     {$deviceCount}\n"
    . "Program:          " . $programLabels[$program] . "\n"
    . "Support Level:    " . $supportLabels[$supportLevel] . "\n"
    . "Primary Use Case: {$useCase}\n\n"
    . "Requirements:\n" . ($requirements ?: 'None provided') . "\n\n"
    . "Additional Notes:\n" . ($notes ?: 'None provided') . "\n";

$adminHeaders = "From: service@tablet-masters.com\r\n";
$adminHeaders .= "Reply-To: {$email}\r\n";
$adminHeaders .= "X-Mailer: PHP/" . phpversion();

mail($toEmail, $adminSubject, $adminMessage, $adminHeaders);

$customerSubject = "Healthcare Inquiry Received [{$ref}] - Tablet Masters";
$customerMessage = "Hi {$contactName},\n\n"
    . "Thanks for contacting Tablet Masters about healthcare and hospital tablet support.\n\n"
    . "Here is the summary we received:\n"
    . "Organization:      {$organizationName}\n"
    . "Location:          {$location}\n"
    . "Units/Departments: {$unitsDepartments}\n"
    . "Device Count:      {$deviceCount}\n"
    . "Program:           " . $programLabels[$program] . "\n"
    . "Support Level:     " . $supportLabels[$supportLevel] . "\n"
    . "Primary Use Case:  {$useCase}\n\n"
    . "A Tablet Masters team member will review the request and follow up with next steps.\n\n"
    . "- The Tablet Masters Team\n"
    . "https://tablet-masters.com";

$customerHeaders = "From: service@tablet-masters.com\r\n";
$customerHeaders .= "Reply-To: service@tablet-masters.com\r\n";
$customerHeaders .= "X-Mailer: PHP/" . phpversion();

mail($email, $customerSubject, $customerMessage, $customerHeaders);

header('Location: /healthcare-hospitals.php?sent=1#healthcare-quote');
exit;
