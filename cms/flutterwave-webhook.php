<?php
/**
 * Flutterwave Webhook Handler
 * 
 * This file handles payment notifications from Flutterwave
 * It verifies payment status and updates order information
 */

// Include required files
require_once 'db_connect.php';
require_once 'flutterwave-config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Log webhook data for debugging
$webhook_data = file_get_contents('php://input');
$webhook_signature = $_SERVER['HTTP_VERIF_HASH'] ?? '';

// Log the webhook (remove in production or use proper logging)
error_log('Flutterwave Webhook Received: ' . $webhook_data);
error_log('Webhook Signature: ' . $webhook_signature);

try {
    // Verify webhook signature
    if (!validateFlutterwaveWebhook($webhook_data, $webhook_signature)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
        exit;
    }
    
    // Parse webhook data
    $data = json_decode($webhook_data, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
        exit;
    }
    
    // Extract payment information
    $tx_ref = $data['data']['tx_ref'] ?? '';
    $transaction_id = $data['data']['id'] ?? '';
    $status = $data['data']['status'] ?? '';
    $amount = $data['data']['amount'] ?? 0;
    $currency = $data['data']['currency'] ?? 'UGX';
    $customer_email = $data['data']['customer']['email'] ?? '';
    $payment_method = $data['data']['payment_type'] ?? '';
    
    // Log payment details
    error_log("Payment Details - TX_REF: $tx_ref, Status: $status, Amount: $amount, Customer: $customer_email");
    
    // Find order by transaction reference
    $order_query = "SELECT * FROM shop_orders WHERE transaction_id = '$tx_ref' OR order_number LIKE '%" . substr($tx_ref, -6) . "%'";
    $order_result = $conn->query($order_query);
    
    if ($order_result && $order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        $order_id = $order['id'];
        
        // Update payment status based on Flutterwave response
        $payment_status = 'pending';
        $order_status = 'pending';
        
        switch ($status) {
            case 'successful':
                $payment_status = 'paid';
                $order_status = 'confirmed';
                break;
            case 'failed':
                $payment_status = 'failed';
                $order_status = 'cancelled';
                break;
            case 'cancelled':
                $payment_status = 'cancelled';
                $order_status = 'cancelled';
                break;
            default:
                $payment_status = 'pending';
                $order_status = 'pending';
                break;
        }
        
        // Update order in database
        $update_query = "UPDATE shop_orders SET 
                        payment_status = '$payment_status',
                        order_status = '$order_status',
                        transaction_id = '$transaction_id',
                        updated_at = NOW()
                        WHERE id = $order_id";
        
        if ($conn->query($update_query)) {
            // Log successful update
            error_log("Order $order_id updated successfully. Status: $status, Payment: $payment_status");
            
            // Send confirmation email if payment successful
            if ($status === 'successful') {
                sendOrderConfirmationEmail($order, $data);
            }
            
            // Return success response
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Webhook processed successfully',
                'order_id' => $order_id,
                'payment_status' => $payment_status
            ]);
        } else {
            error_log("Error updating order: " . $conn->error);
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
        }
        
    } else {
        error_log("Order not found for transaction reference: $tx_ref");
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    }
    
} catch (Exception $e) {
    error_log('Webhook processing error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Internal server error']);
}

/**
 * Send order confirmation email
 */
function sendOrderConfirmationEmail($order, $payment_data) {
    // Email configuration (you can integrate with your preferred email service)
    $to = $order['customer_email'];
    $subject = 'Order Confirmation - FlipAvenue Architecture';
    
    $message = "
    <html>
    <head>
        <title>Order Confirmation</title>
    </head>
    <body>
        <h2>Thank you for your order!</h2>
        <p>Dear " . $order['customer_name'] . ",</p>
        <p>Your order has been confirmed and payment processed successfully.</p>
        
        <h3>Order Details:</h3>
        <ul>
            <li><strong>Order Number:</strong> " . $order['order_number'] . "</li>
            <li><strong>Transaction ID:</strong> " . $payment_data['data']['id'] . "</li>
            <li><strong>Amount Paid:</strong> UGX " . number_format($payment_data['data']['amount']) . "</li>
            <li><strong>Payment Method:</strong> " . $payment_data['data']['payment_type'] . "</li>
            <li><strong>Order Date:</strong> " . date('F j, Y', strtotime($order['created_at'])) . "</li>
        </ul>
        
        <p>We'll process your order and send you updates on shipping.</p>
        <p>Thank you for choosing FlipAvenue Architecture!</p>
        
        <hr>
        <p><small>This is an automated message. Please do not reply to this email.</small></p>
    </body>
    </html>
    ";
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: FlipAvenue Architecture <noreply@flipavenue.com>',
        'Reply-To: support@flipavenue.com',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Send email (configure your SMTP settings)
    mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Verify Flutterwave payment using API
 */
function verifyFlutterwavePayment($transaction_id) {
    $secret_key = FLUTTERWAVE_SECRET_KEY;
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/" . $transaction_id . "/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer " . $secret_key,
            "Content-Type: application/json"
        ),
    ));
    
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($http_code === 200) {
        return json_decode($response, true);
    }
    
    return false;
}
?>