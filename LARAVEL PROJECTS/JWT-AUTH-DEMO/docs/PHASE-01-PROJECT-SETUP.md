# 🚀 PHASE 1: PROJECT SETUP & INSTALLATION

**Complete Step-by-Step Guide for New Developers**

---

## 📋 Prerequisites

Before starting, ensure you have the following installed:

- **PHP** >= 8.2 (Recommended: 8.3)
- **Composer** >= 2.0
- **MySQL** >= 8.0 or **PostgreSQL** >= 13 or **SQLite**
- **Git**
- **Postman** or **Insomnia** (for API testing)

### Check Your PHP Version

```bash
php -v
```

Expected output:
```
PHP 8.3.x (cli) (built: ...)
```

### Check Composer Version

```bash
composer -v
```

---

## 🎯 Step 1: Create New Laravel 11 Project

### Option A: Using Composer (Recommended)

```bash
composer create-project laravel/laravel jwt-auth
```

### Option B: Using Laravel Installer

```bash
# Install Laravel installer globally (one-time)
composer global require laravel/installer

# Create new project
laravel new jwt-auth
```

### Navigate to Project Directory

```bash
cd jwt-auth
```

---

## 🔧 Step 2: Configure Environment

### Create Environment File

The `.env` file should already exist. If not:

```bash
# Windows
copy .env.example .env

# macOS/Linux
cp .env.example .env
```

### Edit `.env` File

Open `.env` in your editor and configure:

```env
APP_NAME="JWT E-Commerce API"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jwt_ecommerce
DB_USERNAME=root
DB_PASSWORD=your_password

# For SQLite (Alternative - Simpler for Development)
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite

# Session & Cache
SESSION_DRIVER=file
CACHE_STORE=file

# JWT Configuration (we'll add this later)
JWT_SECRET=
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

### Generate Application Key

```bash
php artisan key:generate
```

This will populate `APP_KEY` in `.env`

---

## 🗄️ Step 3: Create Database

### For MySQL:

```bash
# Open MySQL client
mysql -u root -p

# Create database
CREATE DATABASE jwt_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Verify database created
SHOW DATABASES;

# Exit
EXIT;
```

### For SQLite (Easier Option):

```bash
# Create database directory if needed
mkdir -p database

# Create empty database file
# Windows PowerShell
New-Item -Path database\database.sqlite -ItemType File

# macOS/Linux
touch database/database.sqlite
```

Then update `.env`:
```env
DB_CONNECTION=sqlite
# Comment out other DB_ lines or remove them
```

---

## 📦 Step 4: Install JWT Authentication Package

### Install php-open-source-saver/jwt-auth

```bash
composer require php-open-source-saver/jwt-auth
```

**Why this package?**
- Modern fork of `tymon/jwt-auth`
- Compatible with Laravel 11
- Actively maintained
- Supports PHP 8.x

### Publish JWT Configuration

```bash
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
```

This creates: `config/jwt.php`

### Generate JWT Secret Key

```bash
php artisan jwt:secret
```

This adds `JWT_SECRET` to your `.env` file.

---

## 🔍 Step 5: Verify Installation

### Check Laravel Version

```bash
php artisan --version
```

Expected output:
```
Laravel Framework 11.x.x
```

### List Installed Packages

```bash
composer show
```

You should see:
- `laravel/framework 11.x`
- `php-open-source-saver/jwt-auth 2.x`

### Test Database Connection

```bash
php artisan migrate:status
```

If connection is successful, you'll see migration status table (even if empty).

---

## 📁 Step 6: Understand Project Structure

```
jwt-auth/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # We'll create controllers here
│   │   └── Middleware/      # We'll create middleware here
│   ├── Models/              # Eloquent models
│   ├── Providers/           # Service providers
│   ├── Repositories/        # We'll create repositories here
│   └── Services/            # We'll create services here
│
├── bootstrap/
│   └── app.php              # Application bootstrap
│
├── config/
│   ├── app.php              # App configuration
│   ├── auth.php             # Auth configuration
│   ├── database.php         # Database configuration
│   └── jwt.php              # JWT configuration (after publish)
│
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
│
├── routes/
│   ├── api.php              # API routes (we'll use this)
│   └── web.php              # Web routes
│
├── .env                     # Environment configuration
├── composer.json            # PHP dependencies
└── artisan                  # Artisan CLI
```

---

## ⚙️ Step 7: Configure JWT in User Model

### Update `config/auth.php`

Open `config/auth.php` and modify the `api` guard:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'jwt',      // Changed from 'sanctum' to 'jwt'
        'provider' => 'users',
    ],
],
```

