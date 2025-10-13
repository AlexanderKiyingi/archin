<?php
/**
 * Flutterwave Payment Configuration
 * 
 * Instructions:
 * 1. Sign up for a Flutterwave account at https://dashboard.flutterwave.com/
 * 2. Get your public and secret keys from the dashboard
 * 3. Replace the placeholder values below with your actual keys
 * 4. For testing, use the test keys (FLWPUBK_TEST-... and FLWSECK_TEST-...)
 * 5. For production, use the live keys (FLWPUBK-... and FLWSECK-...)
 */

// Flutterwave Configuration
define('FLUTTERWAVE_PUBLIC_KEY', 'FLWPUBK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); // Replace with your public key
define('FLUTTERWAVE_SECRET_KEY', 'FLWSECK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); // Replace with your secret key
define('FLUTTERWAVE_ENCRYPTION_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxx'); // Replace with your encryption key

// Environment (test or live)
define('FLUTTERWAVE_ENVIRONMENT', 'test'); // Change to 'live' for production

// Currency and Country
define('FLUTTERWAVE_CURRENCY', 'UGX');
define('FLUTTERWAVE_COUNTRY', 'UG');

// Webhook URL (update this to your actual domain)
define('FLUTTERWAVE_WEBHOOK_URL', 'https://yourdomain.com/cms/flutterwave-webhook.php');

// Payment Options
define('FLUTTERWAVE_PAYMENT_OPTIONS', 'card,mobilemoney');

// Redirect URLs
define('FLUTTERWAVE_SUCCESS_URL', 'https://yourdomain.com/order-success.php');
define('FLUTTERWAVE_CANCEL_URL', 'https://yourdomain.com/checkout.php');

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
