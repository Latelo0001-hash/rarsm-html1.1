<?php
declare(strict_types=1);

function rarsm_request_is_https(): bool
{
    return isset($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off';
}

function rarsm_apply_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
    header(
        "Content-Security-Policy: default-src 'self'; "
        . "base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; "
        . "script-src 'self' 'unsafe-inline'; "
        . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
        . "img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com; connect-src 'self'; "
        . "frame-src https://www.google.com"
        . (rarsm_request_is_https() ? '; upgrade-insecure-requests' : '')
    );
}

function rarsm_configure_session_security(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    session_name('RARSMSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => rarsm_request_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function rarsm_bootstrap_security(): void
{
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');

    rarsm_apply_security_headers();
    rarsm_configure_session_security();
}

function rarsm_security_log(string $event, array $context = []): void
{
    if (!preg_match('/^[a-z0-9_]{3,64}$/', $event)) {
        return;
    }

    $safeContext = [];
    foreach (['user_id', 'reason', 'retry_after'] as $key) {
        if (isset($context[$key]) && (is_string($context[$key]) || is_int($context[$key]))) {
            $safeContext[$key] = (string) $context[$key];
        }
    }

    $entry = array_merge([
        'event' => $event,
        'occurred_at' => gmdate('c'),
    ], $safeContext);

    $encoded = json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (is_string($encoded)) {
        error_log('RARSM_SECURITY ' . $encoded);
    }
}

function rarsm_enforce_session_lifetime(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    $now = time();
    $lastActivity = (int) ($_SESSION['rarsm_last_activity_at'] ?? 0);
    $authenticatedAt = (int) ($_SESSION['rarsm_authenticated_at'] ?? 0);
    $idleLimit = 1800;
    $absoluteLimit = 43200;

    $idleExpired = $lastActivity > 0 && ($now - $lastActivity) > $idleLimit;
    $loginExpired = isset($_SESSION['rarsm_user'])
        && ($authenticatedAt < 1 || ($now - $authenticatedAt) > $absoluteLimit);

    if ($idleExpired || $loginExpired) {
        rarsm_security_log('session_expired', [
            'user_id' => (string) ($_SESSION['rarsm_user']['id'] ?? ''),
            'reason' => $idleExpired ? 'idle_timeout' : 'absolute_timeout',
        ]);
        unset(
            $_SESSION['rarsm_user'],
            $_SESSION['rarsm_cart'],
            $_SESSION['rarsm_latest_order_id'],
            $_SESSION['rarsm_authenticated_at']
        );
        session_regenerate_id(true);
        $_SESSION['rarsm_csrf_token'] = bin2hex(random_bytes(32));
    }

    $_SESSION['rarsm_last_activity_at'] = $now;
}

function rarsm_mark_session_authenticated(): void
{
    session_regenerate_id(true);
    $_SESSION['rarsm_authenticated_at'] = time();
    $_SESSION['rarsm_last_activity_at'] = time();
    $_SESSION['rarsm_csrf_token'] = bin2hex(random_bytes(32));
}

function rarsm_request_origin_is_same_site(): bool
{
    $source = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));
    if ($source === '') {
        $source = trim((string) ($_SERVER['HTTP_REFERER'] ?? ''));
    }

    // Certains clients légitimes ne transmettent aucun de ces en-têtes.
    // Les cookies SameSite restent alors la protection de repli.
    if ($source === '') {
        return true;
    }

    $sourceParts = parse_url($source);
    if (!is_array($sourceParts) || empty($sourceParts['host'])) {
        return false;
    }

    $requestHost = strtolower(trim((string) ($_SERVER['HTTP_HOST'] ?? '')));
    $sourceHost = strtolower((string) $sourceParts['host']);
    $sourcePort = isset($sourceParts['port']) ? ':' . (int) $sourceParts['port'] : '';

    return hash_equals($requestHost, $sourceHost . $sourcePort);
}

function rarsm_require_same_origin_post(): void
{
    if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Method Not Allowed');
    }

    if (!rarsm_request_origin_is_same_site()) {
        http_response_code(403);
        exit('Forbidden');
    }
}

function rarsm_csrf_token(): string
{
    $token = (string) ($_SESSION['rarsm_csrf_token'] ?? '');
    if ($token === '' || strlen($token) !== 64) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['rarsm_csrf_token'] = $token;
    }

    return $token;
}

function rarsm_csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="'
        . htmlspecialchars(rarsm_csrf_token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        . '">';
}

function rarsm_csrf_is_valid(?string $token = null): bool
{
    $expected = (string) ($_SESSION['rarsm_csrf_token'] ?? '');
    $provided = $token ?? (string) ($_POST['_csrf'] ?? '');

    return $expected !== '' && $provided !== '' && hash_equals($expected, $provided);
}

function rarsm_require_csrf_token(): void
{
    if (!rarsm_csrf_is_valid()) {
        http_response_code(403);
        exit('Forbidden');
    }
}

function rarsm_login_throttle_retry_after(): int
{
    $now = time();
    $window = 600;
    $attempts = array_values(array_filter(
        (array) ($_SESSION['rarsm_login_failures'] ?? []),
        static fn ($timestamp): bool => is_int($timestamp) && $timestamp > ($now - $window)
    ));
    $_SESSION['rarsm_login_failures'] = $attempts;

    if (count($attempts) < 5) {
        return 0;
    }

    return max(1, $window - ($now - (int) $attempts[0]));
}

function rarsm_record_login_failure(): void
{
    $attempts = (array) ($_SESSION['rarsm_login_failures'] ?? []);
    $attempts[] = time();
    $_SESSION['rarsm_login_failures'] = array_slice($attempts, -10);
}

function rarsm_clear_login_failures(): void
{
    unset($_SESSION['rarsm_login_failures']);
}
