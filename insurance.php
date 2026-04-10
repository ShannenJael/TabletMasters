<?php $currentPage = 'insurance'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Insurance &amp; Repair &mdash; Tablet Masters</title>
  <meta name="description" content="Tablet Masters protection plans and tablet repair services. Compare coverage, understand claims, and book service in one place." />
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
$repairServices = [
  [
    'icon' => 'fa-solid fa-tablet-screen-button',
    'title' => 'Screen Repair',
    'desc' => 'Cracked glass, failed touch response, or dead display zones repaired with tablet-specific parts and clean installation.',
    'price' => 'From $49',
    'tag' => 'High demand'
  ],
  [
    'icon' => 'fa-solid fa-battery-half',
    'title' => 'Battery Replacement',
    'desc' => 'Restore battery runtime and charging reliability when a tablet no longer holds a practical daily charge.',
    'price' => 'From $39',
    'tag' => 'Same-day on many models'
  ],
  [
    'icon' => 'fa-solid fa-droplet',
    'title' => 'Water Damage',
    'desc' => 'Full cleaning, board inspection, and damage assessment for tablets exposed to water or other liquid intrusion.',
    'price' => 'Diagnostic: Free',
    'tag' => 'Urgent intake'
  ],
  [
    'icon' => 'fa-solid fa-plug-circle-bolt',
    'title' => 'Charging Port',
    'desc' => 'Loose cables, no-charge behavior, or port wear repaired for USB-C, Lightning, and supported legacy connections.',
    'price' => 'From $29',
    'tag' => 'Power issues'
  ],
  [
    'icon' => 'fa-solid fa-camera',
    'title' => 'Camera Repair',
    'desc' => 'Front and rear camera problems fixed for scanning, conferencing, telehealth, and everyday use.',
    'price' => 'From $45',
    'tag' => 'Front or rear'
  ],
  [
    'icon' => 'fa-solid fa-volume-high',
    'title' => 'Speaker and Mic',
    'desc' => 'Low volume, distorted output, or dead microphones addressed without sending your tablet into a generic repair queue.',
    'price' => 'From $35',
    'tag' => 'Audio path repair'
  ],
  [
    'icon' => 'fa-solid fa-hard-drive',
    'title' => 'Data Recovery',
    'desc' => 'Recovery work for non-booting devices when photos, business files, or account data still need to be preserved.',
    'price' => 'Call for Quote',
    'tag' => 'Case-based'
  ],
  [
    'icon' => 'fa-solid fa-stethoscope',
    'title' => 'Full Diagnostic',
    'desc' => 'When the problem is unclear, we narrow it down before parts or labor are approved so the next step is grounded.',
    'price' => 'Free with Repair',
    'tag' => 'Best first step'
  ],
];

$claimSteps = [
  [
    'num' => '01',
    'title' => 'Tell us what happened',
    'desc' => 'Submit the device details, issue type, and how the damage occurred so the claim or repair intake starts with useful information.'
  ],
  [
    'num' => '02',
    'title' => 'We confirm coverage or repair path',
    'desc' => 'Our team reviews whether the request falls under plan coverage, paid repair, or diagnostic service.'
  ],
  [
    'num' => '03',
    'title' => 'Intake and approval',
    'desc' => 'Once we confirm the right path, we coordinate shipping, intake, or next-step communication and keep the process moving.'
  ],
  [
    'num' => '04',
    'title' => 'Repair, replace, and return',
    'desc' => 'Approved work moves into service quickly so customers get a repaired or replaced tablet back without prolonged downtime.'
  ],
];

