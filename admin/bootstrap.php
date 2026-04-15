<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!defined('ADMIN_PASSWORD')) {
    define('ADMIN_PASSWORD', 'tm-admin-2026');
}

function tmAdminHandleAuth(string $redirectPath): array
{
    $loginError = '';

    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: ' . $redirectPath);
        exit;
    }

    if (isset($_POST['password'])) {
        if ((string)$_POST['password'] === ADMIN_PASSWORD) {
            $_SESSION['tm_admin'] = true;
        } else {
            $loginError = 'Incorrect password.';
        }
    }

    return [
        'loggedIn' => !empty($_SESSION['tm_admin']),
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
