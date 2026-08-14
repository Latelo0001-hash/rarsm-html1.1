<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$redirect = trim((string) ($_GET['redirect'] ?? 'shop-cart.php'));

rarsm_page_head('RARSM | Connexion', 'Connexion au compte client RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Se connecter', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Compte'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <?php rarsm_render_flash(); ?>
        <div class="row justify-content-center c-gutter-40">
            <div class="col-lg-5">
				<div class="hero-bg rarsm-account-form-card p-40 p-xl-60 border-r-def h-100">
                    <h3>Connexion</h3>
                    <p>Connectez-vous pour suivre vos commandes, reprendre un paiement et retrouver vos informations plus tard.</p>
                    <form action="actions/login.php" class="c-mb-30" method="post">
                        <?php echo rarsm_csrf_field(); ?>
                        <input type="hidden" name="redirect" value="<?php echo rarsm_e($redirect); ?>">
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input class="form-control" id="login-email" name="email" type="email" required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Mot de passe</label>
                            <input class="form-control" id="login-password" name="password" type="password" required>
                        </div>
                        <button class="btn btn-maincolor" type="submit">Se connecter</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
				<div class="rarsm-status-card rarsm-account-side-card h-100">
                    <p class="rarsm-status-kicker">Nouveau client</p>
                    <h4>Créer un compte pour suivre vos achats</h4>
					<p>Votre compte relie le panier, la validation, le suivi des commandes et l’historique à un même profil client.</p>
                    <div class="rarsm-gateway-actions">
                        <a class="btn btn-outline-maincolor" href="shop-account-register.php?redirect=<?php echo rawurlencode($redirect); ?>">Créer un compte</a>
                        <a class="btn btn-outline-darkgrey" href="shop-cart.php">Retour au panier</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
