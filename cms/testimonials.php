<?php
require_once 'config.php';
$page_title = 'Testimonials Management';
$page_description = 'Manage client testimonials';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $client_name = cleanInput($_POST['client_name']);
            $client_position = cleanInput($_POST['client_position']);
            $client_company = cleanInput($_POST['client_company']);
            $testimonial_text = cleanInput($_POST['testimonial_text']);
            $project_name = cleanInput($_POST['project_name']);
            $rating = (int)$_POST['rating'];
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Handle photo upload
            $client_photo = '';
            if (isset($_FILES['client_photo']) && $_FILES['client_photo']['error'] === 0) {
                $upload = uploadFile($_FILES['client_photo'], 'testimonials');
                if ($upload['success']) {
                    $client_photo = $upload['path'];
                }
            }
            
            if ($action === 'add') {
                $sql = "INSERT INTO testimonials (client_name, client_position, client_company, testimonial_text, client_photo, rating, project_name, display_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssisii", $client_name, $client_position, $client_company, $testimonial_text, $client_photo, $rating, $project_name, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Testimonial added successfully!';
                    $message_type = 'success';
                }
            } else {
                $id = (int)$_POST['id'];
                if ($client_photo) {
                    $sql = "UPDATE testimonials SET client_name = ?, client_position = ?, client_company = ?, testimonial_text = ?, client_photo = ?, rating = ?, project_name = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssissii", $client_name, $client_position, $client_company, $testimonial_text, $client_photo, $rating, $project_name, $display_order, $is_active, $id);
                } else {
                    $sql = "UPDATE testimonials SET client_name = ?, client_position = ?, client_company = ?, testimonial_text = ?, rating = ?, project_name = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssisisii", $client_name, $client_position, $client_company, $testimonial_text, $rating, $project_name, $display_order, $is_active, $id);
                }
                
                if ($stmt->execute()) {
                    $message = 'Testimonial updated successfully!';
                    $message_type = 'success';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            if ($conn->query("DELETE FROM testimonials WHERE id = $id")) {
                $message = 'Testimonial deleted successfully!';
                $message_type = 'success';
            }
        }
    }
}

$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY display_order ASC, id DESC");

$edit_testimonial = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM testimonials WHERE id = $id");
    $edit_testimonial = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Testimonials</h3>
            <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add Testimonial
            </a>
        </div>
        
        <div class="divide-y">
            <?php while ($testimonial = $testimonials->fetch_assoc()): ?>
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($testimonial['client_name']); ?></h4>
                                <span class="ml-3 px-2 py-1 text-xs rounded-full <?php echo $testimonial['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $testimonial['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($testimonial['client_position']); ?> • <?php echo htmlspecialchars($testimonial['client_company']); ?></p>
                            <p class="text-gray-700 italic">"<?php echo htmlspecialchars(substr($testimonial['testimonial_text'], 0, 200)); ?>..."</p>
                            <div class="mt-2 flex items-center">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i < $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="ml-4 flex space-x-2">
                            <a href="?action=edit&id=<?php echo $testimonial['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" class="inline" onsubmit="return confirmDelete();">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold"><?php echo $edit_testimonial ? 'Edit Testimonial' : 'Add Testimonial'; ?></h3>
            <a href="testimonials.php" class="text-gray-600"><i class="fas fa-times"></i></a>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_testimonial ? 'edit' : 'add'; ?>">
            <?php if ($edit_testimonial): ?>
                <input type="hidden" name="id" value="<?php echo $edit_testimonial['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Name <span class="text-red-500">*</span></label>
                    <input type="text" name="client_name" value="<?php echo $edit_testimonial ? htmlspecialchars($edit_testimonial['client_name']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                    <input type="text" name="client_position" value="<?php echo $edit_testimonial ? htmlspecialchars($edit_testimonial['client_position']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <input type="text" name="client_company" value="<?php echo $edit_testimonial ? htmlspecialchars($edit_testimonial['client_company']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project Name</label>
                    <input type="text" name="project_name" value="<?php echo $edit_testimonial ? htmlspecialchars($edit_testimonial['project_name']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Testimonial <span class="text-red-500">*</span></label>
                    <textarea name="testimonial_text" rows="5" class="w-full px-4 py-2 border rounded-lg" required><?php echo $edit_testimonial ? htmlspecialchars($edit_testimonial['testimonial_text']) : ''; ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <select name="rating" class="w-full px-4 py-2 border rounded-lg">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($edit_testimonial && $edit_testimonial['rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Stars</option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="<?php echo $edit_testimonial ? $edit_testimonial['display_order'] : '0'; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Photo</label>
                    <input type="file" name="client_photo" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?php echo (!$edit_testimonial || $edit_testimonial['is_active']) ? 'checked' : ''; ?> class="w-4 h-4">
                        <span class="ml-2 text-sm">Active</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="testimonials.php" class="px-6 py-2 border rounded-lg">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i><?php echo $edit_testimonial ? 'Update' : 'Add'; ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

