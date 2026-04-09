<?php
session_start();

// ── CHANGE THIS PASSWORD ──────────────────────────────────────────────────────
define('ADMIN_PASSWORD', 'tm-admin-2026');
// ─────────────────────────────────────────────────────────────────────────────

define('INVENTORY_FILE', __DIR__ . '/../data/inventory.json');

// Handle login / logout
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: inventory.php');
  exit;
}

if (isset($_POST['password'])) {
  if ($_POST['password'] === ADMIN_PASSWORD) {
    $_SESSION['tm_admin'] = true;
  } else {
    $loginError = 'Incorrect password.';
  }
}

$loggedIn = !empty($_SESSION['tm_admin']);

// Handle flash message from save
$flash = '';
if (isset($_GET['saved'])) $flash = 'success';
if (isset($_GET['error']))  $flash = 'error';

// Load inventory
$products = [];
if (file_exists(INVENTORY_FILE)) {
  $products = json_decode(file_get_contents(INVENTORY_FILE), true) ?: [];
}

// Sort by brand then name for display
usort($products, function($a, $b) {
  $brandOrder = ['Apple' => 0, 'Samsung' => 1, 'Microsoft' => 2, 'Amazon' => 3];
  $ba = $brandOrder[$a['brand']] ?? 9;
  $bb = $brandOrder[$b['brand']] ?? 9;
  return $ba !== $bb ? $ba - $bb : strcmp($a['name'], $b['name']);
});

