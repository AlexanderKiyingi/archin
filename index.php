<?php
// Include centralized database connection
require_once 'cms/db_connect.php';
// Include common helper functions
require_once 'common/functions.php';

// Fetch services (active only, ordered by display_order)
$services_query = "SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC, created_at DESC LIMIT 8";
$services_result = $conn->query($services_query);

// Fetch featured projects
$projects_query = "SELECT * FROM projects WHERE is_active = 1 AND is_featured = 1 ORDER BY display_order ASC LIMIT 6";
$projects_result = $conn->query($projects_query);

// Fetch all active projects for dynamic filtering
$all_projects_query = "SELECT * FROM projects WHERE is_active = 1 ORDER BY display_order ASC";
$all_projects_result = $conn->query($all_projects_query);

// Fetch project categories
$project_categories_query = "SELECT * FROM project_categories WHERE is_active = 1 ORDER BY display_order ASC";
$project_categories_result = $conn->query($project_categories_query);

// Fetch team members
$team_query = "SELECT * FROM team_members WHERE is_active = 1 ORDER BY display_order ASC LIMIT 6";
$team_result = $conn->query($team_query);

// Fetch testimonials (all active testimonials)
$testimonials_query = "SELECT * FROM testimonials WHERE is_active = 1 ORDER BY display_order ASC";
$testimonials_result = $conn->query($testimonials_query);

// Fetch awards
$awards_query = "SELECT * FROM awards WHERE is_active = 1 ORDER BY year DESC, display_order ASC LIMIT 4";
$awards_result = $conn->query($awards_query);

// Fetch latest blog posts
$blog_query = "SELECT bp.*, au.full_name as author_name 
               FROM blog_posts bp 
               LEFT JOIN admin_users au ON bp.author_id = au.id 
               WHERE bp.is_published = 1 
               ORDER BY bp.publish_date DESC 
               LIMIT 3";
$blog_result = $conn->query($blog_query);

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

