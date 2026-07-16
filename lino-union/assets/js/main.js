/* ================================================
   LINO UNION – Main JavaScript
   ================================================ */

'use strict';

// ---- Page Loader ----
function hideLoader() {
    const loader = document.querySelector('.page-loader');
    if (!loader || loader.classList.contains('loaded')) return;
    loader.classList.add('loaded');
    setTimeout(() => { if (loader.parentNode) loader.remove(); }, 600);
}

// Hide loader when window fully loads (all images, fonts, etc.)
window.addEventListener('load', hideLoader);

// Safety net: hide loader after 8 seconds no matter what
setTimeout(hideLoader, 8000);

// ---- Initialize AOS ----
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 80,
        easing: 'ease-out-cubic',
    });
});

// ---- ============================
// HEADER BEHAVIOR
// ---- ============================

(function() {
    const header = document.getElementById('site-header');
    if (!header) return;

    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        // Add shadow on scroll
        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Hide/show header on scroll direction
        if (currentScroll > lastScroll && currentScroll > 200) {
            header.classList.add('header-hidden');
        } else {
            header.classList.remove('header-hidden');
        }

        lastScroll = currentScroll;
    }, { passive: true });
})();

// ---- ============================
// ANNOUNCEMENT BAR
// ---- ============================

(function() {
    const closeBtn = document.querySelector('.announcement-close');
    const bar = document.querySelector('.announcement-bar');

    if (closeBtn && bar) {
        if (sessionStorage.getItem('announcementClosed')) {
            bar.classList.add('hidden');
            bar.style.display = 'none';
        }

        closeBtn.addEventListener('click', () => {
            bar.classList.add('hidden');
            sessionStorage.setItem('announcementClosed', 'true');
            setTimeout(() => { bar.style.display = 'none'; }, 300);
        });
    }
})();

// ---- ============================
// SEARCH OVERLAY
// ---- ============================

(function() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.querySelector('.search-overlay-close');
    const searchInput = document.getElementById('searchInput');

    if (!searchToggle || !searchOverlay) return;

    function openSearch() {
        searchOverlay.classList.add('active');
        document.body.classList.add('search-open');
        setTimeout(() => { if (searchInput) searchInput.focus(); }, 300);
    }

    function closeSearch() {
        searchOverlay.classList.remove('active');
        document.body.classList.remove('search-open');
    }

    searchToggle.addEventListener('click', openSearch);
    if (searchClose) searchClose.addEventListener('click', closeSearch);

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            closeSearch();
        }
    });

    // Close on overlay click
    searchOverlay.addEventListener('click', (e) => {
        if (e.target === searchOverlay) closeSearch();
    });
})();

// ---- ============================
// HERO SLIDER
// ---- ============================

(function() {
    const slider = document.querySelector('.hero-section');
    if (!slider) return;

    const slides = slider.querySelectorAll('.hero-slide');
    const indicators = slider.querySelectorAll('.hero-indicator');
    let currentSlide = 0;
    let slideInterval;
    const AUTOPLAY_DELAY = 6000;

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        indicators.forEach(i => i.classList.remove('active'));

        slides[index].classList.add('active');
        indicators[index].classList.add('active');

        // Reset progress bar animation
        const progressBar = indicators[index].querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '0';
            // Force reflow
            progressBar.offsetHeight;
            progressBar.style.transition = 'width ' + (AUTOPLAY_DELAY / 1000) + 's linear';
            progressBar.style.width = '100%';
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function startAutoplay() {
        stopAutoplay();
        slideInterval = setInterval(nextSlide, AUTOPLAY_DELAY);
        // Start first progress bar
        const firstProgress = indicators[0]?.querySelector('.progress-bar');
        if (firstProgress) {
            firstProgress.style.transition = 'none';
            firstProgress.style.width = '0';
            firstProgress.offsetHeight;
            firstProgress.style.transition = 'width ' + (AUTOPLAY_DELAY / 1000) + 's linear';
            firstProgress.style.width = '100%';
        }
    }

    function stopAutoplay() {
        if (slideInterval) {
            clearInterval(slideInterval);
            slideInterval = null;
        }
    }

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
            startAutoplay();
        });

        indicator.addEventListener('mouseenter', stopAutoplay);
        indicator.addEventListener('mouseleave', startAutoplay);
    });

    // Pause on hover
    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    // Initialize
    showSlide(0);
    startAutoplay();
})();