$totalStock = array_sum(array_column($products, 'stock'));
$lowStock   = count(array_filter($products, fn($p) => $p['stock'] <= 3));
$outOfStock = count(array_filter($products, fn($p) => (int)$p['stock'] === 0));
$brandCounts = [
  'Apple' => count(array_filter($products, fn($p) => ($p['brand'] ?? '') === 'Apple')),
  'Samsung' => count(array_filter($products, fn($p) => ($p['brand'] ?? '') === 'Samsung')),
  'Microsoft' => count(array_filter($products, fn($p) => ($p['brand'] ?? '') === 'Microsoft')),
  'Amazon' => count(array_filter($products, fn($p) => ($p['brand'] ?? '') === 'Amazon')),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inventory Admin — Tablet Masters</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:       #070a10;
      --surface:  #0d1117;
      --card:     #111827;
      --border:   #1e2a3a;
      --blue:     #3b82f6;
      --blue-dim: #1d4ed8;
      --green:    #22c55e;
      --red:      #ef4444;
      --yellow:   #f59e0b;
      --text:     #e2e8f0;
      --muted:    #64748b;
      --mono:     'Courier New', monospace;
      --display:  'Arial Black', sans-serif;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Segoe UI', sans-serif;
      font-size: 14px;
      min-height: 100vh;
    }

    /* ── LOGIN ── */
    .login-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 24px;
    }
    .login-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 48px 40px;
      width: 100%;
      max-width: 380px;
    }
    .login-card h1 {
      font-family: var(--display);
      font-size: 22px;
      letter-spacing: 2px;
      color: #fff;
      margin-bottom: 6px;
    }
    .login-card p {
      color: var(--muted);
      font-size: 13px;
      margin-bottom: 32px;
    }
    .login-card label {
      display: block;
      font-size: 11px;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 8px;
    }
    .login-card input[type="password"] {
      width: 100%;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--text);
      font-size: 15px;
      padding: 10px 14px;
      margin-bottom: 20px;
      outline: none;
    }
    .login-card input[type="password"]:focus { border-color: var(--blue); }
    .login-error {
      background: rgba(239,68,68,0.12);
      border: 1px solid rgba(239,68,68,0.3);
      border-radius: 6px;
      color: var(--red);
      font-size: 13px;
      padding: 10px 14px;
      margin-bottom: 16px;
    }

    /* ── LAYOUT ── */
    .admin-header {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .admin-header-brand {
      font-family: var(--display);
      font-size: 15px;
      letter-spacing: 2px;
      color: #fff;
    }
    .admin-header-brand span { color: var(--blue); }
    .admin-header-actions { display: flex; gap: 12px; align-items: center; }

    .admin-main { padding: 32px; max-width: 1400px; margin: 0 auto; }

    /* ── STATS ── */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 16px;
      margin-bottom: 32px;
    }
    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 20px 24px;
    }
    .stat-label {
      font-size: 11px;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 8px;
    }
    .stat-value {
      font-family: var(--display);
      font-size: 28px;
      color: #fff;
    }
    .stat-value.warn { color: var(--yellow); }
    .stat-value.ok   { color: var(--green); }

    /* ── FLASH ── */
    .flash {
      border-radius: 8px;
      padding: 14px 20px;
      margin-bottom: 24px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .flash.success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); color: var(--green); }
    .flash.error   { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: var(--red); }

    /* ── TOOLBAR ── */
    .toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 20px;
    }
    .toolbar-left { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filter-btn {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--muted);
      cursor: pointer;
      font-size: 13px;
      padding: 7px 16px;
      transition: all .15s;
    }
    .filter-btn:hover, .filter-btn.active {
      background: var(--blue);
      border-color: var(--blue);
      color: #fff;
    }

    /* ── TABLE ── */
    .table-wrap {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      color: var(--muted);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1px;
      padding: 12px 14px;
      text-align: left;
      text-transform: uppercase;
      white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(59,130,246,0.04); }
    tbody tr.row-hidden { display: none; }
    td { padding: 10px 14px; vertical-align: middle; }

    /* editable cells */
    .cell-input {
      background: var(--surface);
      border: 1px solid transparent;
      border-radius: 5px;
      color: var(--text);
      font-size: 13px;
      padding: 5px 8px;
      width: 100%;
      outline: none;
      transition: border-color .15s;
    }
    .cell-input:focus { border-color: var(--blue); }
    .cell-input.stock-input { text-align: center; width: 70px; }
    .cell-input.price-input { width: 90px; }

    select.cell-input { cursor: pointer; }

    .stock-low  { color: var(--red); font-weight: 700; }
    .stock-ok   { color: var(--green); }
    .stock-warn { color: var(--yellow); }

    .badge-pill {
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .5px;
      padding: 2px 8px;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .bp-sale     { background: rgba(239,68,68,0.15);   color: var(--red); }
    .bp-latest   { background: rgba(59,130,246,0.15);  color: var(--blue); }
    .bp-seller   { background: rgba(34,197,94,0.15);   color: var(--green); }
    .bp-prev     { background: rgba(100,116,139,0.15); color: var(--muted); }
    .bp-default  { background: rgba(100,116,139,0.1);  color: var(--muted); }

    .thumb {
      border-radius: 4px;
      height: 36px;
      object-fit: contain;
      width: 36px;
    }

    /* ── BUTTONS ── */
    .btn {
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      padding: 8px 18px;
      transition: opacity .15s, background .15s;
    }
    .btn:hover { opacity: .88; }
    .btn-primary  { background: var(--blue);  color: #fff; }
    .btn-success  { background: var(--green); color: #fff; }
    .btn-danger   { background: transparent; border: 1px solid rgba(239,68,68,0.4); color: var(--red); padding: 5px 10px; font-size: 12px; }
    .btn-danger:hover { background: rgba(239,68,68,0.15); }
    .btn-ghost    { background: var(--card); border: 1px solid var(--border); color: var(--text); }
    .btn-sm       { font-size: 12px; padding: 6px 12px; }

    .save-bar {
      position: fixed;
      bottom: 24px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--blue-dim);
      border: 1px solid var(--blue);
      border-radius: 50px;
      padding: 14px 32px;
      display: none;
      align-items: center;
      gap: 16px;
      z-index: 200;
      box-shadow: 0 8px 32px rgba(0,0,0,0.5);
    }
    .save-bar.visible { display: flex; }
    .save-bar span { color: #fff; font-size: 14px; }

    body {
      background:
        radial-gradient(circle at top right, rgba(59,130,246,0.14), transparent 26%),
        linear-gradient(180deg, #060911 0%, #09101a 100%);
    }
    .login-card,
    .table-wrap,
    .admin-overview-card,
    .inventory-shell {
      box-shadow: 0 18px 48px rgba(0,0,0,0.28);
    }
    .login-card {
      background: linear-gradient(160deg, rgba(59,130,246,0.08) 0%, var(--card) 72%);
      border-radius: 18px;
    }
    .admin-header {
      backdrop-filter: blur(10px);
      background: rgba(10,15,23,0.92);
    }
    .admin-overview {
      display: grid;
      grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.85fr);
      gap: 18px;
      margin-bottom: 20px;
    }
    .admin-overview-card,
    .inventory-shell {
      background: linear-gradient(160deg, rgba(59,130,246,0.06) 0%, var(--card) 72%);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 24px;
    }
    .admin-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 12px;
      color: #93c5fd;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.2px;
      text-transform: uppercase;
    }
    .admin-title {
      margin-bottom: 12px;
      color: #fff;
      font-family: var(--display);
      font-size: clamp(28px, 4vw, 42px);
      line-height: 1;
      letter-spacing: 2px;
    }
    .admin-copy,
    .admin-side-copy,
    .inventory-results-meta {
      color: #b7c4d8;
      line-height: 1.7;
    }
    .admin-chip-row,
    .inventory-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .admin-chip,
    .inventory-legend span,
    .brand-pill,
    .stock-state {
      display: inline-flex;
      align-items: center;
      min-height: 32px;
      padding: 6px 10px;
      border: 1px solid rgba(59,130,246,0.18);
      border-radius: 999px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.8px;
      text-transform: uppercase;
    }
    .admin-chip,
    .inventory-legend span,
    .brand-pill {
      background: rgba(59,130,246,0.1);
      color: #dbeafe;
    }
    .admin-chip-row { margin-top: 18px; }
    .brand-breakdown {
      display: grid;
      gap: 12px;
      margin-top: 18px;
    }
    .brand-breakdown-item {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding-top: 12px;
      border-top: 1px solid rgba(148,163,184,0.14);
    }
    .brand-breakdown-item:first-child {
      border-top: none;
      padding-top: 0;
    }
    .brand-breakdown-item strong {
      color: #fff;
      letter-spacing: 0.4px;
    }
    .brand-breakdown-item span {
      color: #93c5fd;
      font-family: var(--display);
      font-size: 18px;
    }
    .stats-row {
      margin-bottom: 0;
    }
    .stat-card {
      background: #0f1724;
      border: 1px solid rgba(59,130,246,0.12);
      border-radius: 16px;
    }
    .stat-subvalue {
      margin-top: 8px;
      color: var(--muted);
      font-size: 12px;
    }
    .inventory-shell {
      margin-top: 20px;
    }
    .toolbar {
      gap: 18px;
    }
    .toolbar-header {
      display: flex;
      justify-content: space-between;
      align-items: end;
      gap: 16px;
      flex-wrap: wrap;
    }
    .toolbar-copy h2 {
      margin-bottom: 8px;
      color: #fff;
      font-family: var(--display);
      font-size: 28px;
      letter-spacing: 1px;
    }
    .toolbar-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }
    .search-wrap {
      position: relative;
      min-width: min(100%, 320px);
    }
    .search-wrap i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
    }
    .search-input {
      width: 100%;
      min-height: 42px;
      padding: 10px 14px 10px 40px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--surface);
      color: var(--text);
      outline: none;
    }
    .search-input:focus {
      border-color: var(--blue);
    }
    .filter-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .filter-btn {
      border-radius: 999px;
      background: #0f1724;
    }
    .filter-btn.warn.active,
    .filter-btn.warn:hover {
      background: var(--yellow);
      border-color: var(--yellow);
      color: #111827;
    }
    .filter-btn.danger.active,
    .filter-btn.danger:hover {
      background: var(--red);
      border-color: var(--red);
      color: #fff;
    }
    .inventory-results {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      align-items: center;
      margin-bottom: 18px;
      padding-top: 8px;
    }
    .inventory-results-count {
      color: #fff;
      font-family: var(--display);
      font-size: 18px;
      letter-spacing: 1px;
    }
    .table-wrap {
      overflow: auto;
      border-radius: 18px;
    }
    thead th {
      position: sticky;
      top: 0;
      z-index: 2;
      background: rgba(10,15,23,0.96);
    }
    .name-cell {
      min-width: 220px;
    }
    .name-meta {
      display: flex;
      gap: 8px;
      align-items: center;
      margin-bottom: 8px;
    }
    .sku-label {
      color: var(--muted);
      font-size: 11px;
      font-family: var(--mono);
      letter-spacing: 0.8px;
      text-transform: uppercase;
    }
    .thumb {
      height: 44px;
      width: 44px;
      padding: 6px;
      border-radius: 10px;
      border: 1px solid rgba(59,130,246,0.14);
      background: rgba(255,255,255,0.03);
    }
    .thumb-empty {
      display: grid;
      place-items: center;
      height: 44px;
      width: 44px;
      border-radius: 10px;
      border: 1px dashed rgba(148,163,184,0.24);
      background: rgba(255,255,255,0.02);
      color: #93c5fd;
      font-size: 18px;
    }
    .cell-input.url-input {
      min-width: 260px;
    }
    .stock-cell {
      display: grid;
      gap: 8px;
    }
    .stock-state.low {
      background: rgba(239,68,68,0.12);
      border-color: rgba(239,68,68,0.22);
      color: var(--red);
    }
    .stock-state.warn {
      background: rgba(245,158,11,0.12);
      border-color: rgba(245,158,11,0.22);
      color: var(--yellow);
    }
    .stock-state.ok {
      background: rgba(34,197,94,0.12);
      border-color: rgba(34,197,94,0.22);
      color: var(--green);
    }
    .empty-state {
      display: none;
      margin-top: 18px;
      padding: 28px;
      border: 1px dashed rgba(59,130,246,0.22);
      border-radius: 16px;
      text-align: center;
      color: #c7d2e2;
      background: rgba(255,255,255,0.02);
    }
    .empty-state.visible {
      display: block;
    }

    @media (max-width: 900px) {
      .admin-main { padding: 16px; }
      .admin-header { padding: 0 16px; }
      .admin-overview,
      .toolbar-header,
      .inventory-results {
        grid-template-columns: 1fr;
        display: grid;
      }
      thead th:nth-child(6),
      td:nth-child(6) { display: none; }
    }
  </style>
