/* Tablet Masters - main.js */

function getCart() {
  try { return JSON.parse(localStorage.getItem('tm_cart') || '[]'); }
  catch (e) { return []; }
}

function saveCart(cart) {
  localStorage.setItem('tm_cart', JSON.stringify(cart));
}

var TM_INSURANCE_PLANS = {
  none: { key: 'none', name: 'No thanks', monthly: 0 },
  basic: { key: 'basic', name: 'Basic Protection', monthly: 8 },
  protected: { key: 'protected', name: 'Protected', monthly: 12 }
};

function getCartSubtotal(cart) {
  return (cart || getCart()).reduce(function(sum, item) {
    return sum + item.price * item.qty;
  }, 0);
}

function getSelectedInsurancePlan() {
  var selectedPlan = document.querySelector('input[name="insurance_plan"]:checked');
  var planKey = selectedPlan ? selectedPlan.value : 'none';
  return TM_INSURANCE_PLANS[planKey] || TM_INSURANCE_PLANS.none;
}

function fmtMonthly(price) {
  return fmtPrice(price) + '/mo';
}

function updateCartPricing(cart) {
  var activeCart = cart || getCart();
  var count = activeCart.reduce(function(sum, item) { return sum + item.qty; }, 0);
  var subtotal = getCartSubtotal(activeCart);
  var plan = getSelectedInsurancePlan();

  var promptSummary = document.getElementById('cart-prompt-summary');
  if (promptSummary) {
    var summary = count === 1
      ? '1 item | ' + fmtPrice(subtotal)
      : count + ' items | ' + fmtPrice(subtotal);

    if (plan.monthly > 0) {
      summary += ' + ' + fmtMonthly(plan.monthly);
    }

    promptSummary.textContent = summary;
  }

  var totalEl = document.getElementById('cart-total');
  if (totalEl) {
    var dueToday = subtotal + ((plan.monthly > 0 && activeCart.length > 0) ? plan.monthly : 0);
    totalEl.textContent = fmtPrice(dueToday);
  }

  var planRow = document.getElementById('cart-plan-row');
  var planLabel = document.getElementById('cart-plan-label');
  var planPrice = document.getElementById('cart-plan-price');
  if (planRow && planLabel && planPrice) {
    var hasPlan = plan.monthly > 0 && activeCart.length > 0;
    planRow.hidden = !hasPlan;

    if (hasPlan) {
      planLabel.textContent = plan.name;
      planPrice.textContent = fmtMonthly(plan.monthly);
    }
  }

  var totalNote = document.getElementById('cart-total-note');
  if (totalNote) {
    var showNote = plan.monthly > 0 && activeCart.length > 0;
    totalNote.hidden = !showNote;

    if (showNote) {
      totalNote.innerHTML = 'Your total includes the first month of protection. Future monthly protection payments continue in Stripe. Please go to the <a href="register.php">registration page</a> to register your device.';
    }
  }
}

function addToCart(product) {
  var cart = getCart();
  var existing = cart.find(function(item) { return item.id === product.id; });

  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({
      id: product.id,
      name: product.name,
      brand: product.brand,
      price: product.price,
      emoji: product.emoji,
      img: product.img || '',
      qty: 1
    });
  }

  saveCart(cart);
  updateCartBadge();
  renderCartItems();
  showToast(product.name + ' added to cart.', 'View Cart', openCart);

  if (!sessionStorage.getItem('tm_cart_guided_opened')) {
    sessionStorage.setItem('tm_cart_guided_opened', '1');
    openCart();
  }
}

function adjustQty(id, delta) {
  var cart = getCart();
  cart = cart.map(function(item) {
    if (item.id === id) return Object.assign({}, item, { qty: item.qty + delta });
    return item;
  }).filter(function(item) {
    return item.qty > 0;
  });

  saveCart(cart);
  updateCartBadge();
  renderCartItems();
}

function removeItem(id) {
  var cart = getCart().filter(function(item) { return item.id !== id; });
  saveCart(cart);
  updateCartBadge();
  renderCartItems();
}

