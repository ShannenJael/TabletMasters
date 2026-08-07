output "phone_number_arn" {
  description = "ARN of the toll-free phone number"
  value       = aws_pinpointsmsvoicev2_phone_number.toll_free.arn
}

output "phone_number" {
  description = "The toll-free phone number in E.164 format"
  value       = aws_pinpointsmsvoicev2_phone_number.toll_free.phone_number
}

output "configuration_set_name" {
  description = "Name of the End User Messaging configuration set"
  value       = aws_pinpointsmsvoicev2_configuration_set.tablet_masters.name
}

output "sns_inbound_topic_arn" {
  description = "SNS topic ARN for inbound customer replies"
  value       = aws_sns_topic.sms_inbound.arn
}

output "sns_status_topic_arn" {
  description = "SNS topic ARN for delivery status events"
  value       = aws_sns_topic.sms_status.arn
}

output "iam_user_name" {
  description = "IAM user name — create its access key manually in the console"
  value       = aws_iam_user.sms_app.name
}
