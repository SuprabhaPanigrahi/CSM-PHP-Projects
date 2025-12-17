# 🗄️ PHASE 5: REPOSITORY LAYER IMPLEMENTATION

**Building the Data Access Layer with Repository Pattern**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **Repository Interfaces** (Contracts) - 4 interfaces
- ✅ **Repository Implementations** - 4 concrete classes
- ✅ **Service Provider** for dependency injection
- ✅ **Data access abstraction** layer
- ✅ **Testable code** structure

---

## 🎯 Why Repository Pattern?

### Benefits:

1. **Separation of Concerns** - Controllers don't know about database
2. **Testability** - Easy to mock repositories in tests
3. **Flexibility** - Can swap database implementation
4. **Reusability** - Repository methods used across application
5. **Clean Code** - Business logic separated from data access

### Without Repository Pattern (❌ Bad):

```php
// Controller directly accessing database
public function index() {
    $users = User::where('status', 'active')->get();
    return response()->json($users);
}
```

### With Repository Pattern (✅ Good):

```php
// Controller using repository
public function index() {
    $users = $this->userRepository->getAll(['status' => 'active']);
    return response()->json($users);
}
```

---

## 📁 Step 1: Create Directory Structure

```bash
# Create directories
mkdir -p app/Repositories/Contracts
mkdir -p app/Repositories
```

Structure:
```
app/
└── Repositories/
    ├── Contracts/  (Interfaces)
    │   ├── UserRepositoryInterface.php
    │   ├── ProductRepositoryInterface.php
    │   ├── OrderRepositoryInterface.php
    │   └── ReviewRepositoryInterface.php
    │
    └── (Implementations)
        ├── UserRepository.php
        ├── ProductRepository.php
        ├── OrderRepository.php
        └── ReviewRepository.php
```

---

## 📝 Step 2: Create Repository Interfaces

### Create UserRepositoryInterface

Create `app/Repositories/Contracts/UserRepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all users with optional filters
     */
    public function getAll(array $filters = []): Collection;

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create new user
     */
    public function create(array $data): User;

    /**
     * Update user
     */
    public function update(int $id, array $data): User;

    /**
     * Delete user
     */
    public function delete(int $id): bool;

    /**
     * Get users by role
     */
    public function getByRole(string $role): Collection;

    /**
     * Get users by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Count users by role
     */
    public function countByRole(string $role): int;

    /**
     * Check if email exists
     */
    public function emailExists(string $email, ?int $excludeId = null): bool;
}
```

### Create ProductRepositoryInterface

Create `app/Repositories/Contracts/ProductRepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Get paginated products with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find product by ID
     */
    public function findById(int $id): ?Product;

    /**
     * Find product by slug
     */
    public function findBySlug(string $slug): ?Product;

    /**
     * Create new product
     */
    public function create(array $data): Product;

    /**
     * Update product
     */
    public function update(int $id, array $data): Product;

    /**
     * Delete product
     */
    public function delete(int $id): bool;

    /**
     * Get products by vendor
     */
    public function getByVendor(int $vendorId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Sync product categories
     */
    public function syncCategories(int $productId, array $categoryIds): void;

    /**
     * Update stock quantity
     */
    public function updateStock(int $productId, int $quantity): void;

    /**
     * Decrement stock quantity
     */
    public function decrementStock(int $productId, int $quantity): void;

    /**
     * Count products
     */
    public function count(): int;
}
```

### Create OrderRepositoryInterface

Create `app/Repositories/Contracts/OrderRepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    /**
     * Get paginated orders
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find order by ID
     */
    public function findById(int $id): ?Order;

    /**
     * Find order by order number
     */
    public function findByOrderNumber(string $orderNumber): ?Order;

    /**
     * Create new order
     */
    public function create(array $data): Order;

    /**
     * Update order
     */
    public function update(int $id, array $data): Order;

    /**
     * Delete order
     */
    public function delete(int $id): bool;

    /**
     * Get orders by user
     */
    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator;

    /**
     * Get orders by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status): Order;

    /**
     * Count orders
     */
    public function count(): int;
}
```

### Create ReviewRepositoryInterface

Create `app/Repositories/Contracts/ReviewRepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    /**
     * Get reviews for a product
     */
    public function getByProduct(int $productId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find review by ID
     */
    public function findById(int $id): ?Review;

    /**
     * Create new review
     */
    public function create(array $data): Review;

    /**
     * Update review
     */
    public function update(int $id, array $data): Review;

    /**
     * Delete review
     */
    public function delete(int $id): bool;

    /**
     * Check if user has reviewed product
     */
    public function userHasReviewed(int $productId, int $userId): bool;

    /**
     * Get average rating for product
     */
    public function getAverageRating(int $productId): float;

    /**
     * Get reviews count for product
     */
    public function getReviewsCount(int $productId): int;
}
```

---

## 📝 Step 3: Implement Repositories

### Implement UserRepository

Create `app/Repositories/UserRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->get();
    }

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        $user->update($data);
        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        return $user ? $user->delete() : false;
    }

    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function countByRole(string $role): int
    {
        return $this->model->where('role', $role)->count();
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = $this->model->where('email', $email);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
```

### Implement ProductRepository

