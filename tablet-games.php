<?php
$currentPage = 'games';
$lastUpdated = 'April 8, 2026';

$games = [
  [
    'name' => 'Pokemon TCG Pocket',
    'genre' => 'Card Strategy',
    'badge' => 'Best Overall Pick',
    'platforms' => ['iPad', 'Android Tablet'],
    'best_tablet' => 'iPad Air or Galaxy Tab S9 FE',
    'fit' => 'Fast sessions, clean touch controls, and a card layout that scales well on larger displays.',
    'best_for' => 'Players who want collectible strategy, quick matches, and a game that feels natural in portrait or landscape.',
    'source_label' => 'Google Play Best Game 2025',
    'source_url' => 'https://blog.google/products/google-play/best-apps-games-2025/',
    'shop_href' => 'shop.php?brand=Apple',
  ],
  [
    'name' => 'DREDGE',
    'genre' => 'Atmospheric Adventure',
    'badge' => 'Premium Favorite',
    'platforms' => ['iPad', 'Android Tablet'],
    'best_tablet' => 'iPad Pro or iPad Air',
    'fit' => 'A slow-burn premium game that benefits from a bigger display, dark-room visuals, and touch-first inventory play.',
    'best_for' => 'Players who want a richer single-player experience instead of another free-to-play loop.',
    'source_label' => 'Apple iPad Game of the Year 2025 and Google Play Best on Play Pass 2025',
    'source_url' => 'https://developer.apple.com/app-store/app-store-awards-2025/',
    'shop_href' => 'shop.php?brand=Apple',
  ],
  [
    'name' => 'Infinity Nikki',
    'genre' => 'Open-World Adventure',
    'badge' => 'Apple Finalist',
    'platforms' => ['iPad'],
    'best_tablet' => 'iPad Pro',
    'fit' => 'Large environments, outfit-driven progression, and bright visual design make more sense on a large tablet than on a phone.',
    'best_for' => 'Players who want exploration, collecting, and a polished visual experience.',
    'source_label' => 'Apple App Store Awards 2025 iPad finalist',
    'source_url' => 'https://apps.apple.com/ga/app/infinity-nikki/id6502622570',
    'shop_href' => 'shop.php?brand=Apple',
  ],
  [
    'name' => 'Prince of Persia Lost Crown',
    'genre' => 'Action Platformer',
    'badge' => 'Apple Finalist',
    'platforms' => ['iPad'],
    'best_tablet' => 'iPad Air or iPad Pro',
    'fit' => 'Precision combat and platforming benefit from the extra screen space and premium performance headroom.',
    'best_for' => 'Players who want something sharper and faster than a casual mobile platformer.',
    'source_label' => 'Apple App Store Awards 2025 iPad finalist',
    'source_url' => 'https://developer.apple.com/app-store/app-store-awards-2025/',
    'shop_href' => 'shop.php?brand=Apple',
  ],
  [
    'name' => 'Disney Speedstorm',
    'genre' => 'Arcade Racing',
    'badge' => 'Best Multi-Device',
    'platforms' => ['Android Tablet', 'Surface'],
    'best_tablet' => 'Galaxy Tab S10 or Surface Pro 11',
    'fit' => 'Google specifically recognized it for strong play across PC, tablet, and mobile, which is exactly the story this page should tell.',
    'best_for' => 'Players who want fast races, local pickup-and-play energy, and a game that travels well across screens.',
    'source_label' => 'Google Play Best Multi-device Game 2025',
    'source_url' => 'https://blog.google/products/google-play/best-apps-games-2025/',
    'shop_href' => 'shop.php?brand=Samsung',
  ],
  [
    'name' => 'Disco Elysium',
    'genre' => 'Story RPG',
    'badge' => 'Best Story',
    'platforms' => ['Android Tablet', 'Surface'],
    'best_tablet' => 'Surface Pro or Galaxy Tab S10 Ultra',
    'fit' => 'Dense reading, choice-heavy storytelling, and slower pacing are much more comfortable on a tablet than on a smaller phone display.',
    'best_for' => 'Players who want narrative depth, not twitch gameplay.',
    'source_label' => 'Google Play Best Story 2025',
    'source_url' => 'https://blog.google/products/google-play/best-apps-games-2025/',
    'shop_href' => 'shop.php?brand=Microsoft',
  ],
  [
    'name' => 'Wuthering Waves',
    'genre' => 'Action RPG',
    'badge' => 'Best Ongoing',
    'platforms' => ['Android Tablet'],
    'best_tablet' => 'Galaxy Tab S10 Ultra',
    'fit' => 'A stronger tablet gives the combat, exploration, and visual effects enough screen and performance headroom to land properly.',
    'best_for' => 'Players who want a big live-service action RPG and do not mind an ongoing grind.',
    'source_label' => 'Google Play Best Ongoing 2025',
    'source_url' => 'https://blog.google/products/google-play/best-apps-games-2025/',
    'shop_href' => 'shop.php?brand=Samsung',
  ],
  [
    'name' => 'Chants of Sennaar',
    'genre' => 'Puzzle Adventure',
    'badge' => 'Best Indie',
    'platforms' => ['Android Tablet'],
    'best_tablet' => 'Galaxy Tab S9 FE',
    'fit' => 'Its visual-language puzzles and cleaner pace work especially well on a screen that gives the art and symbols more room.',
    'best_for' => 'Players who want something thoughtful, stylish, and different from the usual mobile chart leaders.',
    'source_label' => 'Google Play Best Indie 2025',
    'source_url' => 'https://blog.google/products/google-play/best-apps-games-2025/',
    'shop_href' => 'shop.php?brand=Samsung',
  ],
];

