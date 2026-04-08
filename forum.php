<?php
$currentPage = 'forum';

function cleanForumValue($value) {
  return trim(strip_tags((string)($value ?? '')));
}

$categories = [
  'announcements' => [
    'icon' => 'fa-bullhorn',
    'name' => 'Announcements',
    'desc' => 'Official Tablet Masters updates, launches, service notices, and policy changes.',
  ],
  'tablet-help' => [
    'icon' => 'fa-tablet-screen-button',
    'name' => 'Tablet Help',
    'desc' => 'Setup, charging, battery, screen, and software troubleshooting.',
  ],
  'buying-advice' => [
    'icon' => 'fa-cart-shopping',
    'name' => 'Buying Advice',
    'desc' => 'Questions about the right tablet for school, work, or home.',
  ],
  'repairs-claims' => [
    'icon' => 'fa-shield-heart',
    'name' => 'Repairs and Claims',
    'desc' => 'Protection plans, registration, repairs, claims, and replacements.',
  ],
  'business-schools' => [
    'icon' => 'fa-building',
    'name' => 'Business and Schools',
    'desc' => 'Fleet orders, MDM, conference rentals, classrooms, and enterprise rollouts.',
  ],
  'apps-productivity' => [
    'icon' => 'fa-mobile-screen',
    'name' => 'Apps and Productivity',
    'desc' => 'Tablet workflows, note-taking, kiosk setups, and productivity tools.',
  ],
  'tablet-app-development' => [
    'icon' => 'fa-code',
    'name' => 'Tablet App Development',
    'desc' => 'Custom apps, large-screen UX, touch-first interfaces, and tablet optimization.',
  ],
  'community' => [
    'icon' => 'fa-users',
    'name' => 'Community Introductions',
    'desc' => 'Meet customers, schools, teams, and developers using tablets in the real world.',
  ],
  'feedback' => [
    'icon' => 'fa-lightbulb',
    'name' => 'Feedback and Requests',
    'desc' => 'Ideas for the site, support flow, inventory, plans, and future services.',
  ],
];

$errors = [];
$success = isset($_GET['posted']);
$activeCategory = cleanForumValue($_GET['category'] ?? '');
if ($activeCategory !== '' && !isset($categories[$activeCategory])) {
  $activeCategory = '';
}

$form = [
  'name' => '',
  'email' => '',
  'category' => $activeCategory !== '' ? $activeCategory : 'tablet-help',
  'title' => '',
  'body' => '',
];

$dbError = false;
$posts = [];
$categoryCounts = [];
$totalTopics = 0;

