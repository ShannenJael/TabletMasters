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
  ['icon' => 'fa-solid fa-user-doctor', 'title' => 'Clinical Support Devices', 'desc' => 'Prepared tablets for rounding support, shared workflows, and department-specific operational use.'],
  ['icon' => 'fa-solid fa-clipboard-list', 'title' => 'Check-In and Intake', 'desc' => 'Tablet kiosks and registration devices for front-desk intake, forms, and patient-facing entry workflows.'],
  ['icon' => 'fa-solid fa-book-open-reader', 'title' => 'Patient Education', 'desc' => 'Configured tablets for discharge instructions, educational materials, and guided information delivery.'],
  ['icon' => 'fa-solid fa-lock', 'title' => 'Locked-Down Kiosk Mode', 'desc' => 'Restricted device configurations for public-facing or shared environments where consistency matters.'],
  ['icon' => 'fa-solid fa-pump-soap', 'title' => 'Shared Device Readiness', 'desc' => 'Fleet planning that accounts for labeling, protective accessories, redeployment, and sanitation workflow support.'],
  ['icon' => 'fa-solid fa-arrows-rotate', 'title' => 'Repair and Redeployment', 'desc' => 'Spare-device planning, repair intake, and secure reset support to reduce downtime in care settings.'],
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

$priorityBands = [
  ['label' => 'Best For', 'value' => 'Check-in, bedside education, staff workflows, and shared-device fleets'],
  ['label' => 'Typical Support', 'value' => 'Procurement, setup, kiosk controls, repairs, and redeployment'],
  ['label' => 'What You Get', 'value' => 'A cleaner device program with fewer handoff gaps and less setup friction'],
];

$formHighlights = [
  'Recommended response path within one business day',
  'Planning help for device counts, kiosk mode, and support model',
  'Built for hospitals, clinics, and multi-site care programs',
];
?>

