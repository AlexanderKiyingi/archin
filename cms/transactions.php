<?php
require_once 'config.php';
$page_title = 'Transaction History';
$page_description = 'View and manage payment transactions and order records';

// Pagination settings
$records_per_page = 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Search and filter parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$payment_method_filter = isset($_GET['payment_method']) ? $conn->real_escape_string($_GET['payment_method']) : '';
$date_from = isset($_GET['date_from']) ? $conn->real_escape_string($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? $conn->real_escape_string($_GET['date_to']) : '';

// Build the WHERE clause
$where_conditions = [];
$params = [];

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

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM shop_orders $where_clause";
$count_result = $conn->query($count_query);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Get transactions with pagination
$transactions_query = "SELECT * FROM shop_orders $where_clause ORDER BY created_at DESC LIMIT $records_per_page OFFSET $offset";
$transactions_result = $conn->query($transactions_query);

// Get statistics
$stats = [];

// Total transactions
$result = $conn->query("SELECT COUNT(*) as count FROM shop_orders");
$stats['total_transactions'] = $result->fetch_assoc()['count'];

// Successful transactions
$result = $conn->query("SELECT COUNT(*) as count FROM shop_orders WHERE payment_status = 'paid'");
$stats['successful_transactions'] = $result->fetch_assoc()['count'];

// Total revenue
$result = $conn->query("SELECT SUM(total_amount) as total FROM shop_orders WHERE payment_status = 'paid'");
$stats['total_revenue'] = $result->fetch_assoc()['total'] ?? 0;

// Pending transactions
$result = $conn->query("SELECT COUNT(*) as count FROM shop_orders WHERE payment_status = 'pending'");
$stats['pending_transactions'] = $result->fetch_assoc()['count'];

// Failed transactions
$result = $conn->query("SELECT COUNT(*) as count FROM shop_orders WHERE payment_status = 'failed'");
$stats['failed_transactions'] = $result->fetch_assoc()['count'];

include 'includes/header.php';
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Transactions -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm uppercase">Total Transactions</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo number_format($stats['total_transactions']); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-receipt text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Successful Transactions -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm uppercase">Successful</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo number_format($stats['successful_transactions']); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm uppercase">Total Revenue</p>
                <h3 class="text-2xl font-bold mt-1">UGX <?php echo number_format($stats['total_revenue']); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-chart-line text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Transactions -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm uppercase">Pending</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo number_format($stats['pending_transactions']); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-clock text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Transactions</h3>
    
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Order #, Customer, Email, TXN ID" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="paid" <?php echo $status_filter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="failed" <?php echo $status_filter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                <option value="refunded" <?php echo $status_filter === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
            </select>
        </div>

        <!-- Payment Method Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
            <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Methods</option>
                <option value="mobilemoney" <?php echo $payment_method_filter === 'mobilemoney' ? 'selected' : ''; ?>>Mobile Money</option>
                <option value="visa" <?php echo $payment_method_filter === 'visa' ? 'selected' : ''; ?>>VISA Card</option>
                <option value="card" <?php echo $payment_method_filter === 'card' ? 'selected' : ''; ?>>Card</option>
            </select>
        </div>

        <!-- Date From -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Date To -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Filter Buttons -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="transactions.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-times mr-2"></i>Clear
            </a>
            <a href="export-transactions.php?<?php echo http_build_query($_GET); ?>" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Export CSV
            </a>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Transaction History</h3>
        <p class="text-sm text-gray-600">Showing <?php echo number_format($total_records); ?> transactions</p>
    </div>

    <?php if ($transactions_result->num_rows > 0): ?>
        <div class="table-responsive overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($transaction = $transactions_result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($transaction['order_number']); ?></div>
                                <div class="text-sm text-gray-500">ID: <?php echo $transaction['id']; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($transaction['customer_name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($transaction['customer_email']); ?></div>
                                <?php if ($transaction['customer_phone']): ?>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($transaction['customer_phone']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">UGX <?php echo number_format($transaction['total_amount']); ?></div>
                                <div class="text-xs text-gray-500">
                                    Subtotal: UGX <?php echo number_format($transaction['subtotal']); ?>
                                </div>
                                <?php if ($transaction['shipping_cost'] > 0 || $transaction['tax_amount'] > 0): ?>
                                    <div class="text-xs text-gray-500">
                                        <?php if ($transaction['shipping_cost'] > 0): ?>
                                            Shipping: UGX <?php echo number_format($transaction['shipping_cost']); ?>
                                        <?php endif; ?>
                                        <?php if ($transaction['tax_amount'] > 0): ?>
                                            Tax: UGX <?php echo number_format($transaction['tax_amount']); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($transaction['payment_method']): ?>
                                    <div class="flex items-center">
                                        <?php if ($transaction['payment_method'] === 'mobilemoney'): ?>
                                            <i class="fas fa-mobile-alt text-blue-500 mr-2"></i>
                                            <span class="text-sm text-gray-900">Mobile Money</span>
                                        <?php elseif ($transaction['payment_method'] === 'visa' || $transaction['payment_method'] === 'card'): ?>
                                            <i class="fas fa-credit-card text-green-500 mr-2"></i>
                                            <span class="text-sm text-gray-900">Card Payment</span>
                                        <?php else: ?>
                                            <i class="fas fa-money-bill-wave text-gray-500 mr-2"></i>
                                            <span class="text-sm text-gray-900"><?php echo ucfirst($transaction['payment_method']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($transaction['mobile_money_network'] && $transaction['mobile_money_phone']): ?>
                                        <div class="text-xs text-gray-500">
                                            <?php echo $transaction['mobile_money_network']; ?>: <?php echo htmlspecialchars($transaction['mobile_money_phone']); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">Not specified</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $status_colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-purple-100 text-purple-800'
                                ];
                                $status_color = $status_colors[$transaction['payment_status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_color; ?>">
                                    <?php echo ucfirst($transaction['payment_status']); ?>
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    Order: <?php echo ucfirst($transaction['order_status']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($transaction['transaction_id']): ?>
                                    <div class="text-sm font-mono text-gray-900"><?php echo htmlspecialchars($transaction['transaction_id']); ?></div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">No transaction ID</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo date('M j, Y', strtotime($transaction['created_at'])); ?></div>
                                <div class="text-xs text-gray-500"><?php echo date('g:i A', strtotime($transaction['created_at'])); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="transaction-details.php?id=<?php echo $transaction['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-900" title="View Transaction Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($transaction['payment_status'] === 'pending'): ?>
                                        <button onclick="updateTransactionStatus(<?php echo $transaction['id']; ?>, 'paid')" 
                                                class="text-green-600 hover:text-green-900" title="Mark as Paid">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($transaction['payment_status'] === 'paid' && $transaction['order_status'] === 'pending'): ?>
                                        <button onclick="updateOrderStatus(<?php echo $transaction['id']; ?>, 'processing')" 
                                                class="text-purple-600 hover:text-purple-900" title="Process Order">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>&<?php echo http_build_query($_GET); ?>" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        <?php endif; ?>
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>&<?php echo http_build_query($_GET); ?>" 
                               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to 
                                <span class="font-medium"><?php echo min($offset + $records_per_page, $total_records); ?></span> of 
                                <span class="font-medium"><?php echo number_format($total_records); ?></span> results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                    <a href="?page=<?php echo $i; ?>&<?php echo http_build_query($_GET); ?>" 
                                       class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?php echo $i === $current_page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
            <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
        </div>
    <?php endif; ?>
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
