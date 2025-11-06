<?php
require_once 'config.php';
require_once __DIR__ . '/../common/functions.php';
$page_title = 'Video Showcases Management';
$page_description = 'Manage project showcase videos';

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $title = cleanInput($_POST['title']);
            $video_id = cleanInput($_POST['video_id']);
            $platform = cleanInput($_POST['platform']);
            $display_order = (int)$_POST['display_order'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Handle thumbnail upload
            $thumbnail = '';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
                $upload = uploadFile($_FILES['thumbnail'], 'showcases');
                if ($upload['success']) {
                    $thumbnail = $upload['path'];
                }
            } elseif ($action === 'edit' && !empty($_POST['current_thumbnail'])) {
                // Keep existing thumbnail if no new upload
                $thumbnail = $_POST['current_thumbnail'];
            }
            
            if ($action === 'add') {
                $sql = "INSERT INTO video_showcases (title, video_id, platform, thumbnail, display_order, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssii", $title, $video_id, $platform, $thumbnail, $display_order, $is_active);
                
                if ($stmt->execute()) {
                    $message = 'Video showcase added successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding video showcase: ' . $conn->error;
                    $message_type = 'error';
                }
            } else {
                $id = (int)$_POST['id'];
                
                // Build UPDATE query
                $updates = [];
                $params = [];
                $types = '';
                
                $updates[] = "title = ?";
                $params[] = $title;
                $types .= 's';
                
                $updates[] = "video_id = ?";
                $params[] = $video_id;
                $types .= 's';
                
                $updates[] = "platform = ?";
                $params[] = $platform;
                $types .= 's';
                
                if ($thumbnail !== '') {
                    $updates[] = "thumbnail = ?";
                    $params[] = $thumbnail;
                    $types .= 's';
                }
                
                $updates[] = "display_order = ?";
                $params[] = $display_order;
                $types .= 'i';
                
                $updates[] = "is_active = ?";
                $params[] = $is_active;
                $types .= 'i';
                
                $params[] = $id;
                $types .= 'i';
                
                $sql = "UPDATE video_showcases SET " . implode(', ', $updates) . " WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                
                if ($stmt->execute()) {
                    $message = 'Video showcase updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating video showcase: ' . $conn->error;
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            
            // Get thumbnail path to delete file
            $result = $conn->query("SELECT thumbnail FROM video_showcases WHERE id = $id");
            if ($result && $row = $result->fetch_assoc() && !empty($row['thumbnail'])) {
                $thumbnail_path = UPLOAD_PATH . $row['thumbnail'];
                if (file_exists($thumbnail_path)) {
                    @unlink($thumbnail_path);
                }
            }
            
            $sql = "DELETE FROM video_showcases WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $message = 'Video showcase deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting video showcase: ' . $conn->error;
                $message_type = 'error';
            }
        }
    }
}

// Get all video showcases
$videos = $conn->query("SELECT * FROM video_showcases ORDER BY display_order ASC, id DESC");

// Get single video for editing
$edit_video = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM video_showcases WHERE id = $id");
    $edit_video = $result->fetch_assoc();
}

