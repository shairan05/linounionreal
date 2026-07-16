<?php
require_once __DIR__ . '/includes/config.php';
$meta_title = 'Checkout | LINO UNION';
$meta_description = 'Complete your order at LINO UNION. Premium linen essentials with secure checkout.';

$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal >= SITE_SHIPPING_FREE_MIN ? 0 : SITE_SHIPPING_RATE;
$tax = $subtotal * SITE_TAX_RATE / 100;
$total = $subtotal + $shipping + $tax;

// Pre-fill from session if logged in
$userData = [];
if (isLoggedIn()) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $userData = $stmt->fetch() ?: [];
}
?>

<!-- ======== Page Header ======== -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="cart.php">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
        <h1 class="page-title">Checkout</h1>
    </div>
</section>

<!-- ======== Checkout Content ======== -->
<section class="checkout-page">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <form id="checkoutForm" method="POST" action="place-order.php">
                    <!-- Shipping Information -->
                    <div class="checkout-form-section">
                        <h3 class="checkout-form-title">Shipping Information</h3>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($userData['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($userData['last_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" name="address_line1" value="<?php echo htmlspecialchars($userData['address_line1'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address Line 2 (Optional)</label>
                                <input type="text" class="form-control" name="address_line2" value="<?php echo htmlspecialchars($userData['address_line2'] ?? ''); ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($userData['city'] ?? ''); ?>" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($userData['state'] ?? ''); ?>" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" name="zip_code" value="<?php echo htmlspecialchars($userData['zip_code'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-form-section">
                        <h3 class="checkout-form-title">Payment Method</h3>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" checked>
                            <label class="form-check-label" for="stripe">
                                <i class="bi bi-credit-card me-2"></i>Credit Card (Stripe)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                <i class="bi bi-paypal me-2"></i>PayPal
                            </label>
                        </div>
                        <div class="mt-3 p-3 bg-light">
                            <div class="mb-3">
                                <label class="form-label">Card Number</label>
                                <input type="text" class="form-control" placeholder="4242 4242 4242 4242">
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">CVC</label>
                                    <input type="text" class="form-control" placeholder="123">
                                </div>
                            </div>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="saveInfo" name="save_info">
                            <label class="form-check-label" for="saveInfo">Save this information for next time</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark btn-lg w-100">
                        <i class="bi bi-lock-fill me-2"></i>Place Order — <?php echo formatPrice($total); ?>
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="order-summary">
                    <h4 class="order-summary-title">Order Summary</h4>
                    <?php foreach ($cartItems as $item): ?>
                    <div class="order-item">
                        <div class="order-item-image">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                        </div>
                        <div class="order-item-details">
                            <div class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="order-item-variant">
                                <?php if (!empty($item['size'])): ?>Size: <?php echo $item['size']; ?><?php endif; ?>
                                <?php if (!empty($item['size']) && !empty($item['color'])): ?> | <?php endif; ?>
                                <?php if (!empty($item['color'])): ?>Color: <?php echo $item['color']; ?><?php endif; ?>
                            </div>
                            <div class="order-item-qty">Qty: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="order-item-total"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                    </div>
                    <?php endforeach; ?>

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span><?php echo $shipping > 0 ? formatPrice($shipping) : '<span class="text-success">Free</span>'; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax</span>
                        <span><?php echo formatPrice($tax); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong style="font-size:1.25rem;"><?php echo formatPrice($total); ?></strong>
                    </div>

                    <!-- Trust badges -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-shield-check me-1"></i>
                            SSL Secure Encryption
                        </small>
                        <small class="text-muted d-block">
                            <i class="bi bi-arrow-return-left me-1"></i>
                            30-Day Free Returns
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
