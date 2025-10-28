<?php
// Include centralized database connection
require_once 'cms/db_connect.php';

// Get filters
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$price_range = isset($_GET['price']) ? $_GET['price'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'featured';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Build query
$where = ["is_active = 1"];

if ($category) {
    $where[] = "category = '$category'";
}

if ($search) {
    $where[] = "(name LIKE '%$search%' OR description LIKE '%$search%' OR tags LIKE '%$search%')";
}

if ($price_range) {
    if ($price_range === '0-50000') {
        $where[] = "price BETWEEN 0 AND 50000";
    } elseif ($price_range === '50000-100000') {
        $where[] = "price BETWEEN 50000 AND 100000";
    } elseif ($price_range === '100000-200000') {
        $where[] = "price BETWEEN 100000 AND 200000";
    } elseif ($price_range === '200000+') {
        $where[] = "price >= 200000";
    }
}

$where_clause = implode(' AND ', $where);

// Determine sort order
switch ($sort) {
    case 'price-low':
        $order_by = 'price ASC';
        break;
    case 'price-high':
        $order_by = 'price DESC';
        break;
    case 'newest':
        $order_by = 'created_at DESC';
        break;
    default:
        $order_by = 'id DESC';
}

// Get products
$products_query = "SELECT * FROM shop_products WHERE $where_clause ORDER BY $order_by";
$products_result = $conn->query($products_query);

// Get all categories for filter
$categories_query = "SELECT DISTINCT category FROM shop_products WHERE is_active = 1 AND category != '' ORDER BY category";
$categories_result = $conn->query($categories_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Shop - FlipAvenue</title>
    <meta name="description" content="Discover our collection of architectural products, design tools, and professional resources.">
    <meta name="keywords" content="architecture, design, products, tools, resources, shop">
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
        <header class="tc-header-style1 shop-header" style="min-height: 12vh;">
            <div class="img">
                <img src="assets/img/home1/head_slide2.png" alt="" class="img-cover">
            </div>
            
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start shop  -->
            <section class="tc-shop-style1 section-padding">
                <div class="container">
                    <div class="mb-50">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2 class="fsz-45 mb-3 wow fadeInUp"> Flip Kids </h2>
                                <p class="color-666 wow fadeInUp" data-wow-delay="0.2s">Discover our collection of architectural products, design tools, and professional resources</p>
                            </div>
                            <div class="col-lg-6 mt-4 mt-lg-0">
                                <form method="GET" action="shop.php">
                                    <div class="filters-wrapper d-flex justify-content-end align-items-center flex-wrap gap-3">
                                        <!-- Category Filter -->
                                        <div class="filter-group">
                                            <select class="form-select" name="category" onchange="this.form.submit()">
                                                <option value="">All Categories</option>
                                                <?php while ($cat = $categories_result->fetch_assoc()): ?>
                                                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($cat['category']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <!-- Price Filter -->
                                        <div class="filter-group">
                                            <select class="form-select" name="price" onchange="this.form.submit()">
                                                <option value="">All Prices</option>
                                                <option value="0-50000" <?php echo $price_range === '0-50000' ? 'selected' : ''; ?>>UGX 0 - 50,000</option>
                                                <option value="50000-100000" <?php echo $price_range === '50000-100000' ? 'selected' : ''; ?>>UGX 50,000 - 100,000</option>
                                                <option value="100000-200000" <?php echo $price_range === '100000-200000' ? 'selected' : ''; ?>>UGX 100,000 - 200,000</option>
                                                <option value="200000+" <?php echo $price_range === '200000+' ? 'selected' : ''; ?>>UGX 200,000+</option>
                                            </select>
                                        </div>

                                        <!-- Sort Filter -->
                                        <div class="filter-group">
                                            <select class="form-select" name="sort" onchange="this.form.submit()">
                                                <option value="featured" <?php echo $sort === 'featured' ? 'selected' : ''; ?>>Featured</option>
                                                <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                                <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div class="filter-group">
                                            <div class="search-box">
                                                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search products...">
                                                <button type="submit" class="btn btn-link search-icon border-0 bg-transparent">
                                                    <i class="la la-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Hidden fields to maintain other filters -->
                                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($price_range); ?>">
                                    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--  End shop  -->


            <!--  Start shop products  -->
            <section class="tc-shop-products section-padding pt-0">
                <div class="container">
                    <div class="row">
                        
                        <?php if ($products_result->num_rows > 0): ?>
                            <?php 
                            $delay = 0;
                            while ($product = $products_result->fetch_assoc()): 
                                $image = $product['featured_image'] ? 'cms/' . str_replace('../', '', $product['featured_image']) : 'assets/img/home1/projects/proj1.jpg';
                                $product_json = json_encode([
                                    'id' => $product['id'],
                                    'name' => $product['name'],
                                    'description' => $product['description'],
                                    'price' => (float)$product['price'],
                                    'category' => $product['category'],
                                    'image' => $image
                                ]);
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="product-card wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s" data-product-url="product-details.php?id=<?php echo $product['id']; ?>" style="cursor: pointer;">
                                    <div class="img">
                                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-cover">
                                        <div class="overlay">
                                            <a href="product-details.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Quick View</a>
                                            <button type="button" class="btn btn-sm btn-outline-light add-to-cart-btn" data-product='<?php echo htmlspecialchars($product_json); ?>'>
                                                <i class="la la-shopping-cart me-1"></i>Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                    <div class="info p-4">
                                        <div class="category mb-2">
                                            <span class="badge bg-light text-dark"><?php echo htmlspecialchars($product['category']); ?></span>
                                        </div>
                                        <h5 class="title mb-3"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p class="description mb-3"><?php echo htmlspecialchars(substr($product['description'], 0, 120)) . '...'; ?></p>
                                        <div class="price-rating d-flex justify-content-between align-items-center">
                                            <div class="price">
                                                <span class="current-price fw-bold">UGX <?php echo number_format($product['price']); ?></span>
                                            </div>
                                            <div class="rating">
                                                <i class="la la-star text-warning"></i>
                                                <i class="la la-star text-warning"></i>
                                                <i class="la la-star text-warning"></i>
                                                <i class="la la-star text-warning"></i>
                                                <i class="la la-star text-warning"></i>
                                                <span class="ms-1">(5.0)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $delay += 0.1;
                            if ($delay > 0.5) $delay = 0;
                            endwhile; 
                            ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <i class="la la-shopping-bag" style="font-size: 80px; color: #ddd;"></i>
                                <p class="color-666 mt-3">No products found. <?php echo $search ? 'Try a different search term.' : ''; ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </section>
            <!--  End shop products  -->


        </main>

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

    <!-- Shop JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize WOW.js
            new WOW().init();

            // Update cart count via AJAX
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
                    // Silent fail - cart count update is not critical
                    console.error('Cart count update error:', error);
                });
            }

            // Initialize cart count on page load
            updateCartCount();

            // Handle product card navigation (anywhere on card except buttons)
            document.addEventListener('click', function(e) {
                // Don't navigate if clicking on the add-to-cart button specifically
                if (e.target.closest('.add-to-cart-btn')) {
                    return; // Let the add to cart handler take over
                }
                
                // Navigate if clicking anywhere on the product card (including Quick View link)
                const productCard = e.target.closest('.product-card');
                if (productCard) {
                    const url = productCard.getAttribute('data-product-url');
                    if (url) {
                        window.location.href = url;
                    }
                }
            });

            // Add to cart functionality via AJAX - Use capture phase to catch event first
            document.addEventListener('click', function(e) {
                // Check if clicked element or its parent is the add-to-cart button
                const button = e.target.classList.contains('add-to-cart-btn') ? e.target : e.target.closest('.add-to-cart-btn');
                
                if (button) {
                    // CRITICAL: Stop propagation IMMEDIATELY before any other handlers
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation(); // Stop other listeners on same element
                    
                    try {
                        const productData = JSON.parse(button.getAttribute('data-product'));
                        
                        // Show loading state
                        const originalText = button.innerHTML;
                        button.innerHTML = '<i class="la la-spinner la-spin me-2"></i>Adding...';
                        button.disabled = true;
                        
                        // Make AJAX call
                        fetch('cart-ajax.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=add_to_cart&product_id=${productData.id}&quantity=1&cache_buster=${Date.now()}`,
                            cache: 'no-store',
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update cart count
                                updateCartCount();
                                
                                // Show success message
                                button.innerHTML = '<i class="la la-check me-2"></i>Added!';
                                button.classList.remove('btn-outline-light');
                                button.classList.add('btn-success');
                                
                                setTimeout(() => {
                                    button.innerHTML = originalText;
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-outline-light');
                                    button.disabled = false;
                                }, 2000);
                                
                                // Show modal notification
                                showModal('Success', data.message, 'success');
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            button.innerHTML = originalText;
                            button.disabled = false;
                            showModal('Error', 'Error adding product to cart. Please try again.', 'error');
                        });
                    } catch (error) {
                        // Silent error handling
                    }
                }
            }, true); // Use capture phase to run BEFORE the product card click handler

            // Modal notification function
            function showModal(title, message, type = 'info') {
                // Remove existing modals
                const existingModals = document.querySelectorAll('.shop-modal');
                existingModals.forEach(modal => modal.remove());
                
                // Create modal element
                const modal = document.createElement('div');
                modal.className = 'shop-modal';
                modal.innerHTML = `
                    <div class="modal-overlay">
                        <div class="modal-content">
                            <div class="modal-header ${type}">
                                <h5 class="modal-title">
                                    <i class="la la-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'} me-2"></i>
                                    ${title}
                                </h5>
                                <button type="button" class="modal-close" onclick="this.closest('.shop-modal').remove()">
                                    <i class="la la-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>${message}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="this.closest('.shop-modal').remove()">OK</button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add to page
                document.body.appendChild(modal);
                
                // Show modal with animation
                setTimeout(() => modal.classList.add('show'), 100);
                
                // Auto-close after 3 seconds for success messages
                if (type === 'success') {
                    setTimeout(() => {
                        modal.classList.remove('show');
                        setTimeout(() => modal.remove(), 300);
                    }, 3000);
                }
            }

            // Product card click to view details
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't navigate if clicking on buttons
                    if (e.target.closest('.btn') || e.target.closest('button') || e.target.closest('a.btn')) {
                        return;
                    }
                    
                    // Get product ID from the add-to-cart button
                    const addToCartBtn = this.querySelector('.add-to-cart-btn');
                    if (addToCartBtn && addToCartBtn.dataset.product) {
                        const productData = JSON.parse(addToCartBtn.dataset.product);
                        window.location.href = 'product-details.php?id=' + productData.id;
                    }
                });
            });
        });
    </script>

    <!-- Enhanced WOW animations with mobile optimization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize WOW.js with mobile support
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