$playStyles = [
  [
    'title' => 'Best for quick sessions',
    'game' => 'Pokemon TCG Pocket',
    'desc' => 'Short rounds, strong touch flow, and a layout that works whether you are on the couch or waiting in line.',
  ],
  [
    'title' => 'Best premium single-player pick',
    'game' => 'DREDGE',
    'desc' => 'A more console-like tablet experience with mood, pacing, and actual atmosphere.',
  ],
  [
    'title' => 'Best racing game',
    'game' => 'Disney Speedstorm',
    'desc' => 'High energy, clear visuals, and official recognition for strong multi-device play.',
  ],
  [
    'title' => 'Best story-heavy game',
    'game' => 'Disco Elysium',
    'desc' => 'The kind of game that gets easier to sink into when text and interface elements have real space.',
  ],
  [
    'title' => 'Best action RPG',
    'game' => 'Wuthering Waves',
    'desc' => 'A big-screen Android pick when you want movement, combat, and a live-service progression loop.',
  ],
  [
    'title' => 'Best visual showcase',
    'game' => 'Infinity Nikki',
    'desc' => 'A strong fit for high-end iPads that can turn style and environment detail into the point of the experience.',
  ],
];

$tabletMatches = [
  [
    'tablet' => 'iPad Gaming Picks',
    'games' => 'DREDGE, Infinity Nikki, and Prince of Persia Lost Crown',
    'desc' => 'Best for players who want polished premium releases and the strongest tablet-first ecosystem.',
    'shop_href' => 'shop.php?brand=Apple',
  ],
  [
    'tablet' => 'Galaxy Tab Gaming Picks',
    'games' => 'Disney Speedstorm, Wuthering Waves, and Pokemon TCG Pocket',
    'desc' => 'Best for players who want large Android displays, multitasking flexibility, and a mix of action and pick-up-and-play titles.',
    'shop_href' => 'shop.php?brand=Samsung',
  ],
  [
    'tablet' => 'Surface Gaming Picks',
    'games' => 'Disney Speedstorm and Disco Elysium',
    'desc' => 'Best for users who split time between work and games and want a tablet that still behaves like a PC when needed.',
    'shop_href' => 'shop.php?brand=Microsoft',
  ],
  [
    'tablet' => 'Budget Tablet Gaming Picks',
    'games' => 'Pokemon TCG Pocket and lighter casual titles',
    'desc' => 'Best for buyers who want card, puzzle, and family-friendly play without flagship-tablet pricing.',
    'shop_href' => 'shop.php?brand=Amazon',
  ],
];

