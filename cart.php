<?php
require_once 'cms/db_connect.php';
require_once 'cms/shipping-tax-functions.php';

// Get shipping and tax settings
$shipping_tax_settings = getShippingTaxSettingsJSON();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - FlipAvenue</title>
    <meta name="description" content="Review your selected architectural products and proceed to checkout.">
    <meta name="keywords" content="shopping cart, architecture, products, checkout">
    <meta name="author" content="FlipAvenue">

    <!-- favicon -->
    <link rel="shortcut icon" href="assets/img/home1/fav.png">

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- styles -->
    <link rel="stylesheet" href="common/assets/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/all.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/animate.css">
    <link rel="stylesheet" href="common/assets/css/lib/swiper8.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/lity.css">
    <link rel="stylesheet" href="common/assets/css/lib/themify-icons.css">
    <link rel="stylesheet" href="common/assets/css/lib/line-awesome.css">
    <link rel="stylesheet" href="common/assets/css/lib/jquery.fancybox.css">

    <!-- common style -->
    <link rel="stylesheet" href="common/assets/css/common_style.css">

    <!-- home style -->
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="home-style1">

    <!-- ====== Start Loading ====== -->
    <div class="loader-wrap">
        <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
            <path id="svg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
        </svg>

        <div class="loader-wrap-heading">
            <div class="load-text">
                <span>L</span>
                <span>o</span>
                <span>a</span>
                <span>d</span>
                <span>i</span>
                <span>n</span>
                <span>g</span>
            </div>
        </div>
    </div>

    <!--  start side_menu  -->
    <div class="side_menu4_overlay"></div>
    <div class="side_menu4_overlay2"></div>
    <div class="side_menu_style4">
        <div class="content">
            <div class="main_links">
                <ul>
                    <li> <a href="index.php" class="main_link"> home </a> </li>
                    <li><a href="about.html" class="main_link"> about us </a></li>
                    <li> <a href="portfolio.php" class="main_link"> projects </a> </li>
                    <li> <a href="shop.php" class="main_link"> shop </a> </li>
                    <li> <a href="contact.php" class="main_link"> contact </a> </li>
                </ul>
            </div>
        </div>
        <img src="assets/img/home1/chat_pat2.png" alt="" class="side_shape">
        <img src="assets/img/home1/chat_pat2.png" alt="" class="side_shape2">
        <span class="clss"> <i class="la  la-times"></i> </span>
    </div>
    <!--  End side_menu  -->

    <div class="smooth-scroll-content" id="scrollsmoother-container">

        <!--  Start navbar  -->
        <nav class="navbar navbar-expand-lg navbar-dark tc-navbar-style1 section-padding-x">
            <div class="container-fluid content">
                <a class="navbar-brand" href="#">
                    <img src="assets/img/home1/logo.png" alt="" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.html">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="portfolio.php">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                    </ul>
                    <div class="nav-side">
                        <a href="cart.php" class="icon ms-5 fsz-21 position-relative">
                            <span> <i class="la la-shopping-cart"></i> </span>
                            <span class="cart-badge badge bg-orange1 rounded-pill position-absolute" id="cartCount">0</span>
                        </a>
                        <a href="cms/login.php" class="butn border rounded-pill ms-3 hover-bg-orange1" target="_blank">
                            <span> <i class="la la-user me-2"></i> Login </span>
                        </a>
                        <a href="#" class="icon ms-3 side_menu_btn fsz-21">
                            <span> <i class="la la-grip-lines"></i> </span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!--  End navbar  -->

        
        <!--  Start page header  -->
        <header class="tc-header-style1 cart-header" style="min-height: 12vh; height: 12vh; max-height: 12vh;">
            <div class="img" style="min-height: 12vh !important; height: 12vh !important; max-height: 12vh !important;">
                <img src="assets/img/home1/head_slide1.jpg" alt="" class="img-cover">
            </div>
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start cart  -->
            <section class="tc-cart-style1 section-padding">
                <div class="container">
                    <div class="mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="fsz-45 mb-3 wow fadeInUp"> Your Cart </h2>
                                <p class="color-666 wow fadeInUp" data-wow-delay="0.2s">Review and manage your selected architectural products</p>
                            </div>
                            <div class="col-lg-6 mt-4 mt-lg-0">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="shop.php" class="butn border rounded-pill hover-bg-orange1">
                                        <span>Continue Shopping</span>
                                    </a>
                                    <button class="butn bg-orange1 text-white rounded-pill" id="clearCartBtn">
                                        <span>Clear Cart</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End cart  -->

            <!--  Start cart items  -->
            <section class="tc-cart-items section-padding pt-0">
                <div class="container">
                    <div class="row">
                        <!-- Cart Items -->
                        <div class="col-lg-8">
                            <div id="cartItems">
                                <!-- Cart items will be populated by JavaScript -->
                                <div class="empty-cart text-center py-5" id="emptyCart" style="display: none;">
                                    <i class="la la-shopping-cart fsz-80 color-ccc mb-4"></i>
                                    <h4 class="mb-3">Your cart is empty</h4>
                                    <p class="color-666 mb-4">Add some architectural products to get started!</p>
                                    <a href="shop.php" class="butn bg-orange1 text-white rounded-pill">
                                        <span>Start Shopping</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="col-lg-4">
                            <div class="cart-summary bg-light p-4 rounded">
                                <h5 class="mb-4">Order Summary</h5>
                                
                                <div class="summary-item d-flex justify-content-between mb-3">
                                    <span>Subtotal:</span>
                                    <span id="cartSubtotal">UGX 0</span>
                                </div>
                                
                                <div class="summary-item d-flex justify-content-between mb-3">
                                    <span>Shipping:</span>
                                    <span id="cartShipping">UGX 0</span>
                                </div>
                                
                                <div class="summary-item d-flex justify-content-between mb-3">
                                    <span>Tax:</span>
                                    <span id="cartTax">UGX 0</span>
                                </div>
                                
                                <hr>
                                
                                <div class="summary-item d-flex justify-content-between mb-4">
                                    <strong>Total:</strong>
                                    <strong id="cartTotal">UGX 0</strong>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="checkout.php" class="butn bg-orange1 text-white rounded-pill text-center" id="checkoutBtn">
                                        <span>Proceed to Checkout</span>
                                    </a>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <small class="color-666">
                                        <i class="la la-shield me-2"></i>
                                        Secure checkout with SSL encryption
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End cart items  -->

        </main>

        <!--  Start footer  -->
        <footer class="tc-footer-style1">
            <div class="container">
                <div class="top-content section-padding">
                    <div class="row gx-0">
                        <div class="col-lg-4">
                            <div class="info-side">
                                <div class="text fsz-24 color-333 lh-3 fw-600">
                                    We believe that architecture has the power to shape lives and uplift communities. Flip Avenue's philosophy is passion for innovation, sustainablity and timeless aesthetics
                                </div>
                                <div class="foot-social mt-50">
                                    <a href="https://twitter.com/flipavenueug" target="_blank" rel="noopener noreferrer"> <i class="fab fa-x-twitter"></i> </a>
                                    <a href="https://www.facebook.com/profile.php?id=61550707256066&mibextid=9R9pXO" target="_blank" rel="noopener noreferrer"> <i class="fab fa-facebook-f"></i> </a>
                                    <a href="https://www.instagram.com/flipavenueug?igsh=MWd1YTVwYjdkM3JnOQ%3D%3D&utm_source=qr" target="_blank" rel="noopener noreferrer"> <i class="fab fa-instagram"></i> </a>
                                    <a href="https://www.linkedin.com/company/flip-avenue" target="_blank" rel="noopener noreferrer"> <i class="fab fa-linkedin-in"></i> </a>
                                    <a href="#" target="_blank" rel="noopener noreferrer"> <i class="fab fa-youtube"></i> </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 offset-lg-2">
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Kampala, Uganda </h5>
                                <ul class="footer-links">
                                    <li> <a href="#"> Kataza Close, Bugolobi, Maria House, behind Airtel Building, Kampala, Uganda </a> </li>
                                    <li> <a href="#"> info@flipavenueltd.com </a> </li>
                                    <li> <a href="#"> +256 701380251 / 783370967 </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Other links </h5>
                                <ul class="footer-links">
                                    <li> <a href="shop.php"> Shop </a> </li>
                                    <li> <a href="portfolio.html"> Portfolio </a> </li>
                                    <li> <a href="blog.php"> Blog </a> </li>
                                    <li> <a href="#"> Videos </a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Important links </h5>
                                <ul class="footer-links">
                                    <li> <a href="careers.html"> Careers </a> </li>
                                    <li> <a href="contact.html"> Contact Us </a> </li>
                                    <li> <a href="#"> Help </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Legal </h5>
                                <ul class="footer-links">
                                    <li> <a href="#"> Term & Conditions </a> </li>
                                    <li> <a href="#"> Privacy Policy </a> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="foot">
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="fsz-13"> © 2025 FlipAvenue Limited. All Right Reserved </p>
                        </div>
                        <div class="col-lg-6">
                            <div class="foot-links mt-4 mt-lg-0">
                                <a href="index.php"> Home </a>
                                <a href="about.html"> About Us </a>
                                <a href="portfolio.html"> Projects </a>
                                <a href="shop.php"> Shop </a>
                                <a href="contact.html"> Contact </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--  End footer  -->

    </div>

    <!-- scripts -->
    <script src="common/assets/js/lib/jquery-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/jquery-migrate-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/wow.min.js"></script>
    <script src="common/assets/js/lib/swiper8-bundle.min.js"></script>
    <script src="common/assets/js/gsap_lib/gsap.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollTrigger.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollSmoother.min.js"></script>
    <script src="common/assets/js/gsap_lib/SplitText.min.js"></script>
    <script src="common/assets/js/lib/jquery.fancybox.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/jquery.counterup.js"></script>
    <script src="common/assets/js/lib/jquery.waypoints.min.js"></script>
    <script src="common/assets/js/lib/parallaxie.js"></script>
    <script src="common/assets/js/common_js.js"></script>
    <script src="assets/main.js"></script>

    <!-- Cart JavaScript -->
    <script>
        // Pass shipping and tax settings from PHP to JavaScript
        window.shippingTaxSettings = <?php echo $shipping_tax_settings; ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize WOW.js
            new WOW().init();

            // Cart functionality
            class ShoppingCart {
                constructor() {
                    this.cart = [];
                    this.loadCart();
                }

                async loadCart() {
                    try {
                        const response = await fetch('cart-ajax.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'action=get_cart&cache_buster=' + Date.now(),
                            cache: 'no-store',
                            credentials: 'same-origin'
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            this.cart = data.data.cart;
                            this.renderCart();
                            this.updateSummary();
                        }
                    } catch (error) {
                        console.error('Error loading cart:', error);
                    }
                }

                addItem(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.quantity += 1;
                    } else {
                        this.cart.push({...product, quantity: 1});
                    }
                    this.saveCart();
                    this.renderCart();
                    this.updateSummary();
                }

                removeItem(productId) {
                    this.cart = this.cart.filter(item => item.id !== productId);
                    this.saveCart();
                    this.renderCart();
                    this.updateSummary();
                }

                updateQuantity(productId, quantity) {
                    const item = this.cart.find(item => item.id === productId);
                    if (item) {
                        // Don't allow quantity to go below 1
                        if (quantity < 1) {
                            quantity = 1;
                        }
                        
                        // Show loading state
                        this.showQuantityLoading(productId, true);
                        
                        // Simulate AJAX call with a small delay for better UX
                        setTimeout(() => {
                            item.quantity = quantity;
                            this.saveCart();
                            this.renderCart();
                            this.updateSummary();
                            this.showQuantityLoading(productId, false);
                        }, 300);
                    }
                }

                async updateQuantityAjax(productId, quantity) {
                    if (quantity < 1) {
                        this.removeItemAjax(productId);
                        return;
                    }

                    // Show loading state on quantity controls
                    this.showQuantityLoading(productId, true);

                    try {
                        const response = await fetch('cart-ajax.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=update_quantity&product_id=${productId}&quantity=${quantity}`
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            // Update the specific item quantity immediately
                            const item = this.cart.find(item => item.id == productId);
                            if (item) {
                                item.quantity = quantity;
                            }
                            
                            // Update UI immediately without reloading
                            this.updateItemQuantity(productId, quantity);
                            this.updateSummary();
                            this.updateCartCount();
                            
                            this.showModal('Success', 'Quantity updated successfully!', 'success');
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error('Error updating quantity:', error);
                        this.showModal('Error', 'Error updating quantity. Please try again.', 'error');
                    } finally {
                        this.showQuantityLoading(productId, false);
                    }
                }

                async removeItemAjax(productId) {
                    // Show loading state
                    this.showQuantityLoading(productId, true);

                    try {
                        const response = await fetch('cart-ajax.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=remove_from_cart&product_id=${productId}`
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            // Remove item immediately from UI
                            this.cart = this.cart.filter(item => item.id != productId);
                            
                            // Update UI immediately
                            this.renderCart();
                            this.updateSummary();
                            this.updateCartCount();
                            
                            this.showModal('Success', 'Item removed from cart!', 'success');
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error('Error removing item:', error);
                        this.showModal('Error', 'Error removing item. Please try again.', 'error');
                    } finally {
                        this.showQuantityLoading(productId, false);
                    }
                }

                showButtonLoading(productId, isLoading) {
                    const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
                    if (!cartItem) return;

                    const buttons = cartItem.querySelectorAll('.btn');
                    const loadingSpinners = cartItem.querySelectorAll('.btn-loading');
                    const buttonTexts = cartItem.querySelectorAll('.btn-text');
                    const quantityInput = cartItem.querySelector('.quantity-input');

                    buttons.forEach(btn => {
                        if (isLoading) {
                            btn.disabled = true;
                            btn.style.pointerEvents = 'none';
                        } else {
                            btn.disabled = false;
                            btn.style.pointerEvents = 'auto';
                        }
                    });

                    loadingSpinners.forEach(spinner => {
                        if (isLoading) {
                            spinner.classList.remove('d-none');
                        } else {
                            spinner.classList.add('d-none');
                        }
                    });

                    buttonTexts.forEach(text => {
                        if (isLoading) {
                            text.style.opacity = '0.5';
                        } else {
                            text.style.opacity = '1';
                        }
                    });

                    if (quantityInput) {
                        quantityInput.disabled = isLoading;
                    }
                }

                updateItemTotal(productId) {
                    const item = this.cart.find(item => item.id === productId);
                    if (!item) return;

                    const totalElement = document.querySelector(`[data-product-id="${productId}"] .item-total`);
                    if (totalElement) {
                        const newTotal = (item.price * item.quantity).toFixed(2);
                        totalElement.textContent = `$${newTotal}`;
                        
                        // Add animation
                        totalElement.style.transform = 'scale(1.1)';
                        totalElement.style.color = '#28a745';
                        setTimeout(() => {
                            totalElement.style.transform = 'scale(1)';
                            totalElement.style.color = '';
                        }, 300);
                    }
                }

                // Alias for showButtonLoading to maintain compatibility
                showQuantityLoading(productId, isLoading) {
                    this.showButtonLoading(productId, isLoading);
                }

                // Update specific item quantity in UI without reloading
                updateItemQuantity(productId, quantity) {
                    const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
                    if (cartItem) {
                        const quantityInput = cartItem.querySelector('.quantity-input');
                        const itemTotal = cartItem.querySelector('.item-total');
                        const item = this.cart.find(item => item.id == productId);
                        
                        if (quantityInput) {
                            quantityInput.value = quantity;
                        }
                        
                        if (itemTotal && item) {
                            const total = parseFloat(item.price) * parseInt(quantity);
                            itemTotal.textContent = `UGX ${total.toLocaleString()}`;
                        }
                    }
                }

                // Show modal instead of alerts
                showModal(title, message, type = 'info') {
                    // Remove existing modals
                    const existingModals = document.querySelectorAll('.cart-modal');
                    existingModals.forEach(modal => modal.remove());
                    
                    // Create modal element
                    const modal = document.createElement('div');
                    modal.className = 'cart-modal';
                    modal.innerHTML = `
                        <div class="modal-overlay">
                            <div class="modal-content">
                                <div class="modal-header ${type}">
                                    <h5 class="modal-title">
                                        <i class="la la-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'} me-2"></i>
                                        ${title}
                                    </h5>
                                    <button type="button" class="modal-close" onclick="this.closest('.cart-modal').remove()">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>${message}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="this.closest('.cart-modal').remove()">OK</button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add to page
                    document.body.appendChild(modal);
                    
                    // Show modal with animation
                    setTimeout(() => modal.classList.add('show'), 100);
                    
                    // Auto-close after 3 seconds for success messages
                    if (type === 'success') {
                        setTimeout(() => {
                            modal.classList.remove('show');
                            setTimeout(() => modal.remove(), 300);
                        }, 3000);
                    }
                }

                // Show confirmation modal
                showConfirmModal(title, message, type = 'warning', onConfirm) {
                    // Remove existing modals
                    const existingModals = document.querySelectorAll('.cart-modal');
                    existingModals.forEach(modal => modal.remove());
                    
                    // Create modal element
                    const modal = document.createElement('div');
                    modal.className = 'cart-modal';
                    modal.innerHTML = `
                        <div class="modal-overlay">
                            <div class="modal-content">
                                <div class="modal-header ${type}">
                                    <h5 class="modal-title">
                                        <i class="la la-${type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'times-circle' : 'info-circle'} me-2"></i>
                                        ${title}
                                    </h5>
                                    <button type="button" class="modal-close" onclick="this.closest('.cart-modal').remove()">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>${message}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary me-2" onclick="this.closest('.cart-modal').remove()">Cancel</button>
                                    <button type="button" class="btn btn-${type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'primary'}" onclick="this.closest('.cart-modal').remove(); ${onConfirm.toString().replace(/function\s*\(\)\s*{/, '').replace(/}$/, '')}">Confirm</button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add to page
                    document.body.appendChild(modal);
                    
                    // Show modal with animation
                    setTimeout(() => modal.classList.add('show'), 100);
                    
                    // Handle confirm button click properly
                    const confirmBtn = modal.querySelector('.btn-primary, .btn-warning, .btn-danger');
                    confirmBtn.addEventListener('click', function() {
                        modal.classList.remove('show');
                        setTimeout(() => {
                            modal.remove();
                            onConfirm();
                        }, 300);
                    });
                }

                showToast(message, type = 'info') {
                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `toast-notification toast-${type}`;
                    toast.innerHTML = `
                        <div class="toast-content">
                            <i class="la ${type === 'success' ? 'la-check-circle' : type === 'error' ? 'la-exclamation-circle' : 'la-info-circle'}"></i>
                            <span>${message}</span>
                        </div>
                    `;

                    // Add to page
                    document.body.appendChild(toast);

                    // Show animation
                    setTimeout(() => toast.classList.add('show'), 100);

                    // Remove after delay
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => document.body.removeChild(toast), 300);
                    }, 3000);
                }

                getQuantity(productId) {
                    const item = this.cart.find(item => item.id === productId);
                    return item ? item.quantity : 1;
                }

                async clearCart() {
                    try {
                        const response = await fetch('cart-ajax.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'action=clear_cart'
                        });
                        
                        const data = await response.json();
                        if (data.success) {
                            // Clear cart immediately
                            this.cart = [];
                            
                            // Update UI immediately
                            this.renderCart();
                            this.updateSummary();
                            this.updateCartCount();
                            
                            this.showModal('Success', 'Cart cleared successfully!', 'success');
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error('Error clearing cart:', error);
                        this.showModal('Error', 'Error clearing cart. Please try again.', 'error');
                    }
                }

                renderCart() {
                    const cartItems = document.getElementById('cartItems');
                    const emptyCart = document.getElementById('emptyCart');
                    
                    if (!cartItems) {
                        return;
                    }
                    
                    if (this.cart.length === 0) {
                        cartItems.innerHTML = '';
                        if (emptyCart) {
                            emptyCart.style.display = 'block';
                        }
                        return;
                    }

                    if (emptyCart) {
                        emptyCart.style.display = 'none';
                    }
                    
                    cartItems.innerHTML = this.cart.map(item => {
                        // Fix image path - ensure cms/ prefix is included
                        const imagePath = item.image.startsWith('cms/') ? item.image : (item.image.startsWith('../') ? item.image.replace('../', 'cms/') : 'cms/' + item.image);
                        return `
                        <div class="cart-item bg-white rounded p-4 mb-4 shadow-sm" data-product-id="${item.id}">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="${imagePath}" alt="${item.name}" class="img-fluid rounded" onerror="this.src='assets/img/home1/projects/proj1.jpg'">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-2">${item.name}</h6>
                                    <p class="text-muted mb-2">${item.description || ''}</p>
                                    <span class="badge bg-light text-dark">${item.category}</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="quantity-controls d-flex align-items-center position-relative">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="cart.updateQuantityAjax(${item.id}, ${item.quantity} - 1)">
                                            <span class="btn-text">-</span>
                                            <span class="btn-loading d-none">
                                                <i class="la la-spinner la-spin"></i>
                                            </span>
                                        </button>
                                        <input type="number" class="form-control form-control-sm mx-2 quantity-input" value="${item.quantity}" min="1" onchange="cart.updateQuantityAjax(${item.id}, parseInt(this.value) || 1)" style="width: 60px;" readonly>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="cart.updateQuantityAjax(${item.id}, ${item.quantity} + 1)">
                                            <span class="btn-text">+</span>
                                            <span class="btn-loading d-none">
                                                <i class="la la-spinner la-spin"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold item-total">UGX ${(item.price * item.quantity).toLocaleString()}</span>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-sm btn-outline-danger" onclick="cart.removeItemAjax(${item.id})">
                                        <span class="btn-text">
                                            <i class="la la-trash"></i>
                                        </span>
                                        <span class="btn-loading d-none">
                                            <i class="la la-spinner la-spin"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    }).join('');
                }

                updateSummary() {
                const subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                
                // Use CMS settings for shipping and tax calculations
                const settings = window.shippingTaxSettings || {
                    shipping: { standard_shipping_cost: 10000, free_shipping_threshold: 100000 },
                    tax: { vat_rate: 18 }
                };
                
                const shipping = subtotal >= settings.shipping.free_shipping_threshold ? 0 : settings.shipping.standard_shipping_cost;
                const tax = (subtotal * settings.tax.vat_rate) / 100;
                const total = subtotal + shipping + tax;

                    document.getElementById('cartSubtotal').textContent = `UGX ${subtotal.toLocaleString()}`;
                    document.getElementById('cartShipping').textContent = shipping === 0 ? 'FREE' : `UGX ${shipping.toLocaleString()}`;
                    document.getElementById('cartTax').textContent = `UGX ${tax.toLocaleString()}`;
                    document.getElementById('cartTotal').textContent = `UGX ${total.toLocaleString()}`;

                    // Update cart count badge
                    this.updateCartCount();

                    // Enable/disable checkout button
                    const checkoutBtn = document.getElementById('checkoutBtn');
                    if (this.cart.length === 0) {
                        checkoutBtn.classList.add('disabled');
                        checkoutBtn.href = '#';
                    } else {
                        checkoutBtn.classList.remove('disabled');
                        checkoutBtn.href = 'checkout.php';
                    }
                }

                updateCartCount() {
                    // Fetch from server to ensure sync across browsers
                    fetch('cart-ajax.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=get_cart&cache_buster=' + Date.now(), // Cache busting for Chrome
                        cache: 'no-store', // Prevent Chrome from caching
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const cartCountElement = document.getElementById('cartCount');
                            if (cartCountElement) {
                                // Use innerHTML for better Chrome compatibility
                                cartCountElement.innerHTML = data.data.cart_count;
                                // Alternative: Force re-paint
                                cartCountElement.offsetHeight; // Trigger reflow
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error updating cart count:', error);
                    });
                }
            }

            // Initialize cart
            const cart = new ShoppingCart();

            // Clear cart button
            document.getElementById('clearCartBtn').addEventListener('click', function() {
                cart.showConfirmModal(
                    'Clear Cart',
                    'Are you sure you want to clear your cart? This action cannot be undone.',
                    'warning',
                    () => cart.clearCart()
                );
            });

            // Make cart globally available
            window.cart = cart;
        });
    </script>

    <!-- Enhanced WOW animations with mobile optimization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof WOW !== 'undefined') {
                var wow = new WOW({
                    boxClass: 'wow',
                    animateClass: 'animated',
                    offset: 50,
                    mobile: true,
                    live: false
                });
                wow.init();
            }
            
            var isMobile = window.innerWidth <= 768;
            
            setTimeout(function() {
                const animatedElements = document.querySelectorAll('.wow');
                animatedElements.forEach(function(element) {
                    if (isMobile) {
                        element.style.visibility = 'visible';
                        element.style.opacity = '1';
                        element.style.transform = 'none';
                    }
                    if (!element.classList.contains('animated')) {
                        element.classList.add('animated');
                    }
                });
            }, isMobile ? 500 : 1000);
            
            window.addEventListener('resize', function() {
                var newIsMobile = window.innerWidth <= 768;
                if (newIsMobile !== isMobile) {
                    isMobile = newIsMobile;
                    setTimeout(function() {
                        const animatedElements = document.querySelectorAll('.wow');
                        animatedElements.forEach(function(element) {
                            element.style.visibility = 'visible';
                            element.style.opacity = '1';
                        });
                    }, 100);
                }
            });
            
            window.addEventListener('load', function() {
                const animatedElements = document.querySelectorAll('.wow');
                animatedElements.forEach(function(element) {
                    element.style.visibility = 'visible';
                    element.style.opacity = '1';
                });
            });
        });
    </script>

</body>

</html>
