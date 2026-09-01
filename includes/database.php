<?php

function getDB(): PDO
{
    static $db = null;

    if ($db instanceof PDO) {
        return $db;
    }

    $dataDir = dirname(__DIR__) . '/data';
    if (!is_dir($dataDir) && !mkdir($dataDir, 0755, true) && !is_dir($dataDir)) {
        throw new RuntimeException('Unable to create data directory.');
    }

    $htaccess = $dataDir . '/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Require all denied\n");
    }

    $db = new PDO('sqlite:' . $dataDir . '/tablet-masters.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->exec('PRAGMA foreign_keys = ON');

    tmEnsureDatabaseSchema($db);

    return $db;
}

function tmEnsureDatabaseSchema(PDO $db): void
{
    $db->exec("
        CREATE TABLE IF NOT EXISTS devices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            registration_number TEXT NOT NULL UNIQUE,
            customer_email TEXT NOT NULL,
            customer_name TEXT NOT NULL,
            customer_phone TEXT,
            device_brand TEXT NOT NULL,
            device_model TEXT NOT NULL,
            serial_number TEXT NOT NULL UNIQUE,
            purchase_date TEXT,
            purchase_source TEXT NOT NULL DEFAULT 'tablet-masters',
            order_id TEXT,
            plan TEXT NOT NULL DEFAULT 'none',
            plan_status TEXT NOT NULL DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $db->exec("
        CREATE INDEX IF NOT EXISTS idx_devices_customer_email
        ON devices(customer_email)
    ");
}
