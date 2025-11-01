<?php
requireLogin();

// Regenerate session ID periodically (only on admin pages, not on every request)
// Reduced frequency to avoid issues with concurrent tabs
if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration'] > (SESSION_REGENERATE_INTERVAL * 2))) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Session Keep-Alive Script -->
    <script src="assets/js/session-keepalive.js"></script>
    
    <style>
        .sidebar-link.active {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
        }
        
        /* Layout improvements */
        .main-content-mobile {
            min-height: 100vh;
        }
        
        .desktop-header, .mobile-header {
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .mobile-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .mobile-sidebar.open {
                transform: translateX(0);
            }
            
            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            
            .mobile-overlay.open {
                display: block;
            }
            
            .main-content-mobile {
                margin-left: 0;
            }
            
            .mobile-header {
                display: flex;
                position: sticky;
                top: 0;
                z-index: 30;
            }
            
            .desktop-header {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table-responsive table {
                min-width: 600px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .card-mobile {
                padding: 1rem;
            }
            
            .btn-mobile {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            /* Improve mobile header spacing */
            .mobile-header .flex {
                align-items: center;
                min-height: 60px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .mobile-header h2 {
                font-size: 1.25rem;
            }
            
            .mobile-header p {
                font-size: 0.75rem;
            }
            
            .card-mobile {
                padding: 0.75rem;
            }
            
            .btn-mobile {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        /* Hide mobile elements on desktop */
        @media (min-width: 769px) {
            .mobile-header {
                display: none;
            }
            
            .desktop-header {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: none;
            }
        }
        
        /* User dropdown fixes */
        #userDropdownContainer, #mobileUserDropdownContainer {
            position: relative;
        }
        
        #userDropdown, #mobileUserDropdown {
            min-width: 192px;
        }
        
        /* Ensure dropdown button is clickable */
        #userDropdownBtn, [id*="UserDropdownBtn"] {
            pointer-events: all;
            touch-action: manipulation;
        }
        
        /* Gallery thumbnail styles */
        .gallery-img-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .gallery-img-container img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
        }
        
        .gallery-img-container button {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #dc2626;
            color: white;
            border: none;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
            z-index: 10;
        }
        
        .gallery-img-container button:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar -->
        <aside class="mobile-sidebar fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white lg:relative lg:translate-x-0 lg:z-auto shadow-xl">
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-500 rounded-lg p-2">
                            <i class="fas fa-building text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">FlipAvenue</h1>
                            <p class="text-xs text-gray-300">CMS Panel</p>
                        </div>
                    </div>
                    <button class="lg:hidden text-white hover:text-gray-300 p-1 rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <nav class="p-4 space-y-1">
                <a href="index.php" class="sidebar-link <?php echo $current_page === 'index' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs uppercase text-gray-500 font-semibold">Content</p>
                </div>
                
                <a href="services.php" class="sidebar-link <?php echo $current_page === 'services' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-briefcase w-5"></i>
                    <span>Services</span>
                </a>
                
                <a href="projects.php" class="sidebar-link <?php echo $current_page === 'projects' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-folder-open w-5"></i>
                    <span>Projects</span>
                </a>
                
                <a href="team.php" class="sidebar-link <?php echo $current_page === 'team' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>Team</span>
                </a>
                
                <a href="blog.php" class="sidebar-link <?php echo $current_page === 'blog' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-newspaper w-5"></i>
                    <span>Blog</span>
                </a>
                
                <a href="testimonials.php" class="sidebar-link <?php echo $current_page === 'testimonials' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-quote-right w-5"></i>
                    <span>Testimonials</span>
                </a>
                
                <a href="awards.php" class="sidebar-link <?php echo $current_page === 'awards' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-trophy w-5"></i>
                    <span>Awards</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs uppercase text-gray-500 font-semibold">E-commerce</p>
                </div>
                
                <a href="shop.php" class="sidebar-link <?php echo $current_page === 'shop' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-shopping-bag w-5"></i>
                    <span>Products</span>
                </a>
                
                <a href="orders.php" class="sidebar-link <?php echo $current_page === 'orders' || $current_page === 'order-details' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span>Orders</span>
                </a>
                
                <a href="sales-analytics.php" class="sidebar-link <?php echo $current_page === 'sales-analytics' || $current_page === 'sales' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Sales Analytics</span>
                </a>
                
                <a href="transactions.php" class="sidebar-link <?php echo $current_page === 'transactions' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-credit-card w-5"></i>
                    <span>Transactions</span>
                </a>
                
                <a href="shipping-tax-settings.php" class="sidebar-link <?php echo $current_page === 'shipping-tax-settings' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-shipping-fast w-5"></i>
                    <span>Shipping & Tax Settings</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs uppercase text-gray-500 font-semibold">System</p>
                </div>
                
                <a href="contact-submissions.php" class="sidebar-link <?php echo $current_page === 'contact-submissions' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Contact Forms</span>
                </a>
                
                <a href="job-openings.php" class="sidebar-link <?php echo $current_page === 'job-openings' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-briefcase w-5"></i>
                    <span>Job Openings</span>
                </a>
                <a href="careers.php" class="sidebar-link <?php echo $current_page === 'careers' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-user-tie w-5"></i>
                    <span>Career Applications</span>
                </a>
                
                <a href="settings.php" class="sidebar-link <?php echo $current_page === 'settings' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
                
                <?php if ($_SESSION['admin_role'] === 'super_admin' || $_SESSION['admin_role'] === 'admin'): ?>
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs uppercase text-gray-500 font-semibold">Security</p>
                </div>
                
                <a href="security-dashboard.php" class="sidebar-link <?php echo $current_page === 'security-dashboard' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-shield-alt w-5"></i>
                    <span>Security Dashboard</span>
                </a>
                
                <a href="change-password.php" class="sidebar-link <?php echo $current_page === 'change-password' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-key w-5"></i>
                    <span>Change Password</span>
                </a>
                
                <a href="reset-password.php" class="sidebar-link <?php echo $current_page === 'reset-password' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-shield-alt w-5"></i>
                    <span>Reset User Password</span>
                </a>
                
                <a href="manage-users.php" class="sidebar-link <?php echo $current_page === 'manage-users' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-users-cog w-5"></i>
                    <span>Manage Users</span>
                </a>
                <?php endif; ?>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden main-content-mobile">
            <!-- Mobile Header -->
            <header class="mobile-header bg-white shadow-sm z-10 lg:hidden border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center space-x-3 flex-1">
                        <button class="mobile-menu-btn text-gray-600 hover:text-gray-900 p-1" onclick="toggleMobileSidebar()">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold text-gray-800 leading-tight"><?php echo $page_title ?? 'Dashboard'; ?></h2>
                            <?php if (isset($page_description)): ?>
                                <p class="text-xs text-gray-600 leading-tight"><?php echo $page_description; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <a href="<?php echo SITE_URL; ?>" target="_blank" class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        
                        <div class="relative" id="mobileUserDropdownContainer">
                            <button onclick="toggleMobileUserDropdown()" class="flex items-center space-x-2 bg-gray-50 hover:bg-gray-100 px-3 py-2 rounded-lg transition-all duration-200 border border-gray-200 cursor-pointer">
                                <i class="fas fa-user-circle text-lg text-gray-600"></i>
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-semibold text-gray-800 leading-tight"><?php echo $_SESSION['admin_name']; ?></p>
                                    <p class="text-xs text-gray-500 leading-tight"><?php echo ucfirst($_SESSION['admin_role']); ?></p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            
                            <div id="mobileUserDropdown" class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                                <a href="profile.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg transition-colors">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Desktop Header -->
            <header class="desktop-header bg-white shadow-sm z-10 border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold text-gray-800 mb-1 truncate"><?php echo $page_title ?? 'Dashboard'; ?></h2>
                        <?php if (isset($page_description)): ?>
                            <p class="text-sm text-gray-600 truncate"><?php echo $page_description; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                        <a href="<?php echo SITE_URL; ?>" target="_blank" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 whitespace-nowrap">
                            <i class="fas fa-external-link-alt"></i>
                            <span class="text-sm font-medium hidden sm:inline">View Site</span>
                        </a>
                        
                        <div class="relative" id="userDropdownContainer">
                            <button onclick="toggleUserDropdown()" class="flex items-center space-x-3 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-xl transition-all duration-200 border border-gray-200 hover:border-gray-300 cursor-pointer">
                                <i class="fas fa-user-circle text-xl text-gray-600"></i>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800 leading-tight whitespace-nowrap"><?php echo $_SESSION['admin_name']; ?></p>
                                    <p class="text-xs text-gray-500 leading-tight whitespace-nowrap"><?php echo ucfirst($_SESSION['admin_role']); ?></p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            
                            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                                <a href="profile.php" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl transition-colors">
                                    <i class="fas fa-user mr-3"></i>Profile
                                </a>
                                <a href="logout.php" class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">

