<?php

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    /**
     * Get reviews for a product
     */
    public function getByProduct(int $productId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find review by ID
     */
    public function findById(int $id): ?Review;

    /**
     * Create new review
     */
    public function create(array $data): Review;

    /**
     * Update review
     */
    public function update(int $id, array $data): Review;

    /**
     * Delete review
     */
    public function delete(int $id): bool;

    /**
     * Check if user has reviewed product
     */
    public function userHasReviewed(int $productId, int $userId): bool;

    /**
     * Get average rating for product
     */
    public function getAverageRating(int $productId): float;

    /**
     * Get reviews count for product
     */
    public function getReviewsCount(int $productId): int;
}