$sources = [
  [
    'label' => 'Apple App Store Awards 2025',
    'url' => 'https://developer.apple.com/app-store/app-store-awards-2025/',
  ],
  [
    'label' => 'Apple App Store Awards finalists',
    'url' => 'https://www.apple.com/gw/newsroom/2025/11/apple-announces-finalists-for-the-2025-app-store-awards/',
  ],
  [
    'label' => 'Google Play Best of 2025',
    'url' => 'https://blog.google/products/google-play/best-apps-games-2025/',
  ],
];

$featured = $games[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tablet Games - Tablet Masters</title>
  <meta name="description" content="Tablet Games by Tablet Masters: current, source-backed game picks for iPad, Galaxy Tab, Surface, and Fire tablets." />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png" />
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#3B82F6" />
</head>
<body>

<?php include 'includes/nav.php'; ?>

<section class="tablet-games-hero">
  <div class="tablet-games-hero-inner">
    <div class="tablet-games-hero-copy">
      <div class="section-label">Tablet Masters Editorial</div>
      <h1 class="tablet-games-title">TABLET GAMES</h1>
      <p class="tablet-games-intro">
        A current page for people who want games that actually make sense on tablets, not just phone titles stretched onto a larger screen.
        These picks focus on recent Apple and Google editorial winners, finalists, and games that hold up better on iPad, Galaxy Tab,
        Surface, and other large-screen tablets.
      </p>
      <div class="tablet-games-chip-row">
        <span class="tablet-games-chip">Updated <?= htmlspecialchars($lastUpdated) ?></span>
        <span class="tablet-games-chip">8 current picks</span>
        <span class="tablet-games-chip">iPad, Android Tablet, Surface</span>
      </div>
      <div class="tablet-games-actions">
        <a class="btn-primary" href="#tablet-games-grid">Browse Picks</a>
        <a class="btn-outline" href="#tablet-matches">Match a Tablet</a>
      </div>
    </div>

    <div class="tablet-games-hero-panel">
      <div class="tablet-games-panel-label">Featured Right Now</div>
      <h2><?= htmlspecialchars($featured['name']) ?></h2>
      <p><?= htmlspecialchars($featured['fit']) ?></p>
      <div class="tablet-games-chip-row">
        <span class="tablet-games-chip accent"><?= htmlspecialchars($featured['badge']) ?></span>
        <span class="tablet-games-chip"><?= htmlspecialchars($featured['genre']) ?></span>
      </div>
      <ul class="tablet-games-feature-list">
        <li><span class="plan-dot"></span>Best tablet: <?= htmlspecialchars($featured['best_tablet']) ?></li>
        <li><span class="plan-dot"></span><?= htmlspecialchars($featured['best_for']) ?></li>
        <li><span class="plan-dot"></span>Source: <?= htmlspecialchars($featured['source_label']) ?></li>
      </ul>
      <div class="tablet-games-actions tablet-games-actions-tight">
        <a class="btn-primary" href="<?= htmlspecialchars($featured['source_url']) ?>" target="_blank" rel="noreferrer">View Source</a>
        <a class="btn-outline" href="<?= htmlspecialchars($featured['shop_href']) ?>">Shop Matching Tablets</a>
      </div>
    </div>
  </div>
</section>

<section class="tablet-games-page" id="tablet-games-grid">
  <div class="repair-section-title">
    <div class="section-label">// Best Right Now</div>
    <div class="section-title" style="font-size:44px">CURRENT PICKS</div>
  </div>

  <div class="tablet-games-grid">
    <?php foreach ($games as $game): ?>
    <article class="tablet-game-card">
      <div class="tablet-game-card-top">
        <div>
          <div class="tablet-game-badge"><?= htmlspecialchars($game['badge']) ?></div>
          <h3><?= htmlspecialchars($game['name']) ?></h3>
        </div>
        <div class="reviews-price-tag"><?= htmlspecialchars($game['genre']) ?></div>
      </div>

      <div class="tablet-games-chip-row">
        <?php foreach ($game['platforms'] as $platform): ?>
        <span class="tablet-games-chip"><?= htmlspecialchars($platform) ?></span>
        <?php endforeach; ?>
      </div>

      <p><?= htmlspecialchars($game['fit']) ?></p>

      <div class="tablet-game-meta">
        <strong>Best on:</strong>
        <span><?= htmlspecialchars($game['best_tablet']) ?></span>
      </div>
      <div class="tablet-game-meta">
        <strong>Good for:</strong>
        <span><?= htmlspecialchars($game['best_for']) ?></span>
      </div>
      <div class="tablet-game-meta">
        <strong>Why it made the list:</strong>
        <span><?= htmlspecialchars($game['source_label']) ?></span>
      </div>

      <div class="tablet-games-actions tablet-games-actions-tight">
        <a class="btn-outline" href="<?= htmlspecialchars($game['source_url']) ?>" target="_blank" rel="noreferrer">Official Source</a>
        <a class="btn-primary" href="<?= htmlspecialchars($game['shop_href']) ?>">Shop Tablets</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-top:72px;margin-bottom:32px;">
    <div class="section-label">// Best By Play Style</div>
    <div class="section-title" style="font-size:44px">WHAT TO PLAY</div>
  </div>

  <div class="tablet-games-mini-grid">
    <?php foreach ($playStyles as $item): ?>
    <div class="tablet-games-mini-card">
      <div class="tablet-games-mini-title"><?= htmlspecialchars($item['title']) ?></div>
      <h4><?= htmlspecialchars($item['game']) ?></h4>
      <p><?= htmlspecialchars($item['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" id="tablet-matches" style="margin-top:72px;margin-bottom:32px;">
    <div class="section-label">// Best By Tablet</div>
    <div class="section-title" style="font-size:44px">MATCH THE HARDWARE</div>
  </div>

  <div class="tablet-games-match-grid">
    <?php foreach ($tabletMatches as $match): ?>
    <div class="tablet-games-match-card">
      <h4><?= htmlspecialchars($match['tablet']) ?></h4>
      <p class="tablet-games-match-games"><?= htmlspecialchars($match['games']) ?></p>
      <p><?= htmlspecialchars($match['desc']) ?></p>
      <a class="btn-outline full" href="<?= htmlspecialchars($match['shop_href']) ?>">Shop This Category</a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-cta tablet-games-cta">
    <div>
      <h3>TURN THE PAGE INTO A TRAFFIC ASSET</h3>
      <p>
        This page should not stay static. Update it monthly with one or two new picks, swap the featured game when a better
        release lands, and keep routing readers back into the right tablet category.
      </p>
      <p>
        The page works best when Tablet Masters treats it like editorial content with a commerce path, not just another empty landing page.
      </p>
    </div>

    <div class="repair-form tablet-games-source-card">
      <div class="tablet-games-panel-label">Current source set</div>
      <ul class="tablet-games-source-list">
        <?php foreach ($sources as $source): ?>
        <li>
          <a href="<?= htmlspecialchars($source['url']) ?>" target="_blank" rel="noreferrer"><?= htmlspecialchars($source['label']) ?></a>
        </li>
        <?php endforeach; ?>
      </ul>
      <p class="tablet-games-source-note">
        As of <?= htmlspecialchars($lastUpdated) ?>, these recommendations are anchored to the latest official Apple and Google editorial recognition available.
      </p>
      <a class="btn-primary full" href="shop.php">Shop Tablets</a>
      <a class="btn-outline full" href="reviews.php">Read Tablet Reviews</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
