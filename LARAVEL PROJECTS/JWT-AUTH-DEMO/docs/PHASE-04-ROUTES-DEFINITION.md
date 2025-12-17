# 🛣️ PHASE 4: ROUTES DEFINITION

**Setting Up RESTful API Routes for JWT Authentication**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **Authentication Routes** (register, login, logout, refresh)
- ✅ **Product Routes** (CRUD with public/protected access)
- ✅ **Order Routes** (customer orders with authorization)
- ✅ **Review Routes** (product reviews)
- ✅ **Admin Routes** (user management, statistics)
- ✅ **Route Groups** with middleware
- ✅ **Route Prefixes** and naming conventions

---

## 🎯 API Route Structure Overview

```
/api
├── /health                         (GET - Public)
├── /auth
│   ├── /register                   (POST - Public)
│   ├── /login                      (POST - Public)
│   ├── /me                         (GET - Protected)
│   ├── /logout                     (POST - Protected)
│   └── /refresh                    (POST - Protected)
├── /products
│   ├── /                           (GET - Public, POST - Vendor/Admin)
│   ├── /{id}                       (GET - Public, PUT/DELETE - Owner/Admin)
│   └── /vendor/{vendorId}          (GET - Public)
├── /orders
│   ├── /                           (GET, POST - Customer)
│   ├── /{id}                       (GET - Owner/Admin)
│   ├── /{id}/status                (PUT - Vendor/Admin)
│   └── /{id}/cancel                (DELETE - Customer/Admin)
├── /products/{id}/reviews
│   ├── /                           (GET - Public, POST - Customer)
│   └── /{reviewId}                 (PUT, DELETE - Owner/Admin)
└── /admin
    ├── /users                      (GET - Admin)
    └── /statistics                 (GET - Admin)
```

---

## 📝 Step 1: Clean Up Default Routes

### Remove Unwanted Routes

Edit `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

// Keep only this for web routes (optional)
Route::get('/', function () {
    return response()->json([
        'message' => 'JWT E-Commerce API',
        'version' => '1.0.0',
        'documentation' => '/api/documentation',
    ]);
});
```

---

## 📝 Step 2: Define API Routes

Edit `routes/api.php`:

```php
<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Health Check Route (Public)
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
})->name('api.health');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth.')->group(function () {
    // Public routes (no authentication required)
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protected routes (require JWT authentication)
    Route::middleware(['jwt.auth', 'status'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    });
});

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::prefix('products')->name('products.')->group(function () {
    // Public routes
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/vendor/{vendorId}', [ProductController::class, 'vendorProducts'])->name('vendor');

    // Protected routes (require authentication)
    Route::middleware(['jwt.auth', 'status'])->group(function () {
        // Vendor and Admin can create products
        Route::post('/', [ProductController::class, 'store'])
            ->middleware('role:vendor,admin')
            ->name('store');

        // Owner (vendor who created) or Admin can update/delete
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Product Reviews (nested resource)
    Route::prefix('/{productId}/reviews')->name('reviews.')->group(function () {
        // Public route
        Route::get('/', [ReviewController::class, 'index'])->name('index');

        // Protected routes
        Route::middleware(['jwt.auth', 'status'])->group(function () {
            Route::post('/', [ReviewController::class, 'store'])
                ->middleware('role:customer')
                ->name('store');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Review Routes
|--------------------------------------------------------------------------
*/

Route::prefix('reviews')->name('reviews.')->middleware(['jwt.auth', 'status'])->group(function () {
    Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
    Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Order Routes
|--------------------------------------------------------------------------
*/

Route::prefix('orders')->name('orders.')->middleware(['jwt.auth', 'status'])->group(function () {
    // Customer routes
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/', [OrderController::class, 'store'])
        ->middleware('role:customer')
        ->name('store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');

    // Vendor and Admin routes
    Route::put('/{id}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:vendor,admin')
        ->name('update-status');

    // Customer and Admin can cancel orders
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('cancel');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['jwt.auth', 'status', 'role:admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'getUsers'])->name('users');
    Route::get('/statistics', [AdminController::class, 'getStatistics'])->name('statistics');
});
```

---

## 📝 Step 3: Understanding Route Components

### Route Prefixes

```php
Route::prefix('auth')->group(function () {
    // Routes here will be: /api/auth/*
});
```

### Route Names

```php
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
// Can be used as: route('auth.login')
```

