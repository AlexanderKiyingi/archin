<?php
// Include centralized database connection
require_once 'cms/db_connect.php';

// Fetch site settings
$settings = [];
$settings_query = "SELECT setting_key, setting_value FROM site_settings";
$settings_result = $conn->query($settings_query);
if ($settings_result) {
    while ($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

// Set current year for copyright
$current_year = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="keywords" content="Terms and Conditions, FlipAvenue, Terms of Service, Legal">
    <meta name="description" content="Terms and Conditions for FlipAvenue Limited. Read our terms of service and usage guidelines.">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title>Terms and Conditions | FlipAvenue Limited</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/home1/fav.png" title="Favicon" sizes="16x16">

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="common/assets/css/lib/bootstrap.min.css">

    <!-- font family -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- fontawesome icons  -->
    <link rel="stylesheet" href="common/assets/css/lib/all.min.css">
    <!-- line-awesome icons  -->
    <link rel="stylesheet" href="common/assets/css/lib/line-awesome.css">
    <!-- themify icons  -->
    <link rel="stylesheet" href="common/assets/css/lib/themify-icons.css">
    <!-- animate css  -->
    <link rel="stylesheet" href="common/assets/css/lib/animate.css">
    <!-- fancybox popup  -->
    <link rel="stylesheet" href="common/assets/css/lib/jquery.fancybox.css">
    <!-- lity popup  -->
    <link rel="stylesheet" href="common/assets/css/lib/lity.css">
    <!-- swiper slider  -->
    <link rel="stylesheet" href="common/assets/css/lib/swiper8.min.css">

    <!-- common style -->
    <link rel="stylesheet" href="common/assets/css/common_style.css">

    <!-- home style -->
    <link rel="stylesheet" href="assets/style.css">
    
    <style>
        .legal-content {
            padding: 80px 0;
        }
        .legal-content h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #1a1a1a;
        }
        .legal-content h3 {
            font-size: 22px;
            font-weight: 600;
            margin-top: 40px;
            margin-bottom: 20px;
            color: #333;
        }
        .legal-content p {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 20px;
        }
        .legal-content ul {
            margin-bottom: 20px;
            padding-left: 30px;
        }
        .legal-content ul li {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 10px;
        }
        .last-updated {
            font-size: 14px;
            color: #888;
            font-style: italic;
            margin-bottom: 40px;
        }
    </style>
</head>

<body class="home-style1">

    <!-- ====== Start Loading ====== -->
    <div id="loading-wrapper">
        <div class="loading-text">Loading</div>
        <div class="loading-circle"></div>
    </div>
    <!-- ====== End Loading ====== -->

    <!-- ====== Start header ====== -->
    <?php include 'common/header.php'; ?>
    <!-- ====== End header ====== -->

    <!--Contents-->
    <main>

        <!-- ====== Start header-section ====== -->
        <section class="header-section style-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h1 class="main-title wow fadeInUp" data-wow-delay="0.1s">
                            Terms and Conditions
                        </h1>
                        <div class="breadcrumb-nav mt-20 wow fadeInUp" data-wow-delay="0.2s">
                            <a href="index.php">Home</a> 
                            <span class="mx-2"> / </span>
                            <span>Terms and Conditions</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ====== End header-section ====== -->

        <!-- ====== Start Legal Content ====== -->
        <section class="legal-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="last-updated">Last Updated: <?php echo date('F d, Y'); ?></div>

                        <h2>1. Introduction</h2>
                        <p>
                            These Terms and Conditions govern your use of the Flip Avenue online shop. By accessing or using the shop, you agree to be bound by these Terms and Conditions and our Privacy Policy. If you disagree with any part of these terms, please do not use our shop.
                        </p>

                        <h2>2. Products</h2>
                        <p>
                            All products displayed on the Flip Avenue online shop are subject to availability. We reserve the right to discontinue any product at any time. Prices for our products are subject to change without notice.
                        </p>

                        <h2>3. Payment</h2>
                        <p>
                            We accept payment through Visa and Mobile Money. Payment must be received in full before the order is processed and dispatched.
                        </p>

                        <h2>4. Shipping</h2>
                        <p>
                            Shipping costs and delivery times may vary depending on the destination and shipping method selected. We aim to dispatch orders within 5 business days. Once dispatched, delivery times are estimated and may vary.
                        </p>

                        <h2>5. Returns and Refunds</h2>
                        <p>
                            Please refer to our Refund Policy for information on returns, refunds, and exchanges.
                        </p>

                        <h2>6. Intellectual Property</h2>
                        <p>
                            All content included on the Flip Avenue online shop, such as text, graphics, logos, images, and software, is the property of Flip Avenue and is protected by copyright and other intellectual property laws. You may not reproduce, modify, distribute, or republish any content from this shop without our prior written consent.
                        </p>

                        <h2>7. Privacy</h2>
                        <p>
                            Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and disclose your personal information.
                        </p>

                        <h2>8. Limitation of Liability</h2>
                        <p>
                            Flip Avenue shall not be liable for any direct, indirect, incidental, special, or consequential damages arising out of or in any way connected with the use of the online shop or the products purchased through the shop.
                        </p>

                        <h2>9. Governing Law</h2>
                        <p>
                            These Terms and Conditions shall be governed by and construed in accordance with the laws of Uganda, without regard to its conflict of law provisions.
                        </p>

                        <h2>10. Changes to Terms</h2>
                        <p>
                            Flip Avenue reserves the right to modify or replace these Terms and Conditions at any time. Any changes will be effective immediately upon posting on the shop. Continued use of the shop after any such changes constitutes your acceptance of the new Terms and Conditions.
                        </p>

                        <h2>Contact Information</h2>
                        <p>
                            If you have any questions or concerns about these Terms and Conditions, please contact us at info@flipavenue.com or +256 701 380 251.
                        </p>
                        <p>
                            <strong>Note:</strong> These Terms and Conditions apply only to purchases made through our online shop. For purchases made through other channels, such as in-store or third-party retailers, please refer to their respective terms and conditions.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- ====== End Legal Content ====== -->

    </main>
    <!--End-Contents-->

    <!-- ====== start footer ====== -->
    <?php include 'common/footer.php'; ?>
    <!-- ====== End footer ====== -->

    <!--  Start to top button  -->
    <a href="#" class="to_top" id="to_top">
        <i class="la la-angle-up"></i>
    </a>
    <!--  End to top button  -->

    <!--  request  -->
    <script src="common/assets/js/lib/jquery-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/jquery-migrate-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/wow.min.js"></script>
    <script src="common/assets/js/lib/jquery.fancybox.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/swiper8-bundle.min.js"></script>
    <script src="common/assets/js/lib/jquery.counterup.js"></script>
    <script src="common/assets/js/lib/jquery.waypoints.min.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/parallaxie.js"></script>

    <script src="common/assets/js/common_js.js"></script>

</body>

</html>
