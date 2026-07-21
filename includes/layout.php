<?php
declare(strict_types=1);

function rarsm_e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function rarsm_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function rarsm_set_flash(string $type, string $message): void
{
    $_SESSION['rarsm_flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function rarsm_pull_flash(): ?array
{
    $flash = $_SESSION['rarsm_flash'] ?? null;
    unset($_SESSION['rarsm_flash']);

    return is_array($flash) ? $flash : null;
}

function rarsm_format_money(float $amount, string $currency = 'USD'): string
{
    $prefix = $currency === 'USD' ? '$' : $currency . ' ';

    return $prefix . number_format($amount, 2, '.', ' ');
}

function rarsm_normalize_relative_path(string $path, string $fallback = 'index.html'): string
{
    $path = trim($path);
    if ($path === '' || preg_match('#^(?:[a-z][a-z0-9+\-.]*:)?//#i', $path)) {
        return $fallback;
    }

    $parsed = parse_url($path);
    if ($parsed === false) {
        return $fallback;
    }

    $cleanPath = ltrim((string) ($parsed['path'] ?? ''), '/');
    $segments = [];

    foreach (explode('/', $cleanPath) as $segment) {
        if ($segment === '' || $segment === '.') {
            continue;
        }

        if ($segment === '..') {
            return $fallback;
        }

        $segments[] = $segment;
    }

    $normalizedPath = implode('/', $segments);
    if ($normalizedPath === '') {
        $normalizedPath = $fallback;
    }

    $query = isset($parsed['query']) && $parsed['query'] !== '' ? '?' . $parsed['query'] : '';
    $fragment = isset($parsed['fragment']) && $parsed['fragment'] !== '' ? '#' . $parsed['fragment'] : '';

    return $normalizedPath . $query . $fragment;
}

function rarsm_resolve_redirect_target(string $requestedPath, string $referer = '', string $fallback = 'index.html'): string
{
    $requestedTarget = rarsm_normalize_relative_path($requestedPath, '');
    if ($requestedTarget !== '') {
        return $requestedTarget;
    }

    $referer = trim($referer);
    if ($referer !== '') {
        $parsedReferer = parse_url($referer);
        $requestHost = strtolower((string) ($_SERVER['SERVER_NAME'] ?? ''));
        if ($requestHost === '') {
            $requestHost = strtolower(preg_replace('/:\d+$/', '', (string) ($_SERVER['HTTP_HOST'] ?? '')));
        }
        $refererHost = strtolower((string) ($parsedReferer['host'] ?? ''));

        if (
            $parsedReferer !== false
            && ($refererHost === '' || $requestHost === '' || $refererHost === $requestHost)
        ) {
            $candidate = (string) ($parsedReferer['path'] ?? '');

            if (isset($parsedReferer['query']) && $parsedReferer['query'] !== '') {
                $candidate .= '?' . $parsedReferer['query'];
            }

            if (isset($parsedReferer['fragment']) && $parsedReferer['fragment'] !== '') {
                $candidate .= '#' . $parsedReferer['fragment'];
            }

            $refererTarget = rarsm_normalize_relative_path($candidate, '');
            if ($refererTarget !== '') {
                return $refererTarget;
            }
        }
    }

    return rarsm_normalize_relative_path($fallback, 'index.html');
}

function rarsm_current_request_path(): string
{
    $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '');

    return rarsm_normalize_relative_path($requestUri, 'index.html');
}

function rarsm_auth_redirect_target(?string $requestPath = null): string
{
    $path = rarsm_normalize_relative_path($requestPath ?? rarsm_current_request_path(), 'shop-cart.php');
    $parsedPath = parse_url($path, PHP_URL_PATH);
    $basename = strtolower(basename(is_string($parsedPath) && $parsedPath !== '' ? $parsedPath : $path));
    $shopLikePages = [
        'succèss.php',
        'cancel.php',
        'pending.php',
        'payment-redirect.php',
    ];

    if (strpos($basename, 'shop-') === 0 || in_array($basename, $shopLikePages, true)) {
        return $path;
    }

    return 'shop-cart.php';
}

function rarsm_render_auth_modals(): void
{
    $redirect = rarsm_e(rarsm_auth_redirect_target());

    echo <<<HTML
<div class="modal fade popupLogin" id="popupLogin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ls border-r-def overflow-visible s-overlay s-mobile-overlay">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-4">Se connecter</h4>
                            <form class="form-registration c-mb-40 c-gutter-20" method="post" action="actions/login.php">
                                <input type="hidden" name="redirect" value="{$redirect}">
                                <div class="row mb-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="login" class="form-control" required placeholder="Email ou identifiant" aria-required="true" autocomplete="username">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control" placeholder="Mot de passe" aria-required="true" required autocomplete="current-password">
                                        </div>
                                    </div>
                                </div>
                                <a class="registerRedirect" data-dismiss="modal" data-target="#popupRegistr" data-toggle="modal" href="#">Pas encore membre ? Inscrivez-vous</a>
                                <div class="modal-form-actions">
                                    <button type="button" class="btn btn-outline-maincolor" data-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-maincolor">Se connecter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade popupRegistr" id="popupRegistr" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ls border-r-def overflow-visible s-overlay s-mobile-overlay">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-4">Inscription</h4>
                            <form class="form-registration c-mb-40 c-gutter-40" method="post" action="actions/register.php">
                                <input type="hidden" name="redirect" value="{$redirect}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" required placeholder="Nom ou identifiant" aria-required="true" autocomplete="nickname">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control" required placeholder="Email" aria-required="true" autocomplete="email">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control" placeholder="Mot de passe" aria-required="true" required autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="password" name="password_confirm" class="form-control" placeholder="Confirmer le mot de passe" aria-required="true" required autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input type="checkbox" id="popupRegistrTerms" name="popupRegistrTerms" required aria-required="true">
                                                <label for="popupRegistrTerms">J'accepte les conditions d'utilisation</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-form-actions">
                                    <button type="button" class="btn btn-outline-maincolor" data-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-maincolor">Créer un compte</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
}

function rarsm_page_head(string $title, string $description = '', string $bodyClass = 'rarsm-shop-page'): void
{
    $safeTitle = rarsm_e($title);
    $safeDescription = rarsm_e($description);
    $safeBodyClass = rarsm_e($bodyClass);

    echo <<<HTML
<!DOCTYPE html>
<html class="no-js" lang="fr">
<head>
    <title>{$safeTitle}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{$safeDescription}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.png?v=20260702-favicon" type="image/png">
    <link rel="shortcut icon" href="favicon.png?v=20260702-favicon" type="image/png">
    <link rel="apple-touch-icon" href="favicon.png?v=20260702-favicon">
    <link rel="stylesheet" href="css/site.css">
    <script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body class="{$safeBodyClass}">
<div class="preloader"><div class="preloader_image pulse"></div></div>
HTML;

    rarsm_render_auth_modals();

    echo <<<HTML
<div id="canvas">
<div id="box_wrapper">
HTML;
}

function rarsm_render_header(string $active = 'acheter'): void
{
    $user = rarsm_current_user();
    $totals = rarsm_cart_totals();
    $cartCount = (string) max(0, (int) $totals['item_count']);
    $cartTotal = $totals['contains_quote_only'] && $totals['payable_total'] <= 0
        ? 'Devis'
        : rarsm_format_money((float) $totals['payable_total'], (string) $totals['currency']);

    $navItems = [
        'index.html' => ['label' => 'Accueil', 'key' => 'accueil'],
        'book.html' => ['label' => 'Livre', 'key' => 'livre'],
        'author.html' => ['label' => 'Auteur', 'key' => 'auteur'],
        'pricing.html' => ['label' => 'Shop', 'key' => 'acheter'],
        'institutions.php' => ['label' => 'Institutions', 'key' => 'institutions'],
        'activites.html' => ['label' => 'Activités', 'key' => 'activites'],
        'contact.html' => ['label' => 'Contact', 'key' => 'contact'],
    ];

    echo '<header class="page_header ls s-overlay s-py-10">';
    echo '<div class="container-fluid"><div class="row align-items-center">';
    echo '<div class="col-xl-2 col-lg-3 col-11"><a href="index.html" class="logo"><img src="logo/rarsm-logo-wordmark-color.png" alt="RARSM"></a></div>';
    echo '<div class="col-xl-8 col-lg-6 col-1"><nav class="top-nav"><ul class="nav sf-menu">';

    foreach ($navItems as $href => $item) {
        $liClass = $item['key'] === $active ? ' class="active"' : '';
        echo '<li' . $liClass . '><a href="' . rarsm_e($href) . '">' . rarsm_e($item['label']) . '</a></li>';
    }

    if ($user === null) {
        echo '<li class="menu-auth-item menu-auth-login d-lg-none"><a data-toggle="modal" href="#popupLogin">Se connecter</a></li>';
        echo '<li class="menu-auth-item menu-auth-register d-lg-none"><a data-toggle="modal" href="#popupRegistr">S\'inscrire</a></li>';
    } else {
        $displayName = rarsm_user_display_name($user);
        $initials = rarsm_user_initials($user);
        echo '<li class="menu-session-item d-lg-none">';
        echo '<div class="rarsm-user-menu rarsm-user-menu-mobile">';
        echo '<button class="rarsm-session-nav-link rarsm-user-menu-toggle" type="button" aria-haspopup="true" aria-expanded="false">';
        echo '<span class="rarsm-session-avatar">' . rarsm_e($initials) . '</span>';
        echo '<span class="rarsm-session-text"><span class="rarsm-session-name">' . rarsm_e($displayName) . '</span></span>';
        echo '<i class="fa fa-angle-down rarsm-session-caret" aria-hidden="true"></i>';
        echo '</button>';
        echo '<div class="rarsm-user-menu-panel rarsm-user-menu-panel-mobile" hidden>';
        echo '<a class="rarsm-user-menu-action" href="shop-account-orders.php"><span class="rarsm-user-menu-action-icon"><i class="fa fa-user-o" aria-hidden="true"></i></span><span>Compte</span></a>';
        echo '<a class="rarsm-user-menu-action rarsm-user-menu-action-danger" href="logout.php"><span class="rarsm-user-menu-action-icon"><i class="fa fa-sign-out" aria-hidden="true"></i></span><span>Se déconnecter</span></a>';
        echo '</div>';
        echo '</div>';
        echo '</li>';
    }

    echo '<li class="menu-cart-mobile dropdown d-lg-none">';
    echo '<button class="dropdown-toggle dropdown-shopping-cart" id="dropdown-shopping-cart-mobile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Ouvrir le panier" type="button">';
    echo '<i class="fa fa-shopping-basket" aria-hidden="true"></i>';
    echo '<span class="badge bg-maincolor">' . rarsm_e($cartCount) . '</span>';
    echo '<span class="cart-total">' . rarsm_e($cartTotal) . '</span>';
    echo '</button>';
    echo '<div class="dropdown-menu ls" aria-labelledby="dropdown-shopping-cart-mobile">';
    echo '<div class="widget woocommerce widget_shopping_cart">';
    echo '<div class="widget_shopping_cart_content">';
    echo '<ul class="woocommerce-mini-cart cart_list product_list_widget"></ul>';
    echo '<p class="woocommerce-mini-cart__total total">';
    echo '<strong>Sous-total :</strong>';
    echo '<span class="woocommerce-Price-amount amount">' . rarsm_e($cartTotal) . '</span>';
    echo '</p>';
    echo '<p class="woocommerce-mini-cart__buttons buttons">';
    echo '<a class="button wc-forward" href="shop-cart.php">Voir le panier</a>';
    echo '<a class="button checkout wc-forward" href="shop-checkout.php">Passer à la commande</a>';
    echo '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</li>';
    echo '<li class="menu-language-mobile d-lg-none">';
    echo '<div class="language-switcher js-language-switcher">';
    echo '<button class="language-toggle js-language-toggle" type="button" aria-label="Choix de la langue actuelle" aria-expanded="false">Fr</button>';
    echo '<div class="language-menu" role="menu" aria-label="Choix de la langue">';
    echo '<button class="language-option js-language-option is-active" type="button" role="menuitem" data-language="fr">Fr</button>';
    echo '<button class="language-option js-language-option" type="button" role="menuitem" data-language="en">En</button>';
    echo '</div>';
    echo '</div>';
    echo '</li>';
    echo '</ul></nav></div>';
    echo '<div class="col-xl-2 col-lg-3 text-right d-none d-lg-block"><div class="header-utilities">';

    if ($user === null) {
        echo '<a class="btn btn-outline-maincolor" data-toggle="modal" href="#popupLogin">Se connecter</a>';
        echo '<a class="btn btn-maincolor" data-toggle="modal" href="#popupRegistr">S\'inscrire</a>';
    } else {
        $displayName = rarsm_user_display_name($user);
        $initials = rarsm_user_initials($user);
        echo '<div class="rarsm-user-menu">';
        echo '<button class="rarsm-session-indicator rarsm-user-menu-toggle" type="button" aria-haspopup="true" aria-expanded="false" aria-label="Options du compte">';
        echo '<span class="rarsm-session-avatar">' . rarsm_e($initials) . '</span>';
        echo '<span class="rarsm-session-text"><span class="rarsm-session-name">' . rarsm_e($displayName) . '</span></span>';
        echo '<i class="fa fa-angle-down rarsm-session-caret" aria-hidden="true"></i>';
        echo '</button>';
        echo '<div class="rarsm-user-menu-panel" hidden>';
        echo '<a class="rarsm-user-menu-action" href="shop-account-orders.php"><span class="rarsm-user-menu-action-icon"><i class="fa fa-user-o" aria-hidden="true"></i></span><span>Compte</span></a>';
        echo '<a class="rarsm-user-menu-action rarsm-user-menu-action-danger" href="logout.php"><span class="rarsm-user-menu-action-icon"><i class="fa fa-sign-out" aria-hidden="true"></i></span><span>Se déconnecter</span></a>';
        echo '</div>';
        echo '</div>';
    }

    echo '<a class="dropdown-shopping-cart" href="shop-cart.php" aria-label="Ouvrir le panier">';
    echo '<i class="fa fa-shopping-basket" aria-hidden="true"></i>';
    echo '<span class="badge bg-maincolor">' . rarsm_e($cartCount) . '</span>';
    echo '<span class="cart-total">' . rarsm_e($cartTotal) . '</span>';
    echo '</a>';
    echo '</div></div></div></div>';
    echo '<span class="toggle_menu"><span></span></span>';
    echo '</header>';
}

function rarsm_render_page_title(string $title, array $crumbs): void
{
    echo '<section class="page_title ds s-parallax s-py-110"><div class="container"><div class="row"><div class="col-md-12 text-center">';
    echo '<h1 class="small-title">' . rarsm_e($title) . '</h1>';
    echo '<ol class="breadcrumb">';

    foreach ($crumbs as $index => $crumb) {
        $isLast = $index === array_key_last($crumbs);
        $label = rarsm_e((string) $crumb['label']);

        if ($isLast || empty($crumb['href'])) {
            echo '<li class="breadcrumb-item active">' . $label . '</li>';
            continue;
        }

        echo '<li class="breadcrumb-item"><a href="' . rarsm_e((string) $crumb['href']) . '">' . $label . '</a></li>';
    }

    echo '</ol></div></div></div></section>';
}

function rarsm_render_flash(): void
{
    $flash = rarsm_pull_flash();
    if ($flash === null) {
        return;
    }

    $class = 'woocommerce-info';
    if (($flash['type'] ?? '') === 'succèss') {
        $class = 'woocommerce-message';
    } elseif (($flash['type'] ?? '') === 'error') {
        $class = 'woocommerce-error';
    }

    echo '<div class="woocommerce-notices-wrapper"><div class="' . rarsm_e($class) . '">' . rarsm_e((string) $flash['message']) . '</div></div>';
}

function rarsm_render_footer(): void
{
    $year = date('Y');

    echo <<<HTML
<footer class="page_footer ds s-py-85 s-py-xl-155">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <a href="index.html" class="logo justify-content-center">
                    <img src="logo/rarsm-logo-wordmark-white.png" alt="RARSM">
                </a>
                <div class="divider-40"></div>
                <div class="widget widget_nav_menu nav-in-line">
                    <ul class="menu">
                        <li class="menu-item"><a href="index.html">Accueil</a></li>
                        <li class="menu-item"><a href="book.html">Livre</a></li>
                        <li class="menu-item"><a href="author.html">Auteur</a></li>
                        <li class="menu-item"><a href="pricing.html">Shop</a></li>
                        <li class="menu-item"><a href="institutions.php">Institutions</a></li>
                        <li class="menu-item"><a href="activites.html">Activités</a></li>
                        <li class="menu-item"><a href="contact.html">Contact</a></li>
                        <li class="menu-item"><a href="faq.html">FAQ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<section class="page_copyright ls s-py-20 s-py-xl-50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 text-center color-dark">
                <p>&copy; <span class="copyright_year">{$year}</span> RARSM - Recueil des Actes Reglementaires du Secteur Minier. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</section>
</div>
</div>
<script src="js/compressed.js"></script>
<script src="js/rarsm-ui.js"></script>
</body>
</html>
HTML;
}
