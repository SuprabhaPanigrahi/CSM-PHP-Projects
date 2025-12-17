# 🌱 PHASE 3: SEEDS & FACTORIES

**Creating Realistic Test Data for Your E-Commerce API**

---

## 📋 What You'll Build

In this phase, you'll create:

- ✅ **Model Factories** for all models
- ✅ **Database Seeders** with realistic data
- ✅ **Test Users** (Admin, Vendors, Customers)
- ✅ **Sample Products** with categories
- ✅ **Sample Orders** with order items
- ✅ **Product Reviews**

---

## 🎯 Why Factories and Seeders?

### Factories
- Generate fake data for testing
- Reusable across tests
- Randomized but controlled data

### Seeders
- Populate database with initial data
- Create consistent test environment
- Essential for development and testing

---

## 🏭 Step 1: Create User Factory

Laravel includes a User factory. Let's enhance it.

### Edit User Factory

Open `database/factories/UserFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password123'),
            'role' => fake()->randomElement(['customer', 'vendor']),
            'status' => 'active',
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a vendor.
     */
    public function vendor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'vendor',
        ]);
    }

    /**
     * Indicate that the user is a customer.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'customer',
        ]);
    }

    /**
     * Indicate that the user account is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the user account is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

**Usage Examples:**
```php
User::factory()->create(); // Random user
User::factory()->admin()->create(); // Admin user
User::factory()->vendor()->create(); // Vendor user
User::factory()->customer()->create(); // Customer user
User::factory()->inactive()->create(); // Inactive user
User::factory()->count(10)->create(); // 10 random users
```

---

## 🏭 Step 2: Create Category Factory

```bash
php artisan make:factory CategoryFactory
```

Edit `database/factories/CategoryFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'image' => fake()->imageUrl(640, 480, 'category'),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
```

---

## 🏭 Step 3: Create Product Factory

```bash
php artisan make:factory ProductFactory
```

Edit `database/factories/ProductFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 10, 500);
        
        return [
            'vendor_id' => User::factory()->vendor(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'long_description' => fake()->paragraphs(3, true),
            'price' => $price,
            'compare_price' => $price * 1.2, // 20% higher
            'stock_quantity' => fake()->numberBetween(0, 200),
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'images' => [
                fake()->imageUrl(640, 480, 'product', true),
                fake()->imageUrl(640, 480, 'product', true),
                fake()->imageUrl(640, 480, 'product', true),
            ],
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'is_featured' => fake()->boolean(20), // 20% chance
            'weight' => fake()->randomFloat(2, 0.1, 10),
            'meta_data' => [
                'brand' => fake()->company(),
                'warranty' => fake()->randomElement(['1 year', '2 years', '3 years']),
                'color' => fake()->colorName(),
            ],
        ];
    }

    /**
     * Indicate that the product is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'stock_quantity' => fake()->numberBetween(10, 200),
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'status' => 'published',
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    /**
     * Indicate that the product is in draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
```

---

## 🏭 Step 4: Create Order Factory

```bash
php artisan make:factory OrderFactory
```

Edit `database/factories/OrderFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 1000);
        $tax = $subtotal * 0.1; // 10% tax
        $shippingFee = fake()->randomFloat(2, 5, 20);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2); // Up to 20% discount
        $totalAmount = $subtotal + $tax + $shippingFee - $discount;
        
        return [
            'user_id' => User::factory()->customer(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'shipping_address' => fake()->address(),
            'billing_address' => fake()->address(),
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'cash_on_delivery']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'payment_status' => 'paid',
            'delivered_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }
}
```

---

## 🏭 Step 5: Create OrderItem Factory

```bash
php artisan make:factory OrderItemFactory
```

Edit `database/factories/OrderItemFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $price = fake()->randomFloat(2, 10, 200);
        $total = $quantity * $price;
        
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory()->published(),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
            'product_snapshot' => [
                'name' => fake()->words(3, true),
                'image' => fake()->imageUrl(200, 200, 'product'),
            ],
        ];
    }
}
```

---

## 🏭 Step 6: Create Review Factory

```bash
php artisan make:factory ReviewFactory
```

Edit `database/factories/ReviewFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory()->published(),
            'user_id' => User::factory()->customer(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'is_verified_purchase' => fake()->boolean(70), // 70% verified
            'is_approved' => fake()->boolean(90), // 90% approved
            'images' => fake()->boolean(30) ? [ // 30% have images
                fake()->imageUrl(640, 480, 'review'),
                fake()->imageUrl(640, 480, 'review'),
            ] : null,
        ];
    }

    /**
     * Indicate that the review is verified purchase.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified_purchase' => true,
        ]);
    }

    /**
     * Indicate that the review is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the review has 5 stars.
     */
    public function fiveStars(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
        ]);
    }
}
```

---

## 🌱 Step 7: Create Database Seeder

Edit `database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '+1234567890',
            'address' => '123 Admin Street, Admin City',
            'email_verified_at' => now(),
        ]);

        echo "✅ Admin created: admin@example.com / password123\n";

        // Create 5 Vendor Users
        $vendors = User::factory()->count(5)->vendor()->create();
        
        // Create specific vendors for testing
        $vendor1 = User::create([
            'name' => 'Vendor One',
            'email' => 'vendor1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'status' => 'active',
            'phone' => '+1234567891',
            'address' => '456 Vendor Street, Vendor City',
            'email_verified_at' => now(),
        ]);

        $vendor2 = User::create([
            'name' => 'Vendor Two',
            'email' => 'vendor2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'status' => 'active',
            'phone' => '+1234567892',
            'address' => '789 Vendor Avenue, Vendor Town',
            'email_verified_at' => now(),
        ]);

        echo "✅ Vendors created (7 total)\n";

        // Create 10 Customer Users
        $customers = User::factory()->count(10)->customer()->create();
        
        // Create specific customer for testing
        $customer1 = User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'active',
            'phone' => '+1234567893',
            'address' => '321 Customer Road, Customer City',
            'email_verified_at' => now(),
        ]);

        echo "✅ Customers created (11 total)\n";

        // Create Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel'],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Books and literature'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home improvement and garden supplies'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and gear'],
            ['name' => 'Toys', 'slug' => 'toys', 'description' => 'Toys and games'],
            ['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Beauty and personal care'],
            ['name' => 'Automotive', 'slug' => 'automotive', 'description' => 'Auto parts and accessories'],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'description' => $categoryData['description'],
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }

        echo "✅ Categories created (8 total)\n";

        $allCategories = Category::all();

        // Create Products for Vendor1
        for ($i = 1; $i <= 15; $i++) {
            $product = Product::create([
                'vendor_id' => $vendor1->id,
                'name' => "Vendor1 Product $i",
                'slug' => "vendor1-product-$i",
                'description' => "Quality product from Vendor One",
                'long_description' => "Detailed description of product $i from Vendor One. This is an excellent product with great features.",
                'price' => rand(1000, 50000) / 100,
                'compare_price' => rand(6000, 70000) / 100,
                'stock_quantity' => rand(10, 200),
                'sku' => 'V1-SKU-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => 'published',
                'is_featured' => $i <= 5, // First 5 are featured
                'weight' => rand(100, 5000) / 100,
                'meta_data' => [
                    'brand' => 'Brand A',
                    'warranty' => '2 years',
                ],
            ]);

            // Attach random categories (1-3 categories per product)
            $randomCategories = $allCategories->random(rand(1, 3));
            $product->categories()->attach($randomCategories);
        }

        // Create Products for Vendor2
        for ($i = 1; $i <= 10; $i++) {
            $product = Product::create([
                'vendor_id' => $vendor2->id,
                'name' => "Vendor2 Product $i",
                'slug' => "vendor2-product-$i",
                'description' => "Premium product from Vendor Two",
                'long_description' => "Detailed description of product $i from Vendor Two. High-quality item with premium features.",
                'price' => rand(1500, 60000) / 100,
                'compare_price' => rand(7000, 80000) / 100,
                'stock_quantity' => rand(5, 150),
                'sku' => 'V2-SKU-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => 'published',
                'is_featured' => $i <= 3,
                'weight' => rand(150, 6000) / 100,
                'meta_data' => [
                    'brand' => 'Brand B',
                    'warranty' => '1 year',
                ],
            ]);

            $randomCategories = $allCategories->random(rand(1, 3));
            $product->categories()->attach($randomCategories);
        }

        // Create additional random products from other vendors
        foreach ($vendors as $vendor) {
            $productCount = rand(3, 8);
            for ($i = 0; $i < $productCount; $i++) {
                $product = Product::factory()->published()->create([
                    'vendor_id' => $vendor->id,
                ]);
                
                $randomCategories = $allCategories->random(rand(1, 2));
                $product->categories()->attach($randomCategories);
            }
        }

        echo "✅ Products created (60+ total)\n";

        // Create Orders
        $allProducts = Product::where('status', 'published')->get();
        
        // Create 20 orders
        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(substr(md5(time() . $i), 0, 10)),
                'status' => ['pending', 'processing', 'shipped', 'delivered', 'cancelled'][rand(0, 4)],
                'subtotal' => 0, // Will calculate
                'tax' => 0,
                'shipping_fee' => rand(500, 2000) / 100,
                'discount' => 0,
                'total_amount' => 0, // Will calculate
                'shipping_address' => $customer->address,
                'billing_address' => $customer->address,
                'payment_method' => ['credit_card', 'paypal', 'cash_on_delivery'][rand(0, 2)],
                'payment_status' => ['pending', 'paid', 'failed'][rand(0, 2)],
            ]);

            // Create 1-5 order items
            $itemCount = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $allProducts->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $total = $quantity * $price;
                $subtotal += $total;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                    'product_snapshot' => [
                        'name' => $product->name,
                        'image' => $product->images[0] ?? null,
                    ],
                ]);
            }

            // Update order totals
            $tax = $subtotal * 0.1;
            $totalAmount = $subtotal + $tax + $order->shipping_fee - $order->discount;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $totalAmount,
            ]);
        }

        echo "✅ Orders created (20 total)\n";

        // Create Reviews
        $reviewCount = 0;
        foreach ($allProducts->take(30) as $product) {
            $reviewersCount = rand(0, 5);
            $reviewers = $customers->random(min($reviewersCount, $customers->count()));

            foreach ($reviewers as $reviewer) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $reviewer->id,
                    'rating' => rand(1, 5),
                    'comment' => "Great product! " . fake()->sentence(),
                    'is_verified_purchase' => rand(0, 1),
                    'is_approved' => true,
                ]);
                $reviewCount++;
            }
        }

        echo "✅ Reviews created ($reviewCount total)\n";

        echo "\n";
        echo "========================================\n";
        echo "  DATABASE SEEDING COMPLETED!\n";
        echo "========================================\n";
        echo "Test Accounts:\n";
        echo "  Admin:     admin@example.com / password123\n";
        echo "  Vendor 1:  vendor1@example.com / password123\n";
        echo "  Vendor 2:  vendor2@example.com / password123\n";
        echo "  Customer:  customer1@example.com / password123\n";
        echo "========================================\n";
    }
}
```

---

## 🏃 Step 8: Run Seeds

### Fresh Migration with Seeding

```bash
php artisan migrate:fresh --seed
```

This will:
1. Drop all tables
2. Re-run all migrations
3. Run the DatabaseSeeder

Expected output:
```
   INFO  Preparing database.

  Dropping all tables ................................................ 5ms DONE

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

