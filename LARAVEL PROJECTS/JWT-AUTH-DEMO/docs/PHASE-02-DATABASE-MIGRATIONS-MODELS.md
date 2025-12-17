# 🗄️ PHASE 2: DATABASE - MIGRATIONS, MODELS & RELATIONSHIPS

**Complete Database Setup for JWT E-Commerce API**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **6 Database Tables** (users, categories, products, orders, order_items, reviews)
- ✅ **6 Eloquent Models** with proper relationships
- ✅ **Migration Files** with foreign keys and indexes
- ✅ **Many-to-Many Pivot Table** for categories

---

## 🎯 Database Schema Overview

```
┌─────────────────┐
│     USERS       │
│─────────────────│
│ id              │──┐
│ name            │  │
│ email           │  │
│ password        │  │
│ role            │  │  (One-to-Many)
│ status          │  │
└─────────────────┘  │
                     │
                     ├──────> PRODUCTS (vendor_id)
                     │
                     ├──────> ORDERS (user_id)
                     │
                     └──────> REVIEWS (user_id)

┌─────────────────┐
│   CATEGORIES    │
│─────────────────│
│ id              │──┐
│ name            │  │
│ slug            │  │  (Many-to-Many via pivot)
└─────────────────┘  │
                     │
┌─────────────────┐  │
│   PRODUCTS      │<─┘
│─────────────────│
│ id              │──┐
│ vendor_id       │  │
│ name            │  │
│ slug            │  │  (One-to-Many)
│ price           │  │
│ stock_quantity  │  ├──────> ORDER_ITEMS
└─────────────────┘  │
                     ├──────> REVIEWS
                     │
┌─────────────────┐  │
│    ORDERS       │<─┘
│─────────────────│
│ id              │──┐
│ user_id         │  │
│ order_number    │  │  (One-to-Many)
│ total_amount    │  │
│ status          │  └──────> ORDER_ITEMS
└─────────────────┘

┌─────────────────┐
│  ORDER_ITEMS    │
│─────────────────│
│ id              │
│ order_id        │
│ product_id      │
│ quantity        │
│ price           │
└─────────────────┘

┌─────────────────┐
│    REVIEWS      │
│─────────────────│
│ id              │
│ product_id      │
│ user_id         │
│ rating          │
│ comment         │
└─────────────────┘
```

---

## 📝 Step 1: Modify Users Migration

Laravel includes a default users migration. We'll modify it.

### Find Existing Migration

```bash
# List migrations
ls database/migrations/

# You'll see: xxxx_xx_xx_xxxxxx_create_users_table.php
```

### Edit Users Migration

Open `database/migrations/xxxx_create_users_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Custom fields for our e-commerce platform
            $table->enum('role', ['admin', 'vendor', 'customer'])->default('customer');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes
            $table->index('role');
            $table->index('status');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
```

**Key Additions:**
- `role` enum: admin, vendor, customer
- `status` enum: active, inactive, suspended
- `phone` and `address` fields
- Indexes on role and status for faster queries

---

## 📝 Step 2: Create Categories Migration

```bash
php artisan make:migration create_categories_table
```

Edit the created file `database/migrations/xxxx_create_categories_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

---

## 📝 Step 3: Create Products Migration

```bash
php artisan make:migration create_products_table
```

Edit `database/migrations/xxxx_create_products_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('vendor_id');
            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('created_at');
        });

        // Pivot table for many-to-many relationship with categories
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Unique composite index
            $table->unique(['category_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
    }
};
```

**Key Features:**
- `vendor_id` foreign key to users table
- `slug` for SEO-friendly URLs
- `price` and `compare_price` (for showing discounts)
- `stock_quantity` for inventory management
- `status` enum: draft, published, archived
- `softDeletes()` for soft deletion
- Pivot table `category_product` for many-to-many

---

## 📝 Step 4: Create Orders Migration

```bash
php artisan make:migration create_orders_table
```

Edit `database/migrations/xxxx_create_orders_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', [
                'pending',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

---

## 📝 Step 5: Create Order Items Migration

```bash
php artisan make:migration create_order_items_table
```

Edit `database/migrations/xxxx_create_order_items_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->json('product_snapshot')->nullable(); // Store product details at time of order
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
```

**Why `product_snapshot`?**
- Stores product details (name, price, image) at the time of order
- Ensures order history is accurate even if product is updated/deleted

---

## 📝 Step 6: Create Reviews Migration

```bash
php artisan make:migration create_reviews_table
```

Edit `database/migrations/xxxx_create_reviews_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->json('images')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->index('user_id');
            $table->index('rating');
            $table->index('is_approved');
            
            // Unique constraint: one review per user per product
            $table->unique(['product_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
```

**Key Features:**
- `rating` field (1-5 stars)
- `is_verified_purchase` flag
- `is_approved` for moderation
- Unique constraint: one review per user per product

---

## 🏃 Step 7: Run Migrations

```bash
# Run all migrations
php artisan migrate

# If you need to reset
php artisan migrate:fresh
```

Expected output:
```
   INFO  Preparing database.

  Creating migration table ................................................ 5ms DONE

   INFO  Running migrations.

  xxxx_create_users_table ............................................ 10ms DONE
  xxxx_create_categories_table ....................................... 5ms DONE
  xxxx_create_products_table ........................................ 15ms DONE
  xxxx_create_orders_table ........................................... 8ms DONE
  xxxx_create_order_items_table ...................................... 6ms DONE
  xxxx_create_reviews_table .......................................... 7ms DONE
```

