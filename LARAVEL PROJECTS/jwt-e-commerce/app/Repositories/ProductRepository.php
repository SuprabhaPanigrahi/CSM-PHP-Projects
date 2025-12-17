<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['vendor', 'categories', 'reviews']);

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by vendor
        if (isset($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        // Filter by category
        if (isset($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        // Search by name or description
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Filter by featured
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        // Filter by price range
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->with(['vendor', 'categories', 'reviews'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with(['vendor', 'categories', 'reviews'])
                           ->where('slug', $slug)
                           ->first();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->model->find($id);
        $product->update($data);
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        return $product ? $product->delete() : false;
    }

    public function getByVendor(int $vendorId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $filters['vendor_id'] = $vendorId;
        return $this->getPaginated($filters, $perPage);
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        })->get();
    }

    public function syncCategories(int $productId, array $categoryIds): void
    {
        $product = $this->findById($productId);
        if ($product) {
            $product->categories()->sync($categoryIds);
        }
    }

    public function updateStock(int $productId, int $quantity): void
    {
        $product = $this->findById($productId);
        if ($product) {
            $product->update(['stock_quantity' => $quantity]);
        }
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        $product = $this->findById($productId);
        if ($product && $product->stock_quantity >= $quantity) {
            $product->decrement('stock_quantity', $quantity);
        }
    }

    public function count(): int
    {
        return $this->model->count();
    }
}