# ⚡ Quick Production Deployment Guide

**For experienced developers who want to get to production fast.**

---

## 🚀 5-Minute Production Setup

### 1. Get Your Flutterwave Live Keys (2 min)
```
1. Login: https://dashboard.flutterwave.com/
2. Complete business verification
3. Switch to "Live" mode
4. Copy: Public Key, Secret Key, Encryption Key
```

### 2. Configure Environment (1 min)
```bash
# On your production server
cp env.local.example .env.local
chmod 600 .env.local
nano .env.local
```

**Update these lines:**
```env
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-your-live-key-X
FLUTTERWAVE_SECRET_KEY=FLWSECK-your-live-key-X
FLUTTERWAVE_ENCRYPTION_KEY=FLWSECKyour-key
FLUTTERWAVE_ENVIRONMENT=live
FLUTTERWAVE_WEBHOOK_URL=https://yourdomain.com/cms/flutterwave-webhook.php
SITE_URL=https://yourdomain.com
```

### 3. Set Up Database (1 min)
```bash
# Import database
mysql -u username -p database_name < cms/database-complete.sql

# Update admin password
mysql -u username -p database_name
```
```sql
UPDATE admin_users 
SET password = '$2y$10$YOUR_NEW_HASH' 
WHERE username = 'admin';
```

### 4. Configure Webhook (1 min)
```
1. Generate secret: openssl rand -base64 32
2. Flutterwave Dashboard → Settings → Webhooks
3. URL: https://yourdomain.com/cms/flutterwave-webhook.php
4. Secret: [paste generated secret]
5. Add secret to .env.local: FLUTTERWAVE_WEBHOOK_SECRET=...
```

### 5. Test & Go Live (< 1 min)
```bash
# Test with small transaction (UGX 1000)
# Verify order appears in admin dashboard
# Done! 🎉
```

---

## 📋 Essential Checks

**Before Going Live:**
```
✅ SSL active (HTTPS)
✅ .env.local has LIVE keys
✅ FLUTTERWAVE_ENVIRONMENT=live
✅ Webhook configured
✅ Admin password changed
✅ Test transaction successful
✅ .env.local NOT in git (check .gitignore)
```

---

## 🆘 Quick Troubleshooting

**Payment not processing?**
- Check `.env.local` has correct LIVE keys (not TEST)
- Verify `FLUTTERWAVE_ENVIRONMENT=live`

**Webhook not working?**
```bash
curl https://yourdomain.com/cms/flutterwave-webhook.php
# Should return: "POST method required"
```

**Orders not saving?**
```sql
-- Check database connection
SELECT * FROM shop_orders ORDER BY id DESC LIMIT 1;
```

---

## 📚 Full Documentation

For detailed setup: [`FLUTTERWAVE_PRODUCTION_SETUP.md`](FLUTTERWAVE_PRODUCTION_SETUP.md)

For complete checklist: [`PRODUCTION_CHECKLIST.md`](PRODUCTION_CHECKLIST.md)

---

## 🔐 Security Reminders

```bash
# NEVER commit these files:
.env.local          # Your API keys
cms/config.php      # Site config  
cms/db_connect.php  # Database credentials

# Verify they're protected:
grep -E ".env.local|config.php|db_connect.php" .gitignore
```

---

## 📞 Support

**Flutterwave:** support@flutterwave.com | +234 1 888 9590

**Dashboard:** https://dashboard.flutterwave.com/

---

**That's it! You're live! 🚀**

