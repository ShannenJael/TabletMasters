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
  ['icon' => 'fa-solid fa-clipboard-check', 'title' => 'Check-In Stations', 'desc' => 'Tablet fleets for attendee registration, badge lookup, waivers, and front-desk flow at busy events.'],
  ['icon' => 'fa-solid fa-address-card', 'title' => 'Lead Capture', 'desc' => 'Booth tablets configured for form capture, surveys, and CRM-friendly intake during expo traffic.'],
  ['icon' => 'fa-solid fa-tablet-screen-button', 'title' => 'Demo Kiosks', 'desc' => 'Locked-down demo tablets for exhibitors, sponsors, product launches, and interactive sales stations.'],
  ['icon' => 'fa-solid fa-microphone-lines', 'title' => 'Speaker Support', 'desc' => 'Presentation tablets, presenter notes, session control, and backup devices for key moments on stage.'],
  ['icon' => 'fa-solid fa-people-group', 'title' => 'Staff Operations', 'desc' => 'Temporary devices for floor teams, event managers, runners, and internal coordination during setup and show days.'],
  ['icon' => 'fa-solid fa-square-poll-horizontal', 'title' => 'Surveys and Feedback', 'desc' => 'Post-session and post-event data collection with clean app setup and quick handoff to reporting workflows.'],
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

$priorityBands = [
  ['label' => 'Best For', 'value' => 'Check-in, exhibitor demos, surveys, staff operations, and speaker support'],
  ['label' => 'Typical Support', 'value' => 'Tablet staging, kiosk setup, shipping coordination, backup units, and recovery'],
  ['label' => 'What You Get', 'value' => 'A cleaner event-device workflow that reduces last-minute setup pressure'],
];

$formHighlights = [
  'Practical recommendation based on event size, timing, and use case',
  'Clear handoff on staging, shipping, and support expectations',
  'A quote path built around conference logistics instead of generic rentals',
];
?>

