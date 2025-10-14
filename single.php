<?php
// Include centralized database connection
require_once 'cms/db_connect.php';

// Get post by slug or ID
$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($slug) {
    $post_query = "SELECT bp.*, au.full_name as author_name, au.email as author_email 
                   FROM blog_posts bp 
                   LEFT JOIN admin_users au ON bp.author_id = au.id 
                   WHERE bp.slug = '$slug' AND bp.is_published = 1";
} elseif ($id) {
    $post_query = "SELECT bp.*, au.full_name as author_name, au.email as author_email 
                   FROM blog_posts bp 
                   LEFT JOIN admin_users au ON bp.author_id = au.id 
                   WHERE bp.id = $id AND bp.is_published = 1";
} else {
    header('Location: blog.php');
    exit();
}

$post_result = $conn->query($post_query);

if ($post_result->num_rows == 0) {
    header('Location: blog.php');
    exit();
}

$post = $post_result->fetch_assoc();

// Get related posts (same category, exclude current post)
$related_query = "SELECT * FROM blog_posts 
                  WHERE is_published = 1 
                  AND id != {$post['id']} 
                  AND category = '{$post['category']}' 
                  ORDER BY publish_date DESC 
                  LIMIT 3";
$related_result = $conn->query($related_query);

// Get previous and next posts
$prev_query = "SELECT id, slug, title FROM blog_posts 
               WHERE is_published = 1 AND publish_date < '{$post['publish_date']}' 
               ORDER BY publish_date DESC LIMIT 1";
$prev_result = $conn->query($prev_query);
$prev_post = $prev_result->num_rows > 0 ? $prev_result->fetch_assoc() : null;

$next_query = "SELECT id, slug, title FROM blog_posts 
               WHERE is_published = 1 AND publish_date > '{$post['publish_date']}' 
               ORDER BY publish_date ASC LIMIT 1";
$next_result = $conn->query($next_query);
$next_post = $next_result->num_rows > 0 ? $next_result->fetch_assoc() : null;

// Get all categories with counts
$categories_query = "SELECT category, COUNT(*) as count FROM blog_posts 
                     WHERE is_published = 1 AND category != '' 
                     GROUP BY category ORDER BY category";
$categories_result = $conn->query($categories_query);

