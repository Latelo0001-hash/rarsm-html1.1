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
            'price' => 400.00,
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
            'price' => 380.00,
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
        'rarsm-tshirt' => [
            'id' => 'rarsm-tshirt',
            'sku' => 'RARSM-TSHIRT',
            'name' => 'T-shirt RARSM',
            'slug' => 'tshirt-rarsm',
            'price' => 35.00,
            'currency' => 'USD',
            'quote_only' => false,
            'requires_shipping' => true,
            'image' => 'images/rarsm-generated/cadre-institutionnel.png',
            'short_description' => 'Un t-shirt de presentation aux couleurs RARSM pour conferences, salons et activites publiques.',
        ],
        'rarsm-cap' => [
            'id' => 'rarsm-cap',
            'sku' => 'RARSM-CAP',
            'name' => 'Casquette RARSM',
            'slug' => 'casquette-rarsm',
            'price' => 22.00,
            'currency' => 'USD',
            'quote_only' => false,
            'requires_shipping' => true,
            'image' => 'images/rarsm-generated/suivi-droits-filieres.png',
            'short_description' => 'Une casquette siglee RARSM pour les lecteurs, equipes et activites de terrain.',
        ],
        'rarsm-totebag' => [
            'id' => 'rarsm-totebag',
            'sku' => 'RARSM-TOTEBAG',
            'name' => 'Tote bag RARSM',
            'slug' => 'tote-bag-rarsm',
            'price' => 18.00,
            'currency' => 'USD',
            'quote_only' => false,
            'requires_shipping' => true,
            'image' => 'images/rarsm-generated/tracabilite-certification.png',
            'short_description' => 'Un sac textile pratique pour transporter le livre, vos notes et vos documents de travail.',
        ],
    ];
}

function rarsm_store_product(string $productId): ?array
{
    $catalog = rarsm_store_catalog();

    return $catalog[$productId] ?? null;
}

function rarsm_store_uses_database(): bool
{
    static $ready = null;

    if ($ready !== null) {
        return $ready;
    }

    if (!rarsm_db() instanceof PDO) {
        $ready = false;
        return false;
    }

    foreach (['products', 'carts', 'cart_items', 'orders', 'order_items'] as $table) {
        if (!rarsm_db_has_table($table)) {
            $ready = false;
            return false;
        }
    }

    $ready = true;

    return true;
}

function rarsm_store_can_persist_carts(): bool
{
    return rarsm_store_uses_database();
}

function rarsm_store_can_persist_orders(): bool
{
    return rarsm_store_uses_database();
}

function rarsm_store_local_product_id_from_sku(string $sku): ?string
{
    $normalizedSku = strtoupper(trim($sku));
    if ($normalizedSku === '') {
        return null;
    }

    foreach (rarsm_store_catalog() as $productId => $product) {
        if (strtoupper((string) ($product['sku'] ?? '')) === $normalizedSku) {
            return $productId;
        }
    }

    return null;
}

function rarsm_normalize_cart_payload(array $cart): array
{
    $normalizedCart = [];

    foreach ($cart as $productId => $quantity) {
        $product = rarsm_store_product((string) $productId);
        if ($product === null) {
            continue;
        }

        $normalizedQuantity = (int) $quantity;
        if ($normalizedQuantity < 1) {
            continue;
        }

        $normalizedCart[(string) $productId] = min($normalizedQuantity, 99);
    }

    return $normalizedCart;
}

function rarsm_session_cart_snapshot(): array
{
    $cart = $_SESSION['rarsm_cart'] ?? [];

    return is_array($cart) ? rarsm_normalize_cart_payload($cart) : [];
}

function rarsm_store_set_session_cart(array $cart): void
{
    $_SESSION['rarsm_cart'] = rarsm_normalize_cart_payload($cart);
}

function rarsm_store_product_type(array $product): string
{
    if (!empty($product['quote_only'])) {
        return 'service';
    }

    return !empty($product['requires_shipping']) ? 'physical' : 'digital';
}

