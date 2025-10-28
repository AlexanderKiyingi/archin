<?php
require_once 'config.php';
requireLogin();

// Only super_admin for testing
if ($_SESSION['admin_role'] !== 'super_admin') {
    die('Access denied');
}

$page_title = 'Test File Upload';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">File Upload Test</h1>
            
            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">System Information</h2>
                <div class="space-y-2">
                    <p><strong>UPLOAD_PATH:</strong> <?php echo UPLOAD_PATH; ?></p>
                    <p><strong>UPLOAD_URL:</strong> <?php echo UPLOAD_URL; ?></p>
                    <p><strong>Path Exists:</strong> <?php echo file_exists(UPLOAD_PATH) ? '<span class="text-green-600">✓ Yes</span>' : '<span class="text-red-600">✗ No</span>'; ?></p>
                    <p><strong>Path Writable:</strong> <?php echo is_writable(UPLOAD_PATH) ? '<span class="text-green-600">✓ Yes</span>' : '<span class="text-red-600">✗ No</span>'; ?></p>
                    <p><strong>PHP Upload Max:</strong> <?php echo ini_get('upload_max_filesize'); ?></p>
                    <p><strong>PHP Post Max:</strong> <?php echo ini_get('post_max_size'); ?></p>
                </div>
            </div>
            
            <?php
            // Handle file upload test
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
                echo '<div class="bg-white rounded-lg shadow-lg p-6 mb-6">';
                echo '<h2 class="text-xl font-bold mb-4">Upload Test Results</h2>';
                
                if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
                    echo '<div class="bg-green-100 border-l-4 border-green-500 p-4 mb-4">';
                    echo '<p class="font-semibold text-green-700">✓ File received successfully!</p>';
                    echo '</div>';
                    
                    echo '<div class="space-y-2 mb-4">';
                    echo '<p><strong>Original Name:</strong> ' . htmlspecialchars($_FILES['test_file']['name']) . '</p>';
                    echo '<p><strong>File Size:</strong> ' . number_format($_FILES['test_file']['size'] / 1024, 2) . ' KB</p>';
                    echo '<p><strong>MIME Type:</strong> ' . htmlspecialchars($_FILES['test_file']['type']) . '</p>';
                    echo '<p><strong>Temp Location:</strong> ' . htmlspecialchars($_FILES['test_file']['tmp_name']) . '</p>';
                    echo '</div>';
                    
                    // Test the uploadFile function
                    $test_folders = ['shop', 'blog', 'testimonials', 'team', 'services', 'projects'];
                    echo '<div class="border-t pt-4">';
                    echo '<h3 class="text-lg font-semibold mb-3">Testing uploadFile() function for each folder:</h3>';
                    
                    foreach ($test_folders as $folder) {
                        echo '<div class="mb-4 p-3 bg-gray-50 rounded">';
                        echo '<p class="font-semibold">Testing folder: <code>' . $folder . '</code></p>';
                        
                        // Create a fresh copy of the file for each test
                        $temp_copy = sys_get_temp_dir() . '/' . uniqid() . '_' . $_FILES['test_file']['name'];
                        copy($_FILES['test_file']['tmp_name'], $temp_copy);
                        $_FILES['test_file']['tmp_name'] = $temp_copy;
                        
                        $upload = uploadFile($_FILES['test_file'], $folder);
                        
                        if ($upload['success']) {
                            echo '<div class="bg-green-50 border border-green-200 p-3 rounded mt-2">';
                            echo '<p class="text-green-700">✓ Success!</p>';
                            echo '<p class="text-sm">Filename: ' . htmlspecialchars($upload['filename']) . '</p>';
                            echo '<p class="text-sm">Path: ' . htmlspecialchars($upload['path']) . '</p>';
                            echo '<p class="text-sm">Full URL: ' . htmlspecialchars($upload['url']) . '</p>';
                            
                            // Check if file actually exists
                            $full_path = UPLOAD_PATH . $upload['path'];
                            if (file_exists($full_path)) {
                                echo '<p class="text-sm text-green-600">✓ File exists at: ' . htmlspecialchars($full_path) . '</p>';
                                echo '<p class="text-sm"><a href="' . htmlspecialchars($upload['url']) . '" target="_blank" class="text-blue-600 hover:underline">View Image →</a></p>';
                            } else {
                                echo '<p class="text-sm text-red-600">✗ File NOT found at: ' . htmlspecialchars($full_path) . '</p>';
                            }
                            echo '</div>';
                        } else {
                            echo '<div class="bg-red-50 border border-red-200 p-3 rounded mt-2">';
                            echo '<p class="text-red-700">✗ Failed</p>';
                            echo '<p class="text-sm">Error: ' . htmlspecialchars($upload['message']) . '</p>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    
                    echo '</div>';
                } else {
                    $error_messages = [
                        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                        UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                    ];
                    
                    echo '<div class="bg-red-100 border-l-4 border-red-500 p-4">';
                    echo '<p class="font-semibold text-red-700">✗ Upload Error</p>';
                    echo '<p>Error Code: ' . $_FILES['test_file']['error'] . '</p>';
                    echo '<p>Message: ' . ($error_messages[$_FILES['test_file']['error']] ?? 'Unknown error') . '</p>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
            
            <!-- Test Upload Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Test File Upload</h2>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label for="test_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Select an image file to test upload:
                        </label>
                        <input 
                            type="file" 
                            id="test_file" 
                            name="test_file" 
                            accept="image/*" 
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                file:cursor-pointer
                                border border-gray-300 rounded-lg"
                            required
                        >
                        <p class="mt-2 text-sm text-gray-500">
                            Select any image file (JPG, PNG, GIF, WEBP) to test the upload functionality.
                        </p>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition"
                    >
                        <i class="fas fa-upload mr-2"></i>Test Upload
                    </button>
                </form>
            </div>
            
            <!-- Quick Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mt-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Quick Instructions:</h3>
                <ol class="list-decimal list-inside space-y-1 text-blue-800">
                    <li>Select an image file from your local computer</li>
                    <li>Click "Test Upload" button</li>
                    <li>Review the results for each upload folder (shop, blog, testimonials, team, services, projects)</li>
                    <li>Click on "View Image →" links to verify the images were uploaded correctly</li>
                </ol>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>

