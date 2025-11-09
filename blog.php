<?php
// Include centralized database connection
require_once 'cms/db_connect.php';
// Include common helper functions
require_once 'common/functions.php';

// Check if database connection is established
if (!isset($conn) || $conn->connect_error) {
    die("Database connection error. Please check your database configuration.");
}

// Pagination
$posts_per_page = 6;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $posts_per_page;

// Initialize variables
$total_posts = 0;
$total_pages = 0;
$featured_post = null;
$posts_result = null;
$categories_result = null;

// Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = $search ? "AND (title LIKE '%$search%' OR content LIKE '%$search%' OR tags LIKE '%$search%')" : '';

// Category filter
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$category_condition = $category ? "AND category = '$category'" : '';

// Get total posts count
$count_query = "SELECT COUNT(*) as total FROM blog_posts WHERE is_published = 1 $search_condition $category_condition";
$count_result = $conn->query($count_query);
if ($count_result) {
    $total_posts = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_posts / $posts_per_page);
} else {
    error_log("Blog count query failed: " . $conn->error);
    $total_posts = 0;
    $total_pages = 0;
}

// Get featured post (most recent published)
$featured_query = "SELECT bp.*, au.full_name as author_name 
                   FROM blog_posts bp 
                   LEFT JOIN admin_users au ON bp.author_id = au.id
                   WHERE bp.is_published = 1 
                   ORDER BY bp.publish_date DESC 
                   LIMIT 1";
$featured_result = $conn->query($featured_query);
if ($featured_result && $featured_result->num_rows > 0) {
    $featured_post = $featured_result->fetch_assoc();
    $featured_author = !empty($featured_post['author_name']) ? $featured_post['author_name'] : 'Admin';
    $featured_read_time = max(1, ceil(str_word_count(strip_tags($featured_post['content'])) / 200));
} else {
    $featured_post = null;
    $featured_author = 'Admin';
    $featured_read_time = 0;
}

// Get regular posts
$posts_query = "SELECT bp.*, au.full_name as author_name 
                FROM blog_posts bp 
                LEFT JOIN admin_users au ON bp.author_id = au.id 
                WHERE bp.is_published = 1 $search_condition $category_condition
                ORDER BY bp.publish_date DESC 
                LIMIT $posts_per_page OFFSET $offset";
$posts_result = $conn->query($posts_query);
if (!$posts_result) {
    error_log("Blog posts query failed: " . $conn->error);
    $posts_result = null;
}

