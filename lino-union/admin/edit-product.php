<?php
require_once __DIR__ . '/../includes/config.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

$db = getDB();
$message = '';
$error = '';
$product = null;
$isEditing = false;
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Flash messages
if (isset($_SESSION['flash_message'])) {
    if ($_SESSION['flash_type'] === 'success') {
        $message = $_SESSION['flash_message'];
    } else {
        $error = $_SESSION['flash_message'];
    }
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}

// Load existing product for editing
if ($productId) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();
    if ($product) {
        $isEditing = true;
    } else {
        $error = 'Product not found.';
    }
}

// Handle form save (both add + edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $name = sanitize($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $salePrice = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
    $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $desc = sanitize($_POST['description'] ?? '');
    $shortDesc = sanitize($_POST['short_description'] ?? '');
    $sku = sanitize($_POST['sku'] ?? '');
    $stock = (int)($_POST['stock_quantity'] ?? 0);
    $material = sanitize($_POST['material'] ?? '');
    $care = sanitize($_POST['care_instructions'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isNew = isset($_POST['is_new_arrival']) ? 1 : 0;
    $isBest = isset($_POST['is_best_seller']) ? 1 : 0;
    $metaTitle = sanitize($_POST['meta_title'] ?? '');
    $metaDesc = sanitize($_POST['meta_description'] ?? '');
    $editId = (int)($_POST['product_id'] ?? 0);

    if ($name && $sku) {
        try {
            if ($editId) {
                // UPDATE existing product
                $stmt = $db->prepare("UPDATE products SET
                    name=:name, price=:price, sale_price=:sale_price,
                    category_id=:cat_id, description=:desc, short_description=:short_desc,
                    sku=:sku, stock_quantity=:stock, material=:material,
                    care_instructions=:care, is_active=:active,
                    is_featured=:featured, is_new_arrival=:new_arrival, is_best_seller=:best_seller,
                    meta_title=:meta_title, meta_description=:meta_desc
                    WHERE id=:id");
                $stmt->bindValue(':id', $editId, PDO::PARAM_INT);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':price', $price);
                $stmt->bindValue(':sale_price', $salePrice);
                $stmt->bindValue(':cat_id', $categoryId);
                $stmt->bindValue(':desc', $desc);
                $stmt->bindValue(':short_desc', $shortDesc);
                $stmt->bindValue(':sku', $sku);
                $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
                $stmt->bindValue(':material', $material);
                $stmt->bindValue(':care', $care);
                $stmt->bindValue(':active', $isActive, PDO::PARAM_INT);
                $stmt->bindValue(':featured', $isFeatured, PDO::PARAM_INT);
                $stmt->bindValue(':new_arrival', $isNew, PDO::PARAM_INT);
                $stmt->bindValue(':best_seller', $isBest, PDO::PARAM_INT);
                $stmt->bindValue(':meta_title', $metaTitle);
                $stmt->bindValue(':meta_desc', $metaDesc);
                $stmt->execute();
                $savedId = $editId;
                $flashMsg = '✅ Product updated successfully!';
            } else {
                // INSERT new product
                $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $name), '-'));
                $finalSlug = $slug . '-' . time();
                $stmt = $db->prepare("INSERT INTO products (name, slug, price, sale_price, category_id,
                    description, short_description, sku, stock_quantity, material,
                    care_instructions, is_active, is_featured, is_new_arrival, is_best_seller,
                    meta_title, meta_description)
                    VALUES (:name, :slug, :price, :sale_price, :cat_id, :desc, :short_desc,
                    :sku, :stock, :material, :care, :active, :featured, :new_arrival, :best_seller,
                    :meta_title, :meta_desc)");
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':slug', $finalSlug);
                $stmt->bindValue(':price', $price);
                $stmt->bindValue(':sale_price', $salePrice);
                $stmt->bindValue(':cat_id', $categoryId);
                $stmt->bindValue(':desc', $desc);
                $stmt->bindValue(':short_desc', $shortDesc);
                $stmt->bindValue(':sku', $sku);
                $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
                $stmt->bindValue(':material', $material);
                $stmt->bindValue(':care', $care);
                $stmt->bindValue(':active', $isActive, PDO::PARAM_INT);
                $stmt->bindValue(':featured', $isFeatured, PDO::PARAM_INT);
                $stmt->bindValue(':new_arrival', $isNew, PDO::PARAM_INT);
                $stmt->bindValue(':best_seller', $isBest, PDO::PARAM_INT);
                $stmt->bindValue(':meta_title', $metaTitle);
                $stmt->bindValue(':meta_desc', $metaDesc);
                $stmt->execute();
                $savedId = (int)$db->lastInsertId();
                $flashMsg = '✅ Product saved successfully!';
            }

            // Handle image upload
            if (!empty($_FILES['product_images']['name'][0])) {
                $uploadDir = __DIR__ . '/../assets/uploads/products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                // First: reset ALL existing images' is_primary to 0 so new images take over
                $db->prepare("UPDATE product_images SET is_primary = 0 WHERE product_id = :pid")->execute([':pid' => $savedId]);
                
                $files = $_FILES['product_images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                        $allowed = ['jpg','jpeg','png','webp','gif'];
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $files['tmp_name'][$i]);
                        finfo_close($finfo);
                        $allowedMimes = ['image/jpeg','image/png','image/webp','image/gif'];
                        if (in_array($ext, $allowed) && in_array($mime, $allowedMimes) && $files['size'][$i] <= 5*1024*1024) {
                            $newName = $savedId . '_' . time() . '_' . $i . '.' . $ext;
                            if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $newName)) {
                                $isPrimary = ($i === 0) ? 1 : 0;
                                $imgStmt = $db->prepare("INSERT INTO product_images (product_id, image_url, alt_text, sort_order, is_primary)
                                    VALUES (:pid, :url, :alt, :sort, :primary)");
                                $imgStmt->execute([
                                    ':pid' => $savedId,
                                    ':url' => 'assets/uploads/products/' . $newName,
                                    ':alt' => $name,
                                    ':sort' => $i,
                                    ':primary' => $isPrimary
                                ]);
                            }
                        }
                    }
                }
                
                // Safety net: ensure at least one image is primary (handles edge case where first file fails validation)
                $db->prepare("UPDATE product_images SET is_primary = 1 WHERE product_id = :pid ORDER BY sort_order ASC LIMIT 1")
                   ->execute([':pid' => $savedId]);
            }

            $_SESSION['flash_message'] = $flashMsg;
            $_SESSION['flash_type'] = 'success';
            header('Location: products.php');
            exit;
        } catch (Exception $e) {
            $error = '❌ Error: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all required fields (Name, SKU).';
    }
}

