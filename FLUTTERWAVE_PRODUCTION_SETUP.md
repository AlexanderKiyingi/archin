# 🚀 Flutterwave Production Setup Guide

This guide will help you transition your Flutterwave integration from test mode to live production mode.

---

## 📋 Pre-Production Checklist

Before going live, ensure you have:

- [ ] Completed Flutterwave account verification (business documents)
- [ ] Submitted required compliance documents to Flutterwave
- [ ] Tested payment flows thoroughly in test mode
- [ ] Verified webhook integration is working
- [ ] Set up SSL certificate (HTTPS) on your domain
- [ ] Configured proper error logging
- [ ] Tested all payment methods you want to accept
- [ ] Set up email notifications for failed transactions

---

## 🔑 Step 1: Get Your Live API Keys

### A. Complete Flutterwave Account Verification

1. **Login to Flutterwave Dashboard**
   - Go to: https://dashboard.flutterwave.com/

2. **Complete Business Verification**
   - Navigate to: Settings → Account Settings
   - Upload required documents:
     - Business Registration Certificate
     - Tax Identification Number (TIN)
     - Director's/Owner's ID
     - Proof of Business Address
   - Wait for verification approval (usually 1-3 business days)

3. **Enable Live Mode**
   - Once verified, you'll see a "Go Live" option in your dashboard
   - Click on it to activate live mode

### B. Get Your Live API Keys

1. **Access API Keys Section**
   - Navigate to: Settings → API Keys
   - Switch from "Test" to "Live" mode using the toggle

2. **Copy Your Live Keys**
   ```
   Public Key:     FLWPUBK-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X
   Secret Key:     FLWSECK-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X
   Encryption Key: FLWSECKxxxxxxxx
   ```

   **⚠️ CRITICAL SECURITY WARNINGS:**
   - NEVER commit these keys to GitHub
   - NEVER share these keys publicly
   - NEVER hardcode them in your source code
   - Store them securely in `.env.local` file only
   - Keep a secure backup offline (password manager, encrypted file)

---

## 🔐 Step 2: Configure Production Environment

### A. Create Production Environment File

**On your production server** (via SSH, FTP, or hosting control panel):

```bash
# Navigate to your project root
cd /path/to/your/archin/project

# Create .env.local from example
cp env.local.example .env.local

# Edit with nano, vim, or file manager
nano .env.local
```

### B. Update .env.local with LIVE Credentials

**File: `.env.local`** (Production Server Only)

```env
# ===========================================
# FLUTTERWAVE PRODUCTION CONFIGURATION
# ===========================================

# LIVE API Keys - DO NOT COMMIT TO GIT!
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-your-actual-live-public-key-here-X
FLUTTERWAVE_SECRET_KEY=FLWSECK-your-actual-live-secret-key-here-X
FLUTTERWAVE_ENCRYPTION_KEY=FLWSECKyour-actual-encryption-key

# Environment - MUST be 'live' for production
FLUTTERWAVE_ENVIRONMENT=live

# Currency and Country
FLUTTERWAVE_CURRENCY=UGX
FLUTTERWAVE_COUNTRY=UG

# Webhook URL - Update with your ACTUAL domain
FLUTTERWAVE_WEBHOOK_URL=https://www.flipavenueltd.com/cms/flutterwave-webhook.php

# Payment Options (methods you want to accept)
FLUTTERWAVE_PAYMENT_OPTIONS=card,mobilemoney,visa

# Redirect URLs - Update with your ACTUAL domain
FLUTTERWAVE_SUCCESS_URL=https://www.flipavenueltd.com/order-success.php
FLUTTERWAVE_CANCEL_URL=https://www.flipavenueltd.com/checkout.php

# Site Information
SITE_URL=https://www.flipavenueltd.com
SITE_NAME=FlipAvenue Limited
SITE_LOGO=https://www.flipavenueltd.com/assets/img/home1/logo.png

# Support Email
SUPPORT_EMAIL=support@flipavenueltd.com
```

### C. Secure the .env.local File

```bash
# Set restrictive permissions (owner read/write only)
chmod 600 .env.local

# Verify it's in .gitignore (should already be there)
grep ".env.local" .gitignore
```

