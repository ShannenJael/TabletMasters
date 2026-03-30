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
  ['id'=>1,  'name'=>'iPad Air (5th Gen)',   'brand'=>'Apple',     'price'=>85,    'orig'=>100,  'emoji'=>'📱', 'badge'=>'Sale',        'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-air-select-wifi-blue-202203?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>2,  'name'=>'iPad Pro 12.9"',       'brand'=>'Apple',     'price'=>115,   'orig'=>null, 'emoji'=>'📱', 'badge'=>null,          'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-pro-13-select-wifi-spacegray-202210?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>3,  'name'=>'iPad Mini 6',          'brand'=>'Apple',     'price'=>10,    'orig'=>null, 'emoji'=>'📱', 'badge'=>'Best Seller', 'img'=>'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-mini-select-wifi-purple-202109?wid=470&hei=556&fmt=png-alpha'],
  ['id'=>4,  'name'=>'Galaxy Tab S8',        'brand'=>'Samsung',   'price'=>25,    'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/06252021/tab-s7-fe-mystic-silver-front.jpg'],
  ['id'=>5,  'name'=>'Galaxy Tab S8 Ultra',  'brand'=>'Samsung',   'price'=>75,    'orig'=>null, 'emoji'=>'📟', 'badge'=>'New',         'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/02012022/tab-s8-ultra-graphite-front.jpg'],
  ['id'=>6,  'name'=>'Galaxy Tab A8',        'brand'=>'Samsung',   'price'=>200,   'orig'=>null, 'emoji'=>'📟', 'badge'=>'Best Seller', 'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/12082021/tab-a8-gray-front.jpg'],
  ['id'=>7,  'name'=>'Galaxy Tab S7 FE',     'brand'=>'Samsung',   'price'=>30,    'orig'=>null, 'emoji'=>'📟', 'badge'=>null,          'img'=>'https://image-us.samsung.com/SamsungUS/home/mobile/galaxy-tab/all-galaxy-tabs/06252021/tab-s7-fe-mystic-navy-front.jpg'],
  ['id'=>8,  'name'=>'Surface Pro 9',        'brand'=>'Microsoft', 'price'=>150,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'img'=>'https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RE4OXfM?ver=7e6c'],
  ['id'=>9,  'name'=>'Surface Go 3',         'brand'=>'Microsoft', 'price'=>200,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'img'=>'https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RE4Fp5C?ver=35b6'],
  ['id'=>10, 'name'=>'Surface Pro 8',        'brand'=>'Microsoft', 'price'=>110,   'orig'=>null, 'emoji'=>'💻', 'badge'=>null,          'img'=>'https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RE4OXfL?ver=7dbc'],
  ['id'=>11, 'name'=>'Fire HD 10 Plus',      'brand'=>'Amazon',    'price'=>28.50, 'orig'=>30,   'emoji'=>'🔥', 'badge'=>'New Arrival', 'img'=>'https://m.media-amazon.com/images/I/51VZGxfmMRL._AC_SX679_.jpg'],
  ['id'=>12, 'name'=>'Fire Max 11',          'brand'=>'Amazon',    'price'=>45,    'orig'=>null, 'emoji'=>'🔥', 'badge'=>null,          'img'=>'https://m.media-amazon.com/images/I/61s5I6biLCL._AC_SX679_.jpg'],
];

function badgeClass($badge) {
  if ($badge === 'Sale') return 'badge-sale';
  if ($badge === 'New' || $badge === 'New Arrival') return 'badge-new';
  return 'badge-default';
}
?>

<div class="shop-section">
  <div class="shop-header">
    <div>
      <div class="section-label">// Browse the Collection</div>
      <div class="section-title">TABLETS SALES</div>
    </div>
    <div class="brand-tabs">
      <?php foreach (['All','Apple','Samsung','Microsoft','Amazon'] as $brand): ?>
      <button
        class="brand-tab <?= $brand === 'All' ? 'active' : '' ?>"
        data-brand="<?= htmlspecialchars($brand) ?>"
        onclick="filterBrand('<?= htmlspecialchars($brand) ?>')"
      ><?= htmlspecialchars($brand) ?></button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="product-grid">
    <?php foreach ($products as $p):
      $priceJs  = json_encode($p['price']);
      $nameJs   = json_encode($p['name']);
      $brandJs  = json_encode($p['brand']);
      $emojiJs  = json_encode($p['emoji']);
      $addCall  = "addToCart({id:{$p['id']},name:{$nameJs},brand:{$brandJs},price:{$priceJs},emoji:{$emojiJs}})";
    ?>
    <div class="product-card" data-brand="<?= htmlspecialchars($p['brand']) ?>">
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
      </div>
      <div class="product-info">
        <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="product-brand"><?= htmlspecialchars($p['brand']) ?></div>
        <div class="product-price-row">
          <div>
            <span class="product-price">$<?= number_format($p['price'], 2) ?></span>
            <?php if ($p['orig']): ?>
            <span class="product-price-orig">$<?= number_format($p['orig'], 2) ?></span>
            <?php endif; ?>
          </div>
          <button class="add-btn" onclick="<?= htmlspecialchars($addCall) ?>" title="Add to cart">+</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
