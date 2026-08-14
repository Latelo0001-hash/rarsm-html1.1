<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
rarsm_require_same_origin_post();
rarsm_require_csrf_token();

$redirect = rarsm_resolve_redirect_target(
    (string) ($_POST['redirect'] ?? ''),
    (string) ($_SERVER['HTTP_REFERER'] ?? ''),
    'shop-cart.php'
);

[$success, $message] = rarsm_register_user($_POST);

if ($success) {
    rarsm_restore_authenticated_customer_state();
}

rarsm_set_flash($success ? 'success' : 'error', $message);
rarsm_redirect('../' . ($success ? ltrim($redirect, '/') : 'shop-account-register.php?redirect=' . rawurlencode($redirect)));
