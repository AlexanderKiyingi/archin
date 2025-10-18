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

// Fetch active job openings
$jobs_query = "SELECT * FROM job_openings WHERE status = 'active' ORDER BY posted_date DESC";
$jobs_result = $conn->query($jobs_query);
$active_jobs = [];
if ($jobs_result) {
    while ($row = $jobs_result->fetch_assoc()) {
        $active_jobs[] = $row;
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
    <meta name="keywords" content="FlipAvenue Careers, Architecture Jobs, Design Jobs, Uganda">
    <meta name="description" content="Join FlipAvenue Limited - Explore career opportunities in architecture, design, and construction. Build your future with us.">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title>Careers - Join Our Team | FlipAvenue Limited</title>

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
        <header class="tc-header-style1 careers-header" style="min-height: 12vh;">
            <div class="img">
                <img src="assets/img/home1/head_slide1.jpg" alt="" class="img-cover">
            </div>
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            


           


            <!--  Start open positions  -->
            <section class="tc-process-style1 section-padding">
                <div class="container">
                    
                    <div class="title mb-80 text-left">
                        <h2 class="fsz-45 mb-30 wow fadeInUp"> Open Positions </h2>
                        <p class="color-666 mt-3 wow fadeInUp" data-wow-delay="0.2s"> Explore current job opportunities and find your perfect role </p>
                    </div>
                    
                    <div class="jobs-list">
                        <?php if (count($active_jobs) > 0): ?>
                            <?php 
                            $delay = 0.2;
                            foreach ($active_jobs as $job): 
                                // Calculate days since posted
                                $posted_date = new DateTime($job['posted_date']);
                                $today = new DateTime();
                                $days_ago = $today->diff($posted_date)->days;
                                
                                if ($days_ago == 0) {
                                    $posted_text = 'Posted today';
                                } elseif ($days_ago == 1) {
                                    $posted_text = 'Posted yesterday';
                                } elseif ($days_ago < 7) {
                                    $posted_text = 'Posted ' . $days_ago . ' days ago';
                                } elseif ($days_ago < 14) {
                                    $posted_text = 'Posted 1 week ago';
                                } else {
                                    $weeks = floor($days_ago / 7);
                                    $posted_text = 'Posted ' . $weeks . ' weeks ago';
                                }
                                
                                // Split requirements and responsibilities by pipe separator
                                $requirements = explode('|', $job['requirements']);
                                $responsibilities = !empty($job['responsibilities']) ? explode('|', $job['responsibilities']) : [];
                            ?>
                            <div class="job-card bg-white p-5 mb-4 radius-4 shadow-sm wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <h4 class="fw-600 mb-2"><?php echo htmlspecialchars($job['title']); ?></h4>
                                        <div class="job-meta mb-3">
                                            <span class="badge bg-orange1 text-white me-2"><?php echo htmlspecialchars($job['employment_type']); ?></span>
                                            <span class="color-666 me-3">
                                                <i class="la la-map-marker me-1"></i><?php echo htmlspecialchars($job['location']); ?>
                                            </span>
                                            <span class="color-666">
                                                <i class="la la-clock me-1"></i><?php echo $posted_text; ?>
                                            </span>
                                            <?php if (!empty($job['salary_range'])): ?>
                                                <span class="color-666 ms-2">
                                                    <i class="la la-money-bill me-1"></i><?php echo htmlspecialchars($job['salary_range']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="color-666 mb-3">
                                            <?php echo htmlspecialchars($job['description']); ?>
                                        </p>
                                        
                                        <?php if (count($requirements) > 0): ?>
                                            <div class="requirements mb-3">
                                                <h6 class="fw-600 mb-2">Requirements:</h6>
                                                <ul class="list-unstyled fsz-14 color-666">
                                                    <?php foreach ($requirements as $req): ?>
                                                        <?php if (trim($req)): ?>
                                                            <li><i class="la la-check me-2 color-orange1"></i><?php echo htmlspecialchars(trim($req)); ?></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (count($responsibilities) > 0): ?>
                                            <div class="responsibilities">
                                                <h6 class="fw-600 mb-2">Responsibilities:</h6>
                                                <ul class="list-unstyled fsz-14 color-666">
                                                    <?php foreach ($responsibilities as $resp): ?>
                                                        <?php if (trim($resp)): ?>
                                                            <li><i class="la la-arrow-right me-2 color-orange1"></i><?php echo htmlspecialchars(trim($resp)); ?></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($job['application_deadline'])): ?>
                                            <div class="alert alert-warning mt-3 p-2 fsz-14">
                                                <i class="la la-calendar me-1"></i>
                                                Application deadline: <?php echo date('M d, Y', strtotime($job['application_deadline'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                        <a href="#apply-form" class="butn rounded-pill bg-orange1 text-white hover-bg-black">
                                            <span> Apply Now <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                $delay += 0.1;
                            endforeach; 
                            ?>
                        <?php else: ?>
                            <!-- No jobs available message -->
                            <div class="text-center py-5">
                                <i class="la la-briefcase text-gray-300" style="font-size: 80px; color: #ddd;"></i>
                                <h5 class="mt-4 color-666">No Open Positions Currently Available</h5>
                                <p class="color-666 mt-2">Please check back later or submit your application for future opportunities.</p>
                                <a href="#apply-form" class="butn rounded-pill bg-orange1 text-white hover-bg-black mt-4">
                                    <span> Submit General Application <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <img src="assets/img/home1/c_line2.png" alt="" class="c-line wow">
            </section>
            <!--  End open positions  -->


            <!--  Start application form  -->
            <section class="tc-chat-style1 section-padding" id="apply-form">
                <div class="container">
                    <div class="content">
                        <h2 class="fsz-45 mb-30 wow fadeInUp"> Apply for a Position </h2>
                        <p class="color-666 mb-50 wow fadeInUp" data-wow-delay="0.2s"> Ready to join our team? Fill out the application form below and we'll get back to you soon. </p>
                        
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <form class="application-form wow fadeInUp" data-wow-delay="0.3s" method="POST" action="career-handler.php" enctype="multipart/form-data">
                                    <input type="hidden" name="career_application" value="1">
                                    
                                    <div class="row gx-4">
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Full Name *</label>
                                            <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Email Address *</label>
                                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Phone Number *</label>
                                            <input type="tel" name="phone" class="form-control" placeholder="+256 700 000 000" required>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Position Applied For *</label>
                                            <select name="position" class="form-control" required>
                                                <option value="">Select Position</option>
                                                <?php foreach ($active_jobs as $job): ?>
                                                    <option value="<?php echo htmlspecialchars($job['title']); ?>">
                                                        <?php echo htmlspecialchars($job['title']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                                <option value="Other">Other / General Application</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Cover Letter *</label>
                                            <textarea name="cover_letter" class="form-control" rows="5" placeholder="Tell us why you're interested in this position and what you can bring to our team..." required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Resume/CV *</label>
                                            <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                                            <small class="text-muted">PDF, DOC, or DOCX files only (max 5MB)</small>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fsz-14 color-666 mb-2">Portfolio (Optional)</label>
                                            <input type="file" name="portfolio" class="form-control" accept=".pdf,.zip,.rar">
                                            <small class="text-muted">PDF or ZIP files only (max 10MB)</small>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="butn rounded-pill bg-orange1 text-white hover-bg-black w-100">
                                                <span> Submit Application <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                                <div id="applicationMessage" class="mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="assets/img/home1/c_line4.png" alt="" class="c-line wow">
            </section>
            <!--  End application form  -->

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
                                    <li> <a href="careers.html"> Careers </a> </li>
                                    <li> <a href="contact.php"> Contact Us </a> </li>
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
                                <a href="contact.php"> Contact </a>
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

    <!-- Career application form handler -->
    <script>
        $(document).ready(function() {
            $('.application-form').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<span><i class="la la-spinner la-spin me-2"></i>Submitting...</span>').prop('disabled', true);
                
                // Create FormData for file uploads
                const formData = new FormData(this);
                
                $.ajax({
                    url: 'career-handler.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#applicationMessage').html('<div class="alert alert-success border-l-4 border-green-500 p-4 mb-4"><i class="la la-check-circle me-2"></i>' + response.message + '</div>');
                            $('.application-form')[0].reset();
                        } else {
                            $('#applicationMessage').html('<div class="alert alert-danger border-l-4 border-red-500 p-4 mb-4"><i class="la la-exclamation-circle me-2"></i>' + response.message + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Sorry, there was an error submitting your application. Please try again or email us directly at info@flipavenueltd.com';
                        
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                        } catch (e) {
                            // Use default error message
                        }
                        
                        $('#applicationMessage').html('<div class="alert alert-danger border-l-4 border-red-500 p-4 mb-4"><i class="la la-exclamation-circle me-2"></i>' + errorMessage + '</div>');
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.html(originalText).prop('disabled', false);
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $('#applicationMessage').offset().top - 100
                        }, 500);
                    }
                });
            });
            
            // Clear messages when user starts typing
            $('.application-form input, .application-form textarea, .application-form select').on('focus', function() {
                $('#applicationMessage').html('');
            });
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

        // Update cart count via AJAX
        function updateCartCount() {
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
                if (data.success) {
                    const cartCountElement = document.getElementById('cartCount');
                    if (cartCountElement) {
                        cartCountElement.innerHTML = data.data.cart_count;
                        cartCountElement.offsetHeight;
                    }
                }
            })
            .catch(error => {
                console.error('Cart count update error:', error);
            });
        }

        // Initialize cart count on page load
        updateCartCount();
    </script>

</body>
</html>

