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

$transaction_id = isset($_POST['transaction_id']) ? (int)$_POST['transaction_id'] : 0;
$status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';

// Validate status
$allowed_statuses = ['pending', 'paid', 'failed', 'refunded'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

// Update transaction status
$query = "UPDATE shop_orders SET payment_status = '$status', updated_at = NOW() WHERE id = $transaction_id";

if ($conn->query($query)) {
    if ($conn->affected_rows > 0) {
        // Log the status change
        error_log("Transaction status updated: Order ID $transaction_id -> $status by " . $_SESSION['admin_username']);
        
        echo json_encode(['success' => true, 'message' => 'Transaction status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
    }
} else {
    error_log("Error updating transaction status: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$conn->close();
?>
