<?php
/**
 * Session Keep-Alive Endpoint
 * Simple endpoint to refresh session activity timestamp
 */

require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Update last activity (this keeps session alive)
$_SESSION['last_activity'] = time();

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Session refreshed',
    'timestamp' => time()
]);

