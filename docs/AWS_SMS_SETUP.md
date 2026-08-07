# AWS SMS Setup (End User Messaging)

Tablet Masters sends/receives SMS through **AWS End User Messaging** (Pinpoint SMS/Voice v2) in **us-east-2**, for:

- purchase confirmations
- insurance subscription confirmations
- device registration confirmations
- internal support alerts
- inbound customer replies

## Files

- `includes/aws-sigv4.php` — AWS Signature Version 4 request signer
- `includes/aws-sms.php` — outbound SMS client (`tmSendSmsMessage`, config, logging)
- `includes/aws-sns.php` — SNS HTTPS message signature verification + subscription confirmation
- `includes/notifications.php` — business logic (order/registration notifications), unchanged except its `require_once` target
- `sms-inbound.php` — SNS HTTPS endpoint for inbound customer replies
- `sms-status.php` — SNS HTTPS endpoint for delivery status events
- `webhook.php`, `save-device.php`, `checkout.php`, `subscribe.php` — unchanged, already call the provider-neutral `tmNotify*`/`tmSms*` functions

## Phase 0 — Manual AWS Console Steps (do this first — gates everything else)

These steps cannot be automated via Terraform (AWS has no Terraform resources for phone number registration) and must be done once, by hand, in the AWS Console.

1. **Sign in to the AWS Console**, switch to region **US East (Ohio) / us-east-2**.
2. Go to **AWS End User Messaging → SMS and voice → Phone numbers → Request phone number**.
3. Choose:
   - Country: **United States**
   - Number type: **Toll-free**
   - Capabilities: **SMS** (two-way)
4. Complete **toll-free verification** in the same flow (or under **Toll-free registrations** if presented as a separate step). You'll need:
   - Legal business name, address, contact email/phone, website
   - Use case description, e.g.: *"Transactional SMS for order confirmations, insurance activations, and device registration confirmations sent to customers who opted in during checkout or device registration on tablet-masters.com."*
   - 2 sample messages, e.g.:
     - "Tablet Masters: Your order is confirmed. Thank you for your purchase! Reply STOP to unsubscribe."
     - "Tablet Masters: Your device has been registered successfully. Reply STOP to unsubscribe."
   - Expected monthly volume (rough estimate is fine)
   - Opt-in/opt-out process description: *"Customers provide their phone number and check an SMS opt-in box during checkout or device registration. They can reply STOP at any time to unsubscribe."*
5. Submit. **Expected approval time: 1–3 weeks.** You'll receive an email when it's approved. The number is usable for testing before approval in some cases, but production sending may be restricted until verification completes.
6. Once the number exists (pending or approved), record:
   - The phone number in E.164 format (e.g. `+18005551234`)
   - Its **Phone Number ID** (shown in the console, format like `phone-abc123...`) — needed for `terraform import`
7. Reply back (or note in your ticket/PR) once you have the number + ID so Terraform can import it — see `terraform/main.tf` comments for the exact `terraform import` command.
8. **Create an IAM user** (or ask your AWS admin to) named e.g. `tablet-masters-sms-app` — Terraform will create the user + policy, but the **access key itself must be created manually** in the console (Terraform intentionally does not manage it, to avoid the secret landing in plaintext state). Steps:
   - After `terraform apply` creates the IAM user (Phase 1), go to **IAM → Users → tablet-masters-sms-app → Security credentials → Create access key** (use case: "Application running outside AWS")
   - Save the Access Key ID and Secret Access Key — these go into `includes/config.php` as `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY`.

## Add Credentials

Add to your untracked `includes/config.php`:

```php
define('TM_SMS_ENABLED', true);
define('AWS_REGION', 'us-east-2');
define('AWS_ACCESS_KEY_ID', 'your_aws_access_key_id');
define('AWS_SECRET_ACCESS_KEY', 'your_secret_access_key');
define('AWS_SMS_ORIGINATION_NUMBER', '+18005551234');
define('AWS_SMS_CONFIGURATION_SET', 'tablet-masters-sms');
define('AWS_SNS_INBOUND_TOPIC_ARN', 'arn:aws:sns:us-east-2:XXXXXXXXXXXX:tablet-masters-sms-inbound');
define('AWS_SNS_STATUS_TOPIC_ARN', 'arn:aws:sns:us-east-2:XXXXXXXXXXXX:tablet-masters-sms-status');
define('TM_SUPPORT_ALERT_NUMBER', '+1xxxxxxxxxx');
define('TM_SMS_DEFAULT_COUNTRY', 'US');
```

Notes:

- All customer-facing numbers are expected in US E.164 format (`+1` + 10 digits). Local `(xxx) xxx-xxxx` or bare 10-digit input is normalized automatically.
- The two SNS topic ARNs come from `terraform output sns_inbound_topic_arn` / `sns_status_topic_arn` after Phase 1 Terraform apply.

## Terraform (Phase 1)

See `terraform/main.tf`. Run:

```
cd terraform
terraform init
terraform import aws_pinpointsmsvoicev2_phone_number.toll_free <phone-number-id-from-console>
terraform plan
terraform apply
```

Outputs after apply: `phone_number_arn`, `phone_number`, `configuration_set_name`, `sns_inbound_topic_arn`, `sns_status_topic_arn`, `iam_user_name`.

## SNS Subscription Confirmation

After `terraform apply` creates the SNS topic subscriptions pointing at `https://tablet-masters.com/sms-inbound.php` and `https://tablet-masters.com/sms-status.php`, AWS SNS sends a one-time `SubscriptionConfirmation` message to each endpoint. Both endpoints auto-confirm (see `includes/aws-sns.php` — only confirms if the envelope's `TopicArn` matches the allow-listed constant). Check `data/logs/` or AWS SNS console (Subscriptions tab) to confirm both show status **Confirmed**, not **Pending confirmation**.

## What Sends SMS

### Purchases

`webhook.php` sends:

- customer confirmation SMS after `checkout.session.completed`
- staff alert SMS for new paid purchase activity

### Insurance Subscriptions

`webhook.php` sends:

- customer insurance activation SMS
- staff alert SMS for new signup activity

### Device Registrations

`save-device.php` sends:

- customer registration confirmation SMS
- staff alert SMS for new registration activity

### Inbound Replies

`sms-inbound.php` forwards customer replies to `TM_SUPPORT_ALERT_NUMBER` and sends an automatic acknowledgement back to the customer.

## Stripe Phone Collection

`checkout.php` and `subscribe.php` enable Stripe phone collection so paid flows can capture a customer phone number for messaging. Unchanged by this migration.

## Data Written On The Server

The SMS layer stores logs in `data/logs/`:

- `sms_YYYY-MM-DD.log` (now tagged `"provider": "aws"`)
- `sms_notifications.json` (idempotency ledger — prevents duplicate sends on webhook retries)
- `sms_inbox.json`
- `sms_status_callbacks.json`

## Important Production Notes

- Toll-free numbers can only send to US destinations — this integration is US-only by design.
- Delivery status is asynchronous and can take up to 72 hours for a final carrier status in rare cases; treat `sms-status.php` events as eventually-consistent, not immediate.
- Review message copy before going live so it matches your support workflow, includes "Reply STOP to unsubscribe" language, and matches what was submitted during toll-free verification (changing message content substantially after verification can trigger re-review).
