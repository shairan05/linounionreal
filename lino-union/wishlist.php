<?php
require_once __DIR__ . '/includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=wishlist.php');
    exit;
}

$meta_title = 'My Wishlist | LINO UNION';
$meta_description = 'View your saved LINO UNION wishlist items.';

require_once __DIR__ . '/includes/header.php';

$wishlistItems = [];
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, w.id as wishlist_id, w.created_at as added_date
                          FROM wishlist w
                          JOIN products p ON w.product_id = p.id
                          WHERE w.user_id = :user_id
                          ORDER BY w.created_at DESC");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $wishlistItems = $stmt->fetchAll();
} catch (Exception $e) {}
?>

<!-- ======== Page Header ======== -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">My Wishlist</li>
            </ol>
        </nav>
        <h1 class="page-title">My Wishlist</h1>
        <p class="text-muted"><?php echo count($wishlistItems); ?> saved items</p>
    </div>
</section>

<!-- ======== Wishlist Content ======== -->
<section class="wishlist-page">
    <div class="container">
        <?php if (empty($wishlistItems)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-heart"></i></div>
            <h3 class="empty-state-title">Your wishlist is empty</h3>
            <p class="empty-state-text">Save your favorite pieces and come back to them later.</p>
            <a href="shop.php" class="btn btn-dark">Explore Products</a>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($wishlistItems as $item):
                $img = getPrimaryImage($item['id']);
                $hasSale = $item['sale_price'] && $item['sale_price'] < $item['price'];
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-card-image">
                        <a href="product.php?slug=<?php echo $item['slug']; ?>">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                        </a>
                        <div class="product-card-actions">
                            <button class="product-action-btn wishlist-toggle active" data-product-id="<?php echo $item['id']; ?>">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                            <button class="product-action-btn add-to-cart-btn" data-product-id="<?php echo $item['id']; ?>">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-card-info">
                        <div class="product-card-category"><?php echo htmlspecialchars($item['category_name'] ?? 'Linen'); ?></div>
                        <h3 class="product-card-name"><a href="product.php?slug=<?php echo $item['slug']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                        <div class="product-card-price">
                            <?php if ($hasSale): ?>
                                <span class="original"><?php echo formatPrice($item['price']); ?></span>
                                <span class="sale"><?php echo formatPrice($item['sale_price']); ?></span>
                            <?php else: ?>
                                <?php echo formatPrice($item['price']); ?>
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
