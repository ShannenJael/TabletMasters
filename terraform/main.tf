terraform {
  required_version = ">= 1.5"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = ">= 6.53.0"
    }
  }
}

# ── Provider ──────────────────────────────────────────────────────────────────

provider "aws" {
  region = var.aws_region
}

# ── Opt-out list ─────────────────────────────────────────────────────────────

resource "aws_pinpointsmsvoicev2_opt_out_list" "default" {
  name = var.opt_out_list_name
}

# ── Configuration set (default message type + delivery status routing) ───────

resource "aws_pinpointsmsvoicev2_configuration_set" "tablet_masters" {
  name                 = var.configuration_set_name
  default_message_type = "TRANSACTIONAL"
}

# ── SNS topics ────────────────────────────────────────────────────────────────
# Kept separate (inbound vs status) to mirror the app's separate sms-inbound.php /
# sms-status.php endpoints and keep each topic's resource policy narrowly scoped.

resource "aws_sns_topic" "sms_inbound" {
  name = "${var.resource_prefix}-inbound"
}

resource "aws_sns_topic" "sms_status" {
  name = "${var.resource_prefix}-status"
}

# Allow AWS End User Messaging to publish inbound messages for this phone number.
#
# NOTE: the exact service principal / condition key names below are our best
# documented guess (pinpoint-sms-voice-v2 uses the "sms-voice.amazonaws.com"
# principal elsewhere in IAM). Verify this the first time an inbound test text
# is sent — if messages don't arrive at sms-inbound.php, check CloudTrail /
# the SNS topic's access logs for an AccessDenied publish attempt and correct
# the principal/condition here before re-applying.
resource "aws_sns_topic_policy" "sms_inbound_policy" {
  arn = aws_sns_topic.sms_inbound.arn

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid       = "AllowEndUserMessagingPublishInbound"
        Effect    = "Allow"
        Principal = { Service = "sms-voice.amazonaws.com" }
        Action    = "SNS:Publish"
        Resource  = aws_sns_topic.sms_inbound.arn
        Condition = {
          ArnLike = { "aws:SourceArn" = aws_pinpointsmsvoicev2_phone_number.toll_free.arn }
        }
      }
    ]
  })
}

# Allow AWS End User Messaging to publish delivery status events.
# Same verification caveat as sms_inbound_policy above.
resource "aws_sns_topic_policy" "sms_status_policy" {
  arn = aws_sns_topic.sms_status.arn

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid       = "AllowEndUserMessagingPublishStatus"
        Effect    = "Allow"
        Principal = { Service = "sms-voice.amazonaws.com" }
        Action    = "SNS:Publish"
        Resource  = aws_sns_topic.sms_status.arn
        Condition = {
          ArnLike = { "aws:SourceArn" = aws_pinpointsmsvoicev2_configuration_set.tablet_masters.arn }
        }
      }
    ]
  })
}

# HTTPS subscriptions — AWS SNS will POST a SubscriptionConfirmation to each
# endpoint once created; includes/aws-sns.php auto-confirms it. Full envelope
# is kept (no raw_message_delivery) so the endpoints can verify signatures.

resource "aws_sns_topic_subscription" "sms_inbound_https" {
  topic_arn            = aws_sns_topic.sms_inbound.arn
  protocol             = "https"
  endpoint             = "${var.site_url}/sms-inbound.php"
  raw_message_delivery = false
}

resource "aws_sns_topic_subscription" "sms_status_https" {
  topic_arn            = aws_sns_topic.sms_status.arn
  protocol             = "https"
  endpoint             = "${var.site_url}/sms-status.php"
  raw_message_delivery = false
}

# ── Delivery status event destination ─────────────────────────────────────────

resource "aws_pinpointsmsvoicev2_event_destination" "status_to_sns" {
  configuration_set_name = aws_pinpointsmsvoicev2_configuration_set.tablet_masters.name
  event_destination_name = "status-to-sns"
  matching_event_types = [
    "TEXT_SUCCESSFUL",
    "TEXT_DELIVERED",
    "TEXT_QUEUED",
    "TEXT_BLOCKED",
    "TEXT_CARRIER_BLOCKED",
    "TEXT_TTL_EXPIRED",
  ]

  sns_destination {
    topic_arn = aws_sns_topic.sms_status.arn
  }
}

# ── Toll-free phone number ────────────────────────────────────────────────────
#
# IMPORTANT: this number must be requested manually via the AWS Console first
# (Phase 0 — see docs/AWS_SMS_SETUP.md) because toll-free verification/
# registration has no Terraform resource. After the number exists, import it
# before running `terraform apply`:
#
#   terraform import aws_pinpointsmsvoicev2_phone_number.toll_free <phone-number-id>
#
# `registration_id` can be left null until the toll-free verification
# submitted in the console is approved and AWS issues a registration id;
# re-apply once it's known to attach it here.

resource "aws_pinpointsmsvoicev2_phone_number" "toll_free" {
  iso_country_code            = "US"
  message_type                = "TRANSACTIONAL"
  number_type                 = "TOLL_FREE"
  number_capabilities         = ["SMS"]
  two_way_channel_enabled     = true
  two_way_channel_arn         = aws_sns_topic.sms_inbound.arn
  opt_out_list_name           = aws_pinpointsmsvoicev2_opt_out_list.default.name
  registration_id             = var.tollfree_registration_id != "" ? var.tollfree_registration_id : null
  deletion_protection_enabled = true

  lifecycle {
    prevent_destroy = true
  }
}

# ── IAM: app user for outbound SendTextMessage ────────────────────────────────
#
# The access key itself is NOT managed here (would land in plaintext in
# Terraform state) — create it once manually in the console after apply:
# IAM → Users → <iam_user_name> → Security credentials → Create access key.

resource "aws_iam_user" "sms_app" {
  name = var.iam_user_name
}

resource "aws_iam_user_policy" "sms_app_send" {
  name = "${var.iam_user_name}-send-sms"
  user = aws_iam_user.sms_app.name

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid      = "SendText"
        Effect   = "Allow"
        Action   = ["sms-voice:SendTextMessage"]
        Resource = aws_pinpointsmsvoicev2_phone_number.toll_free.arn
      },
      {
        # sms-voice:DescribePhoneNumbers does not support resource-level
        # scoping in IAM as of writing — verify against the service's IAM
        # action reference before tightening this further.
        Sid      = "DescribeNumbers"
        Effect   = "Allow"
        Action   = ["sms-voice:DescribePhoneNumbers"]
        Resource = "*"
      }
    ]
  })
}
