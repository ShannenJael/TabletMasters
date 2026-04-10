<?php $currentPage = 'home'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tablet Masters &mdash; Sales &middot; Service &middot; Cloud Solutions</title>
  <meta name="description" content="Tablet Masters sells tablets, repairs tablets, and helps customers activate protection plans built around real tablet service and replacement needs." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css?v=20260409-home-redesign" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#3B82F6" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php
$pathways = [
  [
    'eyebrow' => 'Shop tablets',
    'title' => 'Buy the right device without wandering through generic electronics listings.',
    'copy' => 'Focused inventory across iPad, Galaxy Tab, Surface, and Fire models with a tablet-first product mix.',
    'href' => 'shop.php',
    'cta' => 'Browse Inventory',
    'icon' => 'fa-solid fa-tablets'
  ],
  [
    'eyebrow' => 'Protect the device',
    'title' => 'Choose coverage that reads clearly before a customer ever files a claim.',
    'copy' => 'Basic starts at $8 per month. Protected is $12 per month with full replacement coverage for up to 4 years.',
    'href' => 'plans.php',
    'cta' => 'Compare Plans',
    'icon' => 'fa-solid fa-shield-heart'
  ],
  [
    'eyebrow' => 'Repair or claim',
    'title' => 'Start service, diagnostics, or a claim review from one intake path.',
    'copy' => 'When a tablet is already damaged, the next step should be obvious. Repair intake and claim review start here.',
    'href' => 'insurance.php',
    'cta' => 'Start Service',
    'icon' => 'fa-solid fa-screwdriver-wrench'
  ],
];

$brandCards = [
  [
    'name' => 'Apple iPad',
    'copy' => 'Performance models, education-friendly standards, and premium iPad Pro options.',
    'image' => 'assets/images/iPad Pro M4 13.png',
    'link' => 'shop.php'
  ],
  [
    'name' => 'Samsung Galaxy Tab',
    'copy' => 'From mainstream value tablets to large-format flagship Galaxy Tab hardware.',
    'image' => 'assets/images/Galaxy Tab S10 Ultra.png',
    'link' => 'shop.php'
  ],
  [
    'name' => 'Microsoft Surface',
    'copy' => 'Tablet-laptop flexibility for business users, field teams, and power workflows.',
    'image' => 'assets/images/Surface Pro 11.png',
    'link' => 'shop.php'
  ],
  [
    'name' => 'Amazon Fire',
    'copy' => 'Budget-conscious tablets for household use, lighter app needs, and entry-level deployment.',
    'image' => 'assets/images/Fire Max 11.png',
    'link' => 'shop.php'
  ],
];

$serviceCards = [
  [
    'title' => 'Tablet Sales',
    'copy' => 'A store built around tablet decisions instead of burying tablets inside a giant consumer electronics catalog.',
    'points' => ['Major brands only', 'Tablet-specific product mix', 'Direct path into protection']
  ],
  [
    'title' => 'Protection Plans',
    'copy' => 'Protection language tied to what actually happens when a tablet breaks, needs service, or needs replacement.',
    'points' => ['Basic at $8 per month', 'Protected at $12 per month', '4-year full replacement lane']
  ],
  [
    'title' => 'Repair and Intake',
    'copy' => 'Claims, diagnostics, and repair work routed through a cleaner intake experience with tablet-first handling.',
    'points' => ['Screen and battery service', 'Diagnostics and data recovery', 'Repair or claim start from one page']
  ],
];

$sectorCards = [
  [
    'title' => 'Schools',
    'copy' => 'Student programs, classroom fleets, and managed deployment support.',
    'href' => 'schools.php',
    'icon' => 'fa-solid fa-school'
  ],
  [
    'title' => 'Healthcare',
    'copy' => 'Tablet workflows for clinical intake, care teams, and patient-facing operations.',
    'href' => 'healthcare-hospitals.php',
    'icon' => 'fa-solid fa-hospital'
  ],
  [
    'title' => 'Business Conferences',
    'copy' => 'Short-term and event-driven tablet needs with deployment-focused planning.',
    'href' => 'business-conferences.php',
    'icon' => 'fa-solid fa-briefcase'
  ],
  [
    'title' => 'Tablet Games',
    'copy' => 'Editorial tablet gaming content that also routes readers toward the right hardware.',
    'href' => 'tablet-games.php',
    'icon' => 'fa-solid fa-gamepad'
  ],
];
?>

