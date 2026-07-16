<?php
// ============================================
// LINO UNION – Database Seeder
// Run ONCE from your browser, then DELETE this file!
// ============================================

require_once __DIR__ . '/includes/config.php';

$output = [];

// Helper to run SQL safely
function runSQL($db, $sql, $params = []) {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

try {
    $db = getDB();

    // Check if tables exist
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        // No tables at all – import the full schema
        $sql = file_get_contents(__DIR__ . '/database.sql');
        // Split by semicolons and execute each statement
        $statements = explode(';', $sql);
        $count = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && stripos($statement, 'CREATE DATABASE') === false && stripos($statement, 'USE ') === false) {
                try {
                    $db->exec($statement);
                    $count++;
                } catch (Exception $e) {
                    $output[] = "⚠️ Skipped: " . $e->getMessage();
                }
            }
        }
        $output[] = "✅ Database schema created ($count statements executed).";
    } else {
        $output[] = "✅ Tables already exist: " . implode(', ', $tables);
    }

    // Check if products exist
    $productCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();

    if ($productCount == 0) {
        // Insert categories
        $output[] = "📦 Inserting categories...";
        runSQL($db, "INSERT IGNORE INTO categories (name, slug, description, sort_order) VALUES ('Men', 'men', 'Premium linen clothing for men', 1)");
        runSQL($db, "INSERT IGNORE INTO categories (name, slug, description, sort_order) VALUES ('Women', 'women', 'Elegant linen clothing for women', 2)");
        runSQL($db, "INSERT IGNORE INTO categories (name, slug, description, sort_order) VALUES ('Kids', 'kids', 'Comfortable linen clothing for kids', 3)");

        // Get category IDs
        $cats = [];
        $result = $db->query("SELECT id, slug FROM categories WHERE parent_id IS NULL");
        foreach ($result as $row) $cats[$row['slug']] = $row['id'];

        // Insert subcategories
        $subs = [
            ['Men Shirts', 'men-shirts', $cats['men'] ?? 1, 1],
            ['Men Pants', 'men-pants', $cats['men'] ?? 1, 2],
            ['Men Shorts', 'men-shorts', $cats['men'] ?? 1, 3],
            ['Women Dresses', 'women-dresses', $cats['women'] ?? 2, 1],
            ['Women Tops', 'women-tops', $cats['women'] ?? 2, 2],
            ['Women Skirts', 'women-skirts', $cats['women'] ?? 2, 3],
            ['Kids Boys', 'kids-boys', $cats['kids'] ?? 3, 1],
            ['Kids Girls', 'kids-girls', $cats['kids'] ?? 3, 2],
        ];
        foreach ($subs as $s) {
            runSQL($db, "INSERT IGNORE INTO categories (name, slug, parent_id, sort_order) VALUES (?, ?, ?, ?)", $s);
        }

        // Sample products (prices in INR)
        $output[] = "📦 Inserting sample products...";
        $products = [
            ['Classic White Linen Shirt', 'classic-white-linen-shirt', 'Our signature classic white linen shirt, crafted from premium European flax.', 'Premium European flax linen shirt with relaxed fit', 2499.00, 1999.00, 'LU-MSH-001', 50, 4, '100% European Flax Linen', 'Machine wash cold, tumble dry low', 1, 1, 1],
            ['Natural Linen Relaxed Blazer', 'natural-linen-relaxed-blazer', 'A relaxed linen blazer that transitions effortlessly from office to evening.', 'Unstructured linen blazer for versatile styling', 5499.00, null, 'LU-MSH-002', 30, 4, '100% Linen', 'Dry clean recommended', 1, 1, 0],
            ['Linen Wide-Leg Trousers', 'linen-wide-leg-trousers', 'Wide-leg trousers in heavyweight linen with side pockets and high-rise waist.', 'High-rise wide-leg linen trousers', 3799.00, null, 'LU-MPN-001', 35, 5, '100% French Linen', 'Machine wash gentle, hang to dry', 1, 0, 1],
            ['Relaxed Linen Pleated Pant', 'relaxed-linen-pleated-pant', 'A relaxed take on the classic pleated trouser with elasticated waist.', 'Elasticated waist pleated linen pants', 3299.00, 2799.00, 'LU-MPN-002', 45, 5, '100% Linen', 'Machine wash cold, tumble dry low', 0, 1, 0],
            ['The Column Linen Dress', 'the-column-linen-dress', 'A sculptural column dress in heavyweight linen with hidden back zip.', 'Column silhouette linen dress', 4599.00, 3999.00, 'LU-WDR-001', 25, 7, '100% Belgian Linen', 'Dry clean recommended', 1, 1, 1],
            ['Linen Smocked Midi Dress', 'linen-smocked-midi-dress', 'A romantic midi dress with smocked bodice and flowing tiered skirt.', 'Smocked bodice midi linen dress', 3499.00, null, 'LU-WDR-002', 35, 7, '100% Linen', 'Hand wash cold, hang to dry', 1, 1, 0],
            ['Linen Button-Front Midi Skirt', 'linen-button-front-midi-skirt', 'A column midi skirt with full front button placket and center vent.', 'High-waisted button-front linen skirt', 2999.00, 2499.00, 'LU-WSK-001', 30, 9, '100% Linen', 'Machine wash gentle, hang to dry', 0, 0, 1],
            ['Cropped Linen Top', 'cropped-linen-top', 'A modern cropped top in crisp linen with square neckline and puff sleeves.', 'Square neck crop top in linen', 1999.00, null, 'LU-WTP-001', 55, 8, '100% Linen', 'Machine wash cold, tumble dry low', 0, 1, 0],
            ['Linen A-Line Mini Dress', 'linen-a-line-mini-dress', 'A playful mini dress in lightweight linen with A-line silhouette.', 'A-line mini linen dress with puff sleeves', 2599.00, null, 'LU-KGR-001', 40, 11, '100% Linen', 'Machine wash cold, tumble dry low', 1, 1, 1],
            ['Linen Button-Up Shirt for Boys', 'linen-button-up-shirt-boys', 'A miniature version of our classic linen shirt for boys.', 'Classic linen button-up shirt for boys', 1499.00, 1299.00, 'LU-KBY-001', 45, 10, '100% Linen', 'Machine wash cold, tumble dry low', 1, 1, 0],
        ];

        $pStmt = $db->prepare("INSERT INTO products (name, slug, description, short_description, price, sale_price, sku, stock_quantity, category_id, material, care_instructions, is_featured, is_new_arrival, is_best_seller) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($products as $p) {
            $pStmt->execute($p);
            $pid = $db->lastInsertId();

            // Insert variants for the first two products
            if ($pid <= 2) {
                $sizes = ['S', 'M', 'L', 'XL'];
                $colors = $pid == 1 ? ['White', 'Natural'] : ['Natural'];
                foreach ($sizes as $si) {
                    foreach ($colors as $ci) {
                        $hex = $ci === 'White' ? '#FFFFFF' : '#D4C5A9';
                        $db->prepare("INSERT INTO product_variants (product_id, size, color, color_hex, stock_quantity, price_adjustment) VALUES (?, ?, ?, ?, ?, ?)")->execute([$pid, $si, $ci, $hex, rand(5, 15), $si === 'XL' ? 5 : 0]);
                    }
                }
            }
        }

        $productCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $output[] = "✅ Inserted $productCount sample products with variants!";
    } else {
        $output[] = "✅ Products already exist in database ($productCount found).";
    }

    $message = 'Database seeded successfully!';

} catch (Exception $e) {
    $error = '❌ Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed Data – LINO UNION</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; background: #f8f8f8; }
        .card { background: white; padding: 2rem; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid #2e7d32; }
        .error { background: #ffebee; color: #c62828; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid #c62828; }
        .info { background: #f5f5f5; padding: 0.75rem 1rem; margin-bottom: 0.5rem; border-left: 4px solid #999; font-size: 0.9rem; }
        .btn { background: #1a1a1a; color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn:hover { background: #333; }
        .danger { color: #c62828; font-size: 0.85rem; margin-top: 1.5rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🌱 LINO UNION – Database Seeder</h1>
        <p style="color:#666;margin-bottom:1.5rem;">Seeds the database with sample categories, products, and variants.</p>

        <?php if (isset($message)): ?>
            <div class="success">✅ Done! <a href="admin/products.php" class="btn" style="margin-left:1rem;">Go to Admin → Products</a></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php foreach ($output as $line): ?>
            <div class="info"><?php echo $line; ?></div>
        <?php endforeach; ?>

        <?php if (!isset($message)): ?>
        <form method="POST">
            <button type="submit" class="btn">🌱 Seed Database Now</button>
        </form>
        <?php endif; ?>

        <p class="danger">
            ⚠️ <strong>DELETE this file</strong> (seed-data.php) after use!
        </p>
    </div>
</body>
</html>
