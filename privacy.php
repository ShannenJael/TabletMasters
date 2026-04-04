<?php $currentPage = ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Privacy Policy - Tablet Masters</title>
  <meta name="description" content="Read the Tablet Masters privacy policy, including what information we collect, how we use it, and the choices available to customers and site visitors." />
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

<?php $lastUpdated = 'April 3, 2026'; ?>

<div class="policy-section">
  <div class="policy-hero">
    <div class="section-label">// Privacy & Data Practices</div>
    <div class="section-title">PRIVACY POLICY</div>
    <p class="policy-intro">
      This Privacy Policy explains how Tablet Masters collects, uses, stores, and shares
      personal information when you visit <strong>tablet-masters.com</strong>, submit a repair
      request, make a purchase, or otherwise interact with our services.
    </p>
    <div class="policy-meta">Last updated: <?= htmlspecialchars($lastUpdated) ?></div>
  </div>

  <div class="policy-grid">
    <section class="policy-card">
      <h2>Information We Collect</h2>
      <p>Depending on how you use the site, we may collect:</p>
      <ul class="policy-list">
        <li>Your name, email address, phone number, and repair request details when you submit the repair form.</li>
        <li>Your cart contents and product selections while you shop.</li>
        <li>Order-related details returned to us by Stripe, such as your email address, name, phone number, shipping details, Stripe session ID, and payment intent ID.</li>
        <li>Technical information typically provided by browsers and servers, such as IP address, browser type, device type, referring pages, and timestamps.</li>
        <li>Browser-side storage used to keep your cart and cached site files available on your device.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>How We Use Information</h2>
      <ul class="policy-list">
        <li>To provide the website, shopping cart, checkout flow, repair booking, and customer support.</li>
        <li>To process purchases, confirm orders, fulfill shipments, and manage service requests.</li>
        <li>To send transactional emails and service communications related to orders, repairs, and support.</li>
        <li>To maintain records, prevent misuse, troubleshoot issues, and improve site performance.</li>
        <li>To comply with legal, accounting, tax, and operational obligations.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Payments</h2>
      <p>
        Online payments are processed through Stripe Checkout. We do not store full payment
        card numbers or card security codes on the public site. When you complete checkout,
        Stripe may provide us with limited order and customer details needed to confirm and
        fulfill your purchase.
      </p>
      <p class="policy-note">
        Stripe handles payment information under its own terms and privacy practices.
      </p>
    </section>

    <section class="policy-card">
      <h2>Cookies, Local Storage, and Tracking</h2>
      <ul class="policy-list">
        <li>We currently use browser <strong>localStorage</strong> to remember cart contents on your device.</li>
        <li>We use a service worker and browser cache to improve loading speed and basic site performance.</li>
        <li>We do not currently use third-party advertising pixels or analytics scripts on the public site codebase we operate today.</li>
        <li>Our site does not currently respond to browser <strong>Do Not Track</strong> signals.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>How We Share Information</h2>
      <ul class="policy-list">
        <li>With payment providers such as Stripe to process transactions.</li>
        <li>With service providers that help us host the site, send email, maintain systems, and operate our business.</li>
        <li>With shipping, fulfillment, repair, insurance, or supplier partners when needed to complete your purchase or service request.</li>
        <li>When required by law, legal process, or to protect rights, safety, and the integrity of our services.</li>
      </ul>
      <p class="policy-note">We do not sell personal information for money.</p>
    </section>

    <section class="policy-card">
      <h2>Retention</h2>
      <p>
        We keep information for as long as reasonably necessary for order fulfillment, repair
        scheduling, customer support, recordkeeping, fraud prevention, and legal compliance.
        Information stored in your browser, such as cart contents, remains there until it is
        removed by site actions or cleared from your browser.
      </p>
    </section>

    <section class="policy-card">
      <h2>Your Choices</h2>
      <ul class="policy-list">
        <li>You can stop using the cart feature at any time and clear local browser storage from your browser settings.</li>
        <li>You may contact us to request access, correction, or deletion of information we maintain about you, subject to legal and operational limits.</li>
        <li>Depending on where you live, you may have additional privacy rights under applicable state law.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Security</h2>
      <p>
        We use reasonable administrative, technical, and operational measures to protect
        information we keep. No website, network, or transmission method is completely secure,
        so we cannot guarantee absolute security.
      </p>
    </section>

    <section class="policy-card">
      <h2>Children's Privacy</h2>
      <p>
        Our public site is not directed to children under 13, and we do not knowingly collect
        personal information online from children under 13 through the public website.
      </p>
    </section>

    <section class="policy-card">
      <h2>Third-Party Links</h2>
      <p>
        Our site may link to third-party services or websites. Their privacy practices are
        governed by their own policies, not this one.
      </p>
    </section>

    <section class="policy-card">
      <h2>Changes to This Policy</h2>
      <p>
        We may update this Privacy Policy from time to time. When we do, we will update the
        "Last updated" date on this page.
      </p>
    </section>
  </div>

  <div class="policy-contact">
    <h2>Contact Us</h2>
    <p>
      For privacy questions or requests, contact Tablet Masters at
      <a href="mailto:service@tablet-masters.com">service@tablet-masters.com</a>.
    </p>
    <p class="policy-note">
      This page is a practical website privacy policy based on the current public site
      implementation. It is not a substitute for legal review tailored to your business.
    </p>
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