function rarsm_store_product_row(string $productId): ?array
{
    if (!rarsm_store_uses_database()) {
        return null;
    }

    $product = rarsm_store_product($productId);
    $pdo = rarsm_db();

    if ($product === null || !$pdo instanceof PDO) {
        return null;
    }

    try {
        $statement = $pdo->prepare('SELECT * FROM products WHERE sku = :sku LIMIT 1');
        $statement->execute([
            ':sku' => (string) $product['sku'],
        ]);
        $row = $statement->fetch();

        if (is_array($row)) {
            return $row;
        }

        $insert = $pdo->prepare(
            'INSERT INTO products (
                sku,
                slug,
                name,
                short_description,
                description,
                product_type,
                price,
                currency,
                is_quote_only,
                requires_shipping,
                stock_quantity,
                cover_image,
                is_active
            ) VALUES (
                :sku,
                :slug,
                :name,
                :short_description,
                :description,
                :product_type,
                :price,
                :currency,
                :is_quote_only,
                :requires_shipping,
                :stock_quantity,
                :cover_image,
                1
            )'
        );

        $insert->execute([
            ':sku' => (string) $product['sku'],
            ':slug' => (string) $product['slug'],
            ':name' => (string) $product['name'],
            ':short_description' => (string) $product['short_description'],
            ':description' => (string) $product['short_description'],
            ':product_type' => rarsm_store_product_type($product),
            ':price' => (float) $product['price'],
            ':currency' => (string) $product['currency'],
            ':is_quote_only' => !empty($product['quote_only']) ? 1 : 0,
            ':requires_shipping' => !empty($product['requires_shipping']) ? 1 : 0,
            ':stock_quantity' => !empty($product['quote_only']) ? 0 : (!empty($product['requires_shipping']) ? 200 : 9999),
            ':cover_image' => (string) $product['image'],
        ]);

        $statement->execute([
            ':sku' => (string) $product['sku'],
        ]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    } catch (Throwable $exception) {
        return null;
    }
}

function rarsm_store_active_cart_id(int $userId, bool $create = true): ?int
{
    if ($userId < 1 || !rarsm_store_can_persist_carts()) {
        return null;
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return null;
    }

    try {
        $statement = $pdo->prepare(
            'SELECT id
             FROM carts
             WHERE user_id = :user_id
               AND status = :status
             ORDER BY updated_at DESC, id DESC
             LIMIT 1'
        );
        $statement->execute([
            ':user_id' => $userId,
            ':status' => 'active',
        ]);

        $cartId = $statement->fetchColumn();
        if ($cartId !== false) {
            return (int) $cartId;
        }

        if (!$create) {
            return null;
        }

        $cartId = rarsm_db_insert_and_get_id(
            $pdo,
            'INSERT INTO carts (user_id, status)
             VALUES (:user_id, :status)',
            [
            ':user_id' => $userId,
            ':status' => 'active',
            ]
        );

        return $cartId !== null && $cartId > 0 ? $cartId : null;
    } catch (Throwable $exception) {
        return null;
    }
}

function rarsm_store_db_cart_raw(int $userId): array
{
    if ($userId < 1 || !rarsm_store_can_persist_carts()) {
        return [];
    }

    $pdo = rarsm_db();
    $cartId = rarsm_store_active_cart_id($userId, false);

    if (!$pdo instanceof PDO || $cartId === null) {
        return [];
    }

    try {
        $statement = $pdo->prepare(
            'SELECT p.sku, ci.quantity
             FROM cart_items ci
             INNER JOIN products p ON p.id = ci.product_id
             WHERE ci.cart_id = :cart_id
             ORDER BY ci.id ASC'
        );
        $statement->execute([
            ':cart_id' => $cartId,
        ]);

        $cart = [];
        foreach ($statement->fetchAll() as $row) {
            $productId = rarsm_store_local_product_id_from_sku((string) ($row['sku'] ?? ''));
            if ($productId === null) {
                continue;
            }

            $cart[$productId] = min(99, max(1, (int) ($row['quantity'] ?? 1)));
        }

        return $cart;
    } catch (Throwable $exception) {
        return [];
    }
}

function rarsm_store_replace_db_cart_from_session(int $userId): void
{
    if ($userId < 1 || !rarsm_store_can_persist_carts()) {
        return;
    }

    $pdo = rarsm_db();
    $cartId = rarsm_store_active_cart_id($userId, true);

    if (!$pdo instanceof PDO || $cartId === null) {
        return;
    }

    $cart = rarsm_session_cart_snapshot();

    try {
        $pdo->beginTransaction();

        $delete = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = :cart_id');
        $delete->execute([
            ':cart_id' => $cartId,
        ]);

        if (!empty($cart)) {
            $insert = $pdo->prepare(
                'INSERT INTO cart_items (cart_id, product_id, quantity, unit_price)
                 VALUES (:cart_id, :product_id, :quantity, :unit_price)'
            );

            foreach ($cart as $productId => $quantity) {
                $product = rarsm_store_product($productId);
                $productRow = rarsm_store_product_row($productId);

                if ($product === null || !is_array($productRow)) {
                    continue;
                }

                $insert->execute([
                    ':cart_id' => $cartId,
                    ':product_id' => (int) $productRow['id'],
                    ':quantity' => min(99, max(1, (int) $quantity)),
                    ':unit_price' => (float) $product['price'],
                ]);
            }
        }

        $touch = $pdo->prepare('UPDATE carts SET updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $touch->execute([
            ':id' => $cartId,
        ]);

        $pdo->commit();
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    }
}

