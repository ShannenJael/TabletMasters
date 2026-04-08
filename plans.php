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
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#3B82F6" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php
$plans = [
  [
    'name'     => 'BASIC',
    'price'    => '8',
    'period'   => 'per month',
    'featured' => false,
    'plan_key' => 'basic',
    'features' => ['Screen &amp; battery coverage','Accidental damage with deductible based on damage type','Email support','Basic cloud setup'],
    'cta'      => 'Register for Basic',
    'cta_href' => 'register.php?source=external&plan=basic',
  ],
  [
    'name'     => 'PROTECTED',
    'price'    => '12',
    'period'   => 'per month',
    'featured' => true,
    'plan_key' => 'protected',
    'features' => ['Everything in Basic','Full replacement coverage for up to 4 years','No deductible on covered replacement claims','Priority phone support','Full cloud configuration','Annual device checkup'],
    'cta'      => 'Register for Protected',
    'cta_href' => 'register.php?source=external&plan=protected',
  ],
  [
    'name'     => 'ENTERPRISE',
    'price'    => '49',
    'period'   => 'per device &middot; per month',
    'featured' => false,
    'plan_key' => null,
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
    <p class="policy-intro" style="max-width:680px;margin:18px auto 0;">
      Every protection plan must be linked to a specific tablet before activation. Choose a plan below, then complete
      device registration on the next page.
    </p>
    <p class="policy-intro" style="max-width:680px;margin:12px auto 0;">
      Coverage stays active while plan payments remain current. Tablet Masters may suspend or remove coverage for nonpayment.
    </p>
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

      <?php if ($plan['cta_href']): ?>
      <a
        class="<?= $plan['featured'] ? 'btn-primary full' : 'btn-outline full' ?><?= $plan['plan_key'] ? ' plan-cta-link' : '' ?>"
        href="<?= htmlspecialchars($plan['cta_href']) ?>"
      ><?= htmlspecialchars($plan['cta']) ?></a>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
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
