<?php
/**
 * send-repair.php
 * Handles the repair booking form submission from insurance.php
 * Sends repair requests to the Tablet Masters service inbox.
 */

$toEmail = 'service@tablet-masters.com';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: insurance.php');
    exit;
}

// Sanitize inputs
function clean($val) {
    return htmlspecialchars(strip_tags(trim($val)));
}

$name  = clean($_POST['name']  ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$type  = clean($_POST['type']  ?? '');

// Basic validation
if (empty($name) || empty($email) || empty($type)) {
    header('Location: insurance.php?err=1#book-form');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: insurance.php?err=1#book-form');
    exit;
}

// Build email
$subject = 'New Repair Request — ' . $type;
$message = "New repair booking request from the Tablet Masters website.\n\n"
         . "Name:  $name\n"
         . "Email: $email\n"
         . "Phone: $phone\n"
         . "Type:  $type\n";

$headers  = "From: service@tablet-masters.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($toEmail, $subject, $message, $headers);

if ($sent) {
    header('Location: insurance.php?sent=1#book-form');
} else {
    header('Location: insurance.php?err=1#book-form');
}
exit;
