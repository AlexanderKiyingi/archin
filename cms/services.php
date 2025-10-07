<?php
require_once 'config.php';
$page_title = 'Services Management';
$page_description = 'Manage your company services';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $title = cleanInput($_POST['title']);
            $icon = cleanInput($_POST['icon']);
            $description = cleanInput($_POST['description']);
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Handle image upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $upload = uploadFile($_FILES['image'], 'services');
                if ($upload['success']) {
                    $image = $upload['path'];
                }
            }
            
            if ($action === 'add') {
                $sql = "INSERT INTO services (title, icon, description, image, display_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssis", $title, $icon, $description, $image, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Service added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding service: ' . $conn->error;
                    $message_type = 'error';
                }
            } else {
                $id = (int)$_POST['id'];
                if ($image) {
                    $sql = "UPDATE services SET title = ?, icon = ?, description = ?, image = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssiii", $title, $icon, $description, $image, $display_order, $is_active, $id);
                } else {
                    $sql = "UPDATE services SET title = ?, icon = ?, description = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssiii", $title, $icon, $description, $display_order, $is_active, $id);
                }
                
                if ($stmt->execute()) {
                    $message = 'Service updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating service: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            $sql = "DELETE FROM services WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Service deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting service: ' . $conn->error;
                $message_type = 'error';
            }
        }
    }
}

// Get all services
$services = $conn->query("SELECT * FROM services ORDER BY display_order ASC, id DESC");

// Get single service for editing
$edit_service = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM services WHERE id = $id");
    $edit_service = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <!-- Services List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">All Services</h3>
            <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Add New Service
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($service = $services->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($service['title']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <i class="<?php echo htmlspecialchars($service['icon']); ?> text-2xl text-gray-600"></i>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate"><?php echo htmlspecialchars($service['description']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php echo $service['display_order']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $service['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $service['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="?action=edit&id=<?php echo $service['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" class="inline" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
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
                <?php echo $edit_service ? 'Edit Service' : 'Add New Service'; ?>
            </h3>
            <a href="services.php" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_service ? 'edit' : 'add'; ?>">
            <?php if ($edit_service): ?>
                <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Service Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        value="<?php echo $edit_service ? htmlspecialchars($edit_service['title']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Icon Class <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="icon" 
                        value="<?php echo $edit_service ? htmlspecialchars($edit_service['icon']) : 'la la-'; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g. la la-hard-hat"
                        required
                    >
                    <p class="mt-1 text-xs text-gray-500">Use Line Awesome icons (la la-icon-name)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input 
                        type="number" 
                        name="display_order" 
                        value="<?php echo $edit_service ? $edit_service['display_order'] : '0'; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    ><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Service Image
                    </label>
                    <input 
                        type="file" 
                        name="image" 
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <?php if ($edit_service && $edit_service['image']): ?>
                        <p class="mt-2 text-sm text-gray-600">Current image: <?php echo htmlspecialchars($edit_service['image']); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            <?php echo (!$edit_service || $edit_service['is_active']) ? 'checked' : ''; ?>
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="services.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i><?php echo $edit_service ? 'Update' : 'Add'; ?> Service
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

