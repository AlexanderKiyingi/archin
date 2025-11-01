<?php
require_once 'cms/db_connect.php';
// Include common helper functions
require_once 'common/functions.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product from database
$product = null;
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM shop_products WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}

// If product not found, redirect to shop
if (!$product) {
    header('Location: shop.php');
    exit;
}

// Get image path using helper function
$product_image = getImageUrlWithFallback($product['featured_image'] ?? '', 'assets/img/home1/projects/proj1.jpg');

// Format price
$formatted_price = 'UGX ' . number_format($product['price'], 0);

// Generate SKU from product ID
$product_sku = 'PROD-' . str_pad($product['id'], 6, '0', STR_PAD_LEFT);

// Parse JSON fields for product tabs
$additional_details_data = null;
if (!empty($product['additional_details'])) {
    $additional_details_data = json_decode($product['additional_details'], true);
}

$specifications_data = [];
if (!empty($product['specifications'])) {
    $specifications_data = json_decode($product['specifications'], true);
    if (!is_array($specifications_data)) {
        $specifications_data = [];
    }
}

$gallery_images_data = [];
if (!empty($product['gallery_images'])) {
    $gallery_images_data = json_decode($product['gallery_images'], true);
    if (!is_array($gallery_images_data)) {
        $gallery_images_data = [];
    }
}

