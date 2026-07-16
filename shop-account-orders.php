<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$orders = array_reverse(rarsm_orders(), true);

rarsm_page_head('RARSM | Mes commandes', 'Historique et suivi des commandes RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Mes commandes', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Compte'],
    ['label' => 'Mes commandes'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <?php rarsm_render_flash(); ?>
        <div class="row">
            <div class="col-lg-12">
                <?php if (empty($orders)) : ?>
                    <div class="rarsm-status-card text-center">
                        <p class="rarsm-status-kicker">Aucune commande</p>
                        <h3>Votre historique est encore vide</h3>
                        <p>Commencez par ajouter le livre RARSM au panier, puis validez votre checkout pour voir apparaitre vos commandes ici.</p>
                        <div class="rarsm-gateway-actions justify-content-center">
                            <a class="btn btn-maincolor" href="pricing.html#formats">Acheter le livre</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="woocommerce">
                        <table class="shop_table shop_table_responsive cart">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Montant</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td data-title="Reference"><?php echo rarsm_e((string) $order['order_number']); ?></td>
                                        <td data-title="Date"><?php echo rarsm_e((string) $order['created_at']); ?></td>
                                        <td data-title="Statut"><?php echo rarsm_e((string) $order['status']); ?></td>
                                        <td data-title="Montant"><?php echo rarsm_e(rarsm_format_money((float) $order['payable_total'], (string) $order['currency'])); ?></td>
                                        <td data-title="Action">
                                            <?php if ((string) $order['status'] === 'pending_payment' || (string) $order['payment_status'] === 'initiated') : ?>
                                                <a class="btn btn-outline-maincolor" href="payment-redirect.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Payer</a>
                                            <?php elseif ((string) $order['status'] === 'cancelled') : ?>
                                                <a class="btn btn-outline-maincolor" href="payment-redirect.php?order=<?php echo rawurlencode((string) $order['id']); ?>">Relancer</a>
                                            <?php else : ?>
                                                <a class="btn btn-outline-maincolor" href="contact.html">Assistance</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
