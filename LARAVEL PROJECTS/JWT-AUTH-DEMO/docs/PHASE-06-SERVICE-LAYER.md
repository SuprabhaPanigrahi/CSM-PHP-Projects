# ⚙️ PHASE 6: SERVICE LAYER IMPLEMENTATION

**Building Business Logic Layer with Service Classes**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **AuthService** - Authentication business logic
- ✅ **ProductService** - Product management logic
- ✅ **OrderService** - Order processing logic
- ✅ **ReviewService** - Review management logic
- ✅ **AdminService** - Admin operations logic
- ✅ **Business rules** enforcement
- ✅ **Transaction handling**

---

## 🎯 Why Service Layer?

### Benefits:

1. **Business Logic Separation** - Controllers stay thin
2. **Reusability** - Services can be used by controllers, commands, jobs
3. **Testability** - Easy to write unit tests
4. **Maintainability** - Changes in one place
5. **Transaction Management** - Handle complex operations

### Controller → Service → Repository Flow:

```
HTTP Request → Controller → Service → Repository → Database
                  ↓            ↓          ↓
              Validate    Business    Data Access
                           Logic
```

---

## 📁 Step 1: Create Services Directory

```bash
mkdir -p app/Services
```

Structure:
```
app/
└── Services/
    ├── AuthService.php
    ├── ProductService.php
    ├── OrderService.php
    ├── ReviewService.php
    └── AdminService.php
```

---

## 📝 Step 2: Create AuthService

