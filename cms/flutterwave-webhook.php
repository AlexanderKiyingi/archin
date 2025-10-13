<?php
/**
 * Flutterwave Webhook Handler
 * 
 * This file handles payment notifications from Flutterwave
 * It should be accessible via HTTPS at the webhook URL you configured in Flutterwave dashboard
 */

require_once 'config.php';
require_once 'flutterwave-config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get the raw POST data
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_VERIF_HASH'] ?? '';

// Validate webhook signature
if (!validateFlutterwaveWebhook($payload, $signature)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

// Parse the payload
$data = json_decode($payload, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

// Log the webhook for debugging (remove in production)
error_log('Flutterwave Webhook: ' . $payload);

try {
    // Extract transaction details
    $txRef = $data['data']['tx_ref'] ?? '';
    $transactionId = $data['data']['id'] ?? '';
    $status = $data['data']['status'] ?? '';
    $amount = $data['data']['amount'] ?? 0;
    $currency = $data['data']['currency'] ?? 'UGX';
    
    // Find the order by transaction reference
    $order_query = "SELECT * FROM shop_orders WHERE order_number = ?";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param('s', $txRef);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Order not found: ' . $txRef);
    }
    
    $order = $result->fetch_assoc();
    
    // Update payment status based on Flutterwave response
    $payment_status = 'pending';
    $order_status = 'pending';
    
    switch ($status) {
        case 'successful':
            $payment_status = 'paid';
            $order_status = 'processing';
            break;
        case 'failed':
            $payment_status = 'failed';
            $order_status = 'cancelled';
            break;
        case 'cancelled':
            $payment_status = 'failed';
            $order_status = 'cancelled';
            break;
    }
    
    // Update order in database
    $update_query = "UPDATE shop_orders SET 
                     payment_status = ?, 
                     order_status = ?, 
                     transaction_id = ?,
                     updated_at = CURRENT_TIMESTAMP
                     WHERE order_number = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssss', $payment_status, $order_status, $transactionId, $txRef);
    
    if ($stmt->execute()) {
        // Log successful update
        error_log("Order updated successfully: {$txRef} - Status: {$status}");
        
        // Send confirmation email if payment successful (optional)
        if ($status === 'successful') {
            // You can add email notification here
            sendOrderConfirmationEmail($order);
        }
        
        http_response_code(200);
        echo json_encode([
            'status' => 'success', 
            'message' => 'Webhook processed successfully',
            'order_number' => $txRef,
            'transaction_id' => $transactionId
        ]);
    } else {
        throw new Exception('Failed to update order: ' . $stmt->error);
    }
    
} catch (Exception $e) {
    error_log('Flutterwave Webhook Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}

/**
 * Send order confirmation email
 */
function sendOrderConfirmationEmail($order) {
    // This is a placeholder function
    // You can implement email sending logic here
    // For example, using PHPMailer or your preferred email service
    
    $to = $order['customer_email'];
    $subject = 'Order Confirmation - ' . $order['order_number'];
    $message = "Dear {$order['customer_name']},\n\n";
    $message .= "Thank you for your order! Your payment has been confirmed.\n\n";
    $message .= "Order Number: {$order['order_number']}\n";
    $message .= "Total Amount: $" . number_format($order['total_amount'], 2) . "\n\n";
    $message .= "We will process your order and send you a shipping confirmation soon.\n\n";
    $message .= "Best regards,\nFlipAvenue Team";
    
    // For now, just log the email content
    error_log("Order confirmation email for {$order['order_number']}: " . $message);
    
    // Uncomment the line below when you're ready to send actual emails
    // mail($to, $subject, $message);
}
?>
