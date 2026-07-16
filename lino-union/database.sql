-- ============================================
-- LINO UNION - Premium Linen Clothing Database
-- ============================================

CREATE DATABASE IF NOT EXISTS lino_union;
USE lino_union;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'United States',
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products Table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    stock_quantity INT DEFAULT 0,
    category_id INT,
    material VARCHAR(100),
    care_instructions TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    is_new_arrival BOOLEAN DEFAULT FALSE,
    is_best_seller BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product Images Table
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Product Variants Table (Size & Color)
CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size VARCHAR(50),
    color VARCHAR(50),
    color_hex VARCHAR(7),
    stock_quantity INT DEFAULT 0,
    price_adjustment DECIMAL(10,2) DEFAULT 0.00,
    sku_suffix VARCHAR(20),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cart Table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    variant_id INT,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Wishlist Table
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    user_id INT,
    session_id VARCHAR(255),
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) DEFAULT 'United States',
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) DEFAULT 0.00,
    tax DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'stripe',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    product_sku VARCHAR(50),
    variant_info VARCHAR(255),
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact Messages Table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Newsletter Subscribers Table
CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Seed Data
-- ============================================

-- Admin User (password: admin123)
INSERT INTO users (first_name, last_name, email, password, is_admin) VALUES
('Admin', 'LINO', 'admin@linounion.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE);

-- Categories
INSERT INTO categories (name, slug, description, sort_order) VALUES
('Men', 'men', 'Premium linen clothing for men', 1),
('Women', 'women', 'Elegant linen clothing for women', 2),
('Kids', 'kids', 'Comfortable linen clothing for kids', 3);

-- Subcategories
INSERT INTO categories (name, slug, description, parent_id, sort_order) VALUES
('Men Shirts', 'men-shirts', 'Linen shirts for men', 1, 1),
('Men Pants', 'men-pants', 'Linen pants for men', 1, 2),
('Men Shorts', 'men-shorts', 'Linen shorts for men', 1, 3),
('Women Dresses', 'women-dresses', 'Linen dresses for women', 2, 1),
('Women Tops', 'women-tops', 'Linen tops for women', 2, 2),
('Women Skirts', 'women-skirts', 'Linen skirts for women', 2, 3),
('Kids Boys', 'kids-boys', 'Linen clothing for boys', 3, 1),
('Kids Girls', 'kids-girls', 'Linen clothing for girls', 3, 2);

-- Sample Products
INSERT INTO products (name, slug, description, short_description, price, sale_price, sku, stock_quantity, category_id, material, care_instructions, is_featured, is_new_arrival, is_best_seller, meta_title, meta_description) VALUES
('Classic White Linen Shirt', 'classic-white-linen-shirt', 'Our signature classic white linen shirt, crafted from premium European flax. Breathable, lightweight, and effortlessly elegant. Features a relaxed fit with mother-of-pearl buttons and a tailored collar.', 'Premium European flax linen shirt with relaxed fit', 89.00, 69.00, 'LU-MSH-001', 50, 4, '100% European Flax Linen', 'Machine wash cold, tumble dry low, iron on high if desired', TRUE, TRUE, TRUE, 'Classic White Linen Shirt | LINO UNION', 'Shop the Classic White Linen Shirt from LINO UNION. Premium European flax, relaxed fit, mother-of-pearl buttons.'),

('Natural Linen Relaxed Blazer', 'natural-linen-relaxed-blazer', 'A relaxed linen blazer that transitions effortlessly from office to evening. Unlined construction with patch pockets and a soft, unstructured shoulder.', 'Unstructured linen blazer for versatile styling', 195.00, NULL, 'LU-MSH-002', 30, 4, '100% Linen', 'Dry clean recommended', TRUE, TRUE, FALSE, 'Natural Linen Relaxed Blazer | LINO UNION', 'Discover the Natural Linen Relaxed Blazer. Unstructured, unlined, and effortlessly elegant.'),

('Oversized Linen Shirt in Sand', 'oversized-linen-shirt-sand', 'An oversized silhouette meets our signature Italian linen. This shirt features a curved hem, rolled cuffs, and a relaxed collar for an effortless look.', 'Oversized fit linen shirt with curved hem', 95.00, 79.00, 'LU-MSH-003', 40, 4, '100% Italian Linen', 'Machine wash cold, hang to dry', FALSE, FALSE, TRUE, 'Oversized Linen Shirt Sand | LINO UNION', 'Shop the Oversized Linen Shirt in Sand. Italian linen, curved hem, relaxed collar.'),

('Linen Wide-Leg Trousers', 'linen-wide-leg-trousers', 'Wide-leg trousers in heavyweight linen. Features side pockets, a high-rise waist, and a fluid drape that moves beautifully.', 'High-rise wide-leg linen trousers', 135.00, NULL, 'LU-MPN-001', 35, 5, '100% French Linen', 'Machine wash gentle, hang to dry', TRUE, FALSE, TRUE, 'Linen Wide-Leg Trousers | LINO UNION', 'Shop Linen Wide-Leg Trousers. High-rise, fluid drape, premium French linen.'),

('Relaxed Linen Pleated Pant', 'relaxed-linen-pleated-pant', 'A relaxed take on the classic pleated trouser. Crafted from mid-weight linen with a comfortable elasticated waist and a tapered leg.', 'Elasticated waist pleated linen pants', 115.00, 95.00, 'LU-MPN-002', 45, 5, '100% Linen', 'Machine wash cold, tumble dry low', FALSE, TRUE, FALSE, 'Relaxed Linen Pleated Pant | LINO UNION', 'Relaxed linen pleated pants with elastic waist. Comfort meets sophistication.'),

('Linen Drawstring Shorts', 'linen-drawstring-shorts', 'Effortless shorts in lightweight linen. Features an elasticated drawstring waist, side pockets, and a relaxed fit through the leg.', 'Lightweight linen drawstring shorts', 65.00, NULL, 'LU-MSH-001', 60, 6, '100% Linen', 'Machine wash cold, tumble dry low', FALSE, FALSE, FALSE, 'Linen Drawstring Shorts | LINO UNION', 'Comfortable linen drawstring shorts. Perfect for warm weather.'),

('The Column Linen Dress', 'the-column-linen-dress', 'A sculptural column dress in heavyweight linen. Features a hidden back zip, side seam pockets, and a floor-sweeping hem.', 'Column silhouette linen dress', 165.00, 135.00, 'LU-WDR-001', 25, 7, '100% Belgian Linen', 'Dry clean recommended', TRUE, TRUE, TRUE, 'The Column Linen Dress | LINO UNION', 'Shop The Column Linen Dress. Sculptural, floor-sweeping, premium Belgian linen.'),

('Linen Smocked Midi Dress', 'linen-smocked-midi-dress', 'A romantic midi dress with a smocked bodice and flowing skirt. Adjustable straps and a tiered ruffle hem add feminine detail.', 'Smocked bodice midi linen dress', 125.00, NULL, 'LU-WDR-002', 35, 7, '100% Linen', 'Hand wash cold, hang to dry', TRUE, TRUE, FALSE, 'Linen Smocked Midi Dress | LINO UNION', 'Discover the Linen Smocked Midi Dress. Romantic, tiered, adjustable straps.'),

('Linen Button-Front Midi Skirt', 'linen-button-front-midi-skirt', 'A column midi skirt with a full front button placket. High-waisted with a center vent for ease of movement.', 'High-waisted button-front linen skirt', 105.00, 89.00, 'LU-WSK-001', 30, 9, '100% Linen', 'Machine wash gentle, hang to dry', FALSE, FALSE, TRUE, 'Linen Button-Front Midi Skirt | LINO UNION', 'Shop the Linen Button-Front Midi Skirt. High-waisted, column silhouette.'),

('Cropped Linen Top', 'cropped-linen-top', 'A modern cropped top in crisp linen. Features a square neckline, puff sleeves, and a fitted bodice with a back zipper.', 'Square neck crop top in linen', 75.00, NULL, 'LU-WTP-001', 55, 8, '100% Linen', 'Machine wash cold, tumble dry low', FALSE, TRUE, FALSE, 'Cropped Linen Top | LINO UNION', 'Modern cropped linen top with square neck and puff sleeves.'),

('Linen A-Line Mini Dress', 'linen-a-line-mini-dress', 'A playful mini dress in lightweight linen. A-line silhouette with a round collar, short puff sleeves, and side pockets.', 'A-line mini linen dress with puff sleeves', 95.00, NULL, 'LU-KGR-001', 40, 11, '100% Linen', 'Machine wash cold, tumble dry low', TRUE, TRUE, TRUE, 'Linen A-Line Mini Dress Kids | LINO UNION', 'Adorable linen mini dress for girls. A-line, puff sleeves, side pockets.'),

('Linen Button-Up Shirt for Boys', 'linen-button-up-shirt-boys', 'A miniature version of our classic linen shirt. Features a button-front closure, rounded collar, and chest pocket.', 'Classic linen button-up shirt for boys', 55.00, 45.00, 'LU-KBY-001', 45, 10, '100% Linen', 'Machine wash cold, tumble dry low', TRUE, TRUE, FALSE, 'Linen Button-Up Shirt Boys | LINO UNION', 'Classic linen shirt for boys. Button-front, rounded collar, chest pocket.'),

('Relaxed Linen Jumpsuit', 'relaxed-linen-jumpsuit', 'An effortless one-piece in breathable linen. Features a self-belt at the waist, deep side pockets, and a V-neckline.', 'Effortless linen jumpsuit with belt', 145.00, NULL, 'LU-WTP-002', 20, 8, '100% European Flax Linen', 'Machine wash cold, hang to dry', FALSE, FALSE, FALSE, 'Relaxed Linen Jumpsuit | LINO UNION', 'Effortless linen jumpsuit with self-belt and deep pockets.');

-- Product Images for sample products
INSERT INTO product_images (product_id, image_url, alt_text, sort_order, is_primary) VALUES
(1, 'assets/images/products/classic-white-shirt-1.jpg', 'Classic White Linen Shirt front view', 0, TRUE),
(1, 'assets/images/products/classic-white-shirt-2.jpg', 'Classic White Linen Shirt detail view', 1, FALSE),
(2, 'assets/images/products/natural-blazer-1.jpg', 'Natural Linen Blazer front view', 0, TRUE),
(3, 'assets/images/products/oversized-sand-shirt-1.jpg', 'Oversized Sand Linen Shirt front view', 0, TRUE),
(4, 'assets/images/products/wide-leg-trousers-1.jpg', 'Linen Wide-Leg Trousers front view', 0, TRUE),
(5, 'assets/images/products/pleated-pant-1.jpg', 'Relaxed Linen Pleated Pant front view', 0, TRUE),
(6, 'assets/images/products/drawstring-shorts-1.jpg', 'Linen Drawstring Shorts front view', 0, TRUE),
(7, 'assets/images/products/column-dress-1.jpg', 'The Column Linen Dress front view', 0, TRUE),
(8, 'assets/images/products/smocked-dress-1.jpg', 'Linen Smocked Midi Dress front view', 0, TRUE),
(9, 'assets/images/products/button-skirt-1.jpg', 'Linen Button-Front Midi Skirt front view', 0, TRUE),
(10, 'assets/images/products/cropped-top-1.jpg', 'Cropped Linen Top front view', 0, TRUE),
(11, 'assets/images/products/mini-dress-kids-1.jpg', 'Kids Linen A-Line Mini Dress front view', 0, TRUE),
(12, 'assets/images/products/boys-shirt-1.jpg', 'Boys Linen Button-Up Shirt front view', 0, TRUE),
(13, 'assets/images/products/jumpsuit-1.jpg', 'Relaxed Linen Jumpsuit front view', 0, TRUE);

-- Product Variants for sample products
INSERT INTO product_variants (product_id, size, color, color_hex, stock_quantity, price_adjustment, sku_suffix) VALUES
-- Classic White Linen Shirt
(1, 'S', 'White', '#FFFFFF', 10, 0, 'S-WHT'),
(1, 'M', 'White', '#FFFFFF', 15, 0, 'M-WHT'),
(1, 'L', 'White', '#FFFFFF', 15, 0, 'L-WHT'),
(1, 'XL', 'White', '#FFFFFF', 10, 5, 'XL-WHT'),
(1, 'S', 'Natural', '#D4C5A9', 8, 0, 'S-NAT'),
(1, 'M', 'Natural', '#D4C5A9', 12, 0, 'M-NAT'),
(1, 'L', 'Natural', '#D4C5A9', 12, 0, 'L-NAT'),
(1, 'XL', 'Natural', '#D4C5A9', 8, 5, 'XL-NAT'),

-- Natural Linen Relaxed Blazer
(2, 'S', 'Natural', '#C4B59A', 5, 0, 'S-NAT'),
(2, 'M', 'Natural', '#C4B59A', 10, 0, 'M-NAT'),
(2, 'L', 'Natural', '#C4B59A', 10, 0, 'L-NAT'),
(2, 'XL', 'Natural', '#C4B59A', 5, 10, 'XL-NAT');

-- Insert a sample newsletter subscriber
INSERT INTO newsletter_subscribers (email) VALUES ('hello@linounion.com');
