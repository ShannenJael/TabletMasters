<?php $currentPage = 'shop'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop Tablets — Tablet Masters</title>
  <meta name="description" content="Browse Apple iPad, Samsung Galaxy, Microsoft Surface, and Amazon Fire tablets at Tablet Masters." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<?php
$products = [

  // ── CURRENT GEN (2022–2024) ──────────────────────────────────────
  ['id'=>13, 'name'=>'iPad Pro M4 11"',      'brand'=>'Apple',     'price'=>750,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>3,  'img'=>'assets/images/iPad Pro M4 11.png'],
  ['id'=>14, 'name'=>'iPad Pro M4 13"',      'brand'=>'Apple',     'price'=>950,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>2,  'img'=>'assets/images/iPad Pro M4 13.png'],
  ['id'=>15, 'name'=>'iPad Air M2 11"',      'brand'=>'Apple',     'price'=>420,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>4,  'img'=>'assets/images/iPad Air M2 11.png'],
  ['id'=>16, 'name'=>'iPad Air M2 13"',      'brand'=>'Apple',     'price'=>510,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>3,  'img'=>'assets/images/iPad Air M2 13.png'],
  ['id'=>17, 'name'=>'iPad Mini 7',          'brand'=>'Apple',     'price'=>380,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>5,  'img'=>'assets/images/iPad Mini 7.png'],
  ['id'=>18, 'name'=>'iPad 10th Gen',        'brand'=>'Apple',     'price'=>220,   'orig'=>null, 'emoji'=>'📱', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>7,  'img'=>'assets/images/iPad 10th Gen.png'],
  ['id'=>1,  'name'=>'iPad Air (5th Gen)',   'brand'=>'Apple',     'price'=>179,   'orig'=>200,  'emoji'=>'📱', 'badge'=>'Sale',        'condition'=>'Grade A',  'stock'=>5,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-air-select-wifi-blue-202203?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>2,  'name'=>'iPad Pro 12.9"',       'brand'=>'Apple',     'price'=>450,   'orig'=>null, 'emoji'=>'📱', 'badge'=>null,          'condition'=>'Like New', 'stock'=>2,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-pro-13-select-wifi-spacegray-202210?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>3,  'name'=>'iPad Mini 6',          'brand'=>'Apple',     'price'=>160,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Best Seller', 'condition'=>'Grade B',  'stock'=>8,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-mini-select-wifi-purple-202109?wid=470&hei=556&fmt=png-alpha'],

  ['id'=>19, 'name'=>'Galaxy Tab S9 Ultra',  'brand'=>'Samsung',   'price'=>650,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>2,  'img'=>'assets/images/Galaxy Tab S9 Ultra.png'],
  ['id'=>20, 'name'=>'Galaxy Tab S9+',       'brand'=>'Samsung',   'price'=>490,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>3,  'img'=>'assets/images/Galaxy Tab S9+.png'],
  ['id'=>21, 'name'=>'Galaxy Tab S9',        'brand'=>'Samsung',   'price'=>370,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>4,  'img'=>'assets/images/Galaxy Tab S9.png'],
  ['id'=>22, 'name'=>'Galaxy Tab S9 FE',     'brand'=>'Samsung',   'price'=>220,   'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>8,  'img'=>'assets/images/Galaxy Tab S9 FE.png'],
  ['id'=>4,  'name'=>'Galaxy Tab S8',        'brand'=>'Samsung',   'price'=>225,   'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>4,  'img'=>'assets/images/Galaxy Tab S8.png'],
  ['id'=>5,  'name'=>'Galaxy Tab S8 Ultra',  'brand'=>'Samsung',   'price'=>520,   'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'condition'=>'Like New', 'stock'=>3,  'img'=>'assets/images/Galaxy Tab S8 Ultra.png'],
  ['id'=>6,  'name'=>'Galaxy Tab A8',        'brand'=>'Samsung',   'price'=>85,    'orig'=>null, 'emoji'=>'📟', 'badge'=>'Best Seller', 'condition'=>'Grade B',  'stock'=>11, 'img'=>'assets/images/Galaxy Tab A8.png'],
  ['id'=>7,  'name'=>'Galaxy Tab S7 FE',     'brand'=>'Samsung',   'price'=>130,   'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'condition'=>'Grade B',  'stock'=>6,  'img'=>'assets/images/Galaxy Tab S7 FE.png'],

  ['id'=>23, 'name'=>'Surface Pro 11',       'brand'=>'Microsoft', 'price'=>760,   'orig'=>null, 'emoji'=>'💻', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>2,  'img'=>'assets/images/Surface Pro 11.png'],
  ['id'=>24, 'name'=>'Surface Go 4',         'brand'=>'Microsoft', 'price'=>340,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>5,  'img'=>'assets/images/Surface Go 4.png'],
  ['id'=>8,  'name'=>'Surface Pro 9',        'brand'=>'Microsoft', 'price'=>520,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Like New', 'stock'=>2,  'img'=>'assets/images/Surface Pro 9.png', 'imgClass'=>'product-shot-centered'],
  ['id'=>9,  'name'=>'Surface Go 3',         'brand'=>'Microsoft', 'price'=>170,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>7,  'img'=>'assets/images/Surface Go 3.png', 'imgClass'=>'product-shot-centered'],
  ['id'=>10, 'name'=>'Surface Pro 8',        'brand'=>'Microsoft', 'price'=>370,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>4,  'img'=>'assets/images/Surface Pro 8.png', 'imgClass'=>'product-shot-centered'],

  ['id'=>25, 'name'=>'Fire HD 10 (2023)',    'brand'=>'Amazon',    'price'=>80,    'orig'=>null, 'emoji'=>'🔥', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>6,  'img'=>'assets/images/Fire HD 10 (2025) 1.png', 'imgClass'=>'product-shot-centered'],
  ['id'=>11, 'name'=>'Fire HD 10 Plus',      'brand'=>'Amazon',    'price'=>90,    'orig'=>100,  'emoji'=>'🔥', 'badge'=>'Sale',        'condition'=>'Grade A',  'stock'=>9,  'img'=>'assets/images/Fire HD 10 (2025).png', 'imgClass'=>'product-shot-centered'],
  ['id'=>12, 'name'=>'Fire Max 11',          'brand'=>'Amazon',    'price'=>150,   'orig'=>null, 'emoji'=>'🔥', 'badge'=>null,          'condition'=>'Like New', 'stock'=>3,  'img'=>'assets/images/Fire Max 11.png',       'imgClass'=>'product-shot-centered'],

  // ── PREVIOUS GEN ────────────────────────────────────────────────
  ['id'=>26, 'name'=>'iPad Air 4th Gen',     'brand'=>'Apple',     'price'=>160,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>6,  'img'=>'assets/images/iPad Air 4th Gen.png'],
  ['id'=>27, 'name'=>'iPad 9th Gen',         'brand'=>'Apple',     'price'=>120,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>9,  'img'=>'assets/images/iPad 9th Gen.png'],
  ['id'=>28, 'name'=>'iPad Mini 5',          'brand'=>'Apple',     'price'=>110,   'orig'=>null, 'emoji'=>'📱', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>4,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-mini-select-wifi-silver-201903?wid=470&hei=556&fmt=png-alpha'],

  ['id'=>29, 'name'=>'Galaxy Tab S7',        'brand'=>'Samsung',   'price'=>150,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>7,  'img'=>'assets/images/Galaxy Tab S7.png'],
  ['id'=>30, 'name'=>'Galaxy Tab S7+',       'brand'=>'Samsung',   'price'=>200,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>5,  'img'=>'assets/images/Galaxy Tab S7+.png'],
  ['id'=>31, 'name'=>'Galaxy Tab S6 Lite',   'brand'=>'Samsung',   'price'=>100,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>10, 'img'=>'assets/images/Galaxy Tab S6 Lite.png'],

  ['id'=>32, 'name'=>'Surface Pro 7',        'brand'=>'Microsoft', 'price'=>200,   'orig'=>null, 'emoji'=>'💻', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>6,  'img'=>'assets/images/Surface Pro 7.png', 'imgClass'=>'product-shot-centered'],
  ['id'=>33, 'name'=>'Surface Pro X',        'brand'=>'Microsoft', 'price'=>180,   'orig'=>null, 'emoji'=>'💻', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>3,  'img'=>'assets/images/Surface Pro 10 3-Photoroom.png'],

  ['id'=>34, 'name'=>'Fire HD 8 (2022)',     'brand'=>'Amazon',    'price'=>45,    'orig'=>null, 'emoji'=>'🔥', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>12, 'img'=>'assets/images/Fire HD 8.png', 'imgClass'=>'product-shot-centered'],
  ['id'=>35, 'name'=>'Fire HD 7',            'brand'=>'Amazon',    'price'=>30,    'orig'=>null, 'emoji'=>'🔥', 'badge'=>'Previous Gen','condition'=>'Grade B',  'stock'=>8,  'img'=>'assets/images/Fire 7 (2024).png',   'imgClass'=>'product-shot-centered'],
  ['id'=>36, 'name'=>'Galaxy Tab S10',       'brand'=>'Samsung',   'price'=>449,   'orig'=>null, 'emoji'=>'ðŸ“Ÿ', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>3,  'img'=>'assets/images/placeholder-galaxy-tab-s10.svg'],
  ['id'=>37, 'name'=>'Galaxy Tab S10+',      'brand'=>'Samsung',   'price'=>589,   'orig'=>null, 'emoji'=>'ðŸ“Ÿ', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>2,  'img'=>'assets/images/placeholder-galaxy-tab-s10-plus.svg'],
  ['id'=>38, 'name'=>'Galaxy Tab S10 Ultra', 'brand'=>'Samsung',   'price'=>799,   'orig'=>null, 'emoji'=>'ðŸ“Ÿ', 'badge'=>'Latest',      'condition'=>'Like New', 'stock'=>2,  'img'=>'assets/images/placeholder-galaxy-tab-s10-ultra.svg'],
  ['id'=>39, 'name'=>'Galaxy Tab S10 FE',    'brand'=>'Samsung',   'price'=>299,   'orig'=>null, 'emoji'=>'ðŸ“Ÿ', 'badge'=>'Latest',      'condition'=>'Grade A',  'stock'=>4,  'img'=>'assets/images/placeholder-galaxy-tab-s10-fe.svg'],
];

$samsungS10Order = [
  'Galaxy Tab S10' => 0,
  'Galaxy Tab S10+' => 1,
  'Galaxy Tab S10 Ultra' => 2,
  'Galaxy Tab S10 FE' => 3,
];
$insertAfter = 9;

foreach ($products as $index => &$product) {
  $sortOrder = $index;

  if (isset($samsungS10Order[$product['name']])) {
    $sortOrder = $insertAfter + $samsungS10Order[$product['name']];
  } elseif ($index >= $insertAfter) {
    $sortOrder = $index + count($samsungS10Order);
  }

  $product['sortOrder'] = $sortOrder;
}
unset($product);

usort($products, function($a, $b) {
  return $a['sortOrder'] <=> $b['sortOrder'];
});

function badgeClass($badge) {
  if ($badge === 'Sale') return 'badge-sale';
  if ($badge === 'New' || $badge === 'New Arrival' || $badge === 'Latest') return 'badge-new';
  if ($badge === 'Previous Gen') return 'badge-prev';
  return 'badge-default';
}

function conditionClass($condition) {
  if ($condition === 'Like New') return 'condition-like-new';
  if ($condition === 'Grade A')  return 'condition-grade-a';
  return 'condition-grade-b';
}
?>

<div class="shop-section">
  <div class="shop-header">
    <div>
      <div class="section-label">// Browse the Collection</div>
      <div class="section-title">TABLETS SALES</div>
    </div>
    <div class="shop-controls">
      <label class="shop-search" for="shop-search">
        <i class="fas fa-search" aria-hidden="true"></i>
        <input
          id="shop-search"
          type="search"
          placeholder="Search tablets, brands, condition"
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
    </div>
  </div>

  <div class="product-grid" id="product-grid">
    <?php foreach ($products as $p):
      $priceJs    = json_encode($p['price']);
      $nameJs     = json_encode($p['name']);
      $brandJs    = json_encode($p['brand']);
      $emojiJs    = json_encode($p['emoji']);
      $addCall    = "addToCart({id:{$p['id']},name:{$nameJs},brand:{$brandJs},price:{$priceJs},emoji:{$emojiJs}})";
      $stockClass = $p['stock'] <= 3 ? 'stock-low' : 'stock-ok';
      $stockText  = $p['stock'] <= 3
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
          <i class="fas fa-shield-alt"></i> Lifetime Insurance Included
        </div>
        <div class="stock-tag <?= $stockClass ?>"><?= $stockText ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="shop-empty-state" id="shop-empty-state" hidden>No products match your search.</div>
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
  $jsProducts[] = [
    'id'        => $p['id'],
    'name'      => $p['name'],
    'brand'     => $p['brand'],
    'price'     => $p['price'],
    'orig'      => $p['orig'],
    'emoji'     => $p['emoji'],
    'badge'     => $p['badge'],
    'condition' => $p['condition'],
    'stock'     => $p['stock'],
    'img'       => $p['img'],
    'imgClass'  => isset($p['imgClass']) ? $p['imgClass'] : '',
    'sortOrder' => $p['sortOrder'],
  ];
}
?>
<script>
var TM_PRODUCTS = <?= json_encode($jsProducts) ?>;
var TM_ACTIVE_BRAND = 'All';
var TM_SEARCH_QUERY = '';

function _condClass(c) {
  if (c === 'Like New') return 'condition-like-new';
  if (c === 'Grade A')  return 'condition-grade-a';
  return 'condition-grade-b';
}

function openQuickView(id) {
  var p = TM_PRODUCTS.find(function(x){ return x.id === id; });
  if (!p) return;

  var imgClass = p.imgClass ? ' class="' + escHtml(p.imgClass) + '"' : '';
  var imgHtml = p.img
    ? '<img' + imgClass + ' src="' + escHtml(p.img) + '" alt="' + escHtml(p.name) + '" onerror="this.style.display=\'none\'">'
    : '<span class="modal-emoji">' + p.emoji + '</span>';

  var origHtml   = p.orig ? '<span class="modal-price-orig">$' + parseFloat(p.orig).toFixed(2) + '</span>' : '';
  var stockClass = p.stock <= 3 ? 'stock-low' : 'stock-ok';
  var stockText  = p.stock <= 3 ? 'Only ' + p.stock + ' left!' : 'In Stock (' + p.stock + ')';
  var addPayload = 'addToCart({id:' + p.id + ',name:' + JSON.stringify(p.name) + ',brand:' + JSON.stringify(p.brand) + ',price:' + p.price + ',emoji:' + JSON.stringify(p.emoji) + '})';

  document.getElementById('qv-img-side').innerHTML = imgHtml;
  document.getElementById('qv-info-side').innerHTML =
    '<div class="modal-brand">' + escHtml(p.brand) + '</div>' +
    '<div class="modal-name">'  + escHtml(p.name)  + '</div>' +
    '<span class="condition-badge ' + _condClass(p.condition) + '">' + escHtml(p.condition) + '</span>' +
    '<hr class="modal-divider">' +
    '<div class="modal-price-row">' +
      '<span class="modal-price">$' + parseFloat(p.price).toFixed(2) + '</span>' + origHtml +
    '</div>' +
    '<div class="stock-tag ' + stockClass + '" style="margin-top:4px">' + stockText + '</div>' +
    '<div class="modal-warranty"><i class="fas fa-shield-alt"></i>&nbsp; Lifetime Insurance — Free Replacement Included</div>' +
    '<button class="modal-add-btn" onclick="' + escHtml(addPayload) + '; closeQuickView();">Add to Cart</button>';

  document.getElementById('qv-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeQuickView() {
  document.getElementById('qv-overlay').classList.remove('open');
  document.body.style.overflow = '';
}

function sortProducts(val) {
  var grid  = document.getElementById('product-grid');
  var cards = Array.from(grid.querySelectorAll('.product-card'));
  cards.sort(function(a, b) {
    if (val === 'price-asc')  return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
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

  cards.forEach(function(card){
    var matchesBrand = TM_ACTIVE_BRAND === 'All' || card.dataset.brand === TM_ACTIVE_BRAND;
    var haystack = [
      card.dataset.name,
      card.dataset.brand,
      card.dataset.condition,
      card.dataset.badge
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

document.addEventListener('keydown', function(e){
  if (e.key === 'Escape') closeQuickView();
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
