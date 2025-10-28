<?php
require_once 'config.php';
requireLogin();

// Only super_admin can access this page
if ($_SESSION['admin_role'] !== 'super_admin') {
    header("Location: index.php?error=access_denied");
    exit();
}

$page_title = 'Manage Users';
$page_description = 'Manage admin user accounts and authentication details';

$error = '';
$success = '';
$users = [];

// Fetch all users
$stmt = $conn->prepare("SELECT id, username, email, password, full_name, role, two_factor_enabled, account_locked_until, failed_login_count, last_login, created_at, is_active FROM admin_users ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

// Handle edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token';
    } else {
        $action = $_POST['action'];
        $user_id = intval($_POST['user_id']);
        
        if ($action === 'update') {
            $username = cleanInput($_POST['username']);
            $email = cleanInput($_POST['email']);
            $full_name = cleanInput($_POST['full_name']);
            $role = $_POST['role'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Validate required fields
            if (empty($username) || empty($email)) {
                $error = 'Username and email are required';
            } else {
                // Check for duplicate username
                $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
                $stmt->bind_param("si", $username, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $error = 'Username already exists';
                } else {
                    // Check for duplicate email
                    $stmt = $conn->prepare("SELECT id FROM admin_users WHERE email = ? AND id != ?");
                    $stmt->bind_param("si", $email, $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $error = 'Email already exists';
                    } else {
                        // Get current user info for logging
                        $stmt = $conn->prepare("SELECT username, email, role FROM admin_users WHERE id = ?");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $old_user = $stmt->get_result()->fetch_assoc();
                        
                        // Update user
                        $stmt = $conn->prepare("UPDATE admin_users SET username = ?, email = ?, full_name = ?, role = ?, is_active = ? WHERE id = ?");
                        $stmt->bind_param("ssssii", $username, $email, $full_name, $role, $is_active, $user_id);
                        
                        if ($stmt->execute()) {
                            $changes = [];
                            if ($old_user['username'] !== $username) $changes[] = "username: {$old_user['username']} → $username";
                            if ($old_user['email'] !== $email) $changes[] = "email: {$old_user['email']} → $email";
                            if ($old_user['role'] !== $role) $changes[] = "role: {$old_user['role']} → $role";
                            
                            $success = "User updated successfully" . (!empty($changes) ? " (" . implode(", ", $changes) . ")" : "");
                            logSecurityEvent(
                                'user_updated', 
                                "User updated: {$old_user['username']} (ID: $user_id) by super admin: {$_SESSION['admin_username']}" . (!empty($changes) ? " | Changes: " . implode(", ", $changes) : ""), 
                                $_SESSION['admin_id']
                            );
                        } else {
                            $error = 'Failed to update user';
                        }
                    }
                }
            }
        } elseif ($action === 'unlock_account') {
            // Unlock account
            $stmt = $conn->prepare("UPDATE admin_users SET account_locked_until = NULL, failed_login_count = 0 WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                $stmt = $conn->prepare("SELECT username FROM admin_users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                
                $success = "Account unlocked for user: {$user['username']}";
                logSecurityEvent(
                    'account_unlocked', 
                    "Account unlocked for user: {$user['username']} (ID: $user_id) by super admin: {$_SESSION['admin_username']}", 
                    $_SESSION['admin_id']
                );
            } else {
                $error = 'Failed to unlock account';
            }
        }
        
        // Refresh users list
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role, two_factor_enabled, account_locked_until, failed_login_count, last_login, created_at, is_active FROM admin_users ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
    }
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-3 rounded-xl">
                    <i class="fas fa-users-cog text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
                    <p class="text-gray-600 mt-1">Manage admin user accounts and authentication details</p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p class="font-medium"><?php echo $error; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p class="font-medium"><?php echo $success; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-users text-indigo-600 mr-2"></i>All Users (<?php echo count($users); ?>)
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Info</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-semibold"><?php echo strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)); ?></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['username']); ?></div>
                                        <div class="text-xs text-gray-400"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    <?php 
                                        if ($user['role'] === 'super_admin') echo 'bg-purple-100 text-purple-800';
                                        elseif ($user['role'] === 'admin') echo 'bg-blue-100 text-blue-800';
                                        else echo 'bg-gray-100 text-gray-800';
                                    ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!$user['is_active']): ?>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                                <?php elseif ($user['account_locked_until'] && strtotime($user['account_locked_until']) > time()): ?>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Locked</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $user['last_login'] ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-gray-500">
                                    <?php if ($user['two_factor_enabled']): ?>
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-shield-alt text-green-500 mr-1"></i>2FA Enabled
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($user['failed_login_count'] > 0): ?>
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>
                                            <?php echo $user['failed_login_count']; ?> failed attempts
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($user['account_locked_until'] && strtotime($user['account_locked_until']) > time()): ?>
                                        <form method="POST" action="" class="inline" onsubmit="return confirm('Unlock account for <?php echo htmlspecialchars($user['username']); ?>?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="action" value="unlock_account">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 p-6 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-user-edit mr-2"></i>Edit User
                </h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <form id="editForm" method="POST" action="" class="p-6">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="user_id" id="edit_user_id">

            <div class="space-y-4">
                <div>
                    <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="edit_username" name="username" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="edit_email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="edit_full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="edit_full_name" name="full_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="edit_role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">Active Account</label>
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editUser(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_full_name').value = user.full_name || '';
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_is_active').checked = user.is_active == 1;
    
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>

