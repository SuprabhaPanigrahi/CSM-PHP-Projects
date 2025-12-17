# 🚀 PHASE 9: RUNNING & TESTING THE APPLICATION

**Complete Guide to Run, Test, and Deploy Your JWT API**

---

## 📋 What You'll Learn

In this final phase:

- ✅ **Run the application** locally
- ✅ **Test all API endpoints** systematically
- ✅ **Use Postman/Insomnia** for API testing
- ✅ **Write automated tests**
- ✅ **Debug common issues**
- ✅ **Prepare for deployment**

---

## 🏃 Step 1: Final Setup Check

### Verify Environment

```bash
# Check PHP version
php -v  # Should be 8.2+

# Check Composer
composer -v

# Check Laravel version
php artisan --version  # Should be 11.x
```

### Verify .env Configuration

```env
APP_NAME="JWT E-Commerce API"
APP_ENV=local
APP_KEY=base64:xxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jwt_ecommerce
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=xxxxx
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

### Install Dependencies

```bash
# If not already installed
composer install

# Update dependencies
composer update
```

---

## 🏃 Step 2: Database Setup

### Create Database

```bash
# MySQL
mysql -u root -p
CREATE DATABASE jwt_ecommerce;
EXIT;

# Or use SQLite
touch database/database.sqlite
```

### Run Migrations and Seeds

```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed
```

Expected output:
```
   INFO  Preparing database.

  Creating migration table ................................................ 5ms DONE

   INFO  Running migrations.

  xxxx_create_users_table ........................................... 10ms DONE
  xxxx_create_categories_table ....................................... 5ms DONE
  xxxx_create_products_table ........................................ 15ms DONE
  xxxx_create_orders_table ........................................... 8ms DONE
  xxxx_create_order_items_table ...................................... 6ms DONE
  xxxx_create_reviews_table .......................................... 7ms DONE

   INFO  Seeding database.

✅ Admin created: admin@example.com / password123
✅ Vendors created (7 total)
✅ Customers created (11 total)
✅ Categories created (8 total)
✅ Products created (60+ total)
✅ Orders created (20 total)
✅ Reviews created (50+ total)
```

---

## 🏃 Step 3: Start Development Server

### Option 1: Artisan Serve (Recommended for Development)

```bash
php artisan serve
```

Server will start at: `http://127.0.0.1:8000`

### Option 2: Custom Port

```bash
php artisan serve --port=8080
```

### Option 3: Specific Host

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Keep Server Running

Open a new terminal for testing while server runs in the background.

---

## 🧪 Step 4: Test API Endpoints (Using cURL)

### Test 1: Health Check

```bash
curl http://127.0.0.1:8000/api/health
```

Expected response:
```json
{
    "success": true,
    "message": "API is running",
    "version": "1.0.0",
    "timestamp": "2024-12-16 10:30:45"
}
```

### Test 2: User Registration

```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

Expected response:
```json
{
    "success": true,
    "message": "User registered successfully",
    "user": {
        "id": 20,
        "name": "Test User",
        "email": "test@example.com",
        "role": "customer"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### Test 3: User Login

```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

**Save the token** from response for next tests:
```bash
# Example token (yours will be different)
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
```

### Test 4: Get User Profile (Protected Route)

```bash
curl http://127.0.0.1:8000/api/auth/me \
  -H "Authorization: Bearer $TOKEN"
```

### Test 5: List Products (Public)

```bash
curl http://127.0.0.1:8000/api/products
```

### Test 6: Get Product Details

```bash
curl http://127.0.0.1:8000/api/products/1
```

### Test 7: Vendor Login & Create Product

```bash
# Login as vendor
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "vendor1@example.com",
    "password": "password123"
  }'

# Save vendor token
VENDOR_TOKEN="eyJ0eXAiOiJKV1Q..."

# Create product
curl -X POST http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer $VENDOR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Product",
    "description": "A great product",
    "price": 99.99,
    "stock_quantity": 50,
    "categories": [1, 2]
  }'
```

### Test 8: Customer Place Order

```bash
# Login as customer
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "customer1@example.com",
    "password": "password123"
  }'

# Save customer token
CUSTOMER_TOKEN="eyJ0eXAiOiJKV1Q..."

# Create order
curl -X POST http://127.0.0.1:8000/api/orders \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "items": [
      {"product_id": 1, "quantity": 2},
      {"product_id": 2, "quantity": 1}
    ]
  }'
