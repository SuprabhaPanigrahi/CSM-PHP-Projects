# 🛡️ PHASE 8: MIDDLEWARE, GUARDS & POLICIES

**Implementing Authentication and Authorization Layer**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **JwtMiddleware** - JWT token verification
- ✅ **CheckRole** - Role-based authorization
- ✅ **CheckStatus** - User status verification
- ✅ **Authentication Guards** - JWT guard configuration
- ✅ **Middleware Registration** - Global and route middleware

---

## 🎯 Understanding Middleware

Middleware acts as a **filter** for HTTP requests:

```
HTTP Request → Middleware Chain → Controller → Response
                     ↓
              [Authentication]
              [Authorization]
              [Status Check]
```

---

## 📝 Step 1: Create JwtMiddleware

Create `app/Http/Middleware/JwtMiddleware.php`:

```bash
php artisan make:middleware JwtMiddleware
```

Edit the file:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Attempt to parse and authenticate the token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired',
                'error' => 'token_expired',
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid',
                'error' => 'token_invalid',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided',
                'error' => 'token_absent',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization error',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
```

**What it does:**
- Checks if JWT token exists in request
- Validates token signature
- Checks if token is expired
- Authenticates user from token
- Returns 401 if any check fails

---

## 📝 Step 2: Create CheckRole Middleware

Create `app/Http/Middleware/CheckRole.php`:

```bash
php artisan make:middleware CheckRole
```

Edit the file:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        try {
            // Get authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Check if user has one of the required roles
            if (!in_array($user->role, $roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Required role(s): ' . implode(', ', $roles),
                    'user_role' => $user->role,
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization failed',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
```

**What it does:**
- Gets authenticated user
- Checks if user's role matches required roles
- Returns 403 Forbidden if role doesn't match

**Usage:**
```php
Route::get('/admin/users', [AdminController::class, 'index'])
    ->middleware('role:admin');

Route::post('/products', [ProductController::class, 'store'])
    ->middleware('role:vendor,admin'); // Multiple roles
```

---

## 📝 Step 3: Create CheckStatus Middleware

Create `app/Http/Middleware/CheckStatus.php`:

```bash
php artisan make:middleware CheckStatus
```

Edit the file:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Get authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Check if user account is active
            if ($user->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is ' . $user->status . '. Please contact support.',
                    'status' => $user->status,
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization failed',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
```

**What it does:**
- Checks if user account status is 'active'
- Prevents suspended/inactive users from accessing protected routes
- Returns 403 if account is not active

---

## 📝 Step 4: Register Middleware

Edit `bootstrap/app.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'jwt.auth' => \App\Http\Middleware\JwtMiddleware::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'status' => \App\Http\Middleware\CheckStatus::class,
        ]);
    })
    ->withProviders([
        \App\Providers\RepositoryServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

---

## 📝 Step 5: Configure Authentication Guards

### Update `config/auth.php`

Edit the `guards` section:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'jwt',  // Changed from 'sanctum' to 'jwt'
        'provider' => 'users',
        'hash' => false,
    ],
],
```

### Update JWT Configuration

Edit `config/jwt.php` (if not exists, publish it):

```bash
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
```

Key settings in `config/jwt.php`:

```php
'secret' => env('JWT_SECRET'),

'ttl' => env('JWT_TTL', 60), // Token lifetime in minutes

'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Refresh token lifetime (14 days)

'algo' => env('JWT_ALGO', 'HS256'), // Algorithm

'required_claims' => [
    'iss',
    'iat',
    'exp',
    'nbf',
    'sub',
    'jti',
],

