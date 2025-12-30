<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Get paginated products with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find product by ID
     */
    public function findById(int $id): ?Product;

    /**
     * Find product by slug
     */
    public function findBySlug(string $slug): ?Product;

    /**
     * Create new product
     */
    public function create(array $data): Product;

    /**
     * Update product
     */
    public function update(int $id, array $data): Product;

    /**
     * Delete product
     */
    public function delete(int $id): bool;

    /**
     * Get products by vendor
     */
    public function getByVendor(int $vendorId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Sync product categories
     */
    public function syncCategories(int $productId, array $categoryIds): void;

    /**
     * Update stock quantity
     */
    public function updateStock(int $productId, int $quantity): void;

    /**
     * Decrement stock quantity
     */
    public function decrementStock(int $productId, int $quantity): void;

    /**
     * Count products
     */
    public function count(): int;
}