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
    rarsm_apply_security_headers();
    rarsm_configure_session_security();
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
