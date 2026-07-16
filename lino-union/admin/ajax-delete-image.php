<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

header('Content-Type: application/json');

$imageId = (int)($_POST['image_id'] ?? 0);
if (!$imageId) {
    echo json_encode(['success' => false, 'message' => 'Invalid image ID.']);
    exit;
}

try {
    $db = getDB();
    
    // Get image info before deleting
    $stmt = $db->prepare("SELECT * FROM product_images WHERE id = :id");
    $stmt->execute([':id' => $imageId]);
    $image = $stmt->fetch();
    
    if (!$image) {
        echo json_encode(['success' => false, 'message' => 'Image not found.']);
        exit;
    }
    
    $productId = $image['product_id'];
    $wasPrimary = (bool)$image['is_primary'];
    
    // Delete file from disk (suppress warning if file already missing)
    $filePath = BASE_PATH . $image['image_url'];
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
    
    // Delete DB record
    $db->prepare("DELETE FROM product_images WHERE id = :id")->execute([':id' => $imageId]);
    
    // If the deleted image was primary, assign primary to the next available image
    if ($wasPrimary) {
        $db->prepare("UPDATE product_images SET is_primary = 1 WHERE product_id = :pid ORDER BY sort_order ASC LIMIT 1")
           ->execute([':pid' => $productId]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Image deleted.', 'was_primary' => $wasPrimary]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
