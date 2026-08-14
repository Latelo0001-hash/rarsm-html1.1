<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$redirect = trim((string) ($_GET['redirect'] ?? 'shop-cart.php'));

rarsm_page_head('RARSM | Inscription', 'Création d’un compte client RARSM.');
rarsm_render_header('acheter');
rarsm_render_page_title('Créer un compte', [
    ['label' => 'Accueil', 'href' => 'index.html'],
    ['label' => 'Compte'],
    ['label' => 'Inscription'],
]);
?>
<section class="ls s-py-90 s-py-xl-160">
    <div class="container">
        <?php rarsm_render_flash(); ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
				<div class="hero-bg rarsm-account-form-card p-40 p-xl-60 border-r-def">
                    <h3>Inscription client</h3>
					<p>Ce compte servira à suivre les paiements, les commandes et les futures ventes d’articles sur le site.</p>
                    <form action="actions/register.php" class="c-gutter-20" method="post">
                        <?php echo rarsm_csrf_field(); ?>
                        <input type="hidden" name="redirect" value="<?php echo rarsm_e($redirect); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
									<label for="register-first-name">Prénom</label>
                                    <input class="form-control" id="register-first-name" name="first_name" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="register-last-name">Nom</label>
                                    <input class="form-control" id="register-last-name" name="last_name" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="register-email">Email</label>
                                    <input class="form-control" id="register-email" name="email" type="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
									<label for="register-phone">Téléphone / WhatsApp</label>
                                    <input class="form-control" id="register-phone" name="phone" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="register-password">Mot de passe</label>
                                    <input class="form-control" id="register-password" name="password" type="password" minlength="10" autocomplete="new-password" aria-describedby="register-password-help" required>
                                    <small id="register-password-help">10 caractères minimum, avec une majuscule, une minuscule et un chiffre.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="register-password-confirm">Confirmer le mot de passe</label>
                                    <input class="form-control" id="register-password-confirm" name="password_confirm" type="password" minlength="10" autocomplete="new-password" required>
                                </div>
                            </div>
                            <div class="col-12">
								<button class="btn btn-maincolor" type="submit">Créer mon compte</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
rarsm_render_footer();