</head>
<body>

<?php if (!$loggedIn): ?>
<!-- ── LOGIN SCREEN ─────────────────────────────────────────────────────────── -->
<div class="login-wrap">
  <div class="login-card">
    <h1>TABLET <span style="color:#3b82f6">MASTERS</span></h1>
    <p>Inventory Admin — enter password to continue</p>
    <?php if (!empty($loginError)): ?>
    <div class="login-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>
    <form method="POST">
      <label for="pw">Password</label>
      <input type="password" id="pw" name="password" autofocus autocomplete="current-password" />
      <button type="submit" class="btn btn-primary" style="width:100%">Sign In</button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── ADMIN PANEL ───────────────────────────────────────────────────────────── -->
<header class="admin-header">
  <div class="admin-header-brand">TABLET <span>MASTERS</span> &mdash; INVENTORY</div>
  <div class="admin-header-actions">
    <a href="../shop.php" target="_blank" class="btn btn-ghost btn-sm"><i class="fas fa-external-link-alt"></i> View Shop</a>
    <form method="POST" style="display:inline">
      <button type="submit" name="logout" class="btn btn-ghost btn-sm">Sign Out</button>
    </form>
  </div>
</header>

<main class="admin-main">

  <?php if ($flash === 'success'): ?>
  <div class="flash success"><i class="fas fa-check-circle"></i> Inventory saved successfully.</div>
  <?php elseif ($flash === 'error'): ?>
  <div class="flash error"><i class="fas fa-exclamation-circle"></i> Save failed — check server file permissions on data/inventory.json.</div>
  <?php endif; ?>

  <section class="admin-overview">
    <div class="admin-overview-card">
      <div class="admin-eyebrow"><i class="fas fa-layer-group"></i> Inventory Control</div>
      <div class="admin-title">Manage the live catalog with less friction.</div>
      <p class="admin-copy">
        This screen is your fast-edit inventory desk for Tablet Masters. Search, filter, spot low-stock items,
        and adjust pricing or product details in one place before saving changes back to the live shop.
      </p>
      <div class="admin-chip-row">
        <span class="admin-chip"><?= count($products) ?> total SKUs</span>
        <span class="admin-chip"><?= $totalStock ?> total units</span>
        <span class="admin-chip"><?= $lowStock ?> low-stock items</span>
        <span class="admin-chip"><?= $outOfStock ?> out of stock</span>
      </div>
    </div>
    <aside class="admin-overview-card">
      <div class="admin-eyebrow"><i class="fas fa-chart-simple"></i> Brand Mix</div>
      <p class="admin-side-copy">Quick SKU counts by brand so you can see inventory concentration before editing.</p>
      <div class="brand-breakdown">
        <?php foreach ($brandCounts as $brand => $count): ?>
        <div class="brand-breakdown-item">
          <strong><?= htmlspecialchars($brand) ?></strong>
          <span><?= $count ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </aside>
  </section>

  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-label">Total SKUs</div>
      <div class="stat-value ok"><?= count($products) ?></div>
      <div class="stat-subvalue">Current catalog entries available to edit</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Units</div>
      <div class="stat-value"><?= $totalStock ?></div>
      <div class="stat-subvalue">Combined available stock across all products</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Low Stock (&le;3)</div>
      <div class="stat-value <?= $lowStock > 0 ? 'warn' : 'ok' ?>"><?= $lowStock ?></div>
      <div class="stat-subvalue">Products that may need attention soon</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Out of Stock</div>
      <div class="stat-value <?= $outOfStock > 0 ? 'warn' : 'ok' ?>"><?= $outOfStock ?></div>
      <div class="stat-subvalue">Items currently unavailable in the shop</div>
    </div>
  </div>

  <section class="inventory-shell">
    <div class="toolbar">
      <div class="toolbar-header">
        <div class="toolbar-copy">
          <h2>Inventory Grid</h2>
          <p class="admin-copy">Filter by brand, stock state, or product name before editing. Changes stay local to this page until you save.</p>
        </div>
        <div class="toolbar-actions">
          <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input id="inventory-search" class="search-input" type="search" placeholder="Search by name, brand, badge, or image path" oninput="applyFilters()" />
          </div>
          <button class="btn btn-ghost btn-sm" type="button" onclick="resetFilters()"><i class="fas fa-rotate-left"></i> Reset Filters</button>
          <button class="btn btn-primary btn-sm" type="button" onclick="addRow()"><i class="fas fa-plus"></i> Add Product</button>
        </div>
      </div>

      <div class="filter-row">
        <button class="filter-btn active brand-filter" onclick="setBrandFilter('All', this)">All</button>
        <button class="filter-btn brand-filter" onclick="setBrandFilter('Apple', this)">Apple</button>
        <button class="filter-btn brand-filter" onclick="setBrandFilter('Samsung', this)">Samsung</button>
        <button class="filter-btn brand-filter" onclick="setBrandFilter('Microsoft', this)">Microsoft</button>
        <button class="filter-btn brand-filter" onclick="setBrandFilter('Amazon', this)">Amazon</button>
      </div>

      <div class="filter-row">
        <button class="filter-btn active stock-filter" onclick="setStockFilter('all', this)">All Stock States</button>
        <button class="filter-btn stock-filter" onclick="setStockFilter('healthy', this)">Healthy</button>
        <button class="filter-btn warn stock-filter" onclick="setStockFilter('low', this)">Low Stock</button>
        <button class="filter-btn danger stock-filter" onclick="setStockFilter('out', this)">Out of Stock</button>
      </div>

      <div class="inventory-legend">
        <span>Inline editing enabled</span>
        <span>Search updates results live</span>
        <span>Save bar appears after any change</span>
      </div>
    </div>

    <div class="inventory-results">
      <div class="inventory-results-count" id="results-count"><?= count($products) ?> products shown</div>
      <div class="inventory-results-meta">Use the filters above to narrow the table without leaving the page.</div>
    </div>

  <!-- Table -->
  <div class="table-wrap">
    <table id="inv-table">
      <thead>
        <tr>
          <th style="width:44px"></th>
          <th>Name</th>
          <th>Brand</th>
          <th style="width:90px">Price</th>
          <th style="width:90px">Was</th>
          <th style="width:120px">Condition</th>
          <th style="width:140px">Badge</th>
          <th style="width:75px">Stock</th>
          <th>Image URL</th>
          <th style="width:60px"></th>
        </tr>
      </thead>
      <tbody id="inv-body">
        <?php foreach ($products as $p):
          $stock = (int)$p['stock'];
          $stockClass = $stock === 0 ? 'stock-low' : ($stock <= 3 ? 'stock-warn' : 'stock-ok');
        ?>
        <tr data-brand="<?= htmlspecialchars($p['brand']) ?>" data-id="<?= (int)$p['id'] ?>">
          <td>
            <?php if (!empty($p['img'])): ?>
            <img class="thumb" src="<?= htmlspecialchars(strpos($p['img'],'http') === 0 ? $p['img'] : '../' . $p['img']) ?>" alt="" onerror="this.style.opacity='.2'">
            <?php else: ?>
            <div class="thumb-empty"><i class="fas fa-tablet-alt"></i></div>
            <?php endif; ?>
          </td>
          <td class="name-cell">
            <div class="name-meta">
              <span class="sku-label">ID <?= (int)$p['id'] ?></span>
              <span class="brand-pill"><?= htmlspecialchars($p['brand']) ?></span>
            </div>
            <input class="cell-input" type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" oninput="markDirty(); applyFilters()" />
          </td>
          <td>
            <select class="cell-input" name="brand" onchange="markDirty(); this.closest('tr').dataset.brand=this.value; updateBrandPill(this.closest('tr')); applyFilters()">
              <?php foreach (['Apple','Samsung','Microsoft','Amazon'] as $b): ?>
              <option value="<?= $b ?>" <?= $p['brand'] === $b ? 'selected' : '' ?>><?= $b ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><input class="cell-input price-input" type="number" name="price" value="<?= $p['price'] ?>" min="0" step="1" oninput="markDirty()" /></td>
          <td><input class="cell-input price-input" type="number" name="orig" value="<?= $p['orig'] ?? '' ?>" min="0" step="1" placeholder="—" oninput="markDirty()" /></td>
          <td>
            <select class="cell-input" name="condition" onchange="markDirty()">
              <?php foreach (['Like New','Grade A','Grade B'] as $c): ?>
              <option value="<?= $c ?>" <?= $p['condition'] === $c ? 'selected' : '' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <select class="cell-input" name="badge" onchange="markDirty(); applyFilters()">
              <?php foreach (['','Latest','Sale','Best Seller','Previous Gen'] as $bg): ?>
              <option value="<?= $bg ?>" <?= ($p['badge'] ?? '') === $bg ? 'selected' : '' ?>><?= $bg ?: '—' ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td class="stock-cell">
            <input class="cell-input stock-input <?= $stockClass ?>" type="number" name="stock"
              value="<?= $stock ?>" min="0" step="1"
              oninput="markDirty(); updateStockColor(this); applyFilters()" />
            <div class="stock-state <?= $stock === 0 ? 'low' : ($stock <= 3 ? 'warn' : 'ok') ?>" data-stock-indicator>
              <?= $stock === 0 ? 'Out' : ($stock <= 3 ? 'Low' : 'Healthy') ?>
            </div>
          </td>
          <td><input class="cell-input url-input" type="text" name="img" value="<?= htmlspecialchars($p['img'] ?? '') ?>" placeholder="assets/images/..." oninput="markDirty(); updateThumb(this); applyFilters()" /></td>
          <td>
            <button class="btn btn-danger" type="button" onclick="deleteRow(this)" title="Remove"><i class="fas fa-trash-alt"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="empty-state" id="inventory-empty-state">No products match the current search or filter combination.</div>
  </section>

  <!-- Hidden form for save -->
  <form id="save-form" method="POST" action="save-inventory.php">
    <input type="hidden" id="save-payload" name="inventory" />
  </form>