$show_form = isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit');

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<?php if (!$show_form): ?>
    <!-- Video Showcases List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">All Video Showcases</h3>
            <div class="flex gap-3">
                <a href="<?php echo SITE_URL; ?>/index.php#project-showcases" target="_blank" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-external-link-alt mr-2"></i>View on Site
                </a>
                <a href="?action=add" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add New Video
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Video</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($videos && $videos->num_rows > 0): ?>
                        <?php while ($video = $videos->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php 
                                        // Get thumbnail - use custom if available, otherwise use YouTube/Vimeo thumbnail
                                        $list_thumbnail = getVideoThumbnailUrl(
                                            $video['thumbnail'] ?? '',
                                            $video['video_id'] ?? '',
                                            $video['platform'] ?? 'youtube'
                                        );
                                        ?>
                                        <?php if ($list_thumbnail): ?>
                                            <img src="<?php echo htmlspecialchars($list_thumbnail); ?>" alt="" class="w-16 h-12 rounded object-cover mr-3" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'48\'%3E%3Crect fill=\'%23ddd\' width=\'64\' height=\'48\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-size=\'12\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                                        <?php else: ?>
                                            <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center mr-3">
                                                <i class="fas fa-video text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($video['title']); ?></div>
                                            <div class="text-xs text-gray-500 mt-1">ID: <?php echo htmlspecialchars($video['video_id']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $video['platform'] === 'youtube' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                        <i class="fab fa-<?php echo $video['platform']; ?> mr-1"></i>
                                        <?php echo ucfirst($video['platform']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?php echo $video['display_order']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $video['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $video['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="?action=edit&id=<?php echo $video['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this video showcase?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-video text-4xl mb-3 block"></i>
                                <p>No video showcases found. <a href="?action=add" class="text-blue-600 hover:underline">Add your first video showcase</a></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <!-- Add/Edit Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <?php echo $edit_video ? 'Edit Video Showcase' : 'Add New Video Showcase'; ?>
            </h3>
            <a href="video-showcases.php" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?php echo $edit_video ? 'edit' : 'add'; ?>">
            <?php if ($edit_video): ?>
                <input type="hidden" name="id" value="<?php echo $edit_video['id']; ?>">
                <input type="hidden" name="current_thumbnail" value="<?php echo htmlspecialchars($edit_video['thumbnail'] ?? ''); ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Video Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                           value="<?php echo htmlspecialchars($edit_video['title'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Ssi Resort Katosi - Virtual Walkthrough">
                </div>
                
                <!-- Video ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Video ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="video_id" required
                           value="<?php echo htmlspecialchars($edit_video['video_id'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., dQw4w9WgXcQ">
                    <p class="mt-1 text-xs text-gray-500">
                        For YouTube: Extract ID from URL (youtube.com/watch?v=<strong>VIDEO_ID</strong>)<br>
                        For Vimeo: Extract ID from URL (vimeo.com/<strong>VIDEO_ID</strong>)
                    </p>
                </div>
                
                <!-- Platform -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Platform <span class="text-red-500">*</span>
                    </label>
                    <select name="platform" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="youtube" <?php echo (isset($edit_video['platform']) && $edit_video['platform'] === 'youtube') ? 'selected' : ''; ?>>YouTube</option>
                        <option value="vimeo" <?php echo (isset($edit_video['platform']) && $edit_video['platform'] === 'vimeo') ? 'selected' : ''; ?>>Vimeo</option>
                    </select>
                </div>
                
                <!-- Thumbnail -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Thumbnail Image <span class="text-gray-500 font-normal">(Optional - YouTube/Vimeo thumbnail will be used if not provided)</span>
                    </label>
                    <?php if ($edit_video && !empty($edit_video['thumbnail'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo UPLOAD_URL . $edit_video['thumbnail']; ?>" alt="Current thumbnail" class="w-32 h-20 object-cover rounded border border-gray-300">
                            <p class="text-xs text-gray-500 mt-1">Current custom thumbnail</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- YouTube/Vimeo Thumbnail Preview -->
                    <?php if (!empty($_POST['video_id'] ?? $edit_video['video_id'] ?? '')): 
                        $preview_video_id = $_POST['video_id'] ?? $edit_video['video_id'] ?? '';
                        $preview_platform = $_POST['platform'] ?? $edit_video['platform'] ?? 'youtube';
                        require_once __DIR__ . '/../common/functions.php';
                        $preview_thumbnail = getVideoThumbnail($preview_video_id, $preview_platform);
                    ?>
                        <div class="mb-3" id="platformThumbnailPreview">
                            <img src="<?php echo htmlspecialchars($preview_thumbnail); ?>" alt="Platform thumbnail preview" class="w-32 h-20 object-cover rounded border border-gray-300" onerror="this.style.display='none'">
                            <p class="text-xs text-gray-500 mt-1"><?php echo ucfirst($preview_platform); ?> thumbnail (will be used automatically if no custom thumbnail)</p>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Recommended: 16:9 aspect ratio (e.g., 1280x720px). Leave empty to use <?php echo isset($edit_video['platform']) && $edit_video['platform'] === 'vimeo' ? 'Vimeo' : 'YouTube'; ?> thumbnail.</p>
                </div>
                
                <!-- Display Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" name="display_order"
                           value="<?php echo $edit_video['display_order'] ?? 0; ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           min="0">
                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                </div>
                
                <!-- Active Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                               <?php echo (!isset($edit_video) || $edit_video['is_active']) ? 'checked' : ''; ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active (visible on website)</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <a href="video-showcases.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    <?php echo $edit_video ? 'Update Video' : 'Add Video'; ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<script>
// Live preview of YouTube/Vimeo thumbnail
document.addEventListener('DOMContentLoaded', function() {
    const videoIdInput = document.querySelector('input[name="video_id"]');
    const platformSelect = document.querySelector('select[name="platform"]');
    const previewContainer = document.getElementById('platformThumbnailPreview');
    
    function updateThumbnailPreview() {
        const videoId = videoIdInput.value.trim();
        const platform = platformSelect.value;
        
        if (videoId && previewContainer) {
            let thumbnailUrl = '';
            if (platform === 'youtube') {
                thumbnailUrl = `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`;
            } else if (platform === 'vimeo') {
                thumbnailUrl = `https://vumbnail.com/${videoId}.jpg`;
            }
            
            if (thumbnailUrl) {
                // Create or update preview
                let previewDiv = document.getElementById('dynamicThumbnailPreview');
                if (!previewDiv) {
                    previewDiv = document.createElement('div');
                    previewDiv.id = 'dynamicThumbnailPreview';
                    previewDiv.className = 'mb-3';
                    previewDiv.innerHTML = `
                        <img src="" alt="Platform thumbnail preview" class="w-32 h-20 object-cover rounded border border-gray-300" onerror="this.style.display='none'">
                        <p class="text-xs text-gray-500 mt-1">${platform.charAt(0).toUpperCase() + platform.slice(1)} thumbnail (will be used automatically if no custom thumbnail)</p>
                    `;
                    // Insert before the file input
                    const fileInput = document.querySelector('input[name="thumbnail"]');
                    fileInput.parentNode.insertBefore(previewDiv, fileInput);
                }
                
                const img = previewDiv.querySelector('img');
                img.src = thumbnailUrl;
                img.style.display = 'block';
                img.onerror = function() {
                    // Try fallback for YouTube
                    if (platform === 'youtube') {
                        this.src = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
                    }
                };
            }
        } else if (previewContainer) {
            // Hide preview if no video ID
            const dynamicPreview = document.getElementById('dynamicThumbnailPreview');
            if (dynamicPreview) {
                dynamicPreview.style.display = 'none';
            }
        }
    }
    
    if (videoIdInput && platformSelect) {
        videoIdInput.addEventListener('input', updateThumbnailPreview);
        platformSelect.addEventListener('change', updateThumbnailPreview);
        
        // Initial preview if video ID exists
        if (videoIdInput.value.trim()) {
            updateThumbnailPreview();
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>

