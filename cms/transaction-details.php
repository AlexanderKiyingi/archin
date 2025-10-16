<?php
require_once 'config.php';
$page_title = 'Transaction Details';
$page_description = 'View detailed transaction information and order history';

// Get transaction ID from URL
$transaction_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$transaction_id) {
    header("Location: transactions.php");
    exit;
}

// Get transaction details
$transaction_query = "SELECT * FROM shop_orders WHERE id = $transaction_id";
$transaction_result = $conn->query($transaction_query);

if ($transaction_result->num_rows === 0) {
    header("Location: transactions.php?error=transaction_not_found");
    exit;
}

$transaction = $transaction_result->fetch_assoc();

// Get order items
$items_query = "SELECT * FROM shop_order_items WHERE order_id = $transaction_id";
$items_result = $conn->query($items_query);

include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
                <p class="text-gray-600">Order #<?php echo htmlspecialchars($transaction['order_number']); ?></p>
            </div>
            <div class="flex space-x-3">
                <a href="transactions.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Transactions
                </a>
                <a href="order-details.php?id=<?php echo $transaction_id; ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-eye mr-2"></i>View Order Details
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Transaction Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Overview -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaction Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono"><?php echo htmlspecialchars($transaction['transaction_id'] ?: 'Not available'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Number</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($transaction['order_number']); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <?php if ($transaction['payment_method']): ?>
                                <span class="inline-flex items-center">
                                    <?php if ($transaction['payment_method'] === 'mobilemoney'): ?>
                                        <i class="fas fa-mobile-alt text-blue-500 mr-2"></i>
                                        Mobile Money
                                    <?php elseif ($transaction['payment_method'] === 'visa' || $transaction['payment_method'] === 'card'): ?>
                                        <i class="fas fa-credit-card text-green-500 mr-2"></i>
                                        Card Payment
                                    <?php else: ?>
                                        <i class="fas fa-money-bill-wave text-gray-500 mr-2"></i>
                                        <?php echo ucfirst($transaction['payment_method']); ?>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-gray-500">Not specified</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <p class="mt-1">
                            <?php
                            $status_colors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-purple-100 text-purple-800'
                            ];
                            $status_color = $status_colors[$transaction['payment_status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full <?php echo $status_color; ?>">
                                <?php echo ucfirst($transaction['payment_status']); ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Status</label>
                        <p class="mt-1">
                            <?php
                            $order_status_colors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-purple-100 text-purple-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $order_status_color = $order_status_colors[$transaction['order_status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full <?php echo $order_status_color; ?>">
                                <?php echo ucfirst($transaction['order_status']); ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Created</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo date('M j, Y g:i A', strtotime($transaction['created_at'])); ?></p>
                    </div>
                </div>

                <?php if ($transaction['mobile_money_network'] && $transaction['mobile_money_phone']): ?>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Mobile Money Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Network</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($transaction['mobile_money_network']); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($transaction['mobile_money_phone']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                        <p class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($transaction['customer_name']); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="mailto:<?php echo htmlspecialchars($transaction['customer_email']); ?>" 
                               class="text-blue-600 hover:text-blue-800">
                                <?php echo htmlspecialchars($transaction['customer_email']); ?>
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <?php if ($transaction['customer_phone']): ?>
                                <a href="tel:<?php echo htmlspecialchars($transaction['customer_phone']); ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <?php echo htmlspecialchars($transaction['customer_phone']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-gray-500">Not provided</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                <?php if ($items_result->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($item = $items_result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                            <div class="text-sm text-gray-500">ID: <?php echo $item['product_id']; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            UGX <?php echo number_format($item['product_price']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $item['quantity']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            UGX <?php echo number_format($item['total_price']); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No order items found.</p>
                <?php endif; ?>
            </div>

            <?php if ($transaction['order_notes']): ?>
                <!-- Order Notes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700"><?php echo nl2br(htmlspecialchars($transaction['order_notes'])); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Financial Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal</span>
                        <span class="text-sm font-medium">UGX <?php echo number_format($transaction['subtotal']); ?></span>
                    </div>
                    <?php if ($transaction['shipping_cost'] > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Shipping</span>
                            <span class="text-sm font-medium">UGX <?php echo number_format($transaction['shipping_cost']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($transaction['tax_amount'] > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tax</span>
                            <span class="text-sm font-medium">UGX <?php echo number_format($transaction['tax_amount']); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-base font-semibold text-gray-900">Total Amount</span>
                            <span class="text-base font-bold text-gray-900">UGX <?php echo number_format($transaction['total_amount']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <?php if ($transaction['payment_status'] === 'pending'): ?>
                        <button onclick="updateTransactionStatus(<?php echo $transaction['id']; ?>, 'paid')" 
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>Mark as Paid
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($transaction['payment_status'] === 'paid' && $transaction['order_status'] === 'pending'): ?>
                        <button onclick="updateOrderStatus(<?php echo $transaction['id']; ?>, 'processing')" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-cog mr-2"></i>Process Order
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($transaction['order_status'] === 'processing'): ?>
                        <button onclick="updateOrderStatus(<?php echo $transaction['id']; ?>, 'shipped')" 
                                class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-shipping-fast mr-2"></i>Mark as Shipped
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($transaction['order_status'] === 'shipped'): ?>
                        <button onclick="updateOrderStatus(<?php echo $transaction['id']; ?>, 'delivered')" 
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>Mark as Delivered
                        </button>
                    <?php endif; ?>
                    
                    <a href="mailto:<?php echo htmlspecialchars($transaction['customer_email']); ?>" 
                       class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors inline-block text-center">
                        <i class="fas fa-envelope mr-2"></i>Email Customer
                    </a>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Address Information</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Billing Address</h4>
                        <p class="text-sm text-gray-900"><?php echo nl2br(htmlspecialchars($transaction['billing_address'])); ?></p>
                    </div>
                    <?php if ($transaction['shipping_address'] !== $transaction['billing_address']): ?>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Shipping Address</h4>
                            <p class="text-sm text-gray-900"><?php echo nl2br(htmlspecialchars($transaction['shipping_address'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update transaction status
function updateTransactionStatus(transactionId, newStatus) {
    if (confirm('Are you sure you want to update this transaction status?')) {
        fetch('update-transaction-status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `transaction_id=${transactionId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating transaction status: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating transaction status');
        });
    }
}

// Update order status
function updateOrderStatus(orderId, newStatus) {
    if (confirm('Are you sure you want to update this order status?')) {
        fetch('update-order-status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating order status: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error updating order status');
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>
