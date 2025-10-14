<?php
require_once 'config.php';
requireLogin();

// Check if user has admin privileges for security dashboard
if ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'admin') {
    redirect(CMS_URL . '/index.php');
}

// Get security statistics
$stats = [];

// Failed login attempts in last 24 hours
$stmt = $conn->query("SELECT COUNT(*) as count FROM login_attempts WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$stats['failed_logins_24h'] = $stmt->fetch_assoc()['count'];

// Security events in last 7 days
$stmt = $conn->query("SELECT COUNT(*) as count FROM security_logs WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)");
$stats['security_events_7d'] = $stmt->fetch_assoc()['count'];

// Active sessions
$stmt = $conn->query("SELECT COUNT(*) as count FROM admin_sessions WHERE expires_at > NOW() AND is_active = 1");
$stats['active_sessions'] = $stmt->fetch_assoc()['count'];

// Locked accounts
$stmt = $conn->query("SELECT COUNT(*) as count FROM admin_users WHERE account_locked_until > NOW()");
$stats['locked_accounts'] = $stmt->fetch_assoc()['count'];

// Recent security events
$recent_events = [];
$stmt = $conn->query("
    SELECT sl.*, au.username 
    FROM security_logs sl 
    LEFT JOIN admin_users au ON sl.user_id = au.id 
    ORDER BY sl.created_at DESC 
    LIMIT 20
");
while ($row = $stmt->fetch_assoc()) {
    $recent_events[] = $row;
}

// Recent failed login attempts
$recent_failed_logins = [];
$stmt = $conn->query("
    SELECT * FROM login_attempts 
    ORDER BY attempt_time DESC 
    LIMIT 10
");
while ($row = $stmt->fetch_assoc()) {
    $recent_failed_logins[] = $row;
}

// Active sessions
$active_sessions = [];
$stmt = $conn->query("
    SELECT s.*, au.username, au.full_name 
    FROM admin_sessions s 
    JOIN admin_users au ON s.user_id = au.id 
    WHERE s.expires_at > NOW() AND s.is_active = 1 
    ORDER BY s.created_at DESC
");
while ($row = $stmt->fetch_assoc()) {
    $active_sessions[] = $row;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token';
    } else {
        $action = $_POST['action'];
        
        switch ($action) {
            case 'clear_failed_attempts':
                $conn->query("DELETE FROM login_attempts");
                $success = 'Failed login attempts cleared';
                logSecurityEvent('security_action', 'Failed login attempts cleared', $_SESSION['admin_id']);
                break;
                
            case 'terminate_session':
                $session_id = intval($_POST['session_id']);
                $stmt = $conn->prepare("UPDATE admin_sessions SET is_active = 0 WHERE id = ?");
                $stmt->bind_param("i", $session_id);
                $stmt->execute();
                $success = 'Session terminated successfully';
                logSecurityEvent('security_action', 'Session terminated', $_SESSION['admin_id']);
                break;
                
            case 'unlock_account':
                $user_id = intval($_POST['user_id']);
                $stmt = $conn->prepare("UPDATE admin_users SET account_locked_until = NULL, failed_login_count = 0 WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $success = 'Account unlocked successfully';
                logSecurityEvent('security_action', 'Account unlocked', $_SESSION['admin_id']);
                break;
        }
        
        // Refresh page to show updated data
        redirect($_SERVER['PHP_SELF']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Security Dashboard</h1>
            <p class="text-gray-600">Monitor and manage security events and user sessions</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-medium"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p class="font-medium"><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <!-- Security Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Failed Logins (24h)</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['failed_logins_24h']; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Security Events (7d)</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['security_events_7d']; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['active_sessions']; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-lock text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Locked Accounts</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['locked_accounts']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Security Events -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Security Events</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php foreach ($recent_events as $event): ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <?php if (strpos($event['event_type'], 'failed') !== false): ?>
                                        <i class="fas fa-times-circle text-red-500"></i>
                                    <?php elseif (strpos($event['event_type'], 'successful') !== false): ?>
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    <?php else: ?>
                                        <i class="fas fa-info-circle text-blue-500"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo ucfirst(str_replace('_', ' ', $event['event_type'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-500"><?php echo $event['details']; ?></p>
                                    <p class="text-xs text-gray-400">
                                        <?php echo $event['username'] ? $event['username'] : 'System'; ?> • 
                                        <?php echo date('M j, Y H:i', strtotime($event['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Active Sessions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php foreach ($active_sessions as $session): ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?php echo $session['full_name']; ?></p>
                                        <p class="text-xs text-gray-500"><?php echo $session['ip_address']; ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">
                                        <?php echo date('M j, H:i', strtotime($session['created_at'])); ?>
                                    </span>
                                    <?php if ($session['user_id'] != $_SESSION['admin_id']): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="action" value="terminate_session">
                                            <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800" 
                                                    onclick="return confirm('Terminate this session?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Actions -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Security Actions</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-4">
                    <form method="POST" class="inline">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="clear_failed_attempts">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                onclick="return confirm('Clear all failed login attempts?')">
                            <i class="fas fa-trash mr-2"></i>Clear Failed Attempts
                        </button>
                    </form>
                    
                    <a href="<?php echo CMS_URL; ?>/audit-logs.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-list mr-2"></i>View Audit Logs
                    </a>
                    
                    <a href="<?php echo CMS_URL; ?>/user-management.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fas fa-users mr-2"></i>Manage Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
