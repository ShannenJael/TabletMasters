<?php $currentPage = 'about'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About &mdash; Tablet Masters</title>
  <meta name="description" content="Learn about Tablet Masters, the tablet-focused team behind sales, service, protection plans, cloud support, education, healthcare, and enterprise programs." />
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
$capabilities = [
  [
    'icon' => 'fa-solid fa-shield-heart',
    'title' => 'Protection Plans',
    'desc' => 'Coverage built around real repair and replacement needs instead of vague policy language.',
    'href' => 'plans.php',
    'link' => 'View plans',
  ],
  [
    'icon' => 'fa-solid fa-screwdriver-wrench',
    'title' => 'Service & Repair',
    'desc' => 'Tablet troubleshooting, in-house repair support, replacement paths, and intake guidance.',
    'href' => 'insurance.php',
    'link' => 'Book service',
  ],
  [
    'icon' => 'fa-solid fa-graduation-cap',
    'title' => 'Education Programs',
    'desc' => 'Bulk purchasing, MDM support, and practical device planning for school environments.',
    'href' => 'schools.php',
    'link' => 'See schools',
  ],
  [
    'icon' => 'fa-solid fa-hospital',
    'title' => 'Healthcare Support',
    'desc' => 'Procurement, setup, kiosk controls, repair continuity, and shared-device readiness for care settings.',
    'href' => 'healthcare-hospitals.php',
    'link' => 'See healthcare',
  ],
  [
    'icon' => 'fa-solid fa-people-group',
    'title' => 'Conference Deployments',
    'desc' => 'Staging, shipping, support, and recovery for event tablets, check-in, and demo programs.',
    'href' => 'business-conferences.php',
    'link' => 'See conferences',
  ],
  [
    'icon' => 'fa-solid fa-cloud-arrow-up',
    'title' => 'Cloud & App Support',
    'desc' => 'Clouddogg-led cloud services and tablet application work built around how the devices are actually used.',
    'href' => 'https://clouddogg.com',
    'link' => 'Visit Clouddogg',
    'external' => true,
  ],
];

$principles = [
  [
    'title' => 'Tablet-only focus',
    'desc' => 'Tablet Masters is not trying to be a generic electronics store. The company stays focused on tablets, their workflows, and the support systems around them.'
  ],
  [
    'title' => 'Operational clarity',
    'desc' => 'Customers should be able to understand the buying path, the protection path, and the support path without decoding marketing language first.'
  ],
  [
    'title' => 'Longer-term support',
    'desc' => 'The work does not stop at the sale. Repair continuity, protection, onboarding, and redeployment are part of the same customer experience.'
  ],
];

$stats = [
  ['value' => '1', 'label' => 'device category the company is built around'],
  ['value' => '4', 'label' => 'major brand ecosystems supported publicly'],
  ['value' => '6', 'label' => 'core service lanes across consumers and organizations'],
];
?>

