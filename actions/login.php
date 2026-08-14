<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
rarsm_require_same_origin_post();

$redirect = rarsm_resolve_redirect_target(
    (string) ($_POST['redirect'] ?? ''),
    (string) ($_SERVER['HTTP_REFERER'] ?? ''),
    'shop-cart.php'
);

$retryAfter = rarsm_login_throttle_retry_after();
if ($retryAfter > 0) {
    rarsm_set_flash(
        'error',
        rarsm_localized_text(
            'Trop de tentatives. Veuillez patienter quelques minutes avant de réessayer.',
            'Too many attempts. Please wait a few minutes before trying again.'
        )
    );
    header('Retry-After: ' . $retryAfter);
    rarsm_redirect('../shop-account-login.php?redirect=' . rawurlencode($redirect));
}

[$success, $message] = rarsm_login_user($_POST);

if ($success) {
    rarsm_clear_login_failures();
} else {
    rarsm_record_login_failure();
}

if ($success) {
    rarsm_restore_authenticated_customer_state();
}

rarsm_set_flash($success ? 'success' : 'error', $message);
rarsm_redirect('../' . ($success ? ltrim($redirect, '/') : 'shop-account-login.php?redirect=' . rawurlencode($redirect)));
