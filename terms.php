<?php $currentPage = ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Terms of Service - Tablet Masters</title>
  <meta name="description" content="Read the Tablet Masters Terms of Service for purchases, repairs, protection plans, site use, and related services." />
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

<?php $lastUpdated = 'November 19, 2026'; ?>

<div class="policy-section">
  <div class="policy-hero">
    <h1 class="section-title">TERMS OF SERVICE</h1>
    <div class="policy-meta">Last updated: <?= htmlspecialchars($lastUpdated) ?></div>
  </div>

  <div class="policy-grid">
    <section class="policy-card">
      <h2>Acceptance of Terms</h2>
      <p>
        By using the site, submitting a request, or making a purchase, you agree to these terms
        and our <a href="privacy.php">Privacy Policy</a>. If you disagree, do not use the site or services.
      </p>
    </section>

    <section class="policy-card" aria-labelledby="sms-terms-heading">
      <h2 id="sms-terms-heading">SMS Terms</h2>
      <p>
        If you opt in, Tablet Masters (TabletmastersLLC) may text you about orders, device registration,
        and coverage. Message frequency varies. Message and data rates may apply.
        Reply <strong>STOP</strong> to opt out or <strong>HELP</strong> for help.
        Read the <a href="sms-terms.php">full SMS Terms</a>.
      </p>
    </section>

    <section class="policy-card">
      <h2>Services Covered</h2>
      <p>
        These terms cover tablet sales and checkout, repairs and diagnostics, protection and replacement
        services, cloud and customer support, and business, education, and fleet inquiries.
      </p>
    </section>

    <section class="policy-card">
      <h2>Product Listings and Availability</h2>
      <p>
        Listings are for general information and sales. Prices, stock, condition, images, and other product
        details may change without notice. We may correct listing errors and limit or cancel orders when necessary.
      </p>
    </section>

    <section class="policy-card">
      <h2>Orders and Payment</h2>
      <ul class="policy-list">
        <li>Orders are subject to acceptance and availability.</li>
        <li>We use Stripe Checkout or other approved payment tools.</li>
        <li>Provide accurate billing, shipping, and contact information.</li>
        <li>We may decline, limit, or cancel transactions for pricing issues, suspected fraud, abuse, or operational problems.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Shipping and Risk</h2>
      <p>
        Delivery estimates are guaranteed only when expressly stated in writing. Ownership and risk of loss
        transfer according to the shipping method, carrier handling, and fulfillment arrangements for your order.
        Confirm your delivery address before purchasing.
      </p>
    </section>

    <section class="policy-card">
      <h2>Repairs</h2>
      <ul class="policy-list">
        <li>Repair and diagnostic timelines are estimates and may vary by model, part availability, and device condition.</li>
        <li>Back up your device and remove personal information where possible before service.</li>
        <li>Tablet Masters is not responsible for data loss before, during, or after repair unless required by law.</li>
        <li>Repairs may reveal issues that were not apparent at intake.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Protection Plans and Replacements</h2>
      <p>
        Site summaries are informational. Coverage, replacements, and deductibles depend on your plan,
        product, claim history, eligibility, payment status, and any written terms provided at purchase or claim review.
        Coverage transfers only with our express permission.
      </p>
      <p class="policy-note">
        Eligibility and claim decisions may also depend on verification, inventory, and misuse review.
        We may suspend or end coverage for nonpayment.
      </p>
    </section>

    <section class="policy-card">
      <h2>Returns, Refunds, and Cancellations</h2>
      <p>
        The terms given at purchase or service intake govern returns, refunds, exchanges, cancellations,
        and replacement claims unless a separate written policy applies to an order, plan, promotion, or repair.
        Where permitted, shipping, restocking, prior service value, damage, or other reasonable charges may reduce an approved refund.
      </p>
    </section>

    <section class="policy-card">
      <h2>Acceptable Use</h2>
      <ul class="policy-list">
        <li>Do not use the site for unlawful, fraudulent, abusive, or unauthorized purposes.</li>
        <li>Do not interfere with site security, checkout, or other users' access.</li>
        <li>Do not scrape, copy, or reuse site content in bulk without permission.</li>
      </ul>
    </section>

    <section class="policy-card">
      <h2>Intellectual Property</h2>
      <p>
        Unless otherwise stated, Tablet Masters owns or uses with permission the site's design, branding,
        text, graphics, layout, and other content. These terms grant only the limited right to use the site
        for its intended customer-facing purpose.
      </p>
    </section>

    <section class="policy-card">
      <h2>Third-Party Services</h2>
      <p>
        We may use third-party services for payments, email, hosting, or content delivery, and link to other sites.
        We are not responsible for their terms, privacy practices, or downtime.
      </p>
    </section>

    <section class="policy-card">
      <h2>Disclaimers</h2>
      <p>
        Where permitted by law, we provide the site and services "as is" and "as available."
        We do not guarantee uninterrupted access, error-free operation, or that products, repairs,
        or services will meet every expected purpose.
      </p>
    </section>

    <section class="policy-card">
      <h2>Limitation of Liability</h2>
      <p>
        To the maximum extent permitted by law, Tablet Masters is not liable for indirect,
        incidental, special, consequential, exemplary, or punitive damages, or for lost profits,
        data, business, or goodwill from use of the site, products, or services. Where liability cannot
        be excluded, it is limited to what you paid us for the product or service that gave rise to the claim.
      </p>
    </section>

    <section class="policy-card">
      <h2>Indemnity</h2>
      <p>
        You agree to defend, indemnify, and hold harmless Tablet Masters from claims, liabilities,
        losses, and expenses arising out of your misuse of the site, violation of these Terms, or
        infringement of another person's rights.
      </p>
    </section>

    <section class="policy-card">
      <h2>Changes to the Site or Terms</h2>
      <p>
        We may change, suspend, or discontinue all or part of the site or services at any time.
        We post updates to these terms here. Continued use of the site or services after changes take
        effect means you accept the updated terms.
      </p>
    </section>

    <section class="policy-card">
      <h2>Governing Law</h2>
      <p>
        The laws applicable to Tablet Masters' business operations govern these terms without regard to
        conflict-of-law rules, except as required by consumer protection law. Any written agreement that
        applies to your purchase or service may set the venue and dispute procedures.
      </p>
    </section>
  </div>

  <div class="policy-contact">
    <h2>Contact Us</h2>
    <p>
      For questions about these terms, email
      <a href="#" data-email-user="sretsamtelbaT" data-email-domain="moc.ggodduolc" data-email-text="1" rel="nofollow">Tabletmasters@clouddogg.com</a>.
    </p>
    <p>
      Mailing address: <strong>TabletmastersLLC, 550 Mary Esther Cutoff #18, PMB 376,
      Fort Walton Beach, FL 32548</strong>.
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
