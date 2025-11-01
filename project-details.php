<?php
require_once 'cms/db_connect.php';
// Include common helper functions
require_once 'common/functions.php';

// Get project ID from URL
$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch project from database
$project = null;
if ($project_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    }
}

// If project not found, redirect to portfolio
if (!$project) {
    header('Location: portfolio.php');
    exit;
}

// Get image path using helper function
$project_image = !empty($project['featured_image']) ? getImageUrl($project['featured_image']) : '';

// Parse gallery images JSON
$gallery_images_data = [];
if (!empty($project['gallery_images'])) {
    $gallery_images_data = json_decode($project['gallery_images'], true);
    if (!is_array($gallery_images_data)) {
        $gallery_images_data = [];
    }
}

// Function to format project description with proper structure
function formatProjectDescription($description) {
    if (empty($description)) {
        return '';
    }
    
    // Normalize line endings and fix concatenated words
    $description = str_replace("\r\n", "\n", $description);
    $description = str_replace("\r", "\n", $description);
    $description = preg_replace('/\n+/', "\n", $description); // Remove multiple consecutive newlines
    
    // Fix common concatenation issues (word followed by lowercase letter without space)
    $description = preg_replace('/([a-z])([A-Z])/', '$1 $2', $description);
    
    // Parse Problem and Solution sections
    $formatted = '';
    $sections = preg_split('/(Problem:|Solution:)/', $description, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    if (count($sections) > 1) {
        // We have structured content with Problem/Solution
        for ($i = 0; $i < count($sections); $i++) {
            $section = trim($sections[$i]);
            if (empty($section)) {
                continue;
            }
            
            if ($section === 'Problem:') {
                $formatted .= '<div class="problem-section mb-3"><h6 class="fw-bold text-dark mb-2">Problem:</h6>';
            } elseif ($section === 'Solution:') {
                $formatted .= '</div><div class="solution-section"><h6 class="fw-bold text-dark mb-2">Solution:</h6>';
            } else {
                $formatted .= '<p class="mb-2">' . nl2br(htmlspecialchars($section)) . '</p>';
            }
        }
        $formatted .= '</div>';
    } else {
        // Regular paragraph formatting
        $formatted = '<p>' . nl2br(htmlspecialchars($description)) . '</p>';
    }
    
    return $formatted;
}

// Fetch related projects (same category, exclude current project)
$related_projects = [];
if ($project['category']) {
    $related_query = "SELECT * FROM projects WHERE category = ? AND id != ? AND is_active = 1 ORDER BY RAND() LIMIT 4";
    $related_stmt = $conn->prepare($related_query);
    $related_stmt->bind_param("si", $project['category'], $project_id);
    $related_stmt->execute();
    $related_result = $related_stmt->get_result();
    while ($row = $related_result->fetch_assoc()) {
        $related_projects[] = $row;
    }
    $related_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> - FlipAvenue</title>
    <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($project['description']), 0, 160)); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($project['category'] ?? 'interior design, projects'); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($project['title']); ?> - FlipAvenue">
    <meta property="og:description" content="<?php echo htmlspecialchars(substr(strip_tags($project['description']), 0, 160)); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($project_image); ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($project['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars(substr(strip_tags($project['description']), 0, 160)); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($project_image); ?>">
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
                            <a class="nav-link active" href="portfolio.php">Projects</a>
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
        <header class="tc-header-style1 project-header" style="min-height: 8vh; background-color: #1a1a1a;">
            <!-- Image removed - using solid background color instead -->
            <!-- Breadcrumbs removed -->
        </header>
        <!--  End page header  -->


        <!--Contents-->
        <main>

            <!--  Start project details  -->
            <section class="tc-project-details section-padding">
                <div class="container">
                    <div class="row">
                        <!-- Project Images -->
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <?php if (!empty($project_image) || !empty($gallery_images_data)): ?>
                            <div class="project-images">
                                <?php if (!empty($project_image)): ?>
                                <div class="main-image mb-3">
                                    <img src="<?php echo htmlspecialchars($project_image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="img-fluid rounded-3" id="mainProjectImage">
                                </div>
                                <?php endif; ?>
                                <?php 
                                // Combine featured image with gallery images for thumbnails
                                $all_images = [];
                                if (!empty($project_image)) {
                                    $all_images[] = $project_image;
                                }
                                if (!empty($gallery_images_data)) {
                                    foreach ($gallery_images_data as $gallery_img) {
                                        $all_images[] = getImageUrl($gallery_img);
                                    }
                                }
                                
                                // Display thumbnails if we have more than one image
                                if (count($all_images) > 1):
                                ?>
                                <div class="thumbnail-images d-flex gap-2 flex-wrap">
                                    <?php foreach ($all_images as $index => $img): ?>
                                        <img src="<?php echo htmlspecialchars($img); ?>" 
                                             alt="<?php echo htmlspecialchars($project['title']); ?>" 
                                             class="img-thumbnail project-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Project Information -->
                        <div class="<?php echo (!empty($project_image) || !empty($gallery_images_data)) ? 'col-lg-6' : 'col-lg-12'; ?>">
                            <div class="project-info">
                                <?php if ($project['category']): ?>
                                <div class="category mb-3">
                                    <span class="badge bg-light text-dark"><?php echo htmlspecialchars($project['category']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <h1 class="project-title mb-3 fw-bold"><?php echo htmlspecialchars($project['title']); ?></h1>

                                <?php if ($project['short_description']): ?>
                                <div class="short-description mb-4">
                                    <p class="lead text-muted"><?php echo nl2br(htmlspecialchars($project['short_description'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="project-description mb-4">
                                    <h5 class="fw-600 mb-3">About This Project</h5>
                                    <div class="text-muted">
                                        <?php echo formatProjectDescription($project['description']); ?>
                                    </div>
                                </div>

                                <!-- Project Meta Information -->
                                <div class="project-meta mb-4 p-3 bg-light rounded">
                                    <?php if ($project['client_name']): ?>
                                    <p class="mb-2"><strong><i class="la la-user me-2"></i>Client:</strong> <?php echo htmlspecialchars($project['client_name']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($project['location']): ?>
                                    <p class="mb-2"><strong><i class="la la-map-marker me-2"></i>Location:</strong> <?php echo htmlspecialchars($project['location']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($project['completion_date']): ?>
                                    <p class="mb-0"><strong><i class="la la-calendar me-2"></i>Completion Date:</strong> <?php echo date('F Y', strtotime($project['completion_date'])); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="project-actions mt-4">
                                    <a href="portfolio.php" class="btn btn-outline-dark rounded-pill me-2">
                                        <i class="la la-arrow-left me-2"></i>
                                        Back to Portfolio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Projects -->
                    <?php if (!empty($related_projects)): ?>
                    <div class="row mt-5 pt-5">
                        <div class="col-12">
                            <h3 class="mb-4 fw-bold">Related Projects</h3>
                            <div class="row">
                                <?php foreach ($related_projects as $related): ?>
                                    <?php 
                                        $related_image = !empty($related['featured_image']) ? getImageUrl($related['featured_image']) : ''; 
                                        // Only display if image exists
                                        if (!empty($related_image)):
                                    ?>
                                    <div class="col-md-6 col-lg-3 mb-4">
                                        <div class="project-card h-100 border rounded overflow-hidden">
                                            <a href="project-details.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                                <img src="<?php echo htmlspecialchars($related_image); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" class="img-fluid w-100" style="height: 200px; object-fit: cover; loading: lazy;">
                                                <div class="p-3">
                                                    <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($related['title']); ?></h6>
                                                    <?php if ($related['category']): ?>
                                                        <small class="text-muted"><?php echo htmlspecialchars($related['category']); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <!--  End project details  -->

        </main>

        <!--  Start footer  -->
        <?php include 'common/footer.php'; ?>
        <!--  End footer  -->

    </div>

    <script src="common/assets/js/lib/gsap_lib/gsap.min.js"></script>
    <script src="common/assets/js/lib/gsap_lib/ScrollSmoother.min.js"></script>
    <script src="common/assets/js/lib/jquery.js"></script>
    <script src="common/assets/js/lib/jquery-migrate.min.js"></script>
    <script src="common/assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="common/assets/js/lib/wow.min.js"></script>
    <script src="common/assets/js/lib/swiper8.min.js"></script>
    <script src="common/assets/js/lib/jquery.fancybox.min.js"></script>
    <script src="common/assets/js/lib/lity.min.js"></script>
    <script src="common/assets/js/lib/jquery.smartmenus.min.js"></script>
    <script src="common/assets/js/lib/theme.js"></script>
    <script src="assets/main.js"></script>

    <script>
        // Image thumbnail click handler
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('mainProjectImage');
            const thumbnails = document.querySelectorAll('.project-thumb');
            
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    // Remove active class from all thumbnails
                    thumbnails.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked thumbnail
                    this.classList.add('active');
                    // Update main image
                    mainImage.src = this.src;
                });
            });
        });
    </script>

</body>

</html>

