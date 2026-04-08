<?php $currentPage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schools - Tablet Masters</title>
  <meta name="description" content="Tablet fleets, setup, MDM support, repairs, and replacement planning for schools, classrooms, and student programs." />
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
$allowedPrograms = ['launch', 'managed', 'district'];
$requestedProgram = $_GET['program'] ?? 'managed';
$selectedProgram = in_array($requestedProgram, $allowedPrograms, true) ? $requestedProgram : 'managed';
$prefill = [
  'school' => htmlspecialchars($_GET['school'] ?? ''),
  'contact' => htmlspecialchars($_GET['contact'] ?? ''),
  'email' => htmlspecialchars($_GET['email'] ?? ''),
  'phone' => htmlspecialchars($_GET['phone'] ?? ''),
  'city' => htmlspecialchars($_GET['city'] ?? ''),
  'students' => htmlspecialchars($_GET['students'] ?? ''),
  'devices' => htmlspecialchars($_GET['devices'] ?? ''),
  'use_case' => htmlspecialchars($_GET['use_case'] ?? ''),
  'support_level' => htmlspecialchars($_GET['support_level'] ?? 'managed'),
];

$schoolUseCases = [
  ['icon' => '🏫', 'title' => '1:1 Student Programs', 'desc' => 'Tablet planning, setup, and deployment support for student take-home or classroom-assigned fleets.'],
  ['icon' => '🛒', 'title' => 'Cart and Shared Devices', 'desc' => 'Prepared tablets for grade-level carts, library circulation, testing windows, and rotating classroom use.'],
  ['icon' => '👩‍🏫', 'title' => 'Teacher and Admin Devices', 'desc' => 'Configured tablets for staff communications, classroom control, note-taking, attendance, and instructional workflows.'],
  ['icon' => '🔐', 'title' => 'MDM and Restrictions', 'desc' => 'Enrollment, app control, account setup, web filtering support, and device restrictions aligned to school use.'],
  ['icon' => '🧰', 'title' => 'Repair and Replacement', 'desc' => 'Break-fix handling, spare-device planning, and structured replacement options to reduce downtime.'],
  ['icon' => '📦', 'title' => 'Rollout and Refresh', 'desc' => 'Summer prep, label assignment, annual refresh planning, and repeatable lifecycle support for school programs.'],
];

$programs = [
  [
    'key' => 'launch',
    'name' => 'Launch',
    'eyebrow' => '10 to 50 Devices',
    'summary' => 'A practical starting point for smaller schools, pilots, tutoring teams, and early 1:1 rollouts.',
    'points' => ['Bulk sourcing guidance', 'Device setup and labeling', 'App install and account prep', 'Initial rollout support'],
    'cta' => 'Request Launch Plan',
  ],
  [
    'key' => 'managed',
    'name' => 'Managed School Fleet',
    'eyebrow' => 'Most Popular',
    'summary' => 'Built for schools that need a repeatable device program with setup, repairs, and ongoing support.',
    'points' => ['50 to 300 devices', 'MDM and policy support', 'Repair and replacement workflow', 'Staff onboarding and deployment planning'],
    'cta' => 'Request Managed Plan',
    'featured' => true,
  ],
  [
    'key' => 'district',
    'name' => 'District Support',
    'eyebrow' => '300+ Devices',
    'summary' => 'For larger school systems that need procurement structure, lifecycle planning, and long-term fleet management.',
    'points' => ['High-volume planning', 'Refresh and replacement forecasting', 'Program documentation and logistics', 'Multi-site deployment support'],
    'cta' => 'Talk to Tablet Masters',
  ],
];

