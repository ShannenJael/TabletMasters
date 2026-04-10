<?php $currentPage = 'plans'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Plans &amp; Pricing &mdash; Tablet Masters</title>
  <meta name="description" content="Choose a Tablet Masters protection plan. Compare Basic, Protected, and Enterprise tablet coverage in one place." />
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
$plans = [
  [
    'name' => 'Basic',
    'eyebrow' => 'Essential protection',
    'price' => '8',
    'period' => 'per month',
    'featured' => false,
    'plan_key' => 'basic',
    'summary' => 'A lighter monthly plan for customers who want routine coverage without stepping into full replacement.',
    'best_for' => 'Best for everyday personal tablets',
    'claim_note' => 'Deductible varies based on the type of covered damage.',
    'support_note' => 'Email-first support with quick setup help.',
    'features' => [
      'Screen and battery coverage',
      'Accidental damage with deductible based on damage type',
      'Email support',
      'Basic cloud setup'
    ],
    'cta' => 'Register for Basic',
    'cta_href' => 'register.php?source=external&plan=basic',
    'accent_class' => 'plan-card-basic'
  ],
  [
    'name' => 'Protected',
    'eyebrow' => 'Most selected',
    'price' => '12',
    'period' => 'per month',
    'featured' => true,
    'plan_key' => 'protected',
    'summary' => 'The main plan for customers who want replacement confidence, stronger support, and longer coverage runway.',
    'best_for' => 'Best for families, power users, and higher-value tablets',
    'claim_note' => 'Full replacement coverage for up to 4 years with no deductible on covered replacement claims.',
    'support_note' => 'Priority phone support and annual device checkup included.',
    'features' => [
      'Everything in Basic',
      'Full replacement coverage for up to 4 years',
      'No deductible on covered replacement claims',
      'Priority phone support',
      'Full cloud configuration',
      'Annual device checkup'
    ],
    'cta' => 'Register for Protected',
    'cta_href' => 'register.php?source=external&plan=protected',
    'accent_class' => 'plan-card-protected'
  ],
  [
    'name' => 'Enterprise',
    'eyebrow' => 'Fleet operations',
    'price' => '49',
    'period' => 'per device &middot; per month',
    'featured' => false,
    'plan_key' => null,
    'summary' => 'Built for schools, business teams, and managed fleets that need more than a consumer support workflow.',
    'best_for' => 'Best for multi-device environments and managed deployments',
    'claim_note' => 'Custom service structure based on device count, deployment model, and support requirements.',
    'support_note' => 'Dedicated account coordination and operational support.',
    'features' => [
      'Everything in Protected',
      'MDM and fleet management',
      'Dedicated account rep',
      'Custom app deployment',
      'On-site service visits'
    ],
    'cta' => 'Talk to Sales',
    'cta_href' => 'insurance.php',
    'accent_class' => 'plan-card-enterprise'
  ],
];

$planComparison = [
  [
    'label' => 'Monthly price',
    'basic' => '$8',
    'protected' => '$12',
    'enterprise' => '$49 per device'
  ],
  [
    'label' => 'Primary coverage style',
    'basic' => 'Repair-focused essentials',
    'protected' => 'Full replacement path',
    'enterprise' => 'Managed fleet support'
  ],
  [
    'label' => 'Deductible structure',
    'basic' => 'Varies by damage type',
    'protected' => 'No deductible on covered replacement claims',
    'enterprise' => 'Custom by account'
  ],
  [
    'label' => 'Coverage window',
    'basic' => 'Active while payments remain current',
    'protected' => 'Up to 4 years while payments remain current',
    'enterprise' => 'Based on account agreement'
  ],
  [
    'label' => 'Support layer',
    'basic' => 'Email support',
    'protected' => 'Priority phone support',
    'enterprise' => 'Dedicated account support'
  ],
  [
    'label' => 'Best fit',
    'basic' => 'Single-device customers',
    'protected' => 'High-use household tablets',
    'enterprise' => 'Schools, clinics, business fleets'
  ]
];

