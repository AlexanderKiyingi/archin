# Video Showcases CMS Setup

## Database Setup

1. **Run the SQL migration** to create the `video_showcases` table:
   ```sql
   -- Run this SQL file:
   cms/migrations/create_video_showcases_table.sql
   ```

   Or manually execute:
   ```sql
   CREATE TABLE IF NOT EXISTS `video_showcases` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `title` varchar(255) NOT NULL,
     `video_id` varchar(100) NOT NULL COMMENT 'YouTube or Vimeo video ID',
     `platform` enum('youtube','vimeo') NOT NULL DEFAULT 'youtube',
     `thumbnail` varchar(500) DEFAULT NULL COMMENT 'Path to thumbnail image',
     `display_order` int(11) DEFAULT 0 COMMENT 'Order for display on frontend',
     `is_active` tinyint(1) DEFAULT 1 COMMENT '1 = active, 0 = inactive',
     `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     KEY `idx_display_order` (`display_order`),
     KEY `idx_is_active` (`is_active`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

## Features

### CMS Page: `cms/video-showcases.php`
- ✅ List all video showcases
- ✅ Add new video showcases
- ✅ Edit existing videos
- ✅ Delete videos
- ✅ Upload thumbnail images
- ✅ Set display order
- ✅ Toggle active/inactive status
- ✅ Support for YouTube and Vimeo

### Frontend Integration
- ✅ Videos are automatically displayed on the homepage
- ✅ Videos are pulled from the database dynamically
- ✅ Only active videos are shown
- ✅ Videos are ordered by `display_order`

## How to Use

### Adding a Video Showcase

1. **Get Video ID:**
   - **YouTube:** From URL `https://www.youtube.com/watch?v=VIDEO_ID`, extract the `VIDEO_ID`
   - **Vimeo:** From URL `https://vimeo.com/VIDEO_ID`, extract the `VIDEO_ID`

2. **Add in CMS:**
   - Go to CMS → Video Showcases
   - Click "Add New Video"
   - Fill in:
     - **Title:** e.g., "Ssi Resort Katosi - Virtual Walkthrough"
     - **Video ID:** e.g., "dQw4w9WgXcQ" (just the ID, not the full URL)
     - **Platform:** Select YouTube or Vimeo
     - **Thumbnail:** Upload a 16:9 image (recommended: 1280x720px)
     - **Display Order:** Lower numbers appear first
     - **Status:** Check "Active" to show on website

3. **Save:** Click "Add Video"

### Editing/Deleting

- Click "Edit" next to any video to modify it
- Click "Delete" to remove a video (this also deletes the thumbnail file)

## File Structure

```
cms/
├── video-showcases.php          # CMS management page
├── migrations/
│   └── create_video_showcases_table.sql  # Database migration
└── includes/
    └── header.php               # Updated with navigation link

index.php                        # Updated to pull from database
assets/
└── uploads/
    └── showcases/               # Thumbnail images stored here
```

## Navigation

The "Video Showcases" link has been added to the CMS sidebar under the "Content" section, right after "Projects".

## Notes

- Thumbnail images are automatically stored in `assets/uploads/showcases/`
- Videos are displayed in a responsive grid (3 columns on desktop, 2 on tablet, 1 on mobile)
- The video modal opens when users click the play button
- Videos support autoplay when opened in the modal

