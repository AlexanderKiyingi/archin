<?php
require_once 'config.php';
$page_title = 'Team Management';
$page_description = 'Manage team members';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $full_name = cleanInput($_POST['full_name']);
            $position = cleanInput($_POST['position']);
            $bio = cleanInput($_POST['bio']);
            $email = cleanInput($_POST['email']);
            $phone = cleanInput($_POST['phone']);
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Handle photo upload
            $photo = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $upload = uploadFile($_FILES['photo'], 'team');
                if ($upload['success']) {
                    $photo = $upload['path'];
                }
            }
            
            if ($action === 'add') {
                $sql = "INSERT INTO team_members (full_name, position, bio, photo, email, phone, display_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssii", $full_name, $position, $bio, $photo, $email, $phone, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Team member added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding team member';
                    $message_type = 'error';
                }
            } else {
                $id = (int)$_POST['id'];
                if ($photo) {
                    $sql = "UPDATE team_members SET full_name = ?, position = ?, bio = ?, photo = ?, email = ?, phone = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssiii", $full_name, $position, $bio, $photo, $email, $phone, $display_order, $is_active, $id);
                } else {
                    $sql = "UPDATE team_members SET full_name = ?, position = ?, bio = ?, email = ?, phone = ?, display_order = ?, is_active = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssiii", $full_name, $position, $bio, $email, $phone, $display_order, $is_active, $id);
                }
                
                if ($stmt->execute()) {
                    $message = 'Team member updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating team member';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            if ($conn->query("DELETE FROM team_members WHERE id = $id")) {
                $message = 'Team member deleted successfully!';
                $message_type = 'success';
            }
        }
    }
}

$team_members = $conn->query("SELECT * FROM team_members ORDER BY display_order ASC, id DESC");

$edit_member = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM team_members WHERE id = $id");
    $edit_member = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Team Members</h3>
            <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add Team Member
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            <?php while ($member = $team_members->fetch_assoc()): ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                    <?php if ($member['photo']): ?>
                        <img src="<?php echo UPLOAD_URL . $member['photo']; ?>" alt="" class="w-full h-48 object-cover rounded-lg mb-3">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 rounded-lg mb-3 flex items-center justify-center">
                            <i class="fas fa-user text-6xl text-gray-400"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($member['full_name']); ?></h4>
                    <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($member['position']); ?></p>
                    
                    <?php if ($member['email']): ?>
                        <p class="text-xs text-gray-500 mb-1"><i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($member['email']); ?></p>
                    <?php endif; ?>
                    
                    <div class="mt-3 flex justify-between items-center">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $member['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                        
                        <div class="space-x-2">
                            <a href="?action=edit&id=<?php echo $member['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" class="inline" onsubmit="return confirmDelete();">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
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
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <?php echo $edit_member ? 'Edit Team Member' : 'Add Team Member'; ?>
            </h3>
            <a href="team.php" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_member ? 'edit' : 'add'; ?>">
            <?php if ($edit_member): ?>
                <input type="hidden" name="id" value="<?php echo $edit_member['id']; ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="full_name" 
                        value="<?php echo $edit_member ? htmlspecialchars($edit_member['full_name']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Position
                    </label>
                    <input 
                        type="text" 
                        name="position" 
                        value="<?php echo $edit_member ? htmlspecialchars($edit_member['position']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo $edit_member ? htmlspecialchars($edit_member['email']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phone
                    </label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="<?php echo $edit_member ? htmlspecialchars($edit_member['phone']) : ''; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bio
                    </label>
                    <textarea 
                        name="bio" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    ><?php echo $edit_member ? htmlspecialchars($edit_member['bio']) : ''; ?></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Photo
                    </label>
                    <input 
                        type="file" 
                        name="photo" 
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                    >
                    <?php if ($edit_member && $edit_member['photo']): ?>
                        <div class="mt-2">
                            <img src="<?php echo UPLOAD_URL . $edit_member['photo']; ?>" alt="" class="w-32 h-32 object-cover rounded">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input 
                        type="number" 
                        name="display_order" 
                        value="<?php echo $edit_member ? $edit_member['display_order'] : '0'; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                    >
                </div>
                
                <div class="flex items-center">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            <?php echo (!$edit_member || $edit_member['is_active']) ? 'checked' : ''; ?>
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                        >
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="team.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i><?php echo $edit_member ? 'Update' : 'Add'; ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