// ---- ============================
// PRODUCT IMAGE ZOOM
// ---- ============================

(function() {
    const mainImage = document.querySelector('.product-main-image');
    if (!mainImage) return;

    mainImage.addEventListener('mousemove', (e) => {
        const { left, top, width, height } = mainImage.getBoundingClientRect();
        const x = ((e.clientX - left) / width) * 100;
        const y = ((e.clientY - top) / height) * 100;
        const img = mainImage.querySelector('img');
        if (img) {
            img.style.transformOrigin = `${x}% ${y}%`;
        }
    });

    mainImage.addEventListener('mouseenter', () => {
        mainImage.classList.add('zoomed');
    });

    mainImage.addEventListener('mouseleave', () => {
        mainImage.classList.remove('zoomed');
    });
})();

// ---- ============================
// PRODUCT GALLERY THUMBNAILS
// ---- ============================

(function() {
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    const mainImage = document.querySelector('.product-main-image img');
    if (!thumbnails.length || !mainImage) return;

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            thumbnails.forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            const newSrc = thumb.querySelector('img')?.getAttribute('src');
            if (newSrc) {
                mainImage.src = newSrc;
                mainImage.parentElement.classList.remove('zoomed');
            }
        });
    });
})();

// ---- ============================
// PRODUCT CARD IMAGE CYCLING
// ---- ============================

(function() {
    document.addEventListener('click', function(e) {
        const arrow = e.target.closest('.product-image-arrow');
        if (!arrow) return;

        const imagesAttr = arrow.getAttribute('data-images');
        if (!imagesAttr) return;

        let images;
        try {
            images = JSON.parse(imagesAttr);
        } catch (err) {
            return;
        }

        if (!images || images.length < 2) return;

        let current = parseInt(arrow.getAttribute('data-current')) || 0;
        const next = (current + 1) % images.length;

        // Find the product card image
        const card = arrow.closest('.product-card-image');
        if (!card) return;

        const img = card.querySelector('img');
        if (!img) return;

        // Store current src as fallback
        if (!arrow.hasAttribute('data-original')) {
            arrow.setAttribute('data-original', img.src);
        }

        // Fade out, swap, fade in
        img.style.transition = 'opacity 0.25s ease';
        img.style.opacity = '0';

        setTimeout(function() {
            img.src = images[next];
            img.style.opacity = '1';
            arrow.setAttribute('data-current', next);

            // Add a quick scale pulse to the arrow
            arrow.style.transition = 'transform 0.15s ease';
            arrow.style.transform = 'scale(0.9)';
            setTimeout(function() {
                arrow.style.transform = 'scale(1)';
            }, 150);
        }, 250);
    });
})();

// ---- ============================
// PRODUCT VARIANTS (Size & Color)
// ---- ============================

(function() {
    const sizeOptions = document.querySelectorAll('.size-option');
    const colorOptions = document.querySelectorAll('.color-option');

    sizeOptions.forEach(option => {
        option.addEventListener('click', () => {
            if (option.classList.contains('disabled')) return;
            sizeOptions.forEach(o => o.classList.remove('active'));
            option.classList.add('active');
            // Update selected variant label
            const label = option.closest('.variant-section')?.querySelector('.variant-selected');
            if (label) label.textContent = option.textContent.trim();
        });
    });

    colorOptions.forEach(option => {
        option.addEventListener('click', () => {
            colorOptions.forEach(o => o.classList.remove('active'));
            option.classList.add('active');
            const label = option.closest('.variant-section')?.querySelector('.variant-selected');
            if (label) label.textContent = option.getAttribute('data-color') || '';
        });
    });
})();

// ---- ============================
// QUANTITY SELECTOR
// ---- ============================