Create `app/Services/AuthService.php`:

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        // Business Logic: Check if email already exists
        if ($this->userRepository->emailExists($data['email'])) {
            throw new \Exception('Email already registered');
        }

        // Business Logic: Hash password
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'customer';
        $data['status'] = 'active';
        $data['email_verified_at'] = now();

        // Create user via repository
        $user = $this->userRepository->create($data);

        // Business Logic: Generate JWT token
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Authenticate user and generate token
     */
    public function login(array $credentials): array
    {
        // Business Logic: Find user by email
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user) {
            throw new \Exception('Invalid credentials');
        }

        // Business Logic: Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        // Business Logic: Check user status
        if ($user->status !== 'active') {
            throw new \Exception('Account is ' . $user->status);
        }

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Get authenticated user
     */
    public function me(): User
    {
        return JWTAuth::user();
    }

    /**
     * Refresh JWT token
     */
    public function refresh(): array
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Logout user (invalidate token)
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(User $user, string|array $roles): bool
    {
        if (is_string($roles)) {
            return $user->role === $roles;
        }

        return in_array($user->role, $roles);
    }

    /**
     * Check if user is active
     */
    public function isActive(User $user): bool
    {
        return $user->status === 'active';
    }
}
```

---

## 📝 Step 3: Create ProductService

Create `app/Services/ProductService.php`:

```php
<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get products with filters
     */
    public function getProducts(array $filters = [], int $perPage = 15)
    {
        return $this->productRepository->getPaginated($filters, $perPage);
    }

    /**
     * Get single product by ID
     */
    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create new product
     */
    public function createProduct(array $data, User $user): Product
    {
        // Business Rule: Only vendors and admins can create products
        if (!in_array($user->role, ['vendor', 'admin'])) {
            throw new \Exception('Only vendors and admins can create products');
        }

        // Set vendor_id
        $data['vendor_id'] = $user->id;

        // Generate unique slug
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        // Set default status
        $data['status'] = $data['status'] ?? 'draft';

        // Create product
        $product = $this->productRepository->create($data);

        // Sync categories if provided
        if (isset($data['categories']) && is_array($data['categories'])) {
            $this->productRepository->syncCategories($product->id, $data['categories']);
        }

        return $this->productRepository->findById($product->id);
    }

    /**
     * Update product
     */
    public function updateProduct(int $id, array $data, User $user): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyProduct($product, $user)) {
            throw new \Exception('Unauthorized to modify this product');
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
        }

        // Update product
        $product = $this->productRepository->update($id, $data);

        // Sync categories if provided
        if (isset($data['categories']) && is_array($data['categories'])) {
            $this->productRepository->syncCategories($id, $data['categories']);
        }

        return $this->productRepository->findById($id);
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id, User $user): bool
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyProduct($product, $user)) {
            throw new \Exception('Unauthorized to delete this product');
        }

        return $this->productRepository->delete($id);
    }

    /**
     * Get vendor's products
     */
    public function getVendorProducts(int $vendorId, array $filters = [], int $perPage = 15)
    {
        return $this->productRepository->getByVendor($vendorId, $filters, $perPage);
    }

    /**
     * Update product stock
     */
    public function updateStock(int $productId, int $quantity): void
    {
        $this->productRepository->updateStock($productId, $quantity);
    }

    /**
     * Decrease product stock
     */
    public function decreaseStock(int $productId, int $quantity): void
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        if ($product->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for {$product->name}");
        }

        $this->productRepository->decrementStock($productId, $quantity);
    }

    /**
     * Check if user can modify product
     */
    protected function canModifyProduct(Product $product, User $user): bool
    {
        // Admin can modify any product
        if ($user->role === 'admin') {
            return true;
        }

        // Vendor can only modify their own products
        if ($user->role === 'vendor' && $product->vendor_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Generate unique slug
     */
    protected function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            // Check if slug exists
            $exists = Product::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists();

            if (!$exists) {
                break;
            }

            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}
```

---

## 📝 Step 4: Create OrderService

Create `app/Services/OrderService.php`:

```php
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get orders (filtered by user role)
     */
    public function getOrders(User $user, array $filters = [], int $perPage = 15)
    {
        // Business Rule: Customers see only their orders
        if ($user->role === 'customer') {
            $filters['user_id'] = $user->id;
        }

        // Business Rule: Vendors see orders containing their products
        if ($user->role === 'vendor') {
            // This requires complex query - simplified for this example
            $filters['user_id'] = $user->id; // Modify as needed
        }

        // Admin sees all orders (no filter)

        return $this->orderRepository->getPaginated($filters, $perPage);
    }

    /**
     * Get single order
     */
    public function getOrderById(int $id, User $user): ?Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return null;
        }

        // Business Rule: Authorization check
        if (!$this->canViewOrder($order, $user)) {
            throw new \Exception('Unauthorized to view this order');
        }

        return $order;
    }

    /**
     * Create new order
     */
    public function createOrder(array $data, User $user): Order
    {
        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItems = [];

            // Business Logic: Validate and process items
            foreach ($data['items'] as $item) {
                $product = $this->productRepository->findById($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product not found");
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $itemTotal,
                    'product_snapshot' => [
                        'name' => $product->name,
                        'image' => $product->images[0] ?? null,
                    ],
                ];

                // Decrease stock
                $this->productRepository->decrementStock($product->id, $item['quantity']);
            }

            // Create order
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $totalAmount,
                'tax' => $totalAmount * 0.1, // 10% tax
                'shipping_fee' => $data['shipping_fee'] ?? 10,
                'discount' => $data['discount'] ?? 0,
                'total_amount' => $totalAmount + ($totalAmount * 0.1) + ($data['shipping_fee'] ?? 10) - ($data['discount'] ?? 0),
                'shipping_address' => $data['shipping_address'] ?? $user->address,
                'billing_address' => $data['billing_address'] ?? $user->address,
                'payment_method' => $data['payment_method'] ?? 'cash_on_delivery',
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return $this->orderRepository->findById($order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $id, string $status, User $user): Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyOrder($order, $user)) {
            throw new \Exception('Unauthorized to modify this order');
        }

        // Business Rule: Validate status transition
        if (!$this->validateStatusTransition($order->status, $status)) {
            throw new \Exception("Cannot change status from {$order->status} to {$status}");
        }

        return $this->orderRepository->updateStatus($id, $status);
    }

    /**
     * Cancel order
     */
    public function cancelOrder(int $id, User $user): bool
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Business Rule: Only pending/processing orders can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw new \Exception('Cannot cancel order with status: ' . $order->status);
        }

        // Business Rule: Authorization check
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            throw new \Exception('Unauthorized to cancel this order');
        }

        DB::beginTransaction();

        try {
            // Restore stock
            foreach ($order->items as $item) {
                $product = $this->productRepository->findById($item->product_id);
                if ($product) {
                    $this->productRepository->updateStock(
                        $product->id,
                        $product->stock_quantity + $item->quantity
                    );
                }
            }

            // Update order status
            $this->orderRepository->updateStatus($id, 'cancelled');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if user can view order
     */
    protected function canViewOrder(Order $order, User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'customer' && $order->user_id === $user->id) {
            return true;
        }

        // Vendors can view orders containing their products
        if ($user->role === 'vendor') {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->vendor_id === $user->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user can modify order
     */
    protected function canModifyOrder(Order $order, User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'vendor') {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->vendor_id === $user->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Validate status transition
     */
    protected function validateStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => ['refunded'],
            'cancelled' => [],
            'refunded' => [],
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(Str::random(10));
    }
}
```

---

## 📝 Step 5: Create ReviewService

Create `app/Services/ReviewService.php`:

```php
<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ReviewService
{
    protected $reviewRepository;
    protected $productRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get product reviews
     */
    public function getProductReviews(int $productId, int $perPage = 15)
    {
        return $this->reviewRepository->getByProduct($productId, $perPage);
    }

    /**
     * Create new review
     */
    public function createReview(array $data, int $productId, User $user): Review
    {
        // Business Rule: Check if product exists
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Business Rule: User can only review once per product
        if ($this->reviewRepository->userHasReviewed($productId, $user->id)) {
            throw new \Exception('You have already reviewed this product');
        }

        // Business Rule: Rating must be between 1-5
        if ($data['rating'] < 1 || $data['rating'] > 5) {
            throw new \Exception('Rating must be between 1 and 5');
        }

        $data['product_id'] = $productId;
        $data['user_id'] = $user->id;
        $data['is_approved'] = true; // Auto-approve for now
        $data['is_verified_purchase'] = false; // Can be enhanced

        return $this->reviewRepository->create($data);
    }

    /**
     * Update review
     */
    public function updateReview(int $id, array $data, User $user): Review
    {
        $review = $this->reviewRepository->findById($id);

        if (!$review) {
            throw new \Exception('Review not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyReview($review, $user)) {
            throw new \Exception('Unauthorized to modify this review');
        }

        // Validate rating if provided
        if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
            throw new \Exception('Rating must be between 1 and 5');
        }

        return $this->reviewRepository->update($id, $data);
    }

    /**
     * Delete review
     */
    public function deleteReview(int $id, User $user): bool
    {
        $review = $this->reviewRepository->findById($id);

        if (!$review) {
            throw new \Exception('Review not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyReview($review, $user)) {
            throw new \Exception('Unauthorized to delete this review');
        }

        return $this->reviewRepository->delete($id);
    }

    /**
     * Get average rating for product
     */
    public function getAverageRating(int $productId): float
    {
        return $this->reviewRepository->getAverageRating($productId);
    }

    /**
     * Check if user can modify review
     */
    protected function canModifyReview(Review $review, User $user): bool
    {
        // Admin can modify any review
        if ($user->role === 'admin') {
            return true;
        }

        // User can modify their own review
        if ($review->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
```

---

## 📝 Step 6: Create AdminService

Create `app/Services/AdminService.php`:

```php
<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;

class AdminService
{
    protected $userRepository;
    protected $productRepository;
    protected $orderRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Get dashboard statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_users' => $this->userRepository->getAll()->count(),
            'total_customers' => $this->userRepository->countByRole('customer'),
            'total_vendors' => $this->userRepository->countByRole('vendor'),
            'total_admins' => $this->userRepository->countByRole('admin'),
            'total_products' => $this->productRepository->count(),
            'total_orders' => $this->orderRepository->count(),
        ];
    }

    /**
     * Get users with filters
     */
    public function getUsers(array $filters = [])
    {
        return $this->userRepository->getAll($filters);
    }
}
```

---

## ✅ Success Checklist

Before moving to Phase 7:

- ✅ AuthService created with JWT logic
- ✅ ProductService created with business rules
- ✅ OrderService created with transactions
- ✅ ReviewService created with validation
- ✅ AdminService created for statistics
- ✅ Business logic separated from controllers
- ✅ Repository pattern used in services
- ✅ Exception handling implemented

---

## 🎯 What's Next?

**Next Phase:** [PHASE-07-CONTROLLERS.md](./PHASE-07-CONTROLLERS.md)

In Phase 7, you will:
- Create controller classes
- Inject services via dependency injection
- Implement HTTP request handling
- Add validation

---

**Phase 6 Completed:** ✅  
**Estimated Time:** 75-90 minutes  
**Difficulty Level:** Advanced  

---

*Last Updated: December 16, 2024*
