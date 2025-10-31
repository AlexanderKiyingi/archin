<?php
/**
 * Common Helper Functions
 * Shared functions for both CMS and public pages
 */

/**
 * Get the correct image URL from database path
 * Handles different path formats and converts them to correct URLs
 */
function getImageUrl($image_path) {
    if (empty($image_path)) {
        return '';
    }
    
    // If already a full URL, return as is
    if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
        return $image_path;
    }
    
    // Remove any '../' prefixes
    $image_path = str_replace('../', '', $image_path);
    
    // Remove leading slashes
    $image_path = ltrim($image_path, '/');
    
    // If path already includes 'assets/uploads/', use as is
    if (strpos($image_path, 'assets/uploads/') === 0) {
        return $image_path;
    }
    
    // If path already includes 'cms/assets/uploads/', fix it
    if (strpos($image_path, 'cms/assets/uploads/') === 0) {
        return str_replace('cms/assets/uploads/', 'assets/uploads/', $image_path);
    }
    
    // If path already includes 'cms/', remove it and add correct path
    if (strpos($image_path, 'cms/') === 0) {
        $image_path = str_replace('cms/', '', $image_path);
    }
    
    // For paths like 'projects/file.jpg' or 'shop/file.jpg', add 'assets/uploads/' prefix
    // This handles paths stored in database from uploadFile() function
    return 'assets/uploads/' . $image_path;
}

/**
 * Get image URL with fallback
 */
function getImageUrlWithFallback($image_path, $fallback = '') {
    $url = getImageUrl($image_path);
    return !empty($url) ? $url : $fallback;
}