// Set SEO meta data
$page_title = 'Home | Flip Avenue Limited - Interior Architecture & Design Studio';
$page_keywords = 'Flip Avenue Limted, Interior Design, Architecture, Design, Construction, Uganda, Kampala, Architectural Services';
$page_description = 'Flip Avenue Limited is an interior design studio based in Uganda. We cut our teeth on designing and creating buildings that are both beautiful and sustainable.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title><?php echo $page_title; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/home1/fav.png" title="Favicon" sizes="16x16">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/home1/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/home1/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/home1/logo.png">
    
    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'; ?>://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:image" content="<?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'; ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/home1/logo.png">
    <meta property="og:site_name" content="Flip Avenue Limited">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'; ?>://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta name="twitter:title" content="<?php echo $page_title; ?>">
    <meta name="twitter:description" content="<?php echo $page_description; ?>">
    <meta name="twitter:image" content="<?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'; ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/home1/logo.png">
    
    <!-- Additional Meta Tags -->
    <meta name="theme-color" content="#ff9900">

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
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
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

        
        <!--  Start header  -->
        <header class="tc-header-style1">
            <div class="header-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="slider-card">
                            <div class="img">
                                <img src="assets/img/home1/head_slide1.jpg" alt="" class="img-cover">
                            </div>
                            <div class="info section-padding-x pb-70">
                                <div class="row align-items-end gx-5">
                                    <div class="col-lg-7 offset-lg-2">
                                        <h1 data-swiper-parallax="-2000" class="js-title"> Flip Avenue <br>Limited</br> </h1>
                                        <h5 class="fsz-30 mt-30 fw-400"> Interior Design <br> Studio </h5>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="cont pb-30">
                                            <div class="text fsz-17 fw-300 lh-4">
                                                We collaborate with clients to create buildings and environments in dialogue with culture and place. And become one of the country's leading Interior design studio
                                            </div>
                                            <a href="#" class="butn border rounded-pill mt-60 hover-bg-white">
                                                <span> Our Services <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="slider-card">
                            <div class="img">
                                <img src="assets/img/home1/head_slide2.jpg" alt="" class="img-cover">
                            </div>
                            <div class="info section-padding-x pb-70">
                                <div class="row align-items-end gx-5">
                                    <div class="col-lg-6 offset-lg-2">
                                        <h1 data-swiper-parallax="-2000" class="js-title"> Material </h1>
                                        <h5 class="fsz-30 mt-30 fw-400"> Sustainable <br> Resource </h5>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="cont pb-30">
                                            <div class="text fsz-17 fw-300 lh-4">
                                                We collaborate with clients to create buildings and environments in dialogue with culture and place. And become one of the country's leading Interior design studio
                                            </div>
                                            <a href="#" class="butn border rounded-pill mt-60 hover-bg-white">
                                                <span> Our Services <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="slider-card">
                            <div class="img">
                                <img src="assets/img/home1/head_slide3.jpg" alt="" class="img-cover">
                            </div>
                            <div class="info section-padding-x pb-70">
                                <div class="row align-items-end gx-5">
                                    <div class="col-lg-6 offset-lg-2">
                                        <h1 data-swiper-parallax="-2000" class="js-title"> Design </h1>
                                        <h5 class="fsz-30 mt-30 fw-400"> Biophilic design <br> integration </h5>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="cont pb-30">
                                            <div class="text fsz-17 fw-300 lh-4">
                                                We collaborate with clients to create buildings and environments in dialogue with culture and place. And become one of the country's leading Interior design studio
                                            </div>
                                            <a href="#" class="butn border rounded-pill mt-60 hover-bg-white">
                                                <span> Our Services <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider-controls">
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </header>
        <!--  Start header  -->


        <!--Contents-->
        <main>


            <!--  Start experience  -->
            <section class="tc-experience-style1 section-padding-x">
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-2">
                            <div class="exp-num mb-100 wow zoomIn" data-wow-delay="0.3s">
                                <h5 class="fsz-18 text-uppercase"> years of <br> experience </h5>
                                <h2 class="num"> 15 </h2>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="img wow">
                                <img src="assets/img/home1/exp.png" alt="" class="img-cover">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info wow fadeInUp" data-wow-delay="0.3s">   
                                <h3 class="fsz-45 fw-600 mb-30"> 126+ Projects Completed </h3>
                                <div class="text fsz-15 color-666">
                                    Flip Avenue Limited is not merely an interior design company; it's an embodiment of artistry, functionality, and innovation in 
                                    transforming spaces. Our journey is defined by a commitment to excellence, blending aesthetics with practicality, and crafting unique 
                                    living environments that reflect our clients' distinct lifestyles.
                                </div>
                                <a href="about.html" class="butn rounded-pill mt-50 hover-bg-black bg-white">
                                    <span> Our Studio <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="rotate-txt justify-content-lg-end">
                                <ul>
                                    <li>
                                        <a href="#"> info@flipavenueltd.com </a>
                                    </li>
                                    <li>
                                        <a href="#"> +256 701380251 / 783370967 </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="assets/img/home1/c_line.png" alt="" class="c-line wow">
            </section>


            <!--  Start services  -->
            <section class="tc-services-style1 services-with-overlay">
                <div class="content section-padding section-padding-x">
                    <div class="container">
                        <div class="title mb-80 text-center">
                            <p class="color-666 text-uppercase wow"> our services </p>
                        </div>
                        <div class="services">
                            <div class="row">
                                <?php if ($services_result && $services_result->num_rows > 0): ?>
                                    <?php 
                                    $delay = 0.2;
                                    $count = 0;
                                    while ($service = $services_result->fetch_assoc()): 
                                    $mt_class = ($count % 2 == 1) ? 'mt-150' : '';
                                    
                                    // Handle service image with proper fallback
                                    $service_image = '';
                                    if (!empty($service['image'])) {
                                        // Clean the image path and ensure it exists
                                        $image_path = str_replace('../', '', $service['image']);
                                        $full_path = 'cms/' . $image_path;
                                        if (file_exists($full_path)) {
                                            $service_image = $full_path;
                                        }
                                    }
                                    
                                    // Use fallback image if no service image or file doesn't exist
                                    if (empty($service_image)) {
                                        $fallback_images = [
                                            'assets/img/home1/services/ser1.jpg',
                                            'assets/img/home1/services/ser2.jpg',
                                            'assets/img/home1/services/ser3.jpg',
                                            'assets/img/home1/services/ser4.jpg'
                                        ];
                                        $service_image = $fallback_images[$count % count($fallback_images)];
                                    }
                                    ?>
                                    <div class="col-lg-3">
                                        <a href="services.php?id=<?php echo $service['id']; ?>" class="service-card <?php echo $mt_class; ?> wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                            <div class="icon">
                                                <i class="<?php echo htmlspecialchars($service['icon'] ?: 'la la-cube'); ?>"></i>
                                            </div>
                                            <h5 class="fsz-24 mb-20"><?php echo htmlspecialchars($service['title']); ?></h5>
                                            <div class="img">
                                                <img src="<?php echo htmlspecialchars($service_image); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="img-cover" loading="lazy" onerror="this.src='assets/img/home1/services/ser1.jpg'">
                                            </div>
                                            <div class="text color-666 mt-50">
                                                <?php echo htmlspecialchars($service['description']); ?>
                                            </div>
                                            <span class="arrow"> <i class="ti-arrow-top-right"></i> </span>
                                        </a>
                                    </div>
                                    <?php 
                                    $delay += 0.2;
                                    $count++;
                                    endwhile; 
                                    ?>
                                <?php else: ?>
                                    <div class="col-12 text-center py-5">
                                        <p class="color-666">No services available at the moment.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="contact.php" class="butn rounded-pill mt-80 hover-bg-black bg-orange1 text-white">
                                <span> Book a Site Visit Now <i class="small ms-1 ti-arrow-top-right"></i> </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ser-img">
                    <img src="assets/img/home1/services/ser.png" alt="">
                </div>
            </section>
            <!--  End services  -->


            <!--  Start process  -->
            <section class="tc-process-style1">
                <div class="container">
                    <div class="title mb-100 text-center">
                        <h2 class="fsz-45"> Our Process Work </h2>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="info">
                                    <div class="accordion wow fadeInUp slow" id="accordionProcess">
                                        <div class="accordion-item">
                                          <div class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                                <span class="num"> 1 / </span> <h3> Site Visit </h3>
                                            </button>
                                          </div>
                                          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionProcess">
                                            <div class="accordion-body">
                                                <div class="text">
                                                    This vital first step covers preliminary consultations and allows for essential groundwork, including potential future visits with various contractors (like carpenters or plumbers) even before the design phase. You'll gain valuable insights into your project's potential, with the flexibility to use this information to proceed independently or continue with us.
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="accordion-item">
                                          <div class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                                <span class="num"> 2 / </span> <h3> Comprehensive 3D Design & Detailed Costing </h3>
                                            </button>
                                          </div>
                                          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionProcess">
                                            <div class="accordion-body">
                                                <div class="text">
                                                    This crucial stage brings your vision to life. We provide realistic 3D visualizations of the proposed design, complete with a meticulous breakdown of costs for each element. This transparency empowers you to make informed decisions and collaborate on revisions, ensuring the final design perfectly aligns with your aesthetic and budget. All measurements and specifications are precisely documented, allowing you the option to execute the work independently if you choose.
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="accordion-item">
                                          <div class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                                <span class="num"> 3 / </span> <h3> Professional Execution </h3>
                                            </button>
                                          </div>
                                          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionProcess">
                                            <div class="accordion-body">
                                                <div class="text">
                                                    For a truly hassle-free experience, entrust Flip Avenue with the complete execution of your design. We manage the entire transformation, from coordinating contractors to overseeing every detail. Before commencement, a clear contract outlines the scope and terms. Upon successful completion, we conduct a thorough handover of your beautifully transformed space.
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <a href="portfolio.php" class="butn border rounded-pill mt-80 color-orange1 border-orange1 hover-bg-orange1">
                                        <span> Our Projects <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 offset-lg-2">
                                <div class="img wow">
                                    <img src="assets/img/home1/process.jpg" alt="" class="img-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="assets/img/home1/c_line2.png" alt="" class="c-line wow">
            </section>
            <!--  End process  -->


            <!--  Start projects  -->
            <section class="tc-projects-style1">
                <div class="container">
                    <div class="title mb-70">
                        <h2 class="fsz-45"> Featured Projects </h2>
                    </div>
                    <div class="tabs-links mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-9">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <!-- Featured tab first -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" 
                                                id="pills-featured-tab" 
                                                data-bs-toggle="pill" 
                                                data-bs-target="#pills-featured" 
                                                type="button">
                                            Featured
                                        </button>
                                    </li>
                                    <?php if ($project_categories_result && $project_categories_result->num_rows > 0): ?>
                                        <?php 
                                        while ($category = $project_categories_result->fetch_assoc()): 
                                            $category_slug = strtolower(str_replace(' ', '-', $category['slug']));
                                            $tab_id = 'pills-proj' . $category_slug;
                                        ?>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" 
                                                        id="<?php echo $tab_id; ?>-tab" 
                                                        data-bs-toggle="pill" 
                                                        data-bs-target="#<?php echo $tab_id; ?>" 
                                                        type="button">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </button>
                                            </li>
                                        <?php 
                                        endwhile; 
                                        $project_categories_result->data_seek(0); // Reset pointer
                                        ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="col-lg-3 mt-4 mt-lg-0 text-lg-end">
                                <a href="portfolio.php" class="butn border rounded-pill color-orange1 border-orange1 hover-bg-orange1">
                                    <span> See All Projects <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="projects">
                        <div class="tab-content" id="pills-tabContent">
                            <!-- Featured Projects Tab -->
                            <div class="tab-pane fade show active" id="pills-featured" role="tabpanel" aria-labelledby="pills-featured-tab">
                                <div class="projects-content float_box_container">
                                    <div class="projects-slider">
                                        <div class="swiper-wrapper">
                                            <?php if ($projects_result && $projects_result->num_rows > 0): ?>
                                                <?php while ($project = $projects_result->fetch_assoc()): 
                                                    // Handle image path
                                                    $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                                    
                                                    // Skip if no featured image
                                                    if (empty($featured_image)) continue;
                                                    
                                                    // Handle gallery images for fancybox
                                                    $gallery_images = [];
                                                    if (!empty($project['gallery_images'])) {
                                                        $gallery_array = json_decode($project['gallery_images'], true);
                                                        if (is_array($gallery_array)) {
                                                            foreach ($gallery_array as $gallery_img) {
                                                                $gallery_images[] = getImageUrl($gallery_img);
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Use featured image as first gallery image if no gallery
                                                    if (empty($gallery_images)) {
                                                        $gallery_images[] = $featured_image;
                                                    }
                                                ?>
                                                    <div class="swiper-slide">
                                                        <div class="project-card">
                                                            <?php if (count($gallery_images) > 0): ?>
                                                                <a href="<?php echo htmlspecialchars($gallery_images[0]); ?>" class="img" data-fancybox="proj-featured">
                                                                    <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                                                </a>
                                                            <?php endif; ?>
                                                            <div class="info">
                                                                <?php if (!empty($project['category'])): ?>
                                                                    <div class="tags">
                                                                        <a href="#"><?php echo htmlspecialchars($project['category']); ?></a>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <h3 class="title">
                                                                    <a href="project-details.php?id=<?php echo $project['id']; ?>">
                                                                        <?php echo htmlspecialchars($project['title']); ?>
                                                                    </a>
                                                                </h3>
                                                                <?php if (!empty($project['short_description'])): ?>
                                                                    <div class="text"><?php echo nl2br(htmlspecialchars($project['short_description'])); ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                                <?php $projects_result->data_seek(0); // Reset pointer ?>
                                            <?php else: ?>
                                                <div class="swiper-slide">
                                                    <div class="project-card">
                                                        <div class="info">
                                                            <p class="text">No featured projects available.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="float-cursor float_box"> Hold <br> and Drag </div>
                                </div>
                            </div>
                            
                            <?php if ($project_categories_result && $project_categories_result->num_rows > 0): ?>
                                <?php 
                                while ($category = $project_categories_result->fetch_assoc()): 
                                    $category_slug = strtolower(str_replace(' ', '-', $category['slug']));
                                    $tab_id = 'pills-proj' . $category_slug;
                                    
                                    // Get projects for this category
                                    $category_projects = [];
                                    if ($all_projects_result) {
                                        $all_projects_result->data_seek(0);
                                        while ($project = $all_projects_result->fetch_assoc()) {
                                            if (strtolower($project['category']) === strtolower($category['name'])) {
                                                $category_projects[] = $project;
                                            }
                                        }
                                    }
                                ?>
                                    <div class="tab-pane fade" 
                                         id="<?php echo $tab_id; ?>" 
                                         role="tabpanel" 
                                         aria-labelledby="<?php echo $tab_id; ?>-tab">
                                        <div class="projects-content float_box_container">
                                            <div class="projects-slider">
                                                <div class="swiper-wrapper">
                                                    <?php if (count($category_projects) > 0): ?>
                                                        <?php foreach ($category_projects as $project): 
                                                            // Handle image path
                                                            $featured_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';
                                                            
                                                            // Skip if no featured image
                                                            if (empty($featured_image)) continue;
                                                            
                                                            // Handle gallery images for fancybox
                                                            $gallery_images = [];
                                                            if (!empty($project['gallery_images'])) {
                                                                $gallery_array = json_decode($project['gallery_images'], true);
                                                                if (is_array($gallery_array)) {
                                                                    foreach ($gallery_array as $gallery_img) {
                                                                        $gallery_images[] = getImageUrl($gallery_img);
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // Use featured image as first gallery image if no gallery
                                                            if (empty($gallery_images)) {
                                                                $gallery_images[] = $featured_image;
                                                            }
                                                        ?>
                                                            <div class="swiper-slide">
                                                                <div class="project-card">
                                                                    <?php if (count($gallery_images) > 0): ?>
                                                                        <a href="<?php echo htmlspecialchars($gallery_images[0]); ?>" class="img" data-fancybox="proj-<?php echo $category_slug; ?>">
                                                                            <img src="<?php echo htmlspecialchars($featured_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-cover" loading="lazy">
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <div class="info">
                                                                        <?php if (!empty($project['category'])): ?>
                                                                            <div class="tags">
                                                                                <a href="#"><?php echo htmlspecialchars($project['category']); ?></a>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <h3 class="title">
                                                                            <a href="portfolio.php#project-<?php echo $project['id']; ?>">
                                                                                <?php echo htmlspecialchars($project['title']); ?>
                                                                            </a>
                                                                        </h3>
                                                                        <?php if (!empty($project['short_description'])): ?>
                                                                            <div class="text"><?php echo nl2br(htmlspecialchars($project['short_description'])); ?></div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="swiper-slide">
                                                            <div class="project-card">
                                                                <div class="info">
                                                                    <p class="text">No projects found in this category.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="float-cursor float_box"> Hold <br> and Drag </div>
                                        </div>
                                    </div>
                                <?php 
                                endwhile; 
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End projects  -->


            <!--  Start testimonials  -->
            <section class="tc-testimonials-style1">
                <div class="container">
                    <div class="row mb-60">
                        <div class="col-lg-12 text-center">
                            <h6 class="fsz-18 text-uppercase lh-4 mb-3"> what clients say <br> about us </h6>
                            <div class="lg-icon color-orange1"> <i class="la la-quote-right"></i> </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($testimonials_result && $testimonials_result->num_rows > 0): ?>
                            <?php 
                            $testimonial_count = 0;
                            // Reset result pointer to loop again
                            $testimonials_result->data_seek(0);
                            while ($testimonial = $testimonials_result->fetch_assoc()): 
                                $testimonial_count++;
                                $slider_id = 'testimonial-slider-' . $testimonial_count;
                            ?>
                                <div class="col-lg-6 col-md-6 mb-50">
                                    <div class="tc-clients-style1 testimonial-individual-slider">
                                        <div class="clients-slider1 <?php echo $slider_id; ?>">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <div class="clients-card">
                                                        <div class="text fsz-32 fw-600 lh-2 js-splittext-lines">
                                                            "<?php echo htmlspecialchars($testimonial['testimonial_text']); ?>"
                                                        </div>
                                                        <div class="author">
                                                            <?php if (!empty($testimonial['client_photo'])): ?>
                                                                <div class="au-img">
                                                                    <img src="<?php echo htmlspecialchars(getImageUrl($testimonial['client_photo'])); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" loading="lazy">
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="au-inf">
                                                                <h6 class="text-capitalize mb-2 fsz-16 fw-bold"><?php echo htmlspecialchars($testimonial['client_name']); ?></h6>
                                                                <p class="text-capitalize fsz-14 color-666">
                                                                    <?php 
                                                                    echo htmlspecialchars($testimonial['client_position']); 
                                                                    if (!empty($testimonial['client_company'])) {
                                                                        echo ' - ' . htmlspecialchars($testimonial['client_company']);
                                                                    }
                                                                    if (!empty($testimonial['project_name'])) {
                                                                        echo ' (' . htmlspecialchars($testimonial['project_name']) . ')';
                                                                    }
                                                                    ?>
                                                                </p>
                                                                <?php if (!empty($testimonial['rating'])): ?>
                                                                    <div class="rating mt-2">
                                                                        <?php 
                                                                        $rating = (int)$testimonial['rating'];
                                                                        for ($i = 1; $i <= 5; $i++): 
                                                                        ?>
                                                                            <i class="la la-star <?php echo $i <= $rating ? 'color-orange1' : 'color-ccc'; ?>"></i>
                                                                        <?php endfor; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="slider-controls">
                                                <div class="swiper-button-prev swiper-button-prev-<?php echo $testimonial_count; ?>"></div>
                                                <div class="swiper-pagination swiper-pagination-<?php echo $testimonial_count; ?>"></div>
                                                <div class="swiper-button-next swiper-button-next-<?php echo $testimonial_count; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <!-- Fallback testimonials if database is empty -->
                            <div class="col-lg-6 col-md-6 mb-50">
                                <div class="tc-clients-style1 testimonial-individual-slider">
                                    <div class="clients-slider1 testimonial-slider-1">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <div class="clients-card">
                                                    <div class="text fsz-32 fw-600 lh-2 js-splittext-lines">
                                                        "The entire team tactfully delivered a project of exceptional quality while staying on schedule and under budget. More than what i'm expected. I'm really satisfied and recommended!."
                                                    </div>
                                                    <div class="author">
                                                        <div class="au-inf">
                                                            <h6 class="text-capitalize mb-2 fsz-16 fw-bold">M. Salah</h6>
                                                            <p class="text-capitalize fsz-14 color-666">Dash Private Villa Project Investor</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slider-controls">
                                            <div class="swiper-button-prev swiper-button-prev-1"></div>
                                            <div class="swiper-pagination swiper-pagination-1"></div>
                                            <div class="swiper-button-next swiper-button-next-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
             
                <img src="assets/img/home1/c_line3.png" alt="" class="c-line">
            </section>
            <!--  End testimonials  -->


            <!--  Start awards  -->
            <section class="tc-awards-style1">
                <div class="container">
                    <div class="awards-content">
                        <div class="mb-80 text-center js-splittext-lines">
                            <h2 class="fsz-45"> Awards & Recognition </h2>
                        </div>
                        <div class="awards-list wow fadeInUp" data-wow-delay="0.2s">
                            <div class="award-row">
                                <div class="row gx-0 align-items-center">
                                    <div class="col-lg-2">
                                        <div class="year"> 2023 </div>
                                    </div>
                                    <div class="col-lg-6 my-3 my-lg-0">
                                        <h6 class="title fsz-23 fw-400"> NSSF HI Innovator - Women's Accelerator Programme </h6>
                                    </div>
                                    <div class="col-lg-2">
                                        <p> <i class="la la-map-marker me-3"></i> Kampala, Uganda </p>
                                    </div>
                                    <div class="col-lg-2 text-lg-end mt-4 mt-lg-0">
                                        <i class="ti-arrow-top-right fsz-20"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-100 mb-40">
                            <a href="#" class="butn border rounded-pill hover-bg-orange1">
                                <span> Show More <i class="small ms-1 la la-angle-down"></i> </span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End awards  -->




            <!--  Start blog  -->
            <section class="tc-blog-style1 pt-100 mt-80">
                <div class="container">
                    <div class="mb-80 js-splittext-lines">
                        <div class="row">
                            <div class="col-lg-9">
                                <h2 class="fsz-45"> Latest Posts </h2>
                            </div>
                            <div class="col-lg-3 text-lg-end mt-4 mt-lg-0">
                                <a href="blog.php" class="butn border rounded-pill color-orange1 border-orange1 hover-bg-orange1">
                                    <span> All Articles <i class="small ms-1 ti-arrow-top-right"></i> </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="blog-slider position-relative overflow-hidden">
                        <div class="swiper-wrapper">
                            <?php 
                            if ($blog_result && $blog_result->num_rows > 0):
                                while ($blog = $blog_result->fetch_assoc()): 
                                    // Parse publish date
                                    $publish_date = new DateTime($blog['publish_date']);
                                    $day = $publish_date->format('d');
                                    $month = $publish_date->format('F');
                                    $year = $publish_date->format('Y');
                                    
                                    // Get featured image or use fallback
                                    // Check if the image path already includes cms/ or assets/
                                    $featured_image = getImageUrlWithFallback($blog['featured_image'] ?? '', 'assets/img/home1/blog/blog1.jpg');
                                    
                                    // Create excerpt from content
                                    $excerpt = !empty($blog['excerpt']) ? $blog['excerpt'] : strip_tags(substr($blog['content'], 0, 150)) . '...';
                            ?>
                            <a href="single.php?id=<?php echo $blog['id']; ?>" class="swiper-slide" style="display: block; cursor: pointer; text-decoration: none; color: inherit;">
                                <div class="blog-card">
                                    <div class="img">
                                        <img src="<?php echo htmlspecialchars($featured_image); ?>" 
                                             alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                                             class="img-cover" 
                                             loading="lazy"
                                             onerror="this.src='assets/img/home1/blog/blog1.jpg'">
                                    </div>
                                    <div class="info">
                                        <div class="date">
                                            <div class="num fsz-45 mb-2"><?php echo $day; ?></div>
                                            <small class="fsz-12 text-uppercase color-666"><?php echo $month; ?> <br> <?php echo $year; ?></small>
                                        </div>
                                        <div class="cont">
                                            <div class="title d-block fsz-24 hover-orange1 mb-15 fw-600">
                                                <?php echo htmlspecialchars($blog['title']); ?>
                                            </div>
                                            <small class="fsz-12 color-orange1">
                                                <?php echo htmlspecialchars($blog['category']); ?>
                                                <?php if (!empty($blog['author_name'])): ?>
                                                    • By <?php echo htmlspecialchars($blog['author_name']); ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php 
                                endwhile;
                            else:
                                // Fallback: Show default blog posts if no posts in database
                            ?>
                            <div class="swiper-slide">
                                <div class="blog-card">
                                    <div class="img">
                                        <img src="assets/img/home1/blog/blog1.jpg" alt="" class="img-cover">
                                    </div>
                                    <div class="info">
                                        <div class="date">
                                            <div class="num fsz-45 mb-2"> 25 </div>
                                            <small class="fsz-12 text-uppercase color-666"> december <br> 2023 </small>
                                        </div>
                                        <div class="cont">
                                            <a href="#" class="title d-block fsz-24 hover-orange1 mb-15 fw-600"> How to handle the day light in Vray for best reality </a>
                                            <small class="fsz-12 color-orange1"> Architecture, Guide </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="blog-card">
                                    <div class="img">
                                        <img src="assets/img/home1/blog/blog2.jpg" alt="" class="img-cover">
                                    </div>
                                    <div class="info">
                                        <div class="date">
                                            <div class="num fsz-45 mb-2"> 16 </div>
                                            <small class="fsz-12 text-uppercase color-666"> december <br> 2023 </small>
                                        </div>
                                        <div class="cont">
                                            <a href="#" class="title d-block fsz-24 hover-orange1 mb-15 fw-600"> Top 10 Wooden Architecture Building 2023 </a>
                                            <small class="fsz-12 color-orange1"> Inspiration </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </section>
            <!--  End blog  -->


            <!--  Start chat  -->
            <section class="tc-chat-style1">
                <div class="container">
                    <div class="content">
                        <a href="#" class="xl-text"> info@flipavenueltd.com </a>
                        <h5 class="mb-50 lh-5"> Let us help your dream <br> become reality </h5>
                    </div>
                </div>
                <img src="assets/img/home1/c_line4.png" alt="" class="c-line wow">
            </section>
            <!--  End chat  -->

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
                                    <li> <a href="#"> info@flipavenueltd.com </a> </li>
                                    <li> <a href="#"> +256 701380251 / 783370967 </a> </li>
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
    <!-- <a href="#" class="to_top" id="to_top">
        <i class="la la-angle-up"></i>
    </a> -->
    <!--  End to top button  -->

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

    <!-- Cart count update -->
    <script>
        // Update cart count via AJAX
        function updateCartCount() {
            fetch('cart-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_cart'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartCountElement = document.getElementById('cartCount');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.data.cart_count;
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>

    <!-- Enhanced WOW animations with mobile optimization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize WOW.js with mobile support
            if (typeof WOW !== 'undefined') {
                var wow = new WOW({
                    boxClass: 'wow',
                    animateClass: 'animated',
                    offset: 50, // Reduced for better mobile experience
                    mobile: true,
                    live: false
                });
                wow.init();
            }
            
            // Mobile-specific animation enhancements
            var isMobile = window.innerWidth <= 768;
            
            // Fallback: ensure all animated elements are visible
            setTimeout(function() {
                const animatedElements = document.querySelectorAll('.wow');
                animatedElements.forEach(function(element) {
                    // Force visibility on mobile for better UX
                    if (isMobile) {
                        element.style.visibility = 'visible';
                        element.style.opacity = '1';
                        element.style.transform = 'none';
                    }
                    
                    // Ensure animation class is applied
                    if (!element.classList.contains('animated')) {
                        element.classList.add('animated');
                    }
                });
            }, isMobile ? 500 : 1000);
            
            // Handle orientation changes and resize
            window.addEventListener('resize', function() {
                var newIsMobile = window.innerWidth <= 768;
                if (newIsMobile !== isMobile) {
                    isMobile = newIsMobile;
                    // Re-ensure visibility on orientation change
                    setTimeout(function() {
                        const animatedElements = document.querySelectorAll('.wow');
                        animatedElements.forEach(function(element) {
                            element.style.visibility = 'visible';
                            element.style.opacity = '1';
                        });
                    }, 100);
                }
            });
            
            // Additional fallback after page load
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