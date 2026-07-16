<?php
require_once __DIR__ . '/includes/config.php';
$meta_title = "Kids' Linen Clothing Collection | LINO UNION";
$meta_description = 'Discover premium linen clothing for kids. Comfortable, breathable, and adventure-ready linen pieces for boys and girls.';
require_once __DIR__ . '/includes/header.php';
$products = getProducts('kids');
?>

<!-- ======== Page Header ======== -->
<section class="page-header bg-linen">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                <li class="breadcrumb-item active">Kids</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="page-title">Kids' Collection</h1>
                <p class="text-muted mb-0">Adorable linen essentials for little ones. Comfortable, durable, and gentle on sensitive skin.</p>
            </div>
            <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                <a href="#products" class="btn btn-dark">Shop Now</a>
            </div>
        </div>
    </div>
</section>

<!-- ======== Subcategories ======== -->
<section class="py-4 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="kids.php" class="btn btn-sm btn-dark">All Kids</a>
            <?php
            $subs = getSubcategories('kids');
            foreach ($subs as $sub): ?>
            <a href="shop.php?category=<?php echo $sub['slug']; ?>" class="btn btn-sm btn-outline-dark"><?php echo htmlspecialchars($sub['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======== Products ======== -->
<section class="section-padding" id="products">
    <div class="container">
        <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-box"></i></div>
            <h3 class="empty-state-title">Coming Soon</h3>
            <p class="empty-state-text">Our kids' collection is being curated. Sign up for updates.</p>
            <a href="index.php#newsletterForm" class="btn btn-dark">Get Notified</a>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product):
                $img = getPrimaryImage($product['id']);
                $hasSale = $product['sale_price'] && $product['sale_price'] < $product['price'];
                $productImages = getProductImages($product['id'], 4);
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card" data-aos="fade-up">
                    <div class="product-card-image">
                        <a href="product.php?slug=<?php echo $product['slug']; ?>">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
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
                            <button class="product-action-btn wishlist-toggle" data-product-id="<?php echo $product['id']; ?>"><i class="bi bi-heart"></i></button>
                            <button class="product-action-btn add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>"><i class="bi bi-bag-plus"></i></button>
                        </div>
                    </div>
                    <div class="product-card-info">
                        <div class="product-card-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Kids'); ?></div>
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
