<?php $currentPage = 'plans'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Plans &amp; Pricing — Tablet Masters</title>
  <meta name="description" content="Choose a Tablet Masters protection plan. Basic, Protected, and Enterprise coverage options for every need." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php
$plans = [
  [
    'name'     => 'BASIC',
    'price'    => '0',
    'period'   => 'No monthly fee',
    'featured' => false,
    'features' => ['Device purchase access','Standard warranty','Email support','Basic cloud setup'],
    'cta'      => 'Get Started',
    'cta_href' => 'shop.php',
  ],
  [
    'name'     => 'PROTECTED',
    'price'    => '12',
    'period'   => 'per month &middot; billed annually',
    'featured' => true,
    'features' => ['Everything in Basic','Lifetime replacement (1&times;)','Priority phone support','Full cloud configuration','Annual device checkup'],
    'cta'      => 'Choose Protected',
    'cta_href' => 'shop.php',
  ],
  [
    'name'     => 'ENTERPRISE',
    'price'    => '49',
    'period'   => 'per device &middot; per month',
    'featured' => false,
    'features' => ['Everything in Protected','MDM / fleet management','Dedicated account rep','Custom app deployment','On-site service visits'],
    'cta'      => 'Contact Sales',
    'cta_href' => 'insurance.php',
  ],
];
?>

<div class="plans-section">
  <div class="plans-header">
    <div class="section-label">// Choose Your Coverage</div>
    <div class="section-title">PLANS &amp; PRICING</div>
  </div>

  <div class="plans-grid">
    <?php foreach ($plans as $plan): ?>
    <div class="plan-card<?= $plan['featured'] ? ' featured' : '' ?>">
      <?php if ($plan['featured']): ?>
      <div class="plan-pill">MOST POPULAR</div>
      <?php endif; ?>

      <div class="plan-name<?= $plan['featured'] ? ' featured-name' : '' ?>"><?= htmlspecialchars($plan['name']) ?></div>

      <div class="plan-price"><sup>$</sup><?= htmlspecialchars($plan['price']) ?></div>
      <div class="plan-period"><?= $plan['period'] ?></div>

      <ul class="plan-features">
        <?php foreach ($plan['features'] as $f): ?>
        <li><span class="plan-dot"></span><?= $f ?></li>
        <?php endforeach; ?>
      </ul>

      <a
        class="<?= $plan['featured'] ? 'btn-primary full' : 'btn-outline full' ?>"
        href="<?= htmlspecialchars($plan['cta_href']) ?>"
      ><?= htmlspecialchars($plan['cta']) ?></a>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
