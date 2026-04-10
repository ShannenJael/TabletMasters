<?php
$currentPage = 'reviews';

$products = [
  ['name' => 'iPad Pro M4 11"', 'brand' => 'Apple', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 750, 'img' => 'assets/images/iPad Pro M4 11.png'],
  ['name' => 'iPad Pro M4 13"', 'brand' => 'Apple', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 950, 'img' => 'assets/images/iPad Pro M4 13.png'],
  ['name' => 'iPad Air M2 11"', 'brand' => 'Apple', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 420, 'img' => 'assets/images/iPad Air M2 11.png'],
  ['name' => 'iPad Air M2 13"', 'brand' => 'Apple', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 510, 'img' => 'assets/images/iPad Air M2 13.png'],
  ['name' => 'iPad Mini 7', 'brand' => 'Apple', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 380, 'img' => 'assets/images/iPad Mini 7.png'],
  ['name' => 'iPad 10th Gen', 'brand' => 'Apple', 'condition' => 'Grade A', 'badge' => '', 'price' => 220, 'img' => 'assets/images/iPad 10th Gen.png'],
  ['name' => 'iPad Air (5th Gen)', 'brand' => 'Apple', 'condition' => 'Grade A', 'badge' => 'Sale', 'price' => 179, 'img' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-air-select-wifi-blue-202203?wid=470&hei=556&fmt=png-alpha'],
  ['name' => 'iPad Pro 12.9"', 'brand' => 'Apple', 'condition' => 'Like New', 'badge' => '', 'price' => 450, 'img' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-pro-13-select-wifi-spacegray-202210?wid=470&hei=556&fmt=png-alpha'],
  ['name' => 'iPad Mini 6', 'brand' => 'Apple', 'condition' => 'Grade B', 'badge' => 'Best Seller', 'price' => 160, 'img' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-mini-select-wifi-purple-202109?wid=470&hei=556&fmt=png-alpha'],
  ['name' => 'Galaxy Tab S9 Ultra', 'brand' => 'Samsung', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 650, 'img' => 'assets/images/Galaxy Tab S9 Ultra.png'],
  ['name' => 'Galaxy Tab S9+', 'brand' => 'Samsung', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 490, 'img' => 'assets/images/Galaxy Tab S9+.png'],
  ['name' => 'Galaxy Tab S9', 'brand' => 'Samsung', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 370, 'img' => 'assets/images/Galaxy Tab S9.png'],
  ['name' => 'Galaxy Tab S9 FE', 'brand' => 'Samsung', 'condition' => 'Grade A', 'badge' => '', 'price' => 220, 'img' => 'assets/images/Galaxy Tab S9 FE.png'],
  ['name' => 'Galaxy Tab S8', 'brand' => 'Samsung', 'condition' => 'Grade A', 'badge' => '', 'price' => 225, 'img' => 'assets/images/Galaxy Tab S8.png'],
  ['name' => 'Galaxy Tab S8 Ultra', 'brand' => 'Samsung', 'condition' => 'Like New', 'badge' => '', 'price' => 520, 'img' => 'assets/images/Galaxy Tab S8 Ultra.png'],
  ['name' => 'Galaxy Tab A8', 'brand' => 'Samsung', 'condition' => 'Grade B', 'badge' => 'Best Seller', 'price' => 85, 'img' => 'assets/images/Galaxy Tab A8.png'],
  ['name' => 'Galaxy Tab S7 FE', 'brand' => 'Samsung', 'condition' => 'Grade B', 'badge' => '', 'price' => 130, 'img' => 'assets/images/Galaxy Tab S7 FE.png'],
  ['name' => 'Surface Pro 11', 'brand' => 'Microsoft', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 760, 'img' => 'assets/images/Surface Pro 11.png'],
  ['name' => 'Surface Go 4', 'brand' => 'Microsoft', 'condition' => 'Grade A', 'badge' => '', 'price' => 340, 'img' => 'assets/images/Surface Go 4.png'],
  ['name' => 'Surface Pro 9', 'brand' => 'Microsoft', 'condition' => 'Like New', 'badge' => '', 'price' => 520, 'img' => 'assets/images/Surface Pro 9.png'],
  ['name' => 'Surface Go 3', 'brand' => 'Microsoft', 'condition' => 'Grade A', 'badge' => '', 'price' => 170, 'img' => 'assets/images/Surface Go 3.png'],
  ['name' => 'Surface Pro 8', 'brand' => 'Microsoft', 'condition' => 'Grade A', 'badge' => '', 'price' => 370, 'img' => 'assets/images/Surface Pro 8.png'],
  ['name' => 'Fire HD 10 (2023)', 'brand' => 'Amazon', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 80, 'img' => 'assets/images/Fire HD 10 (2025) 1.png'],
  ['name' => 'Fire HD 10 Plus', 'brand' => 'Amazon', 'condition' => 'Grade A', 'badge' => 'Sale', 'price' => 90, 'img' => 'assets/images/Fire HD 10 (2025).png'],
  ['name' => 'Fire Max 11', 'brand' => 'Amazon', 'condition' => 'Like New', 'badge' => '', 'price' => 150, 'img' => 'assets/images/Fire Max 11.png'],
  ['name' => 'iPad Air 4th Gen', 'brand' => 'Apple', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 160, 'img' => 'assets/images/iPad Air 4th Gen.png'],
  ['name' => 'iPad 9th Gen', 'brand' => 'Apple', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 120, 'img' => 'assets/images/iPad 9th Gen.png'],
  ['name' => 'iPad Mini 5', 'brand' => 'Apple', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 110, 'img' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/ipad-mini-select-wifi-silver-201903?wid=470&hei=556&fmt=png-alpha'],
  ['name' => 'Galaxy Tab S7', 'brand' => 'Samsung', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 150, 'img' => 'assets/images/Galaxy Tab S7.png'],
  ['name' => 'Galaxy Tab S7+', 'brand' => 'Samsung', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 200, 'img' => 'assets/images/Galaxy Tab S7+.png'],
  ['name' => 'Galaxy Tab S6 Lite', 'brand' => 'Samsung', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 100, 'img' => 'assets/images/Galaxy Tab S6 Lite.png'],
  ['name' => 'Surface Pro 7', 'brand' => 'Microsoft', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 200, 'img' => 'assets/images/Surface Pro 7.png'],
  ['name' => 'Surface Pro X', 'brand' => 'Microsoft', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 180, 'img' => 'assets/images/Surface Pro 10 3-Photoroom.png'],
  ['name' => 'Fire HD 8 (2022)', 'brand' => 'Amazon', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 45, 'img' => 'assets/images/Fire HD 8.png'],
  ['name' => 'Fire HD 7', 'brand' => 'Amazon', 'condition' => 'Grade B', 'badge' => 'Previous Gen', 'price' => 30, 'img' => 'assets/images/Fire 7 (2024).png'],
  ['name' => 'Galaxy Tab S10+', 'brand' => 'Samsung', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 589, 'img' => 'assets/images/Galaxy Tab S10+.png'],
  ['name' => 'Galaxy Tab S10 Ultra', 'brand' => 'Samsung', 'condition' => 'Like New', 'badge' => 'Latest', 'price' => 799, 'img' => 'assets/images/Galaxy Tab S10 Ultra.png'],
  ['name' => 'Galaxy Tab S10 FE', 'brand' => 'Samsung', 'condition' => 'Grade A', 'badge' => 'Latest', 'price' => 299, 'img' => 'assets/images/Galaxy Tab S10 FE.png'],
  ['name' => 'Galaxy Tab S10 Lite', 'brand' => 'Samsung', 'condition' => 'Grade A', 'badge' => 'Latest', 'price' => 249, 'img' => 'assets/images/Galaxy Tab S10 Lite.png'],
];

$videoReviews = [
  'iPad Pro M4 11"' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/4nzW7RKBGfc',
    'youtube_label' => 'Third-Party YouTube Review',
    'best_for' => 'Creative professionals, executives, and power users who want the strongest tablet-first app ecosystem.',
    'pros' => ['Elite performance', 'Excellent OLED display', 'Best app ecosystem'],
    'cons' => ['Premium pricing', 'Accessories raise total cost'],
    'verdict' => 'If you want the best all-around premium tablet experience, this is the benchmark.',
  ],
  'iPad Pro M4 13"' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/Eib5gMwcT3o',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'iPad Air M2 11"' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/G58y3ua-wRw',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'iPad Mini 7' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/RzM6gzAB-vM',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'iPad 10th Gen' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/1GYc_dzCFbU',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'iPad Air (5th Gen)' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/vWY6nfNC-T8',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'iPad Pro 12.9"' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/l80NRtjgY54',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'iPad Mini 6' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/1uX2hR3NZ9s',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Galaxy Tab S9+' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/nIga1fW2cgE',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Galaxy Tab S9 FE' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/qqAm0txcf5Q',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Galaxy Tab S8 Ultra' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/7G2qALYzEfI',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Galaxy Tab A8' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/8DJn1qzvxB0',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Galaxy Tab S7 FE' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/pKIDkMJ7bUo',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Go 4' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/lvWGL1K0QMQ',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Pro 9' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/TpABlcB4gZI',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Go 3' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/-r5LZ6qfbfg',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Pro 8' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/_J8nXMbYOac',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Pro 7' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/3YT87jwNoaA',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Pro X' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/ed1Ntp0m2II',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Fire Max 11' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/x3bdy6-P5e8',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Fire HD 10 (2023)' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/UCmM1ga4TbM',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Fire HD 10 Plus' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/Sa1RuQFNvGE',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Fire HD 8 (2022)' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/IqzYQZYl1CU',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Galaxy Tab S10 Ultra' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/_NyffAsFdMg',
    'youtube_label' => 'Third-Party YouTube Review',
    'best_for' => 'Android users who want a large canvas for productivity, note-taking, or content creation.',
    'pros' => ['Massive display', 'Strong multitasking tools', 'Great stylus experience'],
    'cons' => ['Very large footprint', 'Android tablet apps can still vary'],
    'verdict' => 'A top-tier Android tablet with serious screen space and a strong creative workflow story.',
  ],
  'Galaxy Tab S10 FE' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/sHpx2cCQ5YA',
    'youtube_label' => 'Third-Party YouTube Review',
  ],
  'Surface Pro 11' => [
    'youtube_embed' => 'https://www.youtube-nocookie.com/embed/Ue2kz3f861I',
    'youtube_label' => 'Third-Party YouTube Review',
    'best_for' => 'Professionals who want a Windows tablet for work, meetings, and lightweight travel.',
    'pros' => ['Strong portability', 'Full Windows workflow', 'Versatile 2-in-1 use'],
    'cons' => ['Keyboard adds cost', 'Not every app feels truly tablet-first'],
    'verdict' => 'A strong pick when your workflow lives in Windows and you still want tablet flexibility.',
  ],
];

function reviewSlug($name) {
  $slug = strtolower($name);
  $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
  return trim($slug, '-');
}

function reviewRating($condition, $badge) {
  $rating = 4.2;
  if ($condition === 'Like New') $rating = 4.7;
  if ($condition === 'Grade A') $rating = 4.4;
  if ($condition === 'Grade B') $rating = 4.0;
  if ($badge === 'Latest') $rating += 0.1;
  if ($badge === 'Best Seller') $rating += 0.1;
  if ($badge === 'Previous Gen') $rating -= 0.1;
  return max(3.8, min(4.9, $rating));
}

function brandAudience($brand) {
  $map = [
    'Apple' => 'users who want the strongest tablet app ecosystem and long-term polish',
    'Samsung' => 'Android buyers who want strong media, multitasking, and stylus flexibility',
    'Microsoft' => 'professionals who want a Windows-first workflow in tablet form',
    'Amazon' => 'budget-focused users who want simple entertainment and everyday use',
  ];
  return $map[$brand] ?? 'tablet buyers comparing everyday performance and value';
}

function defaultSummary($product) {
  $conditionText = strtolower($product['condition']);
  $badgeText = $product['badge'] !== '' ? strtolower($product['badge']) . ' ' : '';
  return $product['name'] . ' is a ' . $badgeText . $conditionText . ' ' . strtolower($product['brand']) . ' tablet option with solid value for buyers comparing performance, condition, and price.';
}

function defaultPros($product) {
  return [
    $product['condition'] . ' condition option',
    'Competitive price point',
    'Good fit for ' . brandAudience($product['brand']),
  ];
}

function defaultCons($product) {
  $cons = ['Specs vary compared with newer flagship tablets'];
  if ($product['badge'] === 'Previous Gen') {
    $cons[] = 'Older generation hardware';
  } else {
    $cons[] = 'May need accessories for the best experience';
  }
  $cons[] = 'Availability can shift with inventory';
  return $cons;
}

function renderStars($rating) {
  $html = '';
  for ($i = 1; $i <= 5; $i++) {
    if ($rating >= $i) {
      $html .= '<i class="fa-solid fa-star"></i>';
    } elseif ($rating >= $i - 0.5) {
      $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
    } else {
      $html .= '<i class="fa-regular fa-star"></i>';
    }
  }
  return $html;
}

$reviews = [];
foreach ($products as $product) {
  $special = $videoReviews[$product['name']] ?? [];
  $hasVideo = isset($special['youtube_embed']);
  $reviews[] = [
    'slug' => reviewSlug($product['name']),
    'name' => $product['name'],
    'brand' => $product['brand'],
    'type' => $hasVideo ? 'YouTube + Written' : 'Written',
    'rating' => reviewRating($product['condition'], $product['badge']),
    'summary' => $special['summary'] ?? defaultSummary($product),
    'best_for' => $special['best_for'] ?? ucfirst($product['condition']) . ' tablet buyers and ' . brandAudience($product['brand']) . '.',
    'pros' => $special['pros'] ?? defaultPros($product),
    'cons' => $special['cons'] ?? defaultCons($product),
    'image' => $product['img'],
    'youtube_embed' => $special['youtube_embed'] ?? '',
    'youtube_label' => $special['youtube_label'] ?? '',
    'verdict' => $special['verdict'] ?? 'A sensible ' . strtolower($product['brand']) . ' option if you want ' . strtolower($product['condition']) . ' condition and a balanced price-to-performance mix.',
    'shop_link' => 'shop.php?brand=' . rawurlencode($product['brand']),
    'condition' => $product['condition'],
    'badge' => $product['badge'],
    'price' => $product['price'],
  ];
}

$featured = $reviews[0];
$brandFilters = ['Apple', 'Samsung', 'Microsoft', 'Amazon'];
$typeFilters = ['Written', 'YouTube + Written'];
$requestedBrand = trim((string)($_GET['brand'] ?? 'all'));
$requestedType = trim((string)($_GET['type'] ?? 'all'));
$activeBrand = in_array($requestedBrand, array_merge(['all'], $brandFilters), true) ? $requestedBrand : 'all';
$activeType = in_array($requestedType, array_merge(['all'], $typeFilters), true) ? $requestedType : 'all';
$filteredReviews = array_values(array_filter($reviews, function ($review) use ($activeBrand, $activeType) {
  $brandMatch = $activeBrand === 'all' || $review['brand'] === $activeBrand;
  $typeMatch = $activeType === 'all' || $review['type'] === $activeType;
  return $brandMatch && $typeMatch;
}));

function buildReviewFilterUrl($brand, $type) {
  $params = [];
  if ($brand !== 'all') {
    $params['brand'] = $brand;
  }
  if ($type !== 'all') {
    $params['type'] = $type;
  }
  return '/reviews.php' . ($params ? '?' . http_build_query($params) : '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tablet Reviews - Tablet Masters</title>
  <meta name="description" content="Read Tablet Masters product reviews with star ratings, written verdicts, and YouTube review embeds across the full tablet lineup." />
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

<section class="reviews-hero">
  <div class="reviews-hero-inner">
    <div class="reviews-hero-copy">
      <div class="section-label">Tablet Masters Reviews</div>
      <h1 class="reviews-title">Product reviews with star ratings, written verdicts, and video analysis.</h1>
      <p class="reviews-intro">
        This review hub now covers the full tablet lineup from the shop page. Customers can scan star ratings, compare brands,
        and watch video reviews where they are available.
      </p>
      <div class="reviews-actions">
        <a class="btn-primary" href="#review-grid">Browse Reviews</a>
        <a class="nav-btn-outline" href="shop.php">Shop Tablets</a>
      </div>

      <section class="reviews-toolbar reviews-toolbar-inline">
        <div class="reviews-toolbar-head">
          <div>
            <div class="section-label">Review Filters</div>
            <h2 class="section-title">Find The Right Review Faster</h2>
          </div>
          <p>Filter by brand or review type right from the hero section.</p>
        </div>
        <div class="reviews-filter-row">
          <div class="reviews-filter-group">
            <a class="reviews-filter <?= $activeBrand === 'all' ? 'active' : '' ?>" href="<?= htmlspecialchars(buildReviewFilterUrl('all', $activeType)) ?>">All Brands</a>
            <?php foreach ($brandFilters as $brand): ?>
            <a class="reviews-filter <?= $activeBrand === $brand ? 'active' : '' ?>" href="<?= htmlspecialchars(buildReviewFilterUrl($brand, $activeType)) ?>"><?= htmlspecialchars($brand) ?></a>
            <?php endforeach; ?>
          </div>
          <div class="reviews-filter-group">
            <a class="reviews-filter <?= $activeType === 'all' ? 'active' : '' ?>" href="<?= htmlspecialchars(buildReviewFilterUrl($activeBrand, 'all')) ?>">All Types</a>
            <?php foreach ($typeFilters as $type): ?>
            <a class="reviews-filter <?= $activeType === $type ? 'active' : '' ?>" href="<?= htmlspecialchars(buildReviewFilterUrl($activeBrand, $type)) ?>"><?= htmlspecialchars($type) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </div>

    <div class="reviews-hero-panel">
      <div class="reviews-hero-stat">
        <strong><?= htmlspecialchars((string)count($reviews)) ?></strong>
        <span>Total tablet reviews</span>
      </div>
      <div class="reviews-hero-stat">
        <strong>Star rated</strong>
        <span>Quick visual scoring for faster product comparison</span>
      </div>
      <div class="reviews-hero-stat">
        <strong>Video ready</strong>
        <span>Embedded YouTube reviews appear on supported products</span>
      </div>
    </div>
  </div>
</section>

<main class="reviews-page">
  <section class="reviews-featured">
    <div class="reviews-featured-media">
      <div class="reviews-featured-image">
        <img src="<?= htmlspecialchars($featured['image']) ?>" alt="<?= htmlspecialchars($featured['name']) ?>" />
      </div>
      <?php if ($featured['youtube_embed'] !== ''): ?>
      <div class="reviews-video-shell">
        <div class="reviews-video-label"><?= htmlspecialchars($featured['youtube_label']) ?></div>
        <div class="reviews-video-frame">
          <iframe src="<?= htmlspecialchars($featured['youtube_embed']) ?>" title="<?= htmlspecialchars($featured['name']) ?> review" loading="lazy" allowfullscreen></iframe>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <div class="reviews-featured-copy">
      <div class="reviews-chip-row">
        <span class="reviews-chip"><?= htmlspecialchars($featured['brand']) ?></span>
        <span class="reviews-chip"><?= htmlspecialchars($featured['type']) ?></span>
        <span class="reviews-chip"><?= htmlspecialchars($featured['condition']) ?></span>
      </div>
      <h2><?= htmlspecialchars($featured['name']) ?></h2>
      <div class="reviews-rating-row">
        <div class="reviews-stars"><?= renderStars($featured['rating']) ?></div>
        <strong><?= number_format($featured['rating'], 1) ?>/5</strong>
        <span class="reviews-price-tag">$<?= number_format($featured['price'], 2) ?></span>
      </div>
      <p class="reviews-summary"><?= htmlspecialchars($featured['summary']) ?></p>
      <div class="reviews-feature-panels">
        <div class="reviews-note-card">
          <span>Best for</span>
          <p><?= htmlspecialchars($featured['best_for']) ?></p>
        </div>
        <div class="reviews-note-card">
          <span>Final verdict</span>
          <p><?= htmlspecialchars($featured['verdict']) ?></p>
        </div>
      </div>
      <div class="reviews-procon-grid">
        <div class="reviews-procon-card">
          <strong>Pros</strong>
          <ul>
            <?php foreach ($featured['pros'] as $item): ?>
            <li><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="reviews-procon-card reviews-procon-card-cons">
          <strong>Cons</strong>
          <ul>
            <?php foreach ($featured['cons'] as $item): ?>
            <li><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="reviews-grid" id="review-grid">
    <?php if (!$filteredReviews): ?>
    <div class="reviews-empty-state">
      No tablets match this filter yet. Try another brand or review type.
    </div>
    <?php endif; ?>
    <?php foreach ($filteredReviews as $review): ?>
    <article class="review-card" data-brand="<?= htmlspecialchars($review['brand']) ?>" data-type="<?= htmlspecialchars($review['type']) ?>">
      <div class="review-card-top">
        <img src="<?= htmlspecialchars($review['image']) ?>" alt="<?= htmlspecialchars($review['name']) ?>" loading="lazy" />
        <div class="review-card-copy">
          <div class="reviews-chip-row">
            <span class="reviews-chip"><?= htmlspecialchars($review['brand']) ?></span>
            <span class="reviews-chip"><?= htmlspecialchars($review['type']) ?></span>
            <span class="reviews-chip"><?= htmlspecialchars($review['condition']) ?></span>
          </div>
          <h3><?= htmlspecialchars($review['name']) ?></h3>
          <div class="reviews-rating-row">
            <div class="reviews-stars"><?= renderStars($review['rating']) ?></div>
            <strong><?= number_format($review['rating'], 1) ?>/5</strong>
            <span class="reviews-price-tag">$<?= number_format($review['price'], 2) ?></span>
          </div>
          <p><?= htmlspecialchars($review['summary']) ?></p>
        </div>
      </div>

      <div class="review-card-body">
        <?php if ($review['youtube_embed'] !== ''): ?>
        <div class="reviews-video-shell">
          <div class="reviews-video-label"><?= htmlspecialchars($review['youtube_label']) ?></div>
          <div class="reviews-video-frame reviews-video-frame-card">
            <iframe src="<?= htmlspecialchars($review['youtube_embed']) ?>" title="<?= htmlspecialchars($review['name']) ?> YouTube review" loading="lazy" allowfullscreen></iframe>
          </div>
        </div>
        <?php endif; ?>

        <div class="reviews-mini-grid">
          <div class="reviews-mini-card">
            <span>Best for</span>
            <p><?= htmlspecialchars($review['best_for']) ?></p>
          </div>
          <div class="reviews-mini-card">
            <span>Verdict</span>
            <p><?= htmlspecialchars($review['verdict']) ?></p>
          </div>
        </div>

        <div class="reviews-procon-grid reviews-procon-grid-compact">
          <div class="reviews-procon-card">
            <strong>Pros</strong>
            <ul>
              <?php foreach ($review['pros'] as $item): ?>
              <li><?= htmlspecialchars($item) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="reviews-procon-card reviews-procon-card-cons">
            <strong>Cons</strong>
            <ul>
              <?php foreach ($review['cons'] as $item): ?>
              <li><?= htmlspecialchars($item) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <a class="btn-primary" href="<?= htmlspecialchars($review['shop_link']) ?>">Shop Similar <?= htmlspecialchars($review['brand']) ?> Tablets</a>
      </div>
    </article>
    <?php endforeach; ?>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js');
}
</script>
</body>
</html>
