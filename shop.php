<?php
$currentPage = 'shop';
require_once __DIR__ . '/includes/accessories-data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop Tablets - Tablet Masters</title>
  <meta name="description" content="Browse Apple iPad, Samsung Galaxy, Microsoft Surface, and Amazon Fire tablets at Tablet Masters." />
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
// Load inventory from JSON — edit via admin/inventory.php
$products = tm_inventory_tablets();

foreach ($products as $index => &$product) {
  $product['sortOrder'] = $index;
  if (empty($product['imgClass'])) $product['imgClass'] = '';
  if (!isset($product['orig']))    $product['orig'] = null;
  if (empty($product['badge']))    $product['badge'] = null;
  if (empty($product['connectivity'])) {
    $product['connectivity'] = stripos($product['name'], 'cellular') !== false ? 'Wi-Fi + Cellular' : 'Wi-Fi';
  }
}
unset($product);

$accessoryCatalog = tm_build_accessory_catalog($products);
$accessoryByTablet = [];
foreach ($accessoryCatalog as $entry) {
  $accessoryByTablet[$entry['tablet_name']] = $entry;
}

function badgeClass($badge) {
  if ($badge === 'Sale') return 'badge-sale';
  if ($badge === 'New' || $badge === 'New Arrival' || $badge === 'Latest') return 'badge-new';
  if ($badge === 'Previous Gen') return 'badge-prev';
  return 'badge-default';
}

function conditionClass($condition) {
  if ($condition === 'Like New') return 'condition-like-new';
  if ($condition === 'Grade A') return 'condition-grade-a';
  return 'condition-grade-b';
}
?>

<div class="shop-section">
  <div class="shop-header">
    <div>
      <div class="section-label">// Browse the Collection</div>
      <div class="section-title">TABLETS SALES</div>
      <p class="shop-subcopy">
        Need protection too? Browse matching <a href="accessories.php">cases and screen covers</a> for every tablet family.
      </p>
    </div>
    <div class="shop-controls">
      <label class="shop-search" for="shop-search">
        <i class="fas fa-search" aria-hidden="true"></i>
        <input
          id="shop-search"
          type="search"
          placeholder="Search tablets, brands, condition, connectivity"
          aria-label="Search tablets"
          oninput="setSearchQuery(this.value)"
        />
      </label>
      <div class="brand-tabs">
        <?php foreach (['All','Apple','Samsung','Microsoft','Amazon'] as $brand): ?>
        <button
          class="brand-tab <?= $brand === 'All' ? 'active' : '' ?>"
          data-brand="<?= htmlspecialchars($brand) ?>"
          onclick="filterBrand('<?= htmlspecialchars($brand) ?>')"
        ><?= htmlspecialchars($brand) ?></button>
        <?php endforeach; ?>
      </div>
      <select class="sort-select" onchange="sortProducts(this.value)" aria-label="Sort products">
        <option value="default">Sort: Newest</option>
        <option value="price-asc">Price: Low &rarr; High</option>
        <option value="price-desc">Price: High &rarr; Low</option>
      </select>
      <a class="btn-outline shop-accessories-link" href="accessories.php">Accessories</a>
    </div>
  </div>

  <div class="product-grid" id="product-grid">
    <?php foreach ($products as $p):
      $priceJs = json_encode($p['price']);
      $nameJs = json_encode($p['name']);
      $brandJs = json_encode($p['brand']);
      $emojiJs = json_encode($p['emoji']);
      $addCall = "addToCart({id:{$p['id']},name:{$nameJs},brand:{$brandJs},price:{$priceJs},emoji:{$emojiJs}})";
      $stockClass = $p['stock'] <= 3 ? 'stock-low' : 'stock-ok';
      $stockText = $p['stock'] <= 3
        ? 'Only ' . $p['stock'] . ' left!'
        : 'In Stock (' . $p['stock'] . ')';
    ?>
    <div
      class="product-card"
      data-brand="<?= htmlspecialchars($p['brand']) ?>"
      data-price="<?= $p['price'] ?>"
      data-id="<?= $p['id'] ?>"
      data-sort-order="<?= $p['sortOrder'] ?>"
      data-name="<?= htmlspecialchars($p['name']) ?>"
      data-condition="<?= htmlspecialchars($p['condition']) ?>"
      data-badge="<?= htmlspecialchars((string)($p['badge'] ?? '')) ?>"
      data-connectivity="<?= htmlspecialchars($p['connectivity']) ?>"
    >
      <div class="product-img">
        <?php
          $imgSrc = '';
          if ($p['name'] === 'Fire HD 11') {
            $imgSrc = 'assets/images/Fire HD 11.jpg';
          } elseif (!empty($p['img'])) {
            $imgSrc = $p['img'];
          }
        ?>
        <?php $imgClass = !empty($p['imgClass']) ? ' ' . $p['imgClass'] : ''; ?>
        <?php if (!empty($imgSrc)): ?>
        <img class="<?= htmlspecialchars(trim($imgClass)) ?>" src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <span style="display:none"><?= $p['emoji'] ?></span>
        <?php else: ?>
        <span><?= $p['emoji'] ?></span>
        <?php endif; ?>
        <div class="product-img-overlay"></div>
        <?php if ($p['badge']): ?>
        <span class="product-badge <?= badgeClass($p['badge']) ?>"><?= htmlspecialchars($p['badge']) ?></span>
        <?php endif; ?>
        <button class="quick-view-btn" onclick="event.stopPropagation(); openQuickView(<?= $p['id'] ?>)">Quick View</button>
      </div>
      <div class="product-info">
        <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="product-meta-row">
          <span class="product-brand"><?= htmlspecialchars($p['brand']) ?></span>
          <span class="condition-badge <?= conditionClass($p['condition']) ?>"><?= htmlspecialchars($p['condition']) ?></span>
        </div>
        <div class="product-connectivity"><?= htmlspecialchars($p['connectivity']) ?></div>
        <div class="product-price-row">
          <div>
            <span class="product-price">$<?= number_format($p['price'], 2) ?></span>
            <?php if ($p['orig']): ?>
            <span class="product-price-orig">$<?= number_format($p['orig'], 2) ?></span>
            <?php endif; ?>
          </div>
          <button class="add-btn" onclick="<?= htmlspecialchars($addCall) ?>" title="Add to cart">+</button>
        </div>
        <div class="warranty-badge">
          <i class="fas fa-shield-alt"></i> 4 year coverage available
        </div>
        <div class="stock-tag <?= $stockClass ?>"><?= $stockText ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="shop-empty-state" id="shop-empty-state" hidden>No products match your search.</div>
