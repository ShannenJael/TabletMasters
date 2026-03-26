<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <a class="logo" href="index.php" style="font-size:22px">
        <span class="logo-dot"></span> TABLET MASTERS
      </a>
      <p>Tablets sales, service and cloud solutions. Protecting your tech investment since 2019.</p>
    </div>

    <div class="footer-col">
      <h4>Shop</h4>
      <a class="footer-link" href="shop.php">Apple</a>
      <a class="footer-link" href="shop.php">Samsung</a>
      <a class="footer-link" href="shop.php">Microsoft</a>
      <a class="footer-link" href="shop.php">Amazon</a>
      <a class="footer-link" href="insurance.php">Services</a>
    </div>

    <div class="footer-col">
      <h4>Company</h4>
      <a class="footer-link" href="about.php">About Us</a>
      <a class="footer-link" href="plans.php">Plans &amp; Pricing</a>
      <a class="footer-link" href="#">Reviews</a>
      <a class="footer-link" href="#">Forum</a>
    </div>

    <div class="footer-col">
      <h4>Solutions</h4>
      <a class="footer-link" href="insurance.php">Insurance &amp; Repair</a>
      <a class="footer-link" href="about.php">Education</a>
      <a class="footer-link" href="about.php">Business</a>
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
  <div class="cart-footer" id="cart-footer" style="display:none">
    <div class="cart-total-row">
      <span class="cart-total-label">Total</span>
      <span class="cart-total-price" id="cart-total">$0.00</span>
    </div>
    <button class="btn-primary full">Checkout</button>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script src="assets/js/main.js"></script>
