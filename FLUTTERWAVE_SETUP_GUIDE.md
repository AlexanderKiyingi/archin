# Flutterwave Payment Integration Setup Guide

## 🚀 Overview
This guide will help you set up Flutterwave payment integration for the FlipAvenue architecture website shop.

## 📋 Prerequisites
- Flutterwave account (sign up at https://dashboard.flutterwave.com/)
- XAMPP/WAMP server running
- PHP 7.4 or higher
- MySQL database

## 🔧 Step 1: Flutterwave Account Setup

### 1.1 Create Flutterwave Account
1. Go to https://dashboard.flutterwave.com/
2. Sign up for a new account
3. Verify your email address
4. Complete your business profile

### 1.2 Get Your API Keys
1. Login to your Flutterwave dashboard
2. Go to **Settings** > **API Keys**
3. Copy your **Public Key** (starts with `FLWPUBK_TEST-` for test mode)
4. Copy your **Secret Key** (starts with `FLWSECK_TEST-` for test mode)
5. Copy your **Encryption Key** (if available)

## 🗄️ Step 2: Database Setup

### 2.1 Add Transaction ID Column
Run the SQL script to add the transaction_id column to your database:

```sql
-- Execute this in phpMyAdmin or MySQL command line
ALTER TABLE shop_orders ADD COLUMN transaction_id VARCHAR(255) DEFAULT NULL AFTER payment_status;
ALTER TABLE shop_orders ADD INDEX idx_transaction_id (transaction_id);
```

**Or use the provided script:**
```bash
# Navigate to your project directory
cd C:\xampp\htdocs\archin

# Run the SQL script
mysql -u root -p flipavenue_cms < cms/add-transaction-id.sql
```

## ⚙️ Step 3: Configure Flutterwave Settings

### 3.1 Update Configuration File
Edit `cms/flutterwave-config.php` and replace the placeholder values:

```php
// Replace these with your actual keys from Flutterwave dashboard
define('FLUTTERWAVE_PUBLIC_KEY', 'FLWPUBK_TEST-your-actual-public-key-here');
define('FLUTTERWAVE_SECRET_KEY', 'FLWSECK_TEST-your-actual-secret-key-here');
define('FLUTTERWAVE_ENCRYPTION_KEY', 'your-encryption-key-here');

// Update your domain
define('FLUTTERWAVE_WEBHOOK_URL', 'https://yourdomain.com/cms/flutterwave-webhook.php');
define('FLUTTERWAVE_SUCCESS_URL', 'https://yourdomain.com/order-success.php');
define('FLUTTERWAVE_CANCEL_URL', 'https://yourdomain.com/checkout.php');
```

### 3.2 Configure Webhook URL
1. In your Flutterwave dashboard, go to **Settings** > **Webhooks**
2. Add webhook URL: `https://yourdomain.com/cms/flutterwave-webhook.php`
3. Select events: `charge.completed`, `charge.failed`
4. Save the configuration

## 🧪 Step 4: Test the Integration

### 4.1 Test Mode Setup
1. Ensure `FLUTTERWAVE_ENVIRONMENT` is set to `'test'` in `flutterwave-config.php`
2. Use test card numbers for testing:
   - **Visa**: 4187427415564246
   - **Mastercard**: 5399838383838381
   - **Expiry**: Any future date
   - **CVV**: Any 3-digit number

### 4.2 Test Payment Flow
1. Add items to cart on your shop page
2. Go to checkout
3. Fill in customer details
4. Click "Pay Securely with Flutterwave"
5. Use test card details
6. Complete the payment

## 🌐 Step 5: Production Setup

### 5.1 Switch to Live Mode
1. Update `FLUTTERWAVE_ENVIRONMENT` to `'live'` in `flutterwave-config.php`
2. Replace test keys with live keys from your Flutterwave dashboard
3. Update webhook URLs to your production domain

### 5.2 SSL Certificate
- Ensure your production site has a valid SSL certificate
- Flutterwave requires HTTPS for webhook URLs

## 📧 Step 6: Email Notifications (Optional)

### 6.1 Configure Email Settings
Update the `sendOrderConfirmationEmail()` function in `cms/flutterwave-webhook.php`:

```php
function sendOrderConfirmationEmail($order) {
    $to = $order['customer_email'];
    $subject = 'Order Confirmation - ' . $order['order_number'];
    $message = "Dear {$order['customer_name']},\n\n";
    $message .= "Thank you for your order! Your payment has been confirmed.\n\n";
    $message .= "Order Number: {$order['order_number']}\n";
    $message .= "Total Amount: $" . number_format($order['total_amount'], 2) . "\n\n";
    $message .= "We will process your order and send you a shipping confirmation soon.\n\n";
    $message .= "Best regards,\nFlipAvenue Team";
    
    // Send the email
    mail($to, $subject, $message);
}
```

## 🔍 Step 7: Monitoring and Debugging

### 7.1 Check Webhook Logs
- Webhook events are logged in your server's error log
- Check `C:\xampp\apache\logs\error.log` for webhook activity

### 7.2 Test Webhook
Use tools like ngrok to test webhooks locally:
```bash
# Install ngrok
ngrok http 80

# Use the ngrok URL as your webhook URL in Flutterwave dashboard
```

## 🚨 Troubleshooting

### Common Issues:

1. **Payment not processing**
   - Check if Flutterwave public key is correct
   - Verify JavaScript console for errors
   - Ensure HTTPS is enabled in production

2. **Webhook not receiving notifications**
   - Verify webhook URL is accessible
   - Check webhook signature validation
   - Ensure webhook URL uses HTTPS in production

3. **Database errors**
   - Run the transaction_id SQL script
   - Check database connection settings
   - Verify table structure

4. **Order not updating after payment**
   - Check webhook handler logs
   - Verify transaction reference matching
   - Ensure database permissions

## 📞 Support

- **Flutterwave Documentation**: https://developer.flutterwave.com/
- **Flutterwave Support**: https://support.flutterwave.com/
- **Test Cards**: https://developer.flutterwave.com/docs/integration-guides/testing

## 🔐 Security Notes

- Never commit your secret keys to version control
- Use environment variables for production keys
- Regularly rotate your API keys
- Monitor webhook signatures for security
- Use HTTPS in production

## ✅ Checklist

- [ ] Flutterwave account created and verified
- [ ] API keys obtained and configured
- [ ] Database updated with transaction_id column
- [ ] Configuration file updated with real keys
- [ ] Webhook URL configured in Flutterwave dashboard
- [ ] Test payment completed successfully
- [ ] Production environment configured (if applicable)
- [ ] SSL certificate installed (production)
- [ ] Email notifications configured (optional)

---

**Note**: This integration supports multiple payment methods including cards, mobile money, and bank transfers through Flutterwave's secure payment gateway.