$steps = [
  [
    'number' => '01',
    'title' => 'Choose the right coverage lane',
    'copy' => 'Pick Basic for lower monthly entry, Protected for stronger replacement coverage, or Enterprise for larger device programs.'
  ],
  [
    'number' => '02',
    'title' => 'Register the exact tablet',
    'copy' => 'Every plan must be attached to a specific device before activation so coverage and service records stay tied to the correct tablet.'
  ],
  [
    'number' => '03',
    'title' => 'Keep billing active',
    'copy' => 'Coverage stays active while payments remain current. Tablet Masters may suspend or remove coverage if payments stop.'
  ]
];
?>

<section class="plans-hero">
  <div class="plans-hero-inner">
    <div class="plans-hero-copy">
      <div class="section-label">Tablet Protection Designed Around Actual Usage</div>
      <h1 class="plans-hero-title">PLANS THAT FEEL CLEAR BEFORE THE CLAIM.</h1>
      <p class="plans-hero-intro">
        Tablet Masters plans are built for one thing: keeping a registered tablet covered without forcing customers
        through vague pricing language. Compare the monthly paths, see how replacement works, and choose the plan
        that matches the way the device is actually used.
      </p>
      <div class="plans-hero-actions">
        <a class="btn-primary" href="register.php?source=external&plan=protected">Start with Protected</a>
        <a class="btn-outline" href="#plan-compare">Compare Plans</a>
      </div>
      <div class="plans-hero-metrics">
        <div class="plans-hero-metric">
          <strong>3</strong>
          <span>coverage tracks</span>
        </div>
        <div class="plans-hero-metric">
          <strong>4 years</strong>
          <span>full replacement runway on Protected</span>
        </div>
        <div class="plans-hero-metric">
          <strong>1 device</strong>
          <span>registration required before activation</span>
        </div>
      </div>
    </div>

    <aside class="plans-hero-panel">
      <div class="plans-hero-panel-head">
        <div class="plans-panel-eyebrow">At a glance</div>
        <h2>What customers need to know before they register</h2>
      </div>
      <div class="plans-hero-visual">
        <img src="assets/images/iPad Pro M4 13.png" alt="Apple iPad Pro tablet" class="plans-device-primary" />
        <img src="assets/images/Galaxy Tab S10 Ultra.png" alt="Samsung Galaxy Tab tablet" class="plans-device-secondary" />
      </div>
      <div class="plans-policy-stack">
        <div class="plans-policy-card">
          <strong>Protected is the premium lane</strong>
          <span>$12 per month with full replacement coverage for up to 4 years and no deductible on covered replacement claims.</span>
        </div>
        <div class="plans-policy-card">
          <strong>Basic keeps monthly cost lower</strong>
          <span>$8 per month with a deductible that depends on the type of covered damage.</span>
        </div>
        <div class="plans-policy-card">
          <strong>Billing status matters</strong>
          <span>Coverage remains active only while payments stay current.</span>
        </div>
      </div>
    </aside>
  </div>
</section>