$categories = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

require_once __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-header">
    <div>
        <h1><?php echo $isEditing ? 'Edit Product' : 'Add Product'; ?></h1>
        <p class="text-muted mb-0"><a href="products.php" class="text-muted">&larr; Back to Products</a></p>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success border-0 rounded-0 py-2"><?php echo $message; ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger border-0 rounded-0 py-2"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <?php if ($isEditing): ?>
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <?php endif; ?>
    <input type="hidden" name="save" value="1">

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card rounded-0 border mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Basic Information</h5>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="<?php echo $isEditing ? htmlspecialchars($product['name']) : ''; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sku" value="<?php echo $isEditing ? htmlspecialchars($product['sku']) : ''; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id">
                                <option value="">Select category</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $isEditing && $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Material</label>
                            <input type="text" class="form-control" name="material" value="<?php echo $isEditing ? htmlspecialchars($product['material'] ?? '') : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Care Instructions</label>
                            <input type="text" class="form-control" name="care_instructions" value="<?php echo $isEditing ? htmlspecialchars($product['care_instructions'] ?? '') : ''; ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short Description</label>
                            <textarea class="form-control" name="short_description" rows="2" maxlength="500"><?php echo $isEditing ? htmlspecialchars($product['short_description'] ?? '') : ''; ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Description</label>
                            <textarea class="form-control" name="description" rows="5"><?php echo $isEditing ? htmlspecialchars($product['description'] ?? '') : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="card rounded-0 border mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Product Images</h5>
                    <?php if ($isEditing): ?>
                        <?php $existingImages = getAllImages($product['id']); ?>
                        <?php if (!empty($existingImages)): ?>
                        <label class="form-label fw-bold">Current Images:</label>
                        <div class="row g-2 mb-3" id="existingImages">
                            <?php foreach ($existingImages as $img): ?>
                            <div class="col-4 col-md-3" data-image-id="<?php echo $img['id']; ?>">
                                <div style="position:relative;aspect-ratio:3/4;overflow:hidden;background:var(--color-grey-light);border:2px solid <?php echo $img['is_primary'] ? 'var(--color-black)' : 'transparent'; ?>;">
                                    <img src="../<?php echo $img['image_url']; ?>" alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                                    <button type="button" class="btn btn-sm btn-danger delete-image-btn"
                                        data-image-id="<?php echo $img['id']; ?>"
                                        data-product-id="<?php echo $product['id']; ?>"
                                        title="Remove this image"
                                        style="position:absolute;top:4px;right:4px;width:28px;height:28px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:0.75rem;opacity:0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <?php if ($img['is_primary']): ?>
                                    <span style="position:absolute;bottom:4px;left:4px;background:var(--color-black);color:white;font-size:0.6rem;padding:2px 6px;letter-spacing:0.05em;">PRIMARY</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <label class="form-label fw-bold">Upload New Images:</label>
                    <input type="file" name="product_images[]" multiple accept="image/jpeg,image/png,image/webp" class="form-control">
                    <small class="text-muted">Accepted: JPG, PNG, WebP (max 5MB each)</small>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card rounded-0 border mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pricing & Stock</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" name="price" value="<?php echo $isEditing ? $product['price'] : ''; ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sale Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="sale_price" value="<?php echo $isEditing && $product['sale_price'] ? $product['sale_price'] : ''; ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" min="0" class="form-control" name="stock_quantity" value="<?php echo $isEditing ? $product['stock_quantity'] : '0'; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card rounded-0 border mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Status</h5>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" <?php echo !$isEditing || $product['is_active'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" <?php echo $isEditing && $product['is_featured'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="featured">Featured</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="newarrival" <?php echo $isEditing && $product['is_new_arrival'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="newarrival">New Arrival</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="bestseller" <?php echo $isEditing && $product['is_best_seller'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="bestseller">Best Seller</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card rounded-0 border mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">SEO</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" value="<?php echo $isEditing ? htmlspecialchars($product['meta_title'] ?? '') : ''; ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Meta Description</label>
                            <textarea class="form-control" name="meta_description" rows="3" maxlength="320"><?php echo $isEditing ? htmlspecialchars($product['meta_description'] ?? '') : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2"><?php echo $isEditing ? '💾 Update Product' : '💾 Save Product'; ?></button>
            <a href="products.php" class="btn btn-outline-dark w-100 mt-2">Cancel</a>
        </div>
    </div>
</form>

<script>
// Delete image via AJAX
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.delete-image-btn');
    if (!btn) return;
    
    var imageId = btn.getAttribute('data-image-id');
    if (!imageId || !confirm('Remove this image permanently?')) return;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:12px;height:12px;"></span>';
    
    var formData = new FormData();
    formData.append('image_id', imageId);
    
    fetch('ajax-delete-image.php', {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            var col = btn.closest('[data-image-id]');
            if (col) {
                col.style.transition = 'all 0.3s ease';
                col.style.opacity = '0';
                col.style.transform = 'scale(0.8)';
                setTimeout(function() { 
                    col.remove(); 
                    // If the deleted image was primary, refresh page so PRIMARY badge updates
                    if (data.was_primary) {
                        location.reload();
                    }
                }, 300);
            }
            showToast('Image removed', 'success');
        } else {
            alert('Error: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-x"></i>';
        }
    })
    .catch(function() {
        alert('Error deleting image.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-x"></i>';
    });
});

// Toast notification helper for admin pages
function showToast(message, type) {
    var container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position:fixed;bottom:1rem;right:1rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;';
        document.body.appendChild(container);
    }
    var toast = document.createElement('div');
    toast.style.cssText = 'padding:0.75rem 1.25rem;background:' + (type === 'success' ? '#E8F5E9' : '#FFEBEE') + ';border-left:3px solid ' + (type === 'success' ? '#2E7D32' : '#C62828') + ';font-size:0.9rem;box-shadow:0 4px 12px rgba(0,0,0,0.1);min-width:250px;opacity:0;transition:opacity 0.3s ease;';
    toast.textContent = message;
    container.appendChild(toast);
    // Fade in
    requestAnimationFrame(function() {
        toast.style.opacity = '1';
    });
    // Fade out after 3s
    setTimeout(function() {
        toast.style.opacity = '0';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
