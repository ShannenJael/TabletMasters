# Twilio SMS setup

Tablet Masters uses **+1 850 467 9868** as its default Twilio sender for opted-in device registration, order, and protection-plan updates. The same number can receive customer replies. An optional staff number receives alerts and forwarded replies.

## Twilio Console

1. Confirm that **+18504679868** is an SMS-capable phone number on the Tablet Masters Twilio account. Add it to the Sender Pool for the Messaging Service associated with the approved US A2P 10DLC campaign. [Twilio number registration guide](https://help.twilio.com/articles/55716117260955)
2. In the Messaging Service's inbound settings, set incoming messages to send a webhook using **HTTP POST** to `https://tablet-masters.com/twilio-incoming.php`. If the service defers to the sender's webhook, set that URL on the number instead. For the campaign's opt-in flow, use `https://tablet-masters.com/register.php` and include the checkout opt-in if the campaign covers order updates. [Twilio campaign onboarding guide](https://help.twilio.com/hc/en-us/articles/11847054539547-A2P-10DLC-Campaign-Approval-Requirements)
3. Twilio handles STOP, START, and HELP. When using Advanced Opt-Out, configure the HELP response to identify Tablet Masters and point customers to `https://tablet-masters.com/support.php`. [Twilio Advanced Opt-Out](https://www.twilio.com/docs/messaging/tutorials/advanced-opt-out)

## Private production configuration

Set these values through the hosting environment or as constants in the server's untracked `includes/config.php`. Keep the Auth Token in that private configuration.

| Setting | Value |
| --- | --- |
| `TM_SMS_ENABLED` | `true` |
| `TM_SMS_PROVIDER` | `twilio` |
| `TWILIO_ACCOUNT_SID` | The live Account SID beginning with `AC` |
| `TWILIO_AUTH_TOKEN` | The account's active Auth Token, also used to verify webhook signatures |
| `TWILIO_MESSAGING_SERVICE_SID` | The registered Messaging Service SID beginning with `MG` |
| `TWILIO_FROM_NUMBER` | Defaults to `+18504679868` |
| `TM_SUPPORT_ALERT_NUMBER` | Optional staff mobile number, separate from the Twilio sender |

The app includes `https://tablet-masters.com/twilio-status.php` as the delivery callback URL for outbound messages. If you use a different site origin, set `TWILIO_INCOMING_WEBHOOK_URL` and `TWILIO_STATUS_CALLBACK_URL` to the exact public HTTPS URLs for that environment. Twilio signs requests against those URLs.

## Production verification

Submit a tablet registration with SMS consent and a mobile number you control. Confirm that the message arrives from **+18504679868**, contains the new registration ID, and shows a delivery receipt in the Twilio messaging logs. Reply to verify the inbound webhook, then test STOP and START on the same test handset. The site's SMS delivery and inbound records are stored in `data/logs/`.
