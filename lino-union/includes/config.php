<?php
// ============================================
// LINO UNION - Configuration File
// ============================================

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'lino_union');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'LINO UNION');
define('SITE_TAGLINE', 'Buy Better, Buy Less.');
define('SITE_EMAIL', 'hello@linounion.com');
define('SITE_PHONE', '+91 1800-555-0199');
define('SITE_ADDRESS', '245 Fashion Street, Colaba, Mumbai 400005');
define('SITE_URL', 'http://localhost/lino-union');
define('SITE_PATH', '/lino-union/');
define('SITE_CURRENCY', '₹ ');
define('SITE_SHIPPING_FREE_MIN', 999);
define('SITE_SHIPPING_RATE', 49);
define('SITE_TAX_RATE', 12);

// Social Media
define('SOCIAL_INSTAGRAM', 'https://instagram.com/linounion');
define('SOCIAL_PINTEREST', 'https://pinterest.com/linounion');
define('SOCIAL_TWITTER', 'https://twitter.com/linounion');
define('SOCIAL_FACEBOOK', 'https://facebook.com/linounion');

// Paths
define('BASE_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', __DIR__ . '/');
define('ASSETS_PATH', 'assets/');
define('ADMIN_PATH', 'admin/');
define('UPLOADS_PATH', 'assets/uploads/');
define('PRODUCT_IMAGES_PATH', 'assets/uploads/products/');

// PDO Database Connection
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

// Helper Functions

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function getCartCount() {
    if (isset($_SESSION['cart'])) {
        return array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
    return 0;
}

function getWishlistCount() {
    if (isset($_SESSION['wishlist'])) {
        return count($_SESSION['wishlist']);
    }
    return 0;
}

function formatPrice($price) {
    return SITE_CURRENCY . number_format($price, 2);
}

function getCartSubtotal() {
    $subtotal = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    return $subtotal;
}

function generateOrderNumber() {
    return 'LU-' . strtoupper(uniqid()) . '-' . date('Ymd');
}

// Site Settings (from database)
function getSetting($key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
            $cache = [];
            while ($row = $stmt->fetch()) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            $cache = [];
        }
    }
    return $cache[$key] ?? $default;
}

function getAllSettingsBySection($section) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM site_settings WHERE section = :section ORDER BY sort_order ASC");
        $stmt->execute([':section' => $section]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getAllSections() {
    try {
        $db = getDB();
        return $db->query("SELECT DISTINCT section FROM site_settings ORDER BY FIELD(section, 'general','social','navbar','hero','collections','arrivals','promo','newsletter','footer','testimonials','instagram')")->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {
        return [];
    }
}

function updateSetting($key, $value) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value2");
        $stmt->execute([':key' => $key, ':value' => $value, ':value2' => $value]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function getProducts($category = null, $limit = null, $featured = false, $newArrivals = false, $bestSellers = false) {
    $db = getDB();
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1";
    $params = [];

    if ($category) {
        $sql .= " AND (c.slug = :category OR c.id IN (SELECT id FROM categories WHERE parent_id = (SELECT id FROM categories WHERE slug = :category2)))";
        $params[':category'] = $category;
        $params[':category2'] = $category;
    }

    if ($featured) {
        $sql .= " AND p.is_featured = 1";
    }

    if ($newArrivals) {
        $sql .= " AND p.is_new_arrival = 1";
    }

    if ($bestSellers) {
        $sql .= " AND p.is_best_seller = 1";
    }

    $sql .= " ORDER BY p.created_at DESC";

    if ($limit) {
        $sql .= " LIMIT :limit";
        $params[':limit'] = (int)$limit;
    }

    $stmt = $db->prepare($sql);

    foreach ($params as $key => &$val) {
        if ($key === ':limit') {
            $stmt->bindValue($key, (int)$val, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $val);
        }
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

function getPrimaryImage($productId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT image_url FROM product_images WHERE product_id = :product_id AND is_primary = 1 LIMIT 1");
    $stmt->execute([':product_id' => $productId]);
    $result = $stmt->fetch();
    if ($result && $result['image_url']) {
        // Check file existence using absolute path (works from admin subdirectory too)
        $fullPath = BASE_PATH . $result['image_url'];
        if (file_exists($fullPath)) {
            // Return root-relative path so it works in <img> tags from any directory
            return SITE_PATH . ltrim($result['image_url'], '/');
        }
    }
    // Use Unsplash as fallback placeholder
    $placeholders = [
        'https://images.unsplash.com/photo-1596900779747-33a5d6c3f3e0?w=600&h=800&fit=crop',
        'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&h=800&fit=crop',
        'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&h=800&fit=crop',
    ];
    return $placeholders[$productId % count($placeholders)];
}

function getProductImages($productId, $limit = 3) {
    $db = getDB();
    $stmt = $db->prepare("SELECT image_url FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, sort_order ASC LIMIT :lim");
    $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
    $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getAllImages($productId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM product_images WHERE product_id = :product_id ORDER BY sort_order ASC");
    $stmt->execute([':product_id' => $productId]);
    return $stmt->fetchAll();
}

function getVariants($productId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM product_variants WHERE product_id = :product_id ORDER BY FIELD(size, 'XS','S','M','L','XL','XXL'), color");
    $stmt->execute([':product_id' => $productId]);
    return $stmt->fetchAll();
}

function getProductBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id
                          WHERE p.slug = :slug LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    return $stmt->fetch();
}

function getCategories($parentId = null) {
    $db = getDB();
    if ($parentId === null) {
        $stmt = $db->query("SELECT * FROM categories WHERE parent_id IS NULL AND is_active = 1 ORDER BY sort_order");
    } else {
        $stmt = $db->prepare("SELECT * FROM categories WHERE parent_id = :parent_id AND is_active = 1 ORDER BY sort_order");
        $stmt->execute([':parent_id' => $parentId]);
    }
    return $stmt->fetchAll();
}

function getSubcategories($parentSlug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT c2.* FROM categories c2
                         JOIN categories c1 ON c2.parent_id = c1.id
                         WHERE c1.slug = :slug AND c2.is_active = 1
                         ORDER BY c2.sort_order");
    $stmt->execute([':slug' => $parentSlug]);
    return $stmt->fetchAll();
}
