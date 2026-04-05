<?php $currentPage = 'insurance'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Insurance &amp; Repair — Tablet Masters</title>
  <meta name="description" content="Tablet Masters lifetime device protection and certified repair services. Screen repair, battery replacement, water damage, and more." />
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
  ['icon'=>'📱','title'=>'Screen Repair',       'desc'=>'Cracked or shattered display? We replace with OEM-grade glass and digitizers for all major brands.',                   'price'=>'From $49',        'gold'=>false],
  ['icon'=>'🔋','title'=>'Battery Replacement', 'desc'=>'Restore full battery life with a certified replacement. Same-day service available on most models.',                    'price'=>'From $39',        'gold'=>false],
  ['icon'=>'💧','title'=>'Water Damage',        'desc'=>"Dropped in water? Our ultrasonic cleaning and component inspection service saves devices others can't.",                 'price'=>'Diagnostic: Free','gold'=>true],
  ['icon'=>'🔌','title'=>'Charging Port',       'desc'=>"Loose connection or won't charge at all — we replace USB-C, Lightning, and micro-USB ports.",                          'price'=>'From $29',        'gold'=>false],
  ['icon'=>'📷','title'=>'Camera Repair',       'desc'=>'Blurry photos, black screen, or front camera failure. We restore full camera functionality.',                           'price'=>'From $45',        'gold'=>false],
  ['icon'=>'🔊','title'=>'Speaker & Mic',       'desc'=>'Audio cutting out or muffled sound? Speaker module and microphone replacements done in-house.',                         'price'=>'From $35',        'gold'=>false],
  ['icon'=>'💾','title'=>'Data Recovery',       'desc'=>'Even from a non-booting device, our specialists can often recover photos, documents, and app data.',                    'price'=>'Call for Quote',  'gold'=>true],
  ['icon'=>'⚙️','title'=>'Full Diagnostic',    'desc'=>"Not sure what's wrong? We run a complete hardware and software diagnostic and give you a clear report.",               'price'=>'Free with Repair','gold'=>true],
];

$claimSteps = [
  ['num'=>'01','title'=>'Report Your Claim',   'desc'=>'Contact us online or by phone with your device serial number and a description of the issue.'],
  ['num'=>'02','title'=>'Approval in 24 hrs',  'desc'=>'Our team reviews your claim and confirms coverage. Most approvals come back within one business day.'],
  ['num'=>'03','title'=>'Ship or Arrange Intake', 'desc'=>'After approval, we will confirm the next intake step and provide shipping guidance or a prepaid label when applicable.'],
  ['num'=>'04','title'=>'Get Your Device Back', 'desc'=>'Repaired or replaced device returned within 48 hours of approval. We cover return shipping.'],
];

$coverageItems = [
  'One free replacement — any circumstance',
  'Accidental damage included',
  'No expiration date on coverage',
  'Deductible only applies on 2nd claim',
  'Same-brand replacement guaranteed',
  '48-hour processing on approved claims',
];

$repairTypes = [
  'Screen Repair','Battery Replacement','Water Damage','Charging Port',
  'Camera Repair','Speaker / Mic','Data Recovery','Insurance Claim','Full Diagnostic',
];

// Handle form submission (via send-repair.php POST)
$success = isset($_GET['sent']) && $_GET['sent'] === '1';
$error   = isset($_GET['err'])  && $_GET['err']  === '1';
?>

<div class="ins-section">

  <!-- ── HERO ── -->
  <div class="ins-hero">
    <div class="ins-hero-text">
      <div class="section-label">// Protection &amp; Service</div>
      <div class="section-title">INSURANCE<br />&amp; REPAIR</div>
      <p>
        Every Tablet Masters device comes with our signature lifetime protection &mdash; and when
        your tablet needs hands-on care, our certified technicians have you covered. Fast
        turnarounds, genuine parts, and transparent pricing on every repair.
      </p>
      <div class="ins-actions">
        <a class="btn-primary" href="#book-form" id="book-btn">Book a Repair</a>
        <a class="btn-outline" href="plans.php">View Coverage Plans</a>
      </div>
    </div>

    <div class="ins-coverage-card">
      <span class="ins-shield">🛡️</span>
      <h3>LIFETIME COVERAGE</h3>
      <p>Our guarantee that sets us apart from every other tablet retailer on the market.</p>
      <ul class="coverage-checklist">
        <?php foreach ($coverageItems as $item): ?>
        <li>
          <span class="check-dot">&#10003;</span>
          <?= htmlspecialchars($item) ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <!-- ── REPAIR SERVICES ── -->
  <div class="repair-section-title">
    <div class="section-label">// Certified Repair Services</div>
    <div class="section-title" style="font-size:44px">WHAT WE FIX</div>
  </div>

  <div class="repair-grid">
    <?php foreach ($repairServices as $s): ?>
    <div class="repair-card">
      <span class="repair-icon"><?= $s['icon'] ?></span>
      <h4><?= htmlspecialchars($s['title']) ?></h4>
      <p><?= htmlspecialchars($s['desc']) ?></p>
      <span class="repair-price-tag<?= $s['gold'] ? ' gold' : '' ?>"><?= htmlspecialchars($s['price']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- ── HOW TO CLAIM ── -->
  <div class="repair-section-title" style="margin-bottom:48px">
    <div class="section-label">// Simple 4-Step Process</div>
    <div class="section-title" style="font-size:44px">HOW TO CLAIM</div>
  </div>

  <div class="claim-steps">
    <?php foreach ($claimSteps as $s): ?>
    <div class="claim-step">
      <div class="claim-step-num"><?= htmlspecialchars($s['num']) ?></div>
      <h5><?= htmlspecialchars($s['title']) ?></h5>
      <p><?= htmlspecialchars($s['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- ── BOOK A REPAIR ── -->
  <div class="repair-cta" id="book-form">
    <div>
      <h3>BOOK A REPAIR</h3>
      <p>
        Drop us your details and we&rsquo;ll reach out to schedule your service appointment or
        start a claim &mdash; usually within a few hours.
      </p>
    </div>

    <div class="repair-form">
      <?php if ($success): ?>
      <div class="alert alert-success">&#10003; Request submitted! We&rsquo;ll be in touch shortly.</div>
      <?php elseif ($error): ?>
      <div class="alert alert-error">Something went wrong. Please try again or call us directly.</div>
      <?php endif; ?>

      <form id="repair-form" method="POST" action="send-repair.php">
        <input class="repair-input" type="text"  name="name"  placeholder="Your full name"   required />
        <input class="repair-input" type="email" name="email" placeholder="Email address"     required />
        <input class="repair-input" type="tel"   name="phone" placeholder="Phone number" />
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
          <option>Other</option>
        </select>
        <select class="repair-input" name="type" required>
          <option value="" disabled selected>Select repair type</option>
          <?php foreach ($repairTypes as $t): ?>
          <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
          <?php endforeach; ?>
        </select>
        <textarea class="repair-input" name="notes" placeholder="Describe the issue (optional)" rows="3" style="resize:vertical"></textarea>
        <select class="repair-input" name="preferred_contact">
          <option value="email">Preferred contact: Email</option>
          <option value="phone">Preferred contact: Phone</option>
          <option value="either">Preferred contact: Either</option>
        </select>
        <button type="submit" class="btn-primary full">Submit Repair Request</button>
      </form>
    </div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>

<script>
// Smooth scroll for "Book a Repair" anchor link
document.getElementById('book-btn').addEventListener('click', function(e){
  e.preventDefault();
  document.getElementById('book-form').scrollIntoView({ behavior: 'smooth' });
});
</script>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
