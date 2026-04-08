<?php
/**
 * send-school-quote.php
 * Handles school service inquiries.
 */

require_once __DIR__ . '/includes/database.php';

$toEmail = 'service@tablet-masters.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: schools.php#school-quote');
    exit;
}

function clean($val) {
    return trim(strip_tags((string)($val ?? '')));
}

function buildSchoolRedirect(array $params = []): string {
    $base = [
        'school' => clean($_POST['school_name'] ?? ''),
        'contact' => clean($_POST['contact_name'] ?? ''),
        'email' => clean($_POST['email'] ?? ''),
        'phone' => clean($_POST['phone'] ?? ''),
        'city' => clean($_POST['city_state'] ?? ''),
        'students' => clean($_POST['student_count'] ?? ''),
        'devices' => clean($_POST['device_count'] ?? ''),
        'program' => clean($_POST['program'] ?? 'managed'),
        'use_case' => clean($_POST['use_case'] ?? ''),
        'support_level' => clean($_POST['support_level'] ?? 'managed'),
    ];

    $query = array_merge($base, $params);
    $query = array_filter($query, static function ($value) {
        return $value !== '' && $value !== null;
    });

    return '/schools.php' . ($query ? '?' . http_build_query($query) : '') . '#school-quote';
}

$schoolName = clean($_POST['school_name'] ?? '');
$contactName = clean($_POST['contact_name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$cityState = clean($_POST['city_state'] ?? '');
$studentCount = clean($_POST['student_count'] ?? '');
$deviceCount = clean($_POST['device_count'] ?? '');
$program = clean($_POST['program'] ?? 'managed');
$useCase = clean($_POST['use_case'] ?? '');
$supportLevel = clean($_POST['support_level'] ?? 'managed');
$requirements = clean($_POST['requirements'] ?? '');
$notes = clean($_POST['notes'] ?? '');

$allowedPrograms = ['launch', 'managed', 'district'];
$allowedSupportLevels = ['managed', 'rollout', 'not-sure'];

if (
    $schoolName === '' ||
    $contactName === '' ||
    $cityState === '' ||
    $useCase === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: ' . buildSchoolRedirect(['err' => 1]));
    exit;
}

if (!ctype_digit($studentCount) || (int)$studentCount < 1 || !ctype_digit($deviceCount) || (int)$deviceCount < 1) {
    header('Location: ' . buildSchoolRedirect(['err' => 1]));
    exit;
}

if (!in_array($program, $allowedPrograms, true)) {
    $program = 'managed';
}

if (!in_array($supportLevel, $allowedSupportLevels, true)) {
    $supportLevel = 'managed';
}

$programLabels = [
    'launch' => 'Launch',
    'managed' => 'Managed School Fleet',
    'district' => 'District Support',
];

$supportLabels = [
    'managed' => 'Ongoing managed support',
    'rollout' => 'Rollout help only',
    'not-sure' => 'Not sure yet',
];

$ref = 'SCH-' . strtoupper(substr(md5($email . time()), 0, 8));

try {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS school_inquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ref TEXT UNIQUE,
        school_name TEXT,
        contact_name TEXT,
        email TEXT,
        phone TEXT,
        city_state TEXT,
        student_count INTEGER,
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
        INSERT INTO school_inquiries
          (ref, school_name, contact_name, email, phone, city_state, student_count, device_count,
           program, use_case, support_level, requirements, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $ref,
        $schoolName,
        $contactName,
        $email,
        $phone,
        $cityState,
        (int)$studentCount,
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

$adminSubject = "New School Inquiry [{$ref}] - {$schoolName}";
$adminMessage = "New school service inquiry submitted via tablet-masters.com\n\n"
    . "Inquiry Ref:      {$ref}\n"
    . "School:           {$schoolName}\n"
    . "Contact:          {$contactName}\n"
    . "Email:            {$email}\n"
    . "Phone:            {$phone}\n"
    . "City/State:       {$cityState}\n"
    . "Student Count:    {$studentCount}\n"
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

$customerSubject = "School Inquiry Received [{$ref}] - Tablet Masters";
$customerMessage = "Hi {$contactName},\n\n"
    . "Thanks for contacting Tablet Masters about school tablet support.\n\n"
    . "Here is the summary we received:\n"
    . "School:           {$schoolName}\n"
    . "City/State:       {$cityState}\n"
    . "Student Count:    {$studentCount}\n"
    . "Device Count:     {$deviceCount}\n"
    . "Program:          " . $programLabels[$program] . "\n"
    . "Support Level:    " . $supportLabels[$supportLevel] . "\n"
    . "Primary Use Case: {$useCase}\n\n"
    . "A Tablet Masters team member will review the request and follow up with next steps.\n\n"
    . "- The Tablet Masters Team\n"
    . "https://tablet-masters.com";

$customerHeaders = "From: service@tablet-masters.com\r\n";
$customerHeaders .= "Reply-To: service@tablet-masters.com\r\n";
$customerHeaders .= "X-Mailer: PHP/" . phpversion();

mail($email, $customerSubject, $customerMessage, $customerHeaders);

header('Location: /schools.php?sent=1#school-quote');
exit;
