<?php
require_once 'config.php';
$page_title = 'Dashboard';
$page_description = 'Overview of your website content';

// Get statistics
$stats = [];

$result = $conn->query("SELECT COUNT(*) as count FROM services WHERE is_active = 1");
$stats['services'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM projects WHERE is_active = 1");
$stats['projects'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM team_members WHERE is_active = 1");
$stats['team'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM blog_posts WHERE is_published = 1");
$stats['blog'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM testimonials WHERE is_active = 1");
$stats['testimonials'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM awards WHERE is_active = 1");
$stats['awards'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'new'");
$stats['new_contacts'] = $result->fetch_assoc()['count'];

// Recent contact submissions
$recent_contacts = $conn->query("SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5");

// Recent blog posts
$recent_posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 5");

include 'includes/header.php';
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Services Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm uppercase">Services</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo $stats['services']; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-briefcase text-3xl"></i>
            </div>
        </div>
        <a href="services.php" class="mt-4 inline-block text-sm text-blue-100 hover:text-white">
            View all <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <!-- Projects Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm uppercase">Projects</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo $stats['projects']; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-folder-open text-3xl"></i>
            </div>
        </div>
        <a href="projects.php" class="mt-4 inline-block text-sm text-green-100 hover:text-white">
            View all <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <!-- Team Card -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm uppercase">Team Members</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo $stats['team']; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
        <a href="team.php" class="mt-4 inline-block text-sm text-purple-100 hover:text-white">
            View all <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <!-- Contact Forms Card -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm uppercase">New Messages</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo $stats['new_contacts']; ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-envelope text-3xl"></i>
            </div>
        </div>
        <a href="contact-submissions.php" class="mt-4 inline-block text-sm text-orange-100 hover:text-white">
            View all <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Blog Posts</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1"><?php echo $stats['blog']; ?></h4>
            </div>
            <i class="fas fa-newspaper text-3xl text-gray-300"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Testimonials</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1"><?php echo $stats['testimonials']; ?></h4>
            </div>
            <i class="fas fa-quote-right text-3xl text-gray-300"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Awards</p>
                <h4 class="text-2xl font-bold text-gray-800 mt-1"><?php echo $stats['awards']; ?></h4>
            </div>
            <i class="fas fa-trophy text-3xl text-gray-300"></i>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Contact Submissions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Contact Submissions</h3>
        </div>
        <div class="p-6">
            <?php if ($recent_contacts->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($contact = $recent_contacts->fetch_assoc()): ?>
                        <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($contact['name']); ?></p>
                                <p class="text-xs text-gray-600 truncate"><?php echo htmlspecialchars($contact['email']); ?></p>
                                <p class="text-xs text-gray-500 mt-1"><?php echo date('M d, Y H:i', strtotime($contact['created_at'])); ?></p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $contact['status'] === 'new' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                <?php echo ucfirst($contact['status']); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
                <a href="contact-submissions.php" class="mt-4 block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                    View all submissions <i class="fas fa-arrow-right ml-1"></i>
                </a>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No contact submissions yet</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Blog Posts -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Blog Posts</h3>
        </div>
        <div class="p-6">
            <?php if ($recent_posts->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($post = $recent_posts->fetch_assoc()): ?>
                        <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-newspaper text-purple-600"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($post['title']); ?></p>
                                <p class="text-xs text-gray-500 mt-1"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $post['is_published'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                <?php echo $post['is_published'] ? 'Published' : 'Draft'; ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
                <a href="blog.php" class="mt-4 block text-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                    View all posts <i class="fas fa-arrow-right ml-1"></i>
                </a>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No blog posts yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="services.php?action=add" class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
            <i class="fas fa-plus-circle text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
            <span class="text-sm text-gray-600 group-hover:text-blue-600">Add Service</span>
        </a>
        
        <a href="projects.php?action=add" class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition group">
            <i class="fas fa-plus-circle text-3xl text-gray-400 group-hover:text-green-500 mb-2"></i>
            <span class="text-sm text-gray-600 group-hover:text-green-600">Add Project</span>
        </a>
        
        <a href="blog.php?action=add" class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
            <i class="fas fa-plus-circle text-3xl text-gray-400 group-hover:text-purple-500 mb-2"></i>
            <span class="text-sm text-gray-600 group-hover:text-purple-600">Add Blog Post</span>
        </a>
        
        <a href="team.php?action=add" class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition group">
            <i class="fas fa-plus-circle text-3xl text-gray-400 group-hover:text-orange-500 mb-2"></i>
            <span class="text-sm text-gray-600 group-hover:text-orange-600">Add Team Member</span>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