$workflow = [
  ['num' => '01', 'title' => 'Assess the Program', 'desc' => 'We define grade levels, use cases, device count, account strategy, and how the school wants the rollout to function.'],
  ['num' => '02', 'title' => 'Prepare the Fleet', 'desc' => 'Tablet Masters handles setup, labeling, enrollment, app loading, and restriction planning before devices go into student hands.'],
  ['num' => '03', 'title' => 'Support the Rollout', 'desc' => 'The school receives a cleaner deployment process with clearer handoff, staff support, and less last-minute configuration work.'],
  ['num' => '04', 'title' => 'Maintain and Refresh', 'desc' => 'Repairs, replacement planning, and ongoing management keep the program usable beyond the initial purchase cycle.'],
];

$advantages = [
  ['title' => 'Lower Teacher Burden', 'desc' => 'The goal is to reduce setup chaos and avoid asking classroom staff to become ad hoc device administrators.'],
  ['title' => 'Faster Student Readiness', 'desc' => 'Devices arrive closer to classroom-ready with the right apps, labels, and assignment structure already in place.'],
  ['title' => 'Repair Continuity', 'desc' => 'Schools need a plan for damaged devices, missing chargers, and battery issues without pausing instruction for weeks.'],
  ['title' => 'One Vendor View', 'desc' => 'Tablet Masters can support sourcing, setup, protection, and service as a tighter operational package instead of a fragmented process.'],
];
?>