'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),
```

---

## 📝 Step 6: Create Policies (Optional Enhancement)

### Create ProductPolicy

```bash
php artisan make:policy ProductPolicy --model=Product
```

Edit `app/Policies/ProductPolicy.php`:

```php
<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine if user can create products.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['vendor', 'admin']);
    }

    /**
     * Determine if user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        // Admin can update any product
        if ($user->role === 'admin') {
            return true;
        }

        // Vendor can only update their own products
        return $user->role === 'vendor' && $product->vendor_id === $user->id;
    }

    /**
     * Determine if user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        // Admin can delete any product
        if ($user->role === 'admin') {
            return true;
        }

        // Vendor can only delete their own products
        return $user->role === 'vendor' && $product->vendor_id === $user->id;
    }
}
```

### Register Policy

Edit `app/Providers/AuthServiceProvider.php` (create if doesn't exist):

```bash
php artisan make:provider AuthServiceProvider
```

```php
<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
```

Then register in `bootstrap/app.php`:

```php
->withProviders([
    \App\Providers\RepositoryServiceProvider::class,
    \App\Providers\AuthServiceProvider::class,
])
```

### Use Policy in Controller

```php
// In ProductController
public function update(Request $request, int $id): JsonResponse
{
    $product = $this->productService->getProductById($id);

    // Check policy
    if (!auth()->user()->can('update', $product)) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 403);
    }

    // Continue with update...
}
```

---

## 📝 Step 7: Middleware Usage Examples

### Single Middleware

```php
// Only JWT authentication
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('jwt.auth');
```

### Multiple Middleware (Chain)

```php
// JWT + Status check
Route::get('/orders', [OrderController::class, 'index'])
    ->middleware(['jwt.auth', 'status']);
```

### Middleware with Parameters

```php
// Admin only
Route::get('/admin/users', [AdminController::class, 'index'])
    ->middleware(['jwt.auth', 'status', 'role:admin']);

// Multiple roles
Route::post('/products', [ProductController::class, 'store'])
    ->middleware(['jwt.auth', 'status', 'role:vendor,admin']);
```

### Middleware Groups

```php
// Group routes with common middleware
Route::middleware(['jwt.auth', 'status'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/orders', [OrderController::class, 'index']);
});
```

---

## 📝 Step 8: Test Middleware

### Test JWT Authentication

```bash
# Without token (should fail)
curl http://127.0.0.1:8000/api/auth/me

# Expected response:
# {
#   "success": false,
#   "message": "Token not provided"
# }

# With token (should succeed)
curl http://127.0.0.1:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Test Role-Based Authorization

```bash
# Customer trying to create product (should fail)
curl -X POST http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer CUSTOMER_TOKEN" \
  -H "Content-Type: application/json"

# Expected response:
# {
#   "success": false,
#   "message": "Unauthorized. Required role(s): vendor, admin"
# }

# Vendor creating product (should succeed)
curl -X POST http://127.0.0.1:8000/api/products \
  -H "Authorization: Bearer VENDOR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name": "Test Product", "price": 99.99, ...}'
```

### Test Status Check

```bash
# If user is suspended
curl http://127.0.0.1:8000/api/orders \
  -H "Authorization: Bearer SUSPENDED_USER_TOKEN"

# Expected response:
# {
#   "success": false,
#   "message": "Account is suspended. Please contact support."
# }
```

---

## 🔐 Security Best Practices

### 1. Always Use HTTPS in Production

```env
# .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Set Appropriate Token TTL

```env
JWT_TTL=60 # 1 hour
JWT_REFRESH_TTL=20160 # 14 days
```

### 3. Enable Token Blacklist

```env
JWT_BLACKLIST_ENABLED=true
```

### 4. Use Strong Secret

```bash
php artisan jwt:secret
```

### 5. Implement Rate Limiting

Edit `app/Http/Kernel.php` or `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->throttleApi('60,1'); // 60 requests per minute
})
```

---

## ✅ Success Checklist

Before moving to Phase 9:

- ✅ JwtMiddleware created and registered
- ✅ CheckRole middleware created
- ✅ CheckStatus middleware created
- ✅ Middleware aliases configured
- ✅ Authentication guards configured
- ✅ JWT settings configured
- ✅ Policies created (optional)
- ✅ Middleware tested

---

## 🎯 What's Next?

**Next Phase:** [PHASE-09-RUNNING-TESTING.md](./PHASE-09-RUNNING-TESTING.md)

In Phase 9, you will:
- Run the complete application
- Test all API endpoints
- Use Postman/Insomnia
- Run automated tests
- Deploy preparation

---

**Phase 8 Completed:** ✅  
**Estimated Time:** 45-60 minutes  
**Difficulty Level:** Intermediate-Advanced  

---

*Last Updated: December 16, 2024*
