<?php $currentPage = 'home'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schools &mdash; Tablet Masters</title>
  <meta name="description" content="Tablet fleets, setup, MDM support, repairs, and replacement planning for schools, classrooms, and student programs." />
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
  [
    'icon' => 'fa-solid fa-school',
    'title' => '1:1 Student Programs',
    'desc' => 'Planning, staging, and assignment support for take-home and classroom-assigned student fleets.',
    'tag' => 'Student deployment'
  ],
  [
    'icon' => 'fa-solid fa-cart-flatbed',
    'title' => 'Cart and Shared Devices',
    'desc' => 'Prepared tablets for media carts, testing windows, library circulation, and rotating classroom use.',
    'tag' => 'Shared access'
  ],
  [
    'icon' => 'fa-solid fa-chalkboard-user',
    'title' => 'Teacher and Admin Devices',
    'desc' => 'Configured tablets for staff communications, classroom control, attendance, and instructional workflows.',
    'tag' => 'Staff workflows'
  ],
  [
    'icon' => 'fa-solid fa-lock',
    'title' => 'MDM and Restrictions',
    'desc' => 'Enrollment, app control, account setup, web filtering support, and restrictions aligned to school use.',
    'tag' => 'Policy support'
  ],
  [
    'icon' => 'fa-solid fa-screwdriver-wrench',
    'title' => 'Repair and Replacement',
    'desc' => 'Break-fix intake, spare-device planning, and replacement structure to keep instruction moving.',
    'tag' => 'Continuity'
  ],
  [
    'icon' => 'fa-solid fa-box-open',
    'title' => 'Rollout and Refresh',
    'desc' => 'Summer prep, labeling, annual refresh planning, and repeatable lifecycle support for school programs.',
    'tag' => 'Program lifecycle'
  ],
];

$programs = [
  [
    'key' => 'launch',
    'name' => 'Launch',
    'eyebrow' => '10 to 50 Devices',
    'summary' => 'A practical starting point for smaller schools, pilots, tutoring teams, and early 1:1 rollouts.',
    'points' => ['Bulk sourcing guidance', 'Device setup and labeling', 'App install and account prep', 'Initial rollout support'],
    'cta' => 'Request Launch Plan',
    'featured' => false
  ],
  [
    'key' => 'managed',
    'name' => 'Managed School Fleet',
    'eyebrow' => 'Most Popular',
    'summary' => 'Built for schools that need a repeatable device program with setup, repairs, and ongoing support.',
    'points' => ['50 to 300 devices', 'MDM and policy support', 'Repair and replacement workflow', 'Staff onboarding and deployment planning'],
    'cta' => 'Request Managed Plan',
    'featured' => true
  ],
  [
    'key' => 'district',
    'name' => 'District Support',
    'eyebrow' => '300+ Devices',
    'summary' => 'For larger school systems that need procurement structure, lifecycle planning, and long-term fleet management.',
    'points' => ['High-volume planning', 'Refresh and replacement forecasting', 'Program documentation and logistics', 'Multi-site deployment support'],
    'cta' => 'Talk to Tablet Masters',
    'featured' => false
  ],
];

$workflow = [
  ['num' => '01', 'title' => 'Assess the Program', 'desc' => 'We define grade levels, use cases, device count, account strategy, and how the school wants the rollout to function.'],
  ['num' => '02', 'title' => 'Prepare the Fleet', 'desc' => 'Tablet Masters handles setup, labeling, enrollment, app loading, and restriction planning before devices go into student hands.'],
  ['num' => '03', 'title' => 'Support the Rollout', 'desc' => 'The school receives a cleaner deployment process with clearer handoff, staff support, and less last-minute configuration work.'],
  ['num' => '04', 'title' => 'Maintain and Refresh', 'desc' => 'Repairs, replacement planning, and ongoing management keep the program usable beyond the initial purchase cycle.'],
];

$advantages = [
  ['title' => 'Lower Teacher Burden', 'desc' => 'Reduce setup chaos and avoid asking classroom staff to become ad hoc device administrators.'],
  ['title' => 'Faster Student Readiness', 'desc' => 'Devices arrive closer to classroom-ready with apps, labels, and assignment structure already in place.'],
  ['title' => 'Repair Continuity', 'desc' => 'Schools need a plan for damaged devices, missing chargers, and battery issues without pausing instruction for weeks.'],
  ['title' => 'One Vendor View', 'desc' => 'Tablet Masters can support sourcing, setup, protection, and service as a tighter operational package instead of a fragmented process.'],
];

$schoolFacts = [
  ['value' => '1:1', 'label' => 'student programs and classroom fleets supported'],
  ['value' => '3', 'label' => 'school service lanes based on size and complexity'],
  ['value' => '4', 'label' => 'operational phases from planning to refresh'],
];