---

## 🔔 Step 3: Configure Webhooks

Webhooks allow Flutterwave to notify your server about payment status changes in real-time.

### A. Set Up Webhook URL in Flutterwave Dashboard

1. **Navigate to Webhooks Settings**
   - Go to: Settings → Webhooks
   - Click "Create Webhook"

2. **Configure Webhook**
   ```
   Webhook URL: https://www.flipavenueltd.com/cms/flutterwave-webhook.php
   Secret Hash: [Generate a strong random string - save this!]
   Events: Select all payment-related events
   ```

3. **Generate Secret Hash**
   ```bash
   # Generate a secure random string
   openssl rand -base64 32
   # Or use: php -r "echo bin2hex(random_bytes(32));"
   ```

4. **Save and Test**
   - Click "Create"
   - Use the "Test Webhook" button to verify it's working

### B. Update Your .env.local with Webhook Secret

Add this line to your `.env.local`:
```env
FLUTTERWAVE_WEBHOOK_SECRET=your_generated_secret_hash_here
```

### C. Verify Webhook Security

The webhook handler (`cms/flutterwave-webhook.php`) should validate incoming requests:

```php
// This is already implemented in your code
$signature = $_SERVER['HTTP_VERIF_HASH'] ?? '';
if ($signature !== FLUTTERWAVE_SECRET_KEY) {
    http_response_code(401);
    exit('Unauthorized');
}
```

---

## 🧪 Step 4: Test in Production

### A. Perform Small Test Transactions

**Before processing real customer payments:**

1. **Test with Small Amount**
   - Make a test purchase with minimal amount (UGX 1,000)
   - Use your own card or mobile money

2. **Test All Payment Methods**
   - [ ] Card payment (Visa/Mastercard)
   - [ ] Mobile Money - MTN
   - [ ] Mobile Money - Airtel
   - [ ] Any other enabled methods

3. **Verify Complete Flow**
   - [ ] Payment initiates correctly
   - [ ] User is redirected to Flutterwave checkout
   - [ ] Payment completes successfully
   - [ ] User is redirected back to success page
   - [ ] Order appears in admin dashboard
   - [ ] Transaction is recorded in database
   - [ ] Webhook receives confirmation
   - [ ] Email notification sent (if configured)

### B. Test Error Scenarios

1. **Failed Payment**
   - Use insufficient funds or cancel payment
   - Verify user sees appropriate error message
   - Confirm order status remains "pending"

2. **Network Timeout**
   - Test with slow connection
   - Verify proper timeout handling

3. **Invalid Card**
   - Test with incorrect card details
   - Verify error is handled gracefully

### C. Monitor Logs

```bash
# Check webhook log
tail -f cms/flutterwave-webhook.log

# Check PHP error log
tail -f /var/log/php_errors.log

# Check web server error log
tail -f /var/log/nginx/error.log
# OR
tail -f /var/log/apache2/error.log
```

---

## 📊 Step 5: Monitor & Maintain

### A. Daily Monitoring

**Check These Daily:**

1. **Transaction Status**
   ```
   Admin Dashboard → Transactions
   - Review all transactions
   - Check for "pending" status older than 24 hours
   - Verify payment amounts match orders
   ```

2. **Failed Payments**
   ```
   Admin Dashboard → Transactions → Filter: Failed
   - Contact customers about failed payments
   - Investigate recurring issues
   ```

3. **Webhook Logs**
   ```bash
   # Check for webhook errors
   grep "ERROR" cms/flutterwave-webhook.log
   ```

### B. Weekly Tasks

1. **Reconciliation**
   - Compare your database transactions with Flutterwave dashboard
   - Ensure all payments are accounted for
   - Investigate any discrepancies

2. **Performance Review**
   - Check average payment completion time
   - Review abandoned cart rate
   - Analyze payment method preferences

3. **Security Audit**
   - Review access logs for suspicious activity
   - Check for failed webhook attempts
   - Verify SSL certificate is valid

### C. Monthly Tasks

1. **Financial Reconciliation**
   - Match settlements with bank account deposits
   - Account for Flutterwave fees
   - Generate financial reports

