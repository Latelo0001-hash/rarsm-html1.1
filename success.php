<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orderId = trim((string) ($_GET['order'] ?? ''));
$order = rarsm_update_order_status($orderId, 'paid', 'paid');

if ($order === null) {
    rarsm_set_flash('error', 'Commande introuvable.');
    rarsm_redirect('shop-account-orders.php');
}

rarsm_page_head('RARSM | Paiement réussi', 'Confirmation de paiement pour une commande RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Paiement confirmé', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Acheter', 'href' => 'pricing.html'],
    ['label' => 'Succès'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="rarsm-status-card rarsm-status-card-success">
                    <p class="rarsm-status-kicker">Paiement réussi</p>
                    <h2 class="special-heading"><span>Commande confirmée</span></h2>
                    <p>Merci. Votre paiement a été enregistré pour la commande <strong><?php echo rarsm_e((string) $order['order_number']); ?></strong>.</p>
                    <div class="rarsm-status-meta">
                        <span>Montant : <?php echo rarsm_e(rarsm_format_money((float) $order['payable_total'], (string) $order['currency'])); ?></span>
                        <span>Statut : <?php echo rarsm_e((string) $order['status']); ?></span>
                    </div>
                    <div class="rarsm-gateway-actions">
                        <a class="btn btn-maincolor" href="shop-account-orders.php">Voir mes commandes</a>
                        <a class="btn btn-outline-maincolor" href="pricing.html#formats">Continuer les achats</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