$coverageItems = [
  'Bulk tablet sourcing and model planning',
  'App install, enrollment, labeling, and account prep',
  'MDM, restrictions, and school-use setup support',
  'Protective accessories and replacement planning',
  'Repair intake and spare-device continuity strategy',
  'Rollout guidance for students, teachers, and staff teams',
];
?>

<section class="school-hero">
  <div class="school-hero-inner">
    <div class="school-hero-copy">
      <div class="section-label">Education Programs</div>
      <h1 class="school-hero-title">TABLET SUPPORT FOR SCHOOLS SHOULD FEEL STRUCTURED BEFORE THE FIRST DEVICE GOES OUT.</h1>
      <p class="school-hero-intro">
        Tablet Masters helps schools build tablet programs that are easier to deploy, easier to support,
        and less disruptive to classrooms. We source, configure, label, repair, and help manage tablets for
        student, teacher, and administrative use.
      </p>
      <div class="school-hero-actions">
        <a class="btn-primary" href="#school-quote">Request a School Plan</a>
        <a class="btn-outline" href="shop.php">Review Tablet Inventory</a>
      </div>
      <div class="school-hero-facts">
        <?php foreach ($schoolFacts as $fact): ?>
        <div class="school-hero-fact">
          <strong><?= htmlspecialchars($fact['value']) ?></strong>
          <span><?= htmlspecialchars($fact['label']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <aside class="school-hero-panel">
      <div class="school-panel-eyebrow">What Tablet Masters Handles</div>
      <h2>Operational clarity for school tablet programs.</h2>
      <div class="school-hero-visual">
        <img src="assets/images/iPad 10th Gen.png" alt="Apple iPad for schools" class="school-device-primary" />
        <img src="assets/images/Galaxy Tab S9 FE.png" alt="Samsung Galaxy Tab for schools" class="school-device-secondary" />
      </div>
      <ul class="school-coverage-list">
        <?php foreach ($coverageItems as $item): ?>
        <li><i class="fa-solid fa-check"></i><span><?= htmlspecialchars($item) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>

<section class="school-page">
  <div class="school-shell">
    <section class="school-use-section">
      <div class="section-label">Where It Fits</div>
      <h2 class="school-section-title">School use cases should be obvious before the rollout gets complicated.</h2>
      <div class="school-use-grid">
        <?php foreach ($schoolUseCases as $case): ?>
        <article class="school-use-card">
          <div class="school-use-icon"><i class="<?= htmlspecialchars($case['icon']) ?>"></i></div>
          <div class="school-use-tag"><?= htmlspecialchars($case['tag']) ?></div>
          <h3><?= htmlspecialchars($case['title']) ?></h3>
          <p><?= htmlspecialchars($case['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="school-program-section">
      <div class="section-label">Program Structure</div>
      <h2 class="school-section-title">Three school support lanes based on rollout size and operational weight.</h2>
      <div class="school-program-grid">
        <?php foreach ($programs as $program): ?>
        <article class="school-program-card<?= !empty($program['featured']) ? ' featured' : '' ?>">
          <div class="school-program-top">
            <div>
              <div class="school-program-eyebrow"><?= htmlspecialchars($program['eyebrow']) ?></div>
              <h3><?= htmlspecialchars($program['name']) ?></h3>
            </div>
            <?php if (!empty($program['featured'])): ?>
            <div class="school-program-pill">Recommended</div>
            <?php endif; ?>
          </div>
          <p><?= htmlspecialchars($program['summary']) ?></p>
          <ul class="school-program-list">
            <?php foreach ($program['points'] as $point): ?>
            <li><i class="fa-solid fa-arrow-right"></i><span><?= htmlspecialchars($point) ?></span></li>
            <?php endforeach; ?>
          </ul>
          <a class="<?= !empty($program['featured']) ? 'btn-primary' : 'btn-outline' ?> full" href="schools.php?program=<?= htmlspecialchars($program['key']) ?>#school-quote"><?= htmlspecialchars($program['cta']) ?></a>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="school-workflow-section">
      <div class="section-label">Delivery Model</div>
      <h2 class="school-section-title">A school fleet works better when planning, deployment, and refresh are treated as one system.</h2>
      <div class="school-workflow-grid">
        <?php foreach ($workflow as $step): ?>
        <article class="school-workflow-card">
          <div class="school-workflow-number"><?= htmlspecialchars($step['num']) ?></div>
          <h3><?= htmlspecialchars($step['title']) ?></h3>
          <p><?= htmlspecialchars($step['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="school-advantage-section">
      <div class="section-label">Program Advantages</div>
      <h2 class="school-section-title">The right support model lowers classroom friction instead of adding another layer of it.</h2>
      <div class="school-advantage-grid">
        <?php foreach ($advantages as $item): ?>
        <article class="school-advantage-card">
          <h3><?= htmlspecialchars($item['title']) ?></h3>
          <p><?= htmlspecialchars($item['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="school-quote-shell" id="school-quote">
      <aside class="school-quote-copy">
        <div class="section-label">Request a School Plan</div>
        <h2 class="school-section-title">Build a school tablet program with less friction.</h2>
        <p class="school-intro-copy">
          If your school is planning a new rollout, replacing aging devices, or trying to improve support after a difficult deployment,
          Tablet Masters can help define a cleaner operating model.
        </p>
        <div class="school-quote-note">
          <strong>What to share</strong>
          <span>Student count, device count, use case, support preference, and any MDM or restrictions requirements you already know about.</span>
        </div>
        <div class="school-quote-note">
          <strong>What happens next</strong>
          <span>Tablet Masters follows up with a recommended approach, device strategy, and next-step conversation.</span>
        </div>
      </aside>

      <div class="school-form-panel">
        <?php if ($quoteSuccess): ?>
        <div class="alert alert-success">&#10003; School inquiry submitted. Tablet Masters will follow up shortly.</div>
        <?php elseif ($quoteError): ?>
        <div class="alert alert-error">Something went wrong. Please review the form and try again.</div>
        <?php endif; ?>

        <form method="POST" action="send-school-quote.php" class="school-form-grid">
          <label class="reg-field">
            <span class="reg-label">School or district</span>
            <input class="repair-input" type="text" name="school_name" placeholder="School, district, or organization" required value="<?= $prefill['school'] ?>" />
          </label>
          <label class="reg-field">
            <span class="reg-label">Primary contact</span>
            <input class="repair-input" type="text" name="contact_name" placeholder="Primary contact name" required value="<?= $prefill['contact'] ?>" />
          </label>

          <label class="reg-field">
            <span class="reg-label">Email address</span>
            <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
          </label>
          <label class="reg-field">
            <span class="reg-label">Phone number</span>
            <input class="repair-input" type="tel" name="phone" placeholder="Phone number" value="<?= $prefill['phone'] ?>" />
          </label>

          <label class="reg-field">
            <span class="reg-label">City and state</span>
            <input class="repair-input" type="text" name="city_state" placeholder="City and state" required value="<?= $prefill['city'] ?>" />
          </label>
          <label class="reg-field">
            <span class="reg-label">Student or user count</span>
            <input class="repair-input" type="number" min="1" name="student_count" placeholder="Approximate student or user count" required value="<?= $prefill['students'] ?>" />
          </label>

          <label class="reg-field">
            <span class="reg-label">Estimated tablet count</span>
            <input class="repair-input" type="number" min="1" name="device_count" placeholder="Estimated tablet count" required value="<?= $prefill['devices'] ?>" />
          </label>
          <label class="reg-field">
            <span class="reg-label">Program lane</span>
            <select class="repair-input" name="program" required>
              <option value="launch" <?= $selectedProgram === 'launch' ? 'selected' : '' ?>>Launch program</option>
              <option value="managed" <?= $selectedProgram === 'managed' ? 'selected' : '' ?>>Managed School Fleet</option>
              <option value="district" <?= $selectedProgram === 'district' ? 'selected' : '' ?>>District Support</option>
            </select>
          </label>

          <label class="reg-field">
            <span class="reg-label">Primary use case</span>
            <input class="repair-input" type="text" name="use_case" placeholder="1:1, carts, teachers, testing, etc." required value="<?= $prefill['use_case'] ?>" />
          </label>
          <label class="reg-field">
            <span class="reg-label">Support preference</span>
            <select class="repair-input" name="support_level">
              <option value="managed" <?= $prefill['support_level'] === 'managed' ? 'selected' : '' ?>>Ongoing managed support</option>
              <option value="rollout" <?= $prefill['support_level'] === 'rollout' ? 'selected' : '' ?>>Rollout help only</option>
              <option value="not-sure" <?= $prefill['support_level'] === 'not-sure' ? 'selected' : '' ?>>Not sure yet</option>
            </select>
          </label>

          <label class="reg-field reg-field-full">
            <span class="reg-label">Requirements</span>
            <textarea class="repair-input school-textarea" name="requirements" placeholder="Tell us about apps, account setup, MDM, restrictions, cases, repairs, or rollout timing"></textarea>
          </label>
          <label class="reg-field reg-field-full">
            <span class="reg-label">Additional notes</span>
            <textarea class="repair-input school-textarea" name="notes" placeholder="Anything else we should know about grade levels, budgets, deployment timing, or support pain points?"></textarea>
          </label>

          <div class="school-form-actions reg-field-full">
            <button type="submit" class="btn-primary full">Submit School Inquiry</button>
            <a class="btn-outline full" href="support.php">Talk Through Requirements</a>
          </div>
        </form>
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
