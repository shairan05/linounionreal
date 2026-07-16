<?php
require_once __DIR__ . '/includes/config.php';

$orderNumber = isset($_GET['order']) ? sanitize($_GET['order']) : null;

if (!$orderNumber) {
    header('Location: index.php');
    exit;
}

// Get order
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = :order_number");
    $stmt->execute([':order_number' => $orderNumber]);
    $order = $stmt->fetch();

    if (!$order) {
        header('Location: index.php');
        exit;
    }

    // Get order items
    $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order['id']]);
    $orderItems = $stmt->fetchAll();

} catch (Exception $e) {
    header('Location: index.php');
    exit;
}

$meta_title = 'Order Confirmation | LINO UNION';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ======== Order Confirmation ======== -->
<section class="auth-page">
    <div class="container">
        <div class="auth-container text-center">
            <div class="mb-4">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--color-black);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-check-lg" style="font-size:2.5rem;color:var(--color-white);"></i>
                </div>
            </div>
            <h1 class="auth-title">Thank You for Your Order!</h1>
            <p class="auth-subtitle">Your order has been placed successfully.</p>

            <div class="mt-4 p-4 bg-light">
                <p class="mb-1"><strong>Order Number:</strong></p>
                <p class="mb-0" style="font-family:var(--font-secondary);font-size:1.5rem;"><?php echo htmlspecialchars($order['order_number']); ?></p>
            </div>

            <p class="text-muted mt-3">A confirmation email has been sent to <strong><?php echo htmlspecialchars($order['email']); ?></strong></p>

            <div class="mt-4 text-start">
                <h5 class="mb-3">Order Summary</h5>
                <?php foreach ($orderItems as $item): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['quantity']; ?></span>
                    <span><?php echo formatPrice($item['total']); ?></span>
                </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-between"><span>Subtotal</span><span><?php echo formatPrice($order['subtotal']); ?></span></div>
                <div class="d-flex justify-content-between"><span>Shipping</span><span><?php echo $order['shipping'] > 0 ? formatPrice($order['shipping']) : 'Free'; ?></span></div>
                <div class="d-flex justify-content-between"><span>Tax</span><span><?php echo formatPrice($order['tax']); ?></span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span><?php echo formatPrice($order['total']); ?></span></div>
            </div>

            <div class="mt-4">
                <p class="text-muted"><strong>Shipping to:</strong><br>
                <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                <?php echo htmlspecialchars($order['address_line1']); ?><br>
                <?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['zip_code']); ?></p>
            </div>

            <div class="mt-4 d-flex gap-3 justify-content-center">
                <a href="shop.php" class="btn btn-dark">Continue Shopping</a>
                <a href="index.php" class="btn btn-outline-dark">Back to Home</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
