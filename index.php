<?php $currentPage = 'home'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tablet Masters — Sales · Service · Cloud Solutions</title>
  <meta name="description" content="Tablet Masters provides everything tablet — Apple iPad, Samsung Galaxy, Microsoft Surface, Amazon Fire. Expert service, cloud integration, and a lifetime replacement guarantee." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css?v=20260402-mobile-hero-person-2" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<!-- ── HERO WITH VIDEO BACKGROUND ── -->
<div class="hero">

  <!-- Full-screen background video -->
  <video class="hero-bg-video" id="hero-bg-video" autoplay muted loop playsinline preload="auto">
    <source src="public/AdobeStock_284077286.mp4"  type="video/mp4" />
    <source src="public/AdobeStock_284077286.mov"  type="video/quicktime" />
  </video>

  <!-- Dark overlay so text stays readable -->
  <div class="hero-video-overlay"></div>

  <!-- Grid pattern on top of overlay -->
  <div class="hero-grid"></div>

  <!-- Content -->
  <div class="hero-content">
    <div class="hero-badge">
      <span class="hero-badge-dot"></span>
      Lifetime Device Protection Included
    </div>

    <h1 class="hero-title">
      TABLET<br /><span>MASTERS</span>
    </h1>

    <p class="hero-sub">Tablets Sales &middot; Service &middot; Cloud Solutions &middot; Create Tablet Applications</p>

    <p class="hero-desc">
      We provide <strong>everything tablet</strong> &mdash; from Apple iPad to Samsung Galaxy,
      Microsoft Surface to Amazon Fire. Expert service, cloud integration, and a
      <strong>lifetime replacement guarantee</strong> on every device.
    </p>

    <div class="hero-actions">
      <a class="btn-primary" href="shop.php">Shop Tablets</a>
      <a class="btn-outline" href="plans.php">View Plans</a>
    </div>

    <div class="hero-stats">
      <div>
        <div class="stat-num">4</div>
        <div class="stat-label">Major Brands</div>
      </div>
      <div>
        <div class="stat-num">12+</div>
        <div class="stat-label">Models Available</div>
      </div>
      <div>
        <div class="stat-num">1&times;</div>
        <div class="stat-label">Free Replacement</div>
      </div>
      <div>
        <div class="stat-num">&infin;</div>
        <div class="stat-label">Cloud Support</div>
      </div>
    </div>
  </div>
</div>

<!-- ── INSURANCE BAR ── -->
<div class="insurance-bar">
  <div class="ins-icon-circle">🛡</div>
  <div>
    <strong>Lifetime Insurance Guarantee</strong> &mdash; Every Tablet Masters device includes one
    free replacement regardless of the circumstance. A deductible applies for a second device.
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
