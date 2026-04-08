<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <a class="logo" href="index.php" aria-label="Tablet Masters home">
        <img src="assets/images/tabletmasters-logo.png" alt="Tablet Masters" class="logo-img" />
      </a>
      <p>Tablets sales, service and cloud solutions. Protecting your tech investment since 2019.</p>
      <p class="footer-address">
        <strong>Mailing Address:</strong><br />
        TabletmastersLLC<br />
        550 Mary Esther Cutoff #18, PMB 376<br />
        Fort Walton Beach, FL 32548
      </p>
    </div>

    <div class="footer-col">
      <h4>Shop</h4>
      <a class="footer-link" href="shop.php?brand=Apple">Apple</a>
      <a class="footer-link" href="shop.php?brand=Samsung">Samsung</a>
      <a class="footer-link" href="shop.php?brand=Microsoft">Microsoft</a>
      <a class="footer-link" href="shop.php?brand=Amazon">Amazon</a>
      <a class="footer-link" href="insurance.php">Services</a>
    </div>

    <div class="footer-col">
      <h4>Company</h4>
      <a class="footer-link" href="about.php">About Us</a>
      <a class="footer-link" href="plans.php">Plans &amp; Pricing</a>
      <a class="footer-link" href="tablet-games.php">Tablet Games</a>
      <a class="footer-link" href="privacy.php">Privacy Policy</a>
      <a class="footer-link" href="terms.php">Terms of Service</a>
      <a class="footer-link" href="reviews.php">Reviews</a>
      <a class="footer-link" href="forum.php">Forum</a>
    </div>

    <div class="footer-col">
      <h4>Solutions</h4>
      <a class="footer-link" href="support.php">Support Center</a>
      <a class="footer-link" href="insurance.php">Insurance &amp; Repair</a>
      <a class="footer-link" href="schools.php">Education</a>
      <a class="footer-link" href="healthcare-hospitals.php">Healthcare</a>
      <a class="footer-link" href="business-conferences.php">Business</a>
      <a class="footer-link" href="about.php">Cloud Setup</a>
    </div>
  </div>

  <div class="footer-bottom">
    <span>&copy;2019&ndash;2026 Tablet Masters. All rights reserved.</span>
    <div class="social-links">
      <a class="social-link" href="https://facebook.com" target="_blank" rel="noreferrer">f</a>
      <a class="social-link" href="https://twitter.com"  target="_blank" rel="noreferrer">&#120143;</a>
      <a class="social-link" href="https://linkedin.com" target="_blank" rel="noreferrer">in</a>
    </div>
  </div>
</footer>

<!-- Cart Drawer -->
<div class="cart-overlay" id="cart-overlay"></div>
<div class="cart-drawer" id="cart-drawer">
  <div class="cart-header">
    <div class="cart-title">CART</div>
    <button class="cart-close" onclick="closeCart()">&#10005;</button>
  </div>
  <div class="cart-items" id="cart-items">
    <div class="cart-empty">
      <div class="cart-empty-icon">🛒</div>
      <div>Your cart is empty</div>
    </div>
  </div>
  <!-- Insurance Upsell -->
  <div class="cart-upsell" id="cart-upsell" style="display:none">
    <div class="cart-upsell-header">
      <span class="cart-upsell-icon">🛡️</span>
      <div>
        <div class="cart-upsell-title">Protect Your Device</div>
        <div class="cart-upsell-sub">Add a protection plan to your order</div>
      </div>
    </div>
    <div class="cart-upsell-plans">
      <label class="upsell-plan">
        <input type="radio" name="insurance_plan" value="none" checked>
        <div class="upsell-plan-info">
          <span class="upsell-plan-name">No thanks</span>
          <span class="upsell-plan-price">$0</span>
        </div>
      </label>
      <label class="upsell-plan">
        <input type="radio" name="insurance_plan" value="basic">
        <div class="upsell-plan-info">
          <span class="upsell-plan-name">Basic Protection</span>
          <span class="upsell-plan-price">$8<small>/mo</small></span>
        </div>
        <div class="upsell-plan-features">Screen &amp; battery coverage with a deductible based on damage</div>
      </label>
      <label class="upsell-plan featured-plan">
        <input type="radio" name="insurance_plan" value="protected">
        <div class="upsell-plan-info">
          <span class="upsell-plan-name">Protected <span class="upsell-badge">POPULAR</span></span>
          <span class="upsell-plan-price">$12<small>/mo</small></span>
        </div>
        <div class="upsell-plan-features">4-year full replacement with no deductible</div>
      </label>
    </div>
  </div>

  <div class="cart-footer" id="cart-footer" style="display:none">
    <div class="cart-summary-row" id="cart-plan-row" hidden>
      <span class="cart-summary-label" id="cart-plan-label">Protection</span>
      <span class="cart-summary-price" id="cart-plan-price">$0.00/mo</span>
    </div>
    <div class="cart-total-row">
      <span class="cart-total-label">Device total</span>
      <span class="cart-total-price" id="cart-total">$0.00</span>
    </div>
    <div class="cart-total-note" id="cart-total-note" hidden></div>
    <button class="btn-primary full" id="checkout-btn" onclick="stripeCheckout()">Checkout</button>
    <a class="cart-insurance-link" href="plans.php">View all coverage plans →</a>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script src="assets/js/main.js"></script>
