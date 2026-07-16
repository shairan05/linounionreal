<?php
require_once __DIR__ . '/includes/config.php';
$meta_title = 'Shopping Cart | LINO UNION';
$meta_description = 'Review your LINO UNION shopping cart. Premium linen essentials for the conscious wardrobe.';
require_once __DIR__ . '/includes/header.php';

$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!-- ======== Page Header ======== -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Cart</li>
            </ol>
        </nav>
        <h1 class="page-title">Shopping Cart</h1>
    </div>
</section>

<!-- ======== Cart Content ======== -->
<section class="cart-page">
    <div class="container">
        <?php if (empty($cartItems)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-bag"></i></div>
            <h3 class="empty-state-title">Your cart is empty</h3>
            <p class="empty-state-text">Looks like you haven't added anything yet. Let's fill it with some beautiful linen pieces.</p>
            <a href="shop.php" class="btn btn-dark btn-lg">Continue Shopping</a>
        </div>
        <?php else: ?>
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><?php echo count($cartItems); ?> item(s) in your cart</h5>
                    <a href="shop.php" class="btn btn-sm btn-outline-dark">Continue Shopping</a>
                </div>

                <?php $subtotal = 0; ?>
                <?php foreach ($cartItems as $index => $item):
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                ?>
                <div class="cart-item" data-item-index="<?php echo $index; ?>">
                    <div class="cart-item-image">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                    </div>
                    <div class="cart-item-details">
                        <h4 class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                        <div class="cart-item-variant">
                            <?php if (!empty($item['size'])): ?>Size: <?php echo htmlspecialchars($item['size']); ?><?php endif; ?>
                            <?php if (!empty($item['size']) && !empty($item['color'])): ?> | <?php endif; ?>
                            <?php if (!empty($item['color'])): ?>Color: <?php echo htmlspecialchars($item['color']); ?><?php endif; ?>
                        </div>
                        <div class="cart-item-price"><?php echo formatPrice($item['price']); ?></div>
                    </div>
                    <div class="cart-item-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn quantity-minus" type="button">−</button>
                            <input type="number" class="quantity-input cart-quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="99" data-item-id="<?php echo $index; ?>">
                            <button class="quantity-btn quantity-plus" type="button">+</button>
                        </div>
                        <div class="text-end" style="min-width:80px;">
                            <div class="cart-item-price"><?php echo formatPrice($itemTotal); ?></div>
                            <button class="cart-item-remove mt-2" data-item-id="<?php echo $index; ?>">
                                <i class="bi bi-trash3 me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4 class="cart-summary-title">Order Summary</h4>
                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <span class="cart-subtotal"><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Shipping</span>
                        <span>
                            <?php if ($subtotal >= SITE_SHIPPING_FREE_MIN): ?>
                                <span class="text-success">Free</span>
                            <?php else: ?>
                                <?php echo formatPrice(SITE_SHIPPING_RATE); ?>
                                <small class="d-block text-muted">Free on orders over <?php echo formatPrice(SITE_SHIPPING_FREE_MIN); ?></small>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Tax (estimated)</span>
                        <span><?php echo formatPrice($subtotal * SITE_TAX_RATE / 100); ?></span>
                    </div>
                    <div class="cart-summary-row total">
                        <span>Total</span>
                        <span class="cart-total"><?php echo formatPrice($subtotal + ($subtotal >= SITE_SHIPPING_FREE_MIN ? 0 : SITE_SHIPPING_RATE) + ($subtotal * SITE_TAX_RATE / 100)); ?></span>
                    </div>

                    <div class="promo-code-form">
                        <input type="text" class="form-control" placeholder="Promo code" id="promoCode">
                        <button class="btn btn-outline-dark" id="applyPromo">Apply</button>
                    </div>

                    <a href="checkout.php" class="btn btn-dark btn-lg w-100 mb-2">
                        <i class="bi bi-lock-fill me-2"></i>Checkout
                    </a>
                    <a href="shop.php" class="btn btn-outline-dark w-100">Continue Shopping</a>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Secure checkout with SSL encryption
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