$coverageLanes = [
  [
    'name' => 'Basic',
    'price' => '$8/mo',
    'tone' => 'Lower monthly entry',
    'copy' => 'Built for customers who want core protection and practical repair coverage without paying for the highest replacement tier.',
    'points' => [
      'Screen and battery coverage',
      'Accidental damage with deductible based on damage type',
      'Email support and basic cloud setup'
    ],
    'cta' => 'Choose Basic',
    'href' => 'register.php?source=external&plan=basic',
    'featured' => false
  ],
  [
    'name' => 'Protected',
    'price' => '$12/mo',
    'tone' => 'Full replacement lane',
    'copy' => 'The strongest consumer plan for customers who want long-run coverage and less friction when a tablet needs to be replaced.',
    'points' => [
      'Full replacement coverage for up to 4 years',
      'No deductible on covered replacement claims',
      'Priority phone support and annual device checkup'
    ],
    'cta' => 'Choose Protected',
    'href' => 'register.php?source=external&plan=protected',
    'featured' => true
  ]
];

$supportFacts = [
  ['value' => 'Tablet-first', 'label' => 'Repair and claim flow built specifically for tablets'],
  ['value' => '1 device', 'label' => 'Coverage must be tied to the exact registered tablet'],
  ['value' => 'Current billing', 'label' => 'Plan status stays active while payments remain current'],
];

$coverageItems = [
  'Basic starts at $8/month with a deductible based on damage type',
  'Protected includes full replacement coverage for up to 4 years',
  'No deductible on covered Protected replacement claims',
  'Coverage must be linked to a specific tablet before activation',
  'Same-brand replacement is used when available',
  'Tablet Masters may suspend or remove coverage for nonpayment',
];

$damageOptions = [
  ['value' => 'Screen Repair', 'icon' => 'fa-solid fa-mobile-screen-button', 'label' => 'Screen Repair'],
  ['value' => 'Battery Replacement', 'icon' => 'fa-solid fa-battery-half', 'label' => 'Battery'],
  ['value' => 'Charging Port', 'icon' => 'fa-solid fa-plug-circle-bolt', 'label' => 'Charging Port'],
  ['value' => 'Water Damage', 'icon' => 'fa-solid fa-droplet', 'label' => 'Water Damage'],
  ['value' => 'Camera Repair', 'icon' => 'fa-solid fa-camera', 'label' => 'Camera'],
  ['value' => 'Speaker / Mic', 'icon' => 'fa-solid fa-volume-high', 'label' => 'Speaker / Mic'],
  ['value' => "Won't Turn On", 'icon' => 'fa-solid fa-bolt', 'label' => "Won't Turn On"],
  ['value' => 'Data Recovery', 'icon' => 'fa-solid fa-hard-drive', 'label' => 'Data Recovery'],
  ['value' => 'Insurance Claim', 'icon' => 'fa-solid fa-shield-heart', 'label' => 'Insurance Claim'],
  ['value' => 'Full Diagnostic', 'icon' => 'fa-solid fa-stethoscope', 'label' => 'Full Diagnostic'],
  ['value' => 'Software Issue', 'icon' => 'fa-solid fa-laptop-code', 'label' => 'Software Issue'],
  ['value' => 'Other', 'icon' => 'fa-solid fa-screwdriver-wrench', 'label' => 'Other'],
];

$success = isset($_GET['sent']) && $_GET['sent'] === '1';
$error   = isset($_GET['err']) && $_GET['err'] === '1';
?>

