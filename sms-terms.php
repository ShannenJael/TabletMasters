<?php $currentPage = ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SMS Terms - Tablet Masters</title>
  <meta name="description" content="Read the Tablet Masters SMS Terms for order updates, coverage alerts, opt-in consent, message frequency, rates, and opt-out instructions." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/assets/css/style.css'); ?>" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#3B82F6" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php $lastUpdated = 'May 5, 2026'; ?>

<div class="policy-section">
  <div class="policy-hero">
    <div class="section-label">// Messaging Terms</div>
    <div class="section-title">SMS TERMS</div>
    <p class="policy-intro">
      These SMS Terms explain how Tablet Masters uses text messages for order updates,
      coverage alerts, registration notices, repair communications, and related customer
      service messages.
    </p>
    <div class="policy-meta">Last updated: <?= htmlspecialchars($lastUpdated) ?></div>
  </div>

  <div class="policy-grid">
    <section class="policy-card">
      <h2>Program Description</h2>
      <p>
        Tablet Masters may send transactional and service-related SMS messages to customers
        who choose to receive them. These messages are used to support purchases, device
        registration, coverage, claims, repairs, and customer support.
      </p>
      <p class="policy-note">
        SMS consent is not required to buy products or services from Tablet Masters.
      </p>
    </section>

    <section class="policy-card">
      <h2>How You Opt In</h2>
      <ul class="policy-list">
        <li>At checkout, you may opt in by checking the SMS updates box before submitting your order.</li>
        <li>During device registration, you may opt in by entering your phone number and checking the SMS updates box.</li>
        <li>By opting in, you agree to receive text messages from Tablet Masters at the mobile number you provide.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Message Types</h2>
      <p>SMS messages from Tablet Masters may include:</p>
      <ul class="policy-list">
        <li>Order confirmations, fulfillment notices, delivery updates, and purchase support.</li>
        <li>Device registration confirmations and coverage activation alerts.</li>
        <li>Protection plan, claim, repair, diagnostic, and service status updates.</li>
        <li>Customer care replies related to your order, coverage, repair, or support request.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Message Frequency</h2>
      <p>
        Message frequency varies based on your activity with Tablet Masters. Most customers
        receive messages only when there is an order, registration, coverage, claim, repair,
        or support update. During an active order, claim, or repair, additional status messages
        may be sent as needed.
      </p>
    </section>

    <section class="policy-card">
      <h2>Rates and Carrier Notice</h2>
      <p>
        Message &amp; data rates may apply. Your mobile carrier may charge you for SMS messages
        according to your wireless plan. Carriers are not liable for delayed or undelivered
        messages.
      </p>
    </section>

    <section class="policy-card">
      <h2>How to Opt Out</h2>
      <p>
        You can opt out at any time by replying <strong>STOP</strong> to a Tablet Masters text
        message. After you reply STOP, you may receive one final confirmation message and then
        no further SMS messages from that sending number unless you opt in again.
      </p>
      <p class="policy-note">
        If supported by your carrier and messaging route, replying START may resubscribe you.
      </p>
    </section>

    <section class="policy-card">
      <h2>Help and Support</h2>
      <p>
        For help, reply <strong>HELP</strong> to a Tablet Masters text message or contact us at
        <a href="mailto:service@tablet-masters.com">service@tablet-masters.com</a>.
      </p>
    </section>

    <section class="policy-card">
      <h2>Privacy</h2>
      <p>
        We handle personal information, including phone numbers and SMS consent records, under
        our <a href="privacy.php">Privacy Policy</a>. Tablet Masters does not sell mobile
        opt-in information for marketing purposes.
      </p>
    </section>
  </div>

  <div class="policy-contact">
    <h2>Contact Us</h2>
    <p>
      Questions about these SMS Terms can be sent to
      <a href="mailto:service@tablet-masters.com">service@tablet-masters.com</a>.
    </p>
    <p>
      Mailing address: <strong>TabletmastersLLC, 550 Mary Esther Cutoff #18, PMB 376,
      Fort Walton Beach, FL 32548</strong>.
    </p>
    <p class="policy-note">
      This page is a practical SMS terms draft tailored to the current Tablet Masters
      checkout, registration, and support flows. It should be reviewed by qualified legal
      counsel before being treated as final legal language.
    </p>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