### Route Middleware

```php
Route::middleware(['jwt.auth', 'status'])->group(function () {
    // All routes here require JWT authentication and active status
});
```

### Role-Based Middleware

```php
Route::post('/products', [ProductController::class, 'store'])
    ->middleware('role:vendor,admin');
// Only vendors and admins can access
```

---

## 📝 Step 4: Route Groups Explained

### Public Routes (No Authentication)

```php
// Anyone can access
Route::get('/products', [ProductController::class, 'index']);
Route::post('/auth/login', [AuthController::class, 'login']);
```

### Protected Routes (JWT Required)

```php
Route::middleware(['jwt.auth'])->group(function () {
    // Requires valid JWT token
    Route::get('/auth/me', [AuthController::class, 'me']);
});
```

### Role-Protected Routes

```php
Route::middleware(['jwt.auth', 'role:admin'])->group(function () {
    // Only admins with valid JWT
    Route::get('/admin/users', [AdminController::class, 'getUsers']);
});
```

### Status Check Middleware

```php
Route::middleware(['jwt.auth', 'status'])->group(function () {
    // User must be active (not suspended/inactive)
});
```

---

## 📝 Step 5: List All Routes

### View All Registered Routes

```bash
php artisan route:list
```

Expected output:
```
  GET|HEAD   /api/health ........................... api.health › Closure
  POST       /api/auth/register ............. auth.register › Auth\AuthController@register
  POST       /api/auth/login ................. auth.login › Auth\AuthController@login
  GET|HEAD   /api/auth/me .................... auth.me › Auth\AuthController@me
  POST       /api/auth/logout ................ auth.logout › Auth\AuthController@logout
  POST       /api/auth/refresh ............... auth.refresh › Auth\AuthController@refresh
  GET|HEAD   /api/products ................... products.index › ProductController@index
  POST       /api/products ................... products.store › ProductController@store
  GET|HEAD   /api/products/{id} .............. products.show › ProductController@show
  PUT        /api/products/{id} .............. products.update › ProductController@update
  DELETE     /api/products/{id} .............. products.destroy › ProductController@destroy
  GET|HEAD   /api/products/vendor/{vendorId}  products.vendor › ProductController@vendorProducts
  GET|HEAD   /api/products/{productId}/reviews  products.reviews.index › ReviewController@index
  POST       /api/products/{productId}/reviews  products.reviews.store › ReviewController@store
  PUT        /api/reviews/{id} ............... reviews.update › ReviewController@update
  DELETE     /api/reviews/{id} ............... reviews.destroy › ReviewController@destroy
  GET|HEAD   /api/orders ..................... orders.index › OrderController@index
  POST       /api/orders ..................... orders.store › OrderController@store
  GET|HEAD   /api/orders/{id} ................ orders.show › OrderController@show
  PUT        /api/orders/{id}/status ......... orders.update-status › OrderController@updateStatus
  DELETE     /api/orders/{id} ................ orders.cancel › OrderController@destroy
  GET|HEAD   /api/admin/users ................ admin.users › AdminController@getUsers
  GET|HEAD   /api/admin/statistics ........... admin.statistics › AdminController@getStatistics
```

### Filter Routes by Method

```bash
# Only GET routes
php artisan route:list --method=GET

# Only POST routes
php artisan route:list --method=POST
```

### Filter Routes by Name

```bash
# Routes starting with 'auth'
php artisan route:list --name=auth
```

---

## 📝 Step 6: Route Testing with cURL

### Test Health Check

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

### Test Public Product Route

```bash
curl http://127.0.0.1:8000/api/products
```

---

## 📝 Step 7: Route Documentation

### Create API Documentation Helper

Create `routes/api-docs.php`:

