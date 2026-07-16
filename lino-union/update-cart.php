<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

$itemId = $_POST['item_id'] ?? null;
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

if ($itemId !== null && isset($_SESSION['cart'][$itemId])) {
    $_SESSION['cart'][$itemId]['quantity'] = $quantity;

    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $shipping = $subtotal >= SITE_SHIPPING_FREE_MIN ? 0 : SITE_SHIPPING_RATE;
    $tax = $subtotal * SITE_TAX_RATE / 100;
    $total = $subtotal + $shipping + $tax;
    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

    echo json_encode([
        'success' => true,
        'subtotal' => $subtotal,
        'subtotalFormatted' => formatPrice($subtotal),
        'totalFormatted' => formatPrice($total),
        'cartCount' => $cartCount
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found.']);
}