```

### Test 9: Create Review

```bash
curl -X POST http://127.0.0.1:8000/api/products/1/reviews \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rating": 5,
    "comment": "Excellent product!"
  }'
```

### Test 10: Admin Statistics

```bash
# Login as admin
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'

# Save admin token
ADMIN_TOKEN="eyJ0eXAiOiJKV1Q..."

# Get statistics
curl http://127.0.0.1:8000/api/admin/statistics \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

---

## 📮 Step 5: Test with Postman

### Install Postman

Download from: https://www.postman.com/downloads/

### Import Collection

Create a new collection: `JWT E-Commerce API`

### Setup Environment Variables

Create environment with:
- `base_url`: `http://127.0.0.1:8000/api`
- `admin_token`: (will be set after login)
- `vendor_token`: (will be set after login)
- `customer_token`: (will be set after login)

### Example Request: Register User

1. **Method**: POST
2. **URL**: `{{base_url}}/auth/register`
3. **Headers**: 
   - `Content-Type`: `application/json`
4. **Body** (raw JSON):
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Example Request: Login

1. **Method**: POST
2. **URL**: `{{base_url}}/auth/login`
3. **Body**:
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```
4. **Tests** (save token):
```javascript
var jsonData = pm.response.json();
pm.environment.set("admin_token", jsonData.access_token);
```

### Example Request: Get Profile

1. **Method**: GET
2. **URL**: `{{base_url}}/auth/me`
3. **Headers**:
   - `Authorization`: `Bearer {{admin_token}}`

### Complete Postman Collection Example

Save this as `JWT-API.postman_collection.json`:

```json
{
  "info": {
    "name": "JWT E-Commerce API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Register",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "{{base_url}}/auth/register",
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"Test User\",\n  \"email\": \"test@example.com\",\n  \"password\": \"password123\",\n  \"password_confirmation\": \"password123\"\n}"
            }
          }
        },
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "{{base_url}}/auth/login",
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"admin@example.com\",\n  \"password\": \"password123\"\n}"
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": ["pm.environment.set('admin_token', pm.response.json().access_token);"]
              }
            }
          ]
        }
      ]
    }
  ]
}
```

---

## 🧪 Step 6: Automated Testing

### Create Feature Tests

```bash
php artisan make:test AuthenticationTest
php artisan make:test ProductTest
php artisan make:test OrderTest
```

### Example: AuthenticationTest

Edit `tests/Feature/AuthenticationTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'user',
                     'access_token',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'access_token',
                 ]);
    }

    public function test_authenticated_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = auth()->tokenById($user->id);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'user' => [
                         'email' => $user->email,
                     ],
                 ]);
    }
}
```

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=AuthenticationTest

# Run with coverage (requires xdebug)
php artisan test --coverage
```

Expected output:
```
   PASS  Tests\Feature\AuthenticationTest
  ✓ user can register
  ✓ user can login
  ✓ authenticated user can get profile

  Tests:  3 passed
  Time:   0.45s
```

---

## 🐛 Step 7: Common Issues & Solutions

### Issue 1: "Token not provided"

**Cause**: Missing Authorization header

**Solution**:
```bash
# Ensure header format is correct
-H "Authorization: Bearer YOUR_TOKEN"
# NOT: "Authorization: YOUR_TOKEN"
```

### Issue 2: "Class 'JWTAuth' not found"

**Solution**:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue 3: Database Connection Error

**Solution**:
```bash
# Check .env settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=jwt_ecommerce

# Test connection
php artisan db:show
```

### Issue 4: "Route not found"

**Solution**:
```bash
# Clear route cache
php artisan route:clear

# List all routes
php artisan route:list
```

### Issue 5: CORS Issues (Frontend)

**Solution**: Install Laravel CORS package
```bash
composer require fruitcake/laravel-cors

# config/cors.php is auto-created
```

Edit `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['http://localhost:3000'], // Your frontend URL
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

---

## 📊 Step 8: Monitor & Debug

### Enable Query Logging

Add to `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\DB;

public function boot(): void
{
    if (config('app.debug')) {
        DB::listen(function ($query) {
            logger()->info('SQL Query', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ]);
        });
    }
}
```

### Use Telescope (Optional)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Access at: `http://127.0.0.1:8000/telescope`

---

## 🚀 Step 9: Deployment Preparation

### Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Update .env for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use strong secret
JWT_SECRET=your-very-strong-secret-key

# Reduce token lifetime for security
JWT_TTL=30
```

### Security Checklist

- ✅ Set `APP_DEBUG=false`
- ✅ Use HTTPS
- ✅ Set strong `APP_KEY` and `JWT_SECRET`
- ✅ Configure CORS properly
- ✅ Enable rate limiting
- ✅ Use environment-specific configs
- ✅ Disable unnecessary routes
- ✅ Review file permissions

---

## 📦 Step 10: Complete Test Script (PowerShell)

Save as `test-api.ps1`:

```powershell
$baseUrl = "http://127.0.0.1:8000/api"
$passed = 0
$failed = 0

Write-Host "`n=== JWT API Test Suite ===" -ForegroundColor Cyan

# Test 1: Health Check
Write-Host "`nTest 1: Health Check" -ForegroundColor Yellow
try {
    $response = Invoke-RestMethod "$baseUrl/health"
    if ($response.success) {
        Write-Host "  PASS" -ForegroundColor Green
        $passed++
    }
} catch {
    Write-Host "  FAIL: $($_.Exception.Message)" -ForegroundColor Red
    $failed++
}

# Test 2: Register
Write-Host "`nTest 2: User Registration" -ForegroundColor Yellow
try {
    $body = @{
        name = "Test User"
        email = "test$(Get-Random)@example.com"
        password = "password123"
        password_confirmation = "password123"
    } | ConvertTo-Json

    $response = Invoke-RestMethod "$baseUrl/auth/register" -Method POST -Body $body -ContentType "application/json"
    if ($response.success) {
        Write-Host "  PASS - User ID: $($response.user.id)" -ForegroundColor Green
        $passed++
    }
} catch {
    Write-Host "  FAIL: $($_.Exception.Message)" -ForegroundColor Red
    $failed++
}

# Test 3: Login
Write-Host "`nTest 3: Admin Login" -ForegroundColor Yellow
try {
    $body = @{
        email = "admin@example.com"
        password = "password123"
    } | ConvertTo-Json

    $response = Invoke-RestMethod "$baseUrl/auth/login" -Method POST -Body $body -ContentType "application/json"
    $token = $response.access_token
    if ($token) {
        Write-Host "  PASS - Token received" -ForegroundColor Green
        $passed++
    }
} catch {
    Write-Host "  FAIL: $($_.Exception.Message)" -ForegroundColor Red
    $failed++
}

# Summary
Write-Host "`n=== Test Summary ===" -ForegroundColor Cyan
Write-Host "Passed: $passed" -ForegroundColor Green
Write-Host "Failed: $failed" -ForegroundColor Red
$rate = [math]::Round(($passed / 3) * 100, 1)
Write-Host "Success Rate: $rate%" -ForegroundColor $(if ($rate -eq 100) { "Green" } else { "Yellow" })
```

Run with:
```powershell
.\test-api.ps1
```

---

## ✅ Final Checklist

Before considering the project complete:

- ✅ All migrations run successfully
- ✅ Database seeded with test data
- ✅ Development server running
- ✅ All API endpoints tested
- ✅ JWT authentication working
- ✅ Role-based authorization working
- ✅ Automated tests passing
- ✅ No console errors
- ✅ Documentation complete
- ✅ Ready for deployment

---

## 🎉 Congratulations!

You've successfully built a complete **JWT Authentication & Authorization API** with:

- ✅ Laravel 11 framework
- ✅ JWT authentication
- ✅ Role-based access control
- ✅ Repository pattern
- ✅ Service layer
- ✅ Clean architecture
- ✅ RESTful API design
- ✅ Complete CRUD operations
- ✅ Transaction handling
- ✅ Production-ready code

---

## 📚 Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs/11.x)
- [JWT Auth Package](https://github.com/PHP-Open-Source-Saver/jwt-auth)
- [Postman Learning](https://learning.postman.com/)

### Next Steps
1. Build a frontend (React, Vue, Angular)
2. Add more features (payments, notifications)
3. Implement advanced caching
4. Add API versioning
5. Deploy to production

---

**Phase 9 Completed:** ✅  
**Project Completed:** ✅  
**Estimated Total Time:** 8-12 hours  
**Difficulty Level:** Intermediate to Advanced  

---

*Last Updated: December 16, 2024*  
*Tutorial Complete - Happy Coding! 🚀*
