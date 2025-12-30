<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get products with filters
     */
    public function getProducts(array $filters = [], int $perPage = 15)
    {
        return $this->productRepository->getPaginated($filters, $perPage);
    }

    /**
     * Get single product by ID
     */
    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create new product
     */
    public function createProduct(array $data, User $user): Product
    {
        // Business Rule: Only vendors and admins can create products
        if (!in_array($user->role, ['vendor', 'admin'])) {
            throw new \Exception('Only vendors and admins can create products');
        }

        // Set vendor_id
        $data['vendor_id'] = $user->id;

        // Generate unique slug
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        // Set default status
        $data['status'] = $data['status'] ?? 'draft';

        // Create product
        $product = $this->productRepository->create($data);

        // Sync categories if provided
        if (isset($data['categories']) && is_array($data['categories'])) {
            $this->productRepository->syncCategories($product->id, $data['categories']);
        }

        return $this->productRepository->findById($product->id);
    }

    /**
     * Update product
     */
    public function updateProduct(int $id, array $data, User $user): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyProduct($product, $user)) {
            throw new \Exception('Unauthorized to modify this product');
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
        }

        // Update product
        $product = $this->productRepository->update($id, $data);

        // Sync categories if provided
        if (isset($data['categories']) && is_array($data['categories'])) {
            $this->productRepository->syncCategories($id, $data['categories']);
        }

        return $this->productRepository->findById($id);
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id, User $user): bool
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyProduct($product, $user)) {
            throw new \Exception('Unauthorized to delete this product');
        }

        return $this->productRepository->delete($id);
    }

    /**
     * Get vendor's products
     */
    public function getVendorProducts(int $vendorId, array $filters = [], int $perPage = 15)
    {
        return $this->productRepository->getByVendor($vendorId, $filters, $perPage);
    }

    /**
     * Update product stock
     */
    public function updateStock(int $productId, int $quantity): void
    {
        $this->productRepository->updateStock($productId, $quantity);
    }

    /**
     * Decrease product stock
     */
    public function decreaseStock(int $productId, int $quantity): void
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        if ($product->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for {$product->name}");
        }

        $this->productRepository->decrementStock($productId, $quantity);
    }

    /**
     * Check if user can modify product
     */
    protected function canModifyProduct(Product $product, User $user): bool
    {
        // Admin can modify any product
        if ($user->role === 'admin') {
            return true;
        }

        // Vendor can only modify their own products
        if ($user->role === 'vendor' && $product->vendor_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Generate unique slug
     */
    protected function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            // Check if slug exists
            $exists = Product::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists();

            if (!$exists) {
                break;
            }

            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}