</main>

<!-- Sticky save bar -->
<div class="save-bar" id="save-bar">
  <span><i class="fas fa-circle" style="color:#f59e0b;font-size:8px"></i> Unsaved changes</span>
  <button class="btn btn-success" onclick="saveInventory()"><i class="fas fa-save"></i> Save Inventory</button>
  <button class="btn btn-ghost btn-sm" onclick="location.reload()">Discard</button>
</div>

<script>
var dirty = false;
var currentBrand = 'All';
var currentStock = 'all';

function markDirty() {
  dirty = true;
  document.getElementById('save-bar').classList.add('visible');
}

function updateStockColor(input) {
  var v = parseInt(input.value) || 0;
  input.className = input.className.replace(/stock-\w+/g, '');
  input.classList.add('cell-input', 'stock-input');
  if (v === 0)      input.classList.add('stock-low');
  else if (v <= 3)  input.classList.add('stock-warn');
  else              input.classList.add('stock-ok');

  var indicator = input.closest('td').querySelector('[data-stock-indicator]');
  if (!indicator) return;

  indicator.className = 'stock-state';
  if (v === 0) {
    indicator.classList.add('low');
    indicator.textContent = 'Out';
  } else if (v <= 3) {
    indicator.classList.add('warn');
    indicator.textContent = 'Low';
  } else {
    indicator.classList.add('ok');
    indicator.textContent = 'Healthy';
  }
}

