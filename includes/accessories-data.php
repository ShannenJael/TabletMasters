<?php

function tm_load_inventory(): array {
    $inventoryFile = __DIR__ . '/../data/inventory.json';
    if (!file_exists($inventoryFile)) {
        return [];
    }

    $inventory = json_decode(file_get_contents($inventoryFile), true);
    return is_array($inventory) ? $inventory : [];
}

function tm_accessory_base_name(string $tabletName): string {
    $name = preg_replace('/\s+Wi-Fi \+ Cellular$/i', '', trim($tabletName));
    return trim((string)$name);
}

function tm_accessory_slug(string $tabletName): string {
    $slug = strtolower(str_replace('+', ' Plus ', tm_accessory_base_name($tabletName)));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim((string)$slug, '-');
}

function tm_accessory_placeholder_image(string $key): string {
    if ($key === 'fire-hd-10-2023') {
        return 'assets/images/Fire HD 10 (2025) 1.png';
    }
    if ($key === 'fire-hd-10-plus') {
        return 'assets/images/Fire HD 10 (2025).png';
    }
    if ($key === 'fire-hd-7') {
        return 'assets/images/Fire 7 (2024).png';
    }
    if ($key === 'fire-hd-8-2022') {
        return 'assets/images/Fire HD 8.png';
    }
    if ($key === 'fire-max-11') {
        return 'assets/images/Fire Max 11.png';
    }
    if ($key === 'surface-go-3') {
        return 'assets/images/Surface Go 3.png';
    }
    if ($key === 'surface-go-4') {
        return 'assets/images/Surface Go 4.png';
    }
    if ($key === 'surface-pro-11') {
        return 'assets/images/Surface Pro 11.png';
    }
    if ($key === 'surface-pro-7') {
        return 'assets/images/Surface Pro 7.png';
    }
    if ($key === 'surface-pro-8') {
        return 'assets/images/Surface Pro 8.png';
    }
    if ($key === 'surface-pro-9') {
        return 'assets/images/Surface Pro 9.png';
    }
    if ($key === 'surface-pro-x') {
        return 'assets/images/Surface Pro 10 3-Photoroom.png';
    }
    if ($key === 'galaxy-tab-a8') {
        return 'assets/images/Galaxy Tab A8.png';
    }
    if ($key === 'galaxy-tab-s6-lite') {
        return 'assets/images/Galaxy Tab S6 Lite.png';
    }
    if ($key === 'galaxy-tab-s7') {
        return 'assets/images/Galaxy Tab S7.png';
    }
    if ($key === 'galaxy-tab-s7-fe') {
        return 'assets/images/Galaxy Tab S7 FE.png';
    }
    if ($key === 'galaxy-tab-s8') {
        return 'assets/images/Galaxy Tab S8.png';
    }
    if ($key === 'galaxy-tab-s8-ultra') {
        return 'assets/images/Galaxy Tab S8 Ultra.png';
    }
    if ($key === 'galaxy-tab-s9-fe') {
        return 'assets/images/Galaxy Tab S9 FE.png';
    }
    if ($key === 'galaxy-tab-s10-plus') {
        return 'assets/images/Galaxy Tab S10+.png';
    }
    if ($key === 'galaxy-tab-s10-fe') {
        return 'assets/images/Galaxy Tab S10 FE.png';
    }
    if ($key === 'galaxy-tab-s10-ultra') {
        return 'assets/images/Galaxy Tab S10 Ultra.png';
    }
    if ($key === 'galaxy-tab-s9-ultra') {
        return 'assets/images/Galaxy Tab S9 Ultra.png';
    }
    if ($key === 'galaxy-tab-s9-plus') {
        return 'assets/images/Galaxy Tab S9+.png';
    }
    if ($key === 'galaxy-tab-s9') {
        return 'assets/images/Galaxy Tab S9.png';
    }
    if ($key === 'galaxy-tab-s10') {
        return 'assets/images/Galaxy Tab S10 Lite.png';
    }
    if ($key === 'galaxy-tab-s10-lite') {
        return 'assets/images/Galaxy Tab S10 Lite.png';
    }
    if ($key === 'galaxy-tab-s7-plus') {
        return 'assets/images/Galaxy Tab S7+.png';
    }
    if ($key === 'ipad-9th-gen') {
        return 'assets/images/iPad 9th Gen.png';
    }
    if ($key === 'ipad-10th-gen-a14-2022' || $key === 'ipad-10th-gen') {
        return 'assets/images/iPad 10th Gen.png';
    }
    if ($key === 'ipad-air-4th-gen') {
        return 'assets/images/iPad Air 4th Gen.png';
    }
    if ($key === 'ipad-air-11-inch-m2-2024') {
        return 'assets/images/iPad Air M2 11.png';
    }
    if ($key === 'ipad-air-13-inch-m2-2024') {
        return 'assets/images/iPad Air M2 13.png';
    }
    if ($key === 'ipad-mini-7th-gen-a17-pro-2024' || $key === 'ipad-mini-7') {
        return 'assets/images/iPad Mini 7.png';
    }
    if ($key === 'ipad-pro-11-inch-m4-2024') {
        return 'assets/images/iPad Pro M4 11.png';
    }
    if ($key === 'ipad-pro-13-inch-m4-2024') {
        return 'assets/images/iPad Pro M4 13.png';
    }

    return 'assets/images/accessories/' . $key . '-bundle-placeholder.svg';
}