<div class="conference-section">
  <div class="ins-hero conference-hero">
    <div class="ins-hero-text">
      <div class="section-label">// Schools</div>
      <div class="section-title">TABLET SUPPORT<br />FOR SCHOOLS</div>
      <p>
        Tablet Masters helps schools build tablet programs that are easier to deploy, easier to support,
        and less disruptive to classrooms. We source, configure, label, repair, and help manage tablets for
        student, teacher, and administrative use.
      </p>
      <p>
        The focus is operational clarity: fewer last-minute setup problems, cleaner assignment workflows,
        and a better plan for repairs, replacements, and annual refresh cycles.
      </p>
      <div class="ins-actions">
        <a class="btn-primary" href="#school-quote">Request a School Plan</a>
        <a class="btn-outline" href="support.php">Talk to Support</a>
      </div>

      <div class="conference-metrics">
        <div class="conference-metric">
          <span class="conference-metric-value">3</span>
          <span class="conference-metric-label">program tiers for school size and rollout complexity</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">1:1</span>
          <span class="conference-metric-label">student deployments and classroom fleet programs supported</span>
        </div>
        <div class="conference-metric">
          <span class="conference-metric-value">4</span>
          <span class="conference-metric-label">stages from planning to repair continuity</span>
        </div>
      </div>
    </div>

    <div class="ins-coverage-card conference-coverage-card">
      <span class="ins-shield">🎓</span>
      <h3>WHAT TABLET MASTERS HANDLES</h3>
      <p>Built for classrooms, student fleets, carts, staff devices, and longer-term school device programs.</p>
      <ul class="coverage-checklist">
        <li><span class="check-dot">&#10003;</span> Bulk tablet sourcing and model planning</li>
        <li><span class="check-dot">&#10003;</span> App install, enrollment, labeling, and account prep</li>
        <li><span class="check-dot">&#10003;</span> MDM, restriction, and school-use setup support</li>
        <li><span class="check-dot">&#10003;</span> Protective accessories and replacement planning</li>
        <li><span class="check-dot">&#10003;</span> Repair intake and spare-device continuity strategy</li>
        <li><span class="check-dot">&#10003;</span> Rollout guidance for students, teachers, and staff teams</li>
      </ul>
    </div>
  </div>

  <div class="repair-section-title">
    <div class="section-label">// Where It Fits</div>
    <div class="section-title" style="font-size:44px">SCHOOL USE CASES</div>
  </div>

  <div class="repair-grid">
    <?php foreach ($schoolUseCases as $case): ?>
    <div class="repair-card conference-card">
      <span class="repair-icon"><?= $case['icon'] ?></span>
      <h4><?= htmlspecialchars($case['title']) ?></h4>
      <p><?= htmlspecialchars($case['desc']) ?></p>
      <span class="repair-price-tag">School operations</span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:32px">
    <div class="section-label">// Program Structure</div>
    <div class="section-title" style="font-size:44px">SERVICE TIERS</div>
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
      <a class="<?= !empty($program['featured']) ? 'btn-primary' : 'btn-outline' ?> full" href="schools.php?program=<?= htmlspecialchars($program['key']) ?>#school-quote"><?= htmlspecialchars($program['cta']) ?></a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-bottom:48px">
    <div class="section-label">// Delivery Model</div>
    <div class="section-title" style="font-size:44px">HOW SCHOOL SUPPORT WORKS</div>
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
    <div class="section-label">// Why Schools Use This</div>
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

  <div class="repair-cta" id="school-quote">
    <div>
      <h3>BUILD A SCHOOL TABLET PROGRAM WITH LESS FRICTION</h3>
      <p>
        If your school is planning a new rollout, replacing aging devices, or trying to improve support after a difficult deployment,
        Tablet Masters can help define a cleaner operating model.
      </p>
      <p>
        Start with student count, device count, and the type of classroom or staff workflow you are trying to support.
        We can help turn that into a practical program plan.
      </p>
      <p class="conference-form-note">
        Share the basics and Tablet Masters will follow up with a recommended approach, device strategy, and next steps.
      </p>
    </div>

    <div class="repair-form">
      <?php if ($quoteSuccess): ?>
      <div class="alert alert-success">&#10003; School inquiry submitted. Tablet Masters will follow up shortly.</div>
      <?php elseif ($quoteError): ?>
      <div class="alert alert-error">Something went wrong. Please review the form and try again.</div>
      <?php endif; ?>

      <form method="POST" action="send-school-quote.php">
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="school_name" placeholder="School, district, or organization" required value="<?= $prefill['school'] ?>" />
          <input class="repair-input" type="text" name="contact_name" placeholder="Primary contact name" required value="<?= $prefill['contact'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
          <input class="repair-input" type="tel" name="phone" placeholder="Phone number" value="<?= $prefill['phone'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="city_state" placeholder="City and state" required value="<?= $prefill['city'] ?>" />
          <input class="repair-input" type="number" min="1" name="student_count" placeholder="Approximate student or user count" required value="<?= $prefill['students'] ?>" />
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="number" min="1" name="device_count" placeholder="Estimated tablet count" required value="<?= $prefill['devices'] ?>" />
          <select class="repair-input" name="program" required>
            <option value="launch" <?= $selectedProgram === 'launch' ? 'selected' : '' ?>>Launch program</option>
            <option value="managed" <?= $selectedProgram === 'managed' ? 'selected' : '' ?>>Managed School Fleet</option>
            <option value="district" <?= $selectedProgram === 'district' ? 'selected' : '' ?>>District Support</option>
          </select>
        </div>
        <div class="conference-form-grid conference-form-grid-2">
          <input class="repair-input" type="text" name="use_case" placeholder="Primary use case: 1:1, carts, teachers, testing, etc." required value="<?= $prefill['use_case'] ?>" />
          <select class="repair-input" name="support_level">
            <option value="managed" <?= $prefill['support_level'] === 'managed' ? 'selected' : '' ?>>Support preference: Ongoing managed support</option>
            <option value="rollout" <?= $prefill['support_level'] === 'rollout' ? 'selected' : '' ?>>Support preference: Rollout help only</option>
            <option value="not-sure" <?= $prefill['support_level'] === 'not-sure' ? 'selected' : '' ?>>Support preference: Not sure yet</option>
          </select>
        </div>
        <textarea class="repair-input conference-textarea" name="requirements" placeholder="Tell us about apps, account setup, MDM, restrictions, cases, repairs, or rollout timing"></textarea>
        <textarea class="repair-input conference-textarea" name="notes" placeholder="Anything else we should know about grade levels, budgets, deployment timing, or support pain points?"></textarea>
        <button type="submit" class="btn-primary full">Submit School Inquiry</button>
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
