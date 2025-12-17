<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    /**
     * Get paginated orders
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find order by ID
     */
    public function findById(int $id): ?Order;

    /**
     * Find order by order number
     */
    public function findByOrderNumber(string $orderNumber): ?Order;

    /**
     * Create new order
     */
    public function create(array $data): Order;

    /**
     * Update order
     */
    public function update(int $id, array $data): Order;

    /**
     * Delete order
     */
    public function delete(int $id): bool;

    /**
     * Get orders by user
     */
    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator;

    /**
     * Get orders by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status): Order;

    /**
     * Count orders
     */
    public function count(): int;
}