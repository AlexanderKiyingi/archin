# 🧪 Blog System - Quick Test Guide

## ✅ Quick 5-Minute Test

Follow these steps to test your new blog system:

---

## Step 1: Access the CMS

1. Open your browser
2. Go to: `http://localhost/archin/cms/`
3. Login with:
   - **Username:** `admin`
   - **Password:** `admin123`

---

## Step 2: Create Your First Blog Post

1. Click **"Blog"** in the left sidebar
2. Click **"Add New Post"** button (top right)
3. Fill in the form:
   - **Title:** `Welcome to FlipAvenue Blog`
   - **Content:** 
     ```html
     <p>We're excited to launch our new blog!</p>
     <h3>What You'll Find Here</h3>
     <p>On this blog, we'll share architecture insights, design tips, and project showcases.</p>
     <ul>
         <li>Architecture design tutorials</li>
         <li>Project case studies</li>
         <li>Industry trends</li>
     </ul>
     ```
   - **Publish Date:** Today's date
   - **Category:** Select "News"
   - **Tags:** `welcome, blog, announcement`
   - **Excerpt:** `We're excited to launch our new blog with architecture insights and design tips.`
   - ✅ Check **"Publish this post"**
4. Click **"Create Post"**

---

## Step 3: View Your Post

### In CMS:
- You should see your post in the table
- Note the green "Published" badge
- Click the 👁️ (eye) icon to view it on the frontend

### On Frontend:
- Or manually go to: `http://localhost/archin/blog.php`
- You should see your post!
- Click **"Read Article"** to view the full post

---

## Step 4: Create More Posts (Optional)

Create 2-3 more posts to test features:

**Post 2:**
- Title: `Top 10 Architectural Trends 2025`
- Category: Architecture
- Tags: `trends, architecture, 2025`
- Content: Any text

**Post 3:**
- Title: `Sustainable Design Guide`
- Category: Guide
- Tags: `sustainability, guide, design`
- Content: Any text

---

## Step 5: Test All Features

### ✅ Search:
1. Go to `blog.php`
2. Use the search box
3. Type "architecture"
4. Press search
5. Should show matching posts

### ✅ Category Filter:
1. On `blog.php`
2. Use the category dropdown
3. Select "Architecture"
4. Should filter posts

### ✅ Pagination:
1. Create 7+ posts
2. Go to `blog.php`
3. Should see page numbers at bottom
4. Click page 2

### ✅ Single Post:
1. Click any post title or "Read Article"
2. Should open `single.php`
3. Check:
   - Post displays correctly
   - Sidebar shows
   - Share buttons present
   - Tags clickable

### ✅ Related Posts:
1. On `single.php`
2. Sidebar should show related posts
3. Same category as current post

### ✅ Previous/Next:
1. On `single.php`
2. Scroll to bottom
3. Should see prev/next post navigation

---

## Step 6: Edit a Post

1. Go back to CMS → Blog
2. Click ✏️ (edit) icon
3. Make a change
4. Click "Update Post"
5. View on frontend to see changes

---

## Step 7: Upload an Image

1. Create a new post or edit existing
2. Click "Choose File" under Featured Image
3. Select an image (JPG, PNG, max 5MB)
4. Save the post
5. View on frontend - image should display!

---

## ✅ Success Criteria

Your blog is working if:

- [x] Can create posts in CMS
- [x] Posts appear on `blog.php`
- [x] Can click to read full post
- [x] Search works
- [x] Category filter works
- [x] Images upload successfully
- [x] Single post page displays correctly
- [x] Sidebar widgets appear
- [x] Social share buttons present
- [x] Previous/Next navigation works
- [x] Can edit and delete posts

---

## 🐛 Troubleshooting

### "Connection failed" error:
- Check database is running
- Verify database name is `flipavenue_cms`
- Check `cms/config.php` credentials

### "No posts found":
- Make sure posts are published (checkbox checked)
- Check `is_published` = 1 in database
- Verify publish date is not in the future

### Images not showing:
- Check upload folder permissions
- Verify path in database
- Check file was uploaded successfully
- Try absolute path in browser

### Blank page:
- Enable PHP error display
- Check PHP error logs
- Verify all files exist
- Check database connection

---

## 🎯 Next Steps

Once basic blog is working:

1. **Add real content** - Replace dummy posts
2. **Upload images** - Add featured images
3. **Customize categories** - Match your needs
4. **Style content** - Use HTML formatting
5. **Test on mobile** - Check responsive design
6. **SEO optimization** - Add meta descriptions
7. **Share posts** - Use social buttons

---

## 📝 Sample Posts to Create

### Architecture Post:
```
Title: Modern Minimalist Architecture Trends
Category: Architecture
Tags: minimalism, modern, trends
Content: Exploring the rise of minimalist design in contemporary architecture...
```

### Interior Design Post:
```
Title: 2025 Interior Color Palettes
Category: Interior Design  
Tags: interior, colors, trends
Content: Discover the hottest color combinations for modern interiors...
```

### Guide Post:
```
Title: How to Choose the Perfect Lighting
Category: Guide
Tags: lighting, guide, tutorial
Content: A comprehensive guide to architectural lighting design...
```

---

## ✨ Demo Data

Want quick test data? Use this SQL:

```sql
INSERT INTO blog_posts (title, slug, content, excerpt, category, tags, publish_date, is_published, author_id) VALUES
('Modern Architecture Trends', 'modern-architecture-trends', '<p>Exploring the latest in modern architecture...</p>', 'Latest trends in modern architecture', 'Architecture', 'modern, trends, architecture', CURDATE(), 1, 1),
('Interior Design Guide', 'interior-design-guide', '<p>A comprehensive guide to interior design...</p>', 'Learn interior design basics', 'Interior Design', 'interior, guide, design', CURDATE(), 1, 1),
('Sustainable Building Tips', 'sustainable-building-tips', '<p>How to build sustainably...</p>', 'Tips for sustainable construction', 'Guide', 'sustainability, tips, building', CURDATE(), 1, 1);
```

---

**Happy Blogging! 🎉**

Your blog system is fully integrated and ready to use!

