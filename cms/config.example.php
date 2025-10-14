<?php
/**
 * FlipAvenue CMS Configuration File - EXAMPLE
 * Copy this file to config.php and update with your actual credentials
 * 
 * IMPORTANT: Never commit config.php to version control!
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// DATABASE CONFIGURATION
// ==========================================
define('DB_HOST', 'localhost');           // Database host
define('DB_USER', 'your_db_username');    // Database username
define('DB_PASS', 'your_db_password');    // Database password
define('DB_NAME', 'u680675202_flipavenue_cms');  // Database name

// ==========================================
// APPLICATION CONFIGURATION
// ==========================================
define('SITE_URL', 'https://yourdomain.com');  // Your site URL (no trailing slash)
define('CMS_URL', SITE_URL . '/cms');           // CMS URL
define('UPLOAD_PATH', dirname(__DIR__) . '/assets/uploads/');  // Upload directory path
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');           // Upload directory URL

// ==========================================
// SECURITY SETTINGS
// ==========================================
define('SESSION_TIMEOUT', 3600); // Session timeout in seconds (1 hour)

// ==========================================
// PAGINATION
// ==========================================
define('ITEMS_PER_PAGE', 10); // Number of items per page in admin lists

// ==========================================
// DATABASE CONNECTION
// ==========================================
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// ==========================================
// HELPER FUNCTIONS
// ==========================================

/**
 * Clean and sanitize user input
 */
function cleanInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Redirect to login if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Check session timeout
 */
function checkSessionTimeout() {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Generate slug from string
 */
function generateSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    return $slug;
}

/**
 * Upload file with validation
 */
function uploadFile($file, $destination, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf']) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    $newFileName = uniqid() . '_' . $fileName;
    $targetPath = $destination . $newFileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $newFileName];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file'];
}

/**
 * Get site setting by key
 */
function getSetting($key) {
    global $conn;
    $key = $conn->real_escape_string($key);
    $result = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key = '$key'");
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['setting_value'];
    }
    
    return null;
}

/**
 * Format currency (UGX)
 */
function formatCurrency($amount) {
    return 'UGX ' . number_format($amount, 0, '.', ',');
}

/**
 * Get admin user info
 */
function getAdminInfo($adminId) {
    global $conn;
    $adminId = (int)$adminId;
    $result = $conn->query("SELECT * FROM admin_users WHERE id = $adminId");
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}
?>

