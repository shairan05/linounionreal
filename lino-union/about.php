<?php
require_once __DIR__ . '/includes/config.php';
$meta_title = 'Our Story | LINO UNION';
$meta_description = 'Discover the LINO UNION story. Premium linen clothing crafted with purpose. Buy Better, Buy Less.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ======== About Hero ======== -->
<section class="about-hero">
    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=1920&h=800&fit=crop" alt="LINO UNION linen collection" loading="lazy">
    <div class="container">
        <div class="about-hero-content" data-aos="fade-up">
            <span class="section-subtitle" style="color:var(--color-gold);">Our Story</span>
            <h1 class="display-heading" style="color:var(--color-black);">Crafted for<br>a Conscious Wardrobe</h1>
            <p class="lead text-muted mt-3" style="max-width:600px;">LINO UNION was born from a simple belief: that the clothes we wear should be made to last, crafted with care, and chosen with intention.</p>
        </div>
    </div>
</section>

<!-- ======== Brand Philosophy ======== -->
<section class="about-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1596900779747-33a5d6c3f3e0?w=800&h=1000&fit=crop" alt="Linen fabric detail" class="img-fluid" loading="lazy">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-subtitle">Our Philosophy</span>
                <h2 class="section-title">Buy Better, Buy Less.</h2>
                <p class="text-muted lead" style="font-size:1.05rem;line-height:1.8;">In a world of fast fashion, we choose a different path. Every piece in our collection is thoughtfully designed, meticulously crafted from European flax, and built to transcend seasons.</p>
                <p class="text-muted" style="line-height:1.8;">We believe that buying better means choosing quality over quantity. It means investing in pieces that feel as good as they look, that last longer than a single season, and that respect both the people who make them and the planet we share.</p>
                <div class="row g-4 mt-4">
                    <div class="col-sm-4">
                        <h3 class="display-6" style="font-family:var(--font-secondary);color:var(--color-gold);">100%</h3>
                        <p class="small text-muted">European Flax Linen</p>
                    </div>
                    <div class="col-sm-4">
                        <h3 class="display-6" style="font-family:var(--font-secondary);color:var(--color-gold);">5K+</h3>
                        <p class="small text-muted">Happy Customers</p>
                    </div>
                    <div class="col-sm-4">
                        <h3 class="display-6" style="font-family:var(--font-secondary);color:var(--color-gold);">30</h3>
                        <p class="small text-muted">Day Returns</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======== Our Values ======== -->
<section class="about-section bg-grey-light">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-subtitle">What We Stand For</span>
            <h2 class="section-title">Our Values</h2>
        </div>

        <div class="about-values">
            <div class="value-card" data-aos="fade-up">
                <div class="value-icon"><i class="bi bi-tree"></i></div>
                <h3 class="value-title">Sustainability</h3>
                <p class="value-text">Linen is one of the most sustainable natural fibers. It requires minimal water, no pesticides, and every part of the flax plant is used. We're committed to keeping it that way.</p>
            </div>
            <div class="value-card" data-aos="fade-up" data-aos-delay="100">
                <div class="value-icon"><i class="bi bi-hand-index-thumb"></i></div>
                <h3 class="value-title">Craftsmanship</h3>
                <p class="value-text">Every seam, every stitch, every detail matters. We partner with family-run mills in Europe that have perfected the art of linen weaving over generations.</p>
            </div>
            <div class="value-card" data-aos="fade-up" data-aos-delay="200">
                <div class="value-icon"><i class="bi bi-infinity"></i></div>
                <h3 class="value-title">Timelessness</h3>
                <p class="value-text">We design for permanence, not trends. Our pieces are meant to be worn, loved, and passed down. Because the most sustainable garment is the one you never throw away.</p>
            </div>
        </div>
    </div>
</section>

<!-- ======== Our Process ======== -->
<section class="about-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&h=1000&fit=crop" alt="Linen production" class="img-fluid" loading="lazy">
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <span class="section-subtitle">Our Process</span>
                <h2 class="section-title">From Field to Wardrobe</h2>
                <p class="text-muted" style="line-height:1.8;">Our journey begins in the flax fields of Normandy, France and Belgium, where the cool, temperate climate produces the world's finest linen fibers. We work directly with master weavers who transform these fibers into our signature fabrics — soft, breathable, and beautifully textured.</p>
                <p class="text-muted" style="line-height:1.8;">Each design is then brought to life in carefully selected ateliers where skilled artisans cut, sew, and finish every garment by hand. The result? Pieces that feel as though they've been in your wardrobe for years from the very first wear.</p>
            </div>
        </div>
    </div>
</section>

<!-- ======== CTA ======== -->
<section class="promo-banner">
    <img class="promo-banner-image" src="https://images.unsplash.com/photo-1558769132-cb1c458f7524?w=1920&h=800&fit=crop" alt="Linen lifestyle" loading="lazy">
    <div class="container">
        <div class="promo-banner-content" data-aos="fade-up">
            <h2 class="promo-title">Ready to Buy Better?</h2>
            <p class="promo-text">Explore our curated collection of premium linen essentials. Thoughtfully made, designed to last.</p>
            <a href="shop.php" class="btn btn-dark btn-lg">Shop the Collection</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