<section class="about-page">
  <div class="about-shell">
    <div class="about-hero">
      <div class="about-hero-copy">
        <div class="section-label">About Tablet Masters</div>
        <h1 class="about-page-title">A TABLET COMPANY BUILT TO FEEL MORE USEFUL THAN NOISY.</h1>
        <p class="about-page-intro">
          Tablet Masters was founded on a simple belief: technology should empower people, not stress them out.
          The company specializes in tablets only, which means deeper product knowledge, cleaner support paths,
          and a better fit for customers who need more than a generic electronics storefront.
        </p>

        <div class="about-hero-actions">
          <a class="btn-primary" href="shop.php">Shop Tablets</a>
          <a class="btn-outline" href="support.php">Contact Support</a>
        </div>

        <div class="about-hero-metrics">
          <?php foreach ($stats as $stat): ?>
          <div class="about-metric-card">
            <strong><?= htmlspecialchars($stat['value']) ?></strong>
            <span><?= htmlspecialchars($stat['label']) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <aside class="about-hero-panel">
        <div class="about-panel-eyebrow">What the company is built around</div>
        <h2>Sales, service, protection, and deployment all tied to the same device category.</h2>
        <p>
          Tablet Masters works with individual buyers, schools, healthcare providers, and business teams
          that need tablets sourced, configured, protected, supported, and kept useful over time.
        </p>

        <div class="about-panel-stack">
          <div class="about-panel-card">
            <i class="fa-solid fa-shield-heart"></i>
            <div>
              <strong>Protected plan confidence</strong>
              <span>Full replacement coverage is surfaced clearly instead of buried in vague copy.</span>
            </div>
          </div>
          <div class="about-panel-card">
            <i class="fa-solid fa-tablet-screen-button"></i>
            <div>
              <strong>Tablet-only expertise</strong>
              <span>The product mix, service language, and support pages are all built around tablets specifically.</span>
            </div>
          </div>
          <div class="about-panel-card">
            <i class="fa-solid fa-building"></i>
            <div>
              <strong>Consumer to fleet support</strong>
              <span>The same business can support single-device customers and larger deployment programs.</span>
            </div>
          </div>
        </div>
      </aside>
    </div>

    <section class="about-story">
      <div class="about-story-head">
        <div class="section-label">Why This Company Exists</div>
        <h2 class="about-section-title">Tablet Masters is built to remove friction at the points where tablet buyers usually lose time.</h2>
      </div>

      <div class="about-story-grid">
        <div class="about-story-card">
          <p>
            Most customers do not just need a tablet. They need the right device, the right configuration,
            a clear support path, and a way to keep that device useful after purchase.
          </p>
          <p>
            Tablet Masters is designed around that full cycle, from shopping and registration to repair,
            protection, fleet planning, and follow-up support.
          </p>
        </div>

        <div class="about-story-card about-story-card-accent">
          <div class="about-address-label">Mailing address</div>
          <p class="about-address-copy">
            <strong>TabletmastersLLC, 550 Mary Esther Cutoff #18, PMB 376, Fort Walton Beach, FL 32548</strong>
          </p>
          <p>
            The public site is focused on one promise: if the customer is dealing with a tablet problem,
            purchase, or deployment question, there should be a clearer path forward here than at a big-box retailer.
          </p>
        </div>
      </div>
    </section>

    <section class="about-principles">
      <div class="section-label">Operating Principles</div>
      <h2 class="about-section-title">The company works best when the message stays practical.</h2>

      <div class="about-principles-grid">
        <?php foreach ($principles as $item): ?>
        <article class="about-principle-card">
          <h3><?= htmlspecialchars($item['title']) ?></h3>
          <p><?= htmlspecialchars($item['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="about-capabilities">
      <div class="section-label">Where Tablet Masters Shows Up</div>
      <h2 class="about-section-title">Core capabilities across individual buyers, organizations, and fleet environments.</h2>

      <div class="about-capability-grid">
        <?php foreach ($capabilities as $item): ?>
        <article class="about-capability-card">
          <span class="about-capability-icon"><i class="<?= htmlspecialchars($item['icon']) ?>"></i></span>
          <h3><?= htmlspecialchars($item['title']) ?></h3>
          <p><?= htmlspecialchars($item['desc']) ?></p>
          <a
            class="about-capability-link"
            href="<?= htmlspecialchars($item['href']) ?>"
            <?= !empty($item['external']) ? 'target="_blank" rel="noreferrer"' : '' ?>
          ><?= htmlspecialchars($item['link']) ?></a>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="about-bottom-cta">
      <div class="about-bottom-copy">
        <div class="section-label">Need the right next step?</div>
        <h2 class="about-section-title">Start with the part of the business that matches the problem you are actually trying to solve.</h2>
        <p>
          Shop tablets, review coverage plans, request organization support, or contact the team directly.
          The site should point people into the right lane instead of forcing every visitor through the same message.
        </p>
      </div>

      <div class="about-action-stack">
        <a class="btn-primary full" href="shop.php">Browse Tablets</a>
        <a class="btn-outline full" href="plans.php">Review Plans</a>
        <a class="btn-outline full" href="support.php">Visit Support Center</a>
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