// Calculate read time (assuming 200 words per minute)
$word_count = str_word_count(strip_tags($post['content']));
$read_time = ceil($word_count / 200);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="<?php echo htmlspecialchars($post['tags'] ?: 'FlipAvenue, Architecture, Design'); ?>">
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($post['excerpt'] ?: $post['content']), 0, 160)); ?>">
    <meta name="author" content="Flip Avenue Limited">

    <!-- Title  -->
    <title><?php echo htmlspecialchars($post['title']); ?> | FlipAvenue Blog</title>

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
        .blog-content .highlight-box {
            border-left: 4px solid var(--color-orange1);
        }
        .blog-content .quote-box {
            position: relative;
        }
        .blog-content .tags .tag {
            display: inline-block;
            padding: 5px 12px;
            background: #f8f9fa;
            color: #666;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 8px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .blog-content .tags .tag:hover {
            background: var(--color-orange1);
            color: white;
        }
        .share-btn {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .share-btn.facebook { background: #3b5998; }
        .share-btn.twitter { background: #1da1f2; }
        .share-btn.linkedin { background: #0077b5; }
        .share-btn.pinterest { background: #bd081c; }
        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
        }
        .category-list li {
            margin-bottom: 10px;
        }
        .category-list a {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
        }
        .nav-post {
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .nav-post:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .related-post img {
            height: 60px;
            object-fit: cover;
        }
        .author-avatar img {
            object-fit: cover;
        }
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 20px 0;
        }
        .blog-content p {
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .blog-content h2, .blog-content h3, .blog-content h4 {
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .blog-content ul, .blog-content ol {
            margin-bottom: 20px;
            padding-left: 30px;
        }
        .blog-content li {
            margin-bottom: 8px;
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
        <header class="tc-header-style1" style="min-height: 40vh;">
            <div class="img">
                <?php if ($post['featured_image']): ?>
                    <img src="<?php echo 'cms/' . str_replace('../', '', $post['featured_image']); ?>" alt="" class="img-cover">
                <?php else: ?>
                    <img src="assets/img/home1/blog/blog1.jpg" alt="" class="img-cover">
                <?php endif; ?>
            </div>
            <div class="info section-padding-x pb-70">
                <div class="row align-items-end gx-5">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="breadcrumb mb-3">
                            <a href="index.php" class="color-666 hover-orange1">Home</a>
                            <span class="mx-2 color-666">/</span>
                            <a href="blog.php" class="color-666 hover-orange1">Blog</a>
                            <span class="mx-2 color-666">/</span>
                            <span class="color-000">Article</span>
                        </div>
                        <h1 class="js-title wow fadeInUp"> <?php echo htmlspecialchars($post['title']); ?> </h1>
                        <div class="meta mt-30 wow fadeInUp" data-wow-delay="0.2s">
                            <span class="me-4"><i class="la la-calendar me-2 color-orange1"></i><?php echo date('F d, Y', strtotime($post['publish_date'])); ?></span>
                            <span class="me-4"><i class="la la-user me-2 color-orange1"></i>By <?php echo htmlspecialchars($post['author_name'] ?: 'Admin'); ?></span>
                            <span><i class="la la-clock me-2 color-orange1"></i><?php echo $read_time; ?> min read</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start blog content  -->
            <section class="tc-blog-single section-padding">
                <div class="container">
                    <div class="row gx-5">
                        <!-- Main Content -->
                        <div class="col-lg-8">
                            <article class="blog-content wow fadeInUp">
                                <!-- Article Content -->
                                <div class="content">
                                    <?php if ($post['excerpt']): ?>
                                    <div class="intro mb-40">
                                        <p class="fsz-18 lh-2 color-666">
                                            <?php echo nl2br(htmlspecialchars($post['excerpt'])); ?>
                                        </p>
                                    </div>
                                    <?php endif; ?>

                                    <div class="main-content">
                                        <?php echo $post['content']; ?>
                                    </div>
                                </div>

                                <!-- Tags -->
                                <?php if ($post['tags']): ?>
                                <div class="tags-section mt-50 pt-40 border-top">
                                    <h6 class="fw-600 mb-3">Tags:</h6>
                                    <div class="tags">
                                        <?php 
                                        $tags = explode(',', $post['tags']);
                                        foreach ($tags as $tag): 
                                            $tag = trim($tag);
                                        ?>
                                            <a href="blog.php?search=<?php echo urlencode($tag); ?>" class="tag"><?php echo htmlspecialchars($tag); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Share Buttons -->
                                <div class="share-section mt-40">
                                    <h6 class="fw-600 mb-3">Share this article:</h6>
                                    <div class="social-share">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&description=<?php echo urlencode($post['title']); ?>" target="_blank" class="share-btn pinterest"><i class="fab fa-pinterest"></i></a>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4 mt-5 mt-lg-0">
                            <div class="sidebar">
                                <!-- Author Info -->
                                <div class="sidebar-widget mb-50 wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="author-card bg-light p-4 radius-4">
                                        <div class="author-avatar mb-3">
                                            <img src="assets/img/home1/team/team1.jpg" alt="Author" class="img-cover radius-4" style="width: 80px; height: 80px;">
                                        </div>
                                        <h6 class="fw-600 mb-2"><?php echo htmlspecialchars($post['author_name'] ?: 'Administrator'); ?></h6>
                                        <p class="fsz-14 color-666 mb-3">Content Author</p>
                                        <p class="fsz-14 color-666">
                                            <?php echo htmlspecialchars($post['author_email'] ?: 'FlipAvenue Limited team member'); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Related Posts -->
                                <?php if ($related_result->num_rows > 0): ?>
                                <div class="sidebar-widget mb-50 wow fadeInUp" data-wow-delay="0.3s">
                                    <h5 class="fw-600 mb-30">Related Articles</h5>
                                    <div class="related-posts">
                                        <?php while ($related = $related_result->fetch_assoc()): ?>
                                        <div class="related-post mb-4">
                                            <div class="row gx-3">
                                                <div class="col-4">
                                                    <a href="single.php?slug=<?php echo $related['slug']; ?>">
                                                        <?php if ($related['featured_image']): ?>
                                                            <img src="<?php echo 'cms/' . str_replace('../', '', $related['featured_image']); ?>" alt="" class="img-cover radius-4">
                                                        <?php else: ?>
                                                            <img src="assets/img/home1/blog/blog2.jpg" alt="" class="img-cover radius-4">
                                                        <?php endif; ?>
                                                    </a>
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="fsz-14 fw-600 mb-2">
                                                        <a href="single.php?slug=<?php echo $related['slug']; ?>" class="hover-orange1">
                                                            <?php echo htmlspecialchars($related['title']); ?>
                                                        </a>
                                                    </h6>
                                                    <small class="color-666"><?php echo date('M d, Y', strtotime($related['publish_date'])); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Categories -->
                                <?php if ($categories_result->num_rows > 0): ?>
                                <div class="sidebar-widget mb-50 wow fadeInUp" data-wow-delay="0.4s">
                                    <h5 class="fw-600 mb-30">Categories</h5>
                                    <ul class="category-list">
                                        <?php while ($cat = $categories_result->fetch_assoc()): ?>
                                        <li>
                                            <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>" class="d-flex justify-content-between hover-orange1">
                                                <span><?php echo htmlspecialchars($cat['category']); ?></span>
                                                <span class="color-666"><?php echo $cat['count']; ?></span>
                                            </a>
                                        </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>

                                <!-- Newsletter -->
                                <div class="sidebar-widget wow fadeInUp" data-wow-delay="0.5s">
                                    <div class="newsletter-card bg-orange1 text-white p-4 radius-4">
                                        <h5 class="fw-600 mb-3">Stay Updated</h5>
                                        <p class="fsz-14 mb-3">Get the latest architecture insights delivered to your inbox.</p>
                                        <form class="newsletter-form">
                                            <input type="email" class="form-control mb-3" placeholder="Your email address">
                                            <button type="submit" class="butn bg-white text-orange1 w-100">
                                                <span>Subscribe</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End blog content  -->


            <!--  Start navigation  -->
            <section class="tc-blog-navigation section-padding-x">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <?php if ($prev_post): ?>
                            <a href="single.php?slug=<?php echo $prev_post['slug']; ?>" class="nav-post prev-post d-flex align-items-center p-4 bg-light radius-4 hover-bg-orange1 hover-text-white">
                                <div class="icon me-3">
                                    <i class="la la-arrow-left fsz-20"></i>
                                </div>
                                <div>
                                    <small class="d-block color-666 mb-1">Previous Post</small>
                                    <h6 class="fw-600 mb-0"><?php echo htmlspecialchars($prev_post['title']); ?></h6>
                                </div>
                            </a>
                            <?php else: ?>
                            <div class="p-4 bg-light radius-4 opacity-50">
                                <small class="color-666">No previous post</small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <?php if ($next_post): ?>
                            <a href="single.php?slug=<?php echo $next_post['slug']; ?>" class="nav-post next-post d-flex align-items-center p-4 bg-light radius-4 hover-bg-orange1 hover-text-white text-end">
                                <div class="flex-grow-1">
                                    <small class="d-block color-666 mb-1">Next Post</small>
                                    <h6 class="fw-600 mb-0"><?php echo htmlspecialchars($next_post['title']); ?></h6>
                                </div>
                                <div class="icon ms-3">
                                    <i class="la la-arrow-right fsz-20"></i>
                                </div>
                            </a>
                            <?php else: ?>
                            <div class="p-4 bg-light radius-4 opacity-50 text-end">
                                <small class="color-666">No next post</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End navigation  -->

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

