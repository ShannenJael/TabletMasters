<?php $currentPage = 'register'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Your Device &mdash; Tablet Masters</title>
  <meta name="description" content="Register your tablet with Tablet Masters to activate insurance coverage. Works for tablets purchased here or anywhere else." />
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
$success   = isset($_GET['success']);
$duplicate = isset($_GET['duplicate']);
$error     = isset($_GET['error']);
$invalidPurchaseDate = isset($_GET['invalid_purchase_date']);
$allowedPlans = ['none', 'basic', 'protected'];
$requestedPlan = $_GET['plan'] ?? 'none';
$selectedPlan = in_array($requestedPlan, $allowedPlans, true) ? $requestedPlan : 'none';
$requestedSource = $_GET['source'] ?? '';
$selectedSource = in_array($requestedSource, ['tablet-masters', 'external'], true)
  ? $requestedSource
  : ($selectedPlan !== 'none' ? 'external' : 'tablet-masters');
$prefill   = [
  'email' => htmlspecialchars($_GET['email'] ?? ''),
  'order' => htmlspecialchars($_GET['order'] ?? ''),
  'model' => htmlspecialchars($_GET['model'] ?? ''),
];
?>

<div class="reg-page">
  <section class="reg-hero-card">
    <div class="reg-hero-copy">
      <div class="section-label">Device Registration</div>
      <h1 class="reg-page-title">Register your tablet with a cleaner, faster coverage flow.</h1>
      <p class="reg-page-intro">
        Link your device to Tablet Masters coverage in a few minutes. Whether you bought from us or from another retailer,
        the experience should feel straightforward, trustworthy, and easy to complete.
      </p>

      <?php if ($selectedPlan !== 'none'): ?>
      <div class="reg-plan-banner">
        <strong><?= htmlspecialchars(ucfirst($selectedPlan)) ?> selected.</strong>
        Complete registration to link this plan to your tablet before activation.
      </div>
      <?php endif; ?>

      <div class="reg-hero-points">
        <div class="reg-hero-point">
          <span class="reg-hero-icon"><i class="fa-solid fa-bolt"></i></span>
          <div>
            <strong>Fast to complete</strong>
            <span>Clear steps, less guesswork, and fewer missed details.</span>
          </div>
        </div>
        <div class="reg-hero-point">
          <span class="reg-hero-icon"><i class="fa-solid fa-shield-heart"></i></span>
          <div>
            <strong>Coverage clarity</strong>
            <span>External purchases can add protection now or later.</span>
          </div>
        </div>
        <div class="reg-hero-point">
          <span class="reg-hero-icon"><i class="fa-solid fa-envelope-open-text"></i></span>
          <div>
            <strong>Instant confirmation</strong>
            <span>Your registration details are sent to your inbox right away.</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="reg-layout">
    <div class="reg-main">
      <?php if ($success): ?>
      <div class="alert alert-success reg-alert" role="status">
        &#10003; Your device has been registered. Check your email for coverage confirmation and next steps.
      </div>
      <?php elseif ($duplicate): ?>
      <div class="alert alert-error reg-alert" role="alert">
        A device with that serial number is already registered. Contact <a href="support.php">support</a> if this looks incorrect.
      </div>
      <?php elseif ($error): ?>
      <div class="alert alert-error reg-alert" role="alert">
        Something went wrong while saving your registration. Please try again or <a href="support.php">contact us</a>.
      </div>
      <?php elseif ($invalidPurchaseDate): ?>
      <div class="alert alert-error reg-alert" role="alert">
        Purchase date must be today or earlier.
      </div>
      <?php endif; ?>

      <form id="reg-form" method="POST" action="/save-device.php" class="reg-form">
        <div class="reg-group">
          <div class="reg-group-header">
            <div class="reg-group-label">
              <span class="reg-step">01</span>
              <div>
                <h3>Your Information</h3>
                <p>Who should receive the confirmation and coverage updates.</p>
              </div>
            </div>
          </div>

          <div class="reg-field-grid reg-field-grid-2">
            <label class="reg-field reg-field-full">
              <span class="reg-label">Full name</span>
              <input class="repair-input" type="text" name="name" placeholder="Jordan Smith" autocomplete="name" required />
            </label>
            <label class="reg-field">
              <span class="reg-label">Email address</span>
              <input class="repair-input" type="email" name="email" placeholder="you@example.com" autocomplete="email" required value="<?= $prefill['email'] ?>" />
            </label>
            <label class="reg-field">
              <span class="reg-label">Phone number</span>
              <input class="repair-input" type="tel" name="phone" placeholder="Optional" autocomplete="tel" inputmode="tel" />
            </label>
          </div>
        </div>

        <div class="reg-group">
          <div class="reg-group-header">
            <div class="reg-group-label">
              <span class="reg-step">02</span>
              <div>
                <h3>Purchase Source</h3>
                <p>This decides whether coverage is already tied to an existing order or needs a plan selected now.</p>
              </div>
            </div>
          </div>

          <div class="reg-option-grid">
            <label class="reg-choice-card">
              <input type="radio" name="purchase_source" value="tablet-masters" <?= $selectedSource === 'tablet-masters' ? 'checked' : '' ?> />
              <span class="reg-choice-indicator" aria-hidden="true"></span>
              <span class="reg-choice-copy">
                <strong>Tablet Masters</strong>
                <span>Purchased on tablet-masters.com or attached to an existing order.</span>
              </span>
            </label>
            <label class="reg-choice-card">
              <input type="radio" name="purchase_source" value="external" <?= $selectedSource === 'external' ? 'checked' : '' ?> />
              <span class="reg-choice-indicator" aria-hidden="true"></span>
              <span class="reg-choice-copy">
                <strong>Another store</strong>
                <span>Amazon, Best Buy, a carrier store, or any outside retailer.</span>
              </span>
            </label>
          </div>
        </div>

        <div class="reg-group" id="reg-plan-group" <?= $selectedSource === 'external' ? '' : 'hidden' ?>>
          <div class="reg-group-header">
            <div class="reg-group-label">
              <span class="reg-step">03</span>
              <div>
                <h3>Protection Plan</h3>
                <p>External purchases need an active plan to start protection immediately.</p>
              </div>
            </div>
          </div>

          <div class="reg-plan-intro">
            Register now and add coverage later, or choose a protection tier before you finish.
          </div>

          <div class="reg-option-grid reg-plan-grid">
            <label class="reg-choice-card reg-plan-card">
              <input type="radio" name="plan" value="none" <?= $selectedPlan === 'none' ? 'checked' : '' ?> />
              <span class="reg-choice-indicator" aria-hidden="true"></span>
              <span class="reg-choice-copy">
                <strong>No plan yet</strong>
                <span>Register the device first and add protection later.</span>
              </span>
            </label>
            <label class="reg-choice-card reg-plan-card">
              <input type="radio" name="plan" value="basic" <?= $selectedPlan === 'basic' ? 'checked' : '' ?> />
              <span class="reg-choice-indicator" aria-hidden="true"></span>
              <span class="reg-choice-copy">
                <strong>Basic</strong>
                <span>Screen, battery, and accidental damage for $8/month with a deductible based on the damage.</span>
              </span>
            </label>
            <label class="reg-choice-card reg-plan-card reg-plan-card-featured">
              <input type="radio" name="plan" value="protected" <?= $selectedPlan === 'protected' ? 'checked' : '' ?> />
              <span class="reg-choice-indicator" aria-hidden="true"></span>
              <span class="reg-choice-copy">
                <strong>Protected <em>Popular</em></strong>
                <span>Everything in Basic plus full replacement coverage for up to 4 years with no deductible for $12/month.</span>
              </span>
            </label>
          </div>
        </div>

        <div class="reg-group">
          <div class="reg-group-header">
            <div class="reg-group-label">
              <span class="reg-step">04</span>
              <div>
                <h3>Device Details</h3>
                <p>Tell us exactly which tablet is being linked to coverage.</p>
              </div>
            </div>
          </div>

          <div class="reg-field-grid reg-field-grid-2">
            <label class="reg-field">
              <span class="reg-label">Brand</span>
              <select class="repair-input" name="brand" id="reg-brand" required>
                <option value="" disabled selected>Select brand</option>
                <option value="Apple">Apple</option>
                <option value="Samsung">Samsung</option>
                <option value="Microsoft">Microsoft</option>
                <option value="Amazon">Amazon</option>
                <option value="Other">Other brand</option>
              </select>
            </label>
            <label class="reg-field" id="reg-brand-other-wrap" hidden>
              <span class="reg-label">Other brand</span>
              <input class="repair-input" type="text" name="brand_other" id="reg-brand-other" placeholder="Enter brand name" />
            </label>
            <label class="reg-field reg-field-full">
              <span class="reg-label">Model</span>
              <input class="repair-input" type="text" name="model" placeholder='Galaxy Tab S10 Ultra, iPad Pro 11", Surface Pro, etc.' value="<?= $prefill['model'] ?>" required />
            </label>
            <label class="reg-field">
              <span class="reg-label">Serial number</span>
              <input class="repair-input reg-serial-input" type="text" name="serial_number" placeholder="Enter serial number" required />
            </label>
            <label class="reg-field">
              <span class="reg-label">Purchase date</span>
              <input class="repair-input" type="date" name="purchase_date" id="reg-purchase-date" title="Purchase date (optional)" max="<?= htmlspecialchars(date('Y-m-d')) ?>" />
            </label>
          </div>

          <div class="reg-inline-tip">
            <span class="reg-inline-tip-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            <p><strong>Where to find it:</strong> Android: Settings &rarr; About device &rarr; Serial number. iPadOS: Settings &rarr; General &rarr; About.</p>
          </div>
        </div>

        <input type="hidden" name="order_id" value="<?= $prefill['order'] ?>" />

        <div class="reg-submit-card">
          <button type="submit" class="btn-primary full"><?= $selectedPlan !== 'none' ? 'Register Tablet and Continue' : 'Register My Device' ?></button>
          <p class="reg-submit-note">
            Your device will be linked by serial number and your confirmation email will include coverage details and next steps.
          </p>
        </div>
      </form>
    </div>

    <aside class="reg-sidebar">
      <div class="reg-sidebar-card">
        <div class="reg-sidebar-section">
          <span class="reg-sidebar-label">Before You Submit</span>
          <ul class="reg-check-list">
            <li><i class="fa-solid fa-check"></i><span>Have the tablet serial number ready.</span></li>
            <li><i class="fa-solid fa-check"></i><span>Use the same email you want tied to coverage.</span></li>
            <li><i class="fa-solid fa-check"></i><span>Select a plan only if the tablet came from another store.</span></li>
            <li><i class="fa-solid fa-check"></i><span>Coverage remains active only while plan payments stay current.</span></li>
          </ul>
        </div>

        <div class="reg-sidebar-section">
          <span class="reg-sidebar-label">What Happens Next</span>
          <div class="reg-side-note">
            <strong>Confirmation email</strong>
            <p>Your registration number and any plan details arrive right away.</p>
          </div>
          <div class="reg-side-note">
            <strong>Claim-ready record</strong>
            <p>The serial number becomes the anchor for future support and repair claims.</p>
          </div>
          <div class="reg-side-note">
            <strong>Need help?</strong>
            <p>Visit <a href="insurance.php" class="reg-link">insurance &amp; repair</a> or <a href="support.php" class="reg-link">support</a> if something looks off.</p>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
