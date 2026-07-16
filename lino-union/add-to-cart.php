<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

$productId = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));
$size = sanitize($_POST['size'] ?? '');
$color = sanitize($_POST['color'] ?? '');

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

// Get product info
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM products WHERE id = :id AND is_active = 1");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit;
    }

    $img = getPrimaryImage($product['id']);
    $price = ($product['sale_price'] && $product['sale_price'] < $product['price']) ? $product['sale_price'] : $product['price'];

    // Initialize cart if needed
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists with same variant
    $existingKey = null;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId && $item['size'] == $size && $item['color'] == $color) {
            $existingKey = $key;
            break;
        }
    }

    if ($existingKey !== null) {
        $_SESSION['cart'][$existingKey]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'name' => $product['name'],
            'price' => (float)$price,
            'size' => $size,
            'color' => $color,
            'quantity' => $quantity,
            'image' => $img,
            'slug' => $product['slug']
        ];
    }

    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

    echo json_encode([
        'success' => true,
        'cartCount' => $cartCount,
        'message' => 'Added to cart!'
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding to cart.']);
}
