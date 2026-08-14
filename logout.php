<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
rarsm_require_same_origin_post();
rarsm_require_csrf_token();

rarsm_logout_user();
rarsm_set_flash('success', rarsm_localized_text('Vous êtes maintenant déconnecté.', 'You are now signed out.'));
rarsm_redirect('index.html');
