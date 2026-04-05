/* ── TABLET MASTERS — main.js ── */

// ── CART (localStorage) ──────────────────────────────────────────
function getCart() {
  try { return JSON.parse(localStorage.getItem('tm_cart') || '[]'); }
  catch(e) { return []; }
}

function saveCart(cart) {
  localStorage.setItem('tm_cart', JSON.stringify(cart));
}

function addToCart(product) {
  var cart = getCart();
  var existing = cart.find(function(i){ return i.id === product.id; });
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ id: product.id, name: product.name, brand: product.brand, price: product.price, emoji: product.emoji, qty: 1 });
  }
  saveCart(cart);
  updateCartBadge();
  renderCartItems();
  showToast('✓ ' + product.name + ' added to cart');
}

function adjustQty(id, delta) {
  var cart = getCart();
  cart = cart.map(function(i){
    if (i.id === id) return Object.assign({}, i, { qty: i.qty + delta });
    return i;
  }).filter(function(i){ return i.qty > 0; });
  saveCart(cart);
  updateCartBadge();
  renderCartItems();
}

function removeItem(id) {
  var cart = getCart().filter(function(i){ return i.id !== id; });
  saveCart(cart);
  updateCartBadge();
  renderCartItems();
}

function updateCartBadge() {
  var cart  = getCart();
  var count = cart.reduce(function(s, i){ return s + i.qty; }, 0);
  var badge = document.getElementById('cart-badge');
  if (badge) badge.textContent = count;
}

function fmtPrice(p) {
  return '$' + parseFloat(p).toFixed(2);
}

function renderCartItems() {
  var container = document.getElementById('cart-items');
  var footer    = document.getElementById('cart-footer');
  if (!container) return;

  var cart = getCart();

  if (cart.length === 0) {
    container.innerHTML =
      '<div class="cart-empty">' +
        '<div class="cart-empty-icon">🛒</div>' +
        '<div>Your cart is empty</div>' +
      '</div>';
    if (footer) footer.style.display = 'none';
    return;
  }

  var html = '';
  cart.forEach(function(item) {
    html +=
      '<div class="cart-item">' +
        '<div class="cart-item-emoji">' + item.emoji + '</div>' +
        '<div class="cart-item-info">' +
          '<div class="cart-item-name">' + escHtml(item.name) + '</div>' +
          '<div class="cart-item-price">' + fmtPrice(item.price) + '</div>' +
          '<div class="cart-item-qty">' +
            '<button class="qty-btn" onclick="adjustQty(' + item.id + ', -1)">−</button>' +
            '<span class="qty-num">' + item.qty + '</span>' +
            '<button class="qty-btn" onclick="adjustQty(' + item.id + ', 1)">+</button>' +
          '</div>' +
        '</div>' +
        '<button class="cart-remove" onclick="removeItem(' + item.id + ')">✕</button>' +
      '</div>';
  });
  container.innerHTML = html;

  if (footer) {
    var total = cart.reduce(function(s, i){ return s + i.price * i.qty; }, 0);
    footer.style.display = '';
    var totalEl = document.getElementById('cart-total');
    if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
  }

  var upsell = document.getElementById('cart-upsell');
  if (upsell) upsell.style.display = cart.length > 0 ? '' : 'none';
}

function escHtml(str) {
  var d = document.createElement('div');
  d.appendChild(document.createTextNode(str));
  return d.innerHTML;
}

// ── STRIPE CHECKOUT ──────────────────────────────────────────────
function stripeCheckout() {
  var cart = getCart();
  if (cart.length === 0) return;

  var btn = document.getElementById('checkout-btn');
  if (btn) { btn.disabled = true; btn.textContent = 'Redirecting...'; }

  // Submit as a standard form POST to avoid ModSecurity JSON blocking
  var form = document.createElement('form');
  form.method = 'POST';
  form.action = '/checkout.php';
  form.style.display = 'none';

  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'cart';
  input.value = JSON.stringify(cart.map(function(i){ return { id: i.id, qty: i.qty }; }));
  form.appendChild(input);

  // Include selected insurance plan
  var planInput = document.createElement('input');
  planInput.type = 'hidden';
  planInput.name = 'insurance_plan';
  var selectedPlan = document.querySelector('input[name="insurance_plan"]:checked');
  planInput.value = selectedPlan ? selectedPlan.value : 'none';
  form.appendChild(planInput);

  document.body.appendChild(form);
  form.submit();
}