// Get categories for filter
$categories_query = "SELECT DISTINCT category FROM blog_posts WHERE is_published = 1 AND category != '' AND category IS NOT NULL ORDER BY category";
$categories_result = $conn->query($categories_query);
if (!$categories_result) {
    error_log("Blog categories query failed: " . $conn->error);
    // Set to false to indicate no result
    $categories_result = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="keywords" content="FlipAvenue, Architecture Blog, Design News, Uganda">
    <meta name="description" content="Read the latest news, insights, and articles about architecture, design, and construction from FlipAvenue Limited.">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title>Blog - News & Insights | FlipAvenue Limited</title>

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
        <header class="tc-header-style1 blog-header" style="min-height: 12vh;">
            <div class="img">
                <img src="assets/img/home1/head_slide2.png" alt="" class="img-cover">
            </div>
            
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start blog  -->
            <section class="tc-blog-style1 section-padding">
                <div class="container">
                    <div class="mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <h2 class="fsz-45 mb-3 wow fadeInUp"> Latest Posts </h2>
                                <p class="color-666 wow fadeInUp" data-wow-delay="0.2s">Explore our latest articles, industry insights, and design inspiration</p>
                            </div>
                        </div>
                    </div>

                    <?php if ($featured_post && $page == 1 && !$search && !$category): ?>
                    <!-- Featured Post -->
                    <div class="row mb-80">
                        <div class="col-lg-12">
                            <div class="blog-card featured-post wow fadeInUp">
                                <div class="row gx-5 align-items-center">
                                    <div class="col-lg-6">
                                        <div class="img">
                                            <?php if ($featured_post['featured_image']): ?>
                                                <img src="<?php echo htmlspecialchars(getImageUrl($featured_post['featured_image'])); ?>" alt="" class="img-cover radius-4">
                                            <?php else: ?>
                                                <img src="assets/img/home1/blog/blog1.jpg" alt="" class="img-cover radius-4">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-4 mt-lg-0">
                                        <div class="info">
                                            <div class="featured-layout">
                                                <div class="featured-title-column">
                                                    <h3 class="featured-title fsz-40 fw-600 mb-0">
                                                        <a href="single.php?slug=<?php echo $featured_post['slug']; ?>" class="hover-orange1">
                                                            <?php echo htmlspecialchars($featured_post['title']); ?>
                                                        </a>
                                                    </h3>
                                                </div>
                                                <div class="featured-meta-column">
                                                    <span class="featured-pill"><i class="la la-star me-1"></i> Featured</span>
                                                    <?php if (!empty($featured_post['category'])): ?>
                                                        <span class="featured-meta-entry"><i class="la la-tag me-2"></i><?php echo htmlspecialchars($featured_post['category']); ?></span>
                                                    <?php endif; ?>
                                                    <span class="featured-meta-entry"><i class="la la-calendar me-2"></i><?php echo date('F d, Y', strtotime($featured_post['publish_date'])); ?></span>
                                                </div>
                                                <div class="featured-content-column">
                                                    <p class="featured-excerpt color-666 mb-0">
                                                        <?php echo htmlspecialchars(substr(strip_tags($featured_post['excerpt'] ?: $featured_post['content']), 0, 240)) . '...'; ?>
                                                    </p>
                                                    <div class="featured-meta-bottom">
                                                        <span class="featured-meta-item"><i class="la la-user"></i><?php echo htmlspecialchars($featured_author); ?></span>
                                                        <?php if ($featured_read_time): ?>
                                                        <span class="featured-meta-item"><i class="la la-clock"></i><?php echo $featured_read_time; ?> min read</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="single.php?slug=<?php echo $featured_post['slug']; ?>" class="featured-read-more butn border rounded-pill color-orange1 border-orange1 hover-bg-orange1 d-inline-flex align-items-center">
                                                        <span> Read Article <i class="small ms-2 ti-arrow-top-right"></i> </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row justify-content-between align-items-center mb-40">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                <!-- Category Filter -->
                                <select class="form-select" style="max-width: 200px;" onchange="window.location.href='blog.php?category='+this.value+(new URLSearchParams(window.location.search).get('search') ? '&search='+new URLSearchParams(window.location.search).get('search') : '')">
                                    <option value="">All Categories</option>
                                    <?php if ($categories_result !== false && $categories_result->num_rows > 0): ?>
                                        <?php 
                                        // Reset pointer to beginning
                                        $categories_result->data_seek(0);
                                        while ($cat = $categories_result->fetch_assoc()): ?>
                                            <?php if (!empty($cat['category'])): ?>
                                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['category']); ?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                                
                                <!-- Search Box -->
                                <form method="GET" class="flex-grow-1">
                                    <?php if ($category): ?>
                                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                                    <?php endif; ?>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search articles..." value="<?php echo htmlspecialchars($search); ?>">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="la la-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Grid -->
                    <?php if ($posts_result && $posts_result->num_rows > 0): ?>
                    <div class="row gx-4 gy-5">
                        <?php 
                        $delay = 0.2;
                        while ($post = $posts_result->fetch_assoc()): 
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <?php 
                                $author_name = !empty($post['author_name']) ? $post['author_name'] : 'Admin';
                                $words = str_word_count(strip_tags($post['content']));
                                $reading_minutes = max(1, ceil($words / 200));
                                $category_label = !empty($post['category']) ? $post['category'] : (!empty($post['tags']) ? $post['tags'] : 'Insights');
                            ?>
                            <div class="blog-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                                <div class="card-inner">
                                    <a href="single.php?slug=<?php echo $post['slug']; ?>" class="media-wrap d-block">
                                        <?php if ($post['featured_image']): ?>
                                            <img src="<?php echo htmlspecialchars(getImageUrl($post['featured_image'])); ?>" alt="" class="img-cover radius-4">
                                        <?php else: ?>
                                            <img src="assets/img/home1/blog/blog1.jpg" alt="" class="img-cover radius-4">
                                        <?php endif; ?>
                                    </a>
                                    <div class="info">
                                        <div class="card-body-row">
                                            <div class="date-block">
                                                <span class="day"><?php echo date('d', strtotime($post['publish_date'])); ?></span>
                                                <span class="month"><?php echo strtoupper(date('F', strtotime($post['publish_date']))); ?></span>
                                                <span class="year"><?php echo date('Y', strtotime($post['publish_date'])); ?></span>
                                            </div>
                                            <div class="content-block">
                                                <a href="single.php?slug=<?php echo $post['slug']; ?>" class="blog-title text-dark">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                                <div class="meta-inline">
                                                    <span class="category"><?php echo htmlspecialchars($category_label); ?></span>
                                                    <span class="dot">•</span>
                                                    <span class="author">By <?php echo htmlspecialchars($author_name); ?></span>
                                                    <span class="dot">•</span>
                                                    <span class="reading-time"><?php echo $reading_minutes; ?> min read</span>
                                                </div>
                                               
                                                <a href="single.php?slug=<?php echo $post['slug']; ?>" class="read-more-link">
                                                    Read Article <i class="la la-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $delay += 0.1;
                        if ($delay > 0.8) $delay = 0.2;
                        endwhile; 
                        ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="la la-inbox" style="font-size: 80px; color: #ddd;"></i>
                        <p class="color-666 mt-3">No blog posts found. <?php echo $search ? 'Try a different search term.' : ''; ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="mt-80 text-center">
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $search ? '&search='.$search : ''; ?><?php echo $category ? '&category='.$category : ''; ?>"> 
                                        <i class="la la-angle-left"></i> 
                                    </a>
                                </li>
                                <?php else: ?>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1"> <i class="la la-angle-left"></i> </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search='.$search : ''; ?><?php echo $category ? '&category='.$category : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $search ? '&search='.$search : ''; ?><?php echo $category ? '&category='.$category : ''; ?>"> 
                                        <i class="la la-angle-right"></i> 
                                    </a>
                                </li>
                                <?php else: ?>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#"> <i class="la la-angle-right"></i> </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <!--  End blog  -->


            <!--  Start newsletter  -->
            <section class="tc-chat-style1">
                <div class="container">
                    <div class="content">
                        <h5 class="mb-30 lh-5"> Subscribe to Our Newsletter </h5>
                        <p class="color-666 mb-40">Stay updated with the latest architecture trends and news</p>
                        <form class="newsletter-form d-inline-flex align-items-center">
                            <input type="email" class="form-control me-3" placeholder="Enter your email" style="min-width: 300px;">
                            <button type="submit" class="butn border rounded-pill hover-bg-orange1">
                                <span> Subscribe <i class="small ms-1 ti-arrow-top-right"></i> </span>
                            </button>
                        </form>
                    </div>
                </div>
                <img src="assets/img/home1/c_line4.png" alt="" class="c-line wow">
            </section>
            <!--  End newsletter  -->

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
<?php $conn->close(); ?>

