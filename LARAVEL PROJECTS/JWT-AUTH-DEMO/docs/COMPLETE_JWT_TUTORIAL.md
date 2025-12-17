# Complete JWT Authentication & Authorization Tutorial - Laravel 11 API

## 📚 Tutorial by: Senior Laravel Architect (25+ Years Experience)

---

## Table of Contents

1. [Theoretical Concepts](#theoretical-concepts)
2. [JWT Deep Dive](#jwt-deep-dive)
3. [Architecture & Design Patterns](#architecture--design-patterns)
4. [Case Study: E-Commerce API](#case-study-e-commerce-api)
5. [Step-by-Step Implementation](#step-by-step-implementation)
6. [Security Best Practices](#security-best-practices)
7. [Testing Strategy](#testing-strategy)
8. [Deployment & Scaling](#deployment--scaling)

---

## 1. Theoretical Concepts

### 1.1 What is Authentication?

**Authentication** is the process of verifying the identity of a user or system. It answers the question: *"Who are you?"*

**Key Concepts:**
- **Identity Verification**: Confirming that users are who they claim to be
- **Credentials**: Information used to authenticate (username/password, tokens, biometrics)
- **Session Management**: Maintaining authenticated state across requests
- **Trust Establishment**: Creating a secure relationship between client and server

### 1.2 What is Authorization?

**Authorization** determines what an authenticated user is allowed to do. It answers: *"What can you do?"*

**Key Concepts:**
- **Permissions**: Specific actions a user can perform
- **Roles**: Collections of permissions assigned to users
- **Access Control**: Mechanisms to enforce authorization rules
- **Resource Protection**: Securing endpoints and data based on user privileges

### 1.3 Traditional vs Token-Based Authentication

#### Traditional Session-Based Authentication:
```
Client                    Server
  |                         |
  |--- Login Request ------>|
  |                         | Create Session
  |                         | Store in DB/Memory
  |<-- Session Cookie ------|
  |                         |
  |--- Request + Cookie --->|
  |                         | Validate Session
  |<------ Response --------|
```

**Pros:**
- Server controls session lifecycle
- Easy to revoke access
- Well-established pattern

**Cons:**
- Stateful (server must store sessions)
- Difficult to scale horizontally
- CORS complexity
- Not ideal for mobile/SPA applications

#### Token-Based Authentication (JWT):
```
Client                    Server
  |                         |
  |--- Login Request ------>|
  |                         | Validate Credentials
  |                         | Generate JWT
  |<------- JWT ------------|
  |                         |
  |--- Request + JWT ------>|
  |                         | Verify JWT Signature
  |<------ Response --------|
```

**Pros:**
- Stateless (no server-side storage)
- Horizontally scalable
- Cross-domain/CORS friendly
- Perfect for microservices
- Mobile-friendly

**Cons:**
- Cannot revoke tokens easily
- Token size overhead
- Token theft concerns

---

## 2. JWT Deep Dive

### 2.1 What is JWT?

**JSON Web Token (JWT)** is an open standard (RFC 7519) that defines a compact and self-contained way for securely transmitting information between parties as a JSON object.

### 2.2 JWT Structure

A JWT consists of three parts separated by dots (`.`):

```
xxxxx.yyyyy.zzzzz
```

**Format:**
```
HEADER.PAYLOAD.SIGNATURE
```

#### **2.2.1 Header**
Contains metadata about the token:
```json
{
  "alg": "HS256",
  "typ": "JWT"
}
```
- `alg`: Algorithm used (HMAC SHA256, RSA)
- `typ`: Token type

**Base64URL Encoded:**
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9
```

#### **2.2.2 Payload**
Contains the claims (user data and metadata):
```json
{
  "sub": "1234567890",
  "name": "John Doe",
  "iat": 1516239022,
  "exp": 1516242622,
  "role": "admin"
}
```

**Standard Claims:**
- `iss` (issuer): Who issued the token
- `sub` (subject): Who the token is about (user ID)
- `aud` (audience): Who the token is for
- `exp` (expiration): When the token expires
- `nbf` (not before): Token not valid before this time
- `iat` (issued at): When the token was issued
- `jti` (JWT ID): Unique identifier for the token

**Base64URL Encoded:**
```
eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ
```

#### **2.2.3 Signature**
Ensures the token hasn't been tampered with:
```javascript
HMACSHA256(
  base64UrlEncode(header) + "." + base64UrlEncode(payload),
  secret
)
```

**Complete JWT Example:**
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyLCJleHAiOjE1MTYyNDI2MjJ9.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c
```

### 2.3 How JWT Works

1. **User Login**: User sends credentials
2. **Server Validation**: Server validates credentials
3. **Token Generation**: Server creates JWT with user claims
4. **Token Signing**: Server signs token with secret key
5. **Token Delivery**: Server sends JWT to client
6. **Storage**: Client stores JWT (localStorage, sessionStorage, memory)
7. **Subsequent Requests**: Client sends JWT in Authorization header
8. **Validation**: Server validates signature and claims
9. **Access Granted**: If valid, server processes request

### 2.4 JWT Security Considerations

#### **2.4.1 Storage Options**

**localStorage:**
- Persistent across sessions
- Vulnerable to XSS attacks
- Simple implementation

**sessionStorage:**
- Cleared when tab closes
- Vulnerable to XSS attacks
- Better than localStorage

**Memory (JavaScript variable):**
- Lost on page reload
- Best security (not accessible to scripts)
- Requires refresh token strategy

**httpOnly Cookies:**
- Not accessible to JavaScript
- Protected from XSS
- Requires CSRF protection

#### **2.4.2 Common Vulnerabilities**

**XSS (Cross-Site Scripting):**
- Malicious scripts steal tokens from localStorage
- **Mitigation**: Use httpOnly cookies, Content Security Policy

**Man-in-the-Middle:**
- Attacker intercepts token during transmission
- **Mitigation**: Always use HTTPS/TLS

**Token Replay:**
- Stolen token used by attacker
- **Mitigation**: Short expiration, refresh tokens, IP binding

**Algorithm Confusion:**
- Changing algorithm from RS256 to HS256
- **Mitigation**: Strictly validate algorithm

---

## 3. Architecture & Design Patterns

### 3.1 Laravel JWT Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    CLIENT APPLICATION                    │
│  (Web/Mobile App with JWT stored in memory/storage)     │
└───────────────────────┬─────────────────────────────────┘
                        │
                        │ HTTPS + Bearer Token
                        ▼
┌─────────────────────────────────────────────────────────┐
│                   API GATEWAY/ROUTES                     │
│              (routes/api.php - Route Guards)             │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ CORS         │  │ Throttle     │  │ Authenticate │  │
│  │ Middleware   │  │ Middleware   │  │ Middleware   │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                    GUARD LAYER                           │
│              (JWT Guard - Token Validation)              │
│  • Extract token from Authorization header               │
│  • Validate signature                                    │
│  • Check expiration                                      │
│  • Decode claims                                         │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                  CONTROLLER LAYER                        │
│  ┌──────────────────────────────────────────────────┐  │
│  │ AuthController    │ UserController │ etc...      │  │
│  │ • login()         │ • index()      │             │  │
│  │ • register()      │ • show()       │             │  │
│  │ • logout()        │ • update()     │             │  │
│  │ • refresh()       │ • delete()     │             │  │
│  └──────────────────────────────────────────────────┘  │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                   SERVICE LAYER                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ AuthService      │ UserService   │ RoleService  │  │
│  │ • Business Logic │ • CRUD Ops    │ • Permissions│  │
│  └──────────────────────────────────────────────────┘  │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                 REPOSITORY LAYER                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │ UserRepository   │ RoleRepository │ etc...       │  │
│  │ • Data Access    │ • Query Building              │  │
│  └──────────────────────────────────────────────────┘  │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                     MODEL LAYER                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ User Model       │ Role Model    │ Permission   │  │
│  │ • Eloquent ORM   │ • Relationships               │  │
│  └──────────────────────────────────────────────────┘  │
└───────────────────────┬─────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│                   DATABASE LAYER                         │
│         (MySQL/PostgreSQL - Persistent Storage)          │
└─────────────────────────────────────────────────────────┘
```

### 3.2 Design Patterns Used

#### **Repository Pattern**
Abstracts data access logic from business logic:
```php
interface UserRepositoryInterface {
    public function findById(int $id): ?User;
    public function create(array $data): User;
}
```

#### **Service Layer Pattern**
Encapsulates business logic:
```php
class AuthService {
    public function authenticate($credentials) {
        // Business logic here
    }
}
```

#### **Dependency Injection**
Promotes loose coupling and testability:
```php
public function __construct(
    private UserRepository $userRepository,
    private JWTAuth $jwt
) {}
```

#### **Strategy Pattern**
Different authentication strategies:
```php
interface AuthenticationStrategy {
    public function authenticate($credentials);
}
```

---

## 4. Case Study: E-Commerce API

### 4.1 Business Requirements

We'll build a **Multi-Vendor E-Commerce API** with the following features:

#### **Actors:**
1. **Guest**: Unauthenticated users
2. **Customer**: Registered buyers
3. **Vendor**: Sellers managing their products
4. **Admin**: Platform administrators

#### **Features:**

**Public (No Auth):**
- Browse products
- View product details
- Search and filter products

**Customer (Auth Required):**
- Register/Login
- Profile management
- Add products to cart
- Place orders
- View order history
- Write product reviews

**Vendor (Auth + Role):**
- Manage own products (CRUD)
- View own orders
- Update order status
- View sales analytics

**Admin (Auth + Admin Role):**
- Manage all users
- Manage all products
- Manage all orders
- Platform settings
- View system analytics

### 4.2 Database Schema

```sql
-- Users Table
users
  - id (PK)
  - name
  - email (unique)
  - password
  - email_verified_at
  - role (enum: customer, vendor, admin)
  - status (enum: active, suspended, banned)
  - created_at
  - updated_at

-- Products Table
products
  - id (PK)
  - vendor_id (FK -> users.id)
  - name
  - slug (unique)
  - description
  - price (decimal)
  - stock_quantity
  - status (enum: draft, published, archived)
  - created_at
  - updated_at

-- Categories Table
categories
  - id (PK)
  - name
  - slug (unique)
  - parent_id (FK -> categories.id, nullable)
  - created_at
  - updated_at

-- Product_Category Pivot
category_product
  - id (PK)
  - product_id (FK)
  - category_id (FK)

-- Orders Table
orders
  - id (PK)
  - user_id (FK -> users.id)
  - order_number (unique)
  - total_amount (decimal)
  - status (enum: pending, processing, shipped, delivered, cancelled)
  - created_at
  - updated_at

-- Order_Items Table
order_items
  - id (PK)
  - order_id (FK -> orders.id)
  - product_id (FK -> products.id)
  - quantity
  - price (decimal)
  - subtotal (decimal)

-- Reviews Table
reviews
  - id (PK)
  - user_id (FK -> users.id)
  - product_id (FK -> products.id)
  - rating (1-5)
  - comment (text)
  - created_at
  - updated_at

-- Personal Access Tokens (for token blacklisting)
personal_access_tokens
  - id (PK)
  - tokenable_type
  - tokenable_id
  - name
  - token (unique, hashed)
  - abilities (text)
  - last_used_at
  - expires_at
  - created_at
  - updated_at
```

### 4.3 API Endpoints Design

#### **Authentication Endpoints**
```
POST   /api/auth/register        - Register new user
POST   /api/auth/login           - Login and get JWT
POST   /api/auth/logout          - Invalidate token
POST   /api/auth/refresh         - Refresh JWT
GET    /api/auth/me              - Get authenticated user
```

#### **User Endpoints**
```
GET    /api/users                - List users (admin only)
GET    /api/users/{id}           - Get user details
PUT    /api/users/{id}           - Update user
DELETE /api/users/{id}           - Delete user (admin only)
```

#### **Product Endpoints**
```
GET    /api/products             - List products (public)
POST   /api/products             - Create product (vendor/admin)
GET    /api/products/{id}        - Get product details (public)
PUT    /api/products/{id}        - Update product (owner/admin)
DELETE /api/products/{id}        - Delete product (owner/admin)
GET    /api/vendor/products      - Get vendor's products
```

#### **Order Endpoints**
```
GET    /api/orders               - List user's orders
POST   /api/orders               - Create order
GET    /api/orders/{id}          - Get order details
PUT    /api/orders/{id}/status   - Update order status (vendor/admin)
DELETE /api/orders/{id}          - Cancel order
```

#### **Review Endpoints**
```
GET    /api/products/{id}/reviews     - Get product reviews
POST   /api/products/{id}/reviews     - Create review (customer)
PUT    /api/reviews/{id}              - Update review (owner)
DELETE /api/reviews/{id}              - Delete review (owner/admin)
```

### 4.4 Authorization Matrix

| Endpoint | Guest | Customer | Vendor | Admin |
|----------|-------|----------|--------|-------|
| GET /products | ✓ | ✓ | ✓ | ✓ |
| POST /products | ✗ | ✗ | ✓ | ✓ |
| PUT /products/{id} | ✗ | ✗ | ✓ (own) | ✓ |
| DELETE /products/{id} | ✗ | ✗ | ✓ (own) | ✓ |
| POST /orders | ✗ | ✓ | ✓ | ✓ |
| GET /orders | ✗ | ✓ (own) | ✓ (own) | ✓ |
| PUT /orders/{id}/status | ✗ | ✗ | ✓ | ✓ |
| POST /reviews | ✗ | ✓ | ✓ | ✓ |
| DELETE /users/{id} | ✗ | ✗ | ✗ | ✓ |

---

## 5. Step-by-Step Implementation

### 5.1 Environment Setup

#### **Prerequisites:**
- PHP 8.2+
- Composer 2.x
- MySQL 8.0+ / PostgreSQL
- Git

#### **Step 1: Create Laravel 11 Project**
```bash
composer create-project laravel/laravel jwt-ecommerce-api
cd jwt-ecommerce-api
```

#### **Step 2: Install JWT Package**
```bash
composer require php-open-source-saver/jwt-auth
```

#### **Step 3: Publish JWT Configuration**
```bash
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
```

#### **Step 4: Generate JWT Secret**
```bash
php artisan jwt:secret
```

This adds `JWT_SECRET` to your `.env` file.

#### **Step 5: Configure Database**
Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jwt_ecommerce
DB_USERNAME=root
DB_PASSWORD=your_password

JWT_SECRET=your_generated_secret
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256
```

### 5.2 JWT Configuration Deep Dive

**config/jwt.php** - Key configurations:

```php
return [
    'secret' => env('JWT_SECRET'),
    
    'keys' => [
        'public' => env('JWT_PUBLIC_KEY'),
        'private' => env('JWT_PRIVATE_KEY'),
        'passphrase' => env('JWT_PASSPHRASE'),
    ],
    
    'ttl' => env('JWT_TTL', 60), // Token lifetime in minutes
    
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // 2 weeks
    
    'algo' => env('JWT_ALGO', 'HS256'),
    
    'required_claims' => [
        'iss',  // Issuer
        'iat',  // Issued at
        'exp',  // Expiration
        'nbf',  // Not before
        'sub',  // Subject (user ID)
        'jti',  // JWT ID
    ],
    
    'persistent_claims' => [],
    
    'lock_subject' => true,
    
    'leeway' => env('JWT_LEEWAY', 0),
    
    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),
    
    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),
    
    'decrypt_cookies' => false,
    
    'providers' => [
        'jwt' => PHPOpenSourceSaver\JWTAuth\Providers\JWT\Lcobucci::class,
        'auth' => PHPOpenSourceSaver\JWTAuth\Providers\Auth\Illuminate::class,
        'storage' => PHPOpenSourceSaver\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];
```

**Key Settings Explained:**

- **ttl**: Token lifetime (60 min = 1 hour)
- **refresh_ttl**: Refresh token lifetime (20160 min = 14 days)
- **algo**: Encryption algorithm (HS256, RS256, etc.)
- **blacklist_enabled**: Enable token revocation
- **lock_subject**: Prevent subject claim modification

### 5.3 User Model Configuration

**app/Models/User.php:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
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
        return [
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
```

### 5.4 Authentication Guard Configuration

**config/auth.php:**
```php
<?php

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
```

---

## 6. Security Best Practices

### 6.1 Token Security

#### **Use Strong Secrets**
```bash
# Generate a strong JWT secret (256-bit)
php artisan jwt:secret --force
```

#### **Short Token Lifetimes**
```env
JWT_TTL=60  # 1 hour for access tokens
JWT_REFRESH_TTL=20160  # 2 weeks for refresh tokens
```

#### **HTTPS Only**
Always use HTTPS in production:
```php
// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}
```

### 6.2 Rate Limiting

**routes/api.php:**
```php
Route::middleware(['throttle:api'])->group(function () {
    // API routes
});

// Custom rate limits
Route::middleware(['throttle:login'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});
```

**app/Providers/RouteServiceProvider.php:**
```php
protected function configureRateLimiting()
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)->by($request->ip());
    });
}
```

### 6.3 Input Validation

Always validate incoming requests:
```php
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    // Process registration
}
```

### 6.4 SQL Injection Prevention

Laravel's query builder and Eloquent ORM automatically prevent SQL injection:
```php
// Safe - parameterized
User::where('email', $email)->first();

// Unsafe - avoid raw queries without bindings
DB::select("SELECT * FROM users WHERE email = '$email'"); // DON'T DO THIS

// Safe raw query with bindings
DB::select("SELECT * FROM users WHERE email = ?", [$email]);
```

### 6.5 XSS Prevention

Sanitize output in API responses:
```php
// Laravel automatically escapes in Blade
// For API, ensure proper Content-Type headers

return response()->json($data, 200, [
    'Content-Type' => 'application/json',
    'X-Content-Type-Options' => 'nosniff',
]);
```

### 6.6 CORS Configuration

**config/cors.php:**
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000')
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

---

## 7. Testing Strategy

### 7.1 Feature Tests

**tests/Feature/AuthTest.php:**
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
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
                     'user' => ['id', 'name', 'email'],
                     'token'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
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
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJson(['id' => $user->id]);
    }

    public function test_user_cannot_access_protected_route_without_token()
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);
    }
}
```

### 7.2 Unit Tests

**tests/Unit/UserTest.php:**
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_jwt_identifier()
    {
        $user = User::factory()->create();
        $this->assertEquals($user->id, $user->getJWTIdentifier());
    }

    public function test_user_has_custom_jwt_claims()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'role' => 'vendor',
        ]);

        $claims = $user->getJWTCustomClaims();
        
        $this->assertArrayHasKey('email', $claims);
        $this->assertArrayHasKey('role', $claims);
        $this->assertEquals('vendor', $claims['role']);
    }

    public function test_is_admin_method()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($customer->isAdmin());
    }
}
```

---

## 8. Deployment & Scaling

### 8.1 Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY` and `JWT_SECRET`
- [ ] Enable HTTPS/SSL
- [ ] Configure proper CORS settings
- [ ] Set up database backups
- [ ] Configure rate limiting
- [ ] Set up monitoring (logs, errors)
- [ ] Enable caching (Redis/Memcached)
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`

### 8.2 Horizontal Scaling Strategies

#### **Stateless Architecture**
JWT enables stateless authentication - each request is independent:
```
Load Balancer
     |
     |-----> API Server 1
     |-----> API Server 2
     |-----> API Server 3
            (All verify JWT independently)
