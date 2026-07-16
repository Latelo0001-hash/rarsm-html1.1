<?php
declare(strict_types=1);

function rarsm_store_catalog(): array
{
    return [
        'rarsm-print' => [
            'id' => 'rarsm-print',
            'sku' => 'RARSM-PRINT',
            'name' => 'RARSM - Edition papier',
            'slug' => 'rarsm-edition-papier',
            'price' => 27.00,
            'currency' => 'USD',
            'quote_only' => false,
            'requires_shipping' => true,
            'image' => 'images/view-rarsm.JPG',
            'short_description' => 'L’exemplaire physique du recueil pour cabinets, institutions et bibliotheques.',
        ],
        'rarsm-digital' => [
            'id' => 'rarsm-digital',
            'sku' => 'RARSM-DIGITAL',
            'name' => 'RARSM - Version numerique',
            'slug' => 'rarsm-version-numerique',
            'price' => 18.00,
            'currency' => 'USD',
            'quote_only' => false,
            'requires_shipping' => false,
            'image' => 'images/rarsm-generated/about-rarsm.png',
            'short_description' => 'Le format numerique pour une consultation rapide sur ordinateur, tablette ou smartphone.',
        ],
        'rarsm-institutional' => [
            'id' => 'rarsm-institutional',
            'sku' => 'RARSM-INSTITUTION',
            'name' => 'RARSM - Commande institutionnelle',
            'slug' => 'rarsm-commande-institutionnelle',
            'price' => 0.00,
            'currency' => 'USD',
            'quote_only' => true,
            'requires_shipping' => true,
            'image' => 'images/rarsm-generated/hero-presentation-rarsm.png',
            'short_description' => 'Une demande groupee avec devis, facture pro forma ou bon de commande.',
        ],
    ];
}

function rarsm_store_product(string $productId): ?array
{
    $catalog = rarsm_store_catalog();

    return $catalog[$productId] ?? null;
}

function rarsm_cart_raw(): array
{
    if (!isset($_SESSION['rarsm_cart']) || !is_array($_SESSION['rarsm_cart'])) {
        $_SESSION['rarsm_cart'] = [];
    }

    return $_SESSION['rarsm_cart'];
}

function rarsm_cart_add(string $productId, int $quantity = 1): bool
{
    $product = rarsm_store_product($productId);
    if ($product === null) {
        return false;
    }

    $quantity = max(1, $quantity);
    $cart = rarsm_cart_raw();
    $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
    $_SESSION['rarsm_cart'] = $cart;

    return true;
}

function rarsm_cart_remove(string $productId): void
{
    $cart = rarsm_cart_raw();
    unset($cart[$productId]);
    $_SESSION['rarsm_cart'] = $cart;
}

function rarsm_cart_update(array $quantities): void
{
    $cart = [];

    foreach ($quantities as $productId => $quantity) {
        $product = rarsm_store_product((string) $productId);
        if ($product === null) {
            continue;
        }

        $normalized = (int) $quantity;
        if ($normalized < 1) {
            continue;
        }

        $cart[(string) $productId] = min($normalized, 99);
    }

    $_SESSION['rarsm_cart'] = $cart;
}

function rarsm_cart_clear(): void
{
    $_SESSION['rarsm_cart'] = [];
}

function rarsm_cart_items(): array
{
    $items = [];

    foreach (rarsm_cart_raw() as $productId => $quantity) {
        $product = rarsm_store_product((string) $productId);
        if ($product === null) {
            continue;
        }

        $qty = max(1, (int) $quantity);
        $subtotal = (float) $product['price'] * $qty;

        $items[] = [
            'id' => $product['id'],
            'sku' => $product['sku'],
            'name' => $product['name'],
            'image' => $product['image'],
            'price' => (float) $product['price'],
            'currency' => $product['currency'],
            'quantity' => $qty,
            'subtotal' => $subtotal,
            'quote_only' => (bool) $product['quote_only'],
            'requires_shipping' => (bool) $product['requires_shipping'],
            'short_description' => $product['short_description'],
        ];
    }

    return $items;
}

