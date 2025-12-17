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