(function() {
  var brandSel = document.getElementById('reg-brand');
  var brandOtherWrap = document.getElementById('reg-brand-other-wrap');
  var brandOther = document.getElementById('reg-brand-other');
  var sources = document.querySelectorAll('input[name="purchase_source"]');
  var planGroup = document.getElementById('reg-plan-group');
  var purchaseDate = document.getElementById('reg-purchase-date');

  function syncBrand() {
    var isOther = brandSel.value === 'Other';
    brandOtherWrap.hidden = !isOther;
    brandOther.required = isOther;
  }

  function syncSource() {
    var selectedSource = document.querySelector('input[name="purchase_source"]:checked');
    var isExternal = selectedSource && selectedSource.value === 'external';

    planGroup.hidden = !isExternal;

    if (!isExternal) {
      document.querySelector('input[name="plan"][value="none"]').checked = true;
    }
  }

  function syncPurchaseDate() {
    if (!purchaseDate) {
      return;
    }

    var today = new Date();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    var maxDate = today.getFullYear() + '-' + month + '-' + day;

    purchaseDate.max = maxDate;

    if (purchaseDate.value && purchaseDate.value > maxDate) {
      purchaseDate.setCustomValidity('Purchase date must be today or earlier.');
    } else {
      purchaseDate.setCustomValidity('');
    }
  }

  brandSel.addEventListener('change', syncBrand);

  sources.forEach(function(source) {
    source.addEventListener('change', syncSource);
  });

  if (purchaseDate) {
    purchaseDate.addEventListener('input', syncPurchaseDate);
    purchaseDate.addEventListener('change', syncPurchaseDate);
  }

  syncBrand();
  syncSource();
  syncPurchaseDate();
})();
</script>

<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js');
}
</script>
</body>
</html>
