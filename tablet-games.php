<?php
$currentPage = 'games';
include 'includes/game-page-data.php';

$lastUpdated = $gamePageMeta['last_updated'];
$coverageMonth = $gamePageMeta['coverage_month'];
$editorNote = $gamePageMeta['editor_note'];
$refreshRhythm = $gamePageMeta['refresh_rhythm'];
$monthlyFocus = $gamePageMeta['monthly_focus'];

$featuredGames = array_values(array_filter($games, static fn($game) => !empty($game['featured'])));
$featured = $featuredGames[0] ?? $games[0];
$newGames = array_values(array_filter($games, static fn($game) => !empty($game['new_this_month'])));
$topPicks = array_slice($games, 0, 4);
$morePicks = array_slice($games, 4);

$faqSchema = [
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(
    static fn($item) => [
      '@type' => 'Question',
      'name' => $item['question'],
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => $item['answer'],
      ],
    ],
    $faqItems
  ),
];
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
  <script type="application/ld+json"><?= json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>

<?php include 'includes/nav.php'; ?>

<section class="tablet-games-hero">
  <div class="tablet-games-hero-inner">
    <div class="tablet-games-hero-copy">
      <div class="section-label">Tablet Masters Editorial</div>
      <h1 class="tablet-games-title">TABLET GAMES</h1>
      <p class="tablet-games-intro">
        <?= htmlspecialchars($monthlyFocus) ?> This page is built to refresh monthly so readers can discover current picks,
        compare what makes sense on bigger screens, and move directly into the right tablet category.
      </p>
      <div class="tablet-games-chip-row">
        <span class="tablet-games-chip">Updated <?= htmlspecialchars($lastUpdated) ?></span>
        <span class="tablet-games-chip"><?= htmlspecialchars($coverageMonth) ?> picks</span>
        <span class="tablet-games-chip">iPad, Android Tablet, Surface</span>
      </div>
      <div class="tablet-games-editor-note">
        <strong>Editor note:</strong>
        <span><?= htmlspecialchars($editorNote) ?></span>
      </div>
      <div class="tablet-games-actions">
        <a class="btn-primary" href="#tablet-games-grid" data-track="games_nav" data-track-label="browse_picks">Browse Picks</a>
        <a class="btn-outline" href="#new-this-month" data-track="games_nav" data-track-label="new_this_month">New This Month</a>
        <a class="btn-outline" href="#tablet-matches" data-track="games_nav" data-track-label="match_tablet">Match a Tablet</a>
      </div>
    </div>

    <div class="tablet-games-hero-panel">
      <div class="tablet-games-panel-label">Featured Right Now</div>
      <h2><?= htmlspecialchars($featured['name']) ?></h2>
      <p><?= htmlspecialchars($featured['fit']) ?></p>
      <div class="tablet-games-chip-row">
        <span class="tablet-games-chip accent"><?= htmlspecialchars($featured['badge']) ?></span>
        <span class="tablet-games-chip"><?= htmlspecialchars($featured['genre']) ?></span>
        <?php if (!empty($featured['new_this_month'])): ?>
        <span class="tablet-games-chip">New This Month</span>
        <?php endif; ?>
      </div>
      <ul class="tablet-games-feature-list">
        <li><span class="plan-dot"></span>Best tablet: <?= htmlspecialchars($featured['best_tablet']) ?></li>
        <li><span class="plan-dot"></span><?= htmlspecialchars($featured['best_for']) ?></li>
        <li><span class="plan-dot"></span>Source: <?= htmlspecialchars($featured['source_label']) ?></li>
      </ul>
      <p class="tablet-games-source-note"><?= htmlspecialchars($refreshRhythm) ?></p>
      <div class="tablet-games-actions tablet-games-actions-tight">
        <a class="btn-primary" href="<?= htmlspecialchars($featured['shop_href']) ?>" data-track="games_featured_shop" data-track-label="<?= htmlspecialchars($featured['name']) ?>">Shop Matching Tablets</a>
        <a class="btn-outline" href="<?= htmlspecialchars($featured['source_url']) ?>" target="_blank" rel="noreferrer" data-track="games_featured_source" data-track-label="<?= htmlspecialchars($featured['name']) ?>">View Source</a>
      </div>
    </div>
  </div>
