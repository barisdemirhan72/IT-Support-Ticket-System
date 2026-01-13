# Quick Start Guide

Get the IT Support Ticket System up and running in 10 minutes!

## Prerequisites

- PHP 8.1+
- Composer
- MySQL/MariaDB
- Node.js & NPM

## Installation Steps

### 1. Clone or Download the Project

```bash
cd it_support_ticket_system
```

### 2. Install Dependencies

```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### 3. Set Up Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file:

```env
DB_DATABASE=it_support_ticket_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```sql
CREATE DATABASE it_support_ticket_system;
```

### 5. Run Migrations & Seed Data

```bash
# Create tables
php artisan migrate

# Add sample data (optional but recommended)
php artisan db:seed
```

### 6. Build Frontend Assets

```bash
npm run dev
```

### 7. Start the Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

## Default Login Credentials

After running `php artisan db:seed`:

| Role    | Email                 | Password |
|---------|-----------------------|----------|
| Admin   | admin@example.com     | password |
| Support | support1@example.com  | password |
| User    | alice@example.com     | password |

## Quick Commands Reference

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seed
php artisan migrate:fresh --seed

# Start queue worker
php artisan queue:work

# View routes
php artisan route:list

# Access tinker
php artisan tinker
```

## Testing the System

### As a User (alice@example.com)
1. Log in
2. Create a new ticket
3. Add a comment
4. View your tickets

### As Support Staff (support1@example.com)
1. Log in
2. View all tickets
3. Assign ticket to yourself
4. Change ticket status
5. Add comments (public and internal)

### As Admin (admin@example.com)
1. Log in
2. Manage categories
3. View system statistics
4. Perform all support actions

## Email Testing (Development)

### Option 1: Log Driver (Default)
Emails saved to: `storage/logs/laravel.log`

### Option 2: Mailtrap
1. Sign up at https://mailtrap.io
2. Get credentials
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

## Common Issues

### Issue: "Class not found"
```bash
composer dump-autoload
```

### Issue: Permission denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Database connection failed
- Verify MySQL is running
- Check `.env` credentials
- Ensure database exists

### Issue: NPM errors
```bash
rm -rf node_modules package-lock.json
npm install
```

## File Structure Overview

```
app/
├── Models/           # User, Ticket, Category, Comment
├── Http/Controllers/ # TicketController, CategoryController
├── Events/           # TicketStatusChanged, CommentAdded
├── Listeners/        # Email notification listeners
└── Mail/             # Email templates

database/
├── migrations/       # Database schema
└── seeders/          # Sample data

resources/
└── views/
    ├── tickets/      # Ticket views
    ├── categories/   # Category views
    ├── dashboard/    # Dashboard views
    └── emails/       # Email templates

routes/
└── web.php          # Application routes
```

## Next Steps

1. ✅ Customize the branding and logo
2. ✅ Configure production email service
3. ✅ Set up proper queue workers
4. ✅ Configure backups
5. ✅ Deploy to production server

## Need Help?

- 📖 Read **README.md** for detailed information
- 🛠️ Check **INSTALLATION.md** for troubleshooting
- 📊 View **PROJECT_SUMMARY.md** for architecture
- 🗺️ See **uml/** folder for diagrams

---

**You're all set!** 🎉

Start by logging in as admin and exploring the system.