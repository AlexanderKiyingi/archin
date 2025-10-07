<?php
require_once 'config.php';
requireLogin();

$page_title = 'Blog Management';
$page_description = 'Manage blog posts and articles';

// Handle form submissions
$message = '';
$message_type = '';

// Add/Edit Blog Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_post'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = cleanInput($_POST['title']);
    $slug = !empty($_POST['slug']) ? generateSlug($_POST['slug']) : generateSlug($title);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $excerpt = cleanInput($_POST['excerpt']);
    $category = cleanInput($_POST['category']);
    $tags = cleanInput($_POST['tags']);
    $publish_date = cleanInput($_POST['publish_date']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $author_id = $_SESSION['admin_id'];
    
    // Handle image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $upload = uploadFile($_FILES['featured_image'], 'blog');
        if ($upload['success']) {
            $featured_image = $upload['path'];
        }
    }
    
    if ($id > 0) {
        // Update existing post
        $sql = "UPDATE blog_posts SET 
                title = ?, 
                slug = ?, 
                content = ?, 
                excerpt = ?, 
                category = ?, 
                tags = ?, 
                publish_date = ?,
                is_published = ?";
        
        if (!empty($featured_image)) {
            $sql .= ", featured_image = ?";
        }
        
        $sql .= " WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($featured_image)) {
            $stmt->bind_param("ssssssssii", $title, $slug, $content, $excerpt, $category, $tags, $publish_date, $is_published, $featured_image, $id);
        } else {
            $stmt->bind_param("sssssssii", $title, $slug, $content, $excerpt, $category, $tags, $publish_date, $is_published, $id);
        }
        
        if ($stmt->execute()) {
            $message = 'Blog post updated successfully!';
            $message_type = 'success';
        } else {
            $message = 'Error updating blog post.';
            $message_type = 'error';
        }
    } else {
        // Insert new post
        $stmt = $conn->prepare("INSERT INTO blog_posts (title, slug, content, excerpt, featured_image, category, tags, publish_date, is_published, author_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssii", $title, $slug, $content, $excerpt, $featured_image, $category, $tags, $publish_date, $is_published, $author_id);
        
        if ($stmt->execute()) {
            $message = 'Blog post created successfully!';
            $message_type = 'success';
        } else {
            $message = 'Error creating blog post.';
            $message_type = 'error';
        }
    }
}

// Delete Blog Post
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = 'Blog post deleted successfully!';
        $message_type = 'success';
    } else {
        $message = 'Error deleting blog post.';
        $message_type = 'error';
    }
}

// Get all blog posts
$posts_query = "SELECT bp.*, au.full_name as author_name 
                FROM blog_posts bp 
                LEFT JOIN admin_users au ON bp.author_id = au.id 
                ORDER BY bp.created_at DESC";
$posts_result = $conn->query($posts_query);

// Get single post for editing
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM blog_posts WHERE id = $edit_id");
    $edit_post = $edit_result->fetch_assoc();
}

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<!-- Add/Edit Form -->
<?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_post): ?>
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">
            <?php echo $edit_post ? 'Edit Blog Post' : 'Add New Blog Post'; ?>
        </h3>
        <a href="blog.php" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-times text-xl"></i>
        </a>
    </div>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php if ($edit_post): ?>
            <input type="hidden" name="id" value="<?php echo $edit_post['id']; ?>">
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" required 
                       value="<?php echo $edit_post ? htmlspecialchars($edit_post['title']) : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter blog post title">
            </div>
            
            <!-- Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL)</label>
                <input type="text" name="slug" 
                       value="<?php echo $edit_post ? htmlspecialchars($edit_post['slug']) : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="auto-generated-from-title">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from title</p>
            </div>
            
            <!-- Publish Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Publish Date *</label>
                <input type="date" name="publish_date" required
                       value="<?php echo $edit_post ? $edit_post['publish_date'] : date('Y-m-d'); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Category</option>
                    <option value="Architecture" <?php echo ($edit_post && $edit_post['category'] === 'Architecture') ? 'selected' : ''; ?>>Architecture</option>
                    <option value="Interior Design" <?php echo ($edit_post && $edit_post['category'] === 'Interior Design') ? 'selected' : ''; ?>>Interior Design</option>
                    <option value="Guide" <?php echo ($edit_post && $edit_post['category'] === 'Guide') ? 'selected' : ''; ?>>Guide</option>
                    <option value="Inspiration" <?php echo ($edit_post && $edit_post['category'] === 'Inspiration') ? 'selected' : ''; ?>>Inspiration</option>
                    <option value="News" <?php echo ($edit_post && $edit_post['category'] === 'News') ? 'selected' : ''; ?>>News</option>
                    <option value="Tips & Tricks" <?php echo ($edit_post && $edit_post['category'] === 'Tips & Tricks') ? 'selected' : ''; ?>>Tips & Tricks</option>
                </select>
            </div>
            
            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <input type="text" name="tags" 
                       value="<?php echo $edit_post ? htmlspecialchars($edit_post['tags']) : ''; ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="design, architecture, modern">
                <p class="text-xs text-gray-500 mt-1">Separate tags with commas</p>
            </div>
            
            <!-- Featured Image -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <?php if ($edit_post && $edit_post['featured_image']): ?>
                    <div class="mt-2">
                        <img src="<?php echo UPLOAD_URL . $edit_post['featured_image']; ?>" 
                             alt="Current image" class="h-32 rounded-lg">
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Excerpt -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                <textarea name="excerpt" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Short description of the post..."><?php echo $edit_post ? htmlspecialchars($edit_post['excerpt']) : ''; ?></textarea>
            </div>
            
            <!-- Content -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea name="content" rows="15" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                          placeholder="Enter your blog post content here (HTML supported)..."><?php echo $edit_post ? htmlspecialchars($edit_post['content']) : ''; ?></textarea>
                <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
            </div>
            
            <!-- Published Status -->
            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_published" value="1" 
                           <?php echo ($edit_post && $edit_post['is_published']) ? 'checked' : ''; ?>
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Publish this post</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Unpublished posts will be saved as drafts</p>
            </div>
        </div>
        
        <div class="flex gap-3 pt-4 border-t">
            <button type="submit" name="save_post" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>
                <?php echo $edit_post ? 'Update Post' : 'Create Post'; ?>
            </button>
            <a href="blog.php" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php else: ?>

<!-- Blog Posts List -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Blog Posts</h3>
        <a href="blog.php?action=add" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Add New Post
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($posts_result->num_rows > 0): ?>
                    <?php while ($post = $posts_result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if ($post['featured_image']): ?>
                                        <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" 
                                             alt="" class="w-12 h-12 rounded object-cover mr-3">
                                    <?php endif; ?>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo htmlspecialchars($post['slug']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    <?php echo $post['category'] ?: 'Uncategorized'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo htmlspecialchars($post['author_name'] ?: 'Unknown'); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo date('M d, Y', strtotime($post['publish_date'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($post['is_published']): ?>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Published
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Draft
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="blog.php?edit=<?php echo $post['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo SITE_URL; ?>/single.php?slug=<?php echo $post['slug']; ?>" 
                                   class="text-green-600 hover:text-green-800 mr-3" target="_blank" title="View Post">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="blog.php?delete=<?php echo $post['id']; ?>" 
                                   class="text-red-600 hover:text-red-800"
                                   onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>No blog posts yet. Click "Add New Post" to create your first post.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
