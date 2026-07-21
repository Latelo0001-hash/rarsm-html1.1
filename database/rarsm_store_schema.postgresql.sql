CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(40) DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    address_type VARCHAR(30) NOT NULL DEFAULT 'billing',
    full_name VARCHAR(190) NOT NULL,
    phone VARCHAR(40) DEFAULT NULL,
    country VARCHAR(120) NOT NULL,
    city VARCHAR(120) DEFAULT NULL,
    state_region VARCHAR(120) DEFAULT NULL,
    address_line_1 VARCHAR(255) DEFAULT NULL,
    address_line_2 VARCHAR(255) DEFAULT NULL,
    postal_code VARCHAR(30) DEFAULT NULL,
    is_default BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    sku VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(190) NOT NULL UNIQUE,
    name VARCHAR(190) NOT NULL,
    short_description TEXT,
    description TEXT,
    product_type VARCHAR(30) NOT NULL DEFAULT 'physical',
    price NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    is_quote_only BOOLEAN NOT NULL DEFAULT FALSE,
    requires_shipping BOOLEAN NOT NULL DEFAULT TRUE,
    stock_quantity INT NOT NULL DEFAULT 0,
    cover_image VARCHAR(255) DEFAULT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE carts (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT DEFAULT NULL REFERENCES users (id) ON DELETE SET NULL,
    session_token VARCHAR(190) DEFAULT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart_items (
    id BIGSERIAL PRIMARY KEY,
    cart_id BIGINT NOT NULL REFERENCES carts (id) ON DELETE CASCADE,
    product_id BIGINT NOT NULL REFERENCES products (id) ON DELETE RESTRICT,
    quantity INT NOT NULL DEFAULT 1,
    unit_price NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    order_number VARCHAR(80) NOT NULL UNIQUE,
    user_id BIGINT DEFAULT NULL REFERENCES users (id) ON DELETE SET NULL,
    billing_address_id BIGINT DEFAULT NULL REFERENCES addresses (id) ON DELETE SET NULL,
    shipping_address_id BIGINT DEFAULT NULL REFERENCES addresses (id) ON DELETE SET NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'pending_payment',
    payment_status VARCHAR(40) NOT NULL DEFAULT 'initiated',
    payment_method VARCHAR(60) DEFAULT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    subtotal NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    shipping_amount NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    total_amount NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    payable_amount NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    notes TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders (id) ON DELETE CASCADE,
    product_id BIGINT DEFAULT NULL REFERENCES products (id) ON DELETE SET NULL,
    sku VARCHAR(80) NOT NULL,
    product_name VARCHAR(190) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    line_total NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    is_quote_only BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders (id) ON DELETE CASCADE,
    provider VARCHAR(80) NOT NULL,
    provider_reference VARCHAR(190) DEFAULT NULL,
    payment_url VARCHAR(255) DEFAULT NULL,
    amount NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    status VARCHAR(40) NOT NULL DEFAULT 'initiated',
    paid_at TIMESTAMP DEFAULT NULL,
    raw_response TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE OR REPLACE FUNCTION rarsm_set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_users_updated_at BEFORE UPDATE ON users
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_addresses_updated_at BEFORE UPDATE ON addresses
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_products_updated_at BEFORE UPDATE ON products
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_carts_updated_at BEFORE UPDATE ON carts
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_cart_items_updated_at BEFORE UPDATE ON cart_items
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_orders_updated_at BEFORE UPDATE ON orders
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_order_items_updated_at BEFORE UPDATE ON order_items
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

CREATE TRIGGER trg_payments_updated_at BEFORE UPDATE ON payments
FOR EACH ROW EXECUTE FUNCTION rarsm_set_updated_at();

INSERT INTO products (
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
    cover_image
) VALUES
(
    'RARSM-PRINT',
    'rarsm-edition-papier',
    'RARSM - Edition papier',
    'L’exemplaire physique de reference pour les professionnels et institutions.',
    'Le recueil papier prioritaire pour la bibliotheque de travail, les cabinets et les administrations.',
    'physical',
    400.00,
    'USD',
    FALSE,
    TRUE,
    200,
    'images/view-rarsm.JPG'
),
(
    'RARSM-DIGITAL',
    'rarsm-version-numerique',
    'RARSM - Version numerique',
    'Le format numerique pour une consultation rapide sur tous les ecrans.',
    'Une edition dematerialisee adaptee aux lecteurs mobiles et aux equipes qui souhaitent un acces immediat.',
    'digital',
    380.00,
    'USD',
    FALSE,
    FALSE,
    9999,
    'images/rarsm-generated/about-rarsm.png'
),
(
    'RARSM-INSTITUTION',
    'rarsm-commande-institutionnelle',
    'RARSM - Commande institutionnelle',
    'Demande de devis pour les structures souhaitant plusieurs exemplaires.',
    'Une commande sur devis pour les institutions, entreprises, administrations et bibliotheques.',
    'service',
    0.00,
    'USD',
    TRUE,
    TRUE,
    0,
    'images/rarsm-generated/hero-presentation-rarsm.png'
);