function updateCartBadge() {
  var cart = getCart();
  var count = cart.reduce(function(sum, item) { return sum + item.qty; }, 0);

  var badge = document.getElementById('cart-badge');
  if (badge) badge.textContent = count;

  var buttonLabel = document.getElementById('cart-button-label');
  if (buttonLabel) buttonLabel.textContent = 'View Cart (' + count + ')';

  var prompt = document.getElementById('cart-prompt');
  if (prompt) prompt.hidden = count === 0;

  var promptSummary = document.getElementById('cart-prompt-summary');
  if (promptSummary) updateCartPricing(cart);
}

function fmtPrice(price) {
  return '$' + parseFloat(price).toFixed(2);
}

function renderCartItems() {
  var container = document.getElementById('cart-items');
  var footer = document.getElementById('cart-footer');
  if (!container) return;

  var cart = getCart();

  if (cart.length === 0) {
    container.innerHTML =
      '<div class="cart-empty">' +
        '<div class="cart-empty-icon">Cart</div>' +
        '<div>Your cart is empty</div>' +
      '</div>';

    if (footer) footer.style.display = 'none';

    var emptyUpsell = document.getElementById('cart-upsell');
    if (emptyUpsell) emptyUpsell.style.display = 'none';
    return;
  }

  var html = '';
  cart.forEach(function(item) {
    var idJs = JSON.stringify(item.id);
    var mediaHtml = item.img
      ? '<img class="cart-item-thumb" src="' + escHtml(item.img) + '" alt="' + escHtml(item.name) + '">'
      : item.emoji;
    html +=
      '<div class="cart-item">' +
        '<div class="cart-item-emoji">' + mediaHtml + '</div>' +
        '<div class="cart-item-info">' +
          '<div class="cart-item-name">' + escHtml(item.name) + '</div>' +
          '<div class="cart-item-price">' + fmtPrice(item.price) + '</div>' +
          '<div class="cart-item-qty">' +
            '<button class="qty-btn" onclick="adjustQty(' + idJs + ', -1)">-</button>' +
            '<span class="qty-num">' + item.qty + '</span>' +
            '<button class="qty-btn" onclick="adjustQty(' + idJs + ', 1)">+</button>' +
          '</div>' +
        '</div>' +
        '<button class="cart-remove" onclick="removeItem(' + idJs + ')">x</button>' +
      '</div>';
  });
  container.innerHTML = html;

  if (footer) {
    footer.style.display = '';
    updateCartPricing(cart);
  }

  var upsell = document.getElementById('cart-upsell');
  if (upsell) upsell.style.display = '';
}

function escHtml(str) {
  var div = document.createElement('div');
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
}

function stripeCheckout() {
  var cart = getCart();
  if (cart.length === 0) return;

  var btn = document.getElementById('checkout-btn');
  if (btn) {
    btn.disabled = true;
    btn.textContent = 'Redirecting...';
  }

  var form = document.createElement('form');
  form.method = 'POST';
  form.action = '/checkout.php';
  form.style.display = 'none';

  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'cart';
  input.value = JSON.stringify(cart.map(function(item) {
    return { id: item.id, qty: item.qty };
  }));
  form.appendChild(input);

  var planInput = document.createElement('input');
  planInput.type = 'hidden';
  planInput.name = 'insurance_plan';
  var selectedPlan = document.querySelector('input[name="insurance_plan"]:checked');
  planInput.value = selectedPlan ? selectedPlan.value : 'none';
  form.appendChild(planInput);

  document.body.appendChild(form);
  form.submit();
}

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

var toastTimer = null;
function showToast(message, actionLabel, actionHandler) {
  var toast = document.getElementById('toast');
  if (!toast) return;

  toast.innerHTML = '';

  var text = document.createElement('span');
  text.className = 'toast-message';
  text.textContent = message;
  toast.appendChild(text);

  if (actionLabel && typeof actionHandler === 'function') {
    var action = document.createElement('button');
    action.type = 'button';
    action.className = 'toast-action';
    action.textContent = actionLabel;
    action.addEventListener('click', function() {
      actionHandler();
      toast.classList.remove('visible');
    });
    toast.appendChild(action);
  }

  toast.classList.add('visible');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(function() {
    toast.classList.remove('visible');
  }, 2800);
}

