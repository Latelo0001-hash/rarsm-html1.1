<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orderId = trim((string) ($_GET['order'] ?? ''));
$order = rarsm_find_order($orderId);

if ($order === null) {
    rarsm_set_flash('error', rarsm_localized_text('Commande introuvable pour la redirection de paiement.', 'Order not found for payment redirection.'));
    rarsm_redirect('shop-account-orders.php');
}

rarsm_page_head('RARSM | Paiement', 'Passerelle intermédiaire de paiement pour les commandes RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Redirection vers le paiement', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Acheter', 'href' => 'pricing.html'],
    ['label' => 'Paiement'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <?php rarsm_render_flash(); ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="hero-bg p-40 p-xl-60 border-r-def">
                    <div class="row align-items-center c-gutter-40">
                        <div class="col-lg-7">
                            <p class="mb-2 color-main">Passerelle partenaire</p>
                            <h2 class="special-heading"><span>Étape de paiement intermédiaire</span></h2>
                            <div class="divider-30"></div>
                            <p class="excerpt">Cette page représente l’écran de transition avant l’ouverture de la plateforme de paiement externe. Quand le prestataire sera choisi, c’est ici que l’API ou l’URL de paiement sera branchée.</p>
							<ul class="list-unstyled rarsm-shop-checklist">
								<li><span class="rarsm-payment-reference-label">Référence de commande :</span> <?php echo rarsm_e((string) $order['order_number']); ?></li>
								<li><span class="rarsm-payment-amount-label">Montant à payer :</span> <span class="rarsm-money"><?php echo rarsm_e(rarsm_format_money((float) $order['payable_total'], (string) $order['currency'])); ?></span></li>
								<li><span class="rarsm-payment-method-label">Méthode choisie :</span> <span class="rarsm-payment-method" data-payment-method="<?php echo rarsm_e((string) $order['payment_method']); ?>"><?php echo rarsm_e((string) $order['payment_method']); ?></span></li>
                            </ul>
                        </div>
                        <div class="col-lg-5">
                            <div class="rarsm-status-card">
                                <p class="rarsm-status-kicker">Simulation de paiement</p>
                                <h4>Choisissez le résultat du retour passerelle</h4>
                                <p class="mb-0">Ces trois boutons permettent de tester le parcours complet tant que la plateforme intermédiaire n’est pas encore raccordée.</p>
                                <div class="rarsm-gateway-actions">
                                    <a class="btn btn-maincolor" href="success.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Paiement réussi</a>
                                    <a class="btn btn-outline-maincolor" href="pending.php?order=<?php echo rawurlencode((string) $order['id']); ?>">En attente</a>
                                    <a class="btn btn-outline-darkgrey" href="cancel.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Annuler</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider-30"></div>
                <p class="rarsm-shop-note mb-0">Quand vous brancherez la vraie plateforme, gardez cette logique : création de commande en base, redirection fournisseur, retour utilisateur sur `success.php`, `cancel.php` ou `pending.php`, puis mise à jour définitive via webhook.</p>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