<section class="ins-hero-redesign">
  <div class="ins-hero-shell">
    <div class="ins-hero-copy-redesign">
      <div class="section-label">Protection, Claims, and Repair in One Flow</div>
      <h1 class="ins-hero-title">TABLET COVERAGE SHOULD LEAD CLEANLY INTO TABLET SERVICE.</h1>
      <p class="ins-hero-intro">
        Tablet Masters handles both sides of the problem: the plan customers choose before damage happens,
        and the repair or claim path they need when something actually goes wrong. This page is built to make
        those paths obvious before someone fills out a form.
      </p>
      <div class="ins-hero-actions">
        <a class="btn-primary" href="#book-form" id="book-btn">Book a Repair</a>
        <a class="btn-outline" href="plans.php">Compare Plans</a>
      </div>
      <div class="ins-hero-facts">
        <?php foreach ($supportFacts as $fact): ?>
        <div class="ins-hero-fact">
          <strong><?= htmlspecialchars($fact['value']) ?></strong>
          <span><?= htmlspecialchars($fact['label']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <aside class="ins-highlight-panel">
      <div class="ins-highlight-head">
        <div class="ins-panel-eyebrow">What this page covers</div>
        <h2>Understand the protection rules before you ever need service.</h2>
      </div>
      <div class="ins-visual-panel">
        <img src="assets/images/Surface Pro 11.png" alt="Microsoft Surface tablet" class="ins-device-primary" />
        <img src="assets/images/iPad Air M2 13.png" alt="Apple iPad Air tablet" class="ins-device-secondary" />
      </div>
      <ul class="ins-coverage-list">
        <?php foreach ($coverageItems as $item): ?>
        <li><i class="fa-solid fa-check"></i><span><?= htmlspecialchars($item) ?></span></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>

<section class="ins-page-redesign">
  <div class="ins-shell">
    <div class="ins-section-head">
      <div class="section-label">Coverage Paths</div>
      <div class="section-title">Choose a plan lane, then keep the service path simple.</div>
      <p class="ins-intro-copy">
        Insurance and repair should not feel like two disconnected experiences. These coverage lanes are written to match
        the way Tablet Masters actually handles claims, replacement, and tablet support.
      </p>
    </div>

    <div class="ins-lane-grid">
      <?php foreach ($coverageLanes as $lane): ?>
      <article class="ins-lane-card<?= $lane['featured'] ? ' featured' : '' ?>">
        <div class="ins-lane-top">
          <div>
            <div class="ins-lane-eyebrow"><?= htmlspecialchars($lane['tone']) ?></div>
            <h3><?= htmlspecialchars($lane['name']) ?></h3>
          </div>
          <?php if ($lane['featured']): ?>
          <div class="ins-lane-pill">Best overall</div>
          <?php endif; ?>
        </div>
        <div class="ins-lane-price"><?= htmlspecialchars($lane['price']) ?></div>
        <p><?= htmlspecialchars($lane['copy']) ?></p>
        <ul class="ins-lane-points">
          <?php foreach ($lane['points'] as $point): ?>
          <li><i class="fa-solid fa-arrow-right"></i><span><?= htmlspecialchars($point) ?></span></li>
          <?php endforeach; ?>
        </ul>
        <a class="<?= $lane['featured'] ? 'btn-primary full' : 'btn-outline full' ?>" href="<?= htmlspecialchars($lane['href']) ?>"><?= htmlspecialchars($lane['cta']) ?></a>
      </article>
      <?php endforeach; ?>

      <article class="ins-lane-card ins-lane-card-service">
        <div class="ins-lane-eyebrow">Need service now?</div>
        <h3>Repair Intake</h3>
        <div class="ins-lane-price">Fast start</div>
        <p>If the tablet is already damaged, you do not need to guess whether to start with a claim or a repair quote. Start intake and we sort the path with you.</p>
        <ul class="ins-lane-points">
          <li><i class="fa-solid fa-arrow-right"></i><span>Claim review and repair intake can begin from the same form.</span></li>
          <li><i class="fa-solid fa-arrow-right"></i><span>Tablet-specific diagnostics help separate covered events from paid repair work.</span></li>
          <li><i class="fa-solid fa-arrow-right"></i><span>Clear next steps instead of generic repair-shop language.</span></li>
        </ul>
        <a class="btn-outline full" href="#book-form">Start Service Request</a>
      </article>
    </div>

    <section class="ins-services-block">
      <div class="ins-section-head ins-section-head-left">
        <div class="section-label">Certified Repair Services</div>
        <h2 class="ins-subtitle">Common issues we handle without sending customers through a vague repair loop.</h2>
      </div>

      <div class="ins-service-grid">
        <?php foreach ($repairServices as $service): ?>
        <article class="ins-service-card">
          <div class="ins-service-icon"><i class="<?= htmlspecialchars($service['icon']) ?>"></i></div>
          <div class="ins-service-meta">
            <span><?= htmlspecialchars($service['tag']) ?></span>
            <strong><?= htmlspecialchars($service['price']) ?></strong>
          </div>
          <h3><?= htmlspecialchars($service['title']) ?></h3>
          <p><?= htmlspecialchars($service['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="ins-steps-block">
      <div class="ins-section-head ins-section-head-left">
        <div class="section-label">Claims and Repair Flow</div>
        <h2 class="ins-subtitle">A claim should read like a process, not a mystery.</h2>
      </div>

      <div class="ins-step-grid">
        <?php foreach ($claimSteps as $step): ?>
        <article class="ins-step-card">
          <div class="ins-step-number"><?= htmlspecialchars($step['num']) ?></div>
          <h3><?= htmlspecialchars($step['title']) ?></h3>
          <p><?= htmlspecialchars($step['desc']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="ins-booking-shell" id="book-form">
      <aside class="ins-booking-aside">
        <div class="section-label">Start a Repair or Claim</div>
        <h2 class="ins-subtitle">Tell us what device is involved and what needs attention.</h2>
        <p class="ins-intro-copy">
          Use the form to start a repair request, report a covered issue, or begin with a diagnostic when the problem is still unclear.
        </p>

        <div class="ins-booking-note">
          <strong>Best use of this form</strong>
          <span>Repairs, claim starts, diagnostics, and damage intake for tablets already in the customer’s hands.</span>
        </div>
        <div class="ins-booking-note">
          <strong>What happens next</strong>
          <span>We review the issue, determine whether it fits coverage or paid repair, then follow up with the right next step.</span>
        </div>
        <div class="ins-booking-note">
          <strong>Need to activate coverage instead?</strong>
          <span>Go to the registration flow if the tablet is being linked to a new plan for the first time.</span>
        </div>

        <div class="ins-booking-actions">
          <a class="btn-outline full" href="register.php">Go to Device Registration</a>
        </div>
      </aside>

      <div class="ins-form-panel">
        <?php if ($success): ?>
        <div class="alert alert-success" role="status">&#10003; Request submitted. We&rsquo;ll be in touch shortly.</div>
        <?php elseif ($error): ?>
        <div class="alert alert-error" role="alert">Something went wrong. Please try again or call us directly.</div>
        <?php endif; ?>

        <form id="repair-form" method="POST" action="send-repair.php" class="ins-form-grid">
          <label class="reg-field reg-field-full">
            <span class="reg-label">Full name</span>
            <input class="repair-input" type="text" name="name" placeholder="Your full name" required />
          </label>

          <label class="reg-field">
            <span class="reg-label">Email address</span>
            <input class="repair-input" type="email" name="email" placeholder="Email address" required />
          </label>

          <label class="reg-field">
            <span class="reg-label">Phone number</span>
            <input class="repair-input" type="tel" name="phone" placeholder="Phone number" />
          </label>

          <label class="reg-field reg-field-full">
            <span class="reg-label">Device</span>
            <select class="repair-input" name="device" required>
              <option value="" disabled selected>Select your device</option>
              <optgroup label="Apple">
                <option>iPad Pro</option>
                <option>iPad Air</option>
                <option>iPad Mini</option>
                <option>iPad (Standard)</option>
              </optgroup>
              <optgroup label="Samsung">
                <option>Galaxy Tab S Series</option>
                <option>Galaxy Tab A Series</option>
              </optgroup>
              <optgroup label="Microsoft">
                <option>Surface Pro</option>
                <option>Surface Go</option>
              </optgroup>
              <optgroup label="Amazon">
                <option>Fire HD 10</option>
                <option>Fire HD 8</option>
                <option>Fire Max 11</option>
                <option>Fire 7</option>
              </optgroup>
              <option value="Other">Other</option>
            </select>
          </label>

          <label class="reg-field reg-field-full" id="device-other-input-wrap" hidden>
            <span class="reg-label">Other device</span>
            <input class="repair-input" type="text" name="device_other" id="device-other-input"
              placeholder="Please describe your device (brand and model)" />
          </label>

          <div class="ins-form-section reg-field-full">
            <div class="damage-type-label">
              What needs fixing? <span style="color:#f87171">*</span>
              <small style="font-weight:400;color:#7f92b3">&nbsp;Select all that apply</small>
            </div>
            <input type="hidden" name="type" id="repair-type-hidden" />
            <div class="damage-cards" id="damage-cards">
              <?php foreach ($damageOptions as $option): ?>
              <button type="button" class="damage-card" data-value="<?= htmlspecialchars($option['value']) ?>">
                <span class="damage-icon"><i class="<?= htmlspecialchars($option['icon']) ?>"></i></span>
                <span class="damage-name"><?= htmlspecialchars($option['label']) ?></span>
              </button>
              <?php endforeach; ?>
            </div>
            <div class="damage-selection-hint" id="damage-hint" style="display:none">
              &#10003; <span id="damage-selected-label"></span>
            </div>
          </div>

          <label class="reg-field reg-field-full">
            <span class="reg-label">Issue notes</span>
            <textarea class="repair-input" name="notes" placeholder="Describe the issue, what happened, or whether this may be a plan claim." rows="4" style="resize:vertical"></textarea>
          </label>

          <label class="reg-field reg-field-full">
            <span class="reg-label">Preferred contact</span>
            <select class="repair-input" name="preferred_contact">
              <option value="email">Preferred contact: Email</option>
              <option value="phone">Preferred contact: Phone</option>
              <option value="either">Preferred contact: Either</option>
            </select>
          </label>

          <div class="ins-form-submit reg-field-full">
            <button type="submit" class="btn-primary full">Submit Repair Request</button>
            <p>Your request starts the intake process for repair, diagnostic, or insurance claim review.</p>
          </div>
        </form>
      </div>
    </section>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('book-btn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('book-form').scrollIntoView({ behavior: 'smooth' });
});

(function() {
  var sel = document.querySelector('select[name="device"]');
  var wrap = document.getElementById('device-other-input-wrap');
  var inp = document.getElementById('device-other-input');
  if (!sel || !inp || !wrap) return;

  sel.addEventListener('change', function() {
    var isOther = this.value === 'Other';
    wrap.hidden = !isOther;
    inp.required = isOther;
  });
})();

(function() {
  var cards = document.querySelectorAll('.damage-card');
  var hidden = document.getElementById('repair-type-hidden');
  var hint = document.getElementById('damage-hint');
  var hintLabel = document.getElementById('damage-selected-label');

  function updateHint() {
    var selected = [];
    cards.forEach(function(card) {
      if (card.classList.contains('selected')) selected.push(card.getAttribute('data-value'));
    });

    hidden.value = selected.join(', ');
    if (selected.length > 0) {
      if (hint) hint.style.display = '';
      if (hintLabel) {
        hintLabel.textContent = selected.length === 1
          ? selected[0] + ' selected'
          : selected.length + ' issues selected';
      }
    } else if (hint) {
      hint.style.display = 'none';
    }
  }

  cards.forEach(function(card) {
    card.addEventListener('click', function() {
      card.classList.toggle('selected');
      updateHint();
    });
  });

  var form = document.getElementById('repair-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      if (!hidden.value) {
        e.preventDefault();
        var grid = document.getElementById('damage-cards');
        grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        grid.style.outline = '2px solid #f87171';
        grid.style.borderRadius = '12px';
        setTimeout(function() { grid.style.outline = ''; }, 2000);
      }
    });
  }
})();
</script>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
