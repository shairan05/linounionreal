<?php
require_once __DIR__ . '/includes/config.php';
$meta_title = getSetting('site_name', 'LINO UNION') . ' – Premium Linen Clothing';
$meta_description = 'Discover premium linen clothing at ' . getSetting('site_name', 'LINO UNION') . '. Timeless essentials crafted from the finest European flax. Buy Better, Buy Less.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ======== Page Loader ======== -->
<div class="page-loader">
    <div class="loader-logo"><?php echo getSetting('brand_logo', 'LINO'); ?> <span><?php echo getSetting('brand_logo_suffix', 'UNION'); ?></span></div>
</div>

<!-- ======== Hero Slider ======== -->
<section class="hero-section">
    <div class="hero-slide active">
        <img src="<?php echo getSetting('hero_1_image', 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=1920&h=1080&fit=crop'); ?>" alt="Linen clothing collection" loading="eager">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-content-inner" data-aos="fade-up" data-aos-duration="1000">
                    <span class="hero-subtitle"><?php echo getSetting('hero_1_subtitle', 'New Collection'); ?></span>
                    <h1 class="hero-title"><?php echo getSetting('hero_1_title', 'Linen,<br>Reimagined.'); ?></h1>
                    <p class="hero-description"><?php echo getSetting('hero_1_desc', 'Crafted from the finest European flax. Breathable, timeless, and made to last.'); ?></p>
                    <div class="hero-actions">
                        <a href="<?php echo getSetting('hero_1_btn_link', 'shop.php'); ?>" class="btn btn-dark btn-lg"><?php echo getSetting('hero_1_btn_text', 'Shop the Collection'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide">
        <img src="<?php echo getSetting('hero_2_image', 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=1920&h=1080&fit=crop'); ?>" alt="Linen fashion" loading="lazy">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-content-inner" data-aos="fade-up" data-aos-duration="1000">
                    <span class="hero-subtitle"><?php echo getSetting('hero_2_subtitle', 'Premium Quality'); ?></span>
                    <h1 class="hero-title"><?php echo getSetting('hero_2_title', 'Buy Better,<br>Buy Less.'); ?></h1>
                    <p class="hero-description"><?php echo getSetting('hero_2_desc', 'Each piece is thoughtfully designed to transcend seasons. Quality over quantity, always.'); ?></p>
                    <div class="hero-actions">
                        <a href="<?php echo getSetting('hero_2_btn_link', 'shop.php?filter=best'); ?>" class="btn btn-dark btn-lg"><?php echo getSetting('hero_2_btn_text', 'Best Sellers'); ?></a>
                        <a href="<?php echo getSetting('hero_2_btn2_link', 'shop.php'); ?>" class="btn btn-outline-light btn-lg"><?php echo getSetting('hero_2_btn2_text', 'Explore All'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide">
        <img src="<?php echo getSetting('hero_3_image', 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=1920&h=1080&fit=crop'); ?>" alt="Sustainable linen" loading="lazy">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-content-inner" data-aos="fade-up" data-aos-duration="1000">
                    <span class="hero-subtitle"><?php echo getSetting('hero_3_subtitle', 'Sustainable Style'); ?></span>
                    <h1 class="hero-title"><?php echo getSetting('hero_3_title', 'Naturally<br>Better.'); ?></h1>
                    <p class="hero-description"><?php echo getSetting('hero_3_desc', '100% natural linen. Biodegradable, low-impact, and incredibly comfortable.'); ?></p>
                    <div class="hero-actions">
                        <a href="<?php echo getSetting('hero_3_btn_link', 'shop.php'); ?>" class="btn btn-dark btn-lg"><?php echo getSetting('hero_3_btn_text', 'Shop Now'); ?></a>
                        <a href="<?php echo getSetting('hero_3_btn2_link', 'about.php'); ?>" class="btn btn-outline-light btn-lg"><?php echo getSetting('hero_3_btn2_text', 'Sustainability'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-indicators">
        <button class="hero-indicator active" aria-label="Slide 1"><span class="progress-bar"></span></button>
        <button class="hero-indicator" aria-label="Slide 2"><span class="progress-bar"></span></button>
        <button class="hero-indicator" aria-label="Slide 3"><span class="progress-bar"></span></button>
    </div>
</section>

<!-- ======== Featured Collections ======== -->
<section class="featured-collections">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-subtitle"><?php echo getSetting('collections_subtitle', 'Collections'); ?></span>
            <h2 class="section-title"><?php echo getSetting('collections_title', 'Shop by Category'); ?></h2>
            <p class="section-description"><?php echo getSetting('collections_desc', 'Curated linen essentials for every wardrobe.'); ?></p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <a href="<?php echo getSetting('collection_men_link', 'men.php'); ?>" class="collection-card">
                    <img src="<?php echo getSetting('collection_men_image', 'https://images.unsplash.com/photo-1596900779747-33a5d6c3f3e0?w=800&h=1000&fit=crop'); ?>" alt="Men's linen collection" loading="lazy">
                    <div class="collection-card-overlay">
                        <h3 class="collection-card-title"><?php echo getSetting('collection_men_title', 'Men'); ?></h3>
                        <span class="collection-card-link"><?php echo getSetting('collection_men_link_text', 'Explore Collection →'); ?></span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <a href="<?php echo getSetting('collection_women_link', 'women.php'); ?>" class="collection-card">
                    <img src="<?php echo getSetting('collection_women_image', 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&h=1000&fit=crop'); ?>" alt="Women's linen collection" loading="lazy">
                    <div class="collection-card-overlay">
                        <h3 class="collection-card-title"><?php echo getSetting('collection_women_title', 'Women'); ?></h3>
                        <span class="collection-card-link"><?php echo getSetting('collection_women_link_text', 'Explore Collection →'); ?></span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <a href="<?php echo getSetting('collection_kids_link', 'kids.php'); ?>" class="collection-card">
                    <img src="<?php echo getSetting('collection_kids_image', 'https://images.unsplash.com/photo-1622290291468-a28f7a7dc6a8?w=800&h=1000&fit=crop'); ?>" alt="Kids linen collection" loading="lazy">
                    <div class="collection-card-overlay">
                        <h3 class="collection-card-title"><?php echo getSetting('collection_kids_title', 'Kids'); ?></h3>
                        <span class="collection-card-link"><?php echo getSetting('collection_kids_link_text', 'Explore Collection →'); ?></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ======== New Arrivals ======== -->
<section class="section-padding bg-grey-light">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-subtitle"><?php echo getSetting('arrivals_subtitle', 'Fresh Drops'); ?></span>
            <h2 class="section-title"><?php echo getSetting('arrivals_title', 'New Arrivals'); ?></h2>
            <p class="section-description"><?php echo getSetting('arrivals_desc', 'The latest additions to our linen family.'); ?></p>
        </div>

        <div class="row">
            <?php
            $newArrivals = getProducts(null, 4, false, true);
            if (empty($newArrivals)) {
                $newArrivals = getProducts(null, 4);
            }
            $arrivalIndex = 0;
            foreach ($newArrivals as $product):
                $arrivalIndex++;
                $img = getPrimaryImage($product['id']);
                $hasSale = $product['sale_price'] && $product['sale_price'] < $product['price'];
                $productImages = getProductImages($product['id'], 4);
            ?>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo 100 * $arrivalIndex; ?>">
                <div class="product-card">
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
                            <button class="product-action-btn wishlist-toggle" data-product-id="<?php echo $product['id']; ?>" aria-label="Add to wishlist">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="product-action-btn quick-view-btn" data-product-id="<?php echo $product['id']; ?>" aria-label="Quick view">
                                <i class="bi bi-eye"></i>
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

        <div class="text-center mt-4" data-aos="fade-up">
            <a href="shop.php?filter=new" class="btn btn-outline-dark">View All New Arrivals</a>
        </div>
    </div>
</section>

<!-- ======== Promotional Banner ======== -->
<section class="promo-banner">
    <img class="promo-banner-image" src="<?php echo getSetting('promo_image', 'https://images.unsplash.com/photo-1558769132-cb1c458f7524?w=1920&h=800&fit=crop'); ?>" alt="Linen lifestyle" loading="lazy">
    <div class="container">
        <div class="promo-banner-content" data-aos="fade-up">
            <span class="promo-badge"><?php echo getSetting('promo_badge', 'Limited Offer'); ?></span>
            <h2 class="promo-title"><?php echo str_replace('{amount}', formatPrice(SITE_SHIPPING_FREE_MIN), getSetting('promo_title', 'Free Shipping on Orders Over {amount}')); ?></h2>
            <p class="promo-text"><?php echo getSetting('promo_text', 'Plus complimentary returns within 30 days. Because buying better should be effortless.'); ?></p>
            <a href="<?php echo getSetting('promo_btn_link', 'shop.php'); ?>" class="btn btn-dark btn-lg"><?php echo getSetting('promo_btn_text', 'Start Shopping'); ?></a>
        </div>
    </div>
</section>

<!-- ======== Best Sellers ======== -->
<section class="section-padding">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-subtitle"><?php echo getSetting('bestsellers_subtitle', 'Most Loved'); ?></span>
            <h2 class="section-title"><?php echo getSetting('bestsellers_title', 'Best Sellers'); ?></h2>
            <p class="section-description"><?php echo getSetting('bestsellers_desc', 'The pieces our community reaches for again and again.'); ?></p>
        </div>

        <div class="row">
            <?php
            $bestSellers = getProducts(null, 4, false, false, true);
            if (empty($bestSellers)) {
                $bestSellers = getProducts(null, 4);
            }
            foreach ($bestSellers as $product):
                $img = getPrimaryImage($product['id']);
                $hasSale = $product['sale_price'] && $product['sale_price'] < $product['price'];
                $productImages = getProductImages($product['id'], 4);
            ?>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo 100 * (array_search($product, $bestSellers) + 1); ?>">
                <div class="product-card">
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
                            <?php if ($product['is_best_seller']): ?><span class="product-badge" style="background:var(--color-black);color:var(--color-white);">Best Seller</span><?php endif; ?>
                            <?php if ($hasSale): ?><span class="product-badge sale">Sale</span><?php endif; ?>
                        </div>
                        <div class="product-card-actions">
                            <button class="product-action-btn wishlist-toggle" data-product-id="<?php echo $product['id']; ?>" aria-label="Add to wishlist">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="product-action-btn quick-view-btn" data-product-id="<?php echo $product['id']; ?>" aria-label="Quick view">
                                <i class="bi bi-eye"></i>
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

        <div class="text-center mt-4" data-aos="fade-up">
            <a href="shop.php" class="btn btn-outline-dark">View All Best Sellers</a>
        </div>
    </div>
</section>

<!-- ======== Testimonials ======== -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-subtitle"><?php echo getSetting('testimonials_subtitle', 'Testimonials'); ?></span>
            <h2 class="section-title"><?php echo getSetting('testimonials_title', 'What Our Community Says'); ?></h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"The quality of LINO UNION linen is exceptional. I've been searching for linen that doesn't feel stiff, and this is perfect. It only gets softer with every wash."</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Sarah" loading="lazy">
                        <div>
                            <div class="testimonial-name">Sarah Mitchell</div>
                            <div class="testimonial-role">Verified Buyer</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Buying less but better has transformed my wardrobe. The relaxed linen blazer is my most-worn piece — it goes with everything and travel beautifully."</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="James" loading="lazy">
                        <div>
                            <div class="testimonial-name">James Chen</div>
                            <div class="testimonial-role">Verified Buyer</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"I bought matching linen sets for my kids and they absolutely love them. Finally, linen that's comfortable enough for playdates but stylish enough for family photos."</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Emma" loading="lazy">
                        <div>
                            <div class="testimonial-name">Emma Rodriguez</div>
                            <div class="testimonial-role">Verified Buyer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
