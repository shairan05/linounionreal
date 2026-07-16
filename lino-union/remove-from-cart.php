<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

$itemId = $_POST['item_id'] ?? null;

if ($itemId !== null && isset($_SESSION['cart'][$itemId])) {
    array_splice($_SESSION['cart'], (int)$itemId, 1);
    // Re-index
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

    echo json_encode([
        'success' => true,
        'cartCount' => $cartCount,
        'cartEmpty' => empty($_SESSION['cart'])
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found.']);
}
