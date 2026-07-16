<?php
require_once __DIR__ . '/includes/config.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : null;

if (!$slug) {
    header('Location: shop.php');
    exit;
}

$product = getProductBySlug($slug);

if (!$product) {
    header('HTTP/1.0 404 Not Found');
    $meta_title = 'Product Not Found | LINO UNION';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container py-5"><div class="empty-state"><div class="empty-state-icon"><i class="bi bi-emoji-frown"></i></div><h3 class="empty-state-title">Product Not Found</h3><p class="empty-state-text">The product you\'re looking for doesn\'t exist or has been removed.</p><a href="shop.php" class="btn btn-dark">Continue Shopping</a></div></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$images = getAllImages($product['id']);
$variants = getVariants($product['id']);

// Get unique sizes and colors from variants
$sizes = [];
$colors = [];
foreach ($variants as $v) {
    if (!empty($v['size']) && !in_array($v['size'], $sizes)) $sizes[] = $v['size'];
    if (!empty($v['color']) && !isset($colors[$v['color']])) {
        $colors[$v['color']] = $v['color_hex'] ?? '#CCCCCC';
    }
}

$meta_title = $product['meta_title'] ?: htmlspecialchars($product['name']) . ' | LINO UNION';
$meta_description = $product['meta_description'] ?: 'Shop the ' . htmlspecialchars($product['name']) . ' from LINO UNION. ' . htmlspecialchars($product['short_description']);

require_once __DIR__ . '/includes/header.php';
?>

<!-- ======== Breadcrumb ======== -->
<section class="py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                <?php if ($product['category_name']): ?>
                <li class="breadcrumb-item"><a href="<?php echo strtolower($product['category_name']); ?>.php"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- ======== Product Details ======== -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Product Gallery -->
            <div class="col-lg-7">
                <div class="product-gallery">
                    <div class="product-main-image">
                        <img src="<?php echo $images[0]['image_url'] ?? 'assets/images/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="mainProductImage">
                    </div>
                    <?php if (count($images) > 1): ?>
                    <div class="product-thumbnails">
                        <?php foreach ($images as $index => $img): ?>
                        <div class="product-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo $img['image_url']; ?>" alt="<?php echo htmlspecialchars($img['alt_text'] ?: $product['name']); ?>" loading="lazy">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info">
                    <div class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Linen'); ?></div>
                    <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <div class="product-rating">
                        <span class="stars">★★★★★</span>
                        <span class="reviews-count">(128 reviews)</span>
                    </div>

                    <div class="product-price">
                        <?php
                        $hasSale = $product['sale_price'] && $product['sale_price'] < $product['price'];
                        ?>
                        <span class="current"><?php echo formatPrice($hasSale ? $product['sale_price'] : $product['price']); ?></span>
                        <?php if ($hasSale): ?>
                            <span class="original"><?php echo formatPrice($product['price']); ?></span>
                            <span class="badge bg-dark ms-2">Save <?php echo formatPrice($product['price'] - $product['sale_price']); ?></span>
                        <?php endif; ?>
                    </div>

                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['short_description'] ?: $product['description'])); ?></p>

                    <!-- Size Variant -->
                    <?php if (!empty($sizes)): ?>
                    <div class="variant-section">
                        <div class="variant-label">
                            <span>Size</span>
                            <span class="variant-selected text-grey"></span>
                        </div>
                        <div class="variant-options">
                            <?php foreach ($sizes as $size): ?>
                            <button class="size-option <?php echo $size === 'M' ? 'active' : ''; ?>" type="button"><?php echo htmlspecialchars($size); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Color Variant -->
                    <?php if (!empty($colors)): ?>
                    <div class="variant-section">
                        <div class="variant-label">
                            <span>Color</span>
                            <span class="variant-selected text-grey"></span>
                        </div>
                        <div class="variant-options">
                            <?php foreach ($colors as $colorName => $colorHex): ?>
                            <button class="color-option <?php echo $colorName === 'White' ? 'active' : ''; ?>" type="button" data-color="<?php echo htmlspecialchars($colorName); ?>" style="background: <?php echo $colorHex; ?>;" title="<?php echo htmlspecialchars($colorName); ?>"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quantity & Actions -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="variant-label mb-0">Quantity</div>
                        <div class="quantity-selector">
                            <button class="quantity-btn quantity-minus" type="button">−</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="99" readonly>
                            <button class="quantity-btn quantity-plus" type="button">+</button>
                        </div>
                    </div>

                    <div class="product-actions">
                        <button class="btn btn-dark btn-lg add-to-cart-btn flex-grow-1" data-product-id="<?php echo $product['id']; ?>">
                            <i class="bi bi-bag-plus me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-gold btn-lg buy-now-btn flex-grow-1" data-product-id="<?php echo $product['id']; ?>">
                            Buy Now
                        </button>
                        <button class="product-action-btn wishlist-toggle" data-product-id="<?php echo $product['id']; ?>" style="width:56px;height:56px;border:1px solid var(--color-grey);border-radius:0;">
                            <i class="bi bi-heart" style="font-size:1.25rem;"></i>
                        </button>
                    </div>

                    <!-- Product Meta -->
                    <div class="product-meta">
                        <?php if ($product['material']): ?>
                        <div class="product-meta-item">
                            <i class="bi bi-sliders2"></i>
                            <span><strong>Material:</strong> <?php echo htmlspecialchars($product['material']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="product-meta-item">
                            <i class="bi bi-truck"></i>
                            <span><strong>Free shipping</strong> on orders over <?php echo formatPrice(SITE_SHIPPING_FREE_MIN); ?></span>
                        </div>
                        <div class="product-meta-item">
                            <i class="bi bi-arrow-return-left"></i>
                            <span><strong>Complimentary returns</strong> within 30 days</span>
                        </div>
                        <?php if ($product['care_instructions']): ?>
                        <div class="product-meta-item">
                            <i class="bi bi-droplet"></i>
                            <span><strong>Care:</strong> <?php echo htmlspecialchars($product['care_instructions']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="product-meta-item">
                            <i class="bi bi-shield-check"></i>
                            <span><strong>Secure checkout</strong> with SSL encryption</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description Tab -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">Details & Care</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Reviews (128)</button>
                    </li>
                </ul>
                <div class="tab-content py-4" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description">
                        <p class="text-muted" style="max-width:800px;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    <div class="tab-pane fade" id="details">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Product Details</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></li>
                                    <?php if ($product['material']): ?><li class="mb-2"><strong>Material:</strong> <?php echo htmlspecialchars($product['material']); ?></li><?php endif; ?>
                                    <li class="mb-2"><strong>Fit:</strong> Relaxed fit, true to size</li>
                                    <li class="mb-2"><strong>Model is 5'10"</strong> wearing size M</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Care Instructions</h5>
                                <p class="text-muted"><?php echo htmlspecialchars($product['care_instructions'] ?: 'Machine wash cold with like colors. Tumble dry low or hang to dry. Iron on high if desired. Do not bleach.'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews">
                        <p class="text-muted">Customer reviews coming soon.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
