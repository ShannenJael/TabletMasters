<?php
require_once __DIR__ . '/bootstrap.php';

$auth = tmAdminHandleAuth('index.php', true);
$loggedIn = $auth['loggedIn'];
$adminAuthenticated = $auth['isAuthenticated'];

$resources = [
    [
        'title' => 'Orders Dashboard',
        'href' => 'orders.php',
        'icon' => 'fa-chart-line',
        'reason' => 'Review completed purchases, subscriptions, customer details, and revenue activity in one place.',
        'label' => 'Open Orders',
    ],
    [
        'title' => 'Inventory Dashboard',
        'href' => 'inventory.php',
        'icon' => 'fa-boxes-stacked',
        'reason' => 'Update stock counts, pricing, product details, and catalog visibility before changes reach the live shop.',
        'label' => 'Open Inventory',
    ],
    [
        'title' => 'Bluehost Web Host',
        'href' => 'https://www.bluehost.com/my-account/login',
        'icon' => 'fa-server',
        'reason' => 'Access hosting, domains, file management, email, and account-level controls outside the app itself.',
        'label' => 'Open Bluehost',
        'external' => true,
    ],
];

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Management Console - Tablet Masters</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #070a10;
      --surface: #0d1117;
      --card: #111827;
      --border: #1e2a3a;
      --blue: #3b82f6;
      --blue-dim: #1d4ed8;
      --text: #e2e8f0;
      --muted: #8aa0ba;
      --display: 'Arial Black', sans-serif;
    }
    body {
      min-height: 100vh;
      background:
        radial-gradient(circle at top left, rgba(59, 130, 246, 0.18), transparent 30%),
        linear-gradient(180deg, #060911 0%, #0a111b 100%);
      color: var(--text);
      font-family: 'Segoe UI', sans-serif;
    }
    a { color: inherit; text-decoration: none; }
    .login-wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .login-card,
    .console-hero,
    .console-card {
      background: rgba(17, 24, 39, 0.96);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: 0 18px 48px rgba(0, 0, 0, 0.28);
    }
    .login-card {
      width: 100%;
      max-width: 380px;
      padding: 40px;
    }
    .login-card h1 {
      font-family: var(--display);
      font-size: 22px;
      letter-spacing: 2px;
      margin-bottom: 8px;
    }
    .login-card p {
      color: var(--muted);
      margin-bottom: 28px;
    }
    .login-card label {
      display: block;
      margin-bottom: 8px;
      color: var(--muted);
      font-size: 11px;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .login-error {
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 18px;
      font-size: 14px;
      background: rgba(239, 68, 68, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.28);
      color: #fca5a5;
    }
    input[type="password"] {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: #0b1220;
      color: var(--text);
      padding: 12px 14px;
      outline: none;
      margin-bottom: 18px;
    }
    input[type="password"]:focus {
      border-color: var(--blue);
    }
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      border-radius: 999px;
      border: 1px solid var(--border);
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
    }
    .btn:hover { transform: translateY(-1px); }
    .btn-primary {
      background: linear-gradient(135deg, var(--blue), var(--blue-dim));
      border-color: transparent;
      color: #fff;
    }
    .btn-ghost {
      background: transparent;
      color: var(--text);
    }
    .admin-header {
      position: sticky;
      top: 0;
      z-index: 10;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      padding: 16px 24px;
      border-bottom: 1px solid rgba(30, 42, 58, 0.8);
      background: rgba(7, 10, 16, 0.9);
      backdrop-filter: blur(12px);
    }
    .admin-brand {
      font-family: var(--display);
      letter-spacing: 2px;
      font-size: 15px;
    }
    .admin-brand span { color: var(--blue); }
    .admin-nav,
    .admin-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .nav-link {
      color: var(--muted);
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 13px;
      border: 1px solid transparent;
    }
    .nav-link.active,
    .nav-link:hover {
      color: #fff;
      border-color: rgba(59, 130, 246, 0.3);
      background: rgba(59, 130, 246, 0.14);
    }
    .admin-main {
      max-width: 1180px;
      margin: 0 auto;
      padding: 32px 24px 48px;
    }
    .console-hero {
      padding: 28px;
      margin-bottom: 22px;
    }
    .console-eyebrow {
      color: #93c5fd;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 11px;
      margin-bottom: 12px;
    }
    .console-title {
      font-family: var(--display);
      font-size: clamp(30px, 5vw, 46px);
      line-height: 1;
      margin-bottom: 14px;
    }
    .console-copy {
      color: #cbd5e1;
      max-width: 760px;
      line-height: 1.7;
    }
    .console-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 18px;
    }
    .console-card {
      padding: 22px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      min-height: 260px;
    }
    .card-icon {
      width: 52px;
      height: 52px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 16px;
      background: rgba(59, 130, 246, 0.14);
      border: 1px solid rgba(59, 130, 246, 0.24);
      color: #bfdbfe;
      font-size: 20px;
    }
    .card-title {
      font-size: 22px;
      font-weight: 700;
      color: #fff;
    }
    .card-reason {
      color: #cbd5e1;
      line-height: 1.7;
      flex: 1;
    }
    .card-meta {
      color: var(--muted);
      font-size: 12px;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    @media (max-width: 980px) {
      .console-grid {
        grid-template-columns: 1fr;
      }
    }
    @media (max-width: 720px) {
      .admin-header {
        padding: 14px 16px;
      }
      .admin-main {
        padding: 20px 16px 40px;
      }
      .console-hero,
      .console-card,
      .login-card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header class="admin-header">
  <div class="admin-brand">TABLET <span>MASTERS</span> ADMIN</div>
  <nav class="admin-nav">
    <a class="nav-link active" href="index.php">Management Console</a>
    <a class="nav-link" href="orders.php">Orders</a>
    <a class="nav-link" href="inventory.php">Inventory</a>
  </nav>
  <div class="admin-actions">
    <?php if ($adminAuthenticated): ?>
    <form method="POST" style="display:inline">
      <button type="submit" name="logout" class="btn btn-ghost">Sign Out</button>
    </form>
    <?php endif; ?>
  </div>
</header>

<main class="admin-main">
  <section class="console-hero">
    <div class="console-eyebrow"><i class="fas fa-sitemap"></i> Control Center</div>
    <div class="console-title">Management Console</div>
    <p class="console-copy">
      Use this page as the admin starting point. Each link below explains why you would open it, so it is easier
      to jump to the right place for order review, inventory edits, or hosting tasks.
    </p>
  </section>

  <section class="console-grid">
    <?php foreach ($resources as $resource): ?>
    <article class="console-card">
      <div class="card-icon"><i class="fas <?= h($resource['icon']) ?>"></i></div>
      <div class="card-meta"><?= !empty($resource['external']) ? 'External Access' : 'Admin Tool' ?></div>
      <div class="card-title"><?= h($resource['title']) ?></div>
      <p class="card-reason"><?= h($resource['reason']) ?></p>
      <a
        class="btn btn-primary"
        href="<?= h($resource['href']) ?>"
        <?= !empty($resource['external']) ? 'target="_blank" rel="noreferrer"' : '' ?>
      >
        <?= h($resource['label']) ?>
        <?php if (!empty($resource['external'])): ?>
        <i class="fas fa-arrow-up-right-from-square"></i>
        <?php endif; ?>
      </a>
    </article>
    <?php endforeach; ?>
  </section>
</main>
</body>
</html>