<div class="conference-section conference-page-alt">
  <div class="conference-page-hero conference-hero">
    <div class="conference-page-copy-shell conference-page-copy">
      <div class="section-label">// Business Conferences</div>
      <div class="section-title">CONFERENCE-READY<br />TABLET SUPPORT</div>
      <p class="conference-page-lead">
        Tablet Masters helps business conferences run on prepared hardware instead of last-minute setup.
        We source, configure, stage, ship, support, and recover tablet fleets for check-in, demos, surveys,
        speaker support, and event operations.
      </p>

      <div class="conference-page-actions">
        <a class="btn-primary" href="#conference-quote">Request a Quote</a>
        <a class="btn-outline" href="#conference-packages">See Package Options</a>
      </div>

      <div class="conference-page-jump-links" aria-label="Conference page sections">
        <a href="#conference-use-cases">Use Cases</a>
        <a href="#conference-packages">Packages</a>
        <a href="#conference-workflow">How It Works</a>
        <a href="#conference-quote">Request Quote</a>
      </div>

      <div class="conference-page-priority-grid">
        <?php foreach ($priorityBands as $band): ?>
        <div class="conference-page-priority-card">
          <span><?= htmlspecialchars($band['label']) ?></span>
          <strong><?= htmlspecialchars($band['value']) ?></strong>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="conference-metrics">
        <div class="conference-metric">
          <span class="conference-metric-value">5+</span>
          <span class="conference-metric-label">event use cases supported across check-in, demos, speaker support, and surveys</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">3</span>
          <span class="conference-metric-label">service tiers for conference scale, from pilots to enterprise events</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">1</span>
          <span class="conference-metric-label">single operational path from staging to recovery</span>
        </div>
      </div>
    </div>

    <div class="conference-page-coverage-panel conference-coverage-card conference-page-coverage-card">
      <span class="conference-page-coverage-icon"><i class="fa-solid fa-box-open"></i></span>
      <h3>WHAT TABLET MASTERS HANDLES</h3>
      <p>Built for conferences, trade shows, training events, and field-heavy business activations.</p>
      <ul class="conference-page-coverage-list">
        <li><span class="check-dot">&#10003;</span> Rental or procurement planning for the right tablet mix</li>
        <li><span class="check-dot">&#10003;</span> App installation, kiosk mode, branding, and account setup</li>
        <li><span class="check-dot">&#10003;</span> Inventory labels, assignment lists, and spare-device planning</li>
        <li><span class="check-dot">&#10003;</span> Venue shipping coordination and pre-event staging</li>
        <li><span class="check-dot">&#10003;</span> Remote support path during the event window</li>
        <li><span class="check-dot">&#10003;</span> Post-event return, wipe, and redeployment preparation</li>
      </ul>

      <div class="conference-page-coverage-note">
        <strong>Built for event pressure</strong>
        <span>The page now surfaces the operational parts first, so buyers can understand how setup, support, and recovery fit together.</span>
      </div>
    </div>
  </div>

  <div class="conference-page-section-head" id="conference-use-cases">
    <div class="section-label">// Where It Fits</div>
    <div class="section-title" style="font-size:44px">USE CASES</div>
    <p class="conference-page-section-copy">Start with what each tablet needs to do during the event. Once the role is clear, staging, accessories, kiosk mode, and support become much easier to scope.</p>
  </div>

  <div class="conference-page-use-grid">
    <?php foreach ($useCases as $case): ?>
    <div class="conference-card conference-page-use-card">
      <span class="conference-page-use-icon"><i class="<?= htmlspecialchars($case['icon']) ?>"></i></span>
      <h4><?= htmlspecialchars($case['title']) ?></h4>
      <p><?= htmlspecialchars($case['desc']) ?></p>
      <span class="conference-page-use-tag">Conference workflow</span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="conference-page-section-head" id="conference-packages" style="margin-bottom:32px">
    <div class="section-label">// Service Plan</div>
    <div class="section-title" style="font-size:44px">PACKAGE STRUCTURE</div>
    <p class="conference-page-section-copy">Each package is designed to make the buying path simpler. Pick the scale that matches the event, and the quote form below will carry that choice through.</p>
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

  <div class="conference-page-section-head" id="conference-workflow" style="margin-bottom:48px">
    <div class="section-label">// Delivery Model</div>
    <div class="section-title" style="font-size:44px">HOW IT WORKS</div>
    <p class="conference-page-section-copy">The event-device path should feel predictable. Tablet Masters scopes the event, stages every device, supports the deployment window, and prepares recovery afterward.</p>
  </div>

  <div class="conference-page-step-grid">
    <?php foreach ($workflow as $step): ?>
    <div class="conference-page-step-card">
      <div class="conference-page-step-num"><?= htmlspecialchars($step['num']) ?></div>
      <h5><?= htmlspecialchars($step['title']) ?></h5>
      <p><?= htmlspecialchars($step['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="conference-page-section-head" style="margin-bottom:32px">
    <div class="section-label">// Why This Offer Works</div>
    <div class="section-title" style="font-size:44px">OPERATING ADVANTAGES</div>
    <p class="conference-page-section-copy">This works best when tablet setup, backup planning, event support, and recovery are treated as one system instead of separate last-minute tasks.</p>
  </div>

  <div class="conference-detail-grid">
    <?php foreach ($advantages as $item): ?>
    <div class="conference-detail-card">
      <h4><?= htmlspecialchars($item['title']) ?></h4>
      <p><?= htmlspecialchars($item['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="conference-page-cta" id="conference-quote">
    <div class="conference-page-cta-copy">
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

      <div class="conference-page-response-panel">
        <div class="conference-package-eyebrow">What Happens Next</div>
        <ul class="conference-page-response-list">
          <?php foreach ($formHighlights as $highlight): ?>
          <li><span class="check-dot">&#10003;</span><?= htmlspecialchars($highlight) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="conference-page-form-shell">
      <?php if ($quoteSuccess): ?>
      <div class="alert alert-success">&#10003; Quote request submitted. Tablet Masters will follow up shortly.</div>
      <?php elseif ($quoteError): ?>
      <div class="alert alert-error">Something went wrong. Please review the form and try again.</div>
      <?php endif; ?>

      <form method="POST" action="send-conference-quote.php" class="conference-page-form">
        <div class="conference-page-form-intro">
          <strong>Tell us about the event</strong>
          <span>The clearer the event size, timing, and device role, the cleaner the first quote path will be.</span>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <label class="conference-page-field">
            <span>Company</span>
            <input class="repair-input" type="text" name="company_name" placeholder="Company or organization" required value="<?= $prefill['company'] ?>" />
          </label>
          <label class="conference-page-field">
            <span>Primary Contact</span>
            <input class="repair-input" type="text" name="contact_name" placeholder="Primary contact name" required value="<?= $prefill['contact'] ?>" />
          </label>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <label class="conference-page-field">
            <span>Email</span>
            <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
          </label>
          <label class="conference-page-field">
            <span>Phone</span>
            <input class="repair-input" type="tel" name="phone" placeholder="Phone number" value="<?= $prefill['phone'] ?>" />
          </label>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <label class="conference-page-field">
            <span>Event Name</span>
            <input class="repair-input" type="text" name="event_name" placeholder="Conference or event name" required value="<?= $prefill['event'] ?>" />
          </label>
          <label class="conference-page-field">
            <span>Location</span>
            <input class="repair-input" type="text" name="event_location" placeholder="City, venue, or destination" required value="<?= $prefill['location'] ?>" />
          </label>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <label class="conference-page-field">
            <span>Event Dates</span>
            <input class="repair-input" type="text" name="event_dates" placeholder="Event dates or setup window" required value="<?= $prefill['dates'] ?>" />
          </label>
          <label class="conference-page-field">
            <span>Tablet Count</span>
            <input class="repair-input" type="number" min="1" name="tablet_count" placeholder="Estimated tablet count" required value="<?= $prefill['count'] ?>" />
          </label>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <label class="conference-page-field">
            <span>Package</span>
            <select class="repair-input" name="package" required>
              <option value="starter" <?= $selectedPackage === 'starter' ? 'selected' : '' ?>>Starter package</option>
              <option value="managed" <?= $selectedPackage === 'managed' ? 'selected' : '' ?>>Managed Event package</option>
              <option value="enterprise" <?= $selectedPackage === 'enterprise' ? 'selected' : '' ?>>Enterprise Conference package</option>
            </select>
          </label>
          <label class="conference-page-field">
            <span>Support Preference</span>
            <select class="repair-input" name="support_level">
              <option value="remote" <?= $prefill['support_level'] === 'remote' ? 'selected' : '' ?>>Remote support</option>
              <option value="onsite" <?= $prefill['support_level'] === 'onsite' ? 'selected' : '' ?>>On-site coordination</option>
              <option value="not-sure" <?= $prefill['support_level'] === 'not-sure' ? 'selected' : '' ?>>Not sure yet</option>
            </select>
          </label>
        </div>
        <label class="conference-page-field">
          <span>Primary Use Case</span>
          <input class="repair-input" type="text" name="use_case" placeholder="Check-in, demos, surveys, staff ops, speaker support, etc." required value="<?= $prefill['use_case'] ?>" />
        </label>
        <label class="conference-page-field">
          <span>App and Setup Requirements</span>
          <textarea class="repair-input conference-textarea" name="app_requirements" placeholder="Apps, kiosk mode, branding, Wi-Fi, account setup, or MDM requirements"></textarea>
        </label>
        <label class="conference-page-field">
          <span>Additional Notes</span>
          <textarea class="repair-input conference-textarea" name="notes" placeholder="Anything else we should know about timing, logistics, or the event workflow?"></textarea>
        </label>
        <button type="submit" class="btn-primary full">Submit Conference Quote Request</button>
      </form>

      <div class="conference-page-form-actions">
        <a class="btn-outline full" href="shop.php">Review Tablet Inventory</a>
        <a class="btn-outline full" href="support.php">Talk Through Requirements</a>
      </div>
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
