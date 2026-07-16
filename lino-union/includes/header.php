<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php if (isset($meta_title) && $meta_title): ?>
    <title><?php echo htmlspecialchars($meta_title); ?> | <?php echo SITE_NAME; ?></title>
    <meta property="og:title" content="<?php echo htmlspecialchars($meta_title); ?> | <?php echo SITE_NAME; ?>">
    <?php else: ?>
    <title><?php echo SITE_NAME; ?> – <?php echo SITE_TAGLINE; ?></title>
    <meta property="og:title" content="<?php echo SITE_NAME; ?> – <?php echo SITE_TAGLINE; ?>">
    <?php endif; ?>

    <?php if (isset($meta_description) && $meta_description): ?>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <?php else: ?>
    <meta name="description" content="LINO UNION – Premium linen clothing crafted for those who believe in buying better, buying less. Discover our curated collection of timeless linen essentials.">
    <meta property="og:description" content="LINO UNION – Premium linen clothing crafted for those who believe in buying better, buying less.">
    <?php endif; ?>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/og-image.jpg">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/style.css">

    <!-- Dynamic Theme Colors -->
    <style id="theme-colors">
    :root {
        --color-black: <?php echo getSetting('color_primary_text') ?: '#1A1A1A'; ?>;
        --color-black-soft: <?php echo getSetting('color_secondary_text') ?: '#2C2C2C'; ?>;
        --color-grey-dark: <?php echo getSetting('color_muted_text') ?: '#666666'; ?>;
        --color-grey-medium: <?php echo getSetting('color_grey_medium') ?: '#999999'; ?>;
        --color-white: <?php echo getSetting('color_white_text') ?: '#FFFFFF'; ?>;
        --color-gold: <?php echo getSetting('color_gold') ?: '#C9A96E'; ?>;
        --color-gold-light: <?php echo getSetting('color_gold_light') ?: '#DFC392'; ?>;
        --color-gold-dark: <?php echo getSetting('color_gold_dark') ?: '#A8884A'; ?>;
        --color-linen: <?php echo getSetting('color_linen_bg') ?: '#F5F0E8'; ?>;
        --color-grey-light: <?php echo getSetting('color_grey_light_bg') ?: '#F8F8F8'; ?>;
        --color-grey: <?php echo getSetting('color_grey_border') ?: '#E5E5E5'; ?>;
        --color-success: <?php echo getSetting('color_success') ?: '#2E7D32'; ?>;
        --color-error: <?php echo getSetting('color_error') ?: '#C62828'; ?>;
        --color-btn-bg: <?php echo getSetting('color_btn_bg') ?: '#1A1A1A'; ?>;
        --color-btn-text: <?php echo getSetting('color_btn_text') ?: '#FFFFFF'; ?>;
        --color-btn-hover-bg: <?php echo getSetting('color_btn_hover_bg') ?: '#C9A96E'; ?>;
        --color-btn-hover-text: <?php echo getSetting('color_btn_hover_text') ?: '#FFFFFF'; ?>;
        --color-footer-bg: <?php echo getSetting('color_footer_bg') ?: '#1A1A1A'; ?>;
        --color-footer-text: <?php echo getSetting('color_footer_text') ?: '#FFFFFF'; ?>;
        --color-footer-muted: <?php echo getSetting('color_footer_muted') ?: '#999999'; ?>;
    }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo ASSETS_PATH; ?>images/favicon.svg">
</head>
<body>

<!-- ======== Announcement Bar ======== -->
<div class="announcement-bar">
    <div class="container-fluid text-center">
        <p class="mb-0 announcement-text">
            <span>Free shipping on orders over <?php echo formatPrice(SITE_SHIPPING_FREE_MIN); ?></span>
            <span class="separator">|</span>
            <span>Complimentary returns within 30 days</span>
            <span class="separator">|</span>
            <span>Buy Better, Buy Less.</span>
        </p>
    </div>
    <button class="announcement-close" aria-label="Close announcement">
        <i class="bi bi-x"></i>
    </button>
</div>

