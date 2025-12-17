<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create test users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@test.com',
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer'
        ]);

        // Create categories
        $categories = ['Electronics', 'Clothing', 'Books', 'Home & Garden'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        // Create some products
        Product::create([
            'name' => 'Laptop',
            'description' => 'High-performance laptop',
            'price' => 999.99,
            'category_id' => 1
        ]);

        Product::create([
            'name' => 'T-Shirt',
            'description' => 'Cotton t-shirt',
            'price' => 19.99,
            'category_id' => 2
        ]);
    }
}