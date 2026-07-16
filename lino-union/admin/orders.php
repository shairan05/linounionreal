<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

$db = getDB();
$message = '';

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    try {
        $stmt = $db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $orderId]);
        $message = 'Order status updated.';
    } catch (Exception $e) {
        $error = 'Error updating order.';
    }
}

// Get orders
$orders = $db->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-header">
    <h1>Orders</h1>
</div>

<?php if ($message): ?>
<div class="alert alert-success border-0 rounded-0 py-2"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (empty($orders)): ?>
<div class="text-center text-muted py-5">
    <i class="bi bi-truck" style="font-size:3rem;"></i>
    <p class="mt-3">No orders yet.</p>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-custom">
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order):
                $stmt = $db->prepare("SELECT SUM(quantity) as total_items FROM order_items WHERE order_id = :id");
                $stmt->execute([':id' => $order['id']]);
                $itemCount = $stmt->fetch()['total_items'] ?? 0;
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                <td><?php echo htmlspecialchars($order['email']); ?></td>
                <td><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></td>
                <td><?php echo $itemCount; ?></td>
                <td><?php echo formatPrice($order['total']); ?></td>
                <td>
                    <span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['id']; ?>">
                        View
                    </button>
                </td>
            </tr>

            <!-- Order Detail Modal -->
            <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Order <?php echo htmlspecialchars($order['order_number']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="mb-2">Customer Details</h6>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['email']); ?></p>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['phone']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-2">Shipping Address</h6>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['address_line1']); ?></p>
                                    <?php if ($order['address_line2']): ?><p class="mb-1"><?php echo htmlspecialchars($order['address_line2']); ?></p><?php endif; ?>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['zip_code']); ?></p>
                                </div>
                            </div>

                            <h6 class="mt-4 mb-2">Order Items</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Variant</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = :id");
                                    $stmt->execute([':id' => $order['id']]);
                                    $items = $stmt->fetchAll();
                                    foreach ($items as $item):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['variant_info'] ?: '—'); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo formatPrice($item['price']); ?></td>
                                        <td><?php echo formatPrice($item['total']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <form method="POST" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" style="width:auto;">
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-dark">Update</button>
                                    </form>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-0"><strong>Subtotal:</strong> <?php echo formatPrice($order['subtotal']); ?></p>
                                    <p class="mb-0"><strong>Shipping:</strong> <?php echo formatPrice($order['shipping']); ?></p>
                                    <p class="mb-0"><strong>Tax:</strong> <?php echo formatPrice($order['tax']); ?></p>
                                    <p class="mb-0" style="font-size:1.1rem;"><strong>Total:</strong> <?php echo formatPrice($order['total']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
