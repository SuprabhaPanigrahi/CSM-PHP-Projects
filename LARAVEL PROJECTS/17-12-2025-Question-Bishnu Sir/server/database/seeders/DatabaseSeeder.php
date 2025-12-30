<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call(MenuSeeder::class);
        // Create offers
        $offers = [
            ['percentage' => 10.00, 'details' => '10% discount on all products'],
            ['percentage' => 15.00, 'details' => '15% discount on electronics'],
            ['percentage' => 20.00, 'details' => '20% discount on clothing'],
            ['percentage' => 25.00, 'details' => '25% discount on furniture'],
            ['percentage' => 30.00, 'details' => '30% discount on seasonal items'],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }

        // Create products
        $products = [
            ['name' => 'Laptop', 'qty' => 50, 'rate' => 50000.00, 'offer_id' => 2],
            ['name' => 'Smartphone', 'qty' => 100, 'rate' => 20000.00, 'offer_id' => 2],
            ['name' => 'T-Shirt', 'qty' => 200, 'rate' => 500.00, 'offer_id' => 3],
            ['name' => 'Jeans', 'qty' => 150, 'rate' => 1500.00, 'offer_id' => 3],
            ['name' => 'Sofa', 'qty' => 20, 'rate' => 25000.00, 'offer_id' => 4],
            ['name' => 'Table', 'qty' => 30, 'rate' => 8000.00, 'offer_id' => 4],
            ['name' => 'Winter Jacket', 'qty' => 60, 'rate' => 3000.00, 'offer_id' => 5],
            ['name' => 'Air Conditioner', 'qty' => 40, 'rate' => 35000.00, 'offer_id' => 5],
            ['name' => 'Books', 'qty' => 500, 'rate' => 300.00, 'offer_id' => 1],
            ['name' => 'Stationery', 'qty' => 1000, 'rate' => 100.00, 'offer_id' => 1],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create sample customers
        \App\Models\Customer::create([
            'customer_name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'address' => '123 Main St, City',
            'phone_number' => '1234567890',
            'customer_type' => 'silver'
        ]);

        \App\Models\Customer::create([
            'customer_name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'address' => '456 Oak Ave, Town',
            'phone_number' => '0987654321',
            'customer_type' => 'gold'
        ]);

        \App\Models\Customer::create([
            'customer_name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'address' => '789 Pine Rd, Village',
            'phone_number' => '1122334455',
            'customer_type' => 'diamond'
        ]);
    }
}
