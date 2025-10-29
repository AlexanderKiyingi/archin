<?php
require_once 'config.php';
$page_title = 'Projects Management';
$page_description = 'Manage your portfolio projects';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $title = cleanInput($_POST['title']);
            $slug = generateSlug($_POST['slug'] ?: $title);
            $category = cleanInput($_POST['category']);
            $description = cleanInput($_POST['description']);
            $short_description = cleanInput($_POST['short_description']);
            $location = cleanInput($_POST['location']);
            $client_name = cleanInput($_POST['client_name']);
            $completion_date = $_POST['completion_date'];
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Handle featured image upload
            $featured_image = '';
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
                $upload = uploadFile($_FILES['featured_image'], 'projects');
                if ($upload['success']) {
                    $featured_image = $upload['path'];
                }
            }
            
            if ($action === 'add') {
                // Format: sssssssssiii (9 strings, 3 integers)
                $sql = "INSERT INTO projects (title, slug, category, description, short_description, featured_image, location, client_name, completion_date, is_featured, display_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssiii", $title, $slug, $category, $description, $short_description, $featured_image, $location, $client_name, $completion_date, $is_featured, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Project added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding project: ' . $conn->error;
                    $message_type = 'error';
                }
            } else {
                $id = (int)$_POST['id'];
                if ($featured_image) {
                    // Format: sssssssssiiii (9 strings, 4 integers)
                    $sql = "UPDATE projects SET title = ?, slug = ?, category = ?, description = ?, short_description = ?, featured_image = ?, location = ?, client_name = ?, completion_date = ?, is_featured = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssssiiii", $title, $slug, $category, $description, $short_description, $featured_image, $location, $client_name, $completion_date, $is_featured, $display_order, $is_active, $id);
                } else {
                    // Format: ssssssssiiii (8 strings, 4 integers)
                    $sql = "UPDATE projects SET title = ?, slug = ?, category = ?, description = ?, short_description = ?, location = ?, client_name = ?, completion_date = ?, is_featured = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssssiiii", $title, $slug, $category, $description, $short_description, $location, $client_name, $completion_date, $is_featured, $display_order, $is_active, $id);
                }
                
                if ($stmt->execute()) {
                    $message = 'Project updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating project: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            $sql = "DELETE FROM projects WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Project deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting project: ' . $conn->error;
                $message_type = 'error';
            }
        }
    }
}

// Get all projects
$projects = $conn->query("SELECT * FROM projects ORDER BY display_order ASC, id DESC");

// Get categories
$categories = $conn->query("SELECT * FROM project_categories WHERE is_active = 1 ORDER BY display_order ASC");

// Get single project for editing
$edit_project = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM projects WHERE id = $id");
    $edit_project = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <!-- Projects List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">All Projects</h3>
            <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Add New Project
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($project = $projects->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if ($project['featured_image']): ?>
                                        <img src="<?php echo UPLOAD_URL . $project['featured_image']; ?>" alt="" class="w-12 h-12 rounded object-cover mr-3">
                                    <?php endif; ?>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($project['title']); ?></div>
                                        <?php if ($project['is_featured']): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i> Featured
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($project['category']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo htmlspecialchars($project['location']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $project['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $project['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="?action=edit&id=<?php echo $project['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" class="inline" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <!-- Add/Edit Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <?php echo $edit_project ? 'Edit Project' : 'Add New Project'; ?>
            </h3>
            <a href="projects.php" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_project ? 'edit' : 'add'; ?>">
            <?php if ($edit_project): ?>
                <input type="hidden" name="id" value="<?php echo $edit_project['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Project Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['title']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (URL friendly)
                    </label>
                    <input 
                        type="text" 
                        name="slug" 
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['slug']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="auto-generated if empty"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="category" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="">Select Category</option>
                        <?php 
                        $categories->data_seek(0);
                        while ($cat = $categories->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $cat['name']; ?>" <?php echo ($edit_project && $edit_project['category'] === $cat['name']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Location
                    </label>
                    <input 
                        type="text" 
                        name="location" 
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['location']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client Name
                    </label>
                    <input 
                        type="text" 
                        name="client_name" 
                        value="<?php echo $edit_project ? htmlspecialchars($edit_project['client_name']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Completion Date
                    </label>
                    <input 
                        type="date" 
                        name="completion_date" 
                        value="<?php echo $edit_project ? $edit_project['completion_date'] : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input 
                        type="number" 
                        name="display_order" 
                        value="<?php echo $edit_project ? $edit_project['display_order'] : '0'; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Short Description
                    </label>
                    <textarea 
                        name="short_description" 
                        rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ><?php echo $edit_project ? htmlspecialchars($edit_project['short_description']) : ''; ?></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Description
                    </label>
                    <textarea 
                        name="description" 
                        rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ><?php echo $edit_project ? htmlspecialchars($edit_project['description']) : ''; ?></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Featured Image
                    </label>
                    <input 
                        type="file" 
                        name="featured_image" 
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <?php if ($edit_project && $edit_project['featured_image']): ?>
                        <div class="mt-2">
                            <img src="<?php echo UPLOAD_URL . $edit_project['featured_image']; ?>" alt="" class="w-32 h-32 object-cover rounded">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="md:col-span-2 flex items-center space-x-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_featured" 
                            value="1"
                            <?php echo ($edit_project && $edit_project['is_featured']) ? 'checked' : ''; ?>
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Featured Project</span>
                    </label>
                    
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            <?php echo (!$edit_project || $edit_project['is_active']) ? 'checked' : ''; ?>
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="projects.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i><?php echo $edit_project ? 'Update' : 'Add'; ?> Project
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