(function() {
    document.querySelectorAll('.quantity-selector').forEach(selector => {
        const input = selector.querySelector('.quantity-input');
        const minus = selector.querySelector('.quantity-minus');
        const plus = selector.querySelector('.quantity-plus');

        if (!input || !minus || !plus) return;

        minus.addEventListener('click', () => {
            const current = parseInt(input.value) || 1;
            if (current > 1) input.value = current - 1;
            triggerInputChange(input);
        });

        plus.addEventListener('click', () => {
            const current = parseInt(input.value) || 1;
            const max = parseInt(input.getAttribute('max')) || 99;
            if (current < max) input.value = current + 1;
            triggerInputChange(input);
        });

        input.addEventListener('change', () => {
            let val = parseInt(input.value) || 1;
            const max = parseInt(input.getAttribute('max')) || 99;
            if (val < 1) val = 1;
            if (val > max) val = max;
            input.value = val;
            triggerInputChange(input);
        });
    });

    function triggerInputChange(input) {
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);
    }
})();

// ---- ============================
// ADD TO CART (AJAX)
// ---- ============================

(function() {
    document.addEventListener('click', function(e) {
        const addBtn = e.target.closest('.add-to-cart-btn');
        if (!addBtn) return;

        const productId = addBtn.getAttribute('data-product-id');
        const qty = addBtn.closest('.product-actions')?.querySelector('.quantity-input')?.value || 1;
        const size = document.querySelector('.size-option.active')?.textContent?.trim() || '';
        const color = document.querySelector('.color-option.active')?.getAttribute('data-color') || '';

        if (!productId) return;

        // Disable button & show feedback
        addBtn.disabled = true;
        const originalText = addBtn.innerHTML;
        addBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';

        fetch('add-to-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&quantity=${qty}&size=${encodeURIComponent(size)}&color=${encodeURIComponent(color)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                const cartCount = document.getElementById('cartCount');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                    cartCount.classList.remove('pulse');
                    void cartCount.offsetWidth;
                    cartCount.classList.add('pulse');
                }
                showToast('Added to cart!', 'success');
            }
        })
        .catch(() => {
            showToast('Error adding to cart. Please try again.', 'error');
        })
        .finally(() => {
            addBtn.disabled = false;
            addBtn.innerHTML = originalText;
        });
    });
})();

// ---- ============================
// WISHLIST TOGGLE
// ---- ============================

(function() {
    document.addEventListener('click', function(e) {
        const wishlistBtn = e.target.closest('.wishlist-toggle');
        if (!wishlistBtn) return;

        const productId = wishlistBtn.getAttribute('data-product-id');
        if (!productId) return;

        wishlistBtn.disabled = true;

        fetch('wishlist-toggle.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                wishlistBtn.classList.toggle('active', data.added);
                const icon = wishlistBtn.querySelector('i');
                if (icon) {
                    icon.className = data.added ? 'bi bi-heart-fill' : 'bi bi-heart';
                }
                // Update wishlist count
                const wlCount = document.getElementById('wishlistCount');
                if (wlCount) wlCount.textContent = data.wishlistCount;

                showToast(data.added ? 'Added to wishlist!' : 'Removed from wishlist', 'success');
            }
        })
        .catch(() => {
            showToast('Error updating wishlist.', 'error');
        })
        .finally(() => {
            wishlistBtn.disabled = false;
        });
    });
})();

// ---- ============================
// CART QUANTITY UPDATE
// ---- ============================

(function() {
    document.querySelectorAll('.cart-quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.getAttribute('data-item-id');
            const quantity = this.value;

            fetch('update-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${itemId}&quantity=${quantity}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update totals
                    const totals = document.querySelectorAll('.cart-subtotal, .cart-total');
                    totals.forEach(el => {
                        if (el.classList.contains('cart-total')) {
                            el.textContent = data.totalFormatted;
                        } else {
                            el.textContent = data.subtotalFormatted;
                        }
                    });
                    const cartCount = document.getElementById('cartCount');
                    if (cartCount) cartCount.textContent = data.cartCount;
                }
            })
            .catch(() => {});
        });
    });
})();

// ---- ============================
// CART REMOVE ITEM
// ---- ============================