<div class="hero">
  <video class="hero-bg-video" id="hero-bg-video" autoplay muted loop playsinline preload="auto">
    <source src="public/AdobeStock_284077286.mp4" type="video/mp4" />
    <source src="public/AdobeStock_284077286.mov" type="video/quicktime" />
  </video>

  <div class="hero-video-overlay"></div>
  <div class="hero-grid"></div>

  <div class="hero-content home-hero-content">
    <div class="home-hero-grid">
      <div class="home-hero-copy">
        <div class="hero-badge">
          <span class="hero-badge-dot"></span>
          Tablet Sales, Protection, Repair, and Cloud Support
        </div>

        <h1 class="hero-title">
          TABLET<br /><span>MASTERS</span>
        </h1>

        <p class="hero-sub">Sales &middot; Plans &middot; Repair Intake &middot; Cloud Solutions</p>

        <p class="hero-desc">
          Tablet Masters is built around one category and one category only: tablets. We help customers buy the right device,
          register protection with less confusion, and move into repair or replacement support without getting stuck in a generic workflow.
        </p>

        <div class="hero-actions">
          <a class="btn-primary" href="shop.php">Shop Tablets</a>
          <a class="btn-outline" href="plans.php">View Plans</a>
        </div>

        <div class="hero-stats home-hero-stats">
          <div>
            <div class="stat-num">4</div>
            <div class="stat-label">Major Brands</div>
          </div>
          <div>
            <div class="stat-num">$8</div>
            <div class="stat-label">Basic Plan Start</div>
          </div>
          <div>
            <div class="stat-num">$12</div>
            <div class="stat-label">Protected Plan</div>
          </div>
          <div>
            <div class="stat-num">4 Yr</div>
            <div class="stat-label">Protected Coverage Window</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="insurance-bar home-insurance-bar">
  <div class="ins-icon-circle"><i class="fa-solid fa-shield-heart"></i></div>
  <div>
    <strong>Coverage That Reads Clearly</strong> &mdash; Basic starts at $8/month with a deductible based on damage type.
    Protected is $12/month with full replacement coverage for up to 4 years and no deductible on covered replacement claims while payments remain current.
  </div>
</div>

<section class="home-page">
  <div class="home-shell">
    <section class="home-entry-panel">
      <div class="home-hero-panel">
        <div class="home-panel-eyebrow">Start in the right place</div>
        <h2>Start in the right place.</h2>
        <div class="home-panel-cards">
          <div class="home-panel-card">
            <span>Shop</span>
            <strong>Need the device first?</strong>
            <p>Browse tablet inventory by the brands customers are already shopping for.</p>
          </div>
          <div class="home-panel-card">
            <span>Protect</span>
            <strong>Already own the tablet?</strong>
            <p>Compare Basic and Protected, then attach coverage through registration.</p>
          </div>
          <div class="home-panel-card">
            <span>Repair</span>
            <strong>Device already damaged?</strong>
            <p>Start repair intake or claim review from one service page instead of guessing.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="home-pathways">
      <div class="section-label">Three Clear Paths</div>
      <div class="section-title">Buy it. Protect it. Service it.</div>
      <p class="home-intro-copy">
        The homepage now points people into the correct lane instead of making every visitor decode the same message.
      </p>

      <div class="home-pathway-grid">
        <?php foreach ($pathways as $pathway): ?>
        <article class="home-pathway-card">
          <div class="home-pathway-icon"><i class="<?= htmlspecialchars($pathway['icon']) ?>"></i></div>
          <div class="home-panel-eyebrow"><?= htmlspecialchars($pathway['eyebrow']) ?></div>
          <h2><?= htmlspecialchars($pathway['title']) ?></h2>
          <p><?= htmlspecialchars($pathway['copy']) ?></p>
          <a class="btn-outline full" href="<?= htmlspecialchars($pathway['href']) ?>"><?= htmlspecialchars($pathway['cta']) ?></a>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="home-brand-section">
      <div class="section-label">Core Inventory Focus</div>
      <h2 class="home-section-title">Major tablet brands, presented like a tablet business instead of a catch-all store.</h2>
      <div class="home-brand-grid">
        <?php foreach ($brandCards as $card): ?>
        <article class="home-brand-card">
          <div class="home-brand-visual">
            <img src="<?= htmlspecialchars($card['image']) ?>" alt="<?= htmlspecialchars($card['name']) ?>" />
          </div>
          <h3><?= htmlspecialchars($card['name']) ?></h3>
          <p><?= htmlspecialchars($card['copy']) ?></p>
          <a href="<?= htmlspecialchars($card['link']) ?>">Explore inventory</a>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="home-service-section">
      <div class="home-service-copy">
        <div class="section-label">Why Tablet Masters</div>
        <h2 class="home-section-title">The business gets stronger when the sales, plans, and repair experience look connected.</h2>
        <p class="home-intro-copy">
          The site now has a much better conversion spine. These are the three operating lanes the homepage should reinforce every time someone lands here.
        </p>
      </div>
      <div class="home-service-grid">
        <?php foreach ($serviceCards as $card): ?>
        <article class="home-service-card">
          <h3><?= htmlspecialchars($card['title']) ?></h3>
          <p><?= htmlspecialchars($card['copy']) ?></p>
          <ul>
            <?php foreach ($card['points'] as $point): ?>
            <li><i class="fa-solid fa-check"></i><span><?= htmlspecialchars($point) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="home-sector-section">
      <div class="section-label">Where Tablets Go</div>
      <h2 class="home-section-title">What We Provide</h2>
      <div class="home-sector-grid">
        <?php foreach ($sectorCards as $sector): ?>
        <a class="home-sector-card" href="<?= htmlspecialchars($sector['href']) ?>">
          <div class="home-sector-icon"><i class="<?= htmlspecialchars($sector['icon']) ?>"></i></div>
          <h3><?= htmlspecialchars($sector['title']) ?></h3>
          <p><?= htmlspecialchars($sector['copy']) ?></p>
          <span>Open page</span>
        </a>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="home-bottom-cta">
      <div>
        <div class="section-label">Move Forward</div>
        <h2 class="home-section-title">Start with the part of the tablet lifecycle you need right now.</h2>
      </div>
      <div class="home-bottom-actions">
        <a class="btn-primary" href="shop.php">Shop Devices</a>
        <a class="btn-outline" href="insurance.php">Repair or Claim</a>
        <a class="btn-outline" href="register.php">Register a Tablet</a>
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
