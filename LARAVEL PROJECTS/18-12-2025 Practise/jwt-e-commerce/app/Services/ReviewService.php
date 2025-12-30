<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ReviewService
{
    protected $reviewRepository;
    protected $productRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get product reviews
     */
    public function getProductReviews(int $productId, int $perPage = 15)
    {
        return $this->reviewRepository->getByProduct($productId, $perPage);
    }

    /**
     * Create new review
     */
    public function createReview(array $data, int $productId, User $user): Review
    {
        // Business Rule: Check if product exists
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Business Rule: User can only review once per product
        if ($this->reviewRepository->userHasReviewed($productId, $user->id)) {
            throw new \Exception('You have already reviewed this product');
        }

        // Business Rule: Rating must be between 1-5
        if ($data['rating'] < 1 || $data['rating'] > 5) {
            throw new \Exception('Rating must be between 1 and 5');
        }

        $data['product_id'] = $productId;
        $data['user_id'] = $user->id;
        $data['is_approved'] = true; // Auto-approve for now
        $data['is_verified_purchase'] = false; // Can be enhanced

        return $this->reviewRepository->create($data);
    }

    /**
     * Update review
     */
    public function updateReview(int $id, array $data, User $user): Review
    {
        $review = $this->reviewRepository->findById($id);

        if (!$review) {
            throw new \Exception('Review not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyReview($review, $user)) {
            throw new \Exception('Unauthorized to modify this review');
        }

        // Validate rating if provided
        if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
            throw new \Exception('Rating must be between 1 and 5');
        }

        return $this->reviewRepository->update($id, $data);
    }

    /**
     * Delete review
     */
    public function deleteReview(int $id, User $user): bool
    {
        $review = $this->reviewRepository->findById($id);

        if (!$review) {
            throw new \Exception('Review not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyReview($review, $user)) {
            throw new \Exception('Unauthorized to delete this review');
        }

        return $this->reviewRepository->delete($id);
    }

    /**
     * Get average rating for product
     */
    public function getAverageRating(int $productId): float
    {
        return $this->reviewRepository->getAverageRating($productId);
    }

    /**
     * Check if user can modify review
     */
    protected function canModifyReview(Review $review, User $user): bool
    {
        // Admin can modify any review
        if ($user->role === 'admin') {
            return true;
        }

        // User can modify their own review
        if ($review->user_id === $user->id) {
            return true;
        }

        return false;
    }
}