<!-- ======== Sticky Header / Navigation ======== -->
<header id="site-header" class="site-header">
    <nav class="navbar navbar-expand-xl">
        <div class="container-fluid">
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <span class="hamburger-box">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <span class="brand-logo">LINO</span>
                <span class="brand-logo-light">UNION</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="shop.php" data-bs-toggle="dropdown">
                            Shop
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="shop.php">All Collections</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="men.php">Men</a></li>
                            <li><a class="dropdown-item" href="women.php">Women</a></li>
                            <li><a class="dropdown-item" href="kids.php">Kids</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="shop.php?filter=new">New Arrivals</a></li>
                            <li><a class="dropdown-item" href="shop.php?filter=best">Best Sellers</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="men.php">Men</a></li>
                    <li class="nav-item"><a class="nav-link" href="women.php">Women</a></li>
                    <li class="nav-item"><a class="nav-link" href="kids.php">Kids</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">Our Story</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>

            <!-- Header Actions -->
            <div class="header-actions d-flex align-items-center">
                <!-- Search Toggle -->
                <button class="action-btn search-toggle" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>

                <!-- User / Login -->
                <a href="login.php" class="action-btn" aria-label="Account">
                    <i class="bi bi-person"></i>
                </a>

                <!-- Wishlist -->
                <a href="wishlist.php" class="action-btn position-relative" aria-label="Wishlist">
                    <i class="bi bi-heart"></i>
                    <span class="badge-count wishlist-count" id="wishlistCount"><?php echo getWishlistCount(); ?></span>
                </a>

                <!-- Cart -->
                <a href="cart.php" class="action-btn position-relative" aria-label="Cart">
                    <i class="bi bi-bag"></i>
                    <span class="badge-count cart-count" id="cartCount"><?php echo getCartCount(); ?></span>
                </a>
            </div>
        </div>
    </nav>
</header>

<!-- ======== Offcanvas Mobile Menu ======== -->
<div class="offcanvas offcanvas-start mobile-menu" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <a class="navbar-brand" href="index.php">
            <span class="brand-logo">LINO</span>
            <span class="brand-logo-light">UNION</span>
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="mobile-nav">
            <li class="mobile-nav-item"><a href="index.php" class="mobile-nav-link">Home</a></li>
            <li class="mobile-nav-item"><a href="shop.php" class="mobile-nav-link">All Shop</a></li>
            <li class="mobile-nav-item"><a href="men.php" class="mobile-nav-link">Men</a></li>
            <li class="mobile-nav-item"><a href="women.php" class="mobile-nav-link">Women</a></li>
            <li class="mobile-nav-item"><a href="kids.php" class="mobile-nav-link">Kids</a></li>
            <li class="mobile-nav-item"><a href="about.php" class="mobile-nav-link">Our Story</a></li>
            <li class="mobile-nav-item"><a href="contact.php" class="mobile-nav-link">Contact</a></li>
        </ul>
        <div class="mobile-menu-footer mt-4">
            <a href="login.php" class="btn btn-outline-dark w-100 mb-2">Sign In</a>
            <a href="register.php" class="btn btn-dark w-100">Create Account</a>
            <div class="mobile-socials mt-4">
                <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank"><i class="bi bi-instagram"></i></a>
                <a href="<?php echo SOCIAL_PINTEREST; ?>" target="_blank"><i class="bi bi-pinterest"></i></a>
                <a href="<?php echo SOCIAL_TWITTER; ?>" target="_blank"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- ======== Search Overlay ======== -->
<div class="search-overlay" id="searchOverlay">
    <div class="search-overlay-content">
        <button class="search-overlay-close" aria-label="Close search">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form class="search-form" action="shop.php" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control search-input" placeholder="Search for linen essentials..." autocomplete="off" id="searchInput">
                            <button class="btn btn-dark search-submit" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="search-suggestions" id="searchSuggestions">
                        <p class="suggestions-label">Quick Links</p>
                        <div class="suggestions-tags">
                            <a href="shop.php?category=men" class="suggestion-tag">Men's Linen</a>
                            <a href="shop.php?category=women" class="suggestion-tag">Women's Linen</a>
                            <a href="shop.php?category=kids" class="suggestion-tag">Kids' Linen</a>
                            <a href="shop.php?filter=new" class="suggestion-tag">New Arrivals</a>
                            <a href="shop.php?filter=best" class="suggestion-tag">Best Sellers</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<main>
