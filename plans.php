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
    'features' => ['Screen &amp; battery coverage','Accidental damage included','Email support','Basic cloud setup'],
    'cta'      => 'Get Basic',
    'cta_href' => null,
  ],
  [
    'name'     => 'PROTECTED',
    'price'    => '12',
    'period'   => 'per month',
    'featured' => true,
    'plan_key' => 'protected',
    'features' => ['Everything in Basic','Lifetime replacement (1&times;)','Priority phone support','Full cloud configuration','Annual device checkup'],
    'cta'      => 'Choose Protected',
    'cta_href' => null,
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

      <?php if ($plan['plan_key']): ?>
      <form method="POST" action="/subscribe.php" class="plan-form">
        <input type="hidden" name="plan" value="<?= htmlspecialchars($plan['plan_key']) ?>">
        <button type="submit"
          class="<?= $plan['featured'] ? 'btn-primary full' : 'btn-outline full' ?> plan-cta-btn"
        ><?= htmlspecialchars($plan['cta']) ?></button>
      </form>
      <?php else: ?>
      <a
        class="<?= $plan['featured'] ? 'btn-primary full' : 'btn-outline full' ?>"
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

document.querySelectorAll('.plan-form').forEach(function(form) {
  form.addEventListener('submit', function() {
    var btn = form.querySelector('.plan-cta-btn');
    var originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Setting up your plan\u2026';

    var toast = document.createElement('div');
    toast.textContent = '\u2713 ' + originalText + ' selected \u2014 redirecting to checkout\u2026';
    toast.style.cssText = [
      'position:fixed','bottom:24px','left:50%','transform:translateX(-50%)',
      'background:#1d4ed8','color:#fff','padding:14px 24px','border-radius:8px',
      'font-weight:600','font-size:15px','z-index:9999',
      'box-shadow:4px 4px 0 #1e3a8a','white-space:nowrap'
    ].join(';');
    document.body.appendChild(toast);
  });
});
</script>
</body>
</html>