try {
  $dbPath = __DIR__ . '/data/orders.db';
  $dbDir = dirname($dbPath);
  if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
  }

  $db = new PDO('sqlite:' . $dbPath);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  $db->exec("
    CREATE TABLE IF NOT EXISTS forum_posts (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      category_slug TEXT NOT NULL,
      author_name TEXT NOT NULL,
      author_email TEXT,
      title TEXT NOT NULL,
      body TEXT NOT NULL,
      status TEXT NOT NULL DEFAULT 'published',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS idx_forum_posts_category ON forum_posts(category_slug);
    CREATE INDEX IF NOT EXISTS idx_forum_posts_created ON forum_posts(created_at DESC);
  ");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['name'] = cleanForumValue($_POST['name'] ?? '');
    $form['email'] = cleanForumValue($_POST['email'] ?? '');
    $form['category'] = cleanForumValue($_POST['category'] ?? '');
    $form['title'] = cleanForumValue($_POST['title'] ?? '');
    $form['body'] = trim((string)($_POST['body'] ?? ''));

    if ($form['name'] === '') {
      $errors[] = 'Name is required.';
    }
    if ($form['category'] === '' || !isset($categories[$form['category']])) {
      $errors[] = 'Please choose a valid category.';
    }
    if ($form['title'] === '' || mb_strlen($form['title']) < 8) {
      $errors[] = 'Topic title must be at least 8 characters.';
    }
    if ($form['body'] === '' || mb_strlen(trim($form['body'])) < 20) {
      $errors[] = 'Post details must be at least 20 characters.';
    }
    if ($form['email'] !== '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Please enter a valid email address or leave it blank.';
    }

    if (!$errors) {
      $stmt = $db->prepare("
        INSERT INTO forum_posts (category_slug, author_name, author_email, title, body)
        VALUES (?, ?, ?, ?, ?)
      ");
      $stmt->execute([
        $form['category'],
        $form['name'],
        $form['email'] !== '' ? $form['email'] : null,
        $form['title'],
        trim($form['body']),
      ]);

      $redirect = '/forum.php?posted=1';
      if ($form['category'] !== '') {
        $redirect .= '&category=' . rawurlencode($form['category']);
      }
      header('Location: ' . $redirect);
      exit;
    }
  }

  $countStmt = $db->query("
    SELECT category_slug, COUNT(*) AS topic_count
    FROM forum_posts
    WHERE status = 'published'
    GROUP BY category_slug
  ");
  foreach ($countStmt->fetchAll() as $row) {
    $categoryCounts[$row['category_slug']] = (int)$row['topic_count'];
    $totalTopics += (int)$row['topic_count'];
  }

  $sql = "
    SELECT id, category_slug, author_name, title, body, created_at
    FROM forum_posts
    WHERE status = 'published'
  ";
  $params = [];

  if ($activeCategory !== '') {
    $sql .= " AND category_slug = ?";
    $params[] = $activeCategory;
  }

  $sql .= " ORDER BY datetime(created_at) DESC, id DESC LIMIT 24";

  $stmt = $db->prepare($sql);
  $stmt->execute($params);
  $posts = $stmt->fetchAll();
} catch (Exception $e) {
  $dbError = true;
}

function forumExcerpt($text, $limit = 180) {
  $text = preg_replace('/\s+/', ' ', trim((string)$text));
  if (mb_strlen($text) <= $limit) {
    return $text;
  }

  return rtrim(mb_substr($text, 0, $limit - 1)) . '…';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tablet Masters Forum</title>
  <meta name="description" content="Post real tablet questions and discussions on the Tablet Masters Forum for support, buying advice, claims, productivity, and tablet app development." />
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

<section class="forum-hero">
  <div class="forum-hero-inner">
    <div class="forum-hero-copy">
      <div class="section-label">Tablet Masters Community</div>
      <h1 class="forum-title">Post real tablet questions, advice, and support topics.</h1>
      <p class="forum-intro">
        The forum is now live as a working posting board. Customers, buyers, schools, businesses, and developers can start topics,
        organize them by category, and build a searchable stream of tablet-focused discussions.
      </p>
      <div class="forum-hero-actions">
        <a class="btn-primary" href="#forum-post-form">Start a Topic</a>
        <a class="nav-btn-outline" href="#forum-topics">Browse Topics</a>
      </div>
    </div>
    <div class="forum-hero-panel">
      <div class="forum-stat">
        <strong><?= htmlspecialchars((string)$totalTopics) ?></strong>
        <span>Published topics</span>
      </div>
      <div class="forum-stat">
        <strong><?= htmlspecialchars((string)count($categories)) ?></strong>
        <span>Live categories</span>
      </div>
      <div class="forum-stat">
        <strong>Open posting</strong>
        <span>Visitors can create topics right from this page</span>
      </div>
    </div>
  </div>
</section>

<main class="forum-page">
  <?php if ($success): ?>
  <div class="alert alert-success reg-alert forum-alert" role="status">
    Your forum topic has been posted.
  </div>
  <?php endif; ?>

  <?php if ($dbError): ?>
  <div class="alert alert-error reg-alert forum-alert" role="alert">
    The forum is temporarily unavailable. Please try again in a moment.
  </div>
  <?php endif; ?>

  <?php if ($errors): ?>
  <div class="alert alert-error reg-alert forum-alert" role="alert">
    <?= htmlspecialchars(implode(' ', $errors)) ?>
  </div>
  <?php endif; ?>

  <section class="forum-columns forum-columns-top">
    <section class="forum-block" id="forum-post-form">
      <div class="forum-block-head">
        <div>
          <div class="section-label">Create Topic</div>
          <h2 class="forum-block-title">Start a New Discussion</h2>
        </div>
      </div>

      <form method="POST" action="/forum.php<?= $activeCategory !== '' ? '?category=' . urlencode($activeCategory) : '' ?>" class="forum-form">
        <div class="forum-form-grid">
          <label class="forum-field">
            <span>Your name</span>
            <input class="repair-input" type="text" name="name" value="<?= htmlspecialchars($form['name']) ?>" placeholder="Jordan Smith" required />
          </label>
          <label class="forum-field">
            <span>Email address</span>
            <input class="repair-input" type="email" name="email" value="<?= htmlspecialchars($form['email']) ?>" placeholder="Optional" />
          </label>
        </div>

        <label class="forum-field">
          <span>Category</span>
          <select class="repair-input" name="category" required>
            <?php foreach ($categories as $slug => $category): ?>
            <option value="<?= htmlspecialchars($slug) ?>" <?= $form['category'] === $slug ? 'selected' : '' ?>>
              <?= htmlspecialchars($category['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </label>

        <label class="forum-field">
          <span>Topic title</span>
          <input class="repair-input" type="text" name="title" value="<?= htmlspecialchars($form['title']) ?>" placeholder="Example: Galaxy Tab S9 battery draining after the latest update" required />
        </label>

        <label class="forum-field">
          <span>Details</span>
          <textarea class="repair-input forum-textarea" name="body" placeholder="Share the issue, device model, where it was purchased, what you have already tried, and what kind of help you need." required><?= htmlspecialchars($form['body']) ?></textarea>
        </label>

        <div class="forum-form-note">
          Avoid posting serial numbers, billing details, addresses, or claim documents in public topics.
        </div>

        <button type="submit" class="btn-primary">Post Topic</button>
      </form>
    </section>

    <aside class="forum-block">
      <div class="forum-block-head">
        <div>
          <div class="section-label">Posting Rules</div>
          <h2 class="forum-block-title">Keep It Clear</h2>
        </div>
      </div>
      <div class="forum-list-card">
        <ul class="forum-guideline-list">
          <li><i class="fa-solid fa-check"></i><span>Use a clear device-and-problem title so replies can help faster.</span></li>
          <li><i class="fa-solid fa-check"></i><span>Put repair and protection questions in Repairs and Claims.</span></li>
          <li><i class="fa-solid fa-check"></i><span>Do not include private data like serial numbers or payment details.</span></li>
          <li><i class="fa-solid fa-check"></i><span>Keep replies respectful and useful.</span></li>
        </ul>
      </div>
    </aside>
  </section>

  <section class="forum-block" id="forum-categories">
    <div class="forum-block-head">
      <div>
        <div class="section-label">Categories</div>
        <h2 class="forum-block-title">Browse by Topic Area</h2>
      </div>
      <p class="forum-block-copy">Choose a category to focus the topic list, or browse everything together.</p>
    </div>

    <div class="forum-category-grid">
      <a class="forum-card forum-card-link<?= $activeCategory === '' ? ' forum-card-active' : '' ?>" href="/forum.php">
        <div class="forum-card-icon"><i class="fa-solid fa-layer-group"></i></div>
        <div class="forum-card-body">
          <div class="forum-card-head">
            <h3>All Topics</h3>
            <span class="forum-topic-count"><?= htmlspecialchars((string)$totalTopics) ?> topics</span>
          </div>
          <p>See the full stream of forum discussions across every category.</p>
        </div>
      </a>
      <?php foreach ($categories as $slug => $category): ?>
      <a class="forum-card forum-card-link<?= $activeCategory === $slug ? ' forum-card-active' : '' ?>" href="/forum.php?category=<?= urlencode($slug) ?>">
        <div class="forum-card-icon"><i class="fa-solid <?= htmlspecialchars($category['icon']) ?>"></i></div>
        <div class="forum-card-body">
          <div class="forum-card-head">
            <h3><?= htmlspecialchars($category['name']) ?></h3>
            <span class="forum-topic-count"><?= htmlspecialchars((string)($categoryCounts[$slug] ?? 0)) ?> topics</span>
          </div>
          <p><?= htmlspecialchars($category['desc']) ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="forum-block" id="forum-topics">
    <div class="forum-block-head">
      <div>
        <div class="section-label">Recent Activity</div>
        <h2 class="forum-block-title"><?= $activeCategory !== '' ? htmlspecialchars($categories[$activeCategory]['name']) : 'Latest Topics' ?></h2>
      </div>
      <p class="forum-block-copy">Newest topics appear first.</p>
    </div>

    <?php if (!$posts): ?>
    <div class="forum-empty">
      <strong>No topics yet<?= $activeCategory !== '' ? ' in this category' : '' ?>.</strong>
      <p>Use the form above to create the first one.</p>
    </div>
    <?php else: ?>
    <div class="forum-thread-list">
      <?php foreach ($posts as $post): ?>
      <?php $category = $categories[$post['category_slug']] ?? null; ?>
      <article class="forum-thread-card">
        <div class="forum-thread-meta">
          <span class="forum-thread-badge">
            <i class="fa-solid <?= htmlspecialchars($category['icon'] ?? 'fa-comments') ?>"></i>
            <?= htmlspecialchars($category['name'] ?? 'General') ?>
          </span>
          <span><?= htmlspecialchars(date('F j, Y', strtotime($post['created_at']))) ?></span>
          <span>by <?= htmlspecialchars($post['author_name']) ?></span>
        </div>
        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= htmlspecialchars(forumExcerpt($post['body'])) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/sw.js");
}
</script>
</body>
</html>
