# 📋 Production Deployment Checklist

Use this checklist to ensure everything is ready before launching to production.

---

## 🔐 Security & Configuration

### Environment Files
- [ ] `.env.local` created from `env.local.example`
- [ ] Live Flutterwave API keys added to `.env.local`
- [ ] `FLUTTERWAVE_ENVIRONMENT` set to `live`
- [ ] Webhook secret generated and configured
- [ ] `.env.local` file permissions set to `600`
- [ ] `.env.local` is in `.gitignore` (verify not committed to git)

### Database Configuration
- [ ] `cms/db_connect.php` created with production credentials
- [ ] Database user has appropriate permissions only
- [ ] Strong database password used
- [ ] `cms/db_connect.php` file permissions set to `600`
- [ ] Database schema imported successfully
- [ ] Sample data reviewed (remove test data if needed)

### Admin Access
- [ ] Default admin password changed from `admin123`
- [ ] Admin email updated to valid address
- [ ] Admin username changed (optional, but recommended)
- [ ] Test admin login works

### File Permissions
```bash
# Run these commands on your server:
chmod 600 .env.local
chmod 600 cms/db_connect.php
chmod 600 cms/config.php
chmod 755 cms/assets/uploads
chmod 755 cms/assets/uploads/shop
chmod 755 cms/assets/uploads/projects
chmod 644 cms/security.log
chmod 644 cms/rate_limits.json
```
- [ ] All file permissions set correctly
- [ ] Upload directories are writable
- [ ] Sensitive config files are not publicly readable

---

## 💳 Flutterwave Configuration

### Account Setup
- [ ] Flutterwave account created
- [ ] Business verification completed and approved
- [ ] All required documents submitted
- [ ] Account switched to LIVE mode
- [ ] Live API keys obtained

### API Keys
- [ ] Live Public Key added to `.env.local`
- [ ] Live Secret Key added to `.env.local`
- [ ] Live Encryption Key added to `.env.local`
- [ ] Keys tested and working

### Webhook Configuration
- [ ] Webhook URL configured in Flutterwave dashboard
- [ ] Webhook URL: `https://yourdomain.com/cms/flutterwave-webhook.php`
- [ ] Webhook secret hash generated
- [ ] Webhook secret added to `.env.local`
- [ ] Webhook secret added to Flutterwave dashboard
- [ ] Webhook tested and receiving notifications

### Payment Methods
- [ ] Card payments enabled
- [ ] Mobile Money (MTN) enabled
- [ ] Mobile Money (Airtel) enabled
- [ ] Other payment methods configured as needed
- [ ] Payment methods tested

---

## 🌐 Domain & Server

### SSL/HTTPS
- [ ] SSL certificate installed
- [ ] HTTPS working correctly
- [ ] All pages force HTTPS
- [ ] No mixed content warnings
- [ ] SSL certificate valid and not expiring soon

### Server Configuration
- [ ] PHP version 7.4 or higher
- [ ] MySQL/MariaDB 5.7 or higher
- [ ] Required PHP extensions enabled:
  - [ ] mysqli
  - [ ] curl
  - [ ] json
  - [ ] mbstring
  - [ ] openssl
- [ ] `.htaccess` file working (if Apache)
- [ ] URL rewriting enabled
- [ ] File upload limits configured
- [ ] Memory limits appropriate
- [ ] Execution time limits set

### URLs
- [ ] All absolute URLs updated to production domain
- [ ] Success URL: `https://yourdomain.com/order-success.php`
- [ ] Cancel URL: `https://yourdomain.com/checkout.php`
- [ ] Webhook URL: `https://yourdomain.com/cms/flutterwave-webhook.php`
- [ ] Site logo URL updated
- [ ] All navigation links work correctly

---

## 🧪 Testing

### Functional Testing
- [ ] Homepage loads correctly
- [ ] All navigation links work
- [ ] Shop page displays products
- [ ] Product details page works
- [ ] Add to cart functionality works
- [ ] Cart page displays items correctly
- [ ] Checkout page loads and validates
- [ ] Payment modal opens correctly

### Payment Testing (with small amounts)
- [ ] Card payment successful (Visa/Mastercard)
- [ ] Mobile Money MTN successful
- [ ] Mobile Money Airtel successful
- [ ] Payment confirmation received
- [ ] User redirected to success page
- [ ] Order recorded in database
- [ ] Transaction appears in admin dashboard
- [ ] Webhook received notification
- [ ] Order details correct (items, amounts, customer info)

### Error Handling
- [ ] Failed payment handled gracefully
- [ ] Cancelled payment redirects correctly
- [ ] Network timeout handled
- [ ] Invalid card error displayed
- [ ] User-friendly error messages shown

### Admin Dashboard
- [ ] Admin login works
- [ ] Dashboard displays correctly
- [ ] Orders page shows transactions
- [ ] Transaction details page works
- [ ] Order status can be updated
- [ ] Transaction filtering works
- [ ] CSV export works
- [ ] All CMS functions operational

### Email Notifications (if configured)
- [ ] Order confirmation emails sent
- [ ] Payment failure notifications work
- [ ] Contact form emails delivered
- [ ] Correct sender email address

---

## 📊 Monitoring & Logging

### Logs
- [ ] Error logging enabled
- [ ] Display errors disabled for users
- [ ] Log files created and writable:
  - [ ] `cms/security.log`
  - [ ] `cms/flutterwave-webhook.log`
  - [ ] Server error logs
- [ ] Log rotation configured

### Monitoring
- [ ] Database backup scheduled
- [ ] Server monitoring active
- [ ] Uptime monitoring configured
- [ ] Payment notification alerts set up

