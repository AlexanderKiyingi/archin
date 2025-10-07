<?php
require_once 'config.php';
$page_title = 'Site Settings';
$page_description = 'Manage your website settings';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    foreach ($_POST as $key => $value) {
        if ($key !== 'update_settings') {
            $setting_value = cleanInput($value);
            $sql = "UPDATE site_settings SET setting_value = ? WHERE setting_key = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $setting_value, $key);
            $stmt->execute();
        }
    }
    
    $message = 'Settings updated successfully!';
    $message_type = 'success';
}

// Get all settings
$settings = getSettings();

include 'includes/header.php';
?>

<?php if ($message): ?>
    <?php echo showAlert($message, $message_type); ?>
<?php endif; ?>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">General Settings</h3>
    </div>
    
    <form method="POST" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Site Information -->
            <div class="md:col-span-2">
                <h4 class="text-md font-semibold text-gray-700 mb-4 pb-2 border-b">Site Information</h4>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                <input 
                    type="text" 
                    name="site_name" 
                    value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                <input 
                    type="text" 
                    name="site_tagline" 
                    value="<?php echo htmlspecialchars($settings['site_tagline'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="site_email" 
                    value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input 
                    type="text" 
                    name="site_phone" 
                    value="<?php echo htmlspecialchars($settings['site_phone'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea 
                    name="site_address" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                ><?php echo htmlspecialchars($settings['site_address'] ?? ''); ?></textarea>
            </div>
            
            <!-- Company Stats -->
            <div class="md:col-span-2 mt-6">
                <h4 class="text-md font-semibold text-gray-700 mb-4 pb-2 border-b">Company Statistics</h4>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Established Year</label>
                <input 
                    type="text" 
                    name="established_year" 
                    value="<?php echo htmlspecialchars($settings['established_year'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                <input 
                    type="number" 
                    name="years_experience" 
                    value="<?php echo htmlspecialchars($settings['years_experience'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Projects Completed</label>
                <input 
                    type="number" 
                    name="projects_completed" 
                    value="<?php echo htmlspecialchars($settings['projects_completed'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Happy Customers (%)</label>
                <input 
                    type="number" 
                    name="happy_customers" 
                    value="<?php echo htmlspecialchars($settings['happy_customers'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Team Members Count</label>
                <input 
                    type="number" 
                    name="team_members_count" 
                    value="<?php echo htmlspecialchars($settings['team_members_count'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <!-- Social Media -->
            <div class="md:col-span-2 mt-6">
                <h4 class="text-md font-semibold text-gray-700 mb-4 pb-2 border-b">Social Media Links</h4>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook URL
                </label>
                <input 
                    type="url" 
                    name="facebook_url" 
                    value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter URL
                </label>
                <input 
                    type="url" 
                    name="twitter_url" 
                    value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram URL
                </label>
                <input 
                    type="url" 
                    name="instagram_url" 
                    value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                </label>
                <input 
                    type="url" 
                    name="linkedin_url" 
                    value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fab fa-youtube text-red-600 mr-2"></i>YouTube URL
                </label>
                <input 
                    type="url" 
                    name="youtube_url" 
                    value="<?php echo htmlspecialchars($settings['youtube_url'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <button type="submit" name="update_settings" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

