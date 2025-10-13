# CMS to Public Pages Data Mapping

## Overview
This document lists all public-facing pages and the CMS database tables that feed data to them.

---

## 📊 **Current Status**

### ✅ **Pages Connected to CMS (Dynamic - PHP)**

| # | Public Page | CMS Data Source | Status | Description |
|---|------------|----------------|--------|-------------|
| 1 | **blog.php** | `blog_posts`, `admin_users` | ✅ Connected | Blog listing with pagination, search, categories |
| 2 | **single.php** | `blog_posts`, `admin_users` | ✅ Connected | Individual blog post view with related posts |
| 3 | **shop.php** | `shop_products` | ✅ Connected | Product catalog with filtering and sorting |
| 4 | **product-details.php** | `shop_products` | ✅ Connected | Individual product view |
| 5 | **cart.php** | localStorage (client-side) | ⚠️ Partial | Shopping cart (uses localStorage, not DB) |
| 6 | **checkout.php** | `shop_orders`, `shop_order_items` | ✅ Connected | Order processing and storage |
| 7 | **order-success.php** | `shop_orders` | ⚠️ Partial | Order confirmation (could fetch from DB) |

---

### ❌ **Pages NOT Connected to CMS (Static - HTML)**

| # | Public Page | Potential CMS Data Source | Status | Should Connect? |
|---|------------|--------------------------|--------|-----------------|
| 1 | **index.html** | `services`, `projects`, `team_members`, `testimonials`, `blog_posts` | ❌ Static | ✅ YES - Home page should be dynamic |
| 2 | **about.html** | `team_members`, `awards`, `testimonials`, `site_settings` | ❌ Static | ✅ YES - Team, awards should be dynamic |
| 3 | **portfolio.html** | `projects`, `project_categories` | ❌ Static | ✅ YES - Portfolio should be dynamic |
| 4 | **contact.html** | `site_settings`, `contact_submissions` | ❌ Static | ⚠️ PARTIAL - Form handler exists |
| 5 | **careers.html** | `site_settings`, `career_applications` | ❌ Static | ⚠️ PARTIAL - Form handler exists |

---

## 📋 **Detailed Breakdown**

### **1. Blog System** ✅ COMPLETE

#### **blog.php**
**CMS Tables Used:**
- `blog_posts` - Main blog content
- `admin_users` - Author information

**Features:**
- Pagination (6 posts per page)
- Search functionality (title, content, tags)
- Category filtering
- Featured post display
- Author attribution
- Publish date filtering
- SEO-friendly slugs

**Sample Query:**
```php
SELECT bp.*, au.full_name as author_name 
FROM blog_posts bp 
LEFT JOIN admin_users au ON bp.author_id = au.id 
WHERE bp.is_published = 1 
ORDER BY bp.publish_date DESC
```

#### **single.php**
**CMS Tables Used:**
- `blog_posts` - Post content
- `admin_users` - Author information

**Features:**
- Post by slug or ID
- Related posts (same category)
- Previous/Next navigation
- Category counts
- Read time calculation
- Dynamic meta tags

---

### **2. E-commerce System** ✅ COMPLETE

#### **shop.php**
**CMS Tables Used:**
- `shop_products` - Product catalog

**Features:**
- Category filtering (from database)
- Price range filtering
- Product search
- Sorting (price, newest, featured)
- Active products only (`is_active = 1`)
- Dynamic product images
- Real-time inventory

**Sample Query:**
```php
SELECT * FROM shop_products 
WHERE is_active = 1 
AND category = ? 
AND price BETWEEN ? AND ?
ORDER BY price ASC
```

#### **product-details.php**
**CMS Tables Used:**
- `shop_products` - Product information

**Features:**
- Dynamic product loading by ID
- Product specifications
- Image gallery
- Related products
- Stock information
- Category display

#### **checkout.php**
**CMS Tables Used:**
- `shop_orders` - Order header
- `shop_order_items` - Order line items
- `shop_products` - Product validation

**Features:**
- Order number generation
- Customer information capture
- Order total calculations
- Database order insertion
- Order item tracking
- Payment status tracking
- Redirect to success page

**Sample Insertion:**
```php
INSERT INTO shop_orders (
    order_number, customer_name, customer_email, 
    billing_address, total_amount, payment_status
) VALUES (?, ?, ?, ?, ?, 'pending')
```

