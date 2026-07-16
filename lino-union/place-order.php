<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Validate required fields
$required = ['first_name', 'last_name', 'email', 'address_line1', 'city', 'state', 'zip_code'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        header('Location: checkout.php?error=missing_fields');
        exit;
    }
}

try {
    $db = getDB();

    // Calculate totals
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping = $subtotal >= SITE_SHIPPING_FREE_MIN ? 0 : SITE_SHIPPING_RATE;
    $tax = $subtotal * SITE_TAX_RATE / 100;
    $total = $subtotal + $shipping + $tax;

    $orderNumber = generateOrderNumber();

    // Insert order
    $stmt = $db->prepare("INSERT INTO orders (order_number, user_id, first_name, last_name, email, phone,
                          address_line1, address_line2, city, state, zip_code, country, subtotal, shipping, tax, total, status)
                          VALUES (:order_number, :user_id, :fn, :ln, :email, :phone, :addr1, :addr2, :city, :state, :zip, :country, :subtotal, :shipping, :tax, :total, 'pending')");

    $stmt->execute([
        ':order_number' => $orderNumber,
        ':user_id' => isLoggedIn() ? $_SESSION['user_id'] : null,
        ':fn' => sanitize($_POST['first_name']),
        ':ln' => sanitize($_POST['last_name']),
        ':email' => sanitize($_POST['email']),
        ':phone' => sanitize($_POST['phone'] ?? ''),
        ':addr1' => sanitize($_POST['address_line1']),
        ':addr2' => sanitize($_POST['address_line2'] ?? ''),
        ':city' => sanitize($_POST['city']),
        ':state' => sanitize($_POST['state']),
        ':zip' => sanitize($_POST['zip_code']),
        ':country' => sanitize($_POST['country'] ?? 'United States'),
        ':subtotal' => $subtotal,
        ':shipping' => $shipping,
        ':tax' => $tax,
        ':total' => $total
    ]);

    $orderId = $db->lastInsertId();

    // Insert order items
    $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_sku, variant_info, quantity, price, total)
                          VALUES (:order_id, :product_id, :name, :sku, :variant, :qty, :price, :total)");

    foreach ($_SESSION['cart'] as $item) {
        $variantInfo = trim(($item['size'] ?? '') . ' ' . ($item['color'] ?? ''));
        $itemTotal = $item['price'] * $item['quantity'];

        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $item['product_id'],
            ':name' => $item['name'],
            ':sku' => '',
            ':variant' => $variantInfo,
            ':qty' => $item['quantity'],
            ':price' => $item['price'],
            ':total' => $itemTotal
        ]);
    }

    // Save user info if logged in
    if (isLoggedIn() && !empty($_POST['save_info'])) {
        $stmt = $db->prepare("UPDATE users SET address_line1 = :addr1, address_line2 = :addr2, city = :city, state = :state, zip_code = :zip, phone = :phone WHERE id = :id");
        $stmt->execute([
            ':addr1' => sanitize($_POST['address_line1']),
            ':addr2' => sanitize($_POST['address_line2'] ?? ''),
            ':city' => sanitize($_POST['city']),
            ':state' => sanitize($_POST['state']),
            ':zip' => sanitize($_POST['zip_code']),
            ':phone' => sanitize($_POST['phone'] ?? ''),
            ':id' => $_SESSION['user_id']
        ]);
    }

    // Clear cart
    $_SESSION['cart'] = [];

    // Redirect to confirmation
    header('Location: order-confirmation.php?order=' . $orderNumber);
    exit;

} catch (Exception $e) {
    header('Location: checkout.php?error=processing');
    exit;
}
