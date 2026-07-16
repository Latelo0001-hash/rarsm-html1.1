<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$user = rarsm_current_user();
$cartItems = rarsm_cart_items();
$cartTotals = rarsm_cart_totals();
$cartDisplayTotal = ($cartTotals['contains_quote_only'] ?? false) && (float) ($cartTotals['payable_total'] ?? 0) <= 0
    ? 'Devis'
    : rarsm_format_money((float) ($cartTotals['payable_total'] ?? 0), (string) ($cartTotals['currency'] ?? 'USD'));

$response = [
    'authenticated' => $user !== null,
    'user' => null,
    'cart' => [
        'item_count' => (int) ($cartTotals['item_count'] ?? 0),
        'is_empty' => (bool) ($cartTotals['is_empty'] ?? true),
        'display_total' => $cartDisplayTotal,
        'items' => [],
    ],
    'links' => [
        'account' => 'shop-account-orders.php',
        'logout' => 'logout.php',
        'login' => 'shop-account-login.php',
        'register' => 'shop-account-register.php',
    ],
];

if ($user !== null) {
    $response['user'] = [
        'display_name' => rarsm_user_display_name($user),
        'initials' => rarsm_user_initials($user),
    ];
}

foreach ($cartItems as $item) {
    $response['cart']['items'][] = [
        'id' => (string) ($item['id'] ?? ''),
        'name' => (string) ($item['name'] ?? ''),
        'image' => (string) ($item['image'] ?? ''),
        'quantity' => (int) ($item['quantity'] ?? 0),
        'display_subtotal' => !empty($item['quote_only'])
            ? 'Devis'
            : rarsm_format_money((float) ($item['subtotal'] ?? 0), (string) ($item['currency'] ?? 'USD')),
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
