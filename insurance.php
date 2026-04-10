<?php $currentPage = 'insurance'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Insurance &amp; Repair &mdash; Tablet Masters</title>
  <meta name="description" content="Tablet Masters protection plans and certified repair services. Screen repair, battery replacement, water damage, and more." />
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
  ['icon' => 'fa-solid fa-tablet-screen-button', 'title' => 'Screen Repair', 'desc' => 'Cracked or shattered display? We replace with OEM-grade glass and digitizers for all major brands.', 'price' => 'From $49', 'gold' => false],
  ['icon' => 'fa-solid fa-battery-full', 'title' => 'Battery Replacement', 'desc' => 'Restore full battery life with a certified replacement. Same-day service available on most models.', 'price' => 'From $39', 'gold' => false],
  ['icon' => 'fa-solid fa-droplet', 'title' => 'Water Damage', 'desc' => 'Dropped in water? Our ultrasonic cleaning and component inspection service saves devices others cannot.', 'price' => 'Diagnostic: Free', 'gold' => true],
  ['icon' => 'fa-solid fa-plug-circle-bolt', 'title' => 'Charging Port', 'desc' => 'Loose connection or no charging at all? We replace USB-C, Lightning, and micro-USB ports.', 'price' => 'From $29', 'gold' => false],
  ['icon' => 'fa-solid fa-camera', 'title' => 'Camera Repair', 'desc' => 'Blurry photos, black screen, or front camera failure. We restore full camera functionality.', 'price' => 'From $45', 'gold' => false],
  ['icon' => 'fa-solid fa-volume-high', 'title' => 'Speaker & Mic', 'desc' => 'Audio cutting out or muffled sound? Speaker module and microphone replacements done in-house.', 'price' => 'From $35', 'gold' => false],
  ['icon' => 'fa-solid fa-database', 'title' => 'Data Recovery', 'desc' => 'Even from a non-booting device, our specialists can often recover photos, documents, and app data.', 'price' => 'Call for Quote', 'gold' => true],
  ['icon' => 'fa-solid fa-stethoscope', 'title' => 'Full Diagnostic', 'desc' => 'Not sure what is wrong? We run a complete hardware and software diagnostic and give you a clear report.', 'price' => 'Free with Repair', 'gold' => true],
];

$claimSteps = [
  ['num' => '01', 'title' => 'Report Your Claim', 'desc' => 'Contact us online or by phone with your device serial number and a description of the issue.'],
  ['num' => '02', 'title' => 'Approval in 24 hrs', 'desc' => 'Our team reviews your claim and confirms coverage. Most approvals come back within one business day.'],
  ['num' => '03', 'title' => 'Ship or Arrange Intake', 'desc' => 'After approval, we confirm the next intake step and provide shipping guidance or a prepaid label when applicable.'],
  ['num' => '04', 'title' => 'Get Your Device Back', 'desc' => 'Repaired or replaced device returned within 48 hours of approval. We cover return shipping.'],
];

$coverageItems = [
  'Basic plan starts at $8/month with a deductible based on damage type',
  'Protected plan includes full replacement coverage for up to 4 years',
  'No deductible on covered Protected replacement claims',
  'Accidental damage support included',
  'Same-brand replacement when available',
  'Coverage remains active while plan payments stay current',
  '48-hour processing on approved claims',
];

$priorityBands = [
  ['label' => 'Best For', 'value' => 'Customers who need practical repair support or long-run replacement confidence'],
  ['label' => 'Fastest Path', 'value' => 'Choose a plan, book a repair, or start a claim without decoding the whole page'],
  ['label' => 'What You Get', 'value' => 'Clear coverage language, transparent repair categories, and a guided intake form'],
];

$repairHighlights = [
  'Repair and replacement paths are presented separately',
  'Most claim approvals come back within one business day',
  'Certified repair coverage for the most common tablet failures',
];

$damageOptions = [
  ['value' => 'Screen Repair', 'icon' => 'fa-solid fa-tablet-screen-button', 'label' => 'Screen Repair'],
  ['value' => 'Battery Replacement', 'icon' => 'fa-solid fa-battery-full', 'label' => 'Battery'],
  ['value' => 'Charging Port', 'icon' => 'fa-solid fa-plug-circle-bolt', 'label' => 'Charging Port'],
  ['value' => 'Water Damage', 'icon' => 'fa-solid fa-droplet', 'label' => 'Water Damage'],
  ['value' => 'Camera Repair', 'icon' => 'fa-solid fa-camera', 'label' => 'Camera'],
  ['value' => 'Speaker / Mic', 'icon' => 'fa-solid fa-volume-high', 'label' => 'Speaker / Mic'],
  ['value' => 'Won\'t Turn On', 'icon' => 'fa-solid fa-bolt', 'label' => 'Won\'t Turn On'],
  ['value' => 'Data Recovery', 'icon' => 'fa-solid fa-database', 'label' => 'Data Recovery'],
  ['value' => 'Insurance Claim', 'icon' => 'fa-solid fa-shield-heart', 'label' => 'Insurance Claim'],
  ['value' => 'Full Diagnostic', 'icon' => 'fa-solid fa-stethoscope', 'label' => 'Full Diagnostic'],
  ['value' => 'Software Issue', 'icon' => 'fa-solid fa-laptop-medical', 'label' => 'Software Issue'],
  ['value' => 'Other', 'icon' => 'fa-solid fa-screwdriver-wrench', 'label' => 'Other'],
];

