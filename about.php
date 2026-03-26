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
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php
$features = [
  ['Lifetime Insurance',    'one free replacement, no questions asked'],
  ['Cloud Solutions',       'setup, sync, and management included'],
  ['Education Specialists', 'bulk pricing and MDM support'],
  ['Business Conferences',  'tablet fleet rental and configuration'],
  ['Certified Service',     'authorized repair for Apple, Samsung, and more'],
];
?>

<div class="about-section">
  <div class="about-grid">

    <div class="about-visual">
      <span class="about-icon-big">🛡️</span>
      <div class="about-tagline">LIFETIME PROTECTION</div>
      <p class="about-detail">One free replacement. Any circumstance. No questions asked.</p>
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

      <ul class="feature-list">
        <?php foreach ($features as [$title, $desc]): ?>
        <li>
          <span class="feature-arrow">&#9658;</span>
          <span><strong><?= htmlspecialchars($title) ?></strong> &mdash; <?= htmlspecialchars($desc) ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
