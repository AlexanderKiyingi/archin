<?php
require_once 'config.php';
$page_title = 'My Profile';

$message = '';
$message_type = '';

// Get current user info
$user_id = $_SESSION['admin_id'];
$user = $conn->query("SELECT * FROM admin_users WHERE id = $user_id")->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = cleanInput($_POST['full_name']);
    $email = cleanInput($_POST['email']);
    
    // Update basic info
    $sql = "UPDATE admin_users SET full_name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $full_name, $email, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['admin_name'] = $full_name;
        $_SESSION['admin_email'] = $email;
        $message = 'Profile updated successfully!';
        $message_type = 'success';
        
        // Refresh user data
        $user = $conn->query("SELECT * FROM admin_users WHERE id = $user_id")->fetch_assoc();
    }
    
    // Handle password change
    if (!empty($_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $conn->query("UPDATE admin_users SET password = '$hashed_password' WHERE id = $user_id");
                $message = 'Password changed successfully!';
                $message_type = 'success';
            } else {
                $message = 'New passwords do not match!';
                $message_type = 'error';
            }
        } else {
            $message = 'Current password is incorrect!';
            $message_type = 'error';
        }
    }
}

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto flex items-center justify-center mb-4">
                    <i class="fas fa-user text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p class="text-sm text-gray-600 mb-2">@<?php echo htmlspecialchars($user['username']); ?></p>
                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                    <?php echo ucfirst($user['role']); ?>
                </span>
                
                <div class="mt-6 pt-6 border-t text-left">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-envelope w-5 mr-3"></i>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar w-5 mr-3"></i>
                            <span>Joined <?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                        </div>
                        <?php if ($user['last_login']): ?>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock w-5 mr-3"></i>
                                <span>Last login: <?php echo date('M d, Y H:i', strtotime($user['last_login'])); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Profile Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Edit Profile</h3>
            
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="<?php echo htmlspecialchars($user['full_name']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($user['email']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username (Read Only)</label>
                        <input 
                            type="text" 
                            value="<?php echo htmlspecialchars($user['username']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                            disabled
                        >
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Change Password</h3>
            
            <form method="POST">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input 
                            type="password" 
                            name="new_password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

