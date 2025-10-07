<?php
require_once 'config.php';
$page_title = 'Contact Submissions';
$page_description = 'Manage contact form submissions';

$message = '';
$message_type = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = (int)$_POST['id'];
    
    if ($action === 'update_status') {
        $status = cleanInput($_POST['status']);
        $sql = "UPDATE contact_submissions SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        
        if ($stmt->execute()) {
            $message = 'Status updated successfully!';
            $message_type = 'success';
        }
    } elseif ($action === 'delete') {
        if ($conn->query("DELETE FROM contact_submissions WHERE id = $id")) {
            $message = 'Submission deleted successfully!';
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

$submissions = $conn->query("SELECT * FROM contact_submissions $where ORDER BY created_at DESC");

// Get counts
$counts = [
    'all' => $conn->query("SELECT COUNT(*) as count FROM contact_submissions")->fetch_assoc()['count'],
    'new' => $conn->query("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'new'")->fetch_assoc()['count'],
    'read' => $conn->query("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'read'")->fetch_assoc()['count'],
    'replied' => $conn->query("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'replied'")->fetch_assoc()['count'],
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
            <a href="?status=read" class="px-6 py-3 border-b-2 font-medium text-sm <?php echo $filter_status === 'read' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                Read (<?php echo $counts['read']; ?>)
            </a>
            <a href="?status=replied" class="px-6 py-3 border-b-2 font-medium text-sm <?php echo $filter_status === 'replied' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                Replied (<?php echo $counts['replied']; ?>)
            </a>
        </nav>
    </div>
</div>

<!-- Submissions List -->
<div class="bg-white rounded-lg shadow">
    <div class="divide-y divide-gray-200">
        <?php if ($submissions->num_rows > 0): ?>
            <?php while ($submission = $submissions->fetch_assoc()): ?>
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($submission['name']); ?></h4>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($submission['email']); ?>
                                        <?php if ($submission['phone']): ?>
                                            <span class="ml-3"><i class="fas fa-phone mr-1"></i><?php echo htmlspecialchars($submission['phone']); ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if ($submission['subject']): ?>
                                <p class="text-sm font-medium text-gray-700 mb-2">Subject: <?php echo htmlspecialchars($submission['subject']); ?></p>
                            <?php endif; ?>
                            
                            <p class="text-gray-700 mb-3"><?php echo nl2br(htmlspecialchars($submission['message'])); ?></p>
                            
                            <div class="flex items-center text-xs text-gray-500">
                                <span><i class="fas fa-clock mr-1"></i><?php echo date('M d, Y H:i', strtotime($submission['created_at'])); ?></span>
                                <span class="ml-4"><i class="fas fa-globe mr-1"></i><?php echo htmlspecialchars($submission['ip_address']); ?></span>
                            </div>
                        </div>
                        
                        <div class="ml-4 flex flex-col items-end space-y-2">
                            <form method="POST" class="flex items-center space-x-2">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <select name="status" onchange="this.form.submit()" class="text-xs px-2 py-1 border rounded">
                                    <option value="new" <?php echo $submission['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="read" <?php echo $submission['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="replied" <?php echo $submission['status'] === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                    <option value="archived" <?php echo $submission['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                </select>
                            </form>
                            
                            <form method="POST" onsubmit="return confirmDelete();">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-inbox text-5xl mb-4"></i>
                <p>No submissions found</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