function applyFilters() {
  var searchField = document.getElementById('inventory-search');
  var query = searchField ? searchField.value.trim().toLowerCase() : '';
  var visible = 0;

  document.querySelectorAll('#inv-body tr').forEach(function(row) {
    var brandMatch = currentBrand === 'All' || row.dataset.brand === currentBrand;
    var stock = parseInt(row.querySelector('[name="stock"]').value, 10) || 0;
    var stockMatch = currentStock === 'all'
      || (currentStock === 'healthy' && stock > 3)
      || (currentStock === 'low' && stock > 0 && stock <= 3)
      || (currentStock === 'out' && stock === 0);
    var haystack = [
      row.querySelector('[name="name"]').value || '',
      row.querySelector('[name="img"]').value || '',
      row.querySelector('[name="badge"]').value || '',
      row.dataset.brand || ''
    ].join(' ').toLowerCase();
    var searchMatch = query === '' || haystack.indexOf(query) !== -1;
    var hidden = !(brandMatch && stockMatch && searchMatch);
    row.classList.toggle('row-hidden', hidden);
    if (!hidden) visible++;
  });

  var resultsCount = document.getElementById('results-count');
  if (resultsCount) {
    resultsCount.textContent = visible + (visible === 1 ? ' product shown' : ' products shown');
  }

  var emptyState = document.getElementById('inventory-empty-state');
  if (emptyState) {
    emptyState.classList.toggle('visible', visible === 0);
  }
}

