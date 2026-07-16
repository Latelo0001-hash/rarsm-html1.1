<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$productId = trim((string) ($_POST['product_id'] ?? $_GET['product_id'] ?? ''));
$quantity = (int) ($_POST['quantity'] ?? $_GET['quantity'] ?? 1);

$product = rarsm_store_product($productId);
if ($product === null) {
    rarsm_set_flash('error', 'Le produit selectionne est introuvable.');
    rarsm_redirect('../pricing.html#formats');
}

rarsm_cart_add($productId, $quantity);
rarsm_set_flash('success', $product['name'] . ' a ete ajoute au panier.');
rarsm_redirect('../shop-cart.php');
