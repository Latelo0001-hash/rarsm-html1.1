<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$items = rarsm_cart_items();
$totals = rarsm_cart_totals();
$user = rarsm_current_user();

rarsm_page_head('RARSM | Checkout', 'Validation de la commande RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Checkout', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Acheter', 'href' => 'pricing.html'],
    ['label' => 'Checkout'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <?php rarsm_render_flash(); ?>
        <?php if ($totals['is_empty']) : ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="rarsm-status-card text-center">
                        <p class="rarsm-status-kicker">Panier vide</p>
                        <h3>Ajoutez d’abord un produit</h3>
                        <p>Le checkout devient disponible une fois le livre ajoute au panier.</p>
                        <div class="rarsm-gateway-actions justify-content-center">
                            <a class="btn btn-maincolor" href="pricing.html#formats">Voir les formats</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($user === null) : ?>
            <div class="row justify-content-center c-gutter-40">
                <div class="col-lg-5">
                    <div class="hero-bg p-40 p-xl-60 border-r-def h-100">
                        <h3>Connexion requise</h3>
                        <p>Pour suivre les commandes et retrouver vos paiements, le checkout est reserve aux utilisateurs connectes.</p>
                        <div class="rarsm-gateway-actions">
                            <a class="btn btn-maincolor" data-toggle="modal" href="#popupLogin">Se connecter</a>
                            <a class="btn btn-outline-darkgrey" href="shop-cart.php">Retour au panier</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="rarsm-status-card h-100">
                        <p class="rarsm-status-kicker">Nouveau compte</p>
                        <h4>Creer un compte avant de payer</h4>
                        <p>En quelques informations, vous pourrez suivre le panier, l’historique et les prochains articles mis en vente. Apres connexion ou inscription, vous reviendrez directement sur ce checkout.</p>
                        <div class="rarsm-gateway-actions">
                            <a class="btn btn-outline-maincolor" data-toggle="modal" href="#popupRegistr">S’inscrire</a>
                            <a class="btn btn-outline-darkgrey" href="shop-cart.php">Retour au panier</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <main class="col-lg-12">
                    <div class="woocommerce">
                        <div class="woocommerce-info">Votre commande sera creee avant redirection vers la plateforme de paiement intermediaire.</div>
                        <form class="checkout woocommerce-checkout" action="actions/checkout-submit.php" method="post" novalidate>
                            <div class="col2-set" id="customer_details">
                                <div class="col-1">
                                    <div class="woocommerce-billing-fields">
                                        <h3>Coordonnees</h3>
                                        <div class="woocommerce-billing-fields__field-wrapper">
                                            <p class="form-row form-row-first validate-required">
                                                <label for="billing_first_name">Prenom *</label>
                                                <input class="input-text" id="billing_first_name" name="first_name" required type="text" value="<?php echo rarsm_e((string) ($user['first_name'] ?? '')); ?>">
                                            </p>
                                            <p class="form-row form-row-last validate-required">
                                                <label for="billing_last_name">Nom *</label>
                                                <input class="input-text" id="billing_last_name" name="last_name" required type="text" value="<?php echo rarsm_e((string) ($user['last_name'] ?? '')); ?>">
                                            </p>
                                            <p class="form-row form-row-wide">
                                                <label for="billing_company">Institution / Societe</label>
                                                <input class="input-text" id="billing_company" name="company" type="text">
                                            </p>
                                            <p class="form-row form-row-first validate-required">
                                                <label for="billing_phone">Telephone / WhatsApp *</label>
                                                <input class="input-text" id="billing_phone" name="phone" required type="text" value="<?php echo rarsm_e((string) ($user['phone'] ?? '')); ?>">
                                            </p>
                                            <p class="form-row form-row-last validate-required">
                                                <label for="billing_email">Email *</label>
                                                <input class="input-text" id="billing_email" name="email" required type="email" value="<?php echo rarsm_e((string) ($user['email'] ?? '')); ?>">
                                            </p>
                                            <p class="form-row form-row-wide validate-required">
                                                <label for="billing_country">Pays *</label>
                                                <input class="input-text" id="billing_country" name="country" required type="text" value="RDC">
                                            </p>
                                            <p class="form-row form-row-wide">
                                                <label for="billing_address_1">Adresse <?php echo $totals['contains_physical'] ? '*' : ''; ?></label>
                                                <input class="input-text" id="billing_address_1" name="address_1" type="text" value="">
                                            </p>
                                            <p class="form-row form-row-first">
                                                <label for="billing_city">Ville <?php echo $totals['contains_physical'] ? '*' : ''; ?></label>
                                                <input class="input-text" id="billing_city" name="city" type="text" value="">
                                            </p>
                                            <p class="form-row form-row-last">
                                                <label for="billing_state">Province / Etat</label>
                                                <input class="input-text" id="billing_state" name="state" type="text" value="">
                                            </p>
                                            <p class="form-row form-row-wide">
                                                <label for="billing_postcode">Code postal</label>
                                                <input class="input-text" id="billing_postcode" name="postal_code" type="text" value="">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="woocommerce-additional-fields">
                                        <h3>Livraison et paiement</h3>
                                        <div class="woocommerce-additional-fields__field-wrapper">
                                            <p class="form-row form-row-wide">
                                                <label for="order_delivery">Mode de remise</label>
                                                <select class="input-text" id="order_delivery" name="delivery_mode">
                                                    <option value="pickup">Retrait a Kinshasa</option>
                                                    <option value="local">Livraison locale</option>
                                                    <option value="shipping">Expedition hors Kinshasa</option>
                                                    <option value="email">Envoi numerique</option>
                                                </select>
                                            </p>
                                            <p class="form-row form-row-wide">
                                                <label for="payment_method">Methode de paiement</label>
                                                <select class="input-text" id="payment_method" name="payment_method">
                                                    <option value="partner_gateway">Passerelle partenaire</option>
                                                    <option value="mobile_money">Mobile Money</option>
                                                    <option value="bank_transfer">Virement bancaire</option>
                                                </select>
                                            </p>
                                            <p class="form-row notes">
                                                <label for="order_comments">Notes de commande</label>
                                                <textarea class="input-text" cols="5" id="order_comments" name="notes" rows="5" placeholder="Facture, devis, quantite, precision de livraison..."></textarea>
                                            </p>
                                        </div>
                                        <div class="hero-bg p-30 p-xl-40">
                                            <h5 class="mt-0">Compte suivi</h5>
                                            <p class="mb-2"><?php echo rarsm_e((string) ($user['first_name'] ?? '')); ?> <?php echo rarsm_e((string) ($user['last_name'] ?? '')); ?></p>
                                            <p class="mb-2"><?php echo rarsm_e((string) ($user['email'] ?? '')); ?></p>
                                            <p class="mb-0">Les commandes creees ici apparaitront dans votre historique.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 id="order_review_heading">Votre commande</h3>
                            <div class="woocommerce-checkout-review-order">
                                <table class="shop_table woocommerce-checkout-review-order-table">
                                    <thead>
                                        <tr>
                                            <th class="product-name">Produit</th>
                                            <th class="product-total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item) : ?>
                                            <tr class="cart_item">
                                                <td class="product-name"><?php echo rarsm_e((string) $item['name']); ?> <strong class="product-quantity">× <?php echo rarsm_e((string) $item['quantity']); ?></strong></td>
                                                <td class="product-total">
                                                    <?php if ((bool) $item['quote_only']) : ?>
                                                        <span class="amount">Sur devis</span>
                                                    <?php else : ?>
                                                        <span class="woocommerce-Price-amount amount"><?php echo rarsm_e(rarsm_format_money((float) $item['subtotal'], (string) $item['currency'])); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cart-subtotal">
                                            <th>Sous-total</th>
                                            <td><?php echo rarsm_e(rarsm_format_money((float) $totals['subtotal'], (string) $totals['currency'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Montant a payer</th>
                                            <td><?php echo rarsm_e(rarsm_format_money((float) $totals['payable_total'], (string) $totals['currency'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Livraison</th>
                                            <td><?php echo $totals['contains_physical'] ? 'Calculee apres validation de la destination' : 'Aucune livraison physique'; ?></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="woocommerce-checkout-payment">
                                    <div class="form-row place-order rarsm-shop-place-order">
                                        <a class="button" href="shop-cart.php">Retour au panier</a>
                                        <input class="button alt" id="place_order" name="place_order" type="submit" value="<?php echo $totals['contains_quote_only'] ? 'Soumettre la demande' : 'Continuer vers le paiement'; ?>">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php
rarsm_render_footer();