function rarsm_sync_current_user_cart_storage(): void
{
    $userId = rarsm_current_user_id();
    if ($userId < 1) {
        return;
    }

    rarsm_store_replace_db_cart_from_session($userId);
}

function rarsm_restore_authenticated_customer_state(): void
{
    $userId = rarsm_current_user_id();
    if ($userId < 1 || !rarsm_store_can_persist_carts()) {
        return;
    }

    $sessionCart = rarsm_session_cart_snapshot();
    $persistentCart = rarsm_store_db_cart_raw($userId);
    $mergedCart = $persistentCart;

    foreach ($sessionCart as $productId => $quantity) {
        $mergedCart[$productId] = min(99, ($mergedCart[$productId] ?? 0) + max(1, (int) $quantity));
    }

    rarsm_store_set_session_cart($mergedCart);
    rarsm_store_replace_db_cart_from_session($userId);
}

function rarsm_cart_raw(): array
{
    $hasSessionCart = isset($_SESSION['rarsm_cart']) && is_array($_SESSION['rarsm_cart']);

    if (!$hasSessionCart && rarsm_current_user_id() > 0 && rarsm_store_can_persist_carts()) {
        $_SESSION['rarsm_cart'] = rarsm_store_db_cart_raw(rarsm_current_user_id());
    }

    if (!isset($_SESSION['rarsm_cart']) || !is_array($_SESSION['rarsm_cart'])) {
        $_SESSION['rarsm_cart'] = [];
    }

    return rarsm_normalize_cart_payload($_SESSION['rarsm_cart']);
}

function rarsm_cart_add(string $productId, int $quantity = 1): bool
{
    $product = rarsm_store_product($productId);
    if ($product === null) {
        return false;
    }

    $quantity = max(1, $quantity);
    $cart = rarsm_cart_raw();
    $cart[$productId] = min(99, ($cart[$productId] ?? 0) + $quantity);
    rarsm_store_set_session_cart($cart);
    rarsm_sync_current_user_cart_storage();

    return true;
}

function rarsm_cart_remove(string $productId): void
{
    $cart = rarsm_cart_raw();
    unset($cart[$productId]);
    rarsm_store_set_session_cart($cart);
    rarsm_sync_current_user_cart_storage();
}

function rarsm_cart_update(array $quantities): void
{
    rarsm_store_set_session_cart($quantities);
    rarsm_sync_current_user_cart_storage();
}

