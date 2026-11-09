<?php

$configFile = __DIR__ . '/../includes/config.php';
if (is_file($configFile)) require_once $configFile;
header('Cache-Control: private, no-store');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax',
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
}

function tmAdminCredentials(): array {
    return [
        'hash' => defined('TM_ADMIN_PASSWORD_HASH') ? (string)TM_ADMIN_PASSWORD_HASH : (string)(getenv('TM_ADMIN_PASSWORD_HASH') ?: ''),
        'password' => defined('ADMIN_PASSWORD') ? (string)ADMIN_PASSWORD : (string)(getenv('TM_ADMIN_PASSWORD') ?: ''),
    ];
}

function tmAdminCredentialVersion(): string {
    $credentials = tmAdminCredentials();
    return hash('sha256', $credentials['hash'] ?: $credentials['password']);
}

function tmAdminHandleAuth(string $redirectPath, bool $allowPublic = false): array
{
    $loginError = '';
    $isAuthenticated = !empty($_SESSION['tm_admin'])
        && hash_equals(tmAdminCredentialVersion(), (string)($_SESSION['tm_admin_version'] ?? ''));

    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: ' . $redirectPath);
        exit;
    }

    if (isset($_POST['password'])) {
        $credentials = tmAdminCredentials();
        $password = is_string($_POST['password']) ? $_POST['password'] : '';
        $valid = $password !== '' && ($credentials['hash'] !== ''
            ? password_verify($password, $credentials['hash'])
            : ($credentials['password'] !== '' && hash_equals($credentials['password'], $password)));
        if ($valid) {
            session_regenerate_id(true);
            $_SESSION['tm_admin'] = true;
            $_SESSION['tm_admin_version'] = tmAdminCredentialVersion();
            $isAuthenticated = true;
            if ($redirectPath === 'inventory.php' && ($_GET['next'] ?? '') === 'orders') {
                header('Location: orders.php');
                exit;
            }
        } else {
            $loginError = 'Incorrect password.';
        }
    }

    return [
        'loggedIn' => $allowPublic || $isAuthenticated,
        'isAuthenticated' => $isAuthenticated,
        'loginError' => $loginError,
    ];
}

function tmAdminRequireLogin(string $redirectPath): void
{
    if (empty($_SESSION['tm_admin'])
        || !hash_equals(tmAdminCredentialVersion(), (string)($_SESSION['tm_admin_version'] ?? ''))) {
        header('Location: ' . $redirectPath);
        exit;
    }
}