function setBrandFilter(brand, btn) {
  currentBrand = brand;
  document.querySelectorAll('.brand-filter').forEach(function(button) {
    button.classList.remove('active');
  });
  if (btn) btn.classList.add('active');
  applyFilters();
}

function setStockFilter(mode, btn) {
  currentStock = mode;
  document.querySelectorAll('.stock-filter').forEach(function(button) {
    button.classList.remove('active');
  });
  if (btn) btn.classList.add('active');
  applyFilters();
}

function resetFilters() {
  currentBrand = 'All';
  currentStock = 'all';
  var searchField = document.getElementById('inventory-search');
  if (searchField) searchField.value = '';
  document.querySelectorAll('.brand-filter').forEach(function(button, index) {
    button.classList.toggle('active', index === 0);
  });
  document.querySelectorAll('.stock-filter').forEach(function(button, index) {
    button.classList.toggle('active', index === 0);
  });
  applyFilters();
}

function filterBrand(brand, btn) {
  setBrandFilter(brand, btn);
}

function updateBrandPill(row) {
  var pill = row.querySelector('.brand-pill');
  var brand = row.querySelector('[name="brand"]').value || 'Apple';
  if (pill) {
    pill.textContent = brand;
  }
}

function updateThumb(input) {
  var row = input.closest('tr');
  if (!row) return;

  var cell = row.cells[0];
  if (!cell) return;

  var value = (input.value || '').trim();
  var src = value && value.indexOf('http') === 0 ? value : (value ? '../' + value : '');
  var img = cell.querySelector('.thumb');

  if (!value) {
    cell.innerHTML = '<div class="thumb-empty"><i class="fas fa-tablet-alt"></i></div>';
    return;
  }

  if (!img) {
    cell.innerHTML = '<img class="thumb" alt="">';
    img = cell.querySelector('.thumb');
  }

  img.style.opacity = '1';
  img.src = src;
  img.onerror = function() {
    this.style.opacity = '.2';
  };
}

