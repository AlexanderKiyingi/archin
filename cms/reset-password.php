<?php
require_once 'config.php';
requireLogin();

// Only super_admin can access this page
if ($_SESSION['admin_role'] !== 'super_admin') {
    header("Location: index.php?error=access_denied");
    exit();
}

$page_title = 'Reset User Password';
$page_description = 'Reset password for admin users';

$error = '';
$success = '';
$users = [];

// Fetch all active users
$stmt = $conn->prepare("SELECT id, username, email, full_name, role, last_login FROM admin_users WHERE is_active = 1 ORDER BY username");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token';
    } else {
        $user_id = intval($_POST['user_id']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validate input
        if (empty($user_id) || empty($new_password) || empty($confirm_password)) {
            $error = 'All fields are required';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Passwords do not match';
        } else {
            // Validate password strength
            $password_errors = validatePasswordStrength($new_password);
            if (!empty($password_errors)) {
                $error = 'Password does not meet requirements:<br>' . implode('<br>', $password_errors);
            } else {
                // Verify user exists
                $stmt = $conn->prepare("SELECT id, username, email FROM admin_users WHERE id = ? AND is_active = 1");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows === 1) {
                    $target_user = $result->fetch_assoc();
                    
                    // Update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE admin_users SET password = ?, password_changed_at = NOW() WHERE id = ?");
                    $stmt->bind_param("si", $hashed_password, $user_id);
                    
                    if ($stmt->execute()) {
                        $success = "Password reset successfully for user: {$target_user['username']}";
                        logSecurityEvent(
                            'password_reset', 
                            "Password reset for user: {$target_user['username']} (ID: {$target_user['id']}) by super admin: {$_SESSION['admin_username']}", 
                            $_SESSION['admin_id']
                        );
                        
                        // Clear the form
                        $_POST = [];
                    } else {
                        $error = 'Failed to reset password';
                    }
                } else {
                    $error = 'User not found';
                }
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 p-3 rounded-xl">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reset User Password</h1>
                    <p class="text-gray-600 mt-1">Reset password for any admin user (Super Admin Only)</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-shield-alt text-purple-600 mr-2"></i>Reset Password
                </h2>

                <!-- Password Requirements -->
                <div class="bg-purple-50 border border-purple-200 p-4 mb-6 rounded-lg">
                    <h3 class="text-sm font-semibold text-purple-900 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Password Requirements:
                    </h3>
                    <ul class="text-sm text-purple-800 space-y-1">
                        <li>• At least 8 characters long</li>
                        <li>• Contains uppercase and lowercase letters</li>
                        <li>• Contains at least one number</li>
                        <li>• Contains at least one special character</li>
                    </ul>
                </div>

                <form method="POST" action="" id="resetForm">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <!-- User Selection -->
                    <div class="mb-6">
                        <label for="user_id" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-user mr-2 text-gray-600"></i>Select User
                        </label>
                        <select 
                            id="user_id" 
                            name="user_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            required
                        >
                            <option value="">-- Select a user --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>" <?php echo (isset($_POST['user_id']) && $_POST['user_id'] == $user['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($user['username']); ?> - <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo ucfirst($user['role']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="new_password" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-key mr-2 text-gray-600"></i>New Password
                        </label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Enter new password"
                            required
                            minlength="8"
                        >
                        <div class="mt-2">
                            <div class="flex items-center flex-wrap gap-2 text-xs text-gray-600">
                                <span id="length-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>8+ characters
                                </span>
                                <span id="uppercase-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Uppercase
                                </span>
                                <span id="lowercase-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Lowercase
                                </span>
                                <span id="number-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Number
                                </span>
                                <span id="special-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Special
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-check mr-2 text-gray-600"></i>Confirm Password
                        </label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Confirm new password"
                            required
                        >
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-sync-alt mr-2"></i>Reset Password
                    </button>
                </form>
            </div>

            <!-- Users List Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-users text-indigo-600 mr-2"></i>Active Users
                </h2>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-slash text-4xl mb-3"></i>
                            <p>No active users found</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h3>
                                            <span class="px-2 py-0.5 text-xs rounded-full 
                                                <?php 
                                                    if ($user['role'] === 'super_admin') echo 'bg-purple-100 text-purple-700';
                                                    elseif ($user['role'] === 'admin') echo 'bg-blue-100 text-blue-700';
                                                    else echo 'bg-gray-100 text-gray-700';
                                                ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($user['username']); ?>
                                        </p>
                                        <?php if ($user['last_login']): ?>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i>Last login: <?php echo date('M d, Y', strtotime($user['last_login'])); ?>
                                            </p>
                                        <?php else: ?>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i>Never logged in
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Warning Notice -->
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Security Notice</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>This action will immediately update the user's password. All existing sessions will remain active until they expire. This action is logged for security purposes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Password strength checker
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        // Update check indicators
        updateCheck('length-check', checks.length);
        updateCheck('uppercase-check', checks.uppercase);
        updateCheck('lowercase-check', checks.lowercase);
        updateCheck('number-check', checks.number);
        updateCheck('special-check', checks.special);
    });
    
    function updateCheck(id, isValid) {
        const element = document.getElementById(id);
        const icon = element.querySelector('i');
        if (isValid) {
            icon.className = 'fas fa-check-circle text-green-500 mr-1';
        } else {
            icon.className = 'fas fa-circle text-gray-300 mr-1';
        }
    }
    
    // Password confirmation checker
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword && newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
    });
    
    // Form confirmation before submission
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        const userSelect = document.getElementById('user_id');
        const selectedUser = userSelect.options[userSelect.selectedIndex].text;
        
        if (!confirm(`Are you sure you want to reset the password for "${selectedUser}"?`)) {
            e.preventDefault();
            return false;
        }
    });
</script>

<?php include 'includes/footer.php'; ?>

