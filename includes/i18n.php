<?php
declare(strict_types=1);

function rarsm_current_language(): string
{
    $requested = strtolower(trim((string) ($_GET['lang'] ?? $_POST['lang'] ?? '')));
    if ($requested === 'en' || $requested === 'fr') {
        if (($_COOKIE['rarsm_lang'] ?? '') !== $requested) {
            $_COOKIE['rarsm_lang'] = $requested;
            if (!headers_sent()) {
                setcookie('rarsm_lang', $requested, [
                    'expires' => time() + 31536000,
                    'path' => '/',
                    'samesite' => 'Lax',
                ]);
            }
        }

        return $requested;
    }

    $stored = strtolower(trim((string) ($_COOKIE['rarsm_lang'] ?? '')));

    return $stored === 'en' ? 'en' : 'fr';
}

function rarsm_localized_text(string $french, string $english): string
{
    return rarsm_current_language() === 'en' ? $english : $french;
}
