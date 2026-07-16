<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$removeId = trim((string) ($_POST['remove_id'] ?? ''));
if ($removeId !== '') {
    rarsm_cart_remove($removeId);
    rarsm_set_flash('success', 'L’article a ete retire du panier.');
    rarsm_redirect('../shop-cart.php');
}

$quantities = $_POST['quantities'] ?? [];
if (is_array($quantities)) {
    rarsm_cart_update($quantities);
    rarsm_set_flash('success', 'Le panier a ete mis a jour.');
}

rarsm_redirect('../shop-cart.php');
