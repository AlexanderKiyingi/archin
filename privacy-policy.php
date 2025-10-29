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
    <!-- ====== End Loading ====== -->

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
                    <li> <a href="blog.php" class="main_link"> news </a> </li>
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
                <a class="navbar-brand" href="index.php">
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
        </nav>
        <!--  End navbar  -->

        
        <!--Contents-->
        <main>

            <!--  Start page header  -->
            <header class="tc-header-style1 portfolio-header" style="min-height: 12vh;">
                <div class="img">
                    <img src="assets/img/home1/head_slide1.jpg" alt="" class="img-cover">
                </div>
            </header>
            <!--  End page header  -->

            <!--  Start page title  -->
            <section class="tc-shop-style1 section-padding">
                <div class="container">
                    <div class="mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-12 text-center">
                                <h2 class="fsz-45 mb-3 wow fadeInUp">Privacy Policy</h2>
                                <p class="color-666 wow fadeInUp" data-wow-delay="0.2s">Learn how we collect, use, and protect your personal information</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End page title  -->

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

                        <h2>10. Contact Us                        </h2>
                        <p>
                            If you have questions about this Privacy Policy or your personal information, please contact us at info@flipavenue.com.
                        </p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ====== End Legal Content ====== -->

        </main>

        <!--  Start footer  -->
        <footer class="tc-footer-style1">
            <div class="container">
                <div class="top-content section-padding">
                    <div class="row gx-0">
                        <div class="col-lg-4">
                            <div class="info-side">
                                <div class="text fsz-24 color-333 lh-3 fw-600">
                                    We believe that interior design has the power to shape lives and uplift communities. Flip Avenue's philosophy is passion for innovation, sustainability and timeless aesthetics
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
                                    <li> <a href="portfolio.php"> Portfolio </a> </li>
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
                                    <li> <a href="contact.php"> Contact Us </a> </li>
                                    <li> <a href="#"> Help </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Legal </h5>
                                <ul class="footer-links">
                                    <li> <a href="terms-conditions.php"> Term & Conditions </a> </li>
                                    <li> <a href="privacy-policy.php"> Privacy Policy </a> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="foot">
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="fsz-13"> © <?php echo $current_year; ?> Flip Avenue Limited. All Right Reserved </p>
                        </div>
                        <div class="col-lg-6">
                            <div class="foot-links mt-4 mt-lg-0">
                                <a href="index.php"> Home </a>
                                <a href="about.html"> About Us </a>
                                <a href="portfolio.php"> Projects </a>
                                <a href="shop.php"> Shop </a>
                                <a href="contact.php"> Contact </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--  End footer  -->

    </div>

    <!--  Start to top button  -->
    <a href="#" class="to_top" id="to_top">
        <i class="la la-angle-up"></i>
    </a>
    <!--  End to top button  -->

    <!-- scripts -->
    <script src="common/assets/js/lib/jquery-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/jquery-migrate-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/wow.min.js"></script>
    <script src="common/assets/js/lib/swiper8-bundle.min.js"></script>
    <script src="common/assets/js/lib/jquery.fancybox.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/jquery.counterup.js"></script>
    <script src="common/assets/js/lib/jquery.waypoints.min.js"></script>
    <script src="common/assets/js/lib/parallaxie.js"></script>
    
    <!-- GSAP -->
    <script src="common/assets/js/gsap_lib/gsap.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollTrigger.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollSmoother.min.js"></script>
    <script src="common/assets/js/gsap_lib/SplitText.min.js"></script>
    
    <script src="common/assets/js/common_js.js"></script>
    <script src="assets/main.js"></script>

</body>

</html>

