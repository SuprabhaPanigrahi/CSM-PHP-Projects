<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'items.product']);

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->with(['user', 'items.product'])->find($id);
    }

    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return $this->model->where('order_number', $orderNumber)->first();
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Order
    {
        $order = $this->findById($id);
        $order->update($data);
        return $order->fresh();
    }

    public function delete(int $id): bool
    {
        $order = $this->findById($id);
        return $order ? $order->delete() : false;
    }

    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $filters['user_id'] = $userId;
        return $this->getPaginated($filters);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function updateStatus(int $orderId, string $status): Order
    {
        $order = $this->findById($orderId);
        $order->update(['status' => $status]);
        return $order->fresh();
    }

    public function count(): int
    {
        return $this->model->count();
    }
}