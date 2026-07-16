<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$redirect = rarsm_resolve_redirect_target(
    (string) ($_POST['redirect'] ?? ''),
    (string) ($_SERVER['HTTP_REFERER'] ?? ''),
    'shop-cart.php'
);

if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'POST') {
    rarsm_redirect('../' . ltrim($redirect, '/'));
}

[$success, $message] = rarsm_register_user($_POST);

rarsm_set_flash($success ? 'success' : 'error', $message);
rarsm_redirect('../' . ($success ? ltrim($redirect, '/') : 'shop-account-register.php?redirect=' . rawurlencode($redirect)));
