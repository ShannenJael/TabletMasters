<?php $currentPage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Business Conferences - Tablet Masters</title>
  <meta name="description" content="Conference-ready tablet rentals, configuration, deployment, and support for business events, trade shows, and field teams." />
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
$quoteSuccess = isset($_GET['sent']);
$quoteError = isset($_GET['err']);
$allowedPackages = ['starter', 'managed', 'enterprise'];
$requestedPackage = $_GET['package'] ?? 'managed';
$selectedPackage = in_array($requestedPackage, $allowedPackages, true) ? $requestedPackage : 'managed';
$prefill = [
  'company' => htmlspecialchars($_GET['company'] ?? ''),
  'contact' => htmlspecialchars($_GET['contact'] ?? ''),
  'email' => htmlspecialchars($_GET['email'] ?? ''),
  'phone' => htmlspecialchars($_GET['phone'] ?? ''),
  'event' => htmlspecialchars($_GET['event'] ?? ''),
  'location' => htmlspecialchars($_GET['location'] ?? ''),
  'dates' => htmlspecialchars($_GET['dates'] ?? ''),
  'count' => htmlspecialchars($_GET['count'] ?? ''),
  'use_case' => htmlspecialchars($_GET['use_case'] ?? ''),
  'support_level' => htmlspecialchars($_GET['support_level'] ?? 'remote'),
];

$useCases = [
  ['icon' => '🎟️', 'title' => 'Check-In Stations', 'desc' => 'Tablet fleets for attendee registration, badge lookup, waivers, and front-desk flow at busy events.'],
  ['icon' => '🧾', 'title' => 'Lead Capture', 'desc' => 'Booth tablets configured for form capture, surveys, and CRM-friendly intake during expo traffic.'],
  ['icon' => '📺', 'title' => 'Demo Kiosks', 'desc' => 'Locked-down demo tablets for exhibitors, sponsors, product launches, and interactive sales stations.'],
  ['icon' => '🎤', 'title' => 'Speaker Support', 'desc' => 'Presentation tablets, presenter notes, session control, and backup devices for key moments on stage.'],
  ['icon' => '🧑‍💼', 'title' => 'Staff Operations', 'desc' => 'Temporary devices for floor teams, event managers, runners, and internal coordination during setup and show days.'],
  ['icon' => '📊', 'title' => 'Surveys and Feedback', 'desc' => 'Post-session and post-event data collection with clean app setup and quick handoff to reporting workflows.'],
];

$packages = [
  [
    'key' => 'starter',
    'name' => 'Starter',
    'eyebrow' => 'Small Events',
    'summary' => 'Best for pilot programs, VIP lounges, smaller registrations, and light booth deployments.',
    'points' => ['5 to 20 tablets', 'Pre-event setup and app install', 'Device labeling and inventory list', 'Remote support during event window'],
    'cta' => 'Request Starter Quote',
  ],
  [
    'key' => 'managed',
    'name' => 'Managed Event',
    'eyebrow' => 'Most Popular',
    'summary' => 'Built for larger conferences that need dependable setup, shipping coordination, and support coverage.',
    'points' => ['20 to 100 tablets', 'Wi-Fi and kiosk mode configuration', 'Branding, asset tags, and staging', 'Backup units plus event support coordination'],
    'cta' => 'Request Managed Event Quote',
    'featured' => true,
  ],
  [
    'key' => 'enterprise',
    'name' => 'Enterprise Conference',
    'eyebrow' => 'High Volume',
    'summary' => 'For national events, multi-room operations, sponsor rollouts, and repeat conference programs.',
    'points' => ['100+ tablets or mixed fleets', 'MDM enrollment and policy control', 'Venue logistics and recovery planning', 'Custom app prep and white-glove deployment'],
    'cta' => 'Talk to Tablet Masters',
  ],
];

$workflow = [
  ['num' => '01', 'title' => 'Scope the Event', 'desc' => 'We define tablet count, venue timing, app requirements, Wi-Fi constraints, and support expectations.'],
  ['num' => '02', 'title' => 'Stage Every Device', 'desc' => 'Tablet Masters configures apps, restrictions, accounts, branding, and labeling before anything ships.'],
  ['num' => '03', 'title' => 'Deploy with Backup', 'desc' => 'Your fleet arrives with inventory structure, contingency units, and a support path for event-day issues.'],
  ['num' => '04', 'title' => 'Recover and Reset', 'desc' => 'After the event, we handle return planning, wipe procedures, and readiness for the next deployment.'],
];