Create `app/Repositories/ProductRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['vendor', 'categories', 'reviews']);

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by vendor
        if (isset($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        // Filter by category
        if (isset($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        // Search by name or description
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter by featured
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        // Filter by price range
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->with(['vendor', 'categories', 'reviews'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with(['vendor', 'categories', 'reviews'])
                           ->where('slug', $slug)
                           ->first();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->model->find($id);
        $product->update($data);
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        return $product ? $product->delete() : false;
    }

    public function getByVendor(int $vendorId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $filters['vendor_id'] = $vendorId;
        return $this->getPaginated($filters, $perPage);
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        })->get();
    }

    public function syncCategories(int $productId, array $categoryIds): void
    {
        $product = $this->findById($productId);
        if ($product) {
            $product->categories()->sync($categoryIds);
        }
    }

    public function updateStock(int $productId, int $quantity): void
    {
        $product = $this->findById($productId);
        if ($product) {
            $product->update(['stock_quantity' => $quantity]);
        }
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        $product = $this->findById($productId);
        if ($product && $product->stock_quantity >= $quantity) {
            $product->decrement('stock_quantity', $quantity);
        }
    }

    public function count(): int
    {
        return $this->model->count();
    }
}
```

### Implement OrderRepository

Create `app/Repositories/OrderRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'items.product']);

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->with(['user', 'items.product'])->find($id);
    }

    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return $this->model->where('order_number', $orderNumber)->first();
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Order
    {
        $order = $this->findById($id);
        $order->update($data);
        return $order->fresh();
    }

    public function delete(int $id): bool
    {
        $order = $this->findById($id);
        return $order ? $order->delete() : false;
    }

    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $filters['user_id'] = $userId;
        return $this->getPaginated($filters);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function updateStatus(int $orderId, string $status): Order
    {
        $order = $this->findById($orderId);
        $order->update(['status' => $status]);
        return $order->fresh();
    }

    public function count(): int
    {
        return $this->model->count();
    }
}
```

### Implement ReviewRepository

Create `app/Repositories/ReviewRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository implements ReviewRepositoryInterface
{
    protected $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function getByProduct(int $productId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')
                           ->where('product_id', $productId)
                           ->where('is_approved', true)
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);
    }

    public function findById(int $id): ?Review
    {
        return $this->model->with(['user', 'product'])->find($id);
    }

    public function create(array $data): Review
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Review
    {
        $review = $this->findById($id);
        $review->update($data);
        return $review->fresh();
    }

    public function delete(int $id): bool
    {
        $review = $this->findById($id);
        return $review ? $review->delete() : false;
    }

    public function userHasReviewed(int $productId, int $userId): bool
    {
        return $this->model->where('product_id', $productId)
                           ->where('user_id', $userId)
                           ->exists();
    }

    public function getAverageRating(int $productId): float
    {
        return (float) $this->model->where('product_id', $productId)
                                   ->where('is_approved', true)
                                   ->avg('rating') ?? 0;
    }

    public function getReviewsCount(int $productId): int
    {
        return $this->model->where('product_id', $productId)
                           ->where('is_approved', true)
                           ->count();
    }
}
```

---

## 📝 Step 4: Create Service Provider

Create `app/Providers/RepositoryServiceProvider.php`:

```bash
php artisan make:provider RepositoryServiceProvider
```

Edit the file:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind UserRepository
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        // Bind ProductRepository
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository::class
        );

        // Bind OrderRepository
        $this->app->bind(
            \App\Repositories\Contracts\OrderRepositoryInterface::class,
            \App\Repositories\OrderRepository::class
        );

        // Bind ReviewRepository
        $this->app->bind(
            \App\Repositories\Contracts\ReviewRepositoryInterface::class,
            \App\Repositories\ReviewRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
```

---

## 📝 Step 5: Register Service Provider

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

## ✅ Step 6: Test Repository Layer

### Using Tinker

```bash
php artisan tinker
```

```php
// Test UserRepository
$userRepo = app(\App\Repositories\Contracts\UserRepositoryInterface::class);

// Get all users
$users = $userRepo->getAll();

// Find by ID
$user = $userRepo->findById(1);

// Find by email
$user = $userRepo->findByEmail('admin@example.com');

// Check email exists
$exists = $userRepo->emailExists('admin@example.com'); // true

// Test ProductRepository
$productRepo = app(\App\Repositories\Contracts\ProductRepositoryInterface::class);

// Get paginated products
$products = $productRepo->getPaginated(['status' => 'published']);

// Find product
$product = $productRepo->findById(1);

// Exit
exit
```

---

## ✅ Success Checklist

Before moving to Phase 6:

- ✅ 4 Repository interfaces created
- ✅ 4 Repository implementations created
- ✅ Service provider created
- ✅ Service provider registered
- ✅ Tested repositories in Tinker
- ✅ Understanding of dependency injection

---

## 🎯 What's Next?

**Next Phase:** [PHASE-06-SERVICE-LAYER.md](./PHASE-06-SERVICE-LAYER.md)

In Phase 6, you will:
- Create service classes
- Implement business logic
- Use repositories in services
- Apply SOLID principles

---

**Phase 5 Completed:** ✅  
**Estimated Time:** 60-75 minutes  
**Difficulty Level:** Intermediate-Advanced  

---

*Last Updated: December 16, 2024*
