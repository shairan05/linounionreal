<?php
http_response_code(404);
require_once __DIR__ . '/includes/config.php';
$meta_title = 'Page Not Found | LINO UNION';
require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-page">
    <div class="container">
        <div class="auth-container text-center">
            <div class="mb-4">
                <span style="font-family:var(--font-secondary);font-size:6rem;color:var(--color-gold);line-height:1;">404</span>
            </div>
            <h1 class="auth-title">Page Not Found</h1>
            <p class="auth-subtitle">The page you're looking for doesn't exist or has been moved.</p>
            <div class="mt-4 d-flex gap-3 justify-content-center">
                <a href="index.php" class="btn btn-dark">Back to Home</a>
                <a href="shop.php" class="btn btn-outline-dark">Shop Collection</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
