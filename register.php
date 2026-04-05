<?php $currentPage = 'register'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Your Device — Tablet Masters</title>
  <meta name="description" content="Register your tablet with Tablet Masters to activate insurance coverage. Works for tablets purchased here or anywhere else." />
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
$success   = isset($_GET['success']);
$duplicate = isset($_GET['duplicate']);
$error     = isset($_GET['error']);
$prefill   = [
  'email' => htmlspecialchars($_GET['email'] ?? ''),
  'order' => htmlspecialchars($_GET['order'] ?? ''),
  'model' => htmlspecialchars($_GET['model'] ?? ''),
];
?>

<div class="reg-section">

  <div class="reg-hero">
    <div class="section-label">// Device Coverage</div>
    <div class="section-title">REGISTER YOUR TABLET</div>
    <p>
      Link your tablet to an insurance plan to activate coverage. Works for devices purchased
      from Tablet Masters or any other store &mdash; same protection, same service.
    </p>
  </div>

  <?php if ($success): ?>
  <div class="alert alert-success" style="max-width:640px;margin:0 auto 32px">
    &#10003; Your device has been registered! Check your email for your coverage confirmation.
  </div>
  <?php elseif ($duplicate): ?>
  <div class="alert alert-error" style="max-width:640px;margin:0 auto 32px">
    A device with that serial number is already registered. Contact us if this is an error &mdash; <a href="support.php" style="color:inherit;font-weight:700">support</a>.
  </div>
  <?php elseif ($error): ?>
  <div class="alert alert-error" style="max-width:640px;margin:0 auto 32px">
    Something went wrong. Please try again or <a href="support.php" style="color:inherit;font-weight:700">contact us</a>.
  </div>
  <?php endif; ?>

  <form id="reg-form" method="POST" action="/save-device.php" class="reg-form">

    <!-- ── Section 1: Your Info ── -->
    <div class="reg-group">
      <div class="reg-group-label">
        <span class="reg-step">01</span>
        <h3>Your Information</h3>
      </div>
      <div class="reg-fields">
        <input class="repair-input" type="text"  name="name"  placeholder="Full name" required />
        <input class="repair-input" type="email" name="email" placeholder="Email address" required value="<?= $prefill['email'] ?>" />
        <input class="repair-input" type="tel"   name="phone" placeholder="Phone number (optional)" />
      </div>
    </div>

    <!-- ── Section 2: Device ── -->
    <div class="reg-group">
      <div class="reg-group-label">
        <span class="reg-step">02</span>
        <h3>Device Details</h3>
      </div>
      <div class="reg-fields">
        <select class="repair-input" name="brand" id="reg-brand" required>
          <option value="" disabled selected>Select brand</option>
          <option value="Apple">Apple</option>
          <option value="Samsung">Samsung</option>
          <option value="Microsoft">Microsoft</option>
          <option value="Amazon">Amazon</option>
          <option value="Other">Other brand</option>
        </select>
        <input class="repair-input" type="text" name="brand_other" id="reg-brand-other"
               placeholder="Brand name" style="display:none" />

        <input class="repair-input" type="text" name="model"
               placeholder="Model (e.g. Galaxy Tab S10 Ultra, iPad Pro M4 11&quot;)"
               value="<?= $prefill['model'] ?>" required />

        <input class="repair-input" type="text" name="serial_number"
               placeholder="Serial number" required
               style="text-transform:uppercase;letter-spacing:1px" />
        <p class="reg-hint">
          &#128269; <strong>Where to find it:</strong> Android: Settings &rarr; About device &rarr; Serial number &nbsp;|&nbsp; iOS/iPadOS: Settings &rarr; General &rarr; About
        </p>

        <input class="repair-input" type="date" name="purchase_date"
               title="Purchase date (optional)" />
      </div>
    </div>

    <!-- ── Section 3: Source ── -->
    <div class="reg-group">
      <div class="reg-group-label">
        <span class="reg-step">03</span>
        <h3>Where did you purchase it?</h3>
      </div>
      <div class="reg-fields">
        <div class="source-options">
          <label class="source-option">
            <input type="radio" name="purchase_source" value="tablet-masters"
                   <?= $prefill['order'] ? 'checked' : 'checked' ?> />
            <div class="source-option-body">
              <strong>Tablet Masters</strong>
              <span>Purchased on tablet-masters.com</span>
            </div>
          </label>
          <label class="source-option">
            <input type="radio" name="purchase_source" value="external" />
            <div class="source-option-body">
              <strong>Another store</strong>
              <span>Amazon, Best Buy, carrier store, etc.</span>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- ── Section 4: Plan (external only) ── -->
    <div class="reg-group" id="reg-plan-group" style="display:none">
      <div class="reg-group-label">
        <span class="reg-step">04</span>
        <h3>Choose a Protection Plan</h3>
      </div>
      <div class="reg-fields">
        <p class="reg-hint" style="margin-bottom:16px">
          Devices from other stores are not automatically covered. Select a plan to activate protection, or register now and add a plan later.
        </p>
        <div class="source-options">
          <label class="source-option">
            <input type="radio" name="plan" value="none" checked />
            <div class="source-option-body">
              <strong>No plan yet</strong>
              <span>Register device &mdash; add plan later</span>
            </div>
          </label>
          <label class="source-option">
            <input type="radio" name="plan" value="basic" />
            <div class="source-option-body">
              <strong>Basic &mdash; $8/mo</strong>
              <span>Screen, battery &amp; accidental damage</span>
            </div>
          </label>
          <label class="source-option">
            <input type="radio" name="plan" value="protected" />
            <div class="source-option-body">
              <strong>Protected &mdash; $12/mo</strong>
              <span>Everything + lifetime replacement</span>
            </div>
          </label>
        </div>
      </div>
    </div>

    <input type="hidden" name="order_id" value="<?= $prefill['order'] ?>" />

    <div style="max-width:640px;margin:0 auto">
      <button type="submit" class="btn-primary full">Register My Device &rarr;</button>
    </div>

  </form>

  <!-- ── What Happens Next ── -->
  <div class="reg-next">
    <div class="section-label" style="text-align:center">// After Registration</div>
    <div class="reg-next-grid">
      <div class="reg-next-step">
        <div class="reg-next-icon">&#9993;</div>
        <strong>Confirmation Email</strong>
        <p>You'll receive your registration number and coverage details instantly.</p>
      </div>
      <div class="reg-next-step">
        <div class="reg-next-icon">&#128274;</div>
        <strong>Serial Number Linked</strong>
        <p>Your device is now tied to your account by serial number — no receipt needed to file a claim.</p>
      </div>
      <div class="reg-next-step">
        <div class="reg-next-icon">&#128241;</div>
        <strong>Coverage Active</strong>
        <p>Once your plan is confirmed, coverage begins immediately. File a claim any time from <a href="insurance.php" class="reg-link">insurance &amp; repair</a>.</p>
      </div>
    </div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>

<script>
// Brand "Other" reveal
(function() {
  var brandSel   = document.getElementById('reg-brand');
  var brandOther = document.getElementById('reg-brand-other');
  brandSel.addEventListener('change', function() {
    var isOther = this.value === 'Other';
    brandOther.style.display = isOther ? '' : 'none';
    brandOther.required = isOther;
  });
})();

// Source toggle — show/hide plan section
(function() {
  var sources   = document.querySelectorAll('input[name="purchase_source"]');
  var planGroup = document.getElementById('reg-plan-group');
  sources.forEach(function(r) {
    r.addEventListener('change', function() {
      planGroup.style.display = this.value === 'external' ? '' : 'none';
    });
  });
})();
</script>

<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
