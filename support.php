<?php $currentPage = 'support'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Support &mdash; Tablet Masters</title>
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
  [
    'icon' => 'fa-solid fa-screwdriver-wrench',
    'title' => 'Repair Support',
    'desc' => 'Screen, battery, charging, camera, speaker, and diagnostic help for the major tablet brands.',
    'label' => 'Fast intake',
    'featured' => false,
  ],
  [
    'icon' => 'fa-solid fa-shield-heart',
    'title' => 'Coverage Questions',
    'desc' => 'Get help understanding replacement eligibility, deductibles, active-plan status, and claim readiness.',
    'label' => 'Plan guidance',
    'featured' => true,
  ],
  [
    'icon' => 'fa-solid fa-box-open',
    'title' => 'Order Support',
    'desc' => 'Questions about order status, fulfillment timing, device condition, or shipping follow-up before delivery.',
    'label' => 'Purchase help',
    'featured' => false,
  ],
  [
    'icon' => 'fa-solid fa-cloud-arrow-up',
    'title' => 'Cloud Setup',
    'desc' => 'Assistance with syncing, account setup, backups, configuration, and device onboarding after delivery.',
    'label' => 'Setup assistance',
    'featured' => false,
  ],
  [
    'icon' => 'fa-solid fa-building-columns',
    'title' => 'Business & Education',
    'desc' => 'Support for school fleets, conference deployments, enterprise rollouts, and managed tablet programs.',
    'label' => 'Team support',
    'featured' => true,
  ],
  [
    'icon' => 'fa-solid fa-magnifying-glass',
    'title' => 'Troubleshooting',
    'desc' => 'If you are not sure what is wrong yet, we can help narrow down the issue before the device comes in.',
    'label' => 'Start here',
    'featured' => false,
  ],
];

$supportSteps = [
  [
    'num' => '01',
    'title' => 'Choose the support lane',
    'desc' => 'Tell us whether the issue is repair-related, coverage-related, order-related, or a setup problem.'
  ],
  [
    'num' => '02',
    'title' => 'Share the device context',
    'desc' => 'Include the tablet model, what is happening, and any order, claim, or registration details you already have.'
  ],
  [
    'num' => '03',
    'title' => 'Get a clear next step',
    'desc' => 'We point you to troubleshooting, repair intake, claim review, or direct follow-up depending on the situation.'
  ],
  [
    'num' => '04',
    'title' => 'Stay supported through resolution',
    'desc' => 'Tablet Masters follows through with updates, routing, and the right service path until the issue is handled.'
  ],
];

$supportFaq = [
  [
    'title' => 'What should I include in a support request?',
    'desc' => 'The fastest requests include your name, tablet model, a short issue summary, and any order or claim details you already have.'
  ],
  [
    'title' => 'Can you help before I ship the device?',
    'desc' => 'Yes. We can help troubleshoot first so you know whether the issue needs repair, claim review, or a setup fix.'
  ],
  [
    'title' => 'Do you support brands besides Apple?',
    'desc' => 'Yes. Tablet Masters supports Apple, Samsung, Microsoft, Amazon, and related tablet service needs.'
  ],
  [
    'title' => 'Where do I go for repair booking?',
    'desc' => 'If you already know the device needs hands-on service, start with the repair booking form on the Insurance & Repair page.'
  ],
];

$priorityBands = [
  ['label' => 'Best For', 'value' => 'Customers who need the right support lane quickly without bouncing between pages'],
  ['label' => 'Fastest Path', 'value' => 'Repair booking, coverage questions, order help, and setup guidance surfaced up front'],
  ['label' => 'What You Get', 'value' => 'A clearer support map, faster routing, and fewer dead ends before the first reply'],
];

$responseHighlights = [
  'Routing that separates repair, coverage, and order questions early',
  'Pre-shipping troubleshooting when the device might not need intake yet',
  'Direct handoff into repair booking or plan review when needed',
];
?>