========================================
  DATABASE SEEDING COMPLETED!
========================================
Test Accounts:
  Admin:     admin@example.com / password123
  Vendor 1:  vendor1@example.com / password123
  Vendor 2:  vendor2@example.com / password123
  Customer:  customer1@example.com / password123
========================================
```

### Seed Only (Without Migration)

```bash
php artisan db:seed
```

---

## 🧪 Step 9: Verify Seeded Data

### Using Tinker

```bash
php artisan tinker
```

```php
// Check users
User::count(); // Should be 19 (1 admin + 7 vendors + 11 customers)

// Check categories
Category::count(); // Should be 8

// Check products
Product::count(); // Should be 60+

// Check orders
Order::count(); // Should be 20

// Check reviews
Review::count(); // Should be 50+

// Test relationships
$product = Product::first();
$product->vendor; // Should return vendor user
$product->categories; // Should return categories
$product->reviews; // Should return reviews

$user = User::where('role', 'customer')->first();
$user->orders; // Should return orders

// Exit
exit
```

### Using Database Query

```bash
php artisan db:show
```

Or connect to database:
```sql
SELECT 
    'users' as table_name, COUNT(*) as count FROM users
UNION ALL
SELECT 'categories', COUNT(*) FROM categories
UNION ALL
SELECT 'products', COUNT(*) FROM products
UNION ALL
SELECT 'orders', COUNT(*) FROM orders
UNION ALL
SELECT 'order_items', COUNT(*) FROM order_items
UNION ALL
SELECT 'reviews', COUNT(*) FROM reviews;
```

---

## 🎯 Using Factories in Tests

### Example Test File

Create `tests/Feature/ProductTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_create_product()
    {
        // Arrange: Create a vendor using factory
        $vendor = User::factory()->vendor()->create();

        // Act: Create a product
        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
        ]);

        // Assert: Verify product exists
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'vendor_id' => $vendor->id,
        ]);
    }

    public function test_published_products_have_stock()
    {
        // Create published products using factory state
        $products = Product::factory()
            ->count(5)
            ->published()
            ->create();

        // Assert all have stock
        $products->each(function ($product) {
            $this->assertTrue($product->stock_quantity > 0);
            $this->assertEquals('published', $product->status);
        });
    }
}
```

Run tests:
```bash
php artisan test
```

---

## ✅ Success Checklist

Before moving to Phase 4:

- ✅ User factory enhanced with states
- ✅ Category factory created
- ✅ Product factory created with states
- ✅ Order factory created
- ✅ OrderItem factory created
- ✅ Review factory created
- ✅ DatabaseSeeder implemented
- ✅ Seeding completed successfully
- ✅ Test accounts created
- ✅ Realistic data generated
- ✅ Relationships verified

---

## 📊 Summary of Seeded Data

| Resource | Count | Details |
|----------|-------|---------|
| Users | 19 | 1 admin, 7 vendors, 11 customers |
| Categories | 8 | Electronics, Clothing, Books, etc. |
| Products | 60+ | Published products with categories |
| Orders | 20 | Various statuses |
| Order Items | 40+ | Products in orders |
| Reviews | 50+ | 1-5 star ratings |

### Test Credentials

```
Admin:
  Email: admin@example.com
  Password: password123
  Role: admin

Vendor 1:
  Email: vendor1@example.com
  Password: password123
  Role: vendor

Vendor 2:
  Email: vendor2@example.com
  Password: password123
  Role: vendor

Customer 1:
  Email: customer1@example.com
  Password: password123
  Role: customer
```

---

## 🎯 What's Next?

**Next Phase:** [PHASE-04-ROUTES-DEFINITION.md](./PHASE-04-ROUTES-DEFINITION.md)

In Phase 4, you will:
- Define all API routes
- Group routes by resource
- Apply middleware
- Set up route prefixes

---

**Phase 3 Completed:** ✅  
**Estimated Time:** 45-60 minutes  
**Difficulty Level:** Intermediate  

---

*Last Updated: December 16, 2024*
