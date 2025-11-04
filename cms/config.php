<?php
/**
 * FlipAvenue CMS Configuration File
 * Database and Application Settings
 */

// Security Configuration (define before using)
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('MAX_LOGIN_ATTEMPTS', 5); // Max failed login attempts
define('LOCKOUT_TIME', 900); // 15 minutes lockout
define('PASSWORD_MIN_LENGTH', 8); // Minimum password length
define('SESSION_REGENERATE_INTERVAL', 300); // Regenerate session ID every 5 minutes

// Application Configuration
define('SITE_URL', ''); // Replace with your actual domain
define('CMS_URL', SITE_URL . '/cms');
define('UPLOAD_PATH', dirname(__DIR__) . '/assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Configure session settings for better security and cross-tab compatibility
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');
    
    // Set session cookie lifetime to match session timeout
    ini_set('session.cookie_lifetime', 0); // Expire when browser closes
    ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);
    
    session_start();
}

// Include centralized database connection
require_once __DIR__ . '/db_connect.php';

// Include security functions
require_once __DIR__ . '/security.php';

// Set security headers
setSecurityHeaders();

// Pagination
define('ITEMS_PER_PAGE', 10);

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
        // Use relative path to work with any domain
        header('Location: login.php');
        exit();
    }
    
    // Check session timeout - with grace period for cross-tab activity
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        
        // Check if this is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            http_response_code(401);
            echo json_encode(['success' => false, 'timeout' => true, 'message' => 'Session expired']);
            exit();
        }
        
        header('Location: login.php?timeout=1');
        exit();
    }
    
    // Update last activity on each request (this works across all tabs sharing the session)
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

/**
 * Upload file with automatic WebP conversion for images
 * Converts JPG, PNG, and GIF images to WebP format for better compression
 * Falls back to original format if conversion fails
 * 
 * @param array $file File array from $_FILES
 * @param string $folder Destination folder within uploads directory
 * @return array Success status and file information
 */
function uploadFile($file, $folder = 'general') {
    $target_dir = UPLOAD_PATH . $folder . '/';
    
    if (!file_exists($target_dir)) {
        // Attempt to create the directory recursively
        if (!mkdir($target_dir, 0777, true) && !is_dir($target_dir)) {
            return ['success' => false, 'message' => 'Failed to create upload directory'];
        }
    }
    
    // Ensure directory is writable
    if (!is_writable($target_dir)) {
        @chmod($target_dir, 0777);
        if (!is_writable($target_dir)) {
            return ['success' => false, 'message' => 'Upload directory is not writable'];
        }
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
    
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if (!isset($file['size']) || $file['size'] > 5000000) { // 5MB
        return ['success' => false, 'message' => 'File too large (max 5MB)'];
    }
    
    // Check if we should convert to WebP (only for images, not PDFs)
    // Require both GD library and WebP support
    $has_gd = function_exists('imagecreatefromjpeg') || function_exists('imagecreatefrompng');
    $has_webp = function_exists('imagewebp');
    $convert_to_webp = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif']) && $has_gd && $has_webp;
    $final_extension = $convert_to_webp ? 'webp' : $file_extension;
    $new_filename = uniqid() . '_' . time() . '.' . $final_extension;
    $target_file = $target_dir . $new_filename;
    
    // First, save the uploaded file to a temporary location
    $temp_file = $target_dir . 'temp_' . uniqid() . '_' . time() . '.' . $file_extension;
    $moved = false;
    
    // Primary: use move_uploaded_file for real HTTP uploads
    if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
        $moved = move_uploaded_file($file['tmp_name'], $temp_file);
    }
    
    // Fallback: allow copy() for cases like our test page where tmp file is a copied file
    if (!$moved && isset($file['tmp_name']) && file_exists($file['tmp_name'])) {
        $moved = @copy($file['tmp_name'], $temp_file);
    }
    
    if (!$moved) {
        // Provide detailed error when possible
        $lastError = error_get_last();
        $detail = $lastError && isset($lastError['message']) ? (' - ' . $lastError['message']) : '';
        return ['success' => false, 'message' => 'Upload failed' . $detail];
    }
    
    // Convert to WebP if needed
    if ($convert_to_webp) {
        $converted = false;
        $image = null;
        
        // Load image based on type
        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
                if (function_exists('imagecreatefromjpeg')) {
                    $image = @imagecreatefromjpeg($temp_file);
                }
                break;
            case 'png':
                if (function_exists('imagecreatefrompng')) {
                    $image = @imagecreatefrompng($temp_file);
                    // Preserve transparency for PNG
                    if ($image) {
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                    }
                }
                break;
            case 'gif':
                if (function_exists('imagecreatefromgif')) {
                    $image = @imagecreatefromgif($temp_file);
                }
                break;
        }
        
        // Convert to WebP if image was loaded successfully
        if ($image && function_exists('imagewebp')) {
            // Use quality 85 for good balance between size and quality
            $quality = 85;
            $converted = @imagewebp($image, $target_file, $quality);
            
            if ($converted) {
                // Clean up
                imagedestroy($image);
                @unlink($temp_file);
            } else {
                // Conversion failed, fall back to original
                imagedestroy($image);
                $final_extension = $file_extension;
                $new_filename = uniqid() . '_' . time() . '.' . $final_extension;
                $target_file = $target_dir . $new_filename;
                $converted = @rename($temp_file, $target_file) || @copy($temp_file, $target_file);
                if ($converted) {
                    @unlink($temp_file);
                }
            }
        } else {
            // Image loading failed, fall back to original
            $final_extension = $file_extension;
            $new_filename = uniqid() . '_' . time() . '.' . $final_extension;
            $target_file = $target_dir . $new_filename;
            $converted = @rename($temp_file, $target_file) || @copy($temp_file, $target_file);
            if ($converted) {
                @unlink($temp_file);
            }
        }
        
        if (!$converted) {
            @unlink($temp_file);
            return ['success' => false, 'message' => 'Failed to process image'];
        }
    } else {
        // Not an image or already WebP/PDF - just rename temp file to final location
        $renamed = @rename($temp_file, $target_file);
        if (!$renamed) {
            $renamed = @copy($temp_file, $target_file);
            if ($renamed) {
                @unlink($temp_file);
            }
        }
        if (!$renamed) {
            @unlink($temp_file);
            return ['success' => false, 'message' => 'Failed to save file'];
        }
    }
    
    return [
        'success' => true,
        'filename' => $new_filename,
        'path' => $folder . '/' . $new_filename,
        'url' => UPLOAD_URL . $folder . '/' . $new_filename,
        'original_format' => $convert_to_webp ? $file_extension : null,
        'converted_to_webp' => $convert_to_webp
    ];
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

