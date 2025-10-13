<?php
requireLogin();
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex-shrink-0">
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-building text-3xl text-blue-400"></i>
                    <div>
                        <h1 class="text-xl font-bold">FlipAvenue</h1>
                        <p class="text-xs text-gray-400">CMS Panel</p>
                    </div>
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
                
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs uppercase text-gray-500 font-semibold">System</p>
                </div>
                
                <a href="contact-submissions.php" class="sidebar-link <?php echo $current_page === 'contact-submissions' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Contact Forms</span>
                </a>
                
                <a href="careers.php" class="sidebar-link <?php echo $current_page === 'careers' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-briefcase w-5"></i>
                    <span>Career Applications</span>
                </a>
                
                <a href="settings.php" class="sidebar-link <?php echo $current_page === 'settings' ? 'active' : ''; ?> flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800"><?php echo $page_title ?? 'Dashboard'; ?></h2>
                        <?php if (isset($page_description)): ?>
                            <p class="text-sm text-gray-600"><?php echo $page_description; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo SITE_URL; ?>" target="_blank" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-external-link-alt"></i>
                            <span class="ml-2 text-sm">View Site</span>
                        </a>
                        
                        <div class="relative group">
                            <button class="flex items-center space-x-2 bg-gray-100 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                                <i class="fas fa-user-circle text-2xl text-gray-600"></i>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800"><?php echo $_SESSION['admin_name']; ?></p>
                                    <p class="text-xs text-gray-500"><?php echo ucfirst($_SESSION['admin_role']); ?></p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-600"></i>
                            </button>
                            
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden group-hover:block">
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">

