#!/bin/bash

# ========================================
# FlipAvenue Production Setup Script
# ========================================
# This script helps prepare your application for production deployment
# Run this ONLY on your production server

set -e  # Exit on any error

echo "========================================="
echo "FlipAvenue Production Setup"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root (not recommended)
if [ "$EUID" -eq 0 ]; then
    echo -e "${RED}WARNING: Running as root is not recommended!${NC}"
    read -p "Continue anyway? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Step 1: Check if .env.local exists
echo "Step 1: Checking environment configuration..."
if [ -f ".env.local" ]; then
    echo -e "${YELLOW}⚠️  .env.local already exists!${NC}"
    read -p "Do you want to overwrite it? (y/N) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        cp env.local.example .env.local
        echo -e "${GREEN}✓ Created new .env.local from example${NC}"
    else
        echo -e "${YELLOW}→ Keeping existing .env.local${NC}"
    fi
else
    cp env.local.example .env.local
    echo -e "${GREEN}✓ Created .env.local from example${NC}"
fi

# Step 2: Set file permissions
echo ""
echo "Step 2: Setting secure file permissions..."
chmod 600 .env.local
echo -e "${GREEN}✓ Set .env.local permissions to 600 (owner read/write only)${NC}"

chmod 600 cms/db_connect.php 2>/dev/null && echo -e "${GREEN}✓ Set cms/db_connect.php permissions to 600${NC}" || echo -e "${YELLOW}⚠️  cms/db_connect.php not found - will need to create it${NC}"

# Step 3: Create upload directories
echo ""
echo "Step 3: Setting up upload directories..."
mkdir -p cms/assets/uploads/shop
mkdir -p cms/assets/uploads/projects
mkdir -p cms/assets/uploads/team
mkdir -p cms/assets/uploads/services
mkdir -p cms/assets/uploads/blog
mkdir -p cms/exports
mkdir -p cms/temp

chmod 755 cms/assets/uploads
chmod 755 cms/assets/uploads/shop
chmod 755 cms/assets/uploads/projects
chmod 755 cms/assets/uploads/team
chmod 755 cms/assets/uploads/services
chmod 755 cms/assets/uploads/blog
chmod 755 cms/exports
chmod 755 cms/temp

echo -e "${GREEN}✓ Upload directories created and permissions set${NC}"

# Step 4: Create log files
echo ""
echo "Step 4: Setting up logging..."
touch cms/security.log
touch cms/rate_limits.json
chmod 644 cms/security.log
chmod 644 cms/rate_limits.json
echo -e "${GREEN}✓ Log files created${NC}"

# Step 5: Create .htaccess for upload protection
echo ""
echo "Step 5: Creating security rules..."
cat > cms/assets/uploads/.htaccess << 'EOF'
# Prevent PHP execution in upload directories
<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Allow only specific file types
<FilesMatch "\.(jpg|jpeg|png|gif|webp|pdf|doc|docx)$">
    Allow from all
</FilesMatch>
EOF
echo -e "${GREEN}✓ Created upload directory .htaccess${NC}"

# Step 6: Check for sensitive files in git
echo ""
echo "Step 6: Checking git configuration..."
if [ -d ".git" ]; then
    if grep -q ".env.local" .gitignore 2>/dev/null; then
        echo -e "${GREEN}✓ .env.local is in .gitignore${NC}"
    else
        echo -e "${RED}✗ .env.local is NOT in .gitignore!${NC}"
        echo ".env.local" >> .gitignore
        echo -e "${GREEN}✓ Added .env.local to .gitignore${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Not a git repository${NC}"
fi

# Step 7: Generate webhook secret
echo ""
echo "Step 7: Generating webhook secret..."
WEBHOOK_SECRET=$(openssl rand -base64 32 | tr -d '\n')
echo -e "${GREEN}✓ Generated webhook secret:${NC}"
echo -e "${YELLOW}$WEBHOOK_SECRET${NC}"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANT: Save this secret! You'll need to:${NC}"
echo "   1. Add it to your .env.local file as FLUTTERWAVE_WEBHOOK_SECRET"
echo "   2. Configure it in Flutterwave Dashboard → Settings → Webhooks"
echo ""

# Step 8: Summary of next steps
echo ""
echo "========================================="
echo "Setup Complete! Next Steps:"
echo "========================================="
echo ""
echo -e "${YELLOW}1. Edit .env.local and add your LIVE Flutterwave API keys:${NC}"
echo "   nano .env.local"
echo ""
echo -e "${YELLOW}2. Create/update cms/db_connect.php with production database credentials:${NC}"
echo "   cp cms/db_connect.example.php cms/db_connect.php"
echo "   nano cms/db_connect.php"
echo ""
echo -e "${YELLOW}3. Import the database schema:${NC}"
echo "   mysql -u username -p database_name < cms/database-complete.sql"
echo ""
echo -e "${YELLOW}4. Configure Flutterwave webhooks in the dashboard:${NC}"
echo "   URL: https://yourdomain.com/cms/flutterwave-webhook.php"
echo "   Secret: $WEBHOOK_SECRET"
echo ""
echo -e "${YELLOW}5. Change the default admin password in the database${NC}"
echo ""
echo -e "${YELLOW}6. Test the application thoroughly before going live${NC}"
echo ""
echo -e "${GREEN}For detailed instructions, see: FLUTTERWAVE_PRODUCTION_SETUP.md${NC}"
echo ""
echo "========================================="

# Optional: Open .env.local for editing
read -p "Do you want to edit .env.local now? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    ${EDITOR:-nano} .env.local
fi

echo ""
echo -e "${GREEN}✓ Production setup complete!${NC}"

