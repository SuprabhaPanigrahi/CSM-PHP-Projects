# 🎮 PHASE 7: CONTROLLERS IMPLEMENTATION

**Building HTTP Request Handlers with Dependency Injection**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **AuthController** - Handle authentication requests
- ✅ **ProductController** - Manage product CRUD
- ✅ **OrderController** - Process orders
- ✅ **ReviewController** - Handle reviews
- ✅ **AdminController** - Admin operations
- ✅ **Request validation**
- ✅ **Response formatting**

---

## 🎯 Controller Responsibilities

Controllers should be **thin** - they only handle:
1. **Validate** incoming HTTP requests
2. **Call** appropriate service methods
3. **Return** HTTP responses

### ❌ What Controllers Should NOT Do:
- Database queries
- Business logic
- Complex calculations
- Direct model access

### ✅ What Controllers SHOULD Do:
- Request validation
- Call services
- Format responses
- Handle exceptions

---

## 📁 Step 1: Create Controllers Directory

```bash
mkdir -p app/Http/Controllers/Api/Auth
mkdir -p app/Http/Controllers/Api
```

Structure:
```
app/Http/Controllers/Api/
├── Auth/
│   └── AuthController.php
├── ProductController.php
├── OrderController.php
├── ReviewController.php
└── AdminController.php
```

---

## 📝 Step 2: Create AuthController

Create `app/Http/Controllers/Api/Auth/AuthController.php`:

```php
<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:customer,vendor',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->authService->register($request->all());

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $result['user'],
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->authService->login($request->only('email', 'password'));

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $result['user'],
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Get authenticated user
     */
    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->me();

            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Logout user
     */
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Refresh JWT token
     */
    public function refresh(): JsonResponse
    {
        try {
            $result = $this->authService->refresh();

            return response()->json([
                'success' => true,
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
```

---

## 📝 Step 3: Create ProductController

Create `app/Http/Controllers/Api/ProductController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * List all products
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
            'vendor_id' => $request->input('vendor_id'),
            'search' => $request->input('search'),
            'is_featured' => $request->input('is_featured'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_order' => $request->input('sort_order', 'desc'),
        ];

        $perPage = $request->input('per_page', 15);

        try {
            $products = $this->productService->getProducts($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create new product
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'status' => 'sometimes|in:draft,published,archived',
            'is_featured' => 'sometimes|boolean',
            'weight' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $product = $this->productService->createProduct($request->all(), $user);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get product details
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update product
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'status' => 'sometimes|in:draft,published,archived',
            'is_featured' => 'sometimes|boolean',
            'weight' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $product = $this->productService->updateProduct($id, $request->all(), $user);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete product
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $this->productService->deleteProduct($id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get vendor products
     */
    public function vendorProducts(int $vendorId, Request $request): JsonResponse
    {
        $filters = [
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        $perPage = $request->input('per_page', 15);

        try {
            $products = $this->productService->getVendorProducts($vendorId, $filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
```

---

## 📝 Step 4: Create OrderController

Create `app/Http/Controllers/Api/OrderController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * List orders
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'status' => $request->input('status'),
            'payment_status' => $request->input('payment_status'),
        ];

        $perPage = $request->input('per_page', 15);

        try {
            $user = JWTAuth::user();
            $orders = $this->orderService->getOrders($user, $filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $orders->items(),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create new order
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $order = $this->orderService->createOrder($request->all(), $user);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get order details
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $order = $this->orderService->getOrderById($id, $user);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $order = $this->orderService->updateOrderStatus($id, $request->status, $user);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cancel order
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $this->orderService->cancelOrder($id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
```

---

## 📝 Step 5: Create ReviewController

Create `app/Http/Controllers/Api/ReviewController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Get product reviews
     */
    public function index(int $productId, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        try {
            $reviews = $this->reviewService->getProductReviews($productId, $perPage);

            return response()->json([
                'success' => true,
                'data' => $reviews->items(),
                'meta' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'average_rating' => $this->reviewService->getAverageRating($productId),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create review
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $review = $this->reviewService->createReview($request->all(), $productId, $user);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update review
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $review = $this->reviewService->updateReview($id, $request->all(), $user);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete review
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $this->reviewService->deleteReview($id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
```

---

## 📝 Step 6: Create AdminController

Create `app/Http/Controllers/Api/AdminController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;
    protected $userRepository;

    public function __construct(
        AdminService $adminService,
        UserRepositoryInterface $userRepository
    ) {
        $this->adminService = $adminService;
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users
     */
    public function getUsers(Request $request): JsonResponse
    {
        $filters = [
            'role' => $request->input('role'),
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        try {
            $users = $this->adminService->getUsers($filters);

            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $stats = $this->adminService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
```

---

## ✅ Success Checklist

Before moving to Phase 8:

- ✅ AuthController created with validation
- ✅ ProductController created with CRUD
- ✅ OrderController created with transactions
- ✅ ReviewController created
- ✅ AdminController created
- ✅ Services injected via constructor
- ✅ Request validation implemented
- ✅ Consistent response formatting
- ✅ Exception handling added

---

## 🎯 What's Next?

**Next Phase:** [PHASE-08-MIDDLEWARE-GUARDS.md](./PHASE-08-MIDDLEWARE-GUARDS.md)

In Phase 8, you will:
- Create JWT middleware
- Create role-based middleware
- Create status check middleware
- Configure authentication guards

---

**Phase 7 Completed:** ✅  
**Estimated Time:** 60-75 minutes  
**Difficulty Level:** Intermediate  

---

*Last Updated: December 16, 2024*