---

### **3. Pages with Form Handlers** ⚠️ PARTIAL

#### **contact.html → contact-handler.php**
**CMS Tables Used:**
- `contact_submissions` - Form submissions

**Status:** ⚠️ Form saves to database, but contact page is static
**Recommendation:** Convert to `contact.php` to display company info from `site_settings`

#### **careers.html → career-handler.php**
**CMS Tables Used:**
- `career_applications` - Job applications

**Status:** ⚠️ Form saves to database, but careers page is static
**Recommendation:** Convert to `careers.php` to display open positions from database

---

### **4. Pages That SHOULD Be Connected** ❌ NOT YET CONNECTED

#### **index.html** → Should be **index.php**
**Potential CMS Tables:**
- `services` - Services section
- `projects` - Featured projects
- `team_members` - Team showcase
- `testimonials` - Client reviews
- `blog_posts` - Latest news
- `awards` - Company achievements
- `site_settings` - Site info, social links

**Sections That Should Be Dynamic:**
1. **Services Section** - Display from `services` table
2. **Featured Projects** - Display from `projects` where `is_featured = TRUE`
3. **Team Members** - Display from `team_members`
4. **Testimonials** - Display from `testimonials`
5. **Latest Blog Posts** - Display from `blog_posts`
6. **Awards/Stats** - Display from `awards`
7. **Contact Info** - Display from `site_settings`

**Benefits of Conversion:**
- ✅ Update services without editing HTML
- ✅ Manage featured projects from CMS
- ✅ Add/remove team members easily
- ✅ Update testimonials dynamically
- ✅ Automatic latest blog posts
- ✅ Centralized contact information

---

#### **about.html** → Should be **about.php**
**Potential CMS Tables:**
- `team_members` - Team section
- `awards` - Awards & recognition
- `testimonials` - Client testimonials
- `site_settings` - Company info, mission, vision

**Sections That Should Be Dynamic:**
1. **About Us Content** - From `site_settings`
2. **Team Members Grid** - From `team_members`
3. **Awards Timeline** - From `awards`
4. **Client Testimonials** - From `testimonials`
5. **Company Stats** - From `site_settings` or calculated

**Benefits of Conversion:**
- ✅ Manage team profiles from CMS
- ✅ Update awards and achievements
- ✅ Edit company information easily
- ✅ Dynamic testimonials

---

#### **portfolio.html** → Should be **portfolio.php**
**Potential CMS Tables:**
- `projects` - Portfolio items
- `project_categories` - Project filters

**Sections That Should Be Dynamic:**
1. **Project Grid** - From `projects` table
2. **Category Filters** - From `project_categories`
3. **Featured Projects** - Where `is_featured = TRUE`
4. **Project Details** - Location, client, date

**Benefits of Conversion:**
- ✅ Add/edit projects from CMS
- ✅ Manage project categories
- ✅ Feature important projects
- ✅ Auto-generate project pages
- ✅ Search and filter functionality

**Would Enable:**
- Project detail pages (`project.php?slug=project-name`)
- Category filtering
- Search functionality
- Pagination

---

## 📈 **CMS Database Tables Summary**

### **Core Content Tables**
| Table | Purpose | Used By (Current) | Should Be Used By |
|-------|---------|-------------------|-------------------|
| `blog_posts` | Blog articles | blog.php, single.php | ✅ index.php (latest posts) |
| `services` | Company services | ❌ NONE | ❌ index.php, about.php |
| `projects` | Portfolio items | ❌ NONE | ❌ index.php, portfolio.php, project.php |
| `project_categories` | Project filters | ❌ NONE | ❌ portfolio.php |
| `team_members` | Team profiles | ❌ NONE | ❌ index.php, about.php |
| `testimonials` | Client reviews | ❌ NONE | ❌ index.php, about.php |
| `awards` | Achievements | ❌ NONE | ❌ about.php |
| `shop_products` | Product catalog | shop.php, product-details.php | ✅ CONNECTED |
| `shop_orders` | Customer orders | checkout.php | ✅ CONNECTED |
| `shop_order_items` | Order details | checkout.php | ✅ CONNECTED |

### **Settings & Configuration**
| Table | Purpose | Used By (Current) | Should Be Used By |
|-------|---------|-------------------|-------------------|
| `site_settings` | Site configuration | ❌ NONE | ❌ ALL pages (footer, contact info) |
| `admin_users` | CMS users | blog.php, single.php (authors) | ✅ CONNECTED |

### **Form Submissions**
| Table | Purpose | Used By (Current) | Status |
|-------|---------|-------------------|--------|
| `contact_submissions` | Contact forms | contact-handler.php | ✅ Saves to DB |
| `career_applications` | Job applications | career-handler.php | ✅ Saves to DB |

---

## 🎯 **Recommendations for Full CMS Integration**

### **Priority 1: Homepage** (index.html → index.php)
**Impact:** HIGH - Most visited page
**Effort:** MEDIUM
**Tables:** services, projects, team_members, testimonials, blog_posts

**Sections to Convert:**
1. Services showcase
2. Featured projects
3. Team members
4. Client testimonials
5. Latest blog posts
6. Awards/statistics

---

### **Priority 2: Portfolio** (portfolio.html → portfolio.php)
**Impact:** HIGH - Core business showcase
**Effort:** MEDIUM
**Tables:** projects, project_categories

**Features to Add:**
1. Dynamic project grid
2. Category filtering
3. Project search
4. Featured projects
5. Individual project pages (project.php?slug=xxx)

---

### **Priority 3: About Page** (about.html → about.php)
**Impact:** MEDIUM
**Effort:** LOW
**Tables:** team_members, awards, testimonials

**Sections to Convert:**
1. Team members grid
2. Awards timeline
3. Client testimonials
4. Company information

---

### **Priority 4: Contact & Careers** (Enhance existing)
**Impact:** LOW - Already functional
**Effort:** LOW
**Tables:** site_settings

**Enhancements:**
1. Display company info from site_settings
2. Show multiple office locations
3. Dynamic contact information
4. List open positions (careers)

---

## 📊 **Current Integration Score**

**Pages Connected:** 7 / 12 (58%)
**Tables Being Used:** 5 / 13 (38%)
**Full Integration:** ⚠️ PARTIAL

### **Fully Connected:**
- ✅ Blog System (blog.php, single.php)
- ✅ E-commerce (shop.php, checkout.php, product-details.php)

### **Not Connected:**
- ❌ Homepage (index.html)
- ❌ About (about.html)
- ❌ Portfolio (portfolio.html)
- ❌ Contact (contact.html - form only)
- ❌ Careers (careers.html - form only)

### **Unused CMS Tables:**
- ❌ services (61% unused)
- ❌ projects (61% unused)
- ❌ project_categories (61% unused)
- ❌ team_members (61% unused)
- ❌ testimonials (61% unused)
- ❌ awards (61% unused)
- ❌ site_settings (61% unused)

---

## 🚀 **Next Steps for Full Integration**

1. **Convert index.html → index.php**
   - Fetch services from database
   - Display featured projects
   - Show team members
   - Load testimonials
   - Pull latest blog posts

2. **Convert portfolio.html → portfolio.php**
   - Dynamic project grid
   - Create project.php for individual projects
   - Category filtering
   - Search functionality

3. **Convert about.html → about.php**
   - Team members from database
   - Awards timeline
   - Dynamic testimonials

4. **Enhance contact.html → contact.php**
   - Company info from site_settings
   - Multiple locations support
   - Dynamic contact details

5. **Enhance careers.html → careers.php**
   - List open positions from database
   - Dynamic job listings
   - Benefits from site_settings

---

## 📝 **Summary**

**Currently CMS-Powered:**
- ✅ Blog & News (2 pages)
- ✅ Shop & E-commerce (5 pages)

**Ready for CMS Integration:**
- ❌ Homepage content
- ❌ Services showcase
- ❌ Portfolio/Projects
- ❌ Team members
- ❌ Testimonials
- ❌ Awards
- ❌ Site settings

**Total Pages:** 12 public pages
**CMS-Connected:** 7 pages (58%)
**Remaining:** 5 pages (42%)

The CMS infrastructure is built and ready. Converting the remaining pages will provide a **100% dynamic, database-driven website** that's easy to maintain and update without touching code!