function addRow() {
  var nextId = Date.now();
  var tbody = document.getElementById('inv-body');
  var tr = document.createElement('tr');
  tr.dataset.brand = 'Apple';
  tr.dataset.id = nextId;
  tr.innerHTML = `
    <td><div class="thumb-empty"><i class="fas fa-tablet-alt"></i></div></td>
    <td class="name-cell">
      <div class="name-meta">
        <span class="sku-label">ID ${nextId}</span>
        <span class="brand-pill">Apple</span>
      </div>
      <input class="cell-input" type="text" name="name" value="New Tablet" oninput="markDirty(); applyFilters()" />
    </td>
    <td>
      <select class="cell-input" name="brand" onchange="markDirty(); this.closest('tr').dataset.brand=this.value; updateBrandPill(this.closest('tr')); applyFilters()">
        <option>Apple</option><option>Samsung</option><option>Microsoft</option><option>Amazon</option>
      </select>
    </td>
    <td><input class="cell-input price-input" type="number" name="price" value="0" min="0" step="1" oninput="markDirty()" /></td>
    <td><input class="cell-input price-input" type="number" name="orig" value="" min="0" step="1" placeholder="—" oninput="markDirty()" /></td>
    <td>
      <select class="cell-input" name="condition" onchange="markDirty()">
        <option>Like New</option><option>Grade A</option><option>Grade B</option>
      </select>
    </td>
    <td>
      <select class="cell-input" name="badge" onchange="markDirty(); applyFilters()">
        <option value="">—</option><option>Latest</option><option>Sale</option><option>Best Seller</option><option>Previous Gen</option>
      </select>
    </td>
    <td class="stock-cell">
      <input class="cell-input stock-input stock-low" type="number" name="stock" value="0" min="0" step="1" oninput="markDirty(); updateStockColor(this); applyFilters()" />
      <div class="stock-state low" data-stock-indicator>Out</div>
    </td>
    <td><input class="cell-input url-input" type="text" name="img" value="" placeholder="assets/images/..." oninput="markDirty(); updateThumb(this); applyFilters()" /></td>
    <td><button class="btn btn-danger" type="button" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></button></td>
  `;
  tbody.appendChild(tr);
  tr.querySelector('input[name="name"]').focus();
  markDirty();
  applyFilters();
}

