<?php $currentPage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Healthcare & Hospitals - Tablet Masters</title>
  <meta name="description" content="Tablet procurement, setup, kiosk configuration, patient education devices, and fleet support for hospitals, clinics, and care facilities." />
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
$allowedPrograms = ['operations', 'patient', 'enterprise'];
$requestedProgram = $_GET['program'] ?? 'operations';
$selectedProgram = in_array($requestedProgram, $allowedPrograms, true) ? $requestedProgram : 'operations';
$prefill = [
  'organization' => htmlspecialchars($_GET['organization'] ?? ''),
  'contact' => htmlspecialchars($_GET['contact'] ?? ''),
  'email' => htmlspecialchars($_GET['email'] ?? ''),
  'phone' => htmlspecialchars($_GET['phone'] ?? ''),
  'location' => htmlspecialchars($_GET['location'] ?? ''),
  'units' => htmlspecialchars($_GET['units'] ?? ''),
  'devices' => htmlspecialchars($_GET['devices'] ?? ''),
  'use_case' => htmlspecialchars($_GET['use_case'] ?? ''),
  'support_level' => htmlspecialchars($_GET['support_level'] ?? 'managed'),
];

$useCases = [
  ['icon' => '🩺', 'title' => 'Clinical Support Devices', 'desc' => 'Prepared tablets for rounding support, shared workflows, and department-specific operational use.'],
  ['icon' => '🛎️', 'title' => 'Check-In and Intake', 'desc' => 'Tablet kiosks and registration devices for front-desk intake, forms, and patient-facing entry workflows.'],
  ['icon' => '📚', 'title' => 'Patient Education', 'desc' => 'Configured tablets for discharge instructions, educational materials, and guided information delivery.'],
  ['icon' => '🔐', 'title' => 'Locked-Down Kiosk Mode', 'desc' => 'Restricted device configurations for public-facing or shared environments where consistency matters.'],
  ['icon' => '🧼', 'title' => 'Shared Device Readiness', 'desc' => 'Fleet planning that accounts for labeling, protective accessories, redeployment, and sanitation workflow support.'],
  ['icon' => '🔁', 'title' => 'Repair and Redeployment', 'desc' => 'Spare-device planning, repair intake, and secure reset support to reduce downtime in care settings.'],
];

$programs = [
  [
    'key' => 'operations',
    'name' => 'Hospital Operations',
    'eyebrow' => 'Core Program',
    'summary' => 'Built for internal staff workflows, check-in devices, shared-use tablets, and operational fleet setup.',
    'points' => ['Tablet sourcing and standardization', 'Account, app, and kiosk preparation', 'Labeling and accessory planning', 'Repair and replacement continuity'],
    'cta' => 'Request Operations Plan',
    'featured' => true,
  ],
  [
    'key' => 'patient',
    'name' => 'Patient Experience',
    'eyebrow' => 'Patient-Facing',
    'summary' => 'For bedside education, discharge content, waiting-room devices, communication workflows, and patient-facing support.',
    'points' => ['Patient education devices', 'Discharge and information workflows', 'Public-facing restrictions and content control', 'Shared-device reset and turnover support'],
    'cta' => 'Request Patient Experience Plan',
  ],
  [
    'key' => 'enterprise',
    'name' => 'Enterprise Health Systems',
    'eyebrow' => 'Multi-Site',
    'summary' => 'For hospital groups and larger systems that need procurement structure, fleet standards, and a scalable support model.',
    'points' => ['Multi-site planning', 'Volume purchasing guidance', 'Lifecycle and replacement strategy', 'Longer-term managed device support'],
    'cta' => 'Talk to Tablet Masters',
  ],
];

$workflow = [
  ['num' => '01', 'title' => 'Map the Environment', 'desc' => 'We define departments, patient-facing versus staff use, device count, kiosk needs, and how the tablets will be used day to day.'],
  ['num' => '02', 'title' => 'Configure the Fleet', 'desc' => 'Tablet Masters prepares apps, restrictions, accessories, labels, and role-specific configurations before deployment.'],
  ['num' => '03', 'title' => 'Deploy with Structure', 'desc' => 'The organization receives a more consistent rollout with clearer assignment, replacement planning, and operational readiness.'],
  ['num' => '04', 'title' => 'Maintain Continuity', 'desc' => 'Repair handling, spare-device planning, secure reset, and redeployment support keep the fleet useful over time.'],
];

