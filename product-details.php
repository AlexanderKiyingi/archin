<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - FlipAvenue</title>
    <meta name="description" content="View detailed information about our architectural products and design tools.">
    <meta name="keywords" content="architecture, design, products, tools, resources">
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
                    <li> <a href="portfolio.html" class="main_link"> projects </a> </li>
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
                            <a class="nav-link" href="portfolio.html">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                    </ul>
                    <div class="nav-side">
                        <a href="cart.php" class="icon ms-5 fsz-21 position-relative">
                            <span> <i class="la la-shopping-cart"></i> </span>
                            <span class="cart-badge badge bg-orange1 rounded-pill position-absolute" id="cartCount">0</span>
                        </a>
                        <a href="#" class="icon ms-3 fsz-21">
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
        <header class="tc-header-style1 shop-header" style="min-height: 15vh;">
            <div class="img">
                <img src="assets/img/home1/head_slide2.png" alt="" class="img-cover">
            </div>
            <div class="info section-padding-x pb-40">
                <div class="row align-items-end gx-5">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="breadcrumb-nav">
                            <a href="index.php" class="text-white">Home</a>
                            <span class="mx-2 text-white">/</span>
                            <a href="shop.php" class="text-white">Shop</a>
                            <span class="mx-2 text-white">/</span>
                            <span class="text-white opacity-75" id="breadcrumbTitle">Product</span>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <img src="assets/img/home1/projects/proj1.jpg" alt="Product" class="img-fluid rounded-3" id="mainProductImage">
                                </div>
                                <div class="thumbnail-images d-flex gap-2">
                                    <img src="assets/img/home1/projects/proj1.jpg" alt="Thumbnail 1" class="img-thumbnail product-thumb active" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                    <img src="assets/img/home1/projects/proj2.jpg" alt="Thumbnail 2" class="img-thumbnail product-thumb" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                    <img src="assets/img/home1/projects/proj3.jpg" alt="Thumbnail 3" class="img-thumbnail product-thumb" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="col-lg-6">
                            <div class="product-info">
                                <div class="category mb-3">
                                    <span class="badge bg-light text-dark" id="productCategory">Design Tools</span>
                                </div>
                                
                                <h1 class="product-title mb-3 fw-bold" id="productTitle">Architectural Design Toolkit</h1>
                                
                                <div class="rating mb-3">
                                    <i class="la la-star text-warning"></i>
                                    <i class="la la-star text-warning"></i>
                                    <i class="la la-star text-warning"></i>
                                    <i class="la la-star text-warning"></i>
                                    <i class="la la-star text-warning"></i>
                                    <span class="ms-2 text-muted">(4.9) - 127 reviews</span>
                                </div>

                                <div class="price mb-4">
                                    <h2 class="text-orange1 fw-bold mb-0" id="productPrice">UGX 89,000</h2>
                                </div>

                                <div class="product-description mb-4">
                                    <h5 class="fw-600 mb-3">Description</h5>
                                    <p class="text-muted" id="productDescription">
                                        Complete set of professional architectural design tools, templates, and digital resources for modern architects. This comprehensive toolkit includes everything you need to create stunning architectural designs.
                                    </p>
                                </div>

                                <div class="product-features mb-4">
                                    <h5 class="fw-600 mb-3">Key Features</h5>
                                    <ul class="list-unstyled" id="productFeatures">
                                        <li class="mb-2"><i class="la la-check text-success me-2"></i> Professional design templates</li>
                                        <li class="mb-2"><i class="la la-check text-success me-2"></i> Digital resources and assets</li>
                                        <li class="mb-2"><i class="la la-check text-success me-2"></i> Compatible with major CAD software</li>
                                        <li class="mb-2"><i class="la la-check text-success me-2"></i> Lifetime updates and support</li>
                                    </ul>
                                </div>

                                <div class="product-actions">
                                    <div class="quantity-selector mb-3 d-flex align-items-center gap-3">
                                        <label class="fw-600">Quantity:</label>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" type="button" id="decreaseQty">-</button>
                                            <input type="number" class="form-control text-center" value="1" min="1" id="productQuantity">
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
                                    <p class="mb-2"><strong>SKU:</strong> <span id="productSku">ARC-TOOLKIT-001</span></p>
                                    <p class="mb-2"><strong>Category:</strong> <span id="productCategoryText">Design Tools</span></p>
                                    <p class="mb-0"><strong>Tags:</strong> <span id="productTags">architectural, design, toolkit, professional</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Tabs -->
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Additional Details</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">Specifications</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews (127)</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    <div class="p-4">
                                        <h5 class="mb-3">What's Included</h5>
                                        <p>This comprehensive architectural design toolkit includes a wide range of professional resources designed to enhance your workflow and creativity. Perfect for architects, designers, and creative professionals.</p>
                                        <ul>
                                            <li>50+ Professional design templates</li>
                                            <li>100+ High-quality architectural assets</li>
                                            <li>Detailed documentation and guides</li>
                                            <li>Sample projects and case studies</li>
                                            <li>Access to exclusive community forum</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="specs" role="tabpanel">
                                    <div class="p-4">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Format</th>
                                                    <td>Digital Download (ZIP)</td>
                                                </tr>
                                                <tr>
                                                    <th>File Size</th>
                                                    <td>2.5 GB</td>
                                                </tr>
                                                <tr>
                                                    <th>Compatibility</th>
                                                    <td>AutoCAD, Revit, SketchUp, ArchiCAD</td>
                                                </tr>
                                                <tr>
                                                    <th>License</th>
                                                    <td>Single User Commercial License</td>
                                                </tr>
                                                <tr>
                                                    <th>Updates</th>
                                                    <td>Lifetime free updates</td>
                                                </tr>
                                                <tr>
                                                    <th>Support</th>
                                                    <td>Email support + Community forum</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    <div class="p-4">
                                        <h5 class="mb-4">Customer Reviews</h5>
                                        
                                        <div class="review-item mb-4 pb-4 border-bottom">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <strong>Sarah Johnson</strong>
                                                    <div class="rating">
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                    </div>
                                                </div>
                                                <span class="text-muted">2 weeks ago</span>
                                            </div>
                                            <p class="mb-0">Excellent toolkit! Has everything I need for my architectural projects. The templates are professional and save me hours of work.</p>
                                        </div>

                                        <div class="review-item mb-4 pb-4 border-bottom">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <strong>Michael Chen</strong>
                                                    <div class="rating">
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star text-warning"></i>
                                                        <i class="la la-star-o text-warning"></i>
                                                    </div>
                                                </div>
                                                <span class="text-muted">1 month ago</span>
                                            </div>
                                            <p class="mb-0">Great value for money. The resources are high quality and well organized. Would recommend to any architect.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Products -->
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <h3 class="mb-4">Related Products</h3>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="img">
                                    <img src="assets/img/home1/blog/blog1.jpg" alt="Related Product" class="img-cover">
                                </div>
                                <div class="info p-3">
                                    <h6 class="title mb-2">Modern Architecture Guide</h6>
                                    <div class="price">
                                        <span class="fw-bold text-orange1">UGX 45,000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="img">
                                    <img src="assets/img/home1/projects/proj2.jpg" alt="Related Product" class="img-cover">
                                </div>
                                <div class="info p-3">
                                    <h6 class="title mb-2">Professional CAD Software</h6>
                                    <div class="price">
                                        <span class="fw-bold text-orange1">UGX 299,000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="img">
                                    <img src="assets/img/home1/blog/blog2.jpg" alt="Related Product" class="img-cover">
                                </div>
                                <div class="info p-3">
                                    <h6 class="title mb-2">Architecture Templates Pack</h6>
                                    <div class="price">
                                        <span class="fw-bold text-orange1">UGX 35,000</span>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="img">
                                    <img src="assets/img/home1/projects/proj3.jpg" alt="Related Product" class="img-cover">
                                </div>
                                <div class="info p-3">
                                    <h6 class="title mb-2">Modern Building 3D Models</h6>
                                    <div class="price">
                                        <span class="fw-bold text-orange1">UGX 25,000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    <li> <a href="#"> info@flipavenueltd.com </a> </li>
                                    <li> <a href="#"> +256 701380251 / 783370967 </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Other links </h5>
                                <ul class="footer-links">
                                    <li> <a href="shop.php"> Shop </a> </li>
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
                                    <li> <a href="careers.html"> Careers </a> </li>
                                    <li> <a href="contact.html"> Contact Us </a> </li>
                                    <li> <a href="#"> Help </a> </li>
                                </ul>
                            </div>
                            <div class="branch-card">
                                <h5 class="mb-20 mt-5 mt-lg-0 fw-600"> Legal </h5>
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
                                <a href="portfolio.html"> Projects </a>
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
            // Get product ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('id');

            // Product data (in a real application, this would come from a database)
            const products = {
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

            // Load product data
            if (productId && products[productId]) {
                const product = products[productId];
                
                document.getElementById('productTitle').textContent = product.name;
                document.getElementById('breadcrumbTitle').textContent = product.name;
                document.getElementById('productDescription').textContent = product.description;
                document.getElementById('productPrice').textContent = 'UGX ' + product.price.toLocaleString();
                document.getElementById('productCategory').textContent = product.category;
                document.getElementById('productCategoryText').textContent = product.category;
                document.getElementById('productSku').textContent = product.sku;
                document.getElementById('productTags').textContent = product.tags;
                document.getElementById('mainProductImage').src = product.image;
                
                // Update page title
                document.title = product.name + ' - FlipAvenue';
            }

            // Update cart count
            function updateCartCount() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                document.getElementById('cartCount').textContent = totalItems;
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
            document.getElementById('addToCartBtn').addEventListener('click', function() {
                if (productId && products[productId]) {
                    const product = products[productId];
                    const quantity = parseInt(document.getElementById('productQuantity').value);
                    
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    const existingItem = cart.find(item => item.id === product.id);
                    
                    if (existingItem) {
                        existingItem.quantity += quantity;
                    } else {
                        cart.push({...product, quantity: quantity});
                    }
                    
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartCount();
                    
                    // Show success message
                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="la la-check me-2"></i>Added to Cart!';
                    btn.classList.add('btn-success');
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-success');
                    }, 2000);
                }
            });
        });
    </script>

</body>

</html>

