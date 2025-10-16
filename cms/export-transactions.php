<?php
require_once 'config.php';

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'admin')) {
    http_response_code(403);
    die('Unauthorized');
}

// Get filter parameters (same as transactions.php)
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$payment_method_filter = isset($_GET['payment_method']) ? $conn->real_escape_string($_GET['payment_method']) : '';
$date_from = isset($_GET['date_from']) ? $conn->real_escape_string($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? $conn->real_escape_string($_GET['date_to']) : '';

// Build the WHERE clause (same logic as transactions.php)
$where_conditions = [];

if (!empty($search)) {
    $where_conditions[] = "(order_number LIKE '%$search%' OR customer_name LIKE '%$search%' OR customer_email LIKE '%$search%' OR transaction_id LIKE '%$search%')";
}

if (!empty($status_filter)) {
    $where_conditions[] = "payment_status = '$status_filter'";
}

if (!empty($payment_method_filter)) {
    $where_conditions[] = "payment_method = '$payment_method_filter'";
}

if (!empty($date_from)) {
    $where_conditions[] = "DATE(created_at) >= '$date_from'";
}

if (!empty($date_to)) {
    $where_conditions[] = "DATE(created_at) <= '$date_to'";
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get all transactions matching filters
$transactions_query = "SELECT * FROM shop_orders $where_clause ORDER BY created_at DESC";
$transactions_result = $conn->query($transactions_query);

// Set headers for CSV download
$filename = 'transactions_' . date('Y-m-d_H-i-s') . '.csv';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Create output stream
$output = fopen('php://output', 'w');

// Write CSV headers
fputcsv($output, [
    'Order ID',
    'Order Number',
    'Transaction ID',
    'Customer Name',
    'Customer Email',
    'Customer Phone',
    'Payment Method',
    'Mobile Money Network',
    'Mobile Money Phone',
    'Payment Status',
    'Order Status',
    'Subtotal (UGX)',
    'Shipping Cost (UGX)',
    'Tax Amount (UGX)',
    'Total Amount (UGX)',
    'Order Notes',
    'Billing Address',
    'Shipping Address',
    'Created At',
    'Updated At'
]);

// Write transaction data
while ($transaction = $transactions_result->fetch_assoc()) {
    fputcsv($output, [
        $transaction['id'],
        $transaction['order_number'],
        $transaction['transaction_id'] ?: '',
        $transaction['customer_name'],
        $transaction['customer_email'],
        $transaction['customer_phone'] ?: '',
        $transaction['payment_method'] ?: '',
        $transaction['mobile_money_network'] ?: '',
        $transaction['mobile_money_phone'] ?: '',
        $transaction['payment_status'],
        $transaction['order_status'],
        number_format($transaction['subtotal'], 2),
        number_format($transaction['shipping_cost'], 2),
        number_format($transaction['tax_amount'], 2),
        number_format($transaction['total_amount'], 2),
        $transaction['order_notes'] ?: '',
        $transaction['billing_address'],
        $transaction['shipping_address'],
        $transaction['created_at'],
        $transaction['updated_at']
    ]);
}

fclose($output);
exit;
?>
