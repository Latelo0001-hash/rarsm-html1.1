<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orderId = trim((string) ($_GET['order'] ?? ''));
$mode = trim((string) ($_GET['mode'] ?? ''));
$status = $mode === 'quote' ? 'pending_quote' : 'payment_pending';
$order = rarsm_update_order_status($orderId, $status, 'pending');

if ($order === null) {
    rarsm_set_flash('error', 'Commande introuvable.');
    rarsm_redirect('shop-account-orders.php');
}

$title = $mode === 'quote' ? 'Demande en attente' : 'Paiement en attente';
$description = $mode === 'quote'
    ? 'Votre commande institutionnelle ou sur devis est en attente de traitement.'
    : 'Votre paiement est en attente de confirmation.';

rarsm_page_head('RARSM | ' . $title, $description);
rarsm_render_header('acheter');
rarsm_render_page_title($title, [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Acheter', 'href' => 'pricing.html'],
    ['label' => 'En attente'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="rarsm-status-card">
                    <p class="rarsm-status-kicker">Suivi de commande</p>
                    <h2 class="special-heading"><span><?php echo rarsm_e($title); ?></span></h2>
                    <p><?php echo rarsm_e($description); ?> Reference : <strong><?php echo rarsm_e((string) $order['order_number']); ?></strong>.</p>
                    <div class="rarsm-status-meta">
                        <span>Statut : <?php echo rarsm_e((string) $order['status']); ?></span>
                        <span>Montant payable : <?php echo rarsm_e(rarsm_format_money((float) $order['payable_total'], (string) $order['currency'])); ?></span>
                    </div>
                    <div class="rarsm-gateway-actions">
                        <a class="btn btn-maincolor" href="shop-account-orders.php">Voir mes commandes</a>
                        <a class="btn btn-outline-maincolor" href="contact.html">Contacter l’équipe</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
