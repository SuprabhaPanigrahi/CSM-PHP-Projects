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