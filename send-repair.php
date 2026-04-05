<?php
/**
 * send-repair.php
 * Handles repair booking — saves to database, emails customer + admin.
 */

require_once __DIR__ . '/includes/database.php';

$toEmail = 'service@tablet-masters.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: insurance.php');
    exit;
}

function clean($val) {
    return htmlspecialchars(strip_tags(trim($val)));
}

$name              = clean($_POST['name']              ?? '');
$email             = clean($_POST['email']             ?? '');
$phone             = clean($_POST['phone']             ?? '');
$device            = clean($_POST['device']            ?? '');
$type              = clean($_POST['type']              ?? '');
$notes             = clean($_POST['notes']             ?? '');
$preferred_contact = clean($_POST['preferred_contact'] ?? 'email');

if (empty($name) || empty($email) || empty($type) || empty($device)) {
    header('Location: insurance.php?err=1#book-form');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: insurance.php?err=1#book-form');
    exit;
}

// Generate booking reference
$ref = 'REP-' . strtoupper(substr(md5($email . time()), 0, 8));

// Save to database
try {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS repair_bookings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ref TEXT UNIQUE,
        name TEXT,
        email TEXT,
        phone TEXT,
        device TEXT,
        type TEXT,
        notes TEXT,
        preferred_contact TEXT,
        status TEXT DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt = $db->prepare("INSERT INTO repair_bookings (ref, name, email, phone, device, type, notes, preferred_contact) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$ref, $name, $email, $phone, $device, $type, $notes, $preferred_contact]);
} catch (\Exception $e) {
    // DB save failed — still send emails
}

// ── Email to admin ──
$adminSubject = "New Repair Booking [{$ref}] — {$type}";
$adminMessage = "New repair booking submitted via tablet-masters.com\n\n"
    . "Booking Ref:  {$ref}\n"
    . "Name:         {$name}\n"
    . "Email:        {$email}\n"
    . "Phone:        {$phone}\n"
    . "Device:       {$device}\n"
    . "Repair Type:  {$type}\n"
    . "Contact Pref: {$preferred_contact}\n\n"
    . "Notes:\n{$notes}\n\n"
    . "View in admin: https://tablet-masters.com/admin/index.php?page=repairs";

$adminHeaders  = "From: service@tablet-masters.com\r\n";
$adminHeaders .= "Reply-To: {$email}\r\n";
$adminHeaders .= "X-Mailer: PHP/" . phpversion();

mail($toEmail, $adminSubject, $adminMessage, $adminHeaders);

// ── Confirmation email to customer ──
$custSubject = "Repair Booking Confirmed [{$ref}] — Tablet Masters";
$custMessage = "Hi {$name},\n\n"
    . "Thanks for booking a repair with Tablet Masters! Here's your booking summary:\n\n"
    . "Booking Reference: {$ref}\n"
    . "Device:            {$device}\n"
    . "Repair Type:       {$type}\n"
    . "Notes:             " . ($notes ?: 'None') . "\n\n"
    . "Our team will reach out within a few hours to confirm your appointment.\n\n"
    . "Questions? Reply to this email or call us directly.\n\n"
    . "— The Tablet Masters Team\n"
    . "https://tablet-masters.com";

$custHeaders  = "From: service@tablet-masters.com\r\n";
$custHeaders .= "Reply-To: service@tablet-masters.com\r\n";
$custHeaders .= "X-Mailer: PHP/" . phpversion();

mail($email, $custSubject, $custMessage, $custHeaders);

header('Location: insurance.php?sent=1#book-form');
exit;