2. **Update Documentation**
   - Document any issues encountered
   - Update troubleshooting guides
   - Review and update FAQ

---

## 🔒 Security Best Practices

### 1. API Key Security

```bash
# NEVER do this:
❌ git add .env.local
❌ echo "FLUTTERWAVE_SECRET_KEY=..." in terminal
❌ Share keys via email or chat
❌ Store keys in plain text files

# ALWAYS do this:
✅ Store in .env.local only
✅ Set file permissions to 600
✅ Keep backup in encrypted password manager
✅ Rotate keys periodically (every 6 months)
```

### 2. Server Security

```apache
# .htaccess protection for sensitive files
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "flutterwave-config\.php$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 3. Transaction Verification

**Always verify transactions on your server:**

```php
// NEVER trust payment status from client-side
// ALWAYS verify with Flutterwave API using secret key
function verifyTransaction($transactionId) {
    $url = "https://api.flutterwave.com/v3/transactions/{$transactionId}/verify";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . FLUTTERWAVE_SECRET_KEY
    ]);
    // ... verify response
}
```

### 4. Rate Limiting

Implement rate limiting on checkout endpoints to prevent abuse:

```php
// Already implemented in your code via cms/security.php
if (!checkRateLimit('checkout', 10, 3600)) {
    die('Too many checkout attempts. Please try again later.');
}
```

---

## 🚨 Troubleshooting Common Issues

### Issue 1: "API key not found" Error

**Symptoms:**
- Console shows "API key not found"
- Payment modal doesn't open

**Solutions:**
1. Verify `.env.local` exists on server
2. Check file permissions (should be 600)
3. Verify keys are correct (no extra spaces)
4. Clear browser cache
5. Check PHP error logs

```bash
# Verify .env.local is loaded
php -r "require 'cms/flutterwave-config.php'; echo FLUTTERWAVE_PUBLIC_KEY;"
```

### Issue 2: Webhook Not Receiving Notifications

**Symptoms:**
- Payments complete but orders stay "pending"
- No entries in webhook log

**Solutions:**
1. **Verify webhook URL is publicly accessible**
   ```bash
   curl https://yourdomain.com/cms/flutterwave-webhook.php
   # Should return: "POST method required"
   ```

2. **Check Flutterwave dashboard**
   - Settings → Webhooks → View logs
   - Look for failed delivery attempts

3. **Verify webhook secret matches**
   ```php
   // In .env.local
   FLUTTERWAVE_WEBHOOK_SECRET=your_secret
   
   // Should match secret hash in Flutterwave dashboard
   ```

4. **Check server firewall**
   - Ensure Flutterwave IPs aren't blocked
   - Allow HTTPS traffic on port 443

### Issue 3: Payments Successful but Not Recorded

**Symptoms:**
- Money deducted from customer
- Order not showing in admin dashboard

**Solutions:**
1. **Check database connection**
   ```bash
   php -r "require 'cms/db_connect.php'; echo 'DB Connected';"
   ```

2. **Review webhook logs**
   ```bash
   tail -50 cms/flutterwave-webhook.log
   ```

3. **Manually verify transaction**
   ```sql
   SELECT * FROM shop_orders 
   WHERE transaction_id = 'TRANSACTION_ID_HERE';
   ```

4. **Contact customer and process manually if needed**

### Issue 4: "Transaction verification failed"

**Symptoms:**
- Payment completes on Flutterwave
- Verification fails on your server

**Solutions:**
1. Check secret key is correct
2. Verify API endpoint is reachable
3. Check for SSL/TLS issues
4. Review transaction ID format

---

## 📈 Performance Optimization

### 1. Enable Caching

```php
// Cache Flutterwave API responses
$cacheKey = "flutterwave_tx_{$transactionId}";
$cached = apcu_fetch($cacheKey);
if ($cached === false) {
    $response = verifyTransaction($transactionId);
    apcu_store($cacheKey, $response, 3600); // Cache for 1 hour
}
```

### 2. Async Webhook Processing

```php
// Queue webhook processing for heavy operations
// Use a job queue system like Laravel Horizon or beanstalkd
processWebhookAsync($webhookData);
```

### 3. Database Optimization

```sql
-- Add indexes for frequent queries
CREATE INDEX idx_transaction_status 
ON shop_orders(payment_status, created_at);