$success = isset($_GET['sent']) && $_GET['sent'] === '1';
$error = isset($_GET['err']) && $_GET['err'] === '1';
?>

<div class="insurance-page-shell insurance-page">
  <div class="insurance-hero-layout insurance-hero">
    <div class="insurance-hero-copy-shell insurance-hero-copy">
      <div class="section-label">// Protection &amp; Service</div>
      <div class="section-title">INSURANCE<br />&amp; REPAIR</div>
      <p class="insurance-lead">
        Tablet Masters protection is built around practical repair and replacement support. Choose the plan that fits your device,
        and when your tablet needs hands-on care, our certified technicians have you covered with fast turnarounds and transparent pricing.
      </p>

      <div class="insurance-actions">
        <a class="btn-primary" href="#book-form" id="book-btn">Book a Repair</a>
        <a class="btn-outline" href="plans.php">View Coverage Plans</a>
      </div>

      <div class="insurance-jump-links" aria-label="Insurance page sections">
        <a href="#insurance-coverage">Coverage</a>
        <a href="#insurance-repairs">Repairs</a>
        <a href="#insurance-claims">Claims</a>
        <a href="#book-form">Book Service</a>
      </div>

      <div class="insurance-priority-grid">
        <?php foreach ($priorityBands as $band): ?>
        <div class="insurance-priority-card">
          <span><?= htmlspecialchars($band['label']) ?></span>
          <strong><?= htmlspecialchars($band['value']) ?></strong>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="insurance-coverage-panel insurance-coverage-card" id="insurance-coverage">
      <span class="insurance-coverage-icon"><i class="fa-solid fa-shield-heart"></i></span>
      <h3>PLAN COVERAGE</h3>
      <p>Clear protection terms for repairs, replacement support, and active-plan customers.</p>
      <ul class="insurance-coverage-list">
        <?php foreach ($coverageItems as $item): ?>
        <li>
          <span class="check-dot">&#10003;</span>
          <?= htmlspecialchars($item) ?>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="insurance-coverage-note">
        <strong>Built to reduce confusion</strong>
        <span>Repair help, claim timing, and plan value are surfaced early so customers can choose the right next step faster.</span>
      </div>
    </div>
  </div>

  <div class="insurance-metrics-grid insurance-metrics">
    <div class="insurance-metric">
      <span class="insurance-metric-value">2</span>
      <span class="insurance-metric-label">clear paths: active coverage claims and direct repair requests</span>
    </div>
    <div class="insurance-metric">
      <span class="insurance-metric-value">8</span>
      <span class="insurance-metric-label">core repair categories surfaced up front for faster self-selection</span>
    </div>
    <div class="insurance-metric">
      <span class="insurance-metric-value">24h</span>
      <span class="insurance-metric-label">typical claim approval target before intake or shipping guidance</span>
    </div>
  </div>

  <div class="insurance-section-head" id="insurance-repairs">
    <div class="section-label">// Certified Repair Services</div>
    <div class="section-title" style="font-size:44px">WHAT WE FIX</div>
    <p class="insurance-section-copy">Each repair card answers a simple question quickly: what broke, what kind of service it is, and what the starting cost looks like.</p>
  </div>

  <div class="insurance-repair-grid">
    <?php foreach ($repairServices as $service): ?>
    <div class="insurance-repair-card">
      <span class="insurance-repair-icon"><i class="<?= htmlspecialchars($service['icon']) ?>"></i></span>
      <h4><?= htmlspecialchars($service['title']) ?></h4>
      <p><?= htmlspecialchars($service['desc']) ?></p>
      <span class="insurance-repair-tag<?= $service['gold'] ? ' gold' : '' ?>"><?= htmlspecialchars($service['price']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="insurance-section-head" id="insurance-claims" style="margin-bottom:48px">
    <div class="section-label">// Simple 4-Step Process</div>
    <div class="section-title" style="font-size:44px">HOW TO CLAIM</div>
    <p class="insurance-section-copy">The claim path is short on purpose. Report the issue, get approval, confirm intake, and get the repaired or replacement device back quickly.</p>
  </div>

  <div class="insurance-claim-grid">
    <?php foreach ($claimSteps as $step): ?>
    <div class="insurance-claim-step">
      <div class="insurance-claim-step-num"><?= htmlspecialchars($step['num']) ?></div>
      <h5><?= htmlspecialchars($step['title']) ?></h5>
      <p><?= htmlspecialchars($step['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="insurance-cta-shell insurance-cta" id="book-form">
    <div class="insurance-cta-copy">
      <h3>BOOK A REPAIR OR START A CLAIM</h3>
      <p>
        Drop in your details and we will reach out to schedule your service appointment or start a claim,
        usually within a few hours.
      </p>
      <p class="insurance-form-note">
        Share the device, what is wrong, and how you want to be contacted. We will take it from there.
      </p>

      <div class="insurance-response-panel">
        <div class="insurance-form-eyebrow">What This Form Does Best</div>
        <ul class="insurance-response-list">
          <?php foreach ($repairHighlights as $highlight): ?>
          <li><span class="check-dot">&#10003;</span><?= htmlspecialchars($highlight) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="insurance-form-shell">
      <?php if ($success): ?>
      <div class="alert alert-success">&#10003; Request submitted! We&rsquo;ll be in touch shortly.</div>
      <?php elseif ($error): ?>
      <div class="alert alert-error">Something went wrong. Please try again or call us directly.</div>
      <?php endif; ?>

      <form id="repair-form" method="POST" action="send-repair.php" class="insurance-form">
        <div class="insurance-form-intro">
          <strong>Start with the device and issue</strong>
          <span>The more specific the issue selection is, the faster the intake conversation can move.</span>
        </div>

        <div class="insurance-form-grid insurance-form-grid-2">
          <label class="insurance-field">
            <span>Your Name</span>
            <input class="repair-input" type="text" name="name" placeholder="Your full name" required />
          </label>
          <label class="insurance-field">
            <span>Email</span>
            <input class="repair-input" type="email" name="email" placeholder="Email address" required />
          </label>
        </div>

        <div class="insurance-form-grid insurance-form-grid-2">
          <label class="insurance-field">
            <span>Phone</span>
            <input class="repair-input" type="tel" name="phone" placeholder="Phone number" />
          </label>
          <label class="insurance-field">
            <span>Preferred Contact</span>
            <select class="repair-input" name="preferred_contact">
              <option value="email">Email</option>
              <option value="phone">Phone</option>
              <option value="either">Either</option>
            </select>
          </label>
        </div>

        <label class="insurance-field">
          <span>Device</span>
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

        <label class="insurance-field" id="device-other-field" style="display:none">
          <span>Other Device</span>
          <input class="repair-input" type="text" name="device_other" id="device-other-input" placeholder="Please describe your device (brand &amp; model)" />
        </label>

        <input type="hidden" name="type" id="repair-type-hidden" />

        <div class="insurance-damage-head">
          <div class="damage-type-label">
            What needs fixing? <span style="color:#f87171">*</span>
            <small>Select all that apply</small>
          </div>
          <div class="insurance-damage-copy">Choose the closest issue categories so we can route the request correctly on the first follow-up.</div>
        </div>

        <div class="insurance-damage-cards" id="damage-cards">
          <?php foreach ($damageOptions as $option): ?>
          <button type="button" class="insurance-damage-card" data-value="<?= htmlspecialchars($option['value']) ?>">
            <span class="insurance-damage-icon"><i class="<?= htmlspecialchars($option['icon']) ?>"></i></span>
            <span class="insurance-damage-name"><?= htmlspecialchars($option['label']) ?></span>
          </button>
          <?php endforeach; ?>
        </div>

        <div class="insurance-selection-hint" id="damage-hint" style="display:none">
          <div class="insurance-selection-heading">&#10003; Selected issues</div>
          <div class="insurance-selection-list" id="damage-selected-label"></div>
        </div>

        <label class="insurance-field">
          <span>Notes</span>
          <textarea class="repair-input insurance-textarea" name="notes" placeholder="Describe the issue, whether the device still turns on, and anything else we should know." rows="4"></textarea>
        </label>

        <button type="submit" class="btn-primary full">Submit Repair Request</button>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('book-btn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('book-form').scrollIntoView({ behavior: 'smooth' });
});

(function() {
  var sel = document.querySelector('select[name="device"]');
  var field = document.getElementById('device-other-field');
  var inp = document.getElementById('device-other-input');
  if (!sel || !field || !inp) return;
  sel.addEventListener('change', function() {
    var isOther = this.value === 'Other';
    field.style.display = isOther ? '' : 'none';
    inp.required = isOther;
  });
})();

(function() {
  var cards = document.querySelectorAll('.insurance-damage-card');
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
        hintLabel.innerHTML = selected.map(function(item) {
          return '<span class="insurance-selection-pill">' + item + '</span>';
        }).join('');
      }
    } else if (hint) {
      hint.style.display = 'none';
      if (hintLabel) hintLabel.innerHTML = '';
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