<section class="plans-page">
  <div class="plans-shell">
    <div class="plans-header">
      <div class="section-label">Choose Your Coverage</div>
      <div class="section-title">Three ways to protect the device you actually depend on.</div>
      <p class="plans-intro-copy">
        This page is built to make plan differences obvious. Each option below is meant for a different level of device use,
        claim tolerance, and support expectation.
      </p>
    </div>

    <div class="plans-grid plans-grid-redesign">
      <?php foreach ($plans as $plan): ?>
      <article class="plan-card plan-card-redesign <?= htmlspecialchars($plan['accent_class']) ?><?= $plan['featured'] ? ' featured' : '' ?>">
        <div class="plan-card-top">
          <div>
            <div class="plan-eyebrow"><?= htmlspecialchars($plan['eyebrow']) ?></div>
            <div class="plan-name<?= $plan['featured'] ? ' featured-name' : '' ?>"><?= htmlspecialchars(strtoupper($plan['name'])) ?></div>
          </div>
          <?php if ($plan['featured']): ?>
          <div class="plan-pill">Recommended</div>
          <?php endif; ?>
        </div>

        <p class="plan-summary"><?= htmlspecialchars($plan['summary']) ?></p>

        <div class="plan-price-row">
          <div class="plan-price"><sup>$</sup><?= htmlspecialchars($plan['price']) ?></div>
          <div class="plan-period"><?= $plan['period'] ?></div>
        </div>

        <div class="plan-focus-band">
          <span>Best fit</span>
          <strong><?= htmlspecialchars($plan['best_for']) ?></strong>
        </div>

        <ul class="plan-features">
          <?php foreach ($plan['features'] as $feature): ?>
          <li><span class="plan-dot"></span><?= htmlspecialchars($feature) ?></li>
          <?php endforeach; ?>
        </ul>

        <div class="plan-notes">
          <div class="plan-note-box">
            <span>Claims</span>
            <strong><?= htmlspecialchars($plan['claim_note']) ?></strong>
          </div>
          <div class="plan-note-box">
            <span>Support</span>
            <strong><?= htmlspecialchars($plan['support_note']) ?></strong>
          </div>
        </div>

        <a
          class="<?= $plan['featured'] ? 'btn-primary full' : 'btn-outline full' ?><?= $plan['plan_key'] ? ' plan-cta-link' : '' ?>"
          href="<?= htmlspecialchars($plan['cta_href']) ?>"
        ><?= htmlspecialchars($plan['cta']) ?></a>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="plans-info-grid">
      <section class="plans-how">
        <div class="section-label">How Activation Works</div>
        <h2 class="plans-subtitle">The flow is simple, but the device registration step matters.</h2>
        <div class="plans-step-grid">
          <?php foreach ($steps as $step): ?>
          <article class="plans-step-card">
            <div class="plans-step-number"><?= htmlspecialchars($step['number']) ?></div>
            <h3><?= htmlspecialchars($step['title']) ?></h3>
            <p><?= htmlspecialchars($step['copy']) ?></p>
          </article>
          <?php endforeach; ?>
        </div>
      </section>

      <aside class="plans-side-panel">
        <div class="section-label">Coverage Rules</div>
        <h2 class="plans-subtitle">Clear policy language beats clever wording.</h2>
        <ul class="plans-policy-list">
          <li><i class="fas fa-check-circle"></i><span>Every plan must be linked to a specific tablet before activation.</span></li>
          <li><i class="fas fa-check-circle"></i><span>Protected includes full replacement coverage for up to 4 years while payments remain current.</span></li>
          <li><i class="fas fa-check-circle"></i><span>Basic keeps monthly cost lower, but deductible amount depends on damage type.</span></li>
          <li><i class="fas fa-check-circle"></i><span>Tablet Masters may suspend or remove coverage when payment is not kept current.</span></li>
        </ul>
        <a class="btn-outline full" href="insurance.php">Need a Custom Team Plan?</a>
      </aside>
    </div>

    <section class="plans-compare" id="plan-compare">
      <div class="plans-compare-head">
        <div class="section-label">Side-by-Side View</div>
        <h2 class="plans-subtitle">Compare the plan mechanics before you commit.</h2>
        <p class="plans-intro-copy">
          This is the cleanest way to see what changes as you move from essential protection to full replacement or fleet support.
        </p>
      </div>

      <div class="plans-compare-table-wrap">
        <table class="plans-compare-table">
          <thead>
            <tr>
              <th>Plan detail</th>
              <th>Basic</th>
              <th>Protected</th>
              <th>Enterprise</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($planComparison as $row): ?>
            <tr>
              <th><?= htmlspecialchars($row['label']) ?></th>
              <td><?= htmlspecialchars($row['basic']) ?></td>
              <td><?= htmlspecialchars($row['protected']) ?></td>
              <td><?= htmlspecialchars($row['enterprise']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="plans-bottom-cta">
      <div>
        <div class="section-label">Ready to register?</div>
        <h2 class="plans-subtitle">Choose the protection level that matches the device, then attach it to the tablet.</h2>
      </div>
      <div class="plans-bottom-actions">
        <a class="btn-primary" href="register.php?source=external&plan=protected">Register Protected</a>
        <a class="btn-outline" href="register.php?source=external&plan=basic">Register Basic</a>
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
