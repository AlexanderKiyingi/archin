<?php
require_once 'config.php';
$page_title = 'Awards & Recognition';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $title = cleanInput($_POST['title']);
            $year = (int)$_POST['year'];
            $organization = cleanInput($_POST['organization']);
            $location = cleanInput($_POST['location']);
            $description = cleanInput($_POST['description']);
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            if ($action === 'add') {
                $sql = "INSERT INTO awards (title, year, organization, location, description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sisssis", $title, $year, $organization, $location, $description, $display_order, $is_active);
                $stmt->execute() ? $message = 'Award added!' : $message = 'Error!';
                $message_type = 'success';
            } else {
                $id = (int)$_POST['id'];
                $sql = "UPDATE awards SET title = ?, year = ?, organization = ?, location = ?, description = ?, display_order = ?, is_active = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sisssiii", $title, $year, $organization, $location, $description, $display_order, $is_active, $id);
                $stmt->execute() ? $message = 'Award updated!' : $message = 'Error!';
                $message_type = 'success';
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            $conn->query("DELETE FROM awards WHERE id = $id");
            $message = 'Award deleted!';
            $message_type = 'success';
        }
    }
}

$awards = $conn->query("SELECT * FROM awards ORDER BY year DESC, display_order ASC");

$edit_award = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM awards WHERE id = $id");
    $edit_award = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between">
            <h3 class="text-lg font-semibold">Awards & Recognition</h3>
            <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add Award
            </a>
        </div>
        
        <div class="p-6">
            <?php while ($award = $awards->fetch_assoc()): ?>
                <div class="flex items-start justify-between border-b pb-4 mb-4 last:border-0">
                    <div>
                        <div class="flex items-center mb-2">
                            <span class="text-2xl font-bold text-gray-800 mr-4"><?php echo $award['year']; ?></span>
                            <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($award['title']); ?></h4>
                        </div>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($award['organization']); ?> • <?php echo htmlspecialchars($award['location']); ?></p>
                        <?php if ($award['description']): ?>
                            <p class="mt-2 text-gray-700"><?php echo htmlspecialchars($award['description']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <a href="?action=edit&id=<?php echo $award['id']; ?>" class="text-blue-600"><i class="fas fa-edit"></i></a>
                        <form method="POST" class="inline" onsubmit="return confirmDelete();">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $award['id']; ?>">
                            <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between">
            <h3 class="text-lg font-semibold"><?php echo $edit_award ? 'Edit Award' : 'Add Award'; ?></h3>
            <a href="awards.php" class="text-gray-600"><i class="fas fa-times"></i></a>
        </div>
        
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_award ? 'edit' : 'add'; ?>">
            <?php if ($edit_award): ?><input type="hidden" name="id" value="<?php echo $edit_award['id']; ?>"><?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Award Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo $edit_award ? htmlspecialchars($edit_award['title']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Year <span class="text-red-500">*</span></label>
                    <input type="number" name="year" value="<?php echo $edit_award ? $edit_award['year'] : date('Y'); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Organization</label>
                    <input type="text" name="organization" value="<?php echo $edit_award ? htmlspecialchars($edit_award['organization']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Location</label>
                    <input type="text" name="location" value="<?php echo $edit_award ? htmlspecialchars($edit_award['location']) : ''; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo $edit_award ? htmlspecialchars($edit_award['description']) : ''; ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Display Order</label>
                    <input type="number" name="display_order" value="<?php echo $edit_award ? $edit_award['display_order'] : '0'; ?>" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?php echo (!$edit_award || $edit_award['is_active']) ? 'checked' : ''; ?> class="w-4 h-4">
                        <span class="ml-2">Active</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="awards.php" class="px-6 py-2 border rounded-lg">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg"><i class="fas fa-save mr-2"></i>Save</button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

