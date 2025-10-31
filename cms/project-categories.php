<?php
require_once 'config.php';
$page_title = 'Project Categories';
$page_description = 'Manage project categories and their display order';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $name = cleanInput($_POST['name']);
            $slug = generateSlug($_POST['slug'] ?: $name);
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            if ($action === 'add') {
                $sql = "INSERT INTO project_categories (name, slug, display_order, is_active) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $name, $slug, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Category added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding category: ' . $conn->error;
                    $message_type = 'error';
                }
            } else {
                $id = (int)$_POST['id'];
                $sql = "UPDATE project_categories SET name = ?, slug = ?, display_order = ?, is_active = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssiii", $name, $slug, $display_order, $is_active, $id);
                
                if ($stmt->execute()) {
                    $message = 'Category updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating category: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            
            // Check if category is being used
            $check_sql = "SELECT COUNT(*) as count FROM projects WHERE category = (SELECT name FROM project_categories WHERE id = $id)";
            $check_result = $conn->query($check_sql);
            $check_row = $check_result->fetch_assoc();
            
            if ($check_row['count'] > 0) {
                $message = 'Cannot delete category: It is being used by ' . $check_row['count'] . ' project(s).';
                $message_type = 'error';
            } else {
                $sql = "DELETE FROM project_categories WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $message = 'Category deleted successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting category: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        }
    }
}

// Get all categories
$categories = $conn->query("SELECT * FROM project_categories ORDER BY display_order ASC, id DESC");

// Get single category for editing
$edit_category = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM project_categories WHERE id = $id");
    $edit_category = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <!-- Categories List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Project Categories</h3>
            <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Add New Category
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?php echo $category['display_order']; ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    <?php echo htmlspecialchars($category['slug']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $category['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $category['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="?action=edit&id=<?php echo $category['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" class="inline" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
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
                <?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?>
            </h3>
            <a href="project-categories.php" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
        
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_category ? 'edit' : 'add'; ?>">
            <?php if ($edit_category): ?>
                <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        value="<?php echo $edit_category ? htmlspecialchars($edit_category['name']) : ''; ?>"
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
                        value="<?php echo $edit_category ? htmlspecialchars($edit_category['slug']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="auto-generated if empty"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="display_order" 
                        value="<?php echo $edit_category ? $edit_category['display_order'] : '0'; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first in the list</p>
                </div>
                
                <div class="flex items-center mt-8">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            <?php echo ($edit_category && $edit_category['is_active']) ? 'checked' : 'checked'; ?>
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
                
                <div class="md:col-span-2">
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                    >
                        <?php echo $edit_category ? 'Update Category' : 'Add Category'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this category? This action cannot be undone.');
}
</script>

<?php include 'includes/footer.php'; ?>