</div>

<div class="cart-prompt" id="cart-prompt" hidden>
  <div class="cart-prompt-copy">
    <div class="cart-prompt-label">Your Cart Is Ready</div>
    <div class="cart-prompt-summary" id="cart-prompt-summary">0 items in cart</div>
  </div>
  <button class="btn-primary cart-prompt-btn" type="button" onclick="openCart()">View Cart</button>
</div>

<!-- Quick View Modal -->
<div class="modal-overlay" id="qv-overlay" onclick="if(event.target===this)closeQuickView()">
  <div class="quick-view-modal" id="qv-modal">
    <button class="modal-close" onclick="closeQuickView()">&#10005;</button>
    <div class="modal-img-side" id="qv-img-side"></div>
    <div class="modal-info-side" id="qv-info-side"></div>
  </div>
</div>

<?php
$jsProducts = [];
foreach ($products as $p) {
  $baseName = tm_accessory_base_name($p['name']);
  $matchingAccessory = $accessoryByTablet[$baseName] ?? null;
  $jsProducts[] = [
    'id'        => $p['id'],
    'name'      => $p['name'],
    'brand'     => $p['brand'],
    'price'     => $p['price'],
    'orig'      => $p['orig'],
    'emoji'     => $p['emoji'],
    'badge'     => $p['badge'],
    'condition' => $p['condition'],
    'connectivity' => $p['connectivity'],
    'stock'     => $p['stock'],
    'img'       => $p['img'],
    'imgClass'  => isset($p['imgClass']) ? $p['imgClass'] : '',
    'sortOrder' => $p['sortOrder'],
    'accessory' => $matchingAccessory ? [
      'tabletName' => $matchingAccessory['tablet_name'],
      'case' => [
        'id' => $matchingAccessory['case_id'],
        'name' => $matchingAccessory['case_name'],
        'price' => $matchingAccessory['case_price'],
        'img' => $matchingAccessory['case_image'],
      ],
      'screen' => [
        'id' => $matchingAccessory['screen_id'],
        'name' => $matchingAccessory['screen_name'],
        'price' => $matchingAccessory['screen_price'],
        'img' => $matchingAccessory['screen_image'],
      ],
    ] : null,
  ];
}
?>
<script>
var TM_PRODUCTS = <?= json_encode($jsProducts) ?>;
var TM_ACTIVE_BRAND = 'All';
var TM_SEARCH_QUERY = '';

function _condClass(c) {
  if (c === 'Like New') return 'condition-like-new';
  if (c === 'Grade A') return 'condition-grade-a';
  return 'condition-grade-b';
}

