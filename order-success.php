<?php
// Include centralized database connection
require_once 'cms/db_connect.php';
require_once 'cms/flutterwave-config.php';

$order = null;
$order_items = [];
$payment_verified = false;

// Get order number from URL parameter
$order_number = $_GET['order'] ?? '';

if ($order_number) {
    // Fetch order details
    $order_query = "SELECT * FROM shop_orders WHERE order_number = '" . $conn->real_escape_string($order_number) . "'";
    $order_result = $conn->query($order_query);
    
    if ($order_result && $order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        
        // Fetch order items
        $items_query = "SELECT * FROM shop_order_items WHERE order_id = " . $order['id'];
        $items_result = $conn->query($items_query);
        
        if ($items_result && $items_result->num_rows > 0) {
            while ($item = $items_result->fetch_assoc()) {
                $order_items[] = $item;
            }
        }
        
        // Verify payment with Flutterwave if transaction ID exists
        if ($order['transaction_id']) {
            $payment_verified = verifyFlutterwavePayment($order['transaction_id']);
        }
    }
}

// If no order found, redirect to shop
if (!$order) {
    header("Location: shop.php");
    exit();
}

/**
 * Verify Flutterwave payment
 */
function verifyFlutterwavePayment($transaction_id) {
    $secret_key = FLUTTERWAVE_SECRET_KEY;
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/" . $transaction_id . "/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $secret_key,
            "Content-Type: application/json"
        ),
    ));
    
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($http_code === 200) {
        $data = json_decode($response, true);
        return $data && $data['status'] === 'success' && $data['data']['status'] === 'successful';
    }
    
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - FlipAvenue</title>
    <meta name="description" content="Your order has been placed successfully.">
    <meta name="keywords" content="order success, payment confirmed, architecture, products">
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
    <link rel="stylesheet" href="common/assets/css/lib/line-awesome.css">
    <link rel="stylesheet" href="common/assets/css/lib/swiper8.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/jquery.fancybox.css">
    <link rel="stylesheet" href="common/assets/css/lib/lity.css">
    <link rel="stylesheet" href="common/assets/css/common_style.css">
    <link rel="stylesheet" href="assets/style.css">

    <style>
        .order-success-header {
            min-height: 12vh !important;
            height: 12vh !important;
            max-height: 12vh !important;
        }
        
        .order-success-header .img {
            min-height: 12vh !important;
            height: 12vh !important;
            max-height: 12vh !important;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .order-details-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
        }
        
        .payment-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .payment-status.paid {
            background: #d4edda;
            color: #155724;
        }
        
        .payment-status.pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .payment-status.failed {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .order-success-header .info h1 {
                font-size: 2rem !important;
            }
            
            .order-success-header .info h5 {
                font-size: 1.2rem !important;
            }
            
            .order-details-card {
                padding: 1.5rem !important;
                margin-bottom: 1.5rem;
            }
            
            .order-item {
                flex-direction: column;
                text-align: center;
                padding: 1rem 0;
            }
            
            .order-item img {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .order-success-header .info h1 {
                font-size: 1.5rem !important;
            }
            
            .order-success-header .info h5 {
                font-size: 1rem !important;
            }
            
            .order-details-card {
                padding: 1rem !important;
            }
            
            .success-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="order-success-header">
        <div class="img">
            <img src="assets/img/home5/header.jpg" alt="Order Success" class="w-100 h-100 object-fit-cover">
        </div>
        <div class="info">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center">
                            <h1 class="fsz-50 fw-600 mb-20 wow fadeInUp" data-wow-delay="0.2s">
                                Order Successful!
                            </h1>
                            <h5 class="fsz-18 wow fadeInUp" data-wow-delay="0.4s">
                                Thank you for your purchase. Your order has been confirmed.
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Order Details Section -->
    <section class="tc-order-success pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <!-- Success Message -->
                    <div class="text-center mb-50">
                        <div class="success-icon wow fadeInUp">
                            <i class="las la-check-circle"></i>
                        </div>
                        <h3 class="fsz-30 fw-600 mb-20 wow fadeInUp" data-wow-delay="0.2s">
                            Payment <?php echo $order['payment_status'] === 'paid' ? 'Confirmed' : 'Pending'; ?>
                        </h3>
                        <p class="fsz-16 color-666 wow fadeInUp" data-wow-delay="0.4s">
                            <?php if ($order['payment_status'] === 'paid'): ?>
                                Your payment has been processed successfully and your order is being prepared.
                            <?php else: ?>
                                Your order has been placed. Payment verification is in progress.
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Order Details Card -->
                    <div class="order-details-card wow fadeInUp" data-wow-delay="0.6s">
                        <h4 class="fsz-24 fw-600 mb-30">Order Details</h4>
                        
                        <div class="row mb-30">
                            <div class="col-md-6">
                                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                                <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                                <p><strong>Payment Status:</strong> 
                                    <span class="payment-status <?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            </div>
                        </div>
                        
                        <?php if ($order['transaction_id']): ?>
                        <div class="row mb-30">
                            <div class="col-12">
                                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($order['transaction_id']); ?></p>
                                <?php if ($order['payment_method']): ?>
                                    <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('mobilemoney', 'Mobile Money', $order['payment_method'])); ?></p>
                                <?php endif; ?>
                                <?php if ($order['mobile_money_network']): ?>
                                    <p><strong>Mobile Money Network:</strong> <?php echo htmlspecialchars($order['mobile_money_network']); ?></p>
                                <?php endif; ?>
                                <?php if ($order['mobile_money_phone']): ?>
                                    <p><strong>Mobile Money Phone:</strong> <?php echo htmlspecialchars($order['mobile_money_phone']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fsz-18 fw-600 mb-15">Billing Address</h6>
                                <p class="color-666"><?php echo nl2br(htmlspecialchars($order['billing_address'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fsz-18 fw-600 mb-15">Shipping Address</h6>
                                <p class="color-666"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                            </div>
                        </div>
                        
                        <?php if (!empty($order['order_notes'])): ?>
                        <div class="row mt-30">
                            <div class="col-12">
                                <h6 class="fsz-18 fw-600 mb-15">Order Notes</h6>
                                <div class="alert alert-info mb-0">
                                    <i class="la la-info-circle me-2"></i>
                                    <span><?php echo nl2br(htmlspecialchars($order['order_notes'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Items -->
                    <div class="order-details-card wow fadeInUp" data-wow-delay="0.8s">
                        <h4 class="fsz-24 fw-600 mb-30">Order Items</h4>
                        
                        <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <img src="cms/assets/uploads/products/<?php echo htmlspecialchars($item['product_name']); ?>.jpg" 
                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                 onerror="this.src='assets/img/home1/services/ser.jpg'">
                            <div class="flex-grow-1">
                                <h6 class="fsz-16 fw-600 mb-5"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                <p class="fsz-14 color-666 mb-0">Quantity: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="text-end">
                                <p class="fsz-16 fw-600 mb-0">UGX <?php echo number_format($item['total_price']); ?></p>
                                <p class="fsz-14 color-666 mb-0">UGX <?php echo number_format($item['product_price']); ?> each</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-details-card wow fadeInUp" data-wow-delay="1s">
                        <h4 class="fsz-24 fw-600 mb-30">Order Summary</h4>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between mb-15">
                                    <span>Subtotal:</span>
                                    <span>UGX <?php echo number_format($order['subtotal']); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-15">
                                    <span>Shipping:</span>
                                    <span>UGX <?php echo number_format($order['shipping_cost']); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-15">
                                    <span>Tax (VAT):</span>
                                    <span>UGX <?php echo number_format($order['tax_amount']); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fsz-18 fw-600">
                                    <span>Total:</span>
                                    <span>UGX <?php echo number_format($order['total_amount']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-50 wow fadeInUp" data-wow-delay="1.2s">
                        <a href="shop.php" class="btn btn-primary btn-lg me-3">
                            <i class="las la-shopping-cart me-2"></i>
                            Continue Shopping
                        </a>
                        <a href="index.php" class="btn btn-outline-primary btn-lg">
                            <i class="las la-home me-2"></i>
                            Back to Home
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="tc-footer-style1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-bottom text-center">
                        <p class="fsz-14 color-666 mb-0">
                            &copy; <?php echo date('Y'); ?> FlipAvenue Architecture. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="common/assets/js/lib/jquery-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/jquery-migrate-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/gsap_lib/gsap.min.js"></script>
    <script src="common/assets/js/lib/gsap_lib/ScrollTrigger.min.js"></script>
    <script src="common/assets/js/lib/gsap_lib/ScrollSmoother.min.js"></script>
    <script src="common/assets/js/lib/gsap_lib/SplitText.min.js"></script>
    <script src="common/assets/js/lib/jquery.fancybox.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/jquery.counterup.js"></script>
    <script src="common/assets/js/lib/jquery.waypoints.min.js"></script>
    <script src="common/assets/js/lib/parallaxie.js"></script>
    <script src="common/assets/js/common_js.js"></script>
    <script src="assets/main.js"></script>

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