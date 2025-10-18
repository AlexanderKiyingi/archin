# Job Openings Management System

## Overview
A complete job openings management system has been implemented to allow administrators to manage career opportunities dynamically through the CMS.

## Features

### 1. **Database Table: `job_openings`**
Stores all job position information:
- Job title
- Employment type (Full-time, Part-time, Contract, Internship)
- Location
- Description
- Requirements (pipe-separated list)
- Responsibilities (pipe-separated list)
- Salary range (optional)
- Status (active, closed, draft)
- Posted date
- Application deadline (optional)
- Automatic timestamps

### 2. **CMS Management Page: `cms/job-openings.php`**
Full-featured admin interface for managing job positions:
- **Add New Jobs**: Create new job openings with all details
- **Edit Jobs**: Update existing job information
- **Delete Jobs**: Remove obsolete positions
- **Quick Status Toggle**: Activate/close positions with one click
- **Filter by Status**: View all, active, draft, or closed positions
- **Application Count**: See how many applicants per position
- **Modal-based UI**: Clean, modern interface for add/edit operations

### 3. **Frontend Display: `careers.php`**
Dynamic job listings on the public-facing website:
- Fetches only active jobs from database
- Displays job details, requirements, and responsibilities
- Shows "days ago" posted date
- Optional salary range display
- Application deadline warnings
- Animated fade-in effects
- "No jobs available" message when empty
- Application form dropdown populated from active jobs

### 4. **Migration Script: `cms/migrate-job-openings.php`**
One-time setup script that:
- Creates the `job_openings` table
- Inserts 4 sample job positions
- Safe to run multiple times (won't duplicate data)

## Setup Instructions

### Step 1: Run Migration
Access the migration script once to create the table and sample data:
```
http://yourdomain.com/cms/migrate-job-openings.php
```

You should see:
```
✓ job_openings table created successfully
✓ Inserted: Senior Architect
✓ Inserted: Interior Designer
✓ Inserted: Project Manager
✓ Inserted: Junior Architect
Migration completed!
```

### Step 2: Access CMS
1. Log in to the CMS admin panel
2. Navigate to **Job Openings** in the sidebar
3. You'll see the 4 sample jobs

### Step 3: Manage Jobs
- **Add New**: Click "Add New Job" button
- **Edit**: Click "Edit" button on any job card
- **Delete**: Click "Delete" button (with confirmation)
- **Toggle Status**: Click "Close" or "Activate" buttons
- **View Applications**: Click on applicant count to see applications for that position

## Data Format

### Requirements and Responsibilities
Use pipe `|` separator to create lists:
```
5+ years experience|Bachelor's degree|AutoCAD skills|Team player
```

This will be displayed as:
- ✓ 5+ years experience
- ✓ Bachelor's degree
- ✓ AutoCAD skills
- ✓ Team player

### Employment Types
Choose from:
- Full-time
- Part-time
- Contract
- Internship

### Job Status
- **Active**: Visible on public careers page
- **Draft**: Hidden from public, visible in CMS
- **Closed**: Position filled or no longer accepting applications

## Frontend Display

### Public Careers Page
- Shows only **active** jobs
- Ordered by posted date (newest first)
- Displays:
  - Job title
  - Employment type badge
  - Location with icon
  - Time since posted (e.g., "Posted 3 days ago")
  - Salary range (if provided)
  - Description
  - Requirements list with checkmarks
  - Responsibilities list with arrows
  - Application deadline alert (if set)
  - "Apply Now" button

### Application Form
- Position dropdown auto-populated with active jobs
- Includes "Other / General Application" option
- Collects: Name, Email, Phone, Position, Cover Letter, Resume, Portfolio

## Database Schema

```sql
CREATE TABLE job_openings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    employment_type ENUM('Full-time', 'Part-time', 'Contract', 'Internship') DEFAULT 'Full-time',
    location VARCHAR(200) DEFAULT 'Kampala, Uganda',
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    responsibilities TEXT,
    salary_range VARCHAR(100),
    status ENUM('active', 'closed', 'draft') DEFAULT 'active',
    posted_date DATE NOT NULL,
    application_deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_posted_date (posted_date)
);
```

## Files Modified/Created

### New Files
- `cms/job-openings.php` - CMS management page
- `cms/migrate-job-openings.php` - Database migration script
- `JOB_OPENINGS_SYSTEM.md` - This documentation

### Modified Files
- `careers.php` - Now fetches jobs from database
- `cms/database.sql` - Added job_openings table and migration
- `cms/database-complete.sql` - Added job_openings table
- `cms/includes/header.php` - Added "Job Openings" link to sidebar

## Usage Tips

### Adding a New Job
1. Click "Add New Job"
2. Fill in all required fields (marked with *)
3. Enter requirements separated by `|` pipes
4. Enter responsibilities separated by `|` pipes
5. Set status:
   - **Draft**: Save without publishing
   - **Active**: Publish immediately
6. Click "Save Job Opening"

### Editing an Existing Job
1. Click "Edit" on the job card
2. Modify any fields
3. Click "Save Job Opening"

### Closing a Position
- Quick method: Click "Close" button
- Full method: Edit job and change status to "Closed"

### Reopening a Position
- Quick method: Click "Activate" button
- Full method: Edit job and change status to "Active"

### Viewing Applications
- Click on the applicant count (e.g., "5 applicants")
- Opens the Career Applications page filtered by that position

## Sample Data

Four sample jobs are created during migration:
1. **Senior Architect** - Full-time, active
2. **Interior Designer** - Full-time, active
3. **Project Manager** - Full-time, active
4. **Junior Architect** - Full-time, active

You can edit or delete these after migration.

## Benefits

✅ **No Code Editing**: Add/edit jobs through CMS, no PHP/HTML knowledge needed
✅ **Dynamic Content**: Frontend automatically updates when jobs are added/removed
✅ **Professional UI**: Modern, responsive design on both CMS and public pages
✅ **Application Tracking**: Link jobs to applications for better management
✅ **Status Control**: Draft, publish, or close positions easily
✅ **Flexible Format**: Support for various employment types and locations
✅ **SEO-Friendly**: Structured job data with proper headings and formatting

## Troubleshooting

### "No jobs found" on careers page
- Check if jobs are set to "active" status in CMS
- Verify database connection in `careers.php`
- Run migration script if table doesn't exist

### CMS page shows error
- Ensure you've run `cms/migrate-job-openings.php`
- Check database credentials in `cms/db_connect.php`
- Verify PHP errors in server logs

### Application form shows "Other" only
- Check if there are any active jobs in database
- Verify `$active_jobs` is being populated in careers.php

## Future Enhancements

Potential improvements:
- Email notifications when new applications arrive
- Job expiration automation (auto-close after deadline)
- Featured/highlighted jobs
- Job categories/departments
- Multi-location support
- Export job listings
- Public job search/filter
- Application tracking workflow

## Support

For issues or questions:
1. Check this documentation
2. Review migration script output
3. Check PHP error logs
4. Verify database connection
5. Contact system administrator

---

**System Version**: 1.0  
**Last Updated**: January 2025  
**Compatibility**: PHP 7.4+, MySQL 5.7+