```php
<?php

return [
    'authentication' => [
        'POST /api/auth/register' => 'Register new user',
        'POST /api/auth/login' => 'Login user',
        'GET /api/auth/me' => 'Get authenticated user',
        'POST /api/auth/logout' => 'Logout user',
        'POST /api/auth/refresh' => 'Refresh JWT token',
    ],
    'products' => [
        'GET /api/products' => 'List all products',
        'POST /api/products' => 'Create product (Vendor/Admin)',
        'GET /api/products/{id}' => 'Get product details',
        'PUT /api/products/{id}' => 'Update product (Owner/Admin)',
        'DELETE /api/products/{id}' => 'Delete product (Owner/Admin)',
        'GET /api/products/vendor/{vendorId}' => 'Get vendor products',
    ],
    'reviews' => [
        'GET /api/products/{productId}/reviews' => 'Get product reviews',
        'POST /api/products/{productId}/reviews' => 'Create review (Customer)',
        'PUT /api/reviews/{id}' => 'Update review (Owner/Admin)',
        'DELETE /api/reviews/{id}' => 'Delete review (Owner/Admin)',
    ],
    'orders' => [
        'GET /api/orders' => 'List user orders',
        'POST /api/orders' => 'Create order (Customer)',
        'GET /api/orders/{id}' => 'Get order details',
        'PUT /api/orders/{id}/status' => 'Update order status (Vendor/Admin)',
        'DELETE /api/orders/{id}' => 'Cancel order (Customer/Admin)',
    ],
    'admin' => [
        'GET /api/admin/users' => 'List all users (Admin)',
        'GET /api/admin/statistics' => 'Get statistics (Admin)',
    ],
];
```

### Add Documentation Endpoint

Add to `routes/api.php`:

```php
Route::get('/documentation', function () {
    $docs = require __DIR__ . '/api-docs.php';
    
    return response()->json([
        'success' => true,
        'message' => 'API Documentation',
        'endpoints' => $docs,
    ]);
})->name('api.documentation');
```

---

## 📝 Step 8: Route Model Binding (Optional Enhancement)

Instead of passing IDs, you can use route model binding to automatically fetch models.

### Implicit Binding

Edit `routes/api.php`:

```php
// Before (manual ID):
Route::get('/products/{id}', [ProductController::class, 'show']);

// After (automatic model injection):
Route::get('/products/{product}', [ProductController::class, 'show']);
```

Then in controller:

```php
// Before:
public function show($id) {
    $product = Product::findOrFail($id);
}

// After:
public function show(Product $product) {
    // $product is already loaded
}
```

---

## 📊 Complete Route Summary

### Public Routes (No Authentication)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/health` | Health check |
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login user |
| GET | `/api/products` | List products |
| GET | `/api/products/{id}` | Get product |
| GET | `/api/products/vendor/{vendorId}` | Vendor products |
| GET | `/api/products/{productId}/reviews` | Product reviews |

### Protected Routes (JWT Required)

| Method | Endpoint | Roles | Description |
|--------|----------|-------|-------------|
| GET | `/api/auth/me` | All | Get profile |
| POST | `/api/auth/logout` | All | Logout |
| POST | `/api/auth/refresh` | All | Refresh token |
| POST | `/api/products` | Vendor, Admin | Create product |
| PUT | `/api/products/{id}` | Owner, Admin | Update product |
| DELETE | `/api/products/{id}` | Owner, Admin | Delete product |
| POST | `/api/products/{productId}/reviews` | Customer | Create review |
| PUT | `/api/reviews/{id}` | Owner, Admin | Update review |
| DELETE | `/api/reviews/{id}` | Owner, Admin | Delete review |
| GET | `/api/orders` | All | List orders |
| POST | `/api/orders` | Customer | Create order |
| GET | `/api/orders/{id}` | Owner, Admin | Get order |
| PUT | `/api/orders/{id}/status` | Vendor, Admin | Update status |
| DELETE | `/api/orders/{id}` | Customer, Admin | Cancel order |
| GET | `/api/admin/users` | Admin | List users |
| GET | `/api/admin/statistics` | Admin | Get stats |

---

## ✅ Success Checklist

Before moving to Phase 5:

- ✅ API routes defined in `routes/api.php`
- ✅ Route groups created for organization
- ✅ Middleware applied correctly
- ✅ Route names defined
- ✅ Route prefixes configured
- ✅ Role-based access implemented
- ✅ Routes listed successfully with `route:list`
- ✅ Health check endpoint working

---

## 🎯 What's Next?

**Next Phase:** [PHASE-05-REPOSITORY-LAYER.md](./PHASE-05-REPOSITORY-LAYER.md)

In Phase 5, you will:
- Create repository interfaces
- Implement repository classes
- Set up dependency injection
- Abstract data access layer

---

**Phase 4 Completed:** ✅  
**Estimated Time:** 30-45 minutes  
**Difficulty Level:** Beginner-Intermediate  

---

*Last Updated: December 16, 2024*