// ── CART DRAWER ──────────────────────────────────────────────────
function openCart() {
  renderCartItems();
  document.getElementById('cart-drawer').classList.add('open');
  document.getElementById('cart-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeCart() {
  document.getElementById('cart-drawer').classList.remove('open');
  document.getElementById('cart-overlay').classList.remove('open');
  document.body.style.overflow = '';
}

// ── TOAST ────────────────────────────────────────────────────────
var toastTimer = null;
function showToast(msg) {
  var toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = msg;
  toast.classList.add('visible');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(function(){
    toast.classList.remove('visible');
  }, 2800);
}

// ── BRAND FILTER (shop page) ─────────────────────────────────────
function filterBrand(brand) {
  if (typeof window.TM_ACTIVE_BRAND !== 'undefined') {
    window.TM_ACTIVE_BRAND = brand;
  }

  // Update active tab
  document.querySelectorAll('.brand-tab').forEach(function(btn){
    btn.classList.toggle('active', btn.dataset.brand === brand);
  });

  if (typeof window.applyProductFilters === 'function') {
    window.applyProductFilters();
    return;
  }

  // Fallback for pages that only use brand filtering
  document.querySelectorAll('.product-card[data-brand]').forEach(function(card){
    var show = brand === 'All' || card.dataset.brand === brand;
    card.style.display = show ? '' : 'none';
  });
}

function getInitialShopBrand() {
  var params = new URLSearchParams(window.location.search);
  var brand = params.get('brand');
  if (!brand) return 'All';

  var isValid = Array.from(document.querySelectorAll('.brand-tab')).some(function(btn){
    return btn.dataset.brand === brand;
  });

  return isValid ? brand : 'All';
}

// ── VIDEO PLAY/PAUSE ──────────────────────────────────────────────
function initVideo() {
  var video   = document.getElementById('hero-video');
  var playBtn = document.getElementById('video-play-btn');
  if (!video || !playBtn) return;

  function updateBtn() {
    playBtn.textContent = video.paused ? '▶' : '⏸';
    if (!video.paused) {
      playBtn.classList.add('hidden');
    }
  }

  var wrapper = video.closest('.video-wrapper');
  if (wrapper) {
    wrapper.addEventListener('mouseenter', function(){
      playBtn.classList.remove('hidden');
    });
    wrapper.addEventListener('mouseleave', function(){
      if (!video.paused) playBtn.classList.add('hidden');
    });
  }

  playBtn.addEventListener('click', function(){
    if (video.paused) { video.play(); }
    else              { video.pause(); }
  });

  video.addEventListener('play',  updateBtn);
  video.addEventListener('pause', updateBtn);
  video.addEventListener('ended', function(){ playBtn.classList.remove('hidden'); playBtn.textContent = '▶'; });
}

// ── MOBILE NAV ───────────────────────────────────────────────────
function toggleMobileNav() {
  var nav = document.getElementById('mobile-nav');
  if (nav) nav.classList.toggle('open');
}

// ── REPAIR FORM (insurance page) ─────────────────────────────────
function initRepairForm() {
  var form = document.getElementById('repair-form');
  if (!form) return;

  // Smooth scroll to form when "Book a Repair" is clicked
  var bookBtn = document.getElementById('book-btn');
  if (bookBtn) {
    bookBtn.addEventListener('click', function(e){
      e.preventDefault();
      document.getElementById('book-form').scrollIntoView({ behavior: 'smooth' });
    });
  }
}

// ── INIT ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function(){
  updateCartBadge();
  initVideo();
  initRepairForm();

  // Close cart on overlay click
  var overlay = document.getElementById('cart-overlay');
  if (overlay) overlay.addEventListener('click', closeCart);

  // Default brand filter to the requested URL brand when present
  if (document.querySelector('.brand-tab')) {
    filterBrand(getInitialShopBrand());
  }
});
