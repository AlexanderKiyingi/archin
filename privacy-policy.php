<?php
// Include centralized database connection
require_once 'cms/db_connect.php';

// Fetch site settings
$settings = [];
$settings_query = "SELECT setting_key, setting_value FROM site_settings";
$settings_result = $conn->query短语($settings_query);
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
    <meta name="keywords" content="Privacy Policy, FlipAvenue, Data Protection, GDPR">
    <meta name="description" content="Privacy Policy of FlipAvenue Limited. Learn how we collect, use, and protect your personal information.">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title>Privacy Policy | FlipAvenue Limited</title>

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
                            Privacy Policy
                        </h1>
                        <div class="breadcrumb-nav mt-20 wow fadeInUp" data-wow-delay="0.2s">
                            <a href="index.php">Home</a> 
                            <span class="mx-2"> / </span>
                            <span>Privacy Policy</span>
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
                        <div class="last-updated">Last Updated: August 22, 2023</div>

                        <h2>1. Introduction</h2>
                        <p>
                            Welcome to Flip Avenue. This Privacy Policy outlines how we collect, use, disclose, and protect the personal information of our clients, customers, and website visitors. By accessing our website and using our services, you agree to the practices described in this policy.
                        </p>

                        <h2>2. Information We Collect</h2>
                        <p>We may collect the following categories of personal information:</p>
                        <ul>
                            <li><strong>Personal Identifiers:</strong> Name, contact information (email address, phone number, physical address).</li>
                            <li><strong>Financial Information:</strong> Billing details, payment information.</li>
                            <li><strong>Commercial Information:</strong> Records of products or services purchased, obtained, or considered.</li>
                            <li><strong>Usage Data:</strong> Information on how you use our website and services.</li>
                            <li><strong>Correspondence:</strong> Correspondence and communications exchanged with us.</li>
                        </ul>

                        <h2>3. How We Use Your Information</h2>
                        <p>We use the collected information for various purposes, including:</p>
                        <ul>
                            <li>Providing and managing services you've requested.</li>
                            <li>Responding to inquiries and providing customer support.</li>
                            <li>Analyzing and improving our website, services, and user experience.</li>
                            <li>Sending promotional materials and offers with your consent.</li>
                            <li>Detecting and preventing fraud and unauthorized access.</li>
                        </ul>

                        <h2>4. Disclosure of Information</h2>
                        <p>We may share your information with:</p>
                        <ul>
                            <li><strong>Service Providers:</strong> Third-party providers who help us deliver services, such as payment processors, shipping companies, and IT service providers.</li>
                            <li><strong>Legal Requirements:</strong> When required by law, regulations, legal processes, or government requests.</li>
                            <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred to the acquiring entity.</li>
                            <li><strong>Affiliates:</strong> We may share information with affiliated companies, but we will only do so with appropriate safeguards.</li>
                        </ul>

                        <h2>5. Your Choices and Rights</h2>
                        <ul>
                            <li>You can choose not to provide certain information, but this may affect your access to certain services.</li>
                            <li>You can opt-out of receiving marketing communications.</li>
                            <li>You have the right to access, correct, and delete your personal information.</li>
                            <li>You can request restrictions on the processing of your data.</li>
                            <li>You can withdraw your consent to processing at any time.</li>
                        </ul>

                        <h2>6. Data Security</h2>
                        <p>
                            We implement appropriate technical and organizational measures to protect your personal information. However, no data transmission over the internet can be guaranteed to be fully secure.
                        </p>

                        <h2>7. Cookies and Tracking Technologies</h2>
                        <p>
                            Our website may use cookies, web beacons, and similar technologies to enhance your browsing experience and collect usage data. You can manage your cookie preferences through your browser settings.
                        </p>

                        <h2>8. Children's Privacy</h2>
                        <p>
                            Our services are not intended for children under the age of 13 years. We do not knowingly collect personal information from children.
                        </p>

                        <h2>9. Changes to this Privacy Policy</h2>
                        <p>
                            We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. The updated policy will be posted on our website.
                        </p>

                        <h2>10. Contact Us</h2>
                        <p>
                            If you have questions about this Privacy Policy or your personal information, please contact us at info@flipavenue.com.
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