function rarsm_cart_totals(): array
{
    $subtotal = 0.0;
    $payableTotal = 0.0;
    $itemCount = 0;
    $containsQuoteOnly = false;
    $containsPhysical = false;

    foreach (rarsm_cart_items() as $item) {
        $subtotal += (float) $item['subtotal'];
        $itemCount += (int) $item['quantity'];
        $containsQuoteOnly = $containsQuoteOnly || (bool) $item['quote_only'];
        $containsPhysical = $containsPhysical || (bool) $item['requires_shipping'];

        if (!$item['quote_only']) {
            $payableTotal += (float) $item['subtotal'];
        }
    }

    return [
        'subtotal' => $subtotal,
        'payable_total' => $payableTotal,
        'item_count' => $itemCount,
        'contains_quote_only' => $containsQuoteOnly,
        'contains_physical' => $containsPhysical,
        'is_empty' => $itemCount === 0,
        'currency' => 'USD',
    ];
}

function rarsm_orders(): array
{
    if (!isset($_SESSION['rarsm_orders']) || !is_array($_SESSION['rarsm_orders'])) {
        $_SESSION['rarsm_orders'] = [];
    }

    return $_SESSION['rarsm_orders'];
}

function rarsm_find_order(?string $orderId = null): ?array
{
    $orders = rarsm_orders();

    if ($orderId === null || $orderId === '') {
        $orderId = (string) ($_SESSION['rarsm_latest_order_id'] ?? '');
    }

    return $orders[$orderId] ?? null;
}

function rarsm_save_order(array $order): void
{
    $_SESSION['rarsm_orders'][$order['id']] = $order;
    $_SESSION['rarsm_latest_order_id'] = $order['id'];
}

function rarsm_generate_order_number(): string
{
    $suffix = strtoupper(substr(hash('sha1', uniqid('', true)), 0, 4));

    return 'RARSM-' . date('Ymd-His') . '-' . $suffix;
}

function rarsm_create_order(array $checkoutData): array
{
    $items = rarsm_cart_items();
    $totals = rarsm_cart_totals();
    $user = rarsm_current_user();
    $orderId = substr(hash('sha256', uniqid('', true)), 0, 16);
    $paymentMethod = (string) ($checkoutData['payment_method'] ?? 'partner_gateway');

    $status = 'pending_payment';
    $paymentStatus = 'initiated';

    if ($totals['contains_quote_only'] || $totals['payable_total'] <= 0) {
        $status = 'pending_quote';
        $paymentStatus = 'pending';
    }

    $order = [
        'id' => $orderId,
        'order_number' => rarsm_generate_order_number(),
        'created_at' => date('Y-m-d H:i:s'),
        'status' => $status,
        'payment_status' => $paymentStatus,
        'payment_method' => $paymentMethod,
        'currency' => $totals['currency'],
        'subtotal' => $totals['subtotal'],
        'payable_total' => $totals['payable_total'],
        'contains_quote_only' => $totals['contains_quote_only'],
        'contains_physical' => $totals['contains_physical'],
        'items' => $items,
        'user' => $user,
        'checkout' => [
            'first_name' => trim((string) ($checkoutData['first_name'] ?? '')),
            'last_name' => trim((string) ($checkoutData['last_name'] ?? '')),
            'company' => trim((string) ($checkoutData['company'] ?? '')),
            'email' => trim((string) ($checkoutData['email'] ?? '')),
            'phone' => trim((string) ($checkoutData['phone'] ?? '')),
            'country' => trim((string) ($checkoutData['country'] ?? '')),
            'address_1' => trim((string) ($checkoutData['address_1'] ?? '')),
            'city' => trim((string) ($checkoutData['city'] ?? '')),
            'state' => trim((string) ($checkoutData['state'] ?? '')),
            'postal_code' => trim((string) ($checkoutData['postal_code'] ?? '')),
            'delivery_mode' => trim((string) ($checkoutData['delivery_mode'] ?? '')),
            'notes' => trim((string) ($checkoutData['notes'] ?? '')),
        ],
    ];

    rarsm_save_order($order);
    rarsm_cart_clear();

    return $order;
}

function rarsm_update_order_status(string $orderId, string $status, string $paymentStatus): ?array
{
    $order = rarsm_find_order($orderId);
    if ($order === null) {
        return null;
    }

    $order['status'] = $status;
    $order['payment_status'] = $paymentStatus;
    $order['updated_at'] = date('Y-m-d H:i:s');

    rarsm_save_order($order);

    return $order;
}
