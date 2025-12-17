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