<section class="support-hero">
  <div class="support-hero-inner">
    <div class="support-hero-copy">
      <div class="section-label">Tablet Masters Support Center</div>
      <h1 class="support-hero-title">HELP SHOULD FEEL DIRECT, NOT GENERIC.</h1>
      <p class="support-hero-intro">
        Whether you need repair help, a coverage answer, order follow-up, or post-purchase setup support,
        Tablet Masters is built to route tablet customers into the right next step quickly.
      </p>

      <div class="support-hero-actions">
        <a class="btn-primary" href="insurance.php#book-form">Book a Repair</a>
        <a class="btn-outline" href="mailto:service@tablet-masters.com">Email Support</a>
      </div>

      <div class="support-jump-links" aria-label="Support page sections">
        <a href="#support-areas">Support Areas</a>
        <a href="#support-workflow">How It Works</a>
        <a href="#support-faq">FAQ</a>
        <a href="#support-cta">Get Help</a>
      </div>

      <div class="support-priority-grid">
        <?php foreach ($priorityBands as $band): ?>
        <div class="support-priority-card">
          <span><?= htmlspecialchars($band['label']) ?></span>
          <strong><?= htmlspecialchars($band['value']) ?></strong>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <aside class="support-hero-panel">
      <div class="support-panel-head">
        <div class="support-panel-eyebrow">What support covers</div>
        <h2>Use this page when you need help before, during, or after a purchase or repair.</h2>
      </div>

      <div class="support-panel-visual">
        <div class="support-panel-badge"><i class="fa-solid fa-headset"></i></div>
        <div class="support-panel-badge"><i class="fa-solid fa-tablet-screen-button"></i></div>
        <div class="support-panel-badge"><i class="fa-solid fa-envelope-open-text"></i></div>
      </div>

      <ul class="support-policy-list">
        <li><i class="fa-solid fa-check-circle"></i><span>Device troubleshooting and intake guidance</span></li>
        <li><i class="fa-solid fa-check-circle"></i><span>Coverage and replacement questions</span></li>
        <li><i class="fa-solid fa-check-circle"></i><span>Order and fulfillment follow-up</span></li>
        <li><i class="fa-solid fa-check-circle"></i><span>Cloud setup and onboarding help</span></li>
        <li><i class="fa-solid fa-check-circle"></i><span>Business, school, and fleet support</span></li>
      </ul>

      <div class="support-response-panel">
        <div class="support-panel-eyebrow">What happens next</div>
        <ul class="support-response-list">
          <?php foreach ($responseHighlights as $highlight): ?>
          <li><span class="check-dot">&#10003;</span><?= htmlspecialchars($highlight) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </div>
</section>

<section class="support-page">
  <div class="support-shell">
    <div class="support-metrics">
      <div class="support-metric">
        <strong>6</strong>
        <span>support lanes surfaced immediately</span>
      </div>
      <div class="support-metric">
        <strong>4</strong>
        <span>simple steps from issue to resolution path</span>
      </div>
      <div class="support-metric">
        <strong>1</strong>
        <span>place to start before you guess wrong</span>
      </div>
    </div>

    <div class="repair-section-title" id="support-areas">
      <div class="section-label">// How We Help</div>
      <div class="section-title" style="font-size:44px">SUPPORT AREAS</div>
      <p class="support-section-copy">Each lane is here to make the first decision easier: what kind of help you need, how urgent it is, and where you should go next.</p>
    </div>

    <div class="support-grid">
      <?php foreach ($supportAreas as $area): ?>
      <article class="support-card<?= $area['featured'] ? ' featured' : '' ?>">
        <span class="support-card-icon"><i class="<?= htmlspecialchars($area['icon']) ?>"></i></span>
        <h3><?= htmlspecialchars($area['title']) ?></h3>
        <p><?= htmlspecialchars($area['desc']) ?></p>
        <span class="support-card-tag<?= $area['featured'] ? ' gold' : '' ?>"><?= htmlspecialchars($area['label']) ?></span>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="support-workflow-wrap" id="support-workflow">
      <div class="repair-section-title" style="margin-bottom:24px">
        <div class="section-label">// Simple 4-Step Process</div>
        <div class="section-title" style="font-size:44px">HOW SUPPORT WORKS</div>
        <p class="support-section-copy">The page is designed to reduce unnecessary back-and-forth. Start with the issue type, share the device context, and we guide the next move.</p>
      </div>

      <div class="support-step-grid">
        <?php foreach ($supportSteps as $step): ?>
        <article class="support-step-card">
          <div class="support-step-number"><?= htmlspecialchars($step['num']) ?></div>
          <h3><?= htmlspecialchars($step['title']) ?></h3>
          <p><?= htmlspecialchars($step['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="support-faq-wrap" id="support-faq">
      <div class="repair-section-title" style="margin-bottom:24px">
        <div class="section-label">// Common Questions</div>
        <div class="section-title" style="font-size:44px">SUPPORT FAQ</div>
        <p class="support-section-copy">These are the questions most likely to slow a customer down before they reach out. The goal is to answer them early.</p>
      </div>

      <div class="support-faq-grid">
        <?php foreach ($supportFaq as $item): ?>
        <article class="support-faq-card">
          <h3><?= htmlspecialchars($item['title']) ?></h3>
          <p><?= htmlspecialchars($item['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>

    <section class="support-bottom-cta" id="support-cta">
      <div class="support-bottom-copy">
        <div class="section-label">Need help right now?</div>
        <h2 class="support-bottom-title">Start with the clearest path instead of guessing where your issue belongs.</h2>
        <p>
          Use repair booking if the device needs hands-on service, email us for coverage or order questions,
          or review plan coverage if you are still deciding how protected the device should be.
        </p>
        <p class="support-address">
          Mailing address:
          <strong>TabletmastersLLC, 550 Mary Esther Cutoff #18, PMB 376, Fort Walton Beach, FL 32548</strong>
        </p>
      </div>

      <div class="support-action-stack">
        <a class="btn-primary full" href="insurance.php#book-form">Go to Repair Booking</a>
        <a class="btn-outline full" href="mailto:service@tablet-masters.com">Email service@tablet-masters.com</a>
        <a class="btn-outline full" href="plans.php">Review Coverage Plans</a>
      </div>
    </section>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
