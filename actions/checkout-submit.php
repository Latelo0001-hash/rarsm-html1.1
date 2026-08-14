<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
rarsm_require_same_origin_post();
rarsm_require_csrf_token();

if (!rarsm_is_logged_in()) {
    rarsm_set_flash('error', rarsm_localized_text('Veuillez vous connecter avant de valider votre commande.', 'Please log in before submitting your order.'));
    rarsm_redirect('../shop-checkout.php');
}

$totals = rarsm_cart_totals();
if ($totals['is_empty']) {
    rarsm_set_flash('error', rarsm_localized_text('Votre panier est vide.', 'Your cart is empty.'));
    rarsm_redirect('../pricing.html#formats');
}

$requiredFields = [
    'first_name' => rarsm_localized_text('Le prénom est obligatoire.', 'First name is required.'),
    'last_name' => rarsm_localized_text('Le nom est obligatoire.', 'Last name is required.'),
    'email' => rarsm_localized_text('L’adresse email est obligatoire.', 'Email address is required.'),
    'phone' => rarsm_localized_text('Le numéro de téléphone est obligatoire.', 'Phone number is required.'),
    'country' => rarsm_localized_text('Le pays est obligatoire.', 'Country is required.'),
];

if ($totals['contains_physical']) {
    $requiredFields['address_1'] = rarsm_localized_text('L’adresse de livraison est obligatoire pour un produit physique.', 'A delivery address is required for a physical product.');
    $requiredFields['city'] = rarsm_localized_text('La ville est obligatoire pour un produit physique.', 'City is required for a physical product.');
}

foreach ($requiredFields as $field => $errorMessage) {
    if (trim((string) ($_POST[$field] ?? '')) === '') {
        rarsm_set_flash('error', $errorMessage);
        rarsm_redirect('../shop-checkout.php');
    }
}

$email = trim((string) ($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    rarsm_set_flash('error', rarsm_localized_text('Veuillez saisir une adresse email valide.', 'Please enter a valid email address.'));
    rarsm_redirect('../shop-checkout.php');
}

$order = rarsm_create_order($_POST);

if ((bool) $order['contains_quote_only'] || (float) $order['payable_total'] <= 0) {
    rarsm_set_flash('success', rarsm_localized_text('Votre demande a été enregistrée. Notre équipe reviendra vers vous pour finaliser le devis.', 'Your request has been recorded. Our team will contact you to finalize the quotation.'));
    rarsm_redirect('../pending.php?order=' . rawurlencode((string) $order['id']) . '&mode=quote');
}

rarsm_set_flash('success', rarsm_localized_text('Commande créée. Vous pouvez maintenant poursuivre le paiement via la passerelle partenaire.', 'Order created. You can now continue payment through the partner gateway.'));
rarsm_redirect('../payment-redirect.php?order=' . rawurlencode((string) $order['id']));