<div class="conference-section healthcare-page">
  <div class="healthcare-page-hero conference-hero">
    <div class="healthcare-hero-copy-shell healthcare-hero-copy">
      <div class="section-label">// Healthcare & Hospitals</div>
      <div class="section-title">TABLET SUPPORT<br />FOR CARE SETTINGS</div>
      <p class="healthcare-lead">
        Tablet Masters helps hospitals, clinics, and care facilities deploy tablets with more structure and less setup friction.
        We support procurement, configuration, kiosk mode, patient education devices, repair continuity, and secure redeployment
        across both staff and patient-facing environments.
      </p>

      <div class="healthcare-actions">
        <a class="btn-primary" href="#healthcare-quote">Request a Healthcare Plan</a>
        <a class="btn-outline" href="#healthcare-programs">See Program Options</a>
      </div>

      <div class="healthcare-jump-links" aria-label="Healthcare page sections">
        <a href="#healthcare-use-cases">Use Cases</a>
        <a href="#healthcare-programs">Programs</a>
        <a href="#healthcare-workflow">How It Works</a>
        <a href="#healthcare-quote">Request Plan</a>
      </div>

      <div class="healthcare-priority-grid">
        <?php foreach ($priorityBands as $band): ?>
        <div class="healthcare-priority-card">
          <span><?= htmlspecialchars($band['label']) ?></span>
          <strong><?= htmlspecialchars($band['value']) ?></strong>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="healthcare-metrics">
        <div class="healthcare-metric">
          <span class="healthcare-metric-value">3</span>
          <span class="healthcare-metric-label">program lanes for operations, patient-facing workflows, and multi-site systems</span>
        </div>
        <div class="healthcare-metric">
          <span class="healthcare-metric-value">2</span>
          <span class="healthcare-metric-label">core environments: internal staff use and patient-facing use</span>
        </div>
        <div class="healthcare-metric">
          <span class="healthcare-metric-value">4</span>
          <span class="healthcare-metric-label">delivery stages from fleet mapping to redeployment continuity</span>
        </div>
      </div>
    </div>

    <div class="healthcare-coverage-panel healthcare-coverage-card">
      <span class="healthcare-coverage-icon"><i class="fa-solid fa-hospital"></i></span>
      <h3>WHAT TABLET MASTERS HANDLES</h3>
      <p>Built for hospital operations, intake, patient education, shared devices, and controlled tablet environments.</p>
      <ul class="healthcare-coverage-list">
        <li><span class="check-dot">&#10003;</span> Tablet sourcing and standardization planning</li>
        <li><span class="check-dot">&#10003;</span> App install, restrictions, kiosk mode, and account setup</li>
        <li><span class="check-dot">&#10003;</span> Patient education and intake device support</li>
        <li><span class="check-dot">&#10003;</span> Protective accessories and shared-device readiness</li>
        <li><span class="check-dot">&#10003;</span> Repair intake, swap planning, and secure redeployment</li>
        <li><span class="check-dot">&#10003;</span> Managed support for larger device programs</li>
      </ul>

      <div class="healthcare-coverage-note">
        <strong>Operational by design</strong>
        <span>Clear device ownership, cleaner rollout prep, and a support path that stays practical for care teams.</span>
      </div>
    </div>
  </div>

  <div class="healthcare-section-head" id="healthcare-use-cases">
    <div class="section-label">// Where It Fits</div>
    <div class="section-title" style="font-size:44px">HEALTHCARE USE CASES</div>
    <p class="healthcare-section-copy">Start with the environment that needs support first, then match the tablet setup, restrictions, accessories, and support model to that workflow.</p>
  </div>

  <div class="healthcare-use-grid">
    <?php foreach ($useCases as $case): ?>
    <div class="healthcare-use-card">
      <span class="healthcare-use-icon"><i class="<?= htmlspecialchars($case['icon']) ?>"></i></span>
      <h4><?= htmlspecialchars($case['title']) ?></h4>
      <p><?= htmlspecialchars($case['desc']) ?></p>
      <span class="healthcare-use-tag">Care operations</span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="healthcare-section-head" id="healthcare-programs" style="margin-bottom:32px">
    <div class="section-label">// Program Structure</div>
    <div class="section-title" style="font-size:44px">SERVICE PROGRAMS</div>
    <p class="healthcare-section-copy">Each program is written to make the buying path clearer. Pick the lane that best matches your environment, and the quote form will preselect it below.</p>
  </div>

  <div class="healthcare-program-grid">
    <?php foreach ($programs as $program): ?>
    <div class="healthcare-program-card<?= !empty($program['featured']) ? ' featured' : '' ?>">
      <div class="healthcare-program-eyebrow"><?= htmlspecialchars($program['eyebrow']) ?></div>
      <div class="healthcare-program-name"><?= htmlspecialchars($program['name']) ?></div>
      <p class="healthcare-program-summary"><?= htmlspecialchars($program['summary']) ?></p>
      <ul class="healthcare-program-list">
        <?php foreach ($program['points'] as $point): ?>
        <li><span class="plan-dot"></span><?= htmlspecialchars($point) ?></li>
        <?php endforeach; ?>
      </ul>
      <a class="<?= !empty($program['featured']) ? 'btn-primary' : 'btn-outline' ?> full" href="healthcare-hospitals.php?program=<?= htmlspecialchars($program['key']) ?>#healthcare-quote"><?= htmlspecialchars($program['cta']) ?></a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="healthcare-section-head" id="healthcare-workflow" style="margin-bottom:48px">
    <div class="section-label">// Delivery Model</div>
    <div class="section-title" style="font-size:44px">HOW HEALTHCARE SUPPORT WORKS</div>
    <p class="healthcare-section-copy">The goal is to reduce ambiguity. Tablet Masters scopes the environment first, then turns that into a repeatable setup and continuity plan.</p>
  </div>

  <div class="healthcare-step-grid">
    <?php foreach ($workflow as $step): ?>
    <div class="healthcare-step-card">
      <div class="healthcare-step-num"><?= htmlspecialchars($step['num']) ?></div>
      <h5><?= htmlspecialchars($step['title']) ?></h5>
      <p><?= htmlspecialchars($step['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="healthcare-section-head" style="margin-bottom:32px">
    <div class="section-label">// Why This Offer Works</div>
    <div class="section-title" style="font-size:44px">PROGRAM ADVANTAGES</div>
    <p class="healthcare-section-copy">This offer is strongest when device planning, setup, support, and replacement are treated as one operating system instead of separate decisions.</p>
  </div>

  <div class="healthcare-detail-grid">
    <?php foreach ($advantages as $item): ?>
    <div class="healthcare-detail-card">
      <h4><?= htmlspecialchars($item['title']) ?></h4>
      <p><?= htmlspecialchars($item['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="healthcare-cta-shell" id="healthcare-quote">
    <div class="healthcare-cta-copy">
      <h3>BUILD A CLEANER TABLET PROGRAM FOR CARE ENVIRONMENTS</h3>
      <p>
        If your hospital, clinic, or care facility needs tablets for operations, intake, education, or patient-facing support,
        Tablet Masters can help define a more consistent deployment and maintenance plan.
      </p>
      <p>
        Start with the organization type, department use case, and estimated device count. We can help turn that into
        a practical support model.
      </p>
      <p class="healthcare-form-note">
        Share the basics and Tablet Masters will follow up with a recommended approach, device strategy, and next steps.
      </p>

      <div class="healthcare-response-panel">
        <div class="healthcare-form-eyebrow">What Happens Next</div>
        <ul class="healthcare-response-list">
          <?php foreach ($formHighlights as $highlight): ?>
          <li><span class="check-dot">&#10003;</span><?= htmlspecialchars($highlight) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="healthcare-form-shell">
      <?php if ($quoteSuccess): ?>
      <div class="alert alert-success">&#10003; Healthcare inquiry submitted. Tablet Masters will follow up shortly.</div>
      <?php elseif ($quoteError): ?>
      <div class="alert alert-error">Something went wrong. Please review the form and try again.</div>
      <?php endif; ?>

      <form method="POST" action="send-healthcare-quote.php" class="healthcare-form">
        <div class="healthcare-form-intro">
          <strong>Tell us about the environment</strong>
          <span>The clearer the environment and workflow, the better the first recommendation will be.</span>
        </div>
        <div class="healthcare-form-grid healthcare-form-grid-2">
          <label class="healthcare-field">
            <span>Organization</span>
            <input class="repair-input" type="text" name="organization_name" placeholder="Hospital, clinic, or care organization" required value="<?= $prefill['organization'] ?>" />
          </label>
          <label class="healthcare-field">
            <span>Primary Contact</span>
            <input class="repair-input" type="text" name="contact_name" placeholder="Primary contact name" required value="<?= $prefill['contact'] ?>" />
          </label>
        </div>
        <div class="healthcare-form-grid healthcare-form-grid-2">
          <label class="healthcare-field">
            <span>Email</span>
            <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
          </label>
          <label class="healthcare-field">
            <span>Phone</span>
            <input class="repair-input" type="tel" name="phone" placeholder="Phone number" value="<?= $prefill['phone'] ?>" />
          </label>
        </div>
        <div class="healthcare-form-grid healthcare-form-grid-2">
          <label class="healthcare-field">
            <span>Location</span>
            <input class="repair-input" type="text" name="location" placeholder="City, state, or service area" required value="<?= $prefill['location'] ?>" />
          </label>
          <label class="healthcare-field">
            <span>Units or Departments</span>
            <input class="repair-input" type="text" name="units_departments" placeholder="Departments, units, or facility type" required value="<?= $prefill['units'] ?>" />
          </label>
        </div>
        <div class="healthcare-form-grid healthcare-form-grid-2">
          <label class="healthcare-field">
            <span>Estimated Tablet Count</span>
            <input class="repair-input" type="number" min="1" name="device_count" placeholder="Estimated tablet count" required value="<?= $prefill['devices'] ?>" />
          </label>
          <label class="healthcare-field">
            <span>Program Lane</span>
            <select class="repair-input" name="program" required>
              <option value="operations" <?= $selectedProgram === 'operations' ? 'selected' : '' ?>>Hospital Operations</option>
              <option value="patient" <?= $selectedProgram === 'patient' ? 'selected' : '' ?>>Patient Experience</option>
              <option value="enterprise" <?= $selectedProgram === 'enterprise' ? 'selected' : '' ?>>Enterprise Health Systems</option>
            </select>
          </label>
        </div>
        <div class="healthcare-form-grid healthcare-form-grid-2">
          <label class="healthcare-field">
            <span>Primary Use Case</span>
            <input class="repair-input" type="text" name="use_case" placeholder="Intake, education, kiosks, staff devices, shared carts, etc." required value="<?= $prefill['use_case'] ?>" />
          </label>
          <label class="healthcare-field">
            <span>Support Preference</span>
            <select class="repair-input" name="support_level">
              <option value="managed" <?= $prefill['support_level'] === 'managed' ? 'selected' : '' ?>>Ongoing managed support</option>
              <option value="deployment" <?= $prefill['support_level'] === 'deployment' ? 'selected' : '' ?>>Deployment help only</option>
              <option value="not-sure" <?= $prefill['support_level'] === 'not-sure' ? 'selected' : '' ?>>Not sure yet</option>
            </select>
          </label>
        </div>
        <label class="healthcare-field">
          <span>Setup Requirements</span>
          <textarea class="repair-input healthcare-textarea" name="requirements" placeholder="Tell us about kiosk mode, apps, patient education content, accessories, resets, or support needs"></textarea>
        </label>
        <label class="healthcare-field">
          <span>Additional Notes</span>
          <textarea class="repair-input healthcare-textarea" name="notes" placeholder="Anything else we should know about timelines, facility needs, departments, or deployment challenges?"></textarea>
        </label>
        <button type="submit" class="btn-primary full">Submit Healthcare Inquiry</button>
      </form>

      <div class="healthcare-form-actions">
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