function filterBrand(brand) {
  if (typeof window.TM_ACTIVE_BRAND !== 'undefined') {
    window.TM_ACTIVE_BRAND = brand;
  }

  document.querySelectorAll('.brand-tab').forEach(function(btn) {
    btn.classList.toggle('active', btn.dataset.brand === brand);
  });

  if (typeof window.applyProductFilters === 'function') {
    window.applyProductFilters();
    return;
  }

  document.querySelectorAll('.product-card[data-brand]').forEach(function(card) {
    var show = brand === 'All' || card.dataset.brand === brand;
    card.style.display = show ? '' : 'none';
  });
}

function getInitialShopBrand() {
  var params = new URLSearchParams(window.location.search);
  var brand = params.get('brand');
  if (!brand) return 'All';

  var isValid = Array.from(document.querySelectorAll('.brand-tab')).some(function(btn) {
    return btn.dataset.brand === brand;
  });

  return isValid ? brand : 'All';
}

function initVideo() {
  var video = document.getElementById('hero-video');
  var playBtn = document.getElementById('video-play-btn');
  if (!video || !playBtn) return;

  function updateBtn() {
    playBtn.textContent = video.paused ? 'Play' : 'Pause';
    if (!video.paused) {
      playBtn.classList.add('hidden');
    }
  }

  var wrapper = video.closest('.video-wrapper');
  if (wrapper) {
    wrapper.addEventListener('mouseenter', function() {
      playBtn.classList.remove('hidden');
    });
    wrapper.addEventListener('mouseleave', function() {
      if (!video.paused) playBtn.classList.add('hidden');
    });
  }

  playBtn.addEventListener('click', function() {
    if (video.paused) video.play();
    else video.pause();
  });

  video.addEventListener('play', updateBtn);
  video.addEventListener('pause', updateBtn);
  video.addEventListener('ended', function() {
    playBtn.classList.remove('hidden');
    playBtn.textContent = 'Play';
  });
}

function toggleMobileNav() {
  var nav = document.getElementById('mobile-nav');
  if (nav) nav.classList.toggle('open');
}

function initRepairForm() {
  var form = document.getElementById('repair-form');
  if (!form) return;

  var bookBtn = document.getElementById('book-btn');
  if (bookBtn) {
    bookBtn.addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('book-form').scrollIntoView({ behavior: 'smooth' });
    });
  }
}

function trackPromoClick(eventName, label, href) {
  var payload = {
    event: eventName || 'tm_click',
    label: label || '',
    href: href || '',
    path: window.location.pathname,
    timestamp: new Date().toISOString()
  };

  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(payload);

  try {
    var key = 'tm_click_log';
    var existing = JSON.parse(localStorage.getItem(key) || '[]');
    existing.push(payload);
    localStorage.setItem(key, JSON.stringify(existing.slice(-100)));
  } catch (e) {}
}

function initTrackedLinks(selector) {
  document.querySelectorAll(selector || '[data-track]').forEach(function(link) {
    if (link.dataset.trackBound === '1') return;
    link.dataset.trackBound = '1';

    link.addEventListener('click', function() {
      trackPromoClick(
        link.dataset.track,
        link.dataset.trackLabel || link.textContent.trim(),
        link.href || ''
      );
    });
  });
}

document.addEventListener('DOMContentLoaded', function() {
  updateCartBadge();
  updateCartPricing();
  initVideo();
  initRepairForm();
  initTrackedLinks('[data-track]');

  var overlay = document.getElementById('cart-overlay');
  if (overlay) overlay.addEventListener('click', closeCart);

  document.querySelectorAll('input[name="insurance_plan"]').forEach(function(input) {
    input.addEventListener('change', function() {
      updateCartPricing();
    });
  });

  if (document.querySelector('.brand-tab')) {
    filterBrand(getInitialShopBrand());
  }
});
