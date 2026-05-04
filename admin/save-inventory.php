<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../includes/accessories-data.php';
tmAdminRequireLogin('inventory.php');

define('INVENTORY_FILE', __DIR__ . '/../data/inventory.json');

$raw = $_POST['inventory'] ?? '';

if (!$raw) {
  header('Location: inventory.php?error=1');
  exit;
}

$data = json_decode($raw, true);

if (!is_array($data)) {
  header('Location: inventory.php?error=1');
  exit;
}

// Sanitize each product
$clean = [];
$usedIds = [];

foreach ($data as $item) {
  if (empty($item['name'])) continue;

  // Ensure unique integer ID
  $id = isset($item['id']) ? (int)$item['id'] : 0;
  if ($id <= 0 || in_array($id, $usedIds)) {
    $id = !empty($usedIds) ? max($usedIds) + 1 : 1;
  }
  $usedIds[] = $id;

  $brandMap = ['Apple' => 'iPad', 'Samsung' => 'Tab', 'Microsoft' => 'Surface', 'Amazon' => 'Fire'];
  $brand    = in_array($item['brand'] ?? '', array_keys($brandMap)) ? $item['brand'] : 'Apple';
  $productType = strtolower(trim((string)($item['productType'] ?? 'tablet')));
  if (!in_array($productType, ['tablet', 'case', 'screen_cover'], true)) {
    $productType = 'tablet';
  }
  $compatibleKey = trim((string)($item['compatibleKey'] ?? ''));
  $emojiMap = [
    'tablet' => $brandMap[$brand],
    'case' => 'Case',
    'screen_cover' => 'Shield',
  ];

  $clean[] = [
    'id'        => $id,
    'name'      => trim($item['name']),
    'brand'     => $brand,
    'productType' => $productType,
    'compatibleKey' => $productType === 'tablet' ? '' : $compatibleKey,
    'price'     => max(0, (float)($item['price'] ?? 0)),
    'orig'      => isset($item['orig']) && $item['orig'] !== null && $item['orig'] !== '' ? max(0, (float)$item['orig']) : null,
    'emoji'     => $emojiMap[$productType],
    'badge'     => trim($item['badge'] ?? ''),
    'condition' => in_array($item['condition'] ?? '', ['Like New','Grade A','Grade B']) ? $item['condition'] : 'Grade A',
    'stock'     => max(0, (int)($item['stock'] ?? 0)),
    'img'       => trim($item['img'] ?? ''),
    'imgClass'  => trim($item['imgClass'] ?? ''),
  ];
}

$json = json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

if (file_put_contents(INVENTORY_FILE, $json) === false) {
  header('Location: inventory.php?error=1');
  exit;
}

header('Location: inventory.php?saved=1');
exit;