CREATE INDEX idx_transaction_id 
ON shop_orders(transaction_id);
```

---

## 💰 Settlement & Finances

### Understanding Settlements

**Flutterwave Settlement Schedule:**
- T+1 for verified accounts (next business day)
- T+3 for new accounts (3 business days)
- Weekends and holidays delay settlement

**Settlement Fees:**
- Card Payments: 1.4% + UGX 25
- Mobile Money: 1.4%
- Minimum fee may apply

### Track Settlements

```sql
-- Generate settlement report
SELECT 
    DATE(created_at) as transaction_date,
    COUNT(*) as total_transactions,
    SUM(total_amount) as gross_amount,
    SUM(total_amount * 0.014) as estimated_fees,
    SUM(total_amount) - SUM(total_amount * 0.014) as net_settlement
FROM shop_orders
WHERE payment_status = 'paid'
    AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(created_at)
ORDER BY transaction_date DESC;
```

---

## 📞 Support & Resources

### Flutterwave Support

- **Email:** support@flutterwave.com
- **Phone:** +234 1 888 9590
- **Dashboard:** https://dashboard.flutterwave.com/
- **Documentation:** https://developer.flutterwave.com/docs
- **Status Page:** https://status.flutterwave.com/

### Your Application Support

- **Technical Issues:** Check `cms/flutterwave-webhook.log`
- **Database Issues:** Check `cms/security.log`
- **Server Issues:** Check server error logs

### Useful Flutterwave Endpoints

```
# Transaction Verification
GET https://api.flutterwave.com/v3/transactions/{id}/verify

# Refund Transaction
POST https://api.flutterwave.com/v3/transactions/{id}/refund

# Get Transaction
GET https://api.flutterwave.com/v3/transactions/{id}

# List Transactions
GET https://api.flutterwave.com/v3/transactions
```

---

## ✅ Production Launch Checklist

**Before announcing to customers:**

```
✅ Live API keys configured in .env.local
✅ Webhook URL configured and verified
✅ SSL certificate active and valid
✅ Test transactions completed successfully
✅ All payment methods tested
✅ Error handling verified
✅ Email notifications working
✅ Admin dashboard displaying orders correctly
✅ Database backups configured
✅ Monitoring and logging active
✅ Support email/phone ready
✅ Refund process documented
✅ Terms of service updated
✅ Privacy policy includes payment info
✅ Compliance documents submitted to Flutterwave
✅ Settlement bank account verified
```

---

## 🎉 Going Live!

Once all checks are complete:

1. **Update .env.local to LIVE mode**
   ```env
   FLUTTERWAVE_ENVIRONMENT=live
   ```

2. **Clear all test data**
   ```sql
   -- Backup first!
   DELETE FROM shop_orders WHERE payment_status = 'pending';
   DELETE FROM shop_order_items WHERE order_id NOT IN (SELECT id FROM shop_orders);
   ```

3. **Announce to customers**
   - Update website with payment options
   - Send newsletter announcing new payment methods
   - Update social media

4. **Monitor closely for first 48 hours**
   - Check dashboard frequently
   - Respond quickly to any issues
   - Be ready to assist customers

---

## 📝 Post-Launch

### Week 1
- Monitor all transactions
- Gather customer feedback
- Fix any issues immediately
- Document problems and solutions

### Month 1
- Analyze payment method preferences
- Review conversion rates
- Optimize checkout flow based on data
- Prepare monthly financial report

### Ongoing
- Keep Flutterwave account information updated
- Renew SSL certificates before expiry
- Update API integration as Flutterwave releases updates
- Maintain compliance with payment regulations

---

**Congratulations on going live with Flutterwave! 🎊**

Remember: Customer trust is paramount. Always prioritize security and transparency in your payment processing.

---

**Last Updated:** October 16, 2025  
**Flutterwave API Version:** v3  
**Integration Status:** Production Ready ✅

