# Twilio Setup

Tablet Masters now includes a lightweight Twilio SMS layer for:

- purchase confirmations
- insurance subscription confirmations
- device registration confirmations
- internal support alerts
- inbound customer replies

## Files

- `includes/twilio.php`
- `includes/notifications.php`
- `twilio-incoming.php`
- `twilio-status.php`
- `webhook.php`
- `save-device.php`
- `checkout.php`
- `subscribe.php`

## Add credentials

The app will read Twilio settings from either:

1. constants inside your untracked `includes/config.php`
2. environment variables on the server

Recommended constants to add to `includes/config.php`:

```php
define('TM_SMS_ENABLED', true);
define('TWILIO_ACCOUNT_SID', 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('TWILIO_AUTH_TOKEN', 'your_auth_token');
define('TWILIO_FROM_NUMBER', '+1xxxxxxxxxx');
define('TWILIO_MESSAGING_SERVICE_SID', '');
define('TWILIO_STATUS_CALLBACK_URL', 'https://tablet-masters.com/twilio-status.php');
define('TM_SUPPORT_ALERT_NUMBER', '+63xxxxxxxxxx');
define('TM_SMS_DEFAULT_COUNTRY', 'PH');
```

Notes:

- Use `TWILIO_MESSAGING_SERVICE_SID` if you prefer a Messaging Service over a single `From` number.
- `TM_SUPPORT_ALERT_NUMBER` is the staff number that receives alerts for new orders, subscriptions, registrations, and customer replies.
- If your customers usually enter Philippine mobile numbers in local format like `09xxxxxxxxx`, the code normalizes them to `+63`.

## Twilio Console webhooks

Set your Twilio number or Messaging Service to use:

- Incoming message webhook: `https://tablet-masters.com/twilio-incoming.php`
- Status callback webhook: `https://tablet-masters.com/twilio-status.php`

## What sends SMS

### Purchases

`webhook.php` sends:

- customer confirmation SMS after `checkout.session.completed`
- staff alert SMS for new paid purchase activity

### Insurance subscriptions

`webhook.php` sends:

- customer insurance activation SMS
- staff alert SMS for new signup activity

### Device registrations

`save-device.php` sends:

- customer registration confirmation SMS
- staff alert SMS for new registration activity

## Stripe phone collection

`checkout.php` and `subscribe.php` now enable Stripe phone collection so paid flows can capture a customer phone number for messaging.

## Data written on the server

The SMS layer stores logs in `data/logs/`:

- `twilio_YYYY-MM-DD.log`
- `twilio_notifications.json`
- `twilio_inbox.json`
- `twilio_status_callbacks.json`

## Important production notes

- Twilio trial accounts can only text verified destination numbers.
- Use E.164 phone numbers where possible.
- For Philippine delivery, make sure your Twilio sender setup and destination rules are approved for your use case.
- Review message copy before going live so it matches your support workflow and compliance needs.
