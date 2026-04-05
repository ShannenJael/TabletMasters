<?php $currentPage = 'insurance'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Support - Tablet Masters</title>
  <meta name="description" content="Get support from Tablet Masters for repairs, coverage questions, orders, cloud setup, and tablet troubleshooting." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#3B82F6" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php
$supportAreas = [
  ['icon' => '🛠️', 'title' => 'Repair Support',      'desc' => 'Screen, battery, charging, camera, speaker, and diagnostic help for the major tablet brands.', 'price' => 'Fast intake',      'gold' => false],
  ['icon' => '🛡️', 'title' => 'Coverage Questions', 'desc' => 'Need help understanding lifetime protection, replacement eligibility, or deductible rules?',      'price' => 'Plan guidance',    'gold' => true],
  ['icon' => '📦', 'title' => 'Order Support',      'desc' => 'Questions about your order, fulfillment, shipping status, or device condition before delivery?',    'price' => 'Purchase help',    'gold' => false],
  ['icon' => '☁️', 'title' => 'Cloud Setup',        'desc' => 'Assistance with syncing, configuration, account setup, backups, and device onboarding.',          'price' => 'Setup assistance', 'gold' => false],
  ['icon' => '🏫', 'title' => 'Business & Education','desc' => 'Support for school fleets, conference deployments, enterprise rollouts, and managed tablet needs.', 'price' => 'Team support',     'gold' => true],
  ['icon' => '🔍', 'title' => 'Troubleshooting',    'desc' => 'If you are not sure what is wrong, we can help narrow down the issue before the device comes in.',  'price' => 'Start here',       'gold' => false],
];

$supportSteps = [
  ['num' => '01', 'title' => 'Choose Your Issue',    'desc' => 'Tell us whether you need repair help, coverage support, order assistance, or setup guidance.'],
  ['num' => '02', 'title' => 'Share Device Details', 'desc' => 'Send the tablet model, the issue you are seeing, and any order or claim context that helps us respond.'],
  ['num' => '03', 'title' => 'Get a Clear Path',     'desc' => 'We will point you to the right next step, whether that is troubleshooting, a claim, shipping, or in-house service.'],
  ['num' => '04', 'title' => 'Stay Supported',       'desc' => 'Our team follows through with repair intake, replacement review, order handling, or setup assistance as needed.'],
];

$supportFaq = [
  ['title' => 'What should I include in a support request?', 'desc' => 'The most helpful details are your name, tablet model, a short description of the issue, and any order or claim information you already have.'],
  ['title' => 'Can you help before I ship the device?',      'desc' => 'Yes. We can help you troubleshoot first so you know whether the issue needs a repair, a claim review, or a setup fix.'],
  ['title' => 'Do you support brands besides Apple?',        'desc' => 'Yes. The site currently supports Apple, Samsung, Microsoft, and Amazon tablet product lines and related service needs.'],
  ['title' => 'Where do I go for repair booking?',           'desc' => 'If you already know you need service, use the repair booking form on the Insurance & Repair page and we will follow up from there.'],
];
?>

<div class="ins-section">

  <div class="ins-hero">
    <div class="ins-hero-text">
      <div class="section-label">// Help When You Need It</div>
      <div class="section-title">SUPPORT<br />CENTER</div>
      <p>
        Whether you need help with a repair, a protection question, an order, or device setup,
        Tablet Masters is built to support tablet customers end to end. We focus on clear next
        steps, fast follow-up, and practical help instead of generic support queues.
      </p>
      <div class="ins-actions">
        <a class="btn-primary" href="insurance.php#book-form">Book a Repair</a>
        <a class="btn-outline" href="mailto:service@tablet-masters.com">Email Support</a>
      </div>
    </div>

    <div class="ins-coverage-card">
      <span class="ins-shield">🎧</span>
      <h3>WHAT SUPPORT COVERS</h3>
      <p>Use this page when you need help before, during, or after a purchase or repair.</p>
      <ul class="coverage-checklist">
        <li><span class="check-dot">&#10003;</span> Device troubleshooting and intake guidance</li>
        <li><span class="check-dot">&#10003;</span> Coverage and replacement questions</li>
        <li><span class="check-dot">&#10003;</span> Order and fulfillment follow-up</li>
        <li><span class="check-dot">&#10003;</span> Cloud and setup assistance</li>
        <li><span class="check-dot">&#10003;</span> Business, school, and fleet support</li>
        <li><span class="check-dot">&#10003;</span> Direct routing to repair booking when needed</li>
      </ul>
    </div>
  </div>

  <div class="repair-section-title">
    <div class="section-label">// How We Help</div>
    <div class="section-title" style="font-size:44px">SUPPORT AREAS</div>
  </div>

  <div class="repair-grid">
    <?php foreach ($supportAreas as $area): ?>
    <div class="repair-card">
      <span class="repair-icon"><?= $area['icon'] ?></span>
      <h4><?= htmlspecialchars($area['title']) ?></h4>
      <p><?= htmlspecialchars($area['desc']) ?></p>
      <span class="repair-price-tag<?= $area['gold'] ? ' gold' : '' ?>"><?= htmlspecialchars($area['price']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:48px">
    <div class="section-label">// Simple 4-Step Process</div>
    <div class="section-title" style="font-size:44px">HOW SUPPORT WORKS</div>
  </div>

  <div class="claim-steps">
    <?php foreach ($supportSteps as $step): ?>
    <div class="claim-step">
      <div class="claim-step-num"><?= htmlspecialchars($step['num']) ?></div>
      <h5><?= htmlspecialchars($step['title']) ?></h5>
      <p><?= htmlspecialchars($step['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:32px">
    <div class="section-label">// Common Questions</div>
    <div class="section-title" style="font-size:44px">SUPPORT FAQ</div>
  </div>

  <div class="repair-grid">
    <?php foreach ($supportFaq as $item): ?>
    <div class="repair-card">
      <h4><?= htmlspecialchars($item['title']) ?></h4>
      <p><?= htmlspecialchars($item['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-cta">
    <div>
      <h3>NEED HELP RIGHT NOW?</h3>
      <p>
        Start with the repair form if your device needs hands-on service, or email our team for
        coverage, order, and setup questions. We will route you to the right next step.
      </p>
      <p>
        Mailing address: <strong>TabletmastersLLC, 550 Mary Esther Cutoof #18, PMB 376,
        Fort Walton Beach, FL 32548</strong>.
      </p>
    </div>

    <div class="repair-form">
      <a class="btn-primary full" href="insurance.php#book-form">Go to Repair Booking</a>
      <a class="btn-outline full" href="mailto:service@tablet-masters.com">Email service@tablet-masters.com</a>
      <a class="btn-outline full" href="plans.php">Review Coverage Plans</a>
    </div>
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
