<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!defined('ADMIN_PASSWORD')) {
    define('ADMIN_PASSWORD', 'tm-admin-2026!');
}

function tmAdminHandleAuth(string $redirectPath, bool $allowPublic = false): array
{
    $loginError = '';
    $isAuthenticated = !empty($_SESSION['tm_admin']);

    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: ' . $redirectPath);
        exit;
    }

    if (isset($_POST['password'])) {
        if ((string)$_POST['password'] === ADMIN_PASSWORD) {
            $_SESSION['tm_admin'] = true;
            $isAuthenticated = true;
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
    if (empty($_SESSION['tm_admin'])) {
        header('Location: ' . $redirectPath);
        exit;
    }
}
