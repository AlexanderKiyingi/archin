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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="FlipAvenue Contact, Get in Touch, Architecture Inquiry, Uganda">
    <meta name="description" content="Get in touch with FlipAvenue Limited. Contact us for architecture, design, and construction services in Kampala, Uganda.">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title>Contact Us | FlipAvenue Limited</title>

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
                    <li> <a href="blog.php" class="main_link"> news </a> </li>
                    <li> <a href="shop.php" class="main_link"> shop </a> </li>
                    <li> <a href="contact.html" class="main_link"> contact </a> </li>
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
                            <a class="nav-link active" aria-current="page" href="contact.html">Contact</a>
                        </li>
                    </ul>
                    <div class="nav-side">
                        <a href="#" class="icon ms-5 fsz-21">
                            <span> <i class="la la-search"></i> </span>
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
        <header class="tc-header-style1 contact-header" style="min-height: 18vh; height: 18vh; max-height: 18vh;">
            <div class="img" style="min-height: 18vh !important; height: 18vh !important; max-height: 18vh !important;">
                <img src="assets/img/home1/head_slide3.png" alt="" class="img-cover">
            </div>
            <div class="info section-padding-x pb-70">
                <div class="row align-items-end gx-5">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <h1 class="js-title wow fadeInUp"> Get In Touch </h1>
                        <h5 class="fsz-30 mt-30 fw-400 wow fadeInUp" data-wow-delay="0.2s"> Let's Bring Your Vision <br> to Life </h5>
                    </div>
                </div>
            </div>
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start contact info  -->
            <section class="tc-experience-style1 section-padding-x pb-0">
                <div class="container-fluid">
                    <div class="row gx-5">
                        <div class="col-lg-4">
                            <div class="contact-info-card text-center p-50 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                                <div class="icon mb-3">
                                    <i class="la la-map-marker fsz-50 color-orange1"></i>
                                </div>
                                <h5 class="fsz-20 fw-600 mb-3">Visit Our Office</h5>
                                <p class="color-666">
                                    Kataza Close, Bugolobi<br>
                                    Maria House, behind Airtel Building<br>
                                    Kampala, Uganda
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="contact-info-card text-center p-50 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                                <div class="icon mb-3">
                                    <i class="la la-phone fsz-50 color-orange1"></i>
                                </div>
                                <h5 class="fsz-20 fw-600 mb-3">Call Us</h5>
                                <p class="color-666">
                                    <a href="tel:+256701380251" class="d-block mb-2 hover-orange1">+256 701 380 251</a>
                                    <a href="tel:+256783370967" class="d-block hover-orange1">+256 783 370 967</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="contact-info-card text-center p-50 mb-4 wow fadeInUp" data-wow-delay="0.4s">
                                <div class="icon mb-3">
                                    <i class="la la-envelope fsz-50 color-orange1"></i>
                                </div>
                                <h5 class="fsz-20 fw-600 mb-3">Email Us</h5>
                                <p class="color-666">
                                    <a href="mailto:info@flipavenueltd.com" class="d-block hover-orange1">info@flipavenueltd.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End contact info  -->


            <!--  Start contact form  -->
            <section class="tc-process-style1 section-padding">
                <div class="container">
                    <div class="row gx-5">
                        <div class="col-lg-6">
                            <div class="info wow fadeInUp">
                                <h2 class="fsz-45 fw-600 mb-30"> Send Us a Message </h2>
                                <p class="text fsz-15 color-666 mb-40">
                                    Have a project in mind? Fill out the form below and our team will get back to you within 24 hours.
                                </p>

                                <div class="why-choose mb-4">
                                    <div class="item d-flex align-items-start mb-4">
                                        <div class="icon me-3">
                                            <i class="la la-check-circle fsz-30 color-orange1"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-600 mb-2">Free Consultation</h6>
                                            <p class="fsz-14 color-666">Initial discussion and site visit at no cost</p>
                                        </div>
                                    </div>
                                    <div class="item d-flex align-items-start mb-4">
                                        <div class="icon me-3">
                                            <i class="la la-check-circle fsz-30 color-orange1"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-600 mb-2">Quick Response</h6>
                                            <p class="fsz-14 color-666">We respond to all inquiries within 24 hours</p>
                                        </div>
                                    </div>
                                    <div class="item d-flex align-items-start">
                                        <div class="icon me-3">
                                            <i class="la la-check-circle fsz-30 color-orange1"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-600 mb-2">Professional Team</h6>
                                            <p class="fsz-14 color-666">15+ years of experience in architecture</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="social-links mt-50">
                                    <h6 class="fw-600 mb-3">Follow Us</h6>
                                    <div class="links">
                                        <a href="#" class="icon me-3"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="icon me-3"><i class="fab fa-twitter"></i></a>
                                        <a href="#" class="icon me-3"><i class="fab fa-instagram"></i></a>
                                        <a href="#" class="icon me-3"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="#" class="icon me-3"><i class="fab fa-youtube"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-5 mt-lg-0">
                            <div class="contact-form wow fadeInUp" data-wow-delay="0.2s">
                                <form method="POST" action="contact-handler.php" id="contactForm">
                                    <input type="hidden" name="contact_form" value="1">
                                    
                                    <div class="row gx-3">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label fsz-14 color-666 mb-2">Your Name *</label>
                                            <input 
                                                type="text" 
                                                name="name" 
                                                class="form-control" 
                                                placeholder="John Doe"
                                                required
                                            >
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label fsz-14 color-666 mb-2">Email Address *</label>
                                            <input 
                                                type="email" 
                                                name="email" 
                                                class="form-control" 
                                                placeholder="john@example.com"
                                                required
                                            >
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label fsz-14 color-666 mb-2">Phone Number</label>
                                            <input 
                                                type="tel" 
                                                name="phone" 
                                                class="form-control" 
                                                placeholder="+256 700 000 000"
                                            >
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label fsz-14 color-666 mb-2">Subject</label>
                                            <input 
                                                type="text" 
                                                name="subject" 
                                                class="form-control" 
                                                placeholder="Project Inquiry"
                                            >
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label fsz-14 color-666 mb-2">Message *</label>
                                            <textarea 
                                                name="message" 
                                                class="form-control" 
                                                rows="5"
                                                placeholder="Tell us about your project..."
                                                required
                                            ></textarea>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="butn rounded-pill bg-orange1 text-white hover-bg-black w-100">
                                                <span> Send Message <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div id="formMessage" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="assets/img/home1/c_line2.png" alt="" class="c-line wow">
            </section>
            <!--  End contact form  -->


            <!--  Start map  -->
            <section class="tc-map section-padding-x">
                <div class="container-fluid">
                    <div class="map-container radius-5 overflow-hidden wow fadeInUp">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127643.04244506914!2d32.52192744863281!3d0.3475964!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc0f7c90690b%3A0x7e1f8b7e7b7e7b7e!2sKampala%2C%20Uganda!5e0!3m2!1sen!2sug!4v1234567890123!5m2!1sen!2sug" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </section>
            <!--  End map  -->


            <!--  Start office hours  -->
            <section class="tc-chat-style1">
                <div class="container">
                    <div class="content">
                        <h5 class="mb-30 lh-5"> Office Hours </h5>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="office-hours text-center">
                                    <p class="mb-2"><strong>Monday - Friday:</strong> 8:00 AM - 6:00 PM</p>
                                    <p class="mb-2"><strong>Saturday:</strong> 9:00 AM - 2:00 PM</p>
                                    <p><strong>Sunday:</strong> Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="assets/img/home1/c_line4.png" alt="" class="c-line wow">
            </section>
            <!--  End office hours  -->

        </main>
        <!--End-Contents-->

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
                                    <li> <a href="mailto:info@flipavenueltd.com"> info@flipavenueltd.com </a> </li>
                                    <li> <a href="tel:+256701380251"> +256 701380251 / 783370967 </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 fw-600"> Other links </h5>
                                <ul class="footer-links">
                                    <li> <a href="#"> Shop </a> </li>
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
                                    <li> <a href="careers.php"> Careers </a> </li>
                                    <li> <a href="contact.html"> Contact Us </a> </li>
                                    <li> <a href="#"> Help </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 fw-600"> Legal </h5>
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
                                <a href="portfolio.php"> Projects </a>
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

    <!--  request  -->
    <script src="common/assets/js/lib/jquery-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/jquery-migrate-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/wow.min.js"></script>
    <script src="common/assets/js/lib/jquery.fancybox.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/swiper8-bundle.min.js"></script>
    <script src="common/assets/js/lib/jquery.waypoints.min.js"></script>
    <script src="common/assets/js/lib/jquery.counterup.js"></script>
    <script src="common/assets/js/lib/parallaxie.js"></script>
    <!-- === Gsap === -->
    <script src="common/assets/js/gsap_lib/gsap.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollSmoother.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollTrigger.min.js"></script>
    <script src="common/assets/js/gsap_lib/SplitText.min.js"></script>
    <!-- === common === -->
    <script src="common/assets/js/common_js.js"></script>

    <!-- ===== home scripts ===== -->
    <script src="assets/main.js"></script>

    <!-- Contact form handler -->
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<span><i class="la la-spinner la-spin me-2"></i>Sending...</span>').prop('disabled', true);
                
                $.ajax({
                    url: 'contact-handler.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#formMessage').html('<div class="alert alert-success border-l-4 border-green-500 p-4 mb-4"><i class="la la-check-circle me-2"></i>' + response.message + '</div>');
                            $('#contactForm')[0].reset();
                        } else {
                            $('#formMessage').html('<div class="alert alert-danger border-l-4 border-red-500 p-4 mb-4"><i class="la la-exclamation-circle me-2"></i>' + response.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Sorry, there was an error sending your message. Please try again or email us directly at info@flipavenueltd.com';
                        
                        // Try to parse error response
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                        } catch (e) {
                            // Use default error message
                        }
                        
                        $('#formMessage').html('<div class="alert alert-danger border-l-4 border-red-500 p-4 mb-4"><i class="la la-exclamation-circle me-2"></i>' + errorMessage + '</div>');
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.html(originalText).prop('disabled', false);
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $('#formMessage').offset().top - 100
                        }, 500);
                    }
                });
            });
            
            // Clear messages when user starts typing
            $('#contactForm input, #contactForm textarea').on('focus', function() {
                $('#formMessage').html('');
            });
        });
    </script>

</body>
</html>