### Update User Model

Open `app/Models/User.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

**Key Changes:**
1. Implements `JWTSubject` interface
2. Added `getJWTIdentifier()` method
3. Added `getJWTCustomClaims()` method

---

## 🧪 Step 8: Test Basic Setup

### Run Development Server

```bash
php artisan serve
```

Server starts at: `http://127.0.0.1:8000`

### Test in Browser

Open browser and visit:
```
http://127.0.0.1:8000
```

You should see Laravel welcome page.

### Create Simple Health Check Route (Optional)

Edit `routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'JWT API is running',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
});
```

### Test API Endpoint

```bash
curl http://127.0.0.1:8000/api/health
```

Expected response:
```json
{
    "success": true,
    "message": "JWT API is running",
    "version": "1.0.0",
    "timestamp": "2024-12-16 10:30:45"
}
```

---

## 📝 Step 9: Version Control Setup

### Initialize Git Repository

```bash
git init
```

### Check `.gitignore`

Laravel includes a `.gitignore` file. Verify it contains:

```gitignore
/.phpunit.cache
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
```

### Make Initial Commit

```bash
git add .
git commit -m "Initial setup: Laravel 11 with JWT authentication"
```

### Create GitHub Repository (Optional)

```bash
# Create repo on GitHub, then:
git remote add origin https://github.com/yourusername/jwt-auth.git
git branch -M main
git push -u origin main
```

---

## ✅ Step 10: Verify Everything Works

### Run Artisan Commands

```bash
# Check if artisan works
php artisan list

# Check routes
php artisan route:list

# Check database connection
php artisan db:show
```

### Check Installed Packages

```bash
composer show php-open-source-saver/jwt-auth
```

Expected output:
```
name     : php-open-source-saver/jwt-auth
descrip. : JSON Web Token Authentication for Laravel
versions : * 2.x.x
```

---

## 🎉 Success Checklist

Before moving to Phase 2, ensure:

- ✅ Laravel 11 project created
- ✅ `.env` file configured
- ✅ Database created and connected
- ✅ JWT package installed
- ✅ JWT secret generated
- ✅ User model updated with JWTSubject
- ✅ `config/auth.php` configured for JWT
- ✅ Development server runs successfully
- ✅ Health check endpoint works
- ✅ Git repository initialized

---

## 🐛 Common Issues & Solutions

### Issue 1: "Class 'JWTAuth' not found"

**Solution:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue 2: Database Connection Failed

**Solution:**
- Check MySQL/PostgreSQL is running
- Verify database exists
- Check username/password in `.env`
- For SQLite, ensure file exists and is writable

### Issue 3: "No application encryption key has been specified"

**Solution:**
```bash
php artisan key:generate
```

### Issue 4: Port 8000 Already in Use

**Solution:**
```bash
# Use different port
php artisan serve --port=8001
```

---

## 📚 Additional Resources

- **Laravel Documentation:** https://laravel.com/docs/11.x
- **JWT Auth Package:** https://github.com/PHP-Open-Source-Saver/jwt-auth
- **Composer:** https://getcomposer.org/doc/
- **PHP Documentation:** https://www.php.net/docs.php

---

## 🎯 What's Next?

You've successfully completed Phase 1! 

**Next Phase:** [PHASE-02-DATABASE-MIGRATIONS-MODELS.md](./PHASE-02-DATABASE-MIGRATIONS-MODELS.md)

In Phase 2, you will:
- Create database migrations
- Create Eloquent models
- Define model relationships
- Set up database schema

---

**Phase 1 Completed:** ✅  
**Estimated Time:** 30-45 minutes  
**Difficulty Level:** Beginner  

---

*Last Updated: December 16, 2024*  
*Laravel Version: 11.x*  
*PHP Version: 8.3*
