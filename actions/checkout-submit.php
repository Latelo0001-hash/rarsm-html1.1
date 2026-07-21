<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

if (!rarsm_is_logged_in()) {
    rarsm_set_flash('error', 'Veuillez vous connecter avant de valider votre commande.');
    rarsm_redirect('../shop-checkout.php');
}

$totals = rarsm_cart_totals();
if ($totals['is_empty']) {
    rarsm_set_flash('error', 'Votre panier est vide.');
    rarsm_redirect('../pricing.html#formats');
}

$requiredFields = [
    'first_name' => 'Le prenom est obligatoire.',
    'last_name' => 'Le nom est obligatoire.',
    'email' => 'L’email est obligatoire.',
    'phone' => 'Le numero de telephone est obligatoire.',
    'country' => 'Le pays est obligatoire.',
];

if ($totals['contains_physical']) {
    $requiredFields['address_1'] = 'L’adresse de livraison est obligatoire pour un produit physique.';
    $requiredFields['city'] = 'La ville est obligatoire pour un produit physique.';
}

foreach ($requiredFields as $field => $errorMessage) {
    if (trim((string) ($_POST[$field] ?? '')) === '') {
        rarsm_set_flash('error', $errorMessage);
        rarsm_redirect('../shop-checkout.php');
    }
}

$email = trim((string) ($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    rarsm_set_flash('error', 'Veuillez saisir une adresse email valide.');
    rarsm_redirect('../shop-checkout.php');
}

$order = rarsm_create_order($_POST);

if ((bool) $order['contains_quote_only'] || (float) $order['payable_total'] <= 0) {
    rarsm_set_flash('succèss', 'Votre demande a été enregistree. Notre équipe reviendra vers vous pour finaliser le devis.');
    rarsm_redirect('../pending.php?order=' . rawurlencode((string) $order['id']) . '&mode=quote');
}

rarsm_set_flash('succèss', 'Commande créée. Vous pouvez maintenant poursuivre le paiement via la passerelle partenaire.');
rarsm_redirect('../payment-redirect.php?order=' . rawurlencode((string) $order['id']));
