<?php
/**
 * Flutterwave Payment Configuration
 * 
 * Instructions:
 * 1. Sign up for a Flutterwave account at https://dashboard.flutterwave.com/
 * 2. Get your public and secret keys from the dashboard
 * 3. Copy env.local.example to .env.local
 * 4. Add your Flutterwave API keys to .env.local
 * 5. For testing, use the test keys (FLWPUBK_TEST-... and FLWSECK_TEST-...)
 * 6. For production, use the live keys (FLWPUBK-... and FLWSECK-...)
 */

// Load environment variables from .env.local
loadEnvFile();

// Flutterwave Configuration from environment variables
define('FLUTTERWAVE_PUBLIC_KEY', getEnvVar('FLUTTERWAVE_PUBLIC_KEY', 'FLWPUBK_TEST-4cf45196e03d8437c55f87faf06d3d79-X'));
define('FLUTTERWAVE_SECRET_KEY', getEnvVar('FLUTTERWAVE_SECRET_KEY', 'FLWSECK_TEST-b6aaa00dcbc5fd8c0e156d3ddb74a818-X'));
define('FLUTTERWAVE_ENCRYPTION_KEY', getEnvVar('FLUTTERWAVE_ENCRYPTION_KEY', 'FLWSECK_TESTd7e56fdeb2d7'));

// Environment (test or live)
define('FLUTTERWAVE_ENVIRONMENT', getEnvVar('FLUTTERWAVE_ENVIRONMENT', 'test'));

// Currency and Country
define('FLUTTERWAVE_CURRENCY', getEnvVar('FLUTTERWAVE_CURRENCY', 'UGX'));
define('FLUTTERWAVE_COUNTRY', getEnvVar('FLUTTERWAVE_COUNTRY', 'UG'));

// Webhook URL
define('FLUTTERWAVE_WEBHOOK_URL', getEnvVar('FLUTTERWAVE_WEBHOOK_URL', 'https://yourdomain.com/cms/flutterwave-webhook.php'));

// Payment Options
define('FLUTTERWAVE_PAYMENT_OPTIONS', getEnvVar('FLUTTERWAVE_PAYMENT_OPTIONS', 'card,mobilemoney'));

// Redirect URLs
define('FLUTTERWAVE_SUCCESS_URL', getEnvVar('FLUTTERWAVE_SUCCESS_URL', 'https://yourdomain.com/order-success.php'));
define('FLUTTERWAVE_CANCEL_URL', getEnvVar('FLUTTERWAVE_CANCEL_URL', 'https://yourdomain.com/checkout.php'));

/**
 * Load environment variables from .env.local file
 */
function loadEnvFile() {
    $envFile = __DIR__ . '/../.env.local';
    
    if (!file_exists($envFile)) {
        // Try alternative path
        $envFile = dirname(__DIR__) . '/.env.local';
    }
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse line
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                $value = trim($value, '"\'');
                
                // Set environment variable
                if (!empty($key)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    } else {
        error_log('Warning: .env.local file not found. Using default Flutterwave configuration.');
    }
}

/**
 * Get environment variable with fallback
 */
function getEnvVar($key, $default = null) {
    // Check $_ENV first
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    
    // Check getenv()
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    
    // Return default
    return $default;
}

/**
 * Get Flutterwave public key based on environment
 */
function getFlutterwavePublicKey() {
    return FLUTTERWAVE_PUBLIC_KEY;
}

/**
 * Get Flutterwave secret key based on environment
 */
function getFlutterwaveSecretKey() {
    return FLUTTERWAVE_SECRET_KEY;
}

/**
 * Get Flutterwave base URL based on environment
 */
function getFlutterwaveBaseUrl() {
    if (FLUTTERWAVE_ENVIRONMENT === 'live') {
        return 'https://api.flutterwave.com/v3';
    } else {
        return 'https://api.flutterwave.com/v3'; // Test environment uses same URL
    }
}

/**
 * Validate Flutterwave webhook signature
 */
function validateFlutterwaveWebhook($payload, $signature) {
    $expectedSignature = hash_hmac('sha256', $payload, FLUTTERWAVE_SECRET_KEY);
    return hash_equals($expectedSignature, $signature);
}

/**
 * Generate Flutterwave transaction reference
 */
function generateFlutterwaveTxRef() {
    return 'FA-' . time() . '-' . substr(md5(uniqid()), 0, 8);
}

/**
 * Format amount for Flutterwave (amount should be in cents for UGX)
 */
function formatFlutterwaveAmount($amount, $currency = 'UGX') {
    if ($currency === 'UGX') {
        return $amount * 100; // Convert to cents for UGX
    } else {
        return $amount * 100; // Convert to cents for other currencies
    }
}
?>