function tm_accessory_case_id(string $key): string {
    return 'acc-case-' . $key;
}

function tm_accessory_screen_id(string $key): string {
    return 'acc-screen-' . $key;
}

function tm_accessory_case_price(array $product): int {
    $price = (float)($product['price'] ?? 0);
    $name = (string)($product['name'] ?? '');

    if ($price >= 1000 || preg_match('/13-inch|Ultra|12\.9|Pro 11/i', $name)) {
        return 69;
    }
    if ($price >= 500 || preg_match('/11-inch|Air|Plus|\+|Surface Pro|Max 11/i', $name)) {
        return 54;
    }
    return 39;
}

function tm_accessory_screen_price(array $product): int {
    $casePrice = tm_accessory_case_price($product);
    return max(19, $casePrice - 20);
}

function tm_accessory_case_family(array $product): string {
    $brand = (string)($product['brand'] ?? '');
    $name = (string)($product['name'] ?? '');

    if ($brand === 'Apple') {
        return stripos($name, 'Pro') !== false ? 'Magnetic folio shell' : 'Slim travel folio';
    }
    if ($brand === 'Samsung') {
        return stripos($name, 'Ultra') !== false ? 'Rugged stand case' : 'Armor folio case';
    }
    if ($brand === 'Microsoft') {
        return 'Work-ready hard shell';
    }
    if ($brand === 'Amazon') {
        return 'Kid-safe bumper case';
    }

    return 'Protective tablet case';
}

function tm_build_accessory_catalog(?array $inventory = null): array {
    $products = $inventory ?? tm_load_inventory();
    $catalog = [];

    foreach ($products as $product) {
        $name = (string)($product['name'] ?? '');
        $brand = (string)($product['brand'] ?? '');
        if ($name === '' || $brand === '') {
            continue;
        }

        $baseName = tm_accessory_base_name($name);
        $key = tm_accessory_slug($baseName);

        if (!isset($catalog[$key])) {
            $catalog[$key] = [
                'key' => $key,
                'tablet_name' => $baseName,
                'brand' => $brand,
                'placeholder_image' => tm_accessory_placeholder_image($key),
                'tablet_image' => $product['img'] ?? '',
                'tablet_image_class' => $product['imgClass'] ?? '',
                'case_name' => $baseName . ' Protective Case',
                'screen_name' => $baseName . ' Screen Cover',
                'case_price' => tm_accessory_case_price($product),
                'screen_price' => tm_accessory_screen_price($product),
                'case_family' => tm_accessory_case_family($product),
                'compatibility' => [],
                'tags' => [
                    $brand,
                    stripos($baseName, 'Pro') !== false ? 'Pro Fit' : 'Daily Carry',
                    stripos($baseName, 'Cellular') !== false ? 'Cellular Friendly' : 'Wi-Fi Fit',
                ],
                'case_features' => [
                    'Raised edges help protect the screen and camera area',
                    'Designed for clean access to charging, speakers, and buttons',
                    'Built to match the tablet family listed on this card',
                ],
                'screen_features' => [
                    'Designed for the listed tablet family',
                    'Helps reduce scratches during everyday use',
                ],
            ];
        }

        $catalog[$key]['compatibility'][] = $name;
    }

    foreach ($catalog as &$entry) {
        $entry['compatibility'] = array_values(array_unique($entry['compatibility']));
    }
    unset($entry);

    uasort($catalog, function (array $a, array $b): int {
        return [$a['brand'], $a['tablet_name']] <=> [$b['brand'], $b['tablet_name']];
    });

    return array_values($catalog);
}

function tm_build_accessory_products(?array $catalog = null): array {
    $catalog = $catalog ?? tm_build_accessory_catalog();
    $products = [];

    foreach ($catalog as $entry) {
        $products[] = [
            'id' => tm_accessory_case_id($entry['key']),
            'name' => $entry['case_name'],
            'brand' => $entry['brand'],
            'price' => $entry['case_price'],
            'emoji' => 'Case',
            'img' => $entry['placeholder_image'],
            'type' => 'accessory',
            'accessory_kind' => 'case',
            'tablet_name' => $entry['tablet_name'],
        ];

        $products[] = [
            'id' => tm_accessory_screen_id($entry['key']),
            'name' => $entry['screen_name'],
            'brand' => $entry['brand'],
            'price' => $entry['screen_price'],
            'emoji' => 'Shield',
            'img' => $entry['placeholder_image'],
            'type' => 'accessory',
            'accessory_kind' => 'screen',
            'tablet_name' => $entry['tablet_name'],
        ];
    }

    return $products;
}

function tm_find_accessories_for_tablets(array $tabletNames, ?array $catalog = null): array {
    $catalog = $catalog ?? tm_build_accessory_catalog();
    $normalizedNames = array_map('tm_accessory_base_name', $tabletNames);
    $normalizedNames = array_values(array_unique(array_filter($normalizedNames)));
    $matches = [];

    foreach ($catalog as $entry) {
        if (in_array($entry['tablet_name'], $normalizedNames, true)) {
            $matches[] = $entry;
        }
    }

    return $matches;
}
