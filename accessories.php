<?php
$currentPage = 'accessories';
require_once __DIR__ . '/includes/accessories-data.php';

$catalog = tm_build_accessory_catalog();
$brandFilter = trim((string)($_GET['brand'] ?? 'All'));
$tabletFilter = trim((string)($_GET['tablet'] ?? ''));

$brands = ['All'];
foreach ($catalog as $entry) {
    if (!in_array($entry['brand'], $brands, true)) {
        $brands[] = $entry['brand'];
    }
}

$brandCounts = ['All' => count($catalog)];
foreach ($catalog as $entry) {
    $brandCounts[$entry['brand']] = ($brandCounts[$entry['brand']] ?? 0) + 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Accessories - Tablet Masters</title>
  <meta name="description" content="Browse protective cases and screen covers for every tablet family sold by Tablet Masters." />
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

<section class="accessories-page">
  <div class="accessories-hero">
    <div class="section-label">// Protection Add-Ons</div>
    <h1 class="accessories-title">ACCESSORIES FOR EVERY TABLET WE SELL.</h1>
    <p class="accessories-intro">
      Match the tablet first, then add protection in one step.
    </p>
    <div class="accessories-hero-actions">
      <a class="btn-primary" href="shop.php">Shop Tablets</a>
    </div>
  </div>

  <div class="accessories-toolbar">
    <div class="accessories-toolbar-copy">
      <div class="accessories-toolbar-label">Browse by brand</div>
      <div class="accessories-toolbar-meta" id="accessories-toolbar-meta"><?= count($catalog) ?> accessory matches</div>
    </div>
    <div class="brand-tabs accessories-brand-tabs">
      <?php foreach ($brands as $brand): ?>
      <button
        class="brand-tab <?= $brand === 'All' ? 'active' : '' ?>"
        data-brand="<?= htmlspecialchars($brand) ?>"
        onclick="filterAccessories('<?= htmlspecialchars($brand) ?>')"
      ><span><?= htmlspecialchars($brand) ?></span><span class="brand-tab-count"><?= (int)($brandCounts[$brand] ?? 0) ?></span></button>
      <?php endforeach; ?>
    </div>
    <label class="shop-search accessories-search" for="accessory-search">
      <i class="fas fa-search" aria-hidden="true"></i>
      <input
        id="accessory-search"
        type="search"
        placeholder="Search tablet name, brand, case, screen cover"
        aria-label="Search accessories"
        value="<?= htmlspecialchars($tabletFilter) ?>"
        oninput="setAccessorySearch(this.value)"
      />
    </label>
  </div>

  <div class="accessories-grid" id="accessories-grid">
    <?php foreach ($catalog as $entry): ?>
    <?php
      $compatibility = implode(' | ', $entry['compatibility']);
      $mailto = 'mailto:service@tablet-masters.com?subject=' . rawurlencode('Accessory request for ' . $entry['tablet_name']);
      $bundleStartingAt = $entry['case_price'] + $entry['screen_price'];
      $caseProduct = [
        'id' => tm_accessory_case_id($entry['key']),
        'name' => $entry['case_name'],
        'brand' => $entry['brand'],
        'price' => $entry['case_price'],
        'emoji' => 'Case',
        'img' => $entry['placeholder_image'],
      ];
      $screenProduct = [
        'id' => tm_accessory_screen_id($entry['key']),
        'name' => $entry['screen_name'],
        'brand' => $entry['brand'],
        'price' => $entry['screen_price'],
        'emoji' => 'Shield',
        'img' => $entry['placeholder_image'],
      ];
    ?>
    <article
      class="accessory-card"
      data-brand="<?= htmlspecialchars($entry['brand']) ?>"
      data-tablet="<?= htmlspecialchars($entry['tablet_name']) ?>"
      data-search="<?= htmlspecialchars(strtolower($entry['tablet_name'] . ' ' . $entry['brand'] . ' ' . $entry['case_name'] . ' ' . $entry['screen_name'])) ?>"
    >
      <div class="accessory-visual">
        <img src="<?= htmlspecialchars($entry['placeholder_image']) ?>" alt="<?= htmlspecialchars($entry['tablet_name']) ?> accessory bundle" loading="lazy" />
      </div>

      <div class="accessory-body">
        <div class="product-meta-row">
          <span class="product-brand"><?= htmlspecialchars($entry['brand']) ?></span>
          <span class="condition-badge condition-grade-a"><?= htmlspecialchars($entry['case_family']) ?></span>
        </div>
        <h2 class="accessory-card-title"><?= htmlspecialchars($entry['tablet_name']) ?></h2>
        <p class="accessory-compatibility"><?= htmlspecialchars($compatibility) ?></p>

        <div class="accessory-bundle-panel">
          <div class="accessory-bundle-top">
            <div class="accessory-bundle-copy">
              <span class="accessory-bundle-eyebrow">Bundle</span>
              <h3>Case + Screen Cover</h3>
            </div>
            <div class="accessory-bundle-price">From $<?= number_format($bundleStartingAt, 2) ?></div>
          </div>
          <button class="btn-primary accessory-buy-btn" type="button" onclick='addAccessoryBundleToCart(<?= json_encode($caseProduct) ?>, <?= json_encode($screenProduct) ?>)'>Add Full Bundle</button>
        </div>

        <div class="accessory-offer-grid">
          <button class="accessory-offer accessory-offer-action" type="button" onclick='addAccessoryToCart(<?= json_encode($caseProduct) ?>)'>
            <div class="accessory-offer-top">
              <div>
                <div class="accessory-offer-label">Case</div>
                <div class="accessory-offer-name">$<?= number_format($entry['case_price'], 2) ?></div>
              </div>
              <div class="accessory-offer-price">+</div>
            </div>
            <div class="accessory-offer-footer">Add case</div>
          </button>
          <button class="accessory-offer accessory-offer-action" type="button" onclick='addAccessoryToCart(<?= json_encode($screenProduct) ?>)'>
            <div class="accessory-offer-top">
              <div>
                <div class="accessory-offer-label">Screen Cover</div>
                <div class="accessory-offer-name">$<?= number_format($entry['screen_price'], 2) ?></div>
              </div>
              <div class="accessory-offer-price">+</div>
            </div>
            <div class="accessory-offer-footer">Add screen cover</div>
          </button>
        </div>

        <div class="accessory-chip-row">
          <span class="tablet-games-chip"><?= htmlspecialchars($entry['brand']) ?></span>
          <span class="tablet-games-chip">Protection</span>
        </div>

        <div class="accessory-actions">
          <button
            class="accessory-cart-icon"
            type="button"
            onclick='addAccessoryBundleToCart(<?= json_encode($caseProduct) ?>, <?= json_encode($screenProduct) ?>)'
            aria-label="Add bundle to cart"
            title="Add bundle to cart"
          >
            <i class="fas fa-cart-plus" aria-hidden="true"></i>
          </button>
          <a class="btn-outline" href="<?= htmlspecialchars($mailto) ?>">Ask About Fit</a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <div class="shop-empty-state" id="accessories-empty-state" hidden>No accessories match that search yet.</div>
</section>

<?php include 'includes/footer.php'; ?>
<script>
var TM_ACCESSORY_BRAND = <?= json_encode(in_array($brandFilter, $brands, true) ? $brandFilter : 'All') ?>;
var TM_ACCESSORY_SEARCH = <?= json_encode(strtolower($tabletFilter)) ?>;

function addAccessoryToCart(product) {
  addToCart(product);
  openCart();
}

function addAccessoryBundleToCart(caseProduct, screenProduct) {
  addToCart(caseProduct);
  addToCart(screenProduct);
  openCart();
}

function filterAccessories(brand) {
  TM_ACCESSORY_BRAND = brand;
  document.querySelectorAll('.brand-tab').forEach(function(btn) {
    btn.classList.toggle('active', btn.dataset.brand === brand);
  });
  applyAccessoryFilters();
}

function setAccessorySearch(query) {
  TM_ACCESSORY_SEARCH = String(query || '').trim().toLowerCase();
  applyAccessoryFilters();
}

function applyAccessoryFilters() {
  var cards = document.querySelectorAll('.accessory-card');
  var visibleCount = 0;
  cards.forEach(function(card) {
    var matchesBrand = TM_ACCESSORY_BRAND === 'All' || card.dataset.brand === TM_ACCESSORY_BRAND;
    var matchesSearch = TM_ACCESSORY_SEARCH === '' || card.dataset.search.indexOf(TM_ACCESSORY_SEARCH) !== -1;
    var show = matchesBrand && matchesSearch;
    card.style.display = show ? '' : 'none';
    if (show) visibleCount += 1;
  });

  var empty = document.getElementById('accessories-empty-state');
  if (empty) {
    empty.hidden = visibleCount !== 0;
  }

  var meta = document.getElementById('accessories-toolbar-meta');
  if (meta) {
    var label = visibleCount === 1 ? 'accessory match' : 'accessory matches';
    meta.textContent = visibleCount + ' ' + label;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  filterAccessories(TM_ACCESSORY_BRAND);
  var search = document.getElementById('accessory-search');
  if (search && TM_ACCESSORY_SEARCH) {
    search.value = TM_ACCESSORY_SEARCH;
  }
});
</script>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
