<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
rarsm_require_same_origin_post();
rarsm_require_csrf_token();

$removeId = trim((string) ($_POST['remove_id'] ?? ''));
if ($removeId !== '') {
    rarsm_cart_remove($removeId);
    rarsm_set_flash('success', rarsm_localized_text('L’article a été retiré du panier.', 'The item has been removed from your cart.'));
    rarsm_redirect('../shop-cart.php');
}

$quantities = $_POST['quantities'] ?? [];
if (is_array($quantities)) {
    rarsm_cart_update($quantities);
    rarsm_set_flash('success', rarsm_localized_text('Le panier a été mis à jour.', 'Your cart has been updated.'));
}

rarsm_redirect('../shop-cart.php');
