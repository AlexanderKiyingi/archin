<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get date range
$period = isset($_GET['period']) ? $_GET['period'] : '30days';
$start_date = date('Y-m-d', strtotime('-30 days'));
$end_date = date('Y-m-d');

switch ($period) {
    case '7days':
        $start_date = date('Y-m-d', strtotime('-7 days'));
        break;
    case '30days':
        $start_date = date('Y-m-d', strtotime('-30 days'));
        break;
    case '90days':
        $start_date = date('Y-m-d', strtotime('-90 days'));
        break;
    case 'year':
        $start_date = date('Y-m-d', strtotime('-1 year'));
        break;
}

// Get overall statistics
$stats_query = "SELECT 
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_order_value,
    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as paid_revenue,
    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as completed_orders,
    SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending_orders
FROM shop_orders
WHERE created_at BETWEEN ? AND ?";

$stmt = $conn->prepare($stats_query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

// Get daily sales for chart
$daily_query = "SELECT 
    DATE(created_at) as date,
    COUNT(*) as orders,
    SUM(total_amount) as revenue
FROM shop_orders
WHERE created_at BETWEEN ? AND ?
GROUP BY DATE(created_at)
ORDER BY date ASC";

$stmt = $conn->prepare($daily_query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$daily_sales = $stmt->get_result();

$chart_dates = [];
$chart_revenue = [];
$chart_orders = [];

while ($row = $daily_sales->fetch_assoc()) {
    $chart_dates[] = date('M d', strtotime($row['date']));
    $chart_revenue[] = (float)$row['revenue'];
    $chart_orders[] = (int)$row['orders'];
}

// Get top selling products
$products_query = "SELECT 
    soi.product_name,
    SUM(soi.quantity) as total_sold,
    SUM(soi.total_price) as total_revenue
FROM shop_order_items soi
JOIN shop_orders so ON soi.order_id = so.id
WHERE so.created_at BETWEEN ? AND ?
GROUP BY soi.product_id, soi.product_name
ORDER BY total_sold DESC
LIMIT 10";

$stmt = $conn->prepare($products_query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$top_products = $stmt->get_result();

// Get order status distribution
$status_query = "SELECT 
    order_status,
    COUNT(*) as count
FROM shop_orders
WHERE created_at BETWEEN ? AND ?
GROUP BY order_status";

$stmt = $conn->prepare($status_query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$status_distribution = $stmt->get_result();

$status_labels = [];
$status_counts = [];
while ($row = $status_distribution->fetch_assoc()) {
    $status_labels[] = ucfirst($row['order_status']);
    $status_counts[] = (int)$row['count'];
}

// Get recent orders
$recent_query = "SELECT * FROM shop_orders 
WHERE created_at BETWEEN ? AND ?
ORDER BY created_at DESC 
LIMIT 5";

$stmt = $conn->prepare($recent_query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$recent_orders = $stmt->get_result();

$current_page = 'sales';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analytics - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Sales Analytics</h1>
                <p class="text-gray-600 mt-2">Track your shop performance and revenue</p>
            </div>
            <div>
                <form method="GET" class="inline-block">
                    <select name="period" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="7days" <?php echo $period === '7days' ? 'selected' : ''; ?>>Last 7 Days</option>
                        <option value="30days" <?php echo $period === '30days' ? 'selected' : ''; ?>>Last 30 Days</option>
                        <option value="90days" <?php echo $period === '90days' ? 'selected' : ''; ?>>Last 90 Days</option>
                        <option value="year" <?php echo $period === 'year' ? 'selected' : ''; ?>>Last Year</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-80">Revenue</span>
                </div>
                <div class="text-3xl font-bold mb-1">UGX <?php echo number_format($stats['total_revenue']); ?></div>
                <div class="text-sm opacity-80">Total Sales</div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-80">Orders</span>
                </div>
                <div class="text-3xl font-bold mb-1"><?php echo number_format($stats['total_orders']); ?></div>
                <div class="text-sm opacity-80">Total Orders</div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-80">Average</span>
                </div>
                <div class="text-3xl font-bold mb-1">UGX <?php echo number_format($stats['avg_order_value']); ?></div>
                <div class="text-sm opacity-80">Order Value</div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-80">Completed</span>
                </div>
                <div class="text-3xl font-bold mb-1"><?php echo number_format($stats['completed_orders']); ?></div>
                <div class="text-sm opacity-80">Delivered Orders</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Over Time</h2>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Order Status Distribution -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Order Status Distribution</h2>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Selling Products -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Top Selling Products</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php if ($top_products->num_rows > 0): ?>
                            <?php $rank = 1; while ($product = $top_products->fetch_assoc()): ?>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-bold"><?php echo $rank++; ?></span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($product['product_name']); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo $product['total_sold']; ?> sold</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-green-600">UGX <?php echo number_format($product['total_revenue']); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500 py-8">No product sales data available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Recent Orders</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php if ($recent_orders->num_rows > 0): ?>
                            <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                <div class="flex items-center justify-between border-b pb-4 last:border-0">
                                    <div>
                                        <p class="font-medium text-blue-600"><?php echo htmlspecialchars($order['order_number']); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo date('M d, Y g:i A', strtotime($order['created_at'])); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">UGX <?php echo number_format($order['total_amount']); ?></p>
                                        <?php
                                        $status_colors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $color = $status_colors[$order['order_status']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full <?php echo $color; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500 py-8">No recent orders</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_dates); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($chart_revenue); ?>,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($status_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($status_counts); ?>,
                    backgroundColor: [
                        'rgb(251, 191, 36)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>