function openQuickView(id) {
  var p = TM_PRODUCTS.find(function(x){ return x.id === id; });
  if (!p) return;

  var imgClass = p.imgClass ? ' class="' + escHtml(p.imgClass) + '"' : '';
  var imgHtml = p.img
    ? '<img' + imgClass + ' src="' + escHtml(p.img) + '" alt="' + escHtml(p.name) + '" onerror="this.style.display=\'none\'">'
    : '<span class="modal-emoji">' + p.emoji + '</span>';

  var origHtml = p.orig ? '<span class="modal-price-orig">$' + parseFloat(p.orig).toFixed(2) + '</span>' : '';
  var stockClass = p.stock <= 3 ? 'stock-low' : 'stock-ok';
  var stockText = p.stock <= 3 ? 'Only ' + p.stock + ' left!' : 'In Stock (' + p.stock + ')';
  var accessoryHtml = '';
  if (p.accessory && p.accessory.case) {
    accessoryHtml =
      '<div class="modal-accessory-card">' +
        '<div class="modal-accessory-copy">' +
          '<div class="modal-accessory-label">Match It With</div>' +
          '<div class="modal-accessory-name">' + escHtml(p.accessory.case.name) + '</div>' +
          '<div class="modal-accessory-price">$' + parseFloat(p.accessory.case.price).toFixed(2) + '</div>' +
        '</div>' +
        '<div class="modal-accessory-actions">' +
          '<button class="modal-accessory-btn" id="qv-case-btn" type="button">Add Matching Case</button>' +
          '<button class="modal-accessory-btn modal-accessory-btn-primary" id="qv-bundle-btn" type="button">Add Tablet + Case</button>' +
        '</div>' +
      '</div>';
  }
  document.getElementById('qv-img-side').innerHTML = imgHtml;

  var infoEl = document.getElementById('qv-info-side');
  infoEl.innerHTML =
    '<div class="modal-brand">' + escHtml(p.brand) + '</div>' +
    '<div class="modal-name">' + escHtml(p.name) + '</div>' +
    '<div class="modal-chip-row">' +
      '<span class="condition-badge ' + _condClass(p.condition) + '">' + escHtml(p.condition) + '</span>' +
      '<span class="condition-badge">' + escHtml(p.connectivity) + '</span>' +
    '</div>' +
    '<hr class="modal-divider">' +
    '<div class="modal-price-row">' +
      '<span class="modal-price">$' + parseFloat(p.price).toFixed(2) + '</span>' + origHtml +
    '</div>' +
    '<div class="stock-tag ' + stockClass + '" style="margin-top:4px">' + stockText + '</div>' +
    '<div class="modal-warranty"><i class="fas fa-shield-alt"></i>&nbsp; 4 year coverage available</div>' +
    accessoryHtml +
    '<button class="modal-add-btn" id="qv-add-btn">Add to Cart</button>';

  document.getElementById('qv-add-btn').addEventListener('click', function() {
    addToCart({ id: p.id, name: p.name, brand: p.brand, price: p.price, emoji: p.emoji });
    closeQuickView();
  });

  var caseBtn = document.getElementById('qv-case-btn');
  if (caseBtn && p.accessory && p.accessory.case) {
    caseBtn.addEventListener('click', function() {
      addToCart({
        id: p.accessory.case.id,
        name: p.accessory.case.name,
        brand: p.brand,
        price: p.accessory.case.price,
        emoji: 'Case',
        img: p.accessory.case.img
      });
    });
  }

  var bundleBtn = document.getElementById('qv-bundle-btn');
  if (bundleBtn && p.accessory && p.accessory.case) {
    bundleBtn.addEventListener('click', function() {
      addToCart({ id: p.id, name: p.name, brand: p.brand, price: p.price, emoji: p.emoji });
      addToCart({
        id: p.accessory.case.id,
        name: p.accessory.case.name,
        brand: p.brand,
        price: p.accessory.case.price,
        emoji: 'Case',
        img: p.accessory.case.img
      });
      closeQuickView();
    });
  }

  document.getElementById('qv-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeQuickView() {
  document.getElementById('qv-overlay').classList.remove('open');
  document.body.style.overflow = '';
}

function sortProducts(val) {
  var grid = document.getElementById('product-grid');
  var cards = Array.from(grid.querySelectorAll('.product-card'));
  cards.sort(function(a, b) {
    if (val === 'price-asc') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
    if (val === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
    return parseInt(a.dataset.sortOrder) - parseInt(b.dataset.sortOrder);
  });
  cards.forEach(function(c){ grid.appendChild(c); });
  applyProductFilters();
}

function setSearchQuery(query) {
  TM_SEARCH_QUERY = String(query || '').trim().toLowerCase();
  applyProductFilters();
}

function applyProductFilters() {
  var cards = document.querySelectorAll('.product-card[data-brand]');
  var empty = document.getElementById('shop-empty-state');
  var visibleCount = 0;

  cards.forEach(function(card) {
    var matchesBrand = TM_ACTIVE_BRAND === 'All' || card.dataset.brand === TM_ACTIVE_BRAND;
    var haystack = [
      card.dataset.name,
      card.dataset.brand,
      card.dataset.condition,
      card.dataset.badge,
      card.dataset.connectivity
    ].join(' ').toLowerCase();
    var matchesSearch = TM_SEARCH_QUERY === '' || haystack.indexOf(TM_SEARCH_QUERY) !== -1;
    var show = matchesBrand && matchesSearch;

    card.style.display = show ? '' : 'none';
    if (show) visibleCount += 1;
  });

  if (empty) {
    empty.hidden = visibleCount !== 0;
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeQuickView();
});
</script>

<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
