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
  ['id'=>1,  'name'=>'iPad Air (5th Gen)',   'brand'=>'Apple',     'price'=>85,    'orig'=>100,  'emoji'=>'📱', 'badge'=>'Sale',        'condition'=>'Grade A',  'stock'=>5,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-air-select-wifi-blue-202203?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>2,  'name'=>'iPad Pro 12.9"',       'brand'=>'Apple',     'price'=>115,   'orig'=>null, 'emoji'=>'📱', 'badge'=>null,          'condition'=>'Like New', 'stock'=>2,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-pro-13-select-wifi-spacegray-202210?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>3,  'name'=>'iPad Mini 6',          'brand'=>'Apple',     'price'=>10,    'orig'=>null, 'emoji'=>'📱', 'badge'=>'Best Seller', 'condition'=>'Grade B',  'stock'=>8,  'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-mini-select-wifi-purple-202109?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>4,  'name'=>'Galaxy Tab S8',        'brand'=>'Samsung',   'price'=>25,    'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>4,  'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/06252021/tab-s7-fe-mystic-silver-front.jpg'],
  ['id'=>5,  'name'=>'Galaxy Tab S8 Ultra',  'brand'=>'Samsung',   'price'=>75,    'orig'=>null, 'emoji'=>'📟', 'badge'=>'New',         'condition'=>'Like New', 'stock'=>3,  'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/02012022/tab-s8-ultra-graphite-front.jpg'],
  ['id'=>6,  'name'=>'Galaxy Tab A8',        'brand'=>'Samsung',   'price'=>200,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Best Seller', 'condition'=>'Grade B',  'stock'=>11, 'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/12082021/tab-a8-gray-front.jpg'],
  ['id'=>7,  'name'=>'Galaxy Tab S7 FE',     'brand'=>'Samsung',   'price'=>30,    'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'condition'=>'Grade B',  'stock'=>6,  'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/06252021/tab-s7-fe-mystic-navy-front.jpg'],
  ['id'=>8,  'name'=>'Surface Pro 9',        'brand'=>'Microsoft', 'price'=>150,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Like New', 'stock'=>2,  'img'=>'https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RE4OXfM?ver=7e6c'],
  ['id'=>9,  'name'=>'Surface Go 3',         'brand'=>'Microsoft', 'price'=>200,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>7,  'img'=>'https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RE4Fp5C?ver=35b6'],
  ['id'=>10, 'name'=>'Surface Pro 8',        'brand'=>'Microsoft', 'price'=>110,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'condition'=>'Grade A',  'stock'=>4,  'img'=>'https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RE4OXfL?ver=7dbc'],
  ['id'=>11, 'name'=>'Fire HD 10 Plus',      'brand'=>'Amazon',    'price'=>28.50, 'orig'=>30,   'emoji'=>'🔥', 'badge'=>'New Arrival', 'condition'=>'Grade A',  'stock'=>9,  'img'=>'https://m.media-amazon.com/images/I/51VZGxfmMRL._AC_SX679_.jpg'],
  ['id'=>12, 'name'=>'Fire Max 11',          'brand'=>'Amazon',    'price'=>45,    'orig'=>null, 'emoji'=>'🔥', 'badge'=>null,          'condition'=>'Like New', 'stock'=>3,  'img'=>'https://m.media-amazon.com/images/I/61s5I6biLCL._AC_SX679_.jpg'],
];

function badgeClass($badge) {
  if ($badge === 'Sale') return 'badge-sale';
  if ($badge === 'New' || $badge === 'New Arrival') return 'badge-new';
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
    <div class="product-card" data-brand="<?= htmlspecialchars($p['brand']) ?>" data-price="<?= $p['price'] ?>" data-id="<?= $p['id'] ?>">
      <div class="product-img">
        <?php
          $imgSrc = '';
          if ($p['name'] === 'Fire HD 11') {
            $imgSrc = 'assets/images/Fire HD 11.jpg';
          } elseif (!empty($p['img'])) {
            $imgSrc = $p['img'];
          }
        ?>
        <?php if (!empty($imgSrc)): ?>
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
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
  ];
}
?>
<script>
var TM_PRODUCTS = <?= json_encode($jsProducts) ?>;

function _condClass(c) {
  if (c === 'Like New') return 'condition-like-new';
  if (c === 'Grade A')  return 'condition-grade-a';
  return 'condition-grade-b';
}

function openQuickView(id) {
  var p = TM_PRODUCTS.find(function(x){ return x.id === id; });
  if (!p) return;

  var imgHtml = p.img
    ? '<img src="' + escHtml(p.img) + '" alt="' + escHtml(p.name) + '" onerror="this.style.display=\'none\'">'
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
    return parseInt(a.dataset.id) - parseInt(b.dataset.id);
  });
  cards.forEach(function(c){ grid.appendChild(c); });
}

document.addEventListener('keydown', function(e){
  if (e.key === 'Escape') closeQuickView();
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
