<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$items = rarsm_cart_items();
$totals = rarsm_cart_totals();

rarsm_page_head('RARSM | Panier', 'Panier de commande RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Panier', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Acheter', 'href' => 'pricing.html'],
    ['label' => 'Panier'],
]);
?>
<section class="ls s-py-90 s-py-xl-160 c-gutter-60">
    <div class="container">
        <?php rarsm_render_flash(); ?>
        <div class="row">
            <main class="col-lg-12">
                <?php if ($totals['is_empty']) : ?>
                    <div class="rarsm-status-card text-center">
                        <p class="rarsm-status-kicker">Panier vide</p>
                        <h3>Aucun article n’a encore ete ajoute</h3>
                        <p>Selectionnez un format du livre RARSM pour commencer votre commande et passer au checkout ensuite.</p>
                        <div class="rarsm-gateway-actions justify-content-center">
                            <a class="btn btn-maincolor" href="pricing.html#formats">Voir les formats</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-message">
                            <a href="shop-checkout.php" class="button wc-forward">Passer a la commande</a>
                            Votre panier contient <?php echo rarsm_e((string) $totals['item_count']); ?> article(s).
                        </div>
                    </div>

                    <form class="woocommerce-cart-form" action="actions/update-cart.php" method="post">
                        <table class="shop_table shop_table_responsive cart">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">Produit</th>
                                    <th class="product-price">Prix</th>
                                    <th class="product-quantity">Quantite</th>
                                    <th class="product-subtotal">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item) : ?>
                                    <tr class="cart_item">
                                        <td class="product-remove">
                                            <button class="remove" name="remove_id" type="submit" value="<?php echo rarsm_e((string) $item['id']); ?>" aria-label="Retirer cet article">×</button>
                                        </td>
                                        <td class="product-thumbnail">
                                            <img width="180" height="180" src="<?php echo rarsm_e((string) $item['image']); ?>" alt="<?php echo rarsm_e((string) $item['name']); ?>">
                                        </td>
                                        <td class="product-name" data-title="Produit">
                                            <strong><?php echo rarsm_e((string) $item['name']); ?></strong>
                                            <p class="mb-0"><?php echo rarsm_e((string) $item['short_description']); ?></p>
                                        </td>
                                        <td class="product-price" data-title="Prix">
                                            <?php if ((bool) $item['quote_only']) : ?>
                                                <span class="amount">Sur devis</span>
                                            <?php else : ?>
                                                <span class="amount"><?php echo rarsm_e(rarsm_format_money((float) $item['price'], (string) $item['currency'])); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="product-quantity" data-title="Quantite">
                                            <div class="quantity">
                                                <input class="input-text qty text" max="99" min="1" name="quantities[<?php echo rarsm_e((string) $item['id']); ?>]" size="4" step="1" type="number" value="<?php echo rarsm_e((string) $item['quantity']); ?>">
                                            </div>
                                        </td>
                                        <td class="product-subtotal" data-title="Total">
                                            <?php if ((bool) $item['quote_only']) : ?>
                                                <span class="amount">A confirmer</span>
                                            <?php else : ?>
                                                <span class="amount"><?php echo rarsm_e(rarsm_format_money((float) $item['subtotal'], (string) $item['currency'])); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="actions" colspan="6">
                                        <input class="button" name="update_cart" type="submit" value="Mettre a jour">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>

                    <div class="cart-collaterals">
                        <div class="cart_totals">
                            <h2>Recapitulatif</h2>
                            <table class="shop_table shop_table_responsive">
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Sous-total</th>
                                        <td data-title="Sous-total"><?php echo rarsm_e(rarsm_format_money((float) $totals['subtotal'], (string) $totals['currency'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paiement immediat</th>
                                        <td data-title="Paiement immediat"><?php echo rarsm_e(rarsm_format_money((float) $totals['payable_total'], (string) $totals['currency'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Livraison</th>
                                        <td data-title="Livraison"><?php echo $totals['contains_physical'] ? 'Confirmee au checkout selon la destination' : 'Aucune livraison physique'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="wc-proceed-to-checkout">
                                <a class="checkout-button button alt wc-forward" href="shop-checkout.php">Passer a la commande</a>
                            </div>
                            <p class="rarsm-shop-note mb-0">Le compte client sera demande au checkout afin de suivre les paiements, les annulations et les futures ventes d’articles sur le site.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
