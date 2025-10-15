<?php
// Include centralized database connection
require_once 'cms/db_connect.php';
require_once 'cms/shipping-tax-functions.php';

// Include Flutterwave configuration
require_once 'cms/flutterwave-config.php';

$order_placed = false;
$order_number = '';

// Process order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_data'])) {
    // Get form data
    $customer_name = $conn->real_escape_string($_POST['firstName'] . ' ' . $_POST['lastName']);
    $customer_email = $conn->real_escape_string($_POST['email']);
    $customer_phone = $conn->real_escape_string($_POST['phone']);
    $billing_address = $conn->real_escape_string($_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['zipCode']);
    $shipping_address = isset($_POST['different_shipping']) ? 
        $conn->real_escape_string($_POST['shipping_address'] . ', ' . $_POST['shipping_city'] . ', ' . $_POST['shipping_zip']) : 
        $billing_address;
    
    // Get cart data from POST
    $cart_data = json_decode($_POST['cart_data'], true);
    
    // Get payment data if available
    $payment_status = 'pending';
    $transaction_id = '';
    if (isset($_POST['payment_data'])) {
        $payment_data = json_decode($_POST['payment_data'], true);
        $payment_status = $payment_data['status'] === 'successful' ? 'paid' : 'pending';
        $transaction_id = $payment_data['transaction_id'] ?? '';
    }
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cart_data as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping_cost = calculateShipping($subtotal);
    $tax_amount = calculateTax($subtotal);
    $total_amount = $subtotal + $shipping_cost + $tax_amount;
    
    // Generate order number
    $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    
    // Insert order
    $order_query = "INSERT INTO shop_orders (order_number, customer_name, customer_email, customer_phone, 
                    billing_address, shipping_address, subtotal, shipping_cost, tax_amount, total_amount, 
                    payment_status, order_status, transaction_id) 
                    VALUES ('$order_number', '$customer_name', '$customer_email', '$customer_phone', 
                    '$billing_address', '$shipping_address', $subtotal, $shipping_cost, $tax_amount, $total_amount, 
                    '$payment_status', 'pending', '$transaction_id')";
    
    if ($conn->query($order_query)) {
        $order_id = $conn->insert_id;
        
        // Insert order items
        foreach ($cart_data as $item) {
            $product_id = str_replace('product-', '', $item['id']);
            $product_name = $conn->real_escape_string($item['name']);
            $product_price = $item['price'];
            $quantity = $item['quantity'];
            $total_price = $product_price * $quantity;
            
            $item_query = "INSERT INTO shop_order_items (order_id, product_id, product_name, product_price, quantity, total_price) 
                          VALUES ($order_id, $product_id, '$product_name', $product_price, $quantity, $total_price)";
            $conn->query($item_query);
        }
        
        $order_placed = true;
        // Redirect to success page
        header("Location: order-success.php?order=" . $order_number);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - FlipAvenue</title>
    <meta name="description" content="Complete your architectural product purchase securely.">
    <meta name="keywords" content="checkout, payment, architecture, products, order">
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
        <header class="tc-header-style1 checkout-header" style="min-height: 12vh; height: 12vh; max-height: 12vh;">
            <div class="img" style="min-height: 12vh !important; height: 12vh !important; max-height: 12vh !important;">
                <img src="assets/img/home1/head_slide2.png" alt="" class="img-cover">
            </div>
           
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start checkout  -->
            <section class="tc-checkout-style1 section-padding">
                <div class="container">
                    <div class="mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="fsz-45 mb-3 wow fadeInUp"> Complete Your Order </h2>
                                <p class="color-666 wow fadeInUp" data-wow-delay="0.2s">Fill in your details to complete your architectural product purchase</p>
                            </div>
                            <div class="col-lg-6 mt-4 mt-lg-0">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="cart.php" class="butn border rounded-pill hover-bg-orange1">
                                        <span>Back to Cart</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End checkout  -->

            <!--  Start checkout form  -->
            <section class="tc-checkout-form section-padding pt-0">
                <div class="container">
                    <form id="checkoutForm" method="POST" action="checkout.php">
                        <div class="row">
                            <!-- Billing Information -->
                            <div class="col-lg-8">
                                <div class="checkout-form bg-white rounded p-4 shadow-sm mb-4">
                                    <h5 class="mb-4">Billing Information</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="firstName" class="form-label">First Name *</label>
                                            <input type="text" class="form-control" id="firstName" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastName" class="form-label">Last Name *</label>
                                            <input type="text" class="form-control" id="lastName" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="email" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone Number *</label>
                                            <input type="tel" class="form-control" id="phone" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Street Address *</label>
                                        <input type="text" class="form-control" id="address" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="city" class="form-label">City *</label>
                                            <input type="text" class="form-control" id="city" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="state" class="form-label">State/Province *</label>
                                            <input type="text" class="form-control" id="state" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="zipCode" class="form-label">ZIP/Postal Code *</label>
                                            <input type="text" class="form-control" id="zipCode" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country *</label>
                                        <select class="form-select" id="country" required>
                                            <option value="">Select Country</option>
                                            <option value="UG">Uganda</option>
                                            <option value="KE">Kenya</option>
                                            <option value="TZ">Tanzania</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="US">United States</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="CA">Canada</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Payment Information -->
                                <div class="checkout-form bg-white rounded p-4 shadow-sm mb-4">
                                    <h5 class="mb-4">Payment Information</h5>
                                    
                                    <!-- Payment Method Selection -->
                                    <div class="mb-4">
                                        <h6 class="mb-3">Select Payment Method</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="payment-option" onclick="selectPaymentMethod('mobilemoney')">
                                                    <div class="card payment-card" id="mobilemoney-card">
                                                        <div class="card-body text-center">
                                                            <i class="la la-mobile-alt fa-2x text-primary mb-2"></i>
                                                            <h6 class="card-title">Mobile Money</h6>
                                                            <small class="text-muted">MTN, Airtel, Africell</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="payment-option" onclick="selectPaymentMethod('visa')">
                                                    <div class="card payment-card" id="visa-card">
                                                        <div class="card-body text-center">
                                                            <i class="la la-credit-card fa-2x text-primary mb-2"></i>
                                                            <h6 class="card-title">VISA Card</h6>
                                                            <small class="text-muted">Credit & Debit Cards</small>
                                                        </div>
                                    </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Button -->
                                    <div class="text-center mb-4">
                                        <button type="button" class="btn btn-primary btn-lg w-100" id="pay-btn" onclick="processPayment()" disabled>
                                            <i class="la la-credit-card me-2"></i>
                                            <span id="pay-btn-text">Select Payment Method</span>
                                        </button>
                                    </div>
                                    
                                    <div class="mt-3 text-center">
                                        <small class="color-666">
                                            <i class="la la-shield me-2"></i>
                                            Secure payment processing
                                        </small>
                                    </div>
                                    
                                    <!-- Hidden payment status -->
                                    <input type="hidden" id="payment_status" name="payment_status" value="pending">
                                    <input type="hidden" id="transaction_id" name="transaction_id" value="">
                                </div>

                                <!-- Order Notes -->
                                <div class="checkout-form bg-white rounded p-4 shadow-sm">
                                    <h5 class="mb-4">Order Notes (Optional)</h5>
                                    <div class="mb-3">
                                        <label for="orderNotes" class="form-label">Special Instructions</label>
                                        <textarea class="form-control" id="orderNotes" rows="3" placeholder="Any special instructions for your order..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="col-lg-4">
                                <div class="order-summary bg-light p-4 rounded">
                                    <h5 class="mb-4">Order Summary</h5>
                                    
                                    <div id="orderItems">
                                        <!-- Order items will be populated by JavaScript -->
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="summary-item d-flex justify-content-between mb-3">
                                        <span>Subtotal:</span>
                                        <span id="orderSubtotal">UGX 0</span>
                                    </div>
                                    
                                    <div class="summary-item d-flex justify-content-between mb-3">
                                        <span>Shipping:</span>
                                        <span id="orderShipping">UGX 0</span>
                                    </div>
                                    
                                    <div class="summary-item d-flex justify-content-between mb-3">
                                        <span>Tax:</span>
                                        <span id="orderTax">UGX 0</span>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="summary-item d-flex justify-content-between mb-4">
                                        <strong>Total:</strong>
                                        <strong id="orderTotal">UGX 0</strong>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="butn bg-orange1 text-white rounded-pill" id="placeOrderBtn">
                                            <span>Place Order - UGX <span id="orderTotalBtn">0</span></span>
                                        </button>
                                    </div>
                                    
                                    <div class="mt-3 text-center">
                                        <small class="color-666">
                                            <i class="la la-shield me-2"></i>
                                            Your payment information is secure and encrypted
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <!--  End checkout form  -->

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
                                    <a href="#"> <i class="fab fa-x-twitter"></i> </a>
                                    <a href="#"> <i class="fab fa-facebook-f"></i> </a>
                                    <a href="#"> <i class="fab fa-instagram"></i> </a>
                                    <a href="#"> <i class="fab fa-linkedin-in"></i> </a>
                                    <a href="#"> <i class="fab fa-youtube"></i> </a>
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
    
    <!-- Flutterwave Payment SDK -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>

    <!-- Checkout JavaScript -->
    <script>
        // Pass shipping and tax settings from PHP to JavaScript
        window.shippingTaxSettings = <?php echo getShippingTaxSettingsJSON(); ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize WOW.js
            new WOW().init();

            // Get cart from session via AJAX
            let cart = [];
            
            // Fetch cart from server
            fetch('cart-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_cart&cache_buster=' + Date.now(),
                cache: 'no-store',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.cart.length > 0) {
                    cart = data.data.cart;
                    renderCheckoutPage(cart);
                } else {
                    alert('Your cart is empty. Redirecting to shop...');
                    window.location.href = 'shop.php';
                }
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                alert('Error loading cart. Please try again.');
            });
        }); // Close DOMContentLoaded
        
        function renderCheckoutPage(cart) {
            if (cart.length === 0) {
                alert('Your cart is empty. Redirecting to shop...');
                window.location.href = 'shop.php';
                return;
            }

            // Display order items
            function displayOrderItems() {
                const orderItems = document.getElementById('orderItems');
                
                if (!orderItems) {
                    return;
                }
                
                const itemsHTML = cart.map(item => {
                    // Fix image path  
                    let imagePath = item.image || 'assets/img/home1/projects/proj1.jpg';
                    if (imagePath.startsWith('../')) {
                        imagePath = imagePath.replace('../', 'cms/');
                    } else if (!imagePath.startsWith('cms/') && !imagePath.startsWith('assets/')) {
                        imagePath = 'cms/' + imagePath;
                    }
                    return `
                    <div class="order-item d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <img src="${imagePath}" alt="${item.name}" class="img-fluid rounded me-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='assets/img/home1/projects/proj1.jpg'">
                            <div>
                                <h6 class="mb-1">${item.name}</h6>
                                <small class="text-muted">Qty: ${item.quantity}</small>
                            </div>
                        </div>
                        <span class="fw-bold">UGX ${(item.price * item.quantity).toLocaleString()}</span>
                    </div>
                `;
                }).join('');
                
                orderItems.innerHTML = itemsHTML;
            }

            // Calculate and display totals
            function updateTotals() {
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                // Use CMS settings for shipping and tax calculations
                const settings = window.shippingTaxSettings || {
                    shipping: { standard_shipping_cost: 10000, free_shipping_threshold: 100000 },
                    tax: { vat_rate: 18 }
                };
                
                const shipping = subtotal >= settings.shipping.free_shipping_threshold ? 0 : settings.shipping.standard_shipping_cost;
                const tax = (subtotal * settings.tax.vat_rate) / 100;
                const total = subtotal + shipping + tax;

                document.getElementById('orderSubtotal').textContent = `UGX ${subtotal.toLocaleString()}`;
                document.getElementById('orderShipping').textContent = shipping === 0 ? 'FREE' : `UGX ${shipping.toLocaleString()}`;
                document.getElementById('orderTax').textContent = `UGX ${tax.toLocaleString()}`;
                document.getElementById('orderTotal').textContent = `UGX ${total.toLocaleString()}`;
                document.getElementById('orderTotalBtn').textContent = total.toLocaleString();
            }

            // Initialize display
            displayOrderItems();
            updateTotals();

            // Form validation before payment
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form before proceeding to payment
                if (validateForm()) {
                    // Enable Flutterwave payment button
                    document.getElementById('flutterwave-pay-btn').disabled = false;
                    alert('Form validated! Click "Pay Securely with Flutterwave" to complete your order.');
                }
            });

            // Form validation function
            function validateForm() {
                const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipCode', 'country'];
                let isValid = true;

                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Email validation
                const email = document.getElementById('email').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    document.getElementById('email').classList.add('is-invalid');
                    isValid = false;
                }

                return isValid;
            }

            // Global cart data for Flutterwave and payment functions
            window.cartData = cart;
            window.checkoutCart = cart; // Make cart accessible to payment functions
        } // Close renderCheckoutPage

        // Payment Method Selection
        let selectedPaymentMethod = null;

        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Remove selected class from all cards
            document.querySelectorAll('.payment-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            document.getElementById(method + '-card').classList.add('selected');
            
            // Enable pay button and update text
            const payBtn = document.getElementById('pay-btn');
            const payBtnText = document.getElementById('pay-btn-text');
            
            payBtn.disabled = false;
            
            if (method === 'mobilemoney') {
                payBtnText.textContent = 'Pay with Mobile Money';
                payBtn.innerHTML = '<i class="la la-mobile-alt me-2"></i><span id="pay-btn-text">Pay with Mobile Money</span>';
            } else if (method === 'visa') {
                payBtnText.textContent = 'Pay with VISA Card';
                payBtn.innerHTML = '<i class="la la-credit-card me-2"></i><span id="pay-btn-text">Pay with VISA Card</span>';
            }
        }

        // Process Payment Function
        function processPayment() {
            if (!selectedPaymentMethod) {
                alert('Please select a payment method');
                return;
            }

            if (selectedPaymentMethod === 'mobilemoney') {
                payWithMobileMoney();
            } else if (selectedPaymentMethod === 'visa') {
                payWithVisa();
            }
        }

        // Mobile Money Payment Function
        function payWithMobileMoney() {
            payWithFlutterwave('mobilemoney');
        }

        // VISA Card Payment Function
        function payWithVisa() {
            payWithFlutterwave('card');
        }

        // Flutterwave Payment Function (Backend Integration)
        function payWithFlutterwave(paymentType) {
            // Fetch cart from session
            fetch('cart-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_cart&cache_buster=' + Date.now(),
                cache: 'no-store',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success || data.data.cart.length === 0) {
                    alert('Your cart is empty!');
                    return;
                }
                
                const cart = data.data.cart;
                processFlutterwavePayment(cart, paymentType);
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                alert('Error processing payment. Please try again.');
            });
        }
        
        function processFlutterwavePayment(cart, paymentType) {

            // Calculate totals
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            // Use CMS settings for shipping and tax calculations
            const settings = window.shippingTaxSettings || {
                shipping: { standard_shipping_cost: 10000, free_shipping_threshold: 100000 },
                tax: { vat_rate: 18 }
            };
            
            const shipping = subtotal >= settings.shipping.free_shipping_threshold ? 0 : settings.shipping.standard_shipping_cost;
            const tax = (subtotal * settings.tax.vat_rate) / 100;
            const total = subtotal + shipping + tax;

            // Get customer details
            const customerName = document.getElementById('firstName').value + ' ' + document.getElementById('lastName').value;
            const customerEmail = document.getElementById('email').value;
            const customerPhone = document.getElementById('phone').value;

            // Generate unique transaction reference
            const txRef = 'FA-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);

            // Flutterwave payment configuration
            FlutterwaveCheckout({
                public_key: "<?php echo getFlutterwavePublicKey(); ?>",
                tx_ref: txRef,
                amount: total,
                currency: "UGX",
                payment_options: paymentType === 'mobilemoney' ? "mobilemoney" : "card",
                redirect_url: "order-success.php",
                customer: {
                    email: customerEmail,
                    phone_number: customerPhone,
                    name: customerName,
                },
                customizations: {
                    title: "FlipAvenue Architecture",
                    description: "Payment for architectural products",
                    logo: "assets/img/home1/logo.png",
                },
                callback: function (data) {
                    // Payment successful
                    if (data.status === 'successful') {
                        // Update payment status
                        document.getElementById('payment_status').value = 'paid';
                        document.getElementById('transaction_id').value = data.transaction_id;
                        
                        // Submit form to process order
                        submitOrder(data);
                    }
                },
                onclose: function() {
                    // Payment cancelled - user will remain on checkout page
                }
            });
        }

        // Submit order after successful payment
        function submitOrder(paymentData) {
            // Fetch cart from session
            fetch('cart-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_cart&cache_buster=' + Date.now(),
                cache: 'no-store',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success || data.data.cart.length === 0) {
                    alert('Cart is empty. Cannot submit order.');
                    return;
                }
                
                const cart = data.data.cart;
                processOrderSubmission(cart, paymentData);
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                alert('Error submitting order. Please try again.');
            });
        }
        
        function processOrderSubmission(cart, paymentData) {
            const form = document.getElementById('checkoutForm');
            
            // Create hidden input for cart data
            const cartInput = document.createElement('input');
            cartInput.type = 'hidden';
            cartInput.name = 'cart_data';
            cartInput.value = JSON.stringify(cart);
            form.appendChild(cartInput);

            // Create hidden input for payment data
            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'payment_data';
            paymentInput.value = JSON.stringify(paymentData);
            form.appendChild(paymentInput);

            // Submit form
            form.submit();
        }
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
