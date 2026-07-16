<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 d-md-block admin-sidebar p-0">
                <div class="p-3">
                    <a href="dashboard.php" class="admin-logo">LINO <span>UNION</span></a>
                    <nav class="mt-4">
                        <a href="dashboard.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="bi bi-grid"></i> Dashboard
                        </a>
                        <a href="products.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>">
                            <i class="bi bi-box-seam"></i> Products
                        </a>
                        <a href="orders.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : ''; ?>">
                            <i class="bi bi-truck"></i> Orders
                        </a>
                        <a href="customize.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'customize.php' ? 'active' : ''; ?>">
                            <i class="bi bi-palette"></i> Customize
                        </a>
                        <a href="../index.php" class="admin-nav-item" target="_blank">
                            <i class="bi bi-shop"></i> View Store
                        </a>
                        <hr style="border-color:rgba(255,255,255,0.1);">
                        <a href="../logout.php" class="admin-nav-item">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 ms-auto admin-content">