function deleteRow(btn) {
  if (!confirm('Remove this product from inventory?')) return;
  btn.closest('tr').remove();
  markDirty();
  applyFilters();
}

function brandToEmoji(brand) {
  var map = { Apple: 'iPad', Samsung: 'Tab', Microsoft: 'Surface', Amazon: 'Fire' };
  return map[brand] || 'Tab';
}

function saveInventory() {
  var rows = document.querySelectorAll('#inv-body tr:not(.row-hidden)');
  // collect ALL rows, not just visible
  var allRows = document.querySelectorAll('#inv-body tr');
  var products = [];
  var id = 1;

  allRows.forEach(function(tr) {
    var get = function(n) { return tr.querySelector('[name="' + n + '"]'); };
    var name   = get('name')  ? get('name').value.trim()   : '';
    var brand  = get('brand') ? get('brand').value         : 'Apple';
    var price  = get('price') ? parseFloat(get('price').value) || 0 : 0;
    var orig   = get('orig')  && get('orig').value.trim() !== '' ? parseFloat(get('orig').value) : null;
    var cond   = get('condition') ? get('condition').value : 'Grade A';
    var badge  = get('badge') ? get('badge').value         : '';
    var stock  = get('stock') ? parseInt(get('stock').value) || 0 : 0;
    var img    = get('img')   ? get('img').value.trim()    : '';

    if (!name) return;

    products.push({
      id:        parseInt(tr.dataset.id) || id,
      name:      name,
      brand:     brand,
      price:     price,
      orig:      orig,
      emoji:     brandToEmoji(brand),
      badge:     badge,
      condition: cond,
      stock:     stock,
      img:       img,
      imgClass:  ''
    });
    id++;
  });

  document.getElementById('save-payload').value = JSON.stringify(products);
  document.getElementById('save-form').submit();
}

window.addEventListener('beforeunload', function(e) {
  if (dirty) { e.preventDefault(); e.returnValue = ''; }
});
applyFilters();
</script>

<?php endif; ?>
</body>
</html>
