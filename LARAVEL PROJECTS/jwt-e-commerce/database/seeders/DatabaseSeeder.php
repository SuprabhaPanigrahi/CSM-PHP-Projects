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