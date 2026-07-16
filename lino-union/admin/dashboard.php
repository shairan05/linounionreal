<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

// Get stats
try {
    $db = getDB();
    $totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalRevenue = $db->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status NOT IN ('cancelled')")->fetchColumn();
    $totalCustomers = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 0")->fetchColumn();
    $pendingOrders = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
    $recentOrders = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
    $totalProducts = $totalOrders = $totalRevenue = $totalCustomers = $pendingOrders = 0;
    $recentOrders = [];
}

require_once __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-header">
    <div>
        <h1>Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    </div>
    <div class="text-muted small"><?php echo date('l, F j, Y'); ?></div>
</div>

<div class="admin-stats">
    <div class="stat-card">
        <div class="stat-card-value">$<?php echo number_format($totalRevenue, 0); ?></div>
        <div class="stat-card-label">Total Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value"><?php echo $totalOrders; ?></div>
        <div class="stat-card-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value"><?php echo $pendingOrders; ?></div>
        <div class="stat-card-label">Pending Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value"><?php echo $totalProducts; ?></div>
        <div class="stat-card-label">Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value"><?php echo $totalCustomers; ?></div>
        <div class="stat-card-label">Customers</div>
    </div>
</div>

<div class="mt-4">
    <h5 class="mb-3">Recent Orders</h5>
    <?php if (empty($recentOrders)): ?>
    <div class="text-center text-muted py-4">No orders yet.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                    <td><?php echo formatPrice($order['total']); ?></td>
                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                    <td><a href="orders.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-dark">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
