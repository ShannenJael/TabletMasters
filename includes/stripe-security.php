<?php

function validStripeSessionId($value): bool {
    return is_string($value)
        && preg_match('/^cs_(test|live)_[A-Za-z0-9_]+$/', $value) === 1;
}

function stripeCheckoutIsPaid(array $session): bool {
    return in_array($session['mode'] ?? '', ['payment', 'subscription'], true)
        && ($session['status'] ?? '') === 'complete'
        && in_array($session['payment_status'] ?? '', ['paid', 'no_payment_required'], true);
}
