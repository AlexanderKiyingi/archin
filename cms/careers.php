<?php
require_once 'config.php';
$page_title = 'Career Applications';
$page_description = 'Manage job applications and career submissions';

$message = '';
$message_type = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = (int)$_POST['id'];
    
    if ($action === 'update_status') {
        $status = cleanInput($_POST['status']);
        $notes = cleanInput($_POST['notes'] ?? '');
        $sql = "UPDATE career_applications SET status = ?, notes = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $notes, $id);
        
        if ($stmt->execute()) {
            $message = 'Application status updated successfully!';
            $message_type = 'success';
        }
    } elseif ($action === 'delete') {
        if ($conn->query("DELETE FROM career_applications WHERE id = $id")) {
            $message = 'Application deleted successfully!';
            $message_type = 'success';
        }
    }
}

// Get filter
$filter_status = isset($_GET['status']) ? cleanInput($_GET['status']) : 'all';

// Build query
$where = '';
if ($filter_status !== 'all') {
    $where = " WHERE status = '$filter_status'";
}

$applications = $conn->query("SELECT * FROM career_applications $where ORDER BY created_at DESC");

// Get counts
$counts = [
    'all' => $conn->query("SELECT COUNT(*) as count FROM career_applications")->fetch_assoc()['count'],
    'new' => $conn->query("SELECT COUNT(*) as count FROM career_applications WHERE status = 'new'")->fetch_assoc()['count'],
    'reviewed' => $conn->query("SELECT COUNT(*) as count FROM career_applications WHERE status = 'reviewed'")->fetch_assoc()['count'],
    'interviewed' => $conn->query("SELECT COUNT(*) as count FROM career_applications WHERE status = 'interviewed'")->fetch_assoc()['count'],
];

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <a href="?status=all" class="px-6 py-3 border-b-2 font-medium text-sm <?php echo $filter_status === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                All (<?php echo $counts['all']; ?>)
            </a>
            <a href="?status=new" class="px-6 py-3 border-b-2 font-medium text-sm <?php echo $filter_status === 'new' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                New (<?php echo $counts['new']; ?>)
            </a>
            <a href="?status=reviewed" class="px-6 py-3 border-b-2 font-medium text-sm <?php echo $filter_status === 'reviewed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                Reviewed (<?php echo $counts['reviewed']; ?>)
            </a>
            <a href="?status=interviewed" class="px-6 py-3 border-b-2 font-medium text-sm <?php echo $filter_status === 'interviewed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                Interviewed (<?php echo $counts['interviewed']; ?>)
            </a>
        </nav>
    </div>
</div>

<!-- Applications List -->
<div class="bg-white rounded-lg shadow">
    <div class="divide-y divide-gray-200">
        <?php if ($applications->num_rows > 0): ?>
            <?php while ($application = $applications->fetch_assoc()): ?>
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="la la-user text-orange-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg"><?php echo htmlspecialchars($application['full_name']); ?></h4>
                                    <p class="text-sm text-gray-600">
                                        <i class="la la-envelope mr-1"></i><?php echo htmlspecialchars($application['email']); ?>
                                        <span class="ml-3"><i class="la la-phone mr-1"></i><?php echo htmlspecialchars($application['phone']); ?></span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                    <i class="la la-briefcase mr-1"></i><?php echo htmlspecialchars($application['position']); ?>
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="font-semibold text-gray-800 mb-2">Cover Letter:</h6>
                                <p class="text-gray-700 text-sm"><?php echo nl2br(htmlspecialchars(substr($application['cover_letter'], 0, 300))); ?><?php echo strlen($application['cover_letter']) > 300 ? '...' : ''; ?></p>
                            </div>
                            
                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                <span><i class="la la-clock mr-1"></i><?php echo date('M d, Y H:i', strtotime($application['created_at'])); ?></span>
                                <span class="ml-4"><i class="la la-globe mr-1"></i><?php echo htmlspecialchars($application['ip_address']); ?></span>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <?php if ($application['resume_path']): ?>
                                    <a href="<?php echo UPLOAD_URL . $application['resume_path']; ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="la la-file-pdf mr-1"></i>View Resume
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($application['portfolio_path']): ?>
                                    <a href="<?php echo UPLOAD_URL . $application['portfolio_path']; ?>" target="_blank" class="text-green-600 hover:text-green-800 text-sm">
                                        <i class="la la-folder mr-1"></i>View Portfolio
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="ml-6 flex flex-col items-end space-y-3">
                            <form method="POST" class="flex items-center space-x-2">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                                <select name="status" class="text-xs px-2 py-1 border rounded">
                                    <option value="new" <?php echo $application['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="reviewed" <?php echo $application['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                    <option value="interviewed" <?php echo $application['status'] === 'interviewed' ? 'selected' : ''; ?>>Interviewed</option>
                                    <option value="hired" <?php echo $application['status'] === 'hired' ? 'selected' : ''; ?>>Hired</option>
                                    <option value="rejected" <?php echo $application['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <button type="submit" class="text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Update
                                </button>
                            </form>
                            
                            <form method="POST" onsubmit="return confirmDelete();">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="la la-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="p-12 text-center text-gray-500">
                <i class="la la-briefcase text-5xl mb-4"></i>
                <p>No applications found</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

