<?php
/**
 * Flutterwave Payment Configuration - EXAMPLE
 * Copy this file to flutterwave-config.php and update with your actual credentials
 * 
 * IMPORTANT: Never commit flutterwave-config.php to version control!
 * 
 * Setup Instructions:
 * 1. Sign up for a Flutterwave account at https://dashboard.flutterwave.com/
 * 2. Get your API keys from Settings > API Keys in the dashboard
 * 3. Copy this file to flutterwave-config.php
 * 4. Replace the placeholder values below with your actual keys
 * 5. For testing, use the test keys (FLWPUBK_TEST-... and FLWSECK_TEST-...)
 * 6. For production, use the live keys (FLWPUBK-... and FLWSECK-...)
 * 7. Set up webhook URL in Flutterwave dashboard (Settings > Webhooks)
 */

// ==========================================
// FLUTTERWAVE API CREDENTIALS
// ==========================================

// Public Key (used in frontend)
define('FLUTTERWAVE_PUBLIC_KEY', 'FLWPUBK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X');

// Secret Key (used in backend - KEEP SECURE!)
define('FLUTTERWAVE_SECRET_KEY', 'FLWSECK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X');

// Encryption Key (for additional security)
define('FLUTTERWAVE_ENCRYPTION_KEY', 'FLWSECK_TESTxxxxxxxxxx');

// ==========================================
// ENVIRONMENT CONFIGURATION
// ==========================================

// Environment: 'test' for testing, 'live' for production
define('FLUTTERWAVE_ENVIRONMENT', 'test');

// ==========================================
// CURRENCY & LOCATION SETTINGS
// ==========================================

// Currency (UGX for Ugandan Shillings)
define('FLUTTERWAVE_CURRENCY', 'UGX');

// Country Code (UG for Uganda)
define('FLUTTERWAVE_COUNTRY', 'UG');

// ==========================================
// PAYMENT SETTINGS
// ==========================================

// Payment methods to enable (comma-separated)
// Options: card, mobilemoney, banktransfer, ussd, credit, barter, payattitude
define('FLUTTERWAVE_PAYMENT_OPTIONS', 'card,mobilemoney');

// ==========================================
// WEBHOOK & REDIRECT URLs
// ==========================================

// Webhook URL (Flutterwave will send payment notifications here)
// Update this to your actual domain!
define('FLUTTERWAVE_WEBHOOK_URL', 'https://yourdomain.com/cms/flutterwave-webhook.php');

// Success redirect URL (where to redirect after successful payment)
define('FLUTTERWAVE_SUCCESS_URL', 'https://yourdomain.com/order-success.php');

// Cancel redirect URL (where to redirect if payment is cancelled)
define('FLUTTERWAVE_CANCEL_URL', 'https://yourdomain.com/checkout.php');

// ==========================================
// HELPER FUNCTIONS
// ==========================================

/**
 * Get Flutterwave public key based on environment
 * @return string Public key
 */
function getFlutterwavePublicKey() {
    return FLUTTERWAVE_PUBLIC_KEY;
}

/**
 * Get Flutterwave secret key based on environment
 * @return string Secret key
 */
function getFlutterwaveSecretKey() {
    return FLUTTERWAVE_SECRET_KEY;
}

/**
 * Get Flutterwave API base URL
 * @return string Base URL
 */
function getFlutterwaveBaseUrl() {
    // Flutterwave uses the same API URL for both test and live
    // The environment is determined by the API keys used
    return 'https://api.flutterwave.com/v3';
}

/**
 * Validate Flutterwave webhook signature
 * @param string $payload Raw webhook payload
 * @param string $signature Signature from webhook header
 * @return bool True if signature is valid
 */
function validateFlutterwaveWebhook($payload, $signature) {
    $expectedSignature = hash_hmac('sha256', $payload, FLUTTERWAVE_SECRET_KEY);
    return hash_equals($expectedSignature, $signature);
}

/**
 * Generate unique transaction reference for Flutterwave
 * @return string Transaction reference
 */
function generateFlutterwaveTxRef() {
    return 'FA-' . time() . '-' . substr(md5(uniqid()), 0, 8);
}

/**
 * Format amount for Flutterwave API
 * For UGX, Flutterwave expects amounts in cents
 * @param float $amount Amount in main currency unit
 * @param string $currency Currency code
 * @return int Amount in cents
 */
function formatFlutterwaveAmount($amount, $currency = 'UGX') {
    if ($currency === 'UGX') {
        return $amount * 100; // Convert to cents
    } else {
        return $amount * 100; // Convert to cents for other currencies
    }
}

/**
 * Verify Flutterwave transaction
 * @param int $transactionId Transaction ID from Flutterwave
 * @return array|false Transaction data or false on failure
 */
function verifyFlutterwaveTransaction($transactionId) {
    $url = getFlutterwaveBaseUrl() . '/transactions/' . $transactionId . '/verify';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . getFlutterwaveSecretKey(),
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data && $data['status'] === 'success') {
            return $data['data'];
        }
    }
    
    return false;
}

/**
 * Log Flutterwave transaction for debugging
 * @param string $message Log message
 * @param array $data Additional data to log
 */
function logFlutterwaveTransaction($message, $data = []) {
    if (FLUTTERWAVE_ENVIRONMENT === 'test') {
        $logFile = __DIR__ . '/logs/flutterwave.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logEntry = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
        if (!empty($data)) {
            $logEntry .= 'Data: ' . json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;
        }
        $logEntry .= str_repeat('-', 80) . PHP_EOL;
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}

// ==========================================
// CONFIGURATION NOTES
// ==========================================

/*
 * TEST CARDS FOR TESTING:
 * 
 * Successful Payment:
 * Card: 5531886652142950
 * CVV: 564
 * Expiry: Any future date
 * PIN: 3310
 * OTP: 12345
 * 
 * Failed Payment:
 * Card: 5531886652142950
 * CVV: 564
 * Expiry: Any future date
 * PIN: 3310
 * OTP: 54321
 * 
 * MOBILE MONEY TEST NUMBERS:
 * MTN: 054709929220
 * Airtel: 0752811046
 * 
 * WEBHOOK SETUP:
 * 1. Login to Flutterwave Dashboard
 * 2. Go to Settings > Webhooks
 * 3. Add your webhook URL: https://yourdomain.com/cms/flutterwave-webhook.php
 * 4. Copy the secret hash and verify webhook signatures
 * 
 * GOING LIVE CHECKLIST:
 * 1. ✅ Change FLUTTERWAVE_ENVIRONMENT to 'live'
 * 2. ✅ Replace test keys with live keys
 * 3. ✅ Update webhook URL to production domain
 * 4. ✅ Update redirect URLs to production domain
 * 5. ✅ Test live payment with small amount
 * 6. ✅ Verify webhook is receiving notifications
 * 7. ✅ Check SSL certificate is valid
 */
?>

