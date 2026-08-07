<?php

function validStripeSessionId($value): bool {
    return is_string($value)
        && preg_match('/^cs_(test|live)_[A-Za-z0-9_]+$/', $value) === 1;
}