$advantages = [
  ['title' => 'Conference-Ready Setup', 'desc' => 'Kiosk mode, account sign-in, app loading, Wi-Fi prep, signage labels, and role-based device assignments.'],
  ['title' => 'Protection Included', 'desc' => 'Coverage planning and spare-device strategy reduce the risk of a single damaged unit disrupting the event floor.'],
  ['title' => 'Logistics Discipline', 'desc' => 'Advance warehouse timing, venue delivery planning, return instructions, and fleet tracking from staging through recovery.'],
  ['title' => 'Tablet-Only Expertise', 'desc' => 'This is not generic AV support. Tablet Masters is built around tablet sales, service, setup, and operational support.'],
];
?>

<div class="conference-section">
  <div class="ins-hero conference-hero">
    <div class="ins-hero-text">
      <div class="section-label">// Business Conferences</div>
      <div class="section-title">CONFERENCE-READY<br />TABLET SUPPORT</div>
      <p>
        Tablet Masters helps business conferences run on prepared hardware instead of last-minute setup.
        We source, configure, stage, ship, support, and recover tablet fleets for check-in, demos, surveys,
        speaker support, and event operations.
      </p>
      <p>
        The offer is simple: your team focuses on the event, while we handle the tablet side with structure,
        backup planning, and a cleaner operational workflow.
      </p>
      <div class="ins-actions">
        <a class="btn-primary" href="#conference-quote">Request a Quote</a>
        <a class="btn-outline" href="support.php">Talk to Support</a>
      </div>

      <div class="conference-metrics">
        <div class="conference-metric">
          <span class="conference-metric-value">5+</span>
          <span class="conference-metric-label">event use cases supported</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">3</span>
          <span class="conference-metric-label">service tiers for conference scale</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">1</span>
          <span class="conference-metric-label">single team handling setup to recovery</span>
        </div>
      </div>
    </div>

    <div class="ins-coverage-card conference-coverage-card">
      <span class="ins-shield">📦</span>
      <h3>WHAT TABLET MASTERS HANDLES</h3>
      <p>Built for conferences, trade shows, training events, and field-heavy business activations.</p>
      <ul class="coverage-checklist">
        <li><span class="check-dot">&#10003;</span> Rental or procurement planning for the right tablet mix</li>
        <li><span class="check-dot">&#10003;</span> App installation, kiosk mode, branding, and account setup</li>
        <li><span class="check-dot">&#10003;</span> Inventory labels, assignment lists, and spare-device planning</li>
        <li><span class="check-dot">&#10003;</span> Venue shipping coordination and pre-event staging</li>
        <li><span class="check-dot">&#10003;</span> Remote support path during the event window</li>
        <li><span class="check-dot">&#10003;</span> Post-event return, wipe, and redeployment preparation</li>
      </ul>
    </div>
  </div>

  <div class="repair-section-title">
    <div class="section-label">// Where It Fits</div>
    <div class="section-title" style="font-size:44px">USE CASES</div>
  </div>

  <div class="repair-grid">
    <?php foreach ($useCases as $case): ?>
    <div class="repair-card conference-card">
      <span class="repair-icon"><?= $case['icon'] ?></span>
      <h4><?= htmlspecialchars($case['title']) ?></h4>
      <p><?= htmlspecialchars($case['desc']) ?></p>
      <span class="repair-price-tag">Conference workflow</span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:32px">
    <div class="section-label">// Service Plan</div>
    <div class="section-title" style="font-size:44px">PACKAGE STRUCTURE</div>
  </div>

  <div class="conference-package-grid">
    <?php foreach ($packages as $package): ?>
    <div class="conference-package-card<?= !empty($package['featured']) ? ' featured' : '' ?>">
      <div class="conference-package-eyebrow"><?= htmlspecialchars($package['eyebrow']) ?></div>
      <div class="conference-package-name"><?= htmlspecialchars($package['name']) ?></div>
      <p class="conference-package-summary"><?= htmlspecialchars($package['summary']) ?></p>
      <ul class="conference-package-list">
        <?php foreach ($package['points'] as $point): ?>
        <li><span class="plan-dot"></span><?= htmlspecialchars($point) ?></li>
        <?php endforeach; ?>
      </ul>
      <a class="<?= !empty($package['featured']) ? 'btn-primary' : 'btn-outline' ?> full" href="business-conferences.php?package=<?= htmlspecialchars($package['key']) ?>#conference-quote"><?= htmlspecialchars($package['cta']) ?></a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:48px">
    <div class="section-label">// Delivery Model</div>
    <div class="section-title" style="font-size:44px">HOW IT WORKS</div>
  </div>

  <div class="claim-steps">
    <?php foreach ($workflow as $step): ?>
    <div class="claim-step">
      <div class="claim-step-num"><?= htmlspecialchars($step['num']) ?></div>
      <h5><?= htmlspecialchars($step['title']) ?></h5>
      <p><?= htmlspecialchars($step['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:32px">
    <div class="section-label">// Why This Offer Works</div>
    <div class="section-title" style="font-size:44px">OPERATING ADVANTAGES</div>
  </div>

  <div class="conference-detail-grid">
    <?php foreach ($advantages as $item): ?>
    <div class="conference-detail-card">
      <h4><?= htmlspecialchars($item['title']) ?></h4>
      <p><?= htmlspecialchars($item['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-cta" id="conference-quote">
    <div>
      <h3>PLAN THE TABLET SIDE BEFORE THE EVENT RUSH</h3>
      <p>
        If your conference needs tablets for registration, exhibitors, session teams, or data capture,
        Tablet Masters can scope the fleet and build the operating plan before the event window gets tight.
      </p>
      <p>
        Start with the event size, location, dates, and what each tablet needs to do. We can turn that into
        a practical deployment plan.
      </p>
      <p class="conference-form-note">
        Share the conference basics and Tablet Masters will follow up with a practical recommendation, timeline,
        and quote path.
      </p>
    </div>

    <div class="repair-form">
      <?php if ($quoteSuccess): ?>
      <div class="alert alert-success">&#10003; Quote request submitted. Tablet Masters will follow up shortly.</div>
      <?php elseif ($quoteError): ?>
      <div class="alert alert-error">Something went wrong. Please review the form and try again.</div>
      <?php endif; ?>

      <form method="POST" action="send-conference-quote.php">
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="company_name" placeholder="Company or organization" required value="<?= $prefill['company'] ?>" />
          <input class="repair-input" type="text" name="contact_name" placeholder="Primary contact name" required value="<?= $prefill['contact'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
          <input class="repair-input" type="tel" name="phone" placeholder="Phone number" value="<?= $prefill['phone'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="event_name" placeholder="Conference or event name" required value="<?= $prefill['event'] ?>" />
          <input class="repair-input" type="text" name="event_location" placeholder="City, venue, or destination" required value="<?= $prefill['location'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="event_dates" placeholder="Event dates or setup window" required value="<?= $prefill['dates'] ?>" />
          <input class="repair-input" type="number" min="1" name="tablet_count" placeholder="Estimated tablet count" required value="<?= $prefill['count'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <select class="repair-input" name="package" required>
            <option value="starter" <?= $selectedPackage === 'starter' ? 'selected' : '' ?>>Starter package</option>
            <option value="managed" <?= $selectedPackage === 'managed' ? 'selected' : '' ?>>Managed Event package</option>
            <option value="enterprise" <?= $selectedPackage === 'enterprise' ? 'selected' : '' ?>>Enterprise Conference package</option>
          </select>
          <select class="repair-input" name="support_level">
            <option value="remote" <?= $prefill['support_level'] === 'remote' ? 'selected' : '' ?>>Support preference: Remote support</option>
            <option value="onsite" <?= $prefill['support_level'] === 'onsite' ? 'selected' : '' ?>>Support preference: On-site coordination</option>
            <option value="not-sure" <?= $prefill['support_level'] === 'not-sure' ? 'selected' : '' ?>>Support preference: Not sure yet</option>
          </select>
        </div>
        <input class="repair-input" type="text" name="use_case" placeholder="Primary use case: check-in, demos, surveys, staff ops, etc." required value="<?= $prefill['use_case'] ?>" />
        <textarea class="repair-input conference-textarea" name="app_requirements" placeholder="Apps, kiosk mode, branding, Wi-Fi, account setup, or MDM requirements"></textarea>
        <textarea class="repair-input conference-textarea" name="notes" placeholder="Anything else we should know about timing, logistics, or the event workflow?"></textarea>
        <button type="submit" class="btn-primary full">Submit Conference Quote Request</button>
      </form>

      <a class="btn-outline full" href="shop.php">Review Tablet Inventory</a>
      <a class="btn-outline full" href="support.php">Talk Through Requirements</a>
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
