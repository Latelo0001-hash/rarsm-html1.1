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

[$succèss, $message] = rarsm_login_user($_POST);

if ($succèss) {
    rarsm_restore_authenticated_customer_state();
}

rarsm_set_flash($succèss ? 'succèss' : 'error', $message);
rarsm_redirect('../' . ($succèss ? ltrim($redirect, '/') : 'shop-account-login.php?redirect=' . rawurlencode($redirect)));
