<?php
/**
 * Explains why the customer was sent back from checkout.php / subscribe.php
 * (?cancelled=1 or ?error=<code>). Include right after the nav.
 */

$tmCheckoutMessages = [
    'cancelled'          => ['success', 'Checkout was cancelled and you have not been charged. Your selections are saved, so you can pick up where you left off.'],
    'stripe'             => ['error', 'We couldn\'t start secure checkout just now. You have not been charged. Please try again in a moment, or <a href="support.php">contact support</a> if it keeps happening.'],
    'empty_cart'         => ['error', 'Your cart is empty. Add a tablet or accessory before checking out.'],
    'invalid_cart'       => ['error', 'Some items in your cart are no longer available. Please remove them and try again.'],
    'insufficient_stock' => ['error', 'We don\'t have enough stock for one of the items in your cart. Please lower the quantity and try again.'],
    'invalid_plan'       => ['error', 'Please choose the Basic or Protected plan to continue.'],
];

$tmNoticeKey = isset($_GET['cancelled']) ? 'cancelled' : (string)($_GET['error'] ?? '');

if (isset($tmCheckoutMessages[$tmNoticeKey])):
    [$tmNoticeType, $tmNoticeText] = $tmCheckoutMessages[$tmNoticeKey];
?>
<div class="checkout-notice">
  <div class="alert alert-<?= $tmNoticeType ?>" role="<?= $tmNoticeType === 'error' ? 'alert' : 'status' ?>" data-checkout-notice="<?= htmlspecialchars($tmNoticeKey) ?>">
    <?= $tmNoticeText ?>
  </div>
</div>
<?php endif; ?>
