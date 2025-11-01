<?php
// Include centralized database connection
require_once 'cms/db_connect.php';
// Include common helper functions
require_once 'common/functions.php';

// Get category filter
$category_filter = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Fetch projects
$where_clause = "WHERE is_active = 1";
if ($category_filter) {
    $where_clause .= " AND category = '$category_filter'";
}

// Fetch all active projects for tab filtering
$projects_query = "SELECT * FROM projects WHERE is_active = 1 ORDER BY display_order ASC, created_at DESC";
$all_projects_result = $conn->query($projects_query);

// Store all projects in an array for easy filtering
$all_projects = [];
if ($all_projects_result) {
    while ($project = $all_projects_result->fetch_assoc()) {
        $all_projects[] = $project;
    }
}

// Fetch categories for filter
$categories_query = "SELECT DISTINCT category FROM projects WHERE is_active = 1 AND category != '' ORDER BY category";
$categories_result = $conn->query($categories_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="keywords" content="FlipAvenue, Architecture Portfolio, Projects, Uganda">
    <meta name="description" content="Explore FlipAvenue's portfolio of architectural projects including residential, commercial, and landscape designs.">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title>Portfolio - Our Projects | FlipAvenue Limited</title>

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
                    <li> <a href="portfolio.html" class="main_link"> projects </a> </li>
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
                            <a class="nav-link active" aria-current="page" href="portfolio.html">Projects</a>
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
        <header class="tc-header-style1 portfolio-header" style="min-height: 12vh;">
            <div class="img">
                <img src="assets/img/home1/head_slide1.jpg" alt="" class="img-cover">
            </div>
            
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start projects  -->
            <section class="tc-projects-style1 section-padding">
                <div class="container">
                    <div class="title mb-70">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h2 class="fsz-45 wow fadeInUp"> Featured Projects </h2>
                                <p class="color-666 mt-3 wow fadeInUp" data-wow-delay="0.2s">Discover our collection of award-winning architectural projects</p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                <div class="text fsz-14 color-666">
                                    <span class="fw-600">126+</span> Projects Completed
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabs-links mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link active" id="pills-proj1-tab" data-bs-toggle="pill" data-bs-target="#pills-proj1" type="button">Featured Projects</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link" id="pills-proj3-tab" data-bs-toggle="pill" data-bs-target="#pills-proj3" type="button">Interior</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link" id="pills-proj2-tab" data-bs-toggle="pill" data-bs-target="#pills-proj2" type="button">Architecture</button>
                                    </li>                                    
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-proj4-tab" data-bs-toggle="pill" data-bs-target="#pills-proj4" type="button">Landscape</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-proj5-tab" data-bs-toggle="pill" data-bs-target="#pills-proj5" type="button">Furniture</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="projects">
                        <div class="tab-content" id="pills-tabContent">
                            <!-- All Projects Tab -->
                            <div class="tab-pane fade show active" id="pills-proj1" role="tabpanel">
                                <div class="row gx-4 gy-4">
                                    <?php 
                                    if (!empty($all_projects)): 
                                        $delay = 0.1;
                                        foreach ($all_projects as $project): 
                                    // Get featured image or skip if none
                                    $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                    if (empty($featured_image)) continue;
                                ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="project-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                        <a href="project-details.php?id=<?php echo $project['id']; ?>" class="img">
                                            <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                        </a>
                                        <div class="info mt-3">
                                            <div class="tags mb-2">
                                                <span class="fsz-12"><?php echo htmlspecialchars($project['category']); ?></span>
                                                <?php if (!empty($project['location'])): ?>
                                                    <span class="fsz-12"><?php echo htmlspecialchars($project['location']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="title fsz-20 mb-2"><a href="project-details.php?id=<?php echo $project['id']; ?>" class="hover-orange1"><?php echo htmlspecialchars($project['title']); ?></a></h3>
                                            <div class="text color-666 fsz-14">
                                                <?php echo htmlspecialchars($project['short_description']); ?>
                                            </div>
                                            <?php if (!empty($project['client_name'])): ?>
                                                <div class="text color-999 fsz-12 mt-2">
                                                    Client: <?php echo htmlspecialchars($project['client_name']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                        <?php 
                                            $delay += 0.1;
                                        endforeach; 
                                    else: ?>
                                    <div class="col-12">
                                        <p class="text-center text-gray-500 py-5">No projects found</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Architecture Tab -->
                            <div class="tab-pane fade" id="pills-proj2" role="tabpanel">
                                <div class="row gx-4 gy-4">
                                    <?php 
                                    $arch_projects = array_filter($all_projects, function($p) { return strtolower($p['category']) == 'architecture'; });
                                    if (!empty($arch_projects)): 
                                        $delay = 0.1;
                                        foreach ($arch_projects as $project): 
                                            // Get featured image or skip if none
                                            $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                            if (empty($featured_image)) continue;
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="project-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                            <a href="project-details.php?id=<?php echo $project['id']; ?>" class="img">
                                                <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                            </a>
                                            <div class="info mt-3">
                                                <div class="tags mb-2">
                                                    <span class="fsz-12"><?php echo htmlspecialchars($project['category']); ?></span>
                                                    <?php if (!empty($project['location'])): ?>
                                                        <span class="fsz-12"><?php echo htmlspecialchars($project['location']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="title fsz-20 mb-2"><a href="project-details.php?id=<?php echo $project['id']; ?>" class="hover-orange1"><?php echo htmlspecialchars($project['title']); ?></a></h3>
                                                <div class="text color-666 fsz-14">
                                                    <?php echo htmlspecialchars($project['short_description']); ?>
                                                </div>
                                                <?php if (!empty($project['client_name'])): ?>
                                                    <div class="text color-999 fsz-12 mt-2">
                                                        Client: <?php echo htmlspecialchars($project['client_name']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        $delay += 0.1;
                                    endforeach; 
                                else: ?>
                                    <div class="col-12">
                                        <p class="text-center text-gray-500 py-5">No architecture projects found</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Interior Tab -->
                            <div class="tab-pane fade" id="pills-proj3" role="tabpanel">
                                <div class="row gx-4 gy-4">
                                    <?php 
                                    $interior_projects = array_filter($all_projects, function($p) { return strtolower($p['category']) == 'interior'; });
                                    if (!empty($interior_projects)): 
                                        $delay = 0.1;
                                        foreach ($interior_projects as $project): 
                                            // Get featured image or skip if none
                                            $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                            if (empty($featured_image)) continue;
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="project-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                            <a href="project-details.php?id=<?php echo $project['id']; ?>" class="img">
                                                <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                            </a>
                                            <div class="info mt-3">
                                                <div class="tags mb-2">
                                                    <span class="fsz-12"><?php echo htmlspecialchars($project['category']); ?></span>
                                                    <?php if (!empty($project['location'])): ?>
                                                        <span class="fsz-12"><?php echo htmlspecialchars($project['location']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="title fsz-20 mb-2"><a href="project-details.php?id=<?php echo $project['id']; ?>" class="hover-orange1"><?php echo htmlspecialchars($project['title']); ?></a></h3>
                                                <div class="text color-666 fsz-14">
                                                    <?php echo htmlspecialchars($project['short_description']); ?>
                                                </div>
                                                <?php if (!empty($project['client_name'])): ?>
                                                    <div class="text color-999 fsz-12 mt-2">
                                                        Client: <?php echo htmlspecialchars($project['client_name']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        $delay += 0.1;
                                    endforeach; 
                                else: ?>
                                    <div class="col-12">
                                        <p class="text-center text-gray-500 py-5">No interior projects found</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Landscape Tab -->
                            <div class="tab-pane fade" id="pills-proj4" role="tabpanel">
                                <div class="row gx-4 gy-4">
                                    <?php 
                                    $landscape_projects = array_filter($all_projects, function($p) { return strtolower($p['category']) == 'landscape'; });
                                    if (!empty($landscape_projects)): 
                                        $delay = 0.1;
                                        foreach ($landscape_projects as $project): 
                                            // Get featured image or skip if none
                                            $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                            if (empty($featured_image)) continue;
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="project-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                            <a href="project-details.php?id=<?php echo $project['id']; ?>" class="img">
                                                <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                            </a>
                                            <div class="info mt-3">
                                                <div class="tags mb-2">
                                                    <span class="fsz-12"><?php echo htmlspecialchars($project['category']); ?></span>
                                                    <?php if (!empty($project['location'])): ?>
                                                        <span class="fsz-12"><?php echo htmlspecialchars($project['location']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="title fsz-20 mb-2"><a href="project-details.php?id=<?php echo $project['id']; ?>" class="hover-orange1"><?php echo htmlspecialchars($project['title']); ?></a></h3>
                                                <div class="text color-666 fsz-14">
                                                    <?php echo htmlspecialchars($project['short_description']); ?>
                                                </div>
                                                <?php if (!empty($project['client_name'])): ?>
                                                    <div class="text color-999 fsz-12 mt-2">
                                                        Client: <?php echo htmlspecialchars($project['client_name']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        $delay += 0.1;
                                    endforeach; 
                                else: ?>
                                    <div class="col-12">
                                        <p class="text-center text-gray-500 py-5">No landscape projects found</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Furniture Tab -->
                            <div class="tab-pane fade" id="pills-proj5" role="tabpanel">
                                <div class="row gx-4 gy-4">
                                    <?php 
                                    $furniture_projects = array_filter($all_projects, function($p) { return strtolower($p['category']) == 'furniture'; });
                                    if (!empty($furniture_projects)): 
                                        $delay = 0.1;
                                        foreach ($furniture_projects as $project): 
                                            // Get featured image or skip if none
                                            $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                            if (empty($featured_image)) continue;
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="project-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                            <a href="<?php echo htmlspecialchars($featured_image); ?>" class="img" data-fancybox="projects">
                                                <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                            </a>
                                            <div class="info mt-3">
                                                <div class="tags mb-2">
                                                    <span class="fsz-12"><?php echo htmlspecialchars($project['category']); ?></span>
                                                    <?php if (!empty($project['location'])): ?>
                                                        <span class="fsz-12"><?php echo htmlspecialchars($project['location']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="title fsz-20 mb-2"><a href="#" class="hover-orange1"><?php echo htmlspecialchars($project['title']); ?></a></h3>
                                                <div class="text color-666 fsz-14">
                                                    <?php echo htmlspecialchars($project['short_description']); ?>
                                                </div>
                                                <?php if (!empty($project['client_name'])): ?>
                                                    <div class="text color-999 fsz-12 mt-2">
                                                        Client: <?php echo htmlspecialchars($project['client_name']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        $delay += 0.1;
                                    endforeach; 
                                else: ?>
                                    <div class="col-12">
                                        <p class="text-center text-gray-500 py-5">No furniture projects found</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End projects  -->


            <!--  Start CTA  -->
            <section class="tc-chat-style1">
                <div class="container">
                    <div class="content">
                        <h5 class="mb-30 lh-5"> Have a project in mind? </h5>
                        <a href="contact.php" class="butn border rounded-pill hover-bg-orange1">
                            <span> Start Your Project <i class="small ms-1 ti-arrow-top-right"></i> </span>
                        </a>
                    </div>
                </div>
                <img src="assets/img/home1/c_line4.png" alt="" class="c-line wow">
            </section>
            <!--  End CTA  -->

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
                                    <li> <a href="mailto:info@flipavenueltd.com"> info@flipavenueltd.com </a> </li>
                                    <li> <a href="tel:+256701380251"> +256 701380251 / 783370967 </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 fw-600"> Other links </h5>
                                <ul class="footer-links">
                                    <li> <a href="#"> Shop </a> </li>
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
                                    <li> <a href="careers.php"> Careers </a> </li>
                                    <li> <a href="contact.php"> Contact Us </a> </li>
                                    <li> <a href="#"> Help </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 fw-600"> Legal </h5>
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

