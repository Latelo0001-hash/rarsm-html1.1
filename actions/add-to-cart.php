<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
rarsm_require_same_origin_post();

$productId = trim((string) ($_POST['product_id'] ?? ''));
$quantity = (int) ($_POST['quantity'] ?? 1);

$product = rarsm_store_product($productId);
if ($product === null) {
    rarsm_set_flash('error', rarsm_localized_text('Le produit sélectionné est introuvable.', 'The selected product could not be found.'));
    rarsm_redirect('../pricing.html#formats');
}

rarsm_cart_add($productId, $quantity);
rarsm_set_flash(
    'success',
    rarsm_localized_text(
        (string) $product['name'] . ' a été ajouté au panier.',
        'The selected product has been added to your cart.'
    )
);
rarsm_redirect('../shop-cart.php');
