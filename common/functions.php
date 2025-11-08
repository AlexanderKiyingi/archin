/**
 * Normalize a provided video identifier or URL into a platform-specific ID.
 *
 * @param string $video_id Raw video ID or URL provided by the user.
 * @param string $platform  Video platform, defaults to YouTube.
 * @return string Normalized video ID or empty string if it cannot be determined.
 */
function normalizeVideoId($video_id, $platform = 'youtube') {
    $video_id = trim((string) $video_id);
    $platform = strtolower($platform ?: 'youtube');

    if ($video_id === '') {
        return '';
    }

    if ($platform === 'youtube') {
        // Already clean ID?
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $video_id)) {
            return $video_id;
        }

        // Parse URL parts if a full URL was supplied.
        $parsed = @parse_url($video_id);
        if ($parsed !== false && is_array($parsed)) {
            // Check query string for v parameter.
            if (!empty($parsed['query'])) {
                parse_str($parsed['query'], $query_vars);
                if (!empty($query_vars['v']) && preg_match('/^[A-Za-z0-9_-]{11}$/', $query_vars['v'])) {
                    return $query_vars['v'];
                }
            }

            // Check path segments for embeds/shorts/etc.
            if (!empty($parsed['path'])) {
                $segments = explode('/', trim($parsed['path'], '/'));
                foreach ($segments as $segment) {
                    if (preg_match('/^[A-Za-z0-9_-]{11}$/', $segment)) {
                        return $segment;
                    }
                }
            }
        }

        // Generic extraction from common patterns.
        if (preg_match('#(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/|v/))([A-Za-z0-9_-]{11})#', $video_id, $matches)) {
            return $matches[1];
        }

        // Remove query/hash fragments and re-test.
        $clean = preg_replace('/[?#&].*/', '', $video_id);
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $clean)) {
            return $clean;
        }

        return '';
    }

    if ($platform === 'vimeo') {
        // If already numeric ID
        if (preg_match('/^[0-9]+$/', $video_id)) {
            return $video_id;
        }

        if (preg_match('#vimeo\.com/(?:video/)?([0-9]+)#', $video_id, $matches)) {
            return $matches[1];
        }

        // Extract digits from the string
        $digits = preg_replace('/\D+/', '', $video_id);
        return $digits !== '' ? $digits : '';
    }

    return $video_id;
}

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

/**
 * Get video thumbnail URL from YouTube or Vimeo
 * @param string $video_id The video ID
 * @param string $platform 'youtube' or 'vimeo'
 * @return string Thumbnail URL
 */
function getVideoThumbnail($video_id, $platform = 'youtube', $use_hqdefault = false) {
    $video_id = normalizeVideoId($video_id, $platform);

    if (empty($video_id)) {
        return '';
    }
    
    if ($platform === 'youtube') {
        // YouTube thumbnail URLs
        // maxresdefault is 1280x720 (best quality, but may not exist for all videos)
        // hqdefault is 480x360 (more reliable fallback)
        if ($use_hqdefault) {
            return "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
        }
        return "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
    } elseif ($platform === 'vimeo') {
        // Vimeo thumbnail via vumbnail service (free, no API needed)
        return "https://vumbnail.com/{$video_id}.jpg";
    }
    
    return '';
}

/**
 * Get video thumbnail URL with fallback
 * First tries custom uploaded thumbnail, then platform thumbnail
 */
function getVideoThumbnailUrl($custom_thumbnail, $video_id, $platform = 'youtube') {
    $video_id = normalizeVideoId($video_id, $platform);

    // If custom thumbnail exists, use it
    if (!empty($custom_thumbnail)) {
        return getImageUrl($custom_thumbnail);
    }
    
    // Otherwise use platform thumbnail
    if (!empty($video_id)) {
        return getVideoThumbnail($video_id, $platform);
    }
    
    // Return empty string if no thumbnail available
    return '';
}

