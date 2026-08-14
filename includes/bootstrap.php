<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';
rarsm_bootstrap_security();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
rarsm_enforce_session_lifetime();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/i18n.php';
rarsm_current_language();
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/store.php';
require_once __DIR__ . '/layout.php';
