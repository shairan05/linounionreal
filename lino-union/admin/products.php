<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

$db = getDB();
$message = '';
$error = '';

// Flash messages
if (isset($_SESSION['flash_message'])) {
    if ($_SESSION['flash_type'] === 'success') {
        $message = $_SESSION['flash_message'];
    } else {
        $error = $_SESSION['flash_message'];
    }
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}

// Handle delete
if (isset($_GET['delete'])) {
    try {
        $pid = (int)$_GET['delete'];
        $imgs = $db->prepare("SELECT image_url FROM product_images WHERE product_id = :pid");
        $imgs->execute([':pid' => $pid]);
        foreach ($imgs->fetchAll() as $img) {
            $filePath = __DIR__ . '/../' . $img['image_url'];
            if (file_exists($filePath)) unlink($filePath);
        }
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $pid]);
        $_SESSION['flash_message'] = '🗑️ Product deleted.';
        $_SESSION['flash_type'] = 'success';
        header('Location: products.php');
        exit;
    } catch (Exception $e) {
        $error = 'Error deleting product.';
    }
}

// Fetch products
$products = $db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
$categories = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

require_once __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-header">
    <div>
        <h1>Products</h1>
        <p class="text-muted mb-0"><?php echo count($products); ?> products total</p>
    </div>
    <a href="edit-product.php" class="btn btn-dark">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

<?php if ($message): ?>
<div class="alert alert-success border-0 rounded-0 py-2"><?php echo $message; ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger border-0 rounded-0 py-2"><?php echo $error; ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-custom">
        <thead>
            <tr>
                <th style="width:50px;">Image</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th style="width:120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product):
                $img = getPrimaryImage($product['id']);
            ?>
            <tr>
                <td>
                    <div style="width:50px;height:60px;overflow:hidden;background:var(--color-grey-light);">
                        <img src="<?php echo $img; ?>" alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                    </div>
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                    <div class="small text-muted">
                        <?php if ($product['is_featured']): ?><span class="badge bg-dark me-1">Featured</span><?php endif; ?>
                        <?php if ($product['is_new_arrival']): ?><span class="badge" style="background:var(--color-gold);color:white;">New</span><?php endif; ?>
                        <?php if ($product['is_best_seller']): ?><span class="badge bg-dark me-1">Best</span><?php endif; ?>
                    </div>
                </td>
                <td class="small"><?php echo htmlspecialchars($product['sku']); ?></td>
                <td><?php echo htmlspecialchars($product['category_name'] ?? '—'); ?></td>
                <td>
                    <?php echo formatPrice($product['price']); ?>
                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <br><small class="text-danger">Sale: <?php echo formatPrice($product['sale_price']); ?></small>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="<?php echo $product['stock_quantity'] > 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $product['stock_quantity']; ?>
                    </span>
                </td>
                <td>
                    <span class="status-badge <?php echo $product['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="edit-product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-sm btn-outline-dark" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?delete=<?php echo (int)$product['id']; ?>"
                           class="btn btn-sm btn-outline-danger" title="Delete"
                           onclick="return confirm('Delete &quot;<?php echo htmlspecialchars($product['name']); ?>&quot; permanently?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
