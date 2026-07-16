<?php
require_once __DIR__ . '/includes/config.php';

$category = isset($_GET['category']) ? sanitize($_GET['category']) : null;
$filter = isset($_GET['filter']) ? sanitize($_GET['filter']) : null;
$search = isset($_GET['q']) ? sanitize($_GET['q']) : null;

$meta_title = 'Shop All Linen Clothing | LINO UNION';
$meta_description = 'Browse our complete collection of premium linen clothing for men, women, and kids. Free shipping on orders over $150.';

// Get products
$products = [];
try {
    $db = getDB();
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1";
    $params = [];

    if ($category) {
        $sql .= " AND (c.slug = :category OR c.parent_id IN (SELECT id FROM categories WHERE slug = :category2))";
        $params[':category'] = $category;
        $params[':category2'] = $category;
    }

    if ($search) {
        $sql .= " AND (p.name LIKE :search OR p.description LIKE :search2)";
        $params[':search'] = '%' . $search . '%';
        $params[':search2'] = '%' . $search . '%';
    }

    if ($filter === 'new') {
        $sql .= " AND p.is_new_arrival = 1";
    } elseif ($filter === 'best') {
        $sql .= " AND p.is_best_seller = 1";
    } elseif ($filter === 'sale') {
        $sql .= " AND p.sale_price IS NOT NULL AND p.sale_price < p.price";
    }

    $sql .= " ORDER BY p.created_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ======== Page Header ======== -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php
                            if ($category) echo ucfirst($category);
                            elseif ($filter === 'new') echo 'New Arrivals';
                            elseif ($filter === 'best') echo 'Best Sellers';
                            elseif ($filter === 'sale') echo 'Sale';
                            else echo 'All Collections';
                            ?>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <?php
                    if ($category) echo ucfirst($category) . "'s Collection";
                    elseif ($filter === 'new') echo 'New Arrivals';
                    elseif ($filter === 'best') echo 'Best Sellers';
                    elseif ($filter === 'sale') echo 'Sale';
                    else echo 'All Collections';
                    ?>
                </h1>
                <p class="text-muted"><?php echo count($products); ?> products</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end align-items-center gap-3">
                    <span class="text-muted small">Sort by:</span>
                    <select class="form-select form-select-sm" style="width:auto;border-radius:0;" id="sortSelect">
                        <option value="newest">Newest</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="name">Name</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======== Shop Content ======== -->
<section class="section-padding">
    <div class="container">
        <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-search"></i></div>
            <h3 class="empty-state-title">No products found</h3>
            <p class="empty-state-text">Try adjusting your search or filter criteria.</p>
            <a href="shop.php" class="btn btn-dark">View All Products</a>
        </div>
        <?php else: ?>
        <div class="row" id="productGrid">
            <?php foreach ($products as $product):
                $img = getPrimaryImage($product['id']);
                $hasSale = $product['sale_price'] && $product['sale_price'] < $product['price'];
                $productImages = getProductImages($product['id'], 4);
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 product-item" data-price="<?php echo $hasSale ? $product['sale_price'] : $product['price']; ?>" data-name="<?php echo strtolower($product['name']); ?>">
                <div class="product-card" data-aos="fade-up">
                    <div class="product-card-image">
                        <a href="product.php?slug=<?php echo $product['slug']; ?>">
                            <img class="primary-image" src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                        </a>
                        <?php if (count($productImages) > 1): ?>
                        <button class="product-image-arrow" data-images='<?php echo htmlspecialchars(json_encode($productImages), ENT_QUOTES, 'UTF-8'); ?>' data-current="0" aria-label="Next image">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <?php endif; ?>
                        <div class="product-card-badges">
                            <?php if ($product['is_new_arrival']): ?><span class="product-badge new">New</span><?php endif; ?>
                            <?php if ($hasSale): ?><span class="product-badge sale">Sale</span><?php endif; ?>
                        </div>
                        <div class="product-card-actions">
                            <button class="product-action-btn wishlist-toggle" data-product-id="<?php echo $product['id']; ?>">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="product-action-btn add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-card-info">
                        <div class="product-card-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Linen'); ?></div>
                        <h3 class="product-card-name"><a href="product.php?slug=<?php echo $product['slug']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                        <div class="product-card-price">
                            <?php if ($hasSale): ?>
                                <span class="original"><?php echo formatPrice($product['price']); ?></span>
                                <span class="sale"><?php echo formatPrice($product['sale_price']); ?></span>
                            <?php else: ?>
                                <?php echo formatPrice($product['price']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
