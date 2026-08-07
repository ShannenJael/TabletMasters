# ── AWS ───────────────────────────────────────────────────────────────────────

variable "aws_region" {
  description = "AWS region for all resources"
  type        = string
  default     = "us-east-2"
}

# ── Site ──────────────────────────────────────────────────────────────────────

variable "site_url" {
  description = "Public base URL for webhook endpoints"
  type        = string
  default     = "https://tablet-masters.com"
}

# ── Naming ────────────────────────────────────────────────────────────────────

variable "resource_prefix" {
  description = "Prefix used for SNS topic names"
  type        = string
  default     = "tablet-masters-sms"
}

variable "opt_out_list_name" {
  description = "Name of the End User Messaging opt-out list"
  type        = string
  default     = "tablet-masters-opt-out"
}

variable "configuration_set_name" {
  description = "Name of the End User Messaging configuration set"
  type        = string
  default     = "tablet-masters-sms"
}

variable "iam_user_name" {
  description = "IAM user name for the app's outbound SendTextMessage calls"
  type        = string
  default     = "tablet-masters-sms-app"
}

# ── Toll-free registration ────────────────────────────────────────────────────

variable "tollfree_registration_id" {
  description = "AWS registration ID from the manually-submitted toll-free verification (Phase 0). Leave empty until approved."
  type        = string
  default     = ""
}
