<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

rarsm_logout_user();
rarsm_set_flash('success', 'Vous etes maintenant deconnecte.');
rarsm_redirect('pricing.html');