(function() {
    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.cart-item-remove');
        if (!removeBtn) return;

        const itemId = removeBtn.getAttribute('data-item-id');
        if (!itemId || !confirm('Remove this item from your cart?')) return;

        fetch('remove-from-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_id=${itemId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const cartItem = removeBtn.closest('.cart-item');
                if (cartItem) {
                    cartItem.style.transition = 'all 0.3s ease';
                    cartItem.style.opacity = '0';
                    cartItem.style.transform = 'translateX(50px)';
                    setTimeout(() => cartItem.remove(), 300);
                }
                // Update counters
                const cartCount = document.getElementById('cartCount');
                if (cartCount) cartCount.textContent = data.cartCount;

                // Reload if cart is empty
                if (data.cartEmpty) {
                    setTimeout(() => location.reload(), 400);
                }
            }
        })
        .catch(() => {
            showToast('Error removing item.', 'error');
        });
    });
})();

// ---- ============================
// NEWSLETTER FORM
// ---- ============================

(function() {
    const newsletterForm = document.getElementById('newsletterForm');
    if (!newsletterForm) return;

    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]')?.value;
        if (!email) return;

        const submitBtn = this.querySelector('.newsletter-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Subscribing...';

        fetch('newsletter.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                newsletterForm.innerHTML = `
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                        <h4 class="mt-3">You're subscribed!</h4>
                        <p class="text-muted">Welcome to the LINO UNION community.</p>
                    </div>
                `;
            } else {
                showToast(data.message || 'Error subscribing.', 'error');
            }
        })
        .catch(() => {
            showToast('Error subscribing. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Subscribe';
        });
    });
})();

// ---- ============================
// BACK TO TOP
// ---- ============================

(function() {
    const backBtn = document.getElementById('backToTop');
    if (!backBtn) return;

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 400) {
            backBtn.classList.add('visible');
        } else {
            backBtn.classList.remove('visible');
        }
    }, { passive: true });

    backBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();

// ---- ============================
// TOAST NOTIFICATIONS
// ---- ============================

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
    const toast = document.createElement('div');
    toast.className = `custom-toast toast ${type} align-items-center border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="toast-body">
            <i class="bi ${icon}"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    container.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

// ---- ============================
// PRICE FORMAT HELPER
// ---- ============================

function formatPrice(price) {
    return '$' + parseFloat(price).toFixed(2);
}

// ---- ============================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ---- ============================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// ---- ============================
// LAZY LOADING FOR IMAGES
// ---- ============================

(function() {
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            img.src = img.getAttribute('src');
        });
    } else {
        // Fallback
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.getAttribute('src');
                        observer.unobserve(img);
                    }
                });
            }, { rootMargin: '200px' });

            lazyImages.forEach(img => observer.observe(img));
        }
    }
})();

// ---- ============================
// SHOP SORTING
// ---- ============================

(function() {
    const sortSelect = document.getElementById('sortSelect');
    const productGrid = document.getElementById('productGrid');
    if (!sortSelect || !productGrid) return;

    sortSelect.addEventListener('change', function() {
        const sortBy = this.value;
        const products = Array.from(productGrid.querySelectorAll('.product-item'));

        products.sort((a, b) => {
            switch (sortBy) {
                case 'price-low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'newest':
                default:
                    return 0;
            }
        });

        products.forEach((product, index) => {
            product.style.opacity = '0';
            product.style.transform = 'translateY(20px)';
        });

        setTimeout(() => {
            products.forEach(product => productGrid.appendChild(product));
            requestAnimationFrame(() => {
                products.forEach((product, index) => {
                    setTimeout(() => {
                        product.style.transition = 'all 0.4s ease';
                        product.style.opacity = '1';
                        product.style.transform = 'translateY(0)';
                    }, index * 50);
                });
            });
        }, 200);
    });
})();

// ---- ============================
// ADMIN CHART
// ---- ============================

(function() {
    console.log('LINO UNION — Premium Linen Essentials');
    console.log('Buy Better, Buy Less.');
})();

console.log('%c LINO UNION ', 'background: #1a1a1a; color: #c9a96e; font-size: 16px; font-weight: bold; padding: 8px 12px; border-radius: 4px;');
console.log('%c Buy Better, Buy Less. ', 'color: #666; font-size: 12px; font-style: italic;');
