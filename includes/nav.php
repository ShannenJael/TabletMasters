<?php
// $currentPage is set by each page before including this file
// Possible values: home, shop, insurance, register, plans, games, about, forum, reviews
if (!isset($currentPage)) $currentPage = 'home';

$nav = [
  ['id' => 'home',      'label' => 'Home',             'href' => 'index.php',     'sub' => [
    ['label' => 'Creating Tablet Applications', 'href' => 'https://clouddogg.com', 'external' => true],
    ['label' => 'Education',                    'href' => 'schools.php'],
    ['label' => 'Healthcare & Hospitals',       'href' => 'healthcare-hospitals.php'],
    ['label' => 'Insurance',                    'href' => 'insurance.php'],
    ['label' => 'Business & Conferences',       'href' => 'business-conferences.php'],
  ]],
  ['id' => 'shop',      'label' => 'Shopping',         'href' => 'shop.php',      'sub' => [
    ['label' => 'All',       'href' => 'shop.php'],
    ['label' => 'Apple',     'href' => 'shop.php?brand=Apple'],
    ['label' => 'Samsung',   'href' => 'shop.php?brand=Samsung'],
    ['label' => 'Microsoft', 'href' => 'shop.php?brand=Microsoft'],
    ['label' => 'Amazon',    'href' => 'shop.php?brand=Amazon'],
    ['label' => 'Services',  'href' => 'insurance.php'],
  ]],
  ['id' => 'insurance', 'label' => 'Insurance & Repair','href' => 'insurance.php', 'sub' => [
    ['label' => 'Coverage Plans',      'href' => 'plans.php'],
    ['label' => 'Register Tablet',     'href' => 'register.php'],
    ['label' => 'Support Center',      'href' => 'support.php'],
    ['label' => 'Book a Repair',       'href' => 'insurance.php#book-form'],
  ]],
  ['id' => 'plans',     'label' => 'Plans & Pricing',  'href' => 'plans.php',     'sub' => []],
  ['id' => 'games',     'label' => 'Tablet Games',     'href' => 'tablet-games.php', 'sub' => []],
  ['id' => 'reviews',   'label' => 'Reviews',          'href' => 'reviews.php',   'sub' => []],
  ['id' => 'forum',     'label' => 'Forum',            'href' => 'forum.php',     'sub' => []],
  ['id' => 'about',     'label' => 'About',             'href' => 'about.php',     'sub' => []],
];
?>
<nav class="nav">
  <a class="logo" href="index.php" aria-label="Tablet Masters home">
    <img src="assets/images/tabletmasters-logo.png" alt="Tablet Masters" class="logo-img" />
  </a>

  <ul class="nav-links">
    <?php foreach ($nav as $item): ?>
    <li class="nav-item">
      <a
        class="nav-link <?= ($currentPage === $item['id'] || ($currentPage === 'register' && $item['id'] === 'insurance')) ? 'active' : '' ?>"
        href="<?= htmlspecialchars($item['href']) ?>"
      ><?= htmlspecialchars($item['label']) ?><?= count($item['sub']) ? ' &#9662;' : '' ?></a>

      <?php if (count($item['sub'])): ?>
      <div class="dropdown">
        <?php foreach ($item['sub'] as $sub): ?>
        <a class="dropdown-link" href="<?= htmlspecialchars($sub['href']) ?>" <?= isset($sub['external']) && $sub['external'] ? 'target="_blank" rel="noreferrer"' : '' ?>><?= htmlspecialchars($sub['label']) ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>

  <div class="nav-right">
    <a class="nav-btn-outline" href="shop.php">Shop Tablets</a>
    <button class="nav-cart" onclick="openCart()" aria-label="View cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="nav-cart-text" id="cart-button-label">View Cart (0)</span>
      <span class="cart-badge" id="cart-badge">0</span>
    </button>
    <button class="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</nav>

<!-- Mobile nav -->
<div class="mobile-nav" id="mobile-nav">
  <?php foreach ($nav as $item): ?>
  <a class="nav-link <?= ($currentPage === $item['id'] || ($currentPage === 'register' && $item['id'] === 'insurance')) ? 'active' : '' ?>" href="<?= htmlspecialchars($item['href']) ?>"><?= htmlspecialchars($item['label']) ?></a>
  <?php endforeach; ?>
</div>