</section>

<section class="tablet-games-page">
  <div class="tablet-games-storyline">
    <div class="tablet-games-story-card" id="new-this-month">
      <div class="section-label">// Monthly Refresh</div>
      <div class="section-title" style="font-size:44px">NEW THIS MONTH</div>
      <p><?= htmlspecialchars($editorNote) ?></p>
      <div class="tablet-games-mini-grid tablet-games-mini-grid-compact">
        <?php foreach ($monthlyUpdates as $item): ?>
        <div class="tablet-games-mini-card">
          <div class="tablet-games-mini-title">Update</div>
          <h4><?= htmlspecialchars($item['title']) ?></h4>
          <p><?= htmlspecialchars($item['desc']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="tablet-games-story-card">
      <div class="section-label">// Commerce Path</div>
      <div class="section-title" style="font-size:44px">SHOP THE RIGHT CATEGORY</div>
      <p>
        The goal is not just to list good games. Each recommendation should push readers toward the right device tier, then
        into <a href="shop.php" data-track="games_internal_link" data-track-label="hero_shop">shopping</a>,
        <a href="reviews.php" data-track="games_internal_link" data-track-label="hero_reviews">reviews</a>, or
        <a href="plans.php" data-track="games_internal_link" data-track-label="hero_plans">protection plans</a> once purchase intent shows up.
      </p>
      <div class="tablet-games-actions">
        <a class="btn-primary" href="shop.php" data-track="games_internal_shop" data-track-label="all_tablets">Shop Tablets</a>
        <a class="btn-outline" href="reviews.php" data-track="games_internal_reviews" data-track-label="read_reviews">Read Reviews</a>
        <a class="btn-outline" href="plans.php" data-track="games_internal_plans" data-track-label="view_plans">View Plans</a>
      </div>
    </div>
  </div>

  <div class="repair-section-title" id="tablet-games-grid">
    <div class="section-label">// Best Right Now</div>
    <div class="section-title" style="font-size:44px">CURRENT PICKS</div>
  </div>

  <div class="tablet-games-grid">
    <?php foreach ($topPicks as $game): ?>
    <article class="tablet-game-card">
      <?php if (!empty($game['img'])): ?>
      <div class="tablet-game-card-img tablet-game-card-img-<?= htmlspecialchars($game['img_fit'] ?? 'cover') ?>">
        <img src="<?= htmlspecialchars($game['img']) ?>" alt="<?= htmlspecialchars($game['name']) ?>" loading="lazy" decoding="async" />
      </div>
      <?php endif; ?>
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
        <?php if (!empty($game['new_this_month'])): ?>
        <span class="tablet-games-chip tablet-games-chip-highlight">New This Month</span>
        <?php endif; ?>
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
      <p class="tablet-games-card-note"><?= htmlspecialchars($game['comparison_note']) ?></p>

      <div class="tablet-games-actions tablet-games-actions-tight">
        <a class="btn-primary" href="<?= htmlspecialchars($game['shop_href']) ?>" data-track="games_card_shop" data-track-label="<?= htmlspecialchars($game['name']) ?>"><?= htmlspecialchars($game['cta_label']) ?></a>
        <a class="btn-outline" href="<?= htmlspecialchars($game['review_href']) ?>" data-track="games_card_review" data-track-label="<?= htmlspecialchars($game['name']) ?>">Read Reviews</a>
        <a class="btn-outline" href="<?= htmlspecialchars($game['source_url']) ?>" target="_blank" rel="noreferrer" data-track="games_card_source" data-track-label="<?= htmlspecialchars($game['name']) ?>">Official Source</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <div class="tablet-games-commerce-banner">
    <div>
      <div class="section-label">// Mid-Page CTA</div>
      <h3>BUY FOR THE WAY PEOPLE ACTUALLY PLAY</h3>
      <p>
        Readers who made it this far are already comparing use cases. Push them into the right device lane before they bounce:
        premium iPads for polished single-player games, Galaxy Tabs for flexibility and Android value, and Surface for work-and-play overlap.
      </p>
    </div>
    <div class="tablet-games-actions">
      <a class="btn-primary" href="shop.php?brand=Apple" data-track="games_mid_cta" data-track-label="apple_lane">Shop iPad Picks</a>
      <a class="btn-outline" href="shop.php?brand=Samsung" data-track="games_mid_cta" data-track-label="samsung_lane">Shop Galaxy Tabs</a>
      <a class="btn-outline" href="shop.php?brand=Microsoft" data-track="games_mid_cta" data-track-label="surface_lane">Shop Surface Picks</a>
    </div>
  </div>

  <div class="tablet-games-grid tablet-games-grid-secondary">
    <?php foreach ($morePicks as $game): ?>
    <article class="tablet-game-card">
      <?php if (!empty($game['img'])): ?>
      <div class="tablet-game-card-img tablet-game-card-img-<?= htmlspecialchars($game['img_fit'] ?? 'cover') ?>">
        <img src="<?= htmlspecialchars($game['img']) ?>" alt="<?= htmlspecialchars($game['name']) ?>" loading="lazy" decoding="async" />
      </div>
      <?php endif; ?>
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
        <?php if (!empty($game['new_this_month'])): ?>
        <span class="tablet-games-chip tablet-games-chip-highlight">New This Month</span>
        <?php endif; ?>
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
      <p class="tablet-games-card-note"><?= htmlspecialchars($game['comparison_note']) ?></p>

      <div class="tablet-games-actions tablet-games-actions-tight">
        <a class="btn-primary" href="<?= htmlspecialchars($game['shop_href']) ?>" data-track="games_card_shop" data-track-label="<?= htmlspecialchars($game['name']) ?>"><?= htmlspecialchars($game['cta_label']) ?></a>
        <a class="btn-outline" href="<?= htmlspecialchars($game['review_href']) ?>" data-track="games_card_review" data-track-label="<?= htmlspecialchars($game['name']) ?>">Read Reviews</a>
        <a class="btn-outline" href="<?= htmlspecialchars($game['source_url']) ?>" target="_blank" rel="noreferrer" data-track="games_card_source" data-track-label="<?= htmlspecialchars($game['name']) ?>">Official Source</a>
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

  <div class="repair-section-title" style="margin-top:72px;margin-bottom:32px;">
    <div class="section-label">// Platform Search Coverage</div>
    <div class="section-title" style="font-size:44px">BEST BY PLATFORM</div>
  </div>

  <div class="tablet-games-guide-grid">
    <?php foreach ($categoryGuides as $guide): ?>
    <div class="tablet-games-guide-card">
      <div class="tablet-games-mini-title"><?= htmlspecialchars($coverageMonth) ?></div>
      <h4><?= htmlspecialchars($guide['title']) ?></h4>
      <p><?= htmlspecialchars($guide['copy']) ?></p>
      <div class="tablet-games-actions">
        <a class="btn-primary" href="<?= htmlspecialchars($guide['shop_href']) ?>" data-track="games_guide_shop" data-track-label="<?= htmlspecialchars($guide['title']) ?>">Shop This Lane</a>
        <a class="btn-outline" href="<?= htmlspecialchars($guide['review_href']) ?>" data-track="games_guide_review" data-track-label="<?= htmlspecialchars($guide['title']) ?>">Read Reviews</a>
      </div>
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
      <a class="btn-outline full" href="<?= htmlspecialchars($match['shop_href']) ?>" data-track="games_match_shop" data-track-label="<?= htmlspecialchars($match['tablet']) ?>">Shop This Category</a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="repair-section-title" style="margin-top:72px;margin-bottom:32px;">
    <div class="section-label">// Common Questions</div>
    <div class="section-title" style="font-size:44px">FAQ</div>
  </div>

  <div class="tablet-games-faq">
    <?php foreach ($faqItems as $item): ?>
    <details class="tablet-games-faq-item">
      <summary><?= htmlspecialchars($item['question']) ?></summary>
      <p><?= htmlspecialchars($item['answer']) ?></p>
    </details>
    <?php endforeach; ?>
  </div>

</section>

<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}

if (typeof initTrackedLinks === "function") {
  initTrackedLinks("[data-track]");
}
</script>
</body>
</html>
