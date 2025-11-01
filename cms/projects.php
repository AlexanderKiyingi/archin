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
            
            // Handle gallery images upload
            $gallery_images_json = null;
            if ($action === 'edit' && !empty($_POST['current_gallery_images'])) {
                $gallery_images_json = $_POST['current_gallery_images'];
            }
            
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $gallery_images = [];
                foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                    $file = [
                        'name' => $_FILES['gallery_images']['name'][$key],
                        'tmp_name' => $tmp_name,
                        'size' => $_FILES['gallery_images']['size'][$key],
                        'error' => $_FILES['gallery_images']['error'][$key]
                    ];
                    if ($file['error'] === 0) {
                        $upload = uploadFile($file, 'projects');
                        if ($upload['success']) {
                            $gallery_images[] = $upload['path'];
                        }
                    }
                }
                if (!empty($gallery_images)) {
                    if ($action === 'edit' && !empty($gallery_images_json)) {
                        $existing = json_decode($gallery_images_json, true);
                        if (!is_array($existing)) {
                            $existing = [];
                        }
                        $gallery_images_json = json_encode(array_merge($existing, $gallery_images));
                    } else {
                        $gallery_images_json = json_encode($gallery_images);
                    }
                }
            }
            
            if ($action === 'add') {
                // Format: sssssssssssi (10 strings, 2 integers)
                $sql = "INSERT INTO projects (title, slug, category, description, short_description, featured_image, gallery_images, location, client_name, completion_date, is_featured, display_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssssiii", $title, $slug, $category, $description, $short_description, $featured_image, $gallery_images_json, $location, $client_name, $completion_date, $is_featured, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Project added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding project: ' . $conn->error;
                    $message_type = 'error';
                }
            } else {
                $id = (int)$_POST['id'];
                
                // Build UPDATE query dynamically based on what changed
                $updates = [];
                $params = [];
                $types = '';
                
                $updates[] = "title = ?";
                $params[] = $title;
                $types .= 's';
                
                $updates[] = "slug = ?";
                $params[] = $slug;
                $types .= 's';
                
                $updates[] = "category = ?";
                $params[] = $category;
                $types .= 's';
                
                $updates[] = "description = ?";
                $params[] = $description;
                $types .= 's';
                
                $updates[] = "short_description = ?";
                $params[] = $short_description;
                $types .= 's';
                
                if ($featured_image) {
                    $updates[] = "featured_image = ?";
                    $params[] = $featured_image;
                    $types .= 's';
                }
                
                if ($gallery_images_json !== null) {
                    $updates[] = "gallery_images = ?";
                    $params[] = $gallery_images_json;
                    $types .= 's';
                }
                
                $updates[] = "location = ?";
                $params[] = $location;
                $types .= 's';
                
                $updates[] = "client_name = ?";
                $params[] = $client_name;
                $types .= 's';
                
                $updates[] = "completion_date = ?";
                $params[] = $completion_date;
                $types .= 's';
                
                $updates[] = "is_featured = ?";
                $params[] = $is_featured;
                $types .= 'i';
                
                $updates[] = "display_order = ?";
                $params[] = $display_order;
                $types .= 'i';
                
                $updates[] = "is_active = ?";
                $params[] = $is_active;
                $types .= 'i';
                
                $params[] = $id;
                $types .= 'i';
                
                $sql = "UPDATE projects SET " . implode(', ', $updates) . " WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                
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
            <div class="flex gap-3">
                <a href="project-categories.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-folder mr-2"></i>Manage Categories
                </a>
                <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add New Project
                </a>
            </div>
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
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gallery Images
                    </label>
                    <input 
                        type="file" 
                        name="gallery_images[]" 
                        accept="image/*"
                        multiple
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <p class="text-sm text-gray-500 mt-1">You can select multiple images for the project gallery</p>
                    <?php if ($edit_project && !empty($edit_project['gallery_images'])): 
                        $existing_gallery = json_decode($edit_project['gallery_images'], true);
                        if (is_array($existing_gallery) && !empty($existing_gallery)):
                    ?>
                        <input type="hidden" name="current_gallery_images" value="<?php echo htmlspecialchars($edit_project['gallery_images']); ?>">
                        <div class="mt-3 grid grid-cols-4 gap-2">
                            <?php foreach ($existing_gallery as $img): ?>
                                <img src="<?php echo UPLOAD_URL . $img; ?>" alt="" class="w-20 h-20 object-cover rounded border">
                            <?php endforeach; ?>
                        </div>
                    <?php 
                        endif;
                    endif; ?>
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

