<?php
/**
 * FlipAvenue CMS Configuration File
 * Database and Application Settings
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'flipavenue_cms');

// Application Configuration
define('SITE_URL', 'http://localhost/archin');
define('CMS_URL', SITE_URL . '/cms');
define('UPLOAD_PATH', dirname(__DIR__) . '/assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');

// Security
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Pagination
define('ITEMS_PER_PAGE', 10);

// Database Connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Helper Functions
function cleanInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . CMS_URL . '/login.php');
        exit();
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header('Location: ' . CMS_URL . '/login.php?timeout=1');
        exit();
    }
    $_SESSION['last_activity'] = time();
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function showAlert($message, $type = 'success') {
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-400 text-blue-700'
    ];
    
    $color = $colors[$type] ?? $colors['info'];
    
    return "<div class='border-l-4 p-4 mb-4 {$color}' role='alert'>
                <p>{$message}</p>
            </div>";
}

function uploadFile($file, $folder = 'general') {
    $target_dir = UPLOAD_PATH . $folder . '/';
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
    
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > 5000000) { // 5MB
        return ['success' => false, 'message' => 'File too large (max 5MB)'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return [
            'success' => true,
            'filename' => $new_filename,
            'path' => $folder . '/' . $new_filename,
            'url' => UPLOAD_URL . $folder . '/' . $new_filename
        ];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

function generateSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

function getSettings() {
    global $conn;
    $settings = [];
    $result = $conn->query("SELECT setting_key, setting_value FROM site_settings");
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0777, true);
}
?>

