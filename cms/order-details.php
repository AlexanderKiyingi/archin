<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['order_status'];
        $stmt = $conn->prepare("UPDATE shop_orders SET order_status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_status, $order_id);
        $stmt->execute();
        $success_message = "Order status updated successfully!";
    }
    
    if (isset($_POST['update_payment'])) {
        $new_payment = $_POST['payment_status'];
        $stmt = $conn->prepare("UPDATE shop_orders SET payment_status = ? WHERE id = ?");
        $stmt->bind_param('si', $new_payment, $order_id);
        $stmt->execute();
        $success_message = "Payment status updated successfully!";
    }
}

// Get order details
$stmt = $conn->prepare("SELECT * FROM shop_orders WHERE id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Get order items
$stmt = $conn->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$items = $stmt->get_result();

$current_page = 'orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo htmlspecialchars($order['order_number']); ?> - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="orders.php" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Order #<?php echo htmlspecialchars($order['order_number']); ?></h1>
                <p class="text-gray-600 mt-2">Placed on <?php echo date('F d, Y \a\t g:i A', strtotime($order['created_at'])); ?></p>
            </div>
            <div>
                <button onclick="window.print()" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-print mr-2"></i>Print Order
                </button>
            </div>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i><?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Order Items</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php while ($item = $items->fetch_assoc()): ?>
                                <div class="flex items-center space-x-4 pb-4 border-b last:border-0">
                                    <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded"></div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                        <p class="text-sm text-gray-500">Quantity: <?php echo $item['quantity']; ?> × UGX <?php echo number_format($item['product_price']); ?></p>
                                    </div>
                                    <div class="font-medium text-gray-900">
                                        UGX <?php echo number_format($item['total_price']); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Order Totals -->
                        <div class="mt-6 pt-6 border-t space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">UGX <?php echo number_format($order['subtotal']); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium"><?php echo $order['shipping_cost'] == 0 ? 'FREE' : 'UGX ' . number_format($order['shipping_cost']); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium">UGX <?php echo number_format($order['tax_amount']); ?></span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2 border-t">
                                <span>Total</span>
                                <span class="text-green-600">UGX <?php echo number_format($order['total_amount']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Customer Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">Contact Details</h4>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <?php if ($order['customer_phone']): ?>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">Billing Address</h4>
                                <p class="text-sm text-gray-600 whitespace-pre-line"><?php echo htmlspecialchars($order['billing_address']); ?></p>
                            </div>
                            <?php if ($order['shipping_address']): ?>
                                <div class="md:col-span-2">
                                    <h4 class="font-medium text-gray-700 mb-2">Shipping Address</h4>
                                    <p class="text-sm text-gray-600 whitespace-pre-line"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($order['order_notes']): ?>
                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-bold text-gray-800">Order Notes</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600"><?php echo nl2br(htmlspecialchars($order['order_notes'])); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Order Status</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                                <select name="order_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Payment Status</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                                <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                    <option value="refunded" <?php echo $order['payment_status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                                </select>
                            </div>
                            <button type="submit" name="update_payment" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                Update Payment
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold text-gray-800">Order Timeline</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Order Placed</p>
                                    <p class="text-xs text-gray-500"><?php echo date('M d, Y g:i A', strtotime($order['created_at'])); ?></p>
                                </div>
                            </div>
                            
                            <?php if ($order['updated_at'] !== $order['created_at']): ?>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-sync text-blue-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Status Updated</p>
                                        <p class="text-xs text-gray-500"><?php echo date('M d, Y g:i A', strtotime($order['updated_at'])); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