// Fetch related products (same category, exclude current product)
$related_products = [];
if ($product['category']) {
    $related_query = "SELECT * FROM shop_products WHERE category = ? AND id != ? AND is_active = 1 ORDER BY RAND() LIMIT 4";
    $related_stmt = $conn->prepare($related_query);
    $related_stmt->bind_param("si", $product['category'], $product_id);
    $related_stmt->execute();
    $related_result = $related_stmt->get_result();
    while ($row = $related_result->fetch_assoc()) {
        $related_products[] = $row;
    }
    $related_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - FlipAvenue</title>
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($product['description']), 0, 160)); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($product['tags'] ?? 'interior design, products'); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($product['name']); ?> - FlipAvenue">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr(strip_tags($product['description']), 0, 160)); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($product_image); ?>">
    <meta property="og:type" content="product">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($product['name']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars(substr(strip_tags($product['description']), 0, 160)); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($product_image); ?>">
    <meta name="author" content="FlipAvenue">

    <!-- favicon -->
    <link rel="shortcut icon" href="assets/img/home1/fav.png">

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- styles -->
    <link rel="stylesheet" href="common/assets/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/all.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/animate.css">
    <link rel="stylesheet" href="common/assets/css/lib/swiper8.min.css">
    <link rel="stylesheet" href="common/assets/css/lib/lity.css">
    <link rel="stylesheet" href="common/assets/css/lib/themify-icons.css">
    <link rel="stylesheet" href="common/assets/css/lib/line-awesome.css">
    <link rel="stylesheet" href="common/assets/css/lib/jquery.fancybox.css">

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
                            <a class="nav-link active" href="shop.php">Shop</a>
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
        <header class="tc-header-style1 shop-header" style="min-height: 8vh; background-color: #1a1a1a;">
            <!-- Image removed - using solid background color instead -->
            <!-- Breadcrumbs removed -->
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start product details  -->
            <section class="tc-product-details section-padding">
                <div class="container">
                    <div class="row">
                        <!-- Product Images -->
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="product-images">
                                <div class="main-image mb-3">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid rounded-3" id="mainProductImage" onerror="this.src='assets/img/home1/projects/proj1.jpg'">
                                </div>
                                <div class="thumbnail-images d-flex gap-2 flex-wrap">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail product-thumb active" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" onerror="this.src='assets/img/home1/projects/proj1.jpg'">
                                    <?php if (!empty($gallery_images_data)): ?>
                                        <?php foreach ($gallery_images_data as $gallery_img): ?>
                                            <img src="<?php echo htmlspecialchars(getImageUrl($gallery_img)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail product-thumb" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" onerror="this.src='assets/img/home1/projects/proj1.jpg'">
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="col-lg-6">
                            <div class="product-info">
                                <?php if ($product['category']): ?>
                                <div class="category mb-3">
                                    <span class="badge bg-light text-dark" id="productCategory"><?php echo htmlspecialchars($product['category']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <h1 class="product-title mb-3 fw-bold" id="productTitle"><?php echo htmlspecialchars($product['name']); ?></h1>

                                <div class="price mb-4">
                                    <h2 class="text-orange1 fw-bold mb-0" id="productPrice"><?php echo $formatted_price; ?></h2>
                                    <?php if ($product['stock_quantity'] !== null): ?>
                                        <?php if ($product['stock_quantity'] > 0): ?>
                                            <span class="text-success small">In Stock (<?php echo $product['stock_quantity']; ?> available)</span>
                                        <?php else: ?>
                                            <span class="text-danger small">Out of Stock</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <div class="product-description mb-4">
                                    <h5 class="fw-600 mb-3">Description</h5>
                                    <div class="text-muted" id="productDescription">
                                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                                    </div>
                                </div>

                                <?php if ($product['tags']): ?>
                                <div class="product-tags mb-4">
                                    <h5 class="fw-600 mb-3">Tags</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php 
                                        $tags = explode(',', $product['tags']);
                                        foreach ($tags as $tag): 
                                            $tag = trim($tag);
                                            if (!empty($tag)):
                                        ?>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($tag); ?></span>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="product-actions">
                                    <div class="quantity-selector mb-3 d-flex align-items-center gap-3">
                                        <label class="fw-600">Quantity:</label>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" type="button" id="decreaseQty">-</button>
                                            <input type="number" class="form-control text-center quantity-input" value="1" min="1" id="productQuantity" readonly>
                                            <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
                                        </div>
                                    </div>

                                    <div class="action-buttons d-flex gap-3">
                                        <button class="butn bg-orange1 text-white rounded-pill px-5 py-3 hover-bg-dark" id="addToCartBtn">
                                            <i class="la la-shopping-cart me-2"></i> Add to Cart
                                        </button>
                                        <a href="shop.php" class="butn border rounded-pill px-5 py-3">
                                            Back to Shop
                                        </a>
                                    </div>
                                </div>

                                <div class="product-meta mt-4 pt-4 border-top">
                                    <p class="mb-2"><strong>SKU:</strong> <span id="productSku"><?php echo $product_sku; ?></span></p>
                                    <?php if ($product['category']): ?>
                                    <p class="mb-2"><strong>Category:</strong> <span id="productCategoryText"><?php echo htmlspecialchars($product['category']); ?></span></p>
                                    <?php endif; ?>
                                    <?php if ($product['tags']): ?>
                                    <p class="mb-0"><strong>Tags:</strong> <span id="productTags"><?php echo htmlspecialchars($product['tags']); ?></span></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Tabs -->
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                                <?php if ($additional_details_data): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Additional Details</button>
                                </li>
                                <?php endif; ?>
                                <?php if (!empty($specifications_data)): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo !$additional_details_data ? 'active' : ''; ?>" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">Specifications</button>
                                </li>
                                <?php endif; ?>
                                <?php if ($product['show_reviews_tab']): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo (!$additional_details_data && empty($specifications_data)) ? 'active' : ''; ?>" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                                </li>
                                <?php endif; ?>
                            </ul>
                            <div class="tab-content" id="productTabContent">
                                <?php if ($additional_details_data): ?>
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    <div class="p-4">
                                        <?php if (!empty($additional_details_data['intro'])): ?>
                                            <p><?php echo nl2br(htmlspecialchars($additional_details_data['intro'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($additional_details_data['items'])): ?>
                                            <ul>
                                                <?php foreach ($additional_details_data['items'] as $item): ?>
                                                    <li><?php echo htmlspecialchars($item); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($specifications_data)): ?>
                                <div class="tab-pane fade <?php echo !$additional_details_data ? 'show active' : ''; ?>" id="specs" role="tabpanel">
                                    <div class="p-4">
                                        <table class="table">
                                            <tbody>
                                                <?php foreach ($specifications_data as $label => $value): ?>
                                                    <tr>
                                                        <th><?php echo htmlspecialchars($label); ?></th>
                                                        <td><?php echo htmlspecialchars($value); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($product['show_reviews_tab']): ?>
                                <div class="tab-pane fade <?php echo (!$additional_details_data && empty($specifications_data)) ? 'show active' : ''; ?>" id="reviews" role="tabpanel">
                                    <div class="p-4">
                                        <h5 class="mb-4">Customer Reviews</h5>
                                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Related Products -->
                    <?php if (!empty($related_products)): ?>
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <h3 class="mb-4">Related Products</h3>
                        </div>
                        <?php foreach ($related_products as $related): 
                            $related_image = getImageUrlWithFallback($related['featured_image'] ?? '', 'assets/img/home1/projects/proj1.jpg');
                            $related_price = 'UGX ' . number_format($related['price'], 0);
                        ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="product-card">
                                <a href="product-details.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                    <div class="img">
                                        <img src="<?php echo htmlspecialchars($related_image); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>" class="img-cover" onerror="this.src='assets/img/home1/projects/proj1.jpg'">
                                    </div>
                                    <div class="info p-3">
                                        <h6 class="title mb-2 text-dark"><?php echo htmlspecialchars($related['name']); ?></h6>
                                        <div class="price">
                                            <span class="fw-bold text-orange1"><?php echo $related_price; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </section>
            <!--  End product details  -->


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

    <!-- scripts -->
    <script src="common/assets/js/lib/jquery-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/jquery-migrate-3.0.0.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/wow.min.js"></script>
    <script src="common/assets/js/lib/swiper8-bundle.min.js"></script>
    
    <!-- GSAP -->
    <script src="common/assets/js/gsap_lib/gsap.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollTrigger.min.js"></script>
    <script src="common/assets/js/gsap_lib/ScrollSmoother.min.js"></script>
    <script src="common/assets/js/gsap_lib/SplitText.min.js"></script>
    
    <script src="common/assets/js/lib/jquery.fancybox.js"></script>
    <script src="common/assets/js/lib/lity.js"></script>
    <script src="common/assets/js/lib/jquery.counterup.js"></script>
    <script src="common/assets/js/lib/jquery.waypoints.min.js"></script>
    <script src="common/assets/js/lib/parallaxie.js"></script>
    <script src="common/assets/js/common_js.js"></script>
    <script src="assets/main.js"></script>

    <!-- Product Details JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get product data from PHP
            const productData = <?php echo json_encode($product); ?>;
            const productId = productData.id;

            // Create products object for compatibility
            const products = {
                [productId]: productData
            };
            
            // Remove hardcoded products - now using database
            /*
            const oldProducts = {
                'tools-1': {
                    id: 'tools-1',
                    name: 'Architectural Design Toolkit',
                    description: 'Complete set of professional architectural design tools, templates, and digital resources for modern architects. This comprehensive toolkit includes everything you need to create stunning architectural designs.',
                    price: 89,
                    category: 'Design Tools',
                    image: 'assets/img/home1/projects/proj1.jpg',
                    sku: 'ARC-TOOLKIT-001',
                    tags: 'architectural, design, toolkit, professional'
                },
                'books-1': {
                    id: 'books-1',
                    name: 'Modern Architecture Guide',
                    description: 'Comprehensive 300-page guide covering contemporary architectural principles, sustainable design, and innovative building techniques.',
                    price: 45,
                    category: 'Books & Guides',
                    image: 'assets/img/home1/blog/blog1.jpg',
                    sku: 'ARC-BOOK-001',
                    tags: 'architecture, guide, modern, sustainable'
                },
                'software-1': {
                    id: 'software-1',
                    name: 'Professional CAD Software',
                    description: 'Industry-leading CAD software with advanced architectural modeling, 3D rendering, and BIM capabilities for professional architects.',
                    price: 299,
                    category: 'Software',
                    image: 'assets/img/home1/projects/proj2.jpg',
                    sku: 'ARC-SOFT-001',
                    tags: 'cad, software, bim, 3d, modeling'
                },
                'models-1': {
                    id: 'models-1',
                    name: 'Modern Building 3D Models',
                    description: 'High-quality 3D building models with detailed textures, perfect for architectural visualization and presentation projects.',
                    price: 25,
                    category: '3D Models',
                    image: 'assets/img/home1/projects/proj3.jpg',
                    sku: 'ARC-3D-001',
                    tags: '3d, models, buildings, visualization'
                },
                'templates-1': {
                    id: 'templates-1',
                    name: 'Architecture Templates Pack',
                    description: 'Professional presentation templates, drawing layouts, and documentation formats for architectural projects.',
                    price: 35,
                    category: 'Templates',
                    image: 'assets/img/home1/blog/blog2.jpg',
                    sku: 'ARC-TEMP-001',
                    tags: 'templates, presentation, documentation'
                },
                'courses-1': {
                    id: 'courses-1',
                    name: 'Advanced Architecture Course',
                    description: 'Comprehensive 40-hour online course covering advanced architectural concepts, sustainable design, and modern construction techniques.',
                    price: 149,
                    category: 'Courses',
                    image: 'assets/img/home1/team/team1.jpg',
                    sku: 'ARC-COURSE-001',
                    tags: 'course, online, advanced, architecture'
                }
            };
            */

            // Product data is already loaded via PHP above
            // This JavaScript is just for cart functionality

            // Update cart count
            function updateCartCount() {
                fetch('cart-ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=get_cart&cache_buster=' + Date.now(), // Cache busting for Chrome
                    cache: 'no-store', // Prevent Chrome from caching
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartCountElement = document.getElementById('cartCount');
                        if (cartCountElement) {
                            // Use innerHTML for better Chrome compatibility
                            cartCountElement.innerHTML = data.data.cart_count;
                            // Alternative: Force re-paint
                            cartCountElement.offsetHeight; // Trigger reflow
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating cart count:', error);
                });
            }
            updateCartCount();

            // Thumbnail image switcher
            document.querySelectorAll('.product-thumb').forEach(thumb => {
                thumb.addEventListener('click', function() {
                    document.querySelector('.product-thumb.active').classList.remove('active');
                    this.classList.add('active');
                    document.getElementById('mainProductImage').src = this.src;
                });
            });

            // Quantity controls
            document.getElementById('decreaseQty').addEventListener('click', function() {
                const qtyInput = document.getElementById('productQuantity');
                if (qtyInput.value > 1) {
                    qtyInput.value = parseInt(qtyInput.value) - 1;
                }
            });

            document.getElementById('increaseQty').addEventListener('click', function() {
                const qtyInput = document.getElementById('productQuantity');
                qtyInput.value = parseInt(qtyInput.value) + 1;
            });

            // Add to cart
            const addToCartBtn = document.getElementById('addToCartBtn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', async function() {
                    if (productId && products[productId]) {
                        const product = products[productId];
                        const quantity = parseInt(document.getElementById('productQuantity').value);
                        const btn = this;
                        
                        // Show loading state
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="la la-spinner la-spin me-2"></i>Adding...';
                        btn.disabled = true;
                        
                        try {
                            // Make AJAX call
                            const response = await fetch('cart-ajax.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `action=add_to_cart&product_id=${product.id}&quantity=${quantity}&cache_buster=${Date.now()}`,
                                cache: 'no-store',
                                credentials: 'same-origin'
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                // Update cart count
                                updateCartCount();
                                
                                // Show success message
                                btn.innerHTML = '<i class="la la-check me-2"></i>Added to Cart!';
                                btn.classList.add('btn-success');
                                
                                setTimeout(() => {
                                    btn.innerHTML = originalText;
                                    btn.classList.remove('btn-success');
                                    btn.disabled = false;
                                }, 2000);
                            } else {
                                throw new Error(data.message);
                            }
                        } catch (error) {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            alert('Error adding product to cart. Please try again.');
                        }
                    }
                });
            }
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
    </script>

</body>

</html>