---

## 📝 Content & Legal

### Website Content
- [ ] All placeholder text replaced
- [ ] Product descriptions complete
- [ ] Product prices accurate
- [ ] Product images uploaded
- [ ] About page updated
- [ ] Contact information correct
- [ ] Social media links updated

### Legal Pages
- [ ] Terms of Service page created/updated
- [ ] Privacy Policy page created/updated
- [ ] Refund Policy page created/updated
- [ ] Payment information in privacy policy
- [ ] Cookie policy (if applicable)
- [ ] GDPR compliance (if applicable)

---

## 🔒 Security Hardening

### Application Security
- [ ] All test/debug files removed
- [ ] Debug mode disabled
- [ ] Error display turned off for users
- [ ] SQL injection protection verified
- [ ] XSS protection enabled
- [ ] CSRF protection implemented (if applicable)
- [ ] Rate limiting active
- [ ] Brute force protection enabled
- [ ] File upload restrictions working
- [ ] Directory listing disabled

### Server Security
- [ ] Firewall configured
- [ ] Unnecessary services disabled
- [ ] Server software updated
- [ ] Weak passwords changed
- [ ] SSH key authentication (if using SSH)
- [ ] Fail2ban or similar installed (optional)

### Sensitive Files Protection
- [ ] `.env.local` not accessible via web
- [ ] `config.php` not accessible via web
- [ ] `db_connect.php` not accessible via web
- [ ] Upload directory `.htaccess` configured
- [ ] `.git` directory not accessible (if exists)

---

## 💰 Financial & Business

### Settlement Configuration
- [ ] Bank account linked to Flutterwave
- [ ] Settlement currency configured
- [ ] Settlement schedule understood
- [ ] Fee structure reviewed and accepted

### Accounting
- [ ] Transaction tracking system ready
- [ ] Revenue reporting process defined
- [ ] Refund process documented
- [ ] Tax collection configured (if applicable)

---

## 📱 User Experience

### Mobile Responsiveness
- [ ] Mobile responsive design verified
- [ ] Shop page mobile-friendly
- [ ] Cart page mobile-friendly
- [ ] Checkout page mobile-friendly
- [ ] Payment modal works on mobile
- [ ] All forms work on mobile devices

### Browser Compatibility
- [ ] Tested on Chrome
- [ ] Tested on Firefox
- [ ] Tested on Safari
- [ ] Tested on Edge
- [ ] No console errors

### Performance
- [ ] Page load time < 3 seconds
- [ ] Images optimized
- [ ] CSS/JS minified (optional)
- [ ] Caching configured
- [ ] Database queries optimized

---

## 📢 Launch Preparation

### Communication
- [ ] Support email monitored
- [ ] Support phone line ready (if applicable)
- [ ] FAQ page created
- [ ] Customer support process defined

### Marketing
- [ ] Launch announcement ready
- [ ] Social media posts prepared
- [ ] Newsletter drafted (if applicable)
- [ ] Launch date set

### Team Preparation
- [ ] Team trained on admin dashboard
- [ ] Order fulfillment process documented
- [ ] Customer service scripts ready
- [ ] Emergency contact list prepared

---

## 🚀 Go Live

### Final Checks
- [ ] Full backup created (database + files)
- [ ] Rollback plan prepared
- [ ] Maintenance page ready (if needed)
- [ ] DNS records configured correctly
- [ ] Domain pointing to production server

### Post-Launch Monitoring (First 24-48 hours)
- [ ] Monitor transactions frequently
- [ ] Check error logs regularly
- [ ] Respond quickly to customer inquiries
- [ ] Track conversion rates
- [ ] Watch for any unusual activity

---

## 📋 Documentation

### Internal Documentation
- [ ] Admin manual created
- [ ] Order processing guide documented
- [ ] Refund process documented
- [ ] Troubleshooting guide ready
- [ ] Emergency procedures documented

### Reference Materials
- [ ] `PRODUCTION_DEPLOYMENT_GUIDE.md` reviewed
- [ ] `FLUTTERWAVE_PRODUCTION_SETUP.md` reviewed
- [ ] `DATABASE_UPDATES_SUMMARY.md` reviewed
- [ ] API credentials stored securely offline

---

## ✅ Final Sign-Off

**Deployment Date:** _______________

**Deployed By:** _______________

**Verified By:** _______________

### Approvals
- [ ] Technical Lead approved
- [ ] Business Owner approved
- [ ] Security review completed
- [ ] Final testing completed

---

## 🎯 Success Metrics

Define how you'll measure success:

- **Target Metrics (First Month):**
  - [ ] Transaction success rate: ____%
  - [ ] Average order value: UGX _____
  - [ ] Customer satisfaction: ____%
  - [ ] Response time: _____ hours

---

## 🆘 Emergency Contacts

**If something goes wrong:**

1. **Technical Issues:**
   - Developer: _______________
   - Phone: _______________
   - Email: _______________

2. **Flutterwave Support:**
   - Email: support@flutterwave.com
   - Phone: +234 1 888 9590
   - Dashboard: https://dashboard.flutterwave.com/

3. **Hosting Support:**
   - Provider: _______________
   - Support: _______________

4. **Business Owner:**
   - Name: _______________
   - Phone: _______________

---

## 📌 Notes

Use this space for any additional notes or reminders:

```
_______________________________________________________________
_______________________________________________________________
_______________________________________________________________
_______________________________________________________________
_______________________________________________________________
```

---

**When all items are checked, you're ready to go live! 🎉**

*Last Updated: October 16, 2025*