function rarsm_cart_clear(): void
{
    $_SESSION['rarsm_cart'] = [];
    rarsm_sync_current_user_cart_storage();
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

function rarsm_session_orders(): array
{
    if (!isset($_SESSION['rarsm_orders']) || !is_array($_SESSION['rarsm_orders'])) {
        $_SESSION['rarsm_orders'] = [];
    }

    return $_SESSION['rarsm_orders'];
}

function rarsm_orders(): array
{
    $userId = rarsm_current_user_id();
    if ($userId > 0 && rarsm_store_can_persist_orders()) {
        return rarsm_store_fetch_orders_for_user($userId);
    }

    return rarsm_session_orders();
}

function rarsm_find_order(?string $orderId = null): ?array
{
    if ($orderId === null || $orderId === '') {
        $orderId = (string) ($_SESSION['rarsm_latest_order_id'] ?? '');
    }

    if ($orderId === '') {
        return null;
    }

    if (rarsm_store_can_persist_orders()) {
        $order = rarsm_store_fetch_order_by_id($orderId);
        if ($order !== null) {
            return $order;
        }
    }

    $orders = rarsm_session_orders();

    return $orders[$orderId] ?? null;
}

function rarsm_save_order(array $order): void
{
    $orderId = (string) ($order['id'] ?? '');
    if ($orderId === '') {
        return;
    }

    $_SESSION['rarsm_latest_order_id'] = $orderId;

    if (!(rarsm_store_can_persist_orders() && ctype_digit($orderId))) {
        $_SESSION['rarsm_orders'][$orderId] = $order;
    }
}

function rarsm_generate_order_number(): string
{
    $suffix = strtoupper(substr(hash('sha1', uniqid('', true)), 0, 4));

    return 'RARSM-' . date('Ymd-His') . '-' . $suffix;
}

function rarsm_create_session_order(array $checkoutData): array
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

function rarsm_store_fetch_order_items_from_db(int $orderId, string $currency): array
{
    if ($orderId < 1 || !rarsm_store_can_persist_orders()) {
        return [];
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return [];
    }

    try {
        $statement = $pdo->prepare(
            'SELECT oi.*, p.cover_image
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :order_id
             ORDER BY oi.id ASC'
        );
        $statement->execute([
            ':order_id' => $orderId,
        ]);

        $items = [];

        foreach ($statement->fetchAll() as $row) {
            $localProductId = rarsm_store_local_product_id_from_sku((string) ($row['sku'] ?? ''));
            $catalogProduct = $localProductId !== null ? rarsm_store_product($localProductId) : null;

            $items[] = [
                'id' => $localProductId ?? (string) ($row['sku'] ?? ''),
                'sku' => (string) ($row['sku'] ?? ''),
                'name' => (string) ($row['product_name'] ?? ''),
                'image' => (string) ($catalogProduct['image'] ?? $row['cover_image'] ?? 'images/view-rarsm.JPG'),
                'price' => (float) ($row['unit_price'] ?? 0),
                'currency' => $currency,
                'quantity' => (int) ($row['quantity'] ?? 0),
                'subtotal' => (float) ($row['line_total'] ?? 0),
                'quote_only' => !empty($row['is_quote_only']),
                'requires_shipping' => !empty($catalogProduct['requires_shipping']),
                'short_description' => (string) ($catalogProduct['short_description'] ?? ''),
            ];
        }

        return $items;
    } catch (Throwable $exception) {
        return [];
    }
}

function rarsm_store_map_order_row(array $row): array
{
    $currency = (string) ($row['currency'] ?? 'USD');
    $orderId = (int) ($row['id'] ?? 0);
    $items = rarsm_store_fetch_order_items_from_db($orderId, $currency);

    $containsQuoteOnly = false;
    $containsPhysical = false;

    foreach ($items as $item) {
        $containsQuoteOnly = $containsQuoteOnly || !empty($item['quote_only']);
        $containsPhysical = $containsPhysical || !empty($item['requires_shipping']);
    }

    return [
        'id' => (string) ($row['id'] ?? ''),
        'order_number' => (string) ($row['order_number'] ?? ''),
        'created_at' => (string) ($row['created_at'] ?? ''),
        'updated_at' => (string) ($row['updated_at'] ?? ''),
        'status' => (string) ($row['status'] ?? ''),
        'payment_status' => (string) ($row['payment_status'] ?? ''),
        'payment_method' => (string) ($row['payment_method'] ?? ''),
        'currency' => $currency,
        'subtotal' => (float) ($row['subtotal'] ?? 0),
        'payable_total' => (float) ($row['payable_amount'] ?? 0),
        'contains_quote_only' => $containsQuoteOnly,
        'contains_physical' => $containsPhysical,
        'items' => $items,
        'notes' => (string) ($row['notes'] ?? ''),
        'user' => rarsm_current_user(),
    ];
}

function rarsm_store_fetch_order_by_id(string $orderId): ?array
{
    if (!rarsm_store_can_persist_orders() || !ctype_digit($orderId)) {
        return null;
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return null;
    }

    $userId = rarsm_current_user_id();
    $latestOrderId = (string) ($_SESSION['rarsm_latest_order_id'] ?? '');

    if ($userId < 1 && $latestOrderId !== $orderId) {
        return null;
    }

    try {
        if ($userId > 0) {
            $statement = $pdo->prepare(
                'SELECT *
                 FROM orders
                 WHERE id = :id
                   AND user_id = :user_id
                 LIMIT 1'
            );
            $statement->execute([
                ':id' => (int) $orderId,
                ':user_id' => $userId,
            ]);
        } else {
            $statement = $pdo->prepare(
                'SELECT *
                 FROM orders
                 WHERE id = :id
                 LIMIT 1'
            );
            $statement->execute([
                ':id' => (int) $orderId,
            ]);
        }

        $row = $statement->fetch();

        return is_array($row) ? rarsm_store_map_order_row($row) : null;
    } catch (Throwable $exception) {
        return null;
    }
}

function rarsm_store_fetch_orders_for_user(int $userId): array
{
    if ($userId < 1 || !rarsm_store_can_persist_orders()) {
        return [];
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return [];
    }

    try {
        $statement = $pdo->prepare(
            'SELECT *
             FROM orders
             WHERE user_id = :user_id
             ORDER BY created_at ASC, id ASC'
        );
        $statement->execute([
            ':user_id' => $userId,
        ]);

        $orders = [];
        foreach ($statement->fetchAll() as $row) {
            $order = rarsm_store_map_order_row($row);
            $orders[(string) $order['id']] = $order;
        }

        return $orders;
    } catch (Throwable $exception) {
        return [];
    }
}

function rarsm_create_order(array $checkoutData): array
{
    $items = rarsm_cart_items();
    $totals = rarsm_cart_totals();
    $user = rarsm_current_user();
    $userId = rarsm_current_user_id();

    if ($userId < 1 || !rarsm_store_can_persist_orders()) {
        return rarsm_create_session_order($checkoutData);
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return rarsm_create_session_order($checkoutData);
    }

    $paymentMethod = (string) ($checkoutData['payment_method'] ?? 'partner_gateway');
    $status = 'pending_payment';
    $paymentStatus = 'initiated';

    if ($totals['contains_quote_only'] || $totals['payable_total'] <= 0) {
        $status = 'pending_quote';
        $paymentStatus = 'pending';
    }

    try {
        $pdo->beginTransaction();

        $orderId = rarsm_db_insert_and_get_id(
            $pdo,
            'INSERT INTO orders (
                order_number,
                user_id,
                status,
                payment_status,
                payment_method,
                currency,
                subtotal,
                shipping_amount,
                total_amount,
                payable_amount,
                notes
            ) VALUES (
                :order_number,
                :user_id,
                :status,
                :payment_status,
                :payment_method,
                :currency,
                :subtotal,
                :shipping_amount,
                :total_amount,
                :payable_amount,
                :notes
            )',
            [
            ':order_number' => rarsm_generate_order_number(),
            ':user_id' => $userId,
            ':status' => $status,
            ':payment_status' => $paymentStatus,
            ':payment_method' => $paymentMethod,
            ':currency' => (string) $totals['currency'],
            ':subtotal' => (float) $totals['subtotal'],
            ':shipping_amount' => 0,
            ':total_amount' => (float) $totals['subtotal'],
            ':payable_amount' => (float) $totals['payable_total'],
            ':notes' => trim((string) ($checkoutData['notes'] ?? '')),
            ]
        );

        if ($orderId === null || $orderId < 1) {
            throw new RuntimeException('Unable to create order record.');
        }

        $insertItem = $pdo->prepare(
            'INSERT INTO order_items (
                order_id,
                product_id,
                sku,
                product_name,
                quantity,
                unit_price,
                line_total,
                is_quote_only
            ) VALUES (
                :order_id,
                :product_id,
                :sku,
                :product_name,
                :quantity,
                :unit_price,
                :line_total,
                :is_quote_only
            )'
        );

        foreach ($items as $item) {
            $productRow = rarsm_store_product_row((string) $item['id']);

            $insertItem->execute([
                ':order_id' => $orderId,
                ':product_id' => is_array($productRow) ? (int) $productRow['id'] : null,
                ':sku' => (string) $item['sku'],
                ':product_name' => (string) $item['name'],
                ':quantity' => (int) $item['quantity'],
                ':unit_price' => (float) $item['price'],
                ':line_total' => (float) $item['subtotal'],
                ':is_quote_only' => !empty($item['quote_only']) ? 1 : 0,
            ]);
        }

        $pdo->commit();

        $_SESSION['rarsm_latest_order_id'] = (string) $orderId;
        rarsm_cart_clear();

        $order = rarsm_store_fetch_order_by_id((string) $orderId);
        if ($order !== null) {
            return $order;
        }
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    }

    $fallbackOrder = rarsm_create_session_order($checkoutData);
    $fallbackOrder['user'] = $user;

    return $fallbackOrder;
}

function rarsm_update_order_status(string $orderId, string $status, string $paymentStatus): ?array
{
    if (rarsm_store_can_persist_orders() && ctype_digit($orderId)) {
        $pdo = rarsm_db();
        $existingOrder = rarsm_store_fetch_order_by_id($orderId);

        if ($pdo instanceof PDO && $existingOrder !== null) {
            try {
                $statement = $pdo->prepare(
                    'UPDATE orders
                     SET status = :status,
                         payment_status = :payment_status,
                         updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id'
                );
                $statement->execute([
                    ':status' => $status,
                    ':payment_status' => $paymentStatus,
                    ':id' => (int) $orderId,
                ]);

                $_SESSION['rarsm_latest_order_id'] = $orderId;

                return rarsm_store_fetch_order_by_id($orderId);
            } catch (Throwable $exception) {
                return $existingOrder;
            }
        }
    }

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
