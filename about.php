<?php $currentPage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About — Tablet Masters</title>
  <meta name="description" content="Learn about Tablet Masters — tablet specialists offering sales, service, cloud solutions, education, and enterprise support since 2019." />
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
$features = [
  ['Protected Plan',        'full replacement coverage for up to 4 years with no deductible while payments remain current'],
  ['Cloud Solutions',       'Clouddogg is our development company', 'https://clouddogg.com'],
  ['Education Specialists', 'bulk pricing and MDM support', 'schools.php'],
  ['Healthcare & Hospitals','tablet support for care operations and patient-facing workflows', 'healthcare-hospitals.php'],
  ['Business Conferences',  'tablet fleet rental and configuration', 'business-conferences.php'],
  ['Certified Service',     'authorized repair for Apple, Samsung, and more'],
];
?>

<div class="about-section">
  <div class="about-grid">

    <div class="about-visual">
      <span class="about-icon-big">🛡️</span>
      <div class="about-tagline">4-YEAR PROTECTED PLAN</div>
      <p class="about-detail">Full replacement coverage with no deductible while plan payments stay current.</p>
    </div>

    <div class="about-text">
      <div class="section-label">// About the Company</div>
      <div class="section-title">WHY TABLET MASTERS</div>

      <p>
        Tablet Masters was founded on a simple belief: technology should empower people,
        not stress them out. We specialize exclusively in tablets &mdash; which means deeper
        expertise, better selection, and smarter solutions than any big-box retailer.
      </p>
      <p>
        We work with individuals, schools, enterprises, and healthcare providers to find
        the right device, configure it for their needs, and back it up with real support.
      </p>
      <p>
        Mailing address: <strong>TabletmastersLLC, 550 Mary Esther Cutoff #18, PMB 376,
        Fort Walton Beach, FL 32548</strong>.
      </p>

      <ul class="feature-list">
        <?php foreach ($features as $feature): ?>
        <?php [$title, $desc] = $feature; ?>
        <li>
          <span class="feature-arrow">&#9658;</span>
          <span>
            <strong><?= htmlspecialchars($title) ?></strong> &mdash; <?= htmlspecialchars($desc) ?>
            <?php if (isset($feature[2])): ?>
              <a href="<?= htmlspecialchars($feature[2]) ?>" <?= $feature[2] === 'https://clouddogg.com' ? 'target="_blank" rel="noreferrer"' : '' ?>>
                <?= $feature[2] === 'https://clouddogg.com' ? 'Clouddogg.com' : 'Learn more' ?>
              </a>
            <?php endif; ?>
          </span>
        </li>
        <?php endforeach; ?>
      </ul>
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