$advantages = [
  ['title' => 'Operational Focus', 'desc' => 'The public offer stays grounded in device operations, deployment, and continuity instead of overpromising complex clinical software integration.'],
  ['title' => 'Patient-Facing Readiness', 'desc' => 'Hospitals and clinics can support bedside education and intake workflows with better-controlled tablet configurations.'],
  ['title' => 'Shared Device Discipline', 'desc' => 'Tablet Masters can help standardize accessory fit, labeling, reset routines, and redeployment practices for shared-use environments.'],
  ['title' => 'Practical Support Path', 'desc' => 'Procurement, setup, protection, replacement, and service can be treated as one operating system instead of separate vendors.'],
];
?>

<div class="conference-section">
  <div class="ins-hero conference-hero">
    <div class="ins-hero-text">
      <div class="section-label">// Healthcare & Hospitals</div>
      <div class="section-title">TABLET SUPPORT<br />FOR CARE SETTINGS</div>
      <p>
        Tablet Masters helps hospitals, clinics, and care facilities deploy tablets with more structure and less setup friction.
        We support procurement, configuration, kiosk mode, patient education devices, repair continuity, and secure redeployment.
      </p>
      <p>
        The public offer is operational by design: device fleets for staff workflows, intake, education, and patient-facing support,
        without making inflated claims about regulated clinical systems.
      </p>
      <div class="ins-actions">
        <a class="btn-primary" href="#healthcare-quote">Request a Healthcare Plan</a>
        <a class="btn-outline" href="support.php">Talk to Support</a>
      </div>

      <div class="conference-metrics">
        <div class="conference-metric">
          <span class="conference-metric-value">3</span>
          <span class="conference-metric-label">program lanes for operations, patient-facing use, and larger systems</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">2</span>
          <span class="conference-metric-label">core audiences: internal staff workflows and patient-facing workflows</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">4</span>
          <span class="conference-metric-label">steps from fleet mapping to redeployment continuity</span>
        </div>
      </div>
    </div>

    <div class="ins-coverage-card conference-coverage-card">
      <span class="ins-shield">🏥</span>
      <h3>WHAT TABLET MASTERS HANDLES</h3>
      <p>Built for hospital operations, check-in, shared devices, patient education, and controlled tablet environments.</p>
      <ul class="coverage-checklist">
        <li><span class="check-dot">&#10003;</span> Tablet sourcing and standardization planning</li>
        <li><span class="check-dot">&#10003;</span> App install, restrictions, kiosk mode, and account setup</li>
        <li><span class="check-dot">&#10003;</span> Patient education and intake device support</li>
        <li><span class="check-dot">&#10003;</span> Protective accessories and shared-device readiness</li>
        <li><span class="check-dot">&#10003;</span> Repair intake, swap planning, and secure redeployment</li>
        <li><span class="check-dot">&#10003;</span> Managed support for larger device programs</li>
      </ul>
    </div>
  </div>

  <div class="repair-section-title">
    <div class="section-label">// Where It Fits</div>
    <div class="section-title" style="font-size:44px">HEALTHCARE USE CASES</div>
  </div>

  <div class="repair-grid">
    <?php foreach ($useCases as $case): ?>
    <div class="repair-card conference-card">
      <span class="repair-icon"><?= $case['icon'] ?></span>
      <h4><?= htmlspecialchars($case['title']) ?></h4>
      <p><?= htmlspecialchars($case['desc']) ?></p>
      <span class="repair-price-tag">Care operations</span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:32px">
    <div class="section-label">// Program Structure</div>
    <div class="section-title" style="font-size:44px">SERVICE PROGRAMS</div>
  </div>

  <div class="conference-package-grid">
    <?php foreach ($programs as $program): ?>
    <div class="conference-package-card<?= !empty($program['featured']) ? ' featured' : '' ?>">
      <div class="conference-package-eyebrow"><?= htmlspecialchars($program['eyebrow']) ?></div>
      <div class="conference-package-name"><?= htmlspecialchars($program['name']) ?></div>
      <p class="conference-package-summary"><?= htmlspecialchars($program['summary']) ?></p>
      <ul class="conference-package-list">
        <?php foreach ($program['points'] as $point): ?>
        <li><span class="plan-dot"></span><?= htmlspecialchars($point) ?></li>
        <?php endforeach; ?>
      </ul>
      <a class="<?= !empty($program['featured']) ? 'btn-primary' : 'btn-outline' ?> full" href="healthcare-hospitals.php?program=<?= htmlspecialchars($program['key']) ?>#healthcare-quote"><?= htmlspecialchars($program['cta']) ?></a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:48px">
    <div class="section-label">// Delivery Model</div>
    <div class="section-title" style="font-size:44px">HOW HEALTHCARE SUPPORT WORKS</div>
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
    <div class="section-title" style="font-size:44px">PROGRAM ADVANTAGES</div>
  </div>

  <div class="conference-detail-grid">
    <?php foreach ($advantages as $item): ?>
    <div class="conference-detail-card">
      <h4><?= htmlspecialchars($item['title']) ?></h4>
      <p><?= htmlspecialchars($item['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-cta" id="healthcare-quote">
    <div>
      <h3>BUILD A CLEANER TABLET PROGRAM FOR CARE ENVIRONMENTS</h3>
      <p>
        If your hospital, clinic, or care facility needs tablets for operations, intake, education, or patient-facing support,
        Tablet Masters can help define a more consistent deployment and maintenance plan.
      </p>
      <p>
        Start with the organization type, department use case, and estimated device count. We can help turn that into
        a practical support model.
      </p>
      <p class="conference-form-note">
        Share the basics and Tablet Masters will follow up with a recommended approach, device strategy, and next steps.
      </p>
    </div>

    <div class="repair-form">
      <?php if ($quoteSuccess): ?>
      <div class="alert alert-success">&#10003; Healthcare inquiry submitted. Tablet Masters will follow up shortly.</div>
      <?php elseif ($quoteError): ?>
      <div class="alert alert-error">Something went wrong. Please review the form and try again.</div>
      <?php endif; ?>

      <form method="POST" action="send-healthcare-quote.php">
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="organization_name" placeholder="Hospital, clinic, or care organization" required value="<?= $prefill['organization'] ?>" />
          <input class="repair-input" type="text" name="contact_name" placeholder="Primary contact name" required value="<?= $prefill['contact'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
          <input class="repair-input" type="tel" name="phone" placeholder="Phone number" value="<?= $prefill['phone'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="location" placeholder="City, state, or service area" required value="<?= $prefill['location'] ?>" />
          <input class="repair-input" type="text" name="units_departments" placeholder="Departments, units, or facility type" required value="<?= $prefill['units'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="number" min="1" name="device_count" placeholder="Estimated tablet count" required value="<?= $prefill['devices'] ?>" />
          <select class="repair-input" name="program" required>
            <option value="operations" <?= $selectedProgram === 'operations' ? 'selected' : '' ?>>Hospital Operations</option>
            <option value="patient" <?= $selectedProgram === 'patient' ? 'selected' : '' ?>>Patient Experience</option>
            <option value="enterprise" <?= $selectedProgram === 'enterprise' ? 'selected' : '' ?>>Enterprise Health Systems</option>
          </select>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="use_case" placeholder="Primary use case: intake, education, kiosks, staff devices, etc." required value="<?= $prefill['use_case'] ?>" />
          <select class="repair-input" name="support_level">
            <option value="managed" <?= $prefill['support_level'] === 'managed' ? 'selected' : '' ?>>Support preference: Ongoing managed support</option>
            <option value="deployment" <?= $prefill['support_level'] === 'deployment' ? 'selected' : '' ?>>Support preference: Deployment help only</option>
            <option value="not-sure" <?= $prefill['support_level'] === 'not-sure' ? 'selected' : '' ?>>Support preference: Not sure yet</option>
          </select>
        </div>
        <textarea class="repair-input conference-textarea" name="requirements" placeholder="Tell us about kiosk mode, apps, patient education content, accessories, resets, or support needs"></textarea>
        <textarea class="repair-input conference-textarea" name="notes" placeholder="Anything else we should know about timelines, facility needs, departments, or deployment challenges?"></textarea>
        <button type="submit" class="btn-primary full">Submit Healthcare Inquiry</button>
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