### Verify Tables Created

```bash
php artisan db:show
```

Or connect to database:
```sql
SHOW TABLES;

-- You should see:
-- categories
-- category_product
-- migrations
-- orders
-- order_items
-- password_reset_tokens
-- products
-- reviews
-- sessions
-- users
```

---

## 🎨 Step 8: Create Models

### Create Category Model

```bash
php artisan make:model Category
```

Edit `app/Models/Category.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get products in this category
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product')
                    ->withTimestamps();
    }
}
```

### Create Product Model

```bash
php artisan make:model Product
```

Edit `app/Models/Product.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'name',
        'slug',
        'description',
        'long_description',
        'price',
        'compare_price',
        'stock_quantity',
        'sku',
        'images',
        'status',
        'is_featured',
        'weight',
        'meta_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'images' => 'array',
        'is_featured' => 'boolean',
        'weight' => 'decimal:2',
        'meta_data' => 'array',
    ];

    /**
     * Get the vendor (user) who owns the product
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get categories for this product
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')
                    ->withTimestamps();
    }

    /**
     * Get reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Check if product is published
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }
}
```

### Create Order Model

```bash
php artisan make:model Order
```

Edit `app/Models/Order.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'shipping_fee',
        'discount',
        'total_amount',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'notes',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Check if order is completed
     */
    public function isCompleted()
    {
        return $this->status === 'delivered';
    }
}
```

### Create OrderItem Model

```bash
php artisan make:model OrderItem
```

Edit `app/Models/OrderItem.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'product_snapshot',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'product_snapshot' => 'array',
    ];

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

### Create Review Model

```bash
php artisan make:model Review
```

Edit `app/Models/Review.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'is_verified_purchase',
        'is_approved',
        'images',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Update User Model with Relationships

Edit `app/Models/User.php` and add these methods:

```php
/**
 * Get products owned by vendor
 */
public function products()
{
    return $this->hasMany(Product::class, 'vendor_id');
}

/**
 * Get orders placed by user
 */
public function orders()
{
    return $this->hasMany(Order::class);
}

/**
 * Get reviews written by user
 */
public function reviews()
{
    return $this->hasMany(Review::class);
}

/**
 * Check if user is admin
 */
public function isAdmin()
{
    return $this->role === 'admin';
}

/**
 * Check if user is vendor
 */
public function isVendor()
{
    return $this->role === 'vendor';
}

/**
 * Check if user is customer
 */
public function isCustomer()
{
    return $this->role === 'customer';
}

/**
 * Check if user is active
 */
public function isActive()
{
    return $this->status === 'active';
}
```

---

## ✅ Step 9: Test Models and Relationships

### Using Tinker

```bash
php artisan tinker
```

```php
// Test User creation
$user = App\Models\User::create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
]);

// Test Category creation
$category = App\Models\Category::create([
    'name' => 'Electronics',
    'slug' => 'electronics',
    'description' => 'Electronic products',
]);

// Test Product creation
$product = App\Models\Product::create([
    'vendor_id' => $user->id,
    'name' => 'Test Product',
    'slug' => 'test-product',
    'description' => 'A test product',
    'price' => 99.99,
    'stock_quantity' => 100,
    'status' => 'published',
]);

// Test relationship
$product->categories()->attach($category->id);

// Verify
$product->categories; // Should return collection with Electronics category
$category->products; // Should return collection with Test Product

// Exit tinker
exit
```

---

## 📊 Relationship Summary

### One-to-Many Relationships:

1. **User → Products** (vendor_id)
   - One vendor can have many products
   ```php
   $user->products; // Get all products by vendor
   $product->vendor; // Get vendor of product
   ```

2. **User → Orders**
   - One user can have many orders
   ```php
   $user->orders; // Get all orders by user
   $order->user; // Get user who placed order
   ```

3. **Order → OrderItems**
   - One order can have many items
   ```php
   $order->items; // Get all items in order
   $orderItem->order; // Get order of item
   ```

4. **Product → Reviews**
   - One product can have many reviews
   ```php
   $product->reviews; // Get all reviews
   $review->product; // Get product of review
   ```

### Many-to-Many Relationships:

1. **Categories ↔ Products**
   - One category can have many products
   - One product can belong to many categories
   ```php
   $category->products; // Get all products in category
   $product->categories; // Get all categories of product
   ```

---

## ✅ Success Checklist

Before moving to Phase 3:

- ✅ 6 migration files created
- ✅ All migrations run successfully
- ✅ 6 Eloquent models created
- ✅ All model relationships defined
- ✅ Model casting configured
- ✅ Helper methods added to models
- ✅ Tested models in Tinker

---

## 🎯 What's Next?

**Next Phase:** [PHASE-03-SEEDS-FACTORIES.md](./PHASE-03-SEEDS-FACTORIES.md)

In Phase 3, you will:
- Create model factories
- Create database seeders
- Generate realistic test data

---

**Phase 2 Completed:** ✅  
**Estimated Time:** 45-60 minutes  
**Difficulty Level:** Intermediate  

---

*Last Updated: December 16, 2024*
