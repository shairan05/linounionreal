</main>

<!-- ======== Newsletter Section ======== -->
<section class="newsletter-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-6">
                <span class="section-subtitle">Stay Connected</span>
                <h2 class="newsletter-title">Join the LINO Community</h2>
                <p class="newsletter-text">Subscribe for exclusive access to new collections, early sale previews, and 10% off your first order.</p>
                <form class="newsletter-form" id="newsletterForm" method="POST" action="newsletter.php">
                    <div class="input-group">
                        <input type="email" name="email" class="form-control newsletter-input" placeholder="Enter your email address" required>
                        <button class="btn btn-dark newsletter-btn" type="submit">Subscribe</button>
                    </div>
                    <div class="form-check mt-3 text-start">
                        <input class="form-check-input" type="checkbox" id="newsletterConsent" required>
                        <label class="form-check-label" for="newsletterConsent">
                            I agree to receive marketing emails and accept the <a href="#" class="text-link">Privacy Policy</a>.
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ======== Instagram Gallery ======== -->
<section class="instagram-gallery">
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-4 col-md-2">
                <a href="#" class="instagram-item" target="_blank">
                    <img src="https://images.unsplash.com/photo-1596900779747-33a5d6c3f3e0?w=400&h=400&fit=crop" alt="LINO UNION Instagram" loading="lazy">
                    <div class="instagram-overlay">
                        <i class="bi bi-instagram"></i>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="#" class="instagram-item" target="_blank">
                    <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&h=400&fit=crop" alt="LINO UNION Instagram" loading="lazy">
                    <div class="instagram-overlay">
                        <i class="bi bi-instagram"></i>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="#" class="instagram-item" target="_blank">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=400&h=400&fit=crop" alt="LINO UNION Instagram" loading="lazy">
                    <div class="instagram-overlay">
                        <i class="bi bi-instagram"></i>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="#" class="instagram-item" target="_blank">
                    <img src="https://images.unsplash.com/photo-1564594736624-def7a10ab047?w=400&h=400&fit=crop" alt="LINO UNION Instagram" loading="lazy">
                    <div class="instagram-overlay">
                        <i class="bi bi-instagram"></i>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="#" class="instagram-item" target="_blank">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&h=400&fit=crop" alt="LINO UNION Instagram" loading="lazy">
                    <div class="instagram-overlay">
                        <i class="bi bi-instagram"></i>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="#" class="instagram-item" target="_blank">
                    <img src="https://images.unsplash.com/photo-1558769132-cb1c458f7524?w=400&h=400&fit=crop" alt="LINO UNION Instagram" loading="lazy">
                    <div class="instagram-overlay">
                        <i class="bi bi-instagram"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="instagram-follow text-center">
            <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" class="btn btn-outline-dark btn-lg">
                <i class="bi bi-instagram me-2"></i>Follow @linounion
            </a>
        </div>
    </div>
</section>

<!-- ======== Footer ======== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand -->
            <div class="footer-brand">
                <a href="index.php" class="footer-logo">
                    <span class="brand-logo">LINO</span>
                    <span class="brand-logo-light">UNION</span>
                </a>
                <p class="footer-tagline"><?php echo getSetting('footer_tagline', 'Buy Better, Buy Less.<br>Premium linen essentials crafted for a conscious wardrobe.'); ?></p>
                <div class="footer-socials">
                    <a href="<?php echo getSetting('social_instagram', SOCIAL_INSTAGRAM); ?>" target="_blank" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="<?php echo getSetting('social_pinterest', SOCIAL_PINTEREST); ?>" target="_blank" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a>
                    <a href="<?php echo getSetting('social_twitter', SOCIAL_TWITTER); ?>" target="_blank" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="<?php echo getSetting('social_facebook', SOCIAL_FACEBOOK); ?>" target="_blank" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                </div>
            </div>

            <!-- Shop -->
            <div class="footer-col">
                <h4 class="footer-col-title"><?php echo getSetting('footer_shop_title', 'Shop'); ?></h4>
                <ul class="footer-links">
                    <li><a href="men.php">Men</a></li>
                    <li><a href="women.php">Women</a></li>
                    <li><a href="kids.php">Kids</a></li>
                    <li><a href="shop.php">All Collections</a></li>
                    <li><a href="shop.php?filter=new">New Arrivals</a></li>
                    <li><a href="shop.php?filter=best">Best Sellers</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="footer-col">
                <h4 class="footer-col-title"><?php echo getSetting('footer_service_title', 'Customer Service'); ?></h4>
                <ul class="footer-links">
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">Shipping & Delivery</a></li>
                    <li><a href="#">Returns & Exchanges</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Track Order</a></li>
                </ul>
            </div>

            <!-- About -->
            <div class="footer-col">
                <h4 class="footer-col-title"><?php echo getSetting('footer_about_title', 'About'); ?></h4>
                <ul class="footer-links">
                    <li><a href="about.php">Our Story</a></li>
                    <li><a href="#">Sustainability</a></li>
                    <li><a href="#">Care Guide</a></li>
                    <li><a href="#">Materials</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-col">
                <h4 class="footer-col-title"><?php echo getSetting('footer_contact_title', 'Get in Touch'); ?></h4>
                <ul class="footer-contact">
                    <li><i class="bi bi-geo-alt"></i> <?php echo getSetting('site_address', SITE_ADDRESS); ?></li>
                    <li><i class="bi bi-envelope"></i> <a href="mailto:<?php echo getSetting('site_email', SITE_EMAIL); ?>"><?php echo getSetting('site_email', SITE_EMAIL); ?></a></li>
                    <li><i class="bi bi-telephone"></i> <a href="tel:<?php echo getSetting('site_phone', SITE_PHONE); ?>"><?php echo getSetting('site_phone', SITE_PHONE); ?></a></li>
                </ul>
                <div class="payment-methods mt-3">
                    <i class="bi bi-credit-card-2-front"></i>
                    <i class="bi bi-paypal"></i>
                    <i class="bi bi-apple"></i>
                    <span class="payment-text">Visa · MC · Amex</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright mb-0">&copy; <?php echo date('Y'); ?> <?php echo getSetting('site_name', SITE_NAME); ?>. <?php echo getSetting('footer_copyright', 'All rights reserved. Premium linen, thoughtfully made.'); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-bottom-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ======== Back to Top ======== -->
<button id="backToTop" class="back-to-top" aria-label="Back to top">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- ======== Toast Container ======== -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="<?php echo ASSETS_PATH; ?>js/main.js"></script>
</body>
</html>
