<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orderId = trim((string) ($_GET['order'] ?? ''));
$order = rarsm_update_order_status($orderId, 'cancelled', 'cancelled');

if ($order === null) {
    rarsm_set_flash('error', 'Commande introuvable.');
    rarsm_redirect('shop-account-orders.php');
}

rarsm_page_head('RARSM | Paiement annule', 'Annulation de paiement pour une commande RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Paiement annule', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Acheter', 'href' => 'pricing.html'],
    ['label' => 'Annule'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="rarsm-status-card">
                    <p class="rarsm-status-kicker">Paiement annule</p>
                    <h2 class="special-heading"><span>Votre commande n’a pas ete finalisee</span></h2>
                    <p>La commande <strong><?php echo rarsm_e((string) $order['order_number']); ?></strong> a ete marquee comme annulee. Vous pouvez reprendre le paiement plus tard.</p>
                    <div class="rarsm-gateway-actions">
                        <a class="btn btn-maincolor" href="payment-redirect.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Reessayer le paiement</a>
                        <a class="btn btn-outline-maincolor" href="shop-account-orders.php">Mes commandes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
