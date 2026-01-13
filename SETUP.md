# IT Support Ticket System - Setup Instructions

## Quick Setup (5 minutes)

### Step 1: Install Dependencies

```bash
composer install
```

### Step 2: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure Database

Edit `.env` file and set your database credentials:

```
DB_DATABASE=it_support_ticket_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL:

```sql
CREATE DATABASE it_support_ticket_system;
```

### Step 4: Run Migrations and Seed Data

```bash
# Create tables
php artisan migrate

# Add sample data (includes test users and tickets)
php artisan db:seed
```

### Step 5: Start the Application

```bash
php artisan serve
```

Visit: http://localhost:8000

## Test Login Credentials

After running `php artisan db:seed`, you can login with:

**Admin Account:**
- Email: admin@example.com
- Password: password

**Support Staff:**
- Email: support1@example.com
- Password: password

**Regular User:**
- Email: alice@example.com
- Password: password

## Common Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Fresh start (reset database)
php artisan migrate:fresh --seed

# Start queue worker (for emails)
php artisan queue:work
```

## Troubleshooting

**Problem: "Class not found"**
```bash
composer dump-autoload
```

**Problem: Permission errors**
```bash
chmod -R 775 storage bootstrap/cache
```

**Problem: Database connection failed**
- Check MySQL is running
- Verify credentials in `.env`
- Ensure database exists

## Done!

You should now have a working IT Support Ticket System.