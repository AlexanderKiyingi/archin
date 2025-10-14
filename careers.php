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
        <header class="tc-header-style1" style="min-height: 50vh;">
            <div class="img">
                <img src="assets/img/home1/head_slide1.jpg" alt="" class="img-cover">
            </div>
            <div class="info section-padding-x pb-70">
                <div class="row align-items-end gx-5">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <h1 class="js-title wow fadeInUp"> Join Our Team </h1>
                        <h5 class="fsz-30 mt-30 fw-400 wow fadeInUp" data-wow-delay="0.2s"> Build Your Career in <br> Architecture & Design </h5>
                    </div>
                </div>
            </div>
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start careers intro  -->
            <section class="tc-experience-style1 section-padding-x">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="info wow fadeInUp">
                                <h2 class="fsz-45 fw-600 mb-30"> Why Work With Us? </h2>
                                <p class="text fsz-16 color-666 mb-40">
                                    At FlipAvenue Limited, we believe that great architecture comes from great people. Join our passionate team of architects, designers, and innovators who are shaping the future of built environments in East Africa.
                                </p>
                                <p class="text fsz-16 color-666">
                                    We offer competitive compensation, professional development opportunities, and the chance to work on exciting projects that make a real difference in communities.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="assets/img/home1/c_line.png" alt="" class="c-line wow">
            </section>
            <!--  End careers intro  -->


            <!--  Start benefits  -->
            <section class="tc-services-style1">
                <div class="content section-padding section-padding-x">
                    <div class="container">
                        <div class="title mb-80 text-center">
                            <h2 class="fsz-45 mb-30 wow fadeInUp"> Employee Benefits </h2>
                            <p class="color-666 wow fadeInUp" data-wow-delay="0.2s"> We take care of our team with comprehensive benefits and perks </p>
                        </div>
                        <div class="row gx-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="benefit-card text-center p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="icon mb-3">
                                        <i class="la la-money-bill-wave fsz-50 color-orange1"></i>
                                    </div>
                                    <h5 class="fsz-20 fw-600 mb-3">Competitive Salary</h5>
                                    <p class="color-666 fsz-14">Market-competitive compensation packages with performance bonuses</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="benefit-card text-center p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="icon mb-3">
                                        <i class="la la-graduation-cap fsz-50 color-orange1"></i>
                                    </div>
                                    <h5 class="fsz-20 fw-600 mb-3">Professional Development</h5>
                                    <p class="color-666 fsz-14">Training programs, conferences, and skill enhancement opportunities</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="benefit-card text-center p-4 mb-4 wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="icon mb-3">
                                        <i class="la la-heart fsz-50 color-orange1"></i>
                                    </div>
                                    <h5 class="fsz-20 fw-600 mb-3">Health Insurance</h5>
                                    <p class="color-666 fsz-14">Comprehensive health coverage for you and your family</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="benefit-card text-center p-4 mb-4 wow fadeInUp" data-wow-delay="0.5s">
                                    <div class="icon mb-3">
                                        <i class="la la-clock fsz-50 color-orange1"></i>
                                    </div>
                                    <h5 class="fsz-20 fw-600 mb-3">Flexible Hours</h5>
                                    <p class="color-666 fsz-14">Work-life balance with flexible scheduling options</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End benefits  -->


            <!--  Start open positions  -->
            <section class="tc-process-style1 section-padding">
                <div class="container">
                    <div class="title mb-80 text-center">
                        <h2 class="fsz-45 wow fadeInUp"> Open Positions </h2>
                        <p class="color-666 mt-3 wow fadeInUp" data-wow-delay="0.2s"> Explore current job opportunities and find your perfect role </p>
                    </div>
                    
                    <div class="jobs-list">
                        <!-- Senior Architect -->
                        <div class="job-card bg-white p-5 mb-4 radius-4 shadow-sm wow fadeInUp" data-wow-delay="0.2s">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h4 class="fw-600 mb-2">Senior Architect</h4>
                                    <div class="job-meta mb-3">
                                        <span class="badge bg-orange1 text-white me-2">Full-time</span>
                                        <span class="color-666 me-3"><i class="la la-map-marker me-1"></i>Kampala, Uganda</span>
                                        <span class="color-666"><i class="la la-clock me-1"></i>Posted 2 days ago</span>
                                    </div>
                                    <p class="color-666 mb-3">
                                        Lead architectural projects from concept to completion. Minimum 8 years experience in residential and commercial design. Proficiency in AutoCAD, Revit, and 3D modeling required.
                                    </p>
                                    <div class="requirements">
                                        <h6 class="fw-600 mb-2">Requirements:</h6>
                                        <ul class="list-unstyled fsz-14 color-666">
                                            <li><i class="la la-check me-2 color-orange1"></i>Bachelor's degree in Architecture</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>8+ years professional experience</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Proficiency in AutoCAD, Revit, SketchUp</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Strong project management skills</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                    <a href="#apply-form" class="butn rounded-pill bg-orange1 text-white hover-bg-black">
                                        <span> Apply Now <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Interior Designer -->
                        <div class="job-card bg-white p-5 mb-4 radius-4 shadow-sm wow fadeInUp" data-wow-delay="0.3s">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h4 class="fw-600 mb-2">Interior Designer</h4>
                                    <div class="job-meta mb-3">
                                        <span class="badge bg-orange1 text-white me-2">Full-time</span>
                                        <span class="color-666 me-3"><i class="la la-map-marker me-1"></i>Kampala, Uganda</span>
                                        <span class="color-666"><i class="la la-clock me-1"></i>Posted 1 week ago</span>
                                    </div>
                                    <p class="color-666 mb-3">
                                        Create stunning interior spaces for residential and commercial clients. Work with clients to understand their vision and bring it to life through innovative design solutions.
                                    </p>
                                    <div class="requirements">
                                        <h6 class="fw-600 mb-2">Requirements:</h6>
                                        <ul class="list-unstyled fsz-14 color-666">
                                            <li><i class="la la-check me-2 color-orange1"></i>Degree in Interior Design or related field</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>3+ years design experience</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Proficiency in 3D modeling software</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Strong communication skills</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                    <a href="#apply-form" class="butn rounded-pill bg-orange1 text-white hover-bg-black">
                                        <span> Apply Now <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Project Manager -->
                        <div class="job-card bg-white p-5 mb-4 radius-4 shadow-sm wow fadeInUp" data-wow-delay="0.4s">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h4 class="fw-600 mb-2">Project Manager</h4>
                                    <div class="job-meta mb-3">
                                        <span class="badge bg-orange1 text-white me-2">Full-time</span>
                                        <span class="color-666 me-3"><i class="la la-map-marker me-1"></i>Kampala, Uganda</span>
                                        <span class="color-666"><i class="la la-clock me-1"></i>Posted 3 days ago</span>
                                    </div>
                                    <p class="color-666 mb-3">
                                        Oversee construction projects from planning to completion. Coordinate with clients, contractors, and design team to ensure projects are delivered on time and within budget.
                                    </p>
                                    <div class="requirements">
                                        <h6 class="fw-600 mb-2">Requirements:</h6>
                                        <ul class="list-unstyled fsz-14 color-666">
                                            <li><i class="la la-check me-2 color-orange1"></i>Bachelor's degree in Construction Management</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>5+ years project management experience</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>PMP certification preferred</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Strong leadership and communication skills</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                    <a href="#apply-form" class="butn rounded-pill bg-orange1 text-white hover-bg-black">
                                        <span> Apply Now <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Junior Architect -->
                        <div class="job-card bg-white p-5 mb-4 radius-4 shadow-sm wow fadeInUp" data-wow-delay="0.5s">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h4 class="fw-600 mb-2">Junior Architect</h4>
                                    <div class="job-meta mb-3">
                                        <span class="badge bg-orange1 text-white me-2">Full-time</span>
                                        <span class="color-666 me-3"><i class="la la-map-marker me-1"></i>Kampala, Uganda</span>
                                        <span class="color-666"><i class="la la-clock me-1"></i>Posted 1 week ago</span>
                                    </div>
                                    <p class="color-666 mb-3">
                                        Entry-level position for recent graduates. Work alongside senior architects on exciting projects while developing your skills and building your portfolio.
                                    </p>
                                    <div class="requirements">
                                        <h6 class="fw-600 mb-2">Requirements:</h6>
                                        <ul class="list-unstyled fsz-14 color-666">
                                            <li><i class="la la-check me-2 color-orange1"></i>Bachelor's degree in Architecture</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>0-2 years experience</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Basic knowledge of AutoCAD, SketchUp</li>
                                            <li><i class="la la-check me-2 color-orange1"></i>Strong portfolio and passion for design</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                    <a href="#apply-form" class="butn rounded-pill bg-orange1 text-white hover-bg-black">
                                        <span> Apply Now <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                                <option value="Senior Architect">Senior Architect</option>
                                                <option value="Interior Designer">Interior Designer</option>
                                                <option value="Project Manager">Project Manager</option>
                                                <option value="Junior Architect">Junior Architect</option>
                                                <option value="Other">Other</option>
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

</body>
</html>

