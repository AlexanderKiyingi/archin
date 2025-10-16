<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'admin')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';

// Validate status
$allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

// Update order status
$query = "UPDATE shop_orders SET order_status = '$status', updated_at = NOW() WHERE id = $order_id";

if ($conn->query($query)) {
    if ($conn->affected_rows > 0) {
        // Log the status change
        error_log("Order status updated: Order ID $order_id -> $status by " . $_SESSION['admin_username']);
        
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
    }
} else {
    error_log("Error updating order status: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$conn->close();
?>
