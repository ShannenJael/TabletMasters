<?php
// Copy to config.php for a real Stripe sandbox or deployment. Never commit keys.
// The automated payment suite generates its own isolated config instead.
define('SITE_URL', rtrim(getenv('SITE_URL') ?: 'http://127.0.0.1:3100', '/'));
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');
define('STRIPE_WEBHOOK_SECRET', getenv('STRIPE_WEBHOOK_SECRET') ?: '');
define('STRIPE_PRICE_BASIC', getenv('STRIPE_PRICE_BASIC') ?: '');
define('STRIPE_PRICE_PROTECTED', getenv('STRIPE_PRICE_PROTECTED') ?: '');
// See docs/TWILIO_SETUP.md for Twilio and docs/AWS_SMS_SETUP.md for AWS.
define('TM_SMS_ENABLED', filter_var(getenv('TM_SMS_ENABLED'), FILTER_VALIDATE_BOOL));
// Generate with PHP password_hash(); never use a password stored in source code.
define('TM_ADMIN_PASSWORD_HASH', getenv('TM_ADMIN_PASSWORD_HASH') ?: '');
