# Flutterwave Payment Integration Guide

This document explains how to set up and use the Flutterwave payment integration for FlipAvenue Architecture.

## 📋 Table of Contents

1. [Overview](#overview)
2. [Setup Instructions](#setup-instructions)
3. [Configuration](#configuration)
4. [Payment Flow](#payment-flow)
5. [Testing](#testing)
6. [Going Live](#going-live)
7. [Troubleshooting](#troubleshooting)

## 🎯 Overview

FlipAvenue integrates with Flutterwave to accept payments via:
- **Card Payments** (Visa, Mastercard, etc.)
- **Mobile Money** (MTN Mobile Money, Airtel Money)

### Features
- ✅ Secure payment processing
- ✅ Multiple payment methods
- ✅ Mobile money network selection (MTN/Airtel)
- ✅ Payment verification via webhook
- ✅ Order management system
- ✅ Transaction tracking
- ✅ Automatic email confirmations

## 🚀 Setup Instructions

### Step 1: Create a Flutterwave Account

1. Visit [https://dashboard.flutterwave.com/signup](https://dashboard.flutterwave.com/signup)
2. Register for an account
3. Complete the verification process
4. Access your dashboard

### Step 2: Get Your API Keys

1. Log in to your Flutterwave dashboard
2. Navigate to **Settings** → **API Keys**
3. You'll find:
   - **Public Key** (FLWPUBK_TEST-... for test mode)
   - **Secret Key** (FLWSECK_TEST-... for test mode)
   - **Encryption Key**

### Step 3: Configure Environment Variables

1. Copy the example environment file:
   ```bash
   cp env.local.example .env.local
   ```

2. Open `.env.local` and add your Flutterwave API keys:
   ```env
   # Test Keys (for development)
   FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-your-actual-public-key-here
   FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-your-actual-secret-key-here
   FLUTTERWAVE_ENCRYPTION_KEY=your-actual-encryption-key-here

   # Environment
   FLUTTERWAVE_ENVIRONMENT=test

   # Currency and Country
   FLUTTERWAVE_CURRENCY=UGX
   FLUTTERWAVE_COUNTRY=UG

   # Webhook URL (update with your actual domain)
   FLUTTERWAVE_WEBHOOK_URL=https://yourdomain.com/cms/flutterwave-webhook.php

   # Payment Options
   FLUTTERWAVE_PAYMENT_OPTIONS=card,mobilemoney

   # Redirect URLs (update with your actual domain)
   FLUTTERWAVE_SUCCESS_URL=https://yourdomain.com/order-success.php
   FLUTTERWAVE_CANCEL_URL=https://yourdomain.com/checkout.php
   ```

3. **Important**: Add `.env.local` to your `.gitignore` file to keep your keys secure:
   ```
   .env.local
   ```

### Step 4: Update Database Schema

Ensure your `shop_orders` table has the necessary columns:

```sql
ALTER TABLE shop_orders 
ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS mobile_money_network VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS mobile_money_phone VARCHAR(20) DEFAULT NULL;
```

### Step 5: Configure Webhook in Flutterwave Dashboard

1. Go to Flutterwave Dashboard → **Settings** → **Webhooks**
2. Add your webhook URL: `https://yourdomain.com/cms/flutterwave-webhook.php`
3. Copy the **Webhook Secret Hash** (used for verification)

## ⚙️ Configuration

### Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `FLUTTERWAVE_PUBLIC_KEY` | Your Flutterwave public key | `FLWPUBK_TEST-xxxxx` |
| `FLUTTERWAVE_SECRET_KEY` | Your Flutterwave secret key | `FLWSECK_TEST-xxxxx` |
| `FLUTTERWAVE_ENCRYPTION_KEY` | Your Flutterwave encryption key | `FLWSECK_TESTxxxxx` |
| `FLUTTERWAVE_ENVIRONMENT` | Environment mode | `test` or `live` |
| `FLUTTERWAVE_CURRENCY` | Payment currency | `UGX` |
| `FLUTTERWAVE_COUNTRY` | Country code | `UG` |
| `FLUTTERWAVE_WEBHOOK_URL` | Webhook endpoint | `https://yourdomain.com/cms/flutterwave-webhook.php` |
| `FLUTTERWAVE_PAYMENT_OPTIONS` | Accepted payment methods | `card,mobilemoney` |
| `FLUTTERWAVE_SUCCESS_URL` | Success redirect URL | `https://yourdomain.com/order-success.php` |
| `FLUTTERWAVE_CANCEL_URL` | Cancel redirect URL | `https://yourdomain.com/checkout.php` |

### File Structure

```
archin/
├── .env.local                      # Your API keys (DO NOT commit)
├── env.local.example              # Example environment file
├── checkout.php                   # Checkout page with payment integration
├── order-success.php              # Order confirmation page
├── cms/
│   ├── flutterwave-config.php    # Flutterwave configuration
│   ├── flutterwave-webhook.php   # Webhook handler
│   └── db_connect.php            # Database connection
└── FLUTTERWAVE_INTEGRATION.md    # This file
```

## 💳 Payment Flow

### 1. Customer Journey

1. **Browse Products** → Customer adds items to cart on `shop.php`
2. **Checkout** → Customer fills in details on `checkout.php`
3. **Payment Method** → Customer selects:
   - Card Payment (Visa/Mastercard)
   - Mobile Money (MTN/Airtel)
4. **Pay** → Flutterwave payment modal opens
5. **Complete Payment** → Customer completes payment
6. **Confirmation** → Customer redirected to `order-success.php`

### 2. Technical Flow

```
Customer                    Frontend                Backend                 Flutterwave
   |                           |                       |                         |
   |--[Add to Cart]----------->|                       |                         |
   |                           |                       |                         |
   |--[Checkout]-------------->|                       |                         |
   |                           |--[Process Payment]--->|                         |
   |                           |                       |--[Initialize Payment]-->|
   |                           |                       |                         |
   |                           |<--[Payment Modal]-----|<--[Payment Modal]-------|
   |<--[Complete Payment]------|                       |                         |
   |                           |                       |<--[Webhook Notification]|
   |                           |                       |--[Verify Payment]------>|
   |                           |                       |<--[Verification Result]-|
   |                           |                       |--[Update Order Status]  |
   |                           |                       |--[Send Email]           |
   |<--[Success Page]----------|<--[Redirect]----------|                         |
```

### 3. Mobile Money Flow

For mobile money payments:

1. Customer selects "Mobile Money" payment method
2. Customer chooses network (MTN or Airtel)
3. Customer enters mobile money phone number
4. Flutterwave sends payment prompt to customer's phone
5. Customer completes payment on their phone
6. Webhook updates order status

## 🧪 Testing

### Test Cards

Use these test cards in **test mode**:

| Card Type | Card Number | CVV | Expiry | PIN | OTP |
|-----------|-------------|-----|--------|-----|-----|
| Mastercard | 5531 8866 5214 2950 | 564 | 09/32 | 3310 | 12345 |
| Visa | 4187 4274 1556 4246 | 828 | 09/32 | 3310 | 12345 |

### Test Mobile Money

For mobile money testing in test mode:
- Use any 10-digit phone number
- The payment will be simulated

### Testing Checklist

- [ ] Test card payment flow
- [ ] Test mobile money payment (MTN)
- [ ] Test mobile money payment (Airtel)
- [ ] Verify webhook receives notifications
- [ ] Check order status updates correctly
- [ ] Confirm email notifications are sent
- [ ] Test payment cancellation
- [ ] Test payment failure scenarios

## 🌐 Going Live

### Checklist

1. **Get Live API Keys**
   - Submit business documents to Flutterwave
   - Get approval for live mode
   - Obtain live API keys

2. **Update Configuration**
   ```env
   # Live Keys
   FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-your-live-public-key
   FLUTTERWAVE_SECRET_KEY=FLWSECK-your-live-secret-key
   FLUTTERWAVE_ENCRYPTION_KEY=your-live-encryption-key
   
   # Change environment to live
   FLUTTERWAVE_ENVIRONMENT=live
   ```

3. **Update URLs**
   - Update `FLUTTERWAVE_WEBHOOK_URL` with production domain
   - Update `FLUTTERWAVE_SUCCESS_URL` with production domain
   - Update `FLUTTERWAVE_CANCEL_URL` with production domain

4. **SSL Certificate**
   - Ensure your website has a valid SSL certificate
   - Flutterwave requires HTTPS for live transactions

5. **Test in Live Mode**
   - Perform small test transactions
   - Verify all flows work correctly
   - Monitor webhook notifications

## 🔧 Troubleshooting

### Issue: Payment modal doesn't open

**Solution:**
- Check browser console for JavaScript errors
- Verify Flutterwave SDK is loaded: `https://checkout.flutterwave.com/v3.js`
- Ensure public key is correctly set in `.env.local`

### Issue: Webhook not receiving notifications

**Solution:**
- Verify webhook URL is accessible (test with curl or Postman)
- Check Flutterwave dashboard webhook logs
- Ensure webhook URL uses HTTPS
- Check server error logs: `tail -f /var/log/apache2/error.log`

### Issue: Payment verification fails

**Solution:**
- Check secret key is correct
- Verify transaction ID is being passed correctly
- Check API response in webhook logs
- Ensure your IP is not blocked by Flutterwave

### Issue: Order status not updating

**Solution:**
- Check database connection in `cms/db_connect.php`
- Verify `shop_orders` table has required columns
- Check webhook is properly updating the database
- Review error logs for SQL errors

### Common Error Messages

| Error | Cause | Solution |
|-------|-------|----------|
| "Invalid public key" | Wrong API key | Check `.env.local` for correct key |
| "Transaction not found" | Wrong transaction ID | Verify payment callback data |
| "Webhook signature invalid" | Wrong secret key | Check secret key in webhook handler |
| "Cart is empty" | Session issue | Clear browser cookies, try again |

## 📞 Support

### Flutterwave Support
- Email: developers@flutterwavego.com
- Developer Docs: https://developer.flutterwave.com/docs

### FlipAvenue Support
- Review code comments in integration files
- Check server error logs for detailed messages
- Test with Flutterwave's test credentials first

## 📚 Additional Resources

- [Flutterwave Developer Documentation](https://developer.flutterwave.com/docs)
- [Flutterwave API Reference](https://developer.flutterwave.com/reference)
- [Flutterwave Dashboard](https://dashboard.flutterwave.com/)
- [Flutterwave Webhook Guide](https://developer.flutterwave.com/docs/integration-guides/webhooks/)

## 🔒 Security Best Practices

1. **Never commit `.env.local`** to version control
2. **Use HTTPS** for all production environments
3. **Validate webhook signatures** to prevent fraud
4. **Verify payments** server-side before fulfilling orders
5. **Store API keys securely** and restrict access
6. **Monitor transactions** regularly for suspicious activity
7. **Keep dependencies updated** for security patches

---

**Last Updated:** October 2025
**Integration Version:** 1.0
**Flutterwave API Version:** v3

