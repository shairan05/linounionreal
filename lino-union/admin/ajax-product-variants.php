<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

header('Content-Type: application/json');

$productId = (int)($_GET['product_id'] ?? 0);
if (!$productId) {
    echo json_encode([]);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM product_variants WHERE product_id = :pid ORDER BY FIELD(size, 'XS','S','M','L','XL','XXL'), color");
    $stmt->execute([':pid' => $productId]);
    $variants = $stmt->fetchAll();
    echo json_encode($variants);
} catch (Exception $e) {
    echo json_encode([]);
}
