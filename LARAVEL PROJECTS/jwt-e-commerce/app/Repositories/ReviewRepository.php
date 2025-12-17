<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository implements ReviewRepositoryInterface
{
    protected $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function getByProduct(int $productId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')
                           ->where('product_id', $productId)
                           ->where('is_approved', true)
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);
    }

    public function findById(int $id): ?Review
    {
        return $this->model->with(['user', 'product'])->find($id);
    }

    public function create(array $data): Review
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Review
    {
        $review = $this->findById($id);
        $review->update($data);
        return $review->fresh();
    }

    public function delete(int $id): bool
    {
        $review = $this->findById($id);
        return $review ? $review->delete() : false;
    }

    public function userHasReviewed(int $productId, int $userId): bool
    {
        return $this->model->where('product_id', $productId)
                           ->where('user_id', $userId)
                           ->exists();
    }

    public function getAverageRating(int $productId): float
    {
        return (float) $this->model->where('product_id', $productId)
                                   ->where('is_approved', true)
                                   ->avg('rating') ?? 0;
    }

    public function getReviewsCount(int $productId): int
    {
        return $this->model->where('product_id', $productId)
                           ->where('is_approved', true)
                           ->count();
    }
}