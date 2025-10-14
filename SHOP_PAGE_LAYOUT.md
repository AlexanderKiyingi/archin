# Shop Page Layout Structure

## 📋 Overview
The shop page (`shop.php`) is a fully functional e-commerce page with dynamic filtering, sorting, and AJAX-powered cart functionality.

---

## 🏗️ Page Structure Breakdown

### 1️⃣ **PHP Logic Section** (Lines 1-58)
```
┌─────────────────────────────────────┐
│ PHP Backend Processing              │
├─────────────────────────────────────┤
│ • Database connection               │
│ • GET parameter handling            │
│   - Category filter                 │
│   - Price range filter              │
│   - Sort order                      │
│   - Search query                    │
│ • SQL query building                │
│ • Product fetching                  │
│ • Category list fetching            │
└─────────────────────────────────────┘
```

**Key Features:**
- Dynamic filtering based on URL parameters
- Price range filtering (0-50K, 50K-100K, 100K-200K, 200K+)
- Multiple sort options (Featured, Price, Newest)
- Search functionality across name, description, and tags

---

### 2️⃣ **HTML Head Section** (Lines 59-93)
```
┌─────────────────────────────────────┐
│ Meta & Asset Loading                │
├─────────────────────────────────────┤
│ • Page metadata                     │
│ • Favicon                           │
│ • Google Fonts (Inter, Playfair)   │
│ • CSS Libraries:                    │
│   - Bootstrap 5                     │
│   - Font Awesome                    │
│   - Line Awesome icons              │
│   - Animate.css                     │
│   - Swiper.js                       │
│ • Custom styles:                    │
│   - common_style.css                │
│   - style.css                       │
└─────────────────────────────────────┘
```

---

### 3️⃣ **Loading Screen** (Lines 97-114)
```
┌─────────────────────────────────────┐
│       LOADING ANIMATION             │
│                                     │
│   L o a d i n g . . .               │
│                                     │
│ (Animated SVG with text spinner)   │
└─────────────────────────────────────┘
```

---

### 4️⃣ **Side Menu** (Lines 116-135)
```
┌─────────────────────────────────────┐
│ HAMBURGER SIDE MENU                 │
├─────────────────────────────────────┤
│ Navigation Links:                   │
│ • Home                              │
│ • About Us                          │
│ • Projects                          │
│ • Shop (current)                    │
│ • Contact                           │
│                                     │
│ [Close Button]                      │
│                                     │
│ (Background pattern decorations)    │
└─────────────────────────────────────┘
```

**Features:**
- Overlay-based slide-in menu
- Decorative background patterns
- Close button with icon

---

### 5️⃣ **Top Navigation Bar** (Lines 139-185)
```
┌────────────────────────────────────────────────────────┐
│ [Logo]  Home  About  Projects  Shop*  Contact          │
│                                                         │
│                    [Cart 🛒 Badge] [Search] [Login] [☰] │
└────────────────────────────────────────────────────────┘
```

**Components:**
- **Logo**: FlipAvenue branding (left-aligned)
- **Main Navigation**: Horizontal menu
  - Home → `index.php`
  - About Us → `about.html`
  - Projects → `portfolio.php`
  - **Shop (Active)** → `shop.php`
  - Contact → `contact.php`
- **Right Side Icons**:
  - **Cart Icon** with dynamic badge count (`#cartCount`)
  - Search icon
  - Login button → `cms/login.php`
  - Hamburger menu toggle

**Dynamic Features:**
- Cart badge updates via AJAX
- Active state on "Shop" link
- Responsive collapse for mobile

---

### 6️⃣ **Page Header** (Lines 188-202)
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│             SHOP HEADER (with background image)        │
│                                                         │
│               S h o p                                   │
│                                                         │
│     Architectural Products & Design Tools              │
│            for Professionals                            │
│                                                         │
└────────────────────────────────────────────────────────┘
```

**Styling:**
- Background image overlay
- Large centered title
- Subtitle with professional description
- Minimal height (15vh) for clean look
- WOW.js animations (fadeInUp)

---

### 7️⃣ **Shop Section - Filters** (Lines 209-272)
```
┌────────────────────────────────────────────────────────┐
│  Shop Products                                          │
│  Discover our collection...                            │
│                                                         │
│         [Category ▼] [Price ▼] [Sort ▼] [🔍 Search]    │
└────────────────────────────────────────────────────────┘
```

**Filter Controls:**

1. **Category Dropdown**
   - "All Categories" (default)
   - Dynamically populated from database
   - Auto-submit on change

2. **Price Range Dropdown**
   - All Prices
   - UGX 0 - 50,000
   - UGX 50,000 - 100,000
   - UGX 100,000 - 200,000
   - UGX 200,000+

3. **Sort Dropdown**
   - Featured (default)
   - Price: Low to High
   - Price: High to Low
   - Newest

4. **Search Box**
   - Text input with search icon
   - Submit button
   - Searches: name, description, tags

**Form Behavior:**
- GET method
- Maintains filter state via hidden inputs
- Auto-submit on select change
- Manual submit for search

---

### 8️⃣ **Product Grid** (Lines 276-343)
```
┌──────────────┬──────────────┬──────────────┐
│              │              │              │
│  [Product 1] │  [Product 2] │  [Product 3] │
│              │              │              │
├──────────────┼──────────────┼──────────────┤
│              │              │              │
│  [Product 4] │  [Product 5] │  [Product 6] │
│              │              │              │
└──────────────┴──────────────┴──────────────┘

           (3 columns on desktop)
```

**Grid Layout:**
- Bootstrap grid system
- `col-lg-4` (3 columns on large screens)
- `col-md-6` (2 columns on tablets)
- Responsive stacking on mobile

---

### 9️⃣ **Individual Product Card**
```
┌───────────────────────────────────┐
│                                   │
│     [Product Image]               │
│                                   │
│  ┌─ Hover Overlay ────────────┐  │
│  │ [Quick View Button]        │  │
│  │ [Add to Cart Button]       │  │
│  └────────────────────────────┘  │
│                                   │
│  [Category Badge]                 │
│                                   │
│  Product Name                     │
│                                   │
│  Short description text here...   │
│                                   │
│  UGX 125,000      ⭐⭐⭐⭐⭐ (5.0)  │
│                                   │
└───────────────────────────────────┘
```

**Card Structure:**

1. **Image Container** (`.img`)
   - Product image (`.img-cover` for proper sizing)
   - **Hover Overlay** (`.overlay`)
     - Quick View button (currently disabled)
     - **Add to Cart button** with:
       - Shopping cart icon
       - `data-product` attribute (JSON encoded)
       - AJAX functionality

2. **Info Container** (`.info`)
   - **Category Badge**: Light background with category name
   - **Product Title**: Bold heading
   - **Description**: Truncated to 120 characters
   - **Bottom Row**:
     - **Price**: Bold, formatted with commas
     - **Rating**: 5-star display with count

**Data Attributes:**
Each card stores complete product data in JSON:
```json
{
  "id": 1,
  "name": "Product Name",
  "description": "Full description...",
  "price": 125000,
  "category": "Furniture",
  "image": "cms/uploads/..."
}
```

**Animations:**
- WOW.js `fadeInUp` with staggered delays (0s, 0.1s, 0.2s...)
- Resets after 0.5s delay

---

### 🔟 **Empty State** (Lines 333-338)
```
┌────────────────────────────────────┐
│                                    │
│           🛍️                       │
│      (Large shopping bag icon)     │
│                                    │
│     No products found.             │
│  Try a different search term.      │
│                                    │
└────────────────────────────────────┘
```

Displays when:
- No products match filters
- Search returns no results
- Category is empty

---

### 1️⃣1️⃣ **Footer** (Lines 348+)
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│  [FlipAvenue Logo]                                      │
│                                                         │
│  About | Services | Portfolio | Contact                │
│                                                         │
│  © 2025 FlipAvenue. All Rights Reserved.               │
│                                                         │
│  [Social Media Icons]                                   │
│                                                         │
└────────────────────────────────────────────────────────┘
```

Standard footer with:
- Company branding
- Quick links
- Copyright notice
- Social media links

---

### 1️⃣2️⃣ **JavaScript Section** (Lines 440-540)

```
┌─────────────────────────────────────┐
│ JAVASCRIPT FUNCTIONALITY            │
├─────────────────────────────────────┤
│ 1. WOW.js Animation Init            │
│ 2. Cart Count Updater (AJAX)       │
│ 3. Add to Cart Handler (AJAX)      │
│ 4. Product Card Click Handler      │
│ 5. Modal System                     │
└─────────────────────────────────────┘
```

**Key Functions:**

1. **`updateCartCount()`**
   - Fetches cart count from `cart-ajax.php`
   - Updates badge number (`#cartCount`)
   - Runs on page load and after cart operations

2. **Add to Cart Event Listener**
   - Listens for clicks on `.add-to-cart-btn`
   - Extracts product data from `data-product` attribute
   - Sends AJAX request to `cart-ajax.php`
   - Updates cart count on success
   - Shows visual feedback (button changes to "Added!")
   - Displays success modal

3. **Product Card Click**
   - Navigates to `product-details.php?id={product_id}`
   - Allows user to view full product details

4. **`showModal()`**
   - Professional modal notifications
   - Success/error/info types
   - Smooth animations
   - Auto-close functionality

---

## 🎨 Styling Classes

### Main Container Classes:
- `.tc-shop-style1` - Shop section wrapper
- `.tc-shop-products` - Products grid section
- `.product-card` - Individual product card
- `.shop-header` - Page header styling

### Utility Classes:
- `.wow fadeInUp` - Entrance animations
- `.img-cover` - Image object-fit styling
- `.badge` - Category labels
- `.btn-outline-light` - Add to cart button
- `.cart-badge` - Cart count indicator

---

## 📱 Responsive Breakpoints

```
Mobile (< 768px):
┌──────────┐
│ Product 1│
├──────────┤
│ Product 2│
├──────────┤
│ Product 3│
└──────────┘
(1 column, stacked vertically)

Tablet (768px - 991px):
┌──────────┬──────────┐
│ Product 1│ Product 2│
├──────────┼──────────┤
│ Product 3│ Product 4│
└──────────┴──────────┘
(2 columns)

Desktop (≥ 992px):
┌──────────┬──────────┬──────────┐
│ Product 1│ Product 2│ Product 3│
├──────────┼──────────┼──────────┤
│ Product 4│ Product 5│ Product 6│
└──────────┴──────────┴──────────┘
(3 columns)
```

---

## 🔄 User Flow

```
1. User Lands on Shop Page
         ↓
2. Views Product Grid (all products by default)
         ↓
3. (Optional) Applies Filters
   • Select category
   • Choose price range
   • Sort by preference
   • Search by keyword
         ↓
4. Product Grid Updates (page reload with filters)
         ↓
5. User Clicks Product Card
         ↓
   → Goes to Product Details Page
         ↓
6. OR User Clicks "Add to Cart"
         ↓
   → AJAX request adds item
   → Cart badge updates
   → Success modal appears
   → Button shows "Added!"
         ↓
7. User Can Continue Shopping or Go to Cart
```

---

## 🛠️ Key Technical Features

### Backend (PHP):
✅ **Dynamic product fetching** from database  
✅ **SQL injection prevention** with `real_escape_string`  
✅ **Filter/sort/search** URL parameter handling  
✅ **Category extraction** for dropdown population  
✅ **Image path normalization** for CMS uploads  
✅ **JSON encoding** for JavaScript data transfer  

### Frontend (JavaScript):
✅ **AJAX cart operations** (no page reload)  
✅ **Real-time cart count updates**  
✅ **Professional modal system** (replaces alerts)  
✅ **Event delegation** for dynamic elements  
✅ **Visual feedback** on user actions  
✅ **Error handling** with graceful fallbacks  

### UX/UI:
✅ **WOW.js animations** for smooth entrance effects  
✅ **Hover overlays** on product images  
✅ **Responsive grid** (3 → 2 → 1 columns)  
✅ **Badge notifications** for cart count  
✅ **Empty state** messaging  
✅ **Loading screen** for better perceived performance  

---

## 📊 Data Flow

```
Database (shop_products)
         ↓
    PHP Query
         ↓
Filter/Sort/Search Logic
         ↓
    SQL Results
         ↓
   HTML Generation
         ↓
   Product Grid Display
         ↓
User Interaction (Add to Cart)
         ↓
   AJAX Request
         ↓
cart-ajax.php (Session Storage)
         ↓
   JSON Response
         ↓
UI Update (Badge + Modal)
```

---

## 🎯 Summary

The shop page is a **fully-featured e-commerce product listing** with:
- ✅ **Dynamic filtering** (category, price, search)
- ✅ **Multiple sort options** (featured, price, date)
- ✅ **AJAX-powered cart** (smooth UX, no reloads)
- ✅ **Professional UI** (modals, animations, hover effects)
- ✅ **Responsive design** (mobile-first approach)
- ✅ **CMS integration** (products managed via admin panel)
- ✅ **Clean code structure** (separation of concerns)

**Total Lines:** ~608 lines of well-organized PHP, HTML, CSS, and JavaScript code.

