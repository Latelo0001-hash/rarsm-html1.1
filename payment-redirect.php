<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orderId = trim((string) ($_GET['order'] ?? ''));
$order = rarsm_find_order($orderId);

if ($order === null) {
    rarsm_set_flash('error', 'Commande introuvable pour la redirection de paiement.');
    rarsm_redirect('shop-account-orders.php');
}

rarsm_page_head('RARSM | Paiement', 'Passerelle intermediaire de paiement pour les commandes RARSM.');
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
                            <h2 class="special-heading"><span>Etape de paiement intermediaire</span></h2>
                            <div class="divider-30"></div>
                            <p class="excerpt">Cette page represente l’ecran de transition avant l’ouverture de la plateforme de paiement externe. Quand le prestataire sera choisi, c’est ici que l’API ou l’URL de paiement sera branchee.</p>
                            <ul class="list-unstyled rarsm-shop-checklist">
                                <li>Reference de commande : <?php echo rarsm_e((string) $order['order_number']); ?></li>
                                <li>Montant a payer : <?php echo rarsm_e(rarsm_format_money((float) $order['payable_total'], (string) $order['currency'])); ?></li>
                                <li>Methode choisie : <?php echo rarsm_e((string) $order['payment_method']); ?></li>
                            </ul>
                        </div>
                        <div class="col-lg-5">
                            <div class="rarsm-status-card">
                                <p class="rarsm-status-kicker">Simulation de paiement</p>
                                <h4>Choisissez le resultat du retour passerelle</h4>
                                <p class="mb-0">Ces trois boutons permettent de tester le parcours complet tant que la plateforme intermediaire n’est pas encore raccordee.</p>
                                <div class="rarsm-gateway-actions">
                                    <a class="btn btn-maincolor" href="success.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Paiement reussi</a>
                                    <a class="btn btn-outline-maincolor" href="pending.php?order=<?php echo rawurlencode((string) $order['id']); ?>">En attente</a>
                                    <a class="btn btn-outline-darkgrey" href="cancel.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Annuler</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider-30"></div>
                <p class="rarsm-shop-note mb-0">Quand vous brancherez la vraie plateforme, gardez cette logique : creation de commande en base, redirection fournisseur, retour utilisateur sur `success.php`, `cancel.php` ou `pending.php`, puis mise a jour definitive via webhook.</p>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