```

#### **Shared Cache for Blacklisting**
Use Redis for token blacklist across servers:
```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

#### **Database Optimization**
- Use read replicas for scaling reads
- Index frequently queried columns
- Use query optimization and eager loading

### 8.3 Monitoring & Logging

**Install Laravel Telescope (development):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**Production Monitoring:**
- Use services like New Relic, Datadog, or Sentry
- Monitor JWT token generation/validation times
- Track failed authentication attempts
- Monitor API response times

---

## Summary

This tutorial covered:
✅ **Authentication vs Authorization theory**
✅ **JWT structure and security**
✅ **Laravel 11 architecture patterns**
✅ **Complete e-commerce case study**
✅ **Step-by-step implementation**
✅ **Security best practices**
✅ **Testing strategies**
✅ **Deployment and scaling**

**Next Steps:**
1. Review the implementation files in this project
2. Run migrations and seeders
3. Test API endpoints with Postman
4. Implement additional features
5. Deploy to production

**Resources:**
- [JWT.io](https://jwt.io) - JWT debugger
- [Laravel Documentation](https://laravel.com/docs)
- [PHP-Open-Source-Saver JWT Auth](https://github.com/PHP-Open-Source-Saver/jwt-auth)
- [RFC 7519 - JWT Specification](https://tools.ietf.org/html/rfc7519)

---

**Author**: Senior Laravel Architect (25+ Years Experience)  
**Date**: December 16, 2025  
**Version**: 1.0  
**Laravel Version**: 11.x  
**PHP Version**: 8.2+
