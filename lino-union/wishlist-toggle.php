<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

$productId = (int)($_POST['product_id'] ?? 0);

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

// Require login for wishlist
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please sign in to use wishlist.', 'loginRequired' => true]);
    exit;
}

try {
    $db = getDB();

    // Check if already in wishlist
    $stmt = $db->prepare("SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([':user_id' => $_SESSION['user_id'], ':product_id' => $productId]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Remove
        $stmt = $db->prepare("DELETE FROM wishlist WHERE id = :id");
        $stmt->execute([':id' => $existing['id']]);
        $added = false;
    } else {
        // Add
        $stmt = $db->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)");
        $stmt->execute([':user_id' => $_SESSION['user_id'], ':product_id' => $productId]);
        $added = true;
    }

    // Get count
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $count = $stmt->fetch()['total'];

    echo json_encode([
        'success' => true,
        'added' => $added,
        'wishlistCount' => $count
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating wishlist.']);
}
