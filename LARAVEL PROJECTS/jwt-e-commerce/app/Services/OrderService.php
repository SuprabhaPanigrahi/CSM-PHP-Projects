<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get orders (filtered by user role)
     */
    public function getOrders(User $user, array $filters = [], int $perPage = 15)
    {
        // Business Rule: Customers see only their orders
        if ($user->role === 'customer') {
            $filters['user_id'] = $user->id;
        }

        // Business Rule: Vendors see orders containing their products
        if ($user->role === 'vendor') {
            // This requires complex query - simplified for this example
            $filters['user_id'] = $user->id; // Modify as needed
        }

        // Admin sees all orders (no filter)

        return $this->orderRepository->getPaginated($filters, $perPage);
    }

    /**
     * Get single order
     */
    public function getOrderById(int $id, User $user): ?Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return null;
        }

        // Business Rule: Authorization check
        if (!$this->canViewOrder($order, $user)) {
            throw new \Exception('Unauthorized to view this order');
        }

        return $order;
    }

    /**
     * Create new order
     */
    public function createOrder(array $data, User $user): Order
    {
        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItems = [];

            // Business Logic: Validate and process items
            foreach ($data['items'] as $item) {
                $product = $this->productRepository->findById($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product not found");
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $itemTotal,
                    'product_snapshot' => [
                        'name' => $product->name,
                        'image' => $product->images[0] ?? null,
                    ],
                ];

                // Decrease stock
                $this->productRepository->decrementStock($product->id, $item['quantity']);
            }

            // Create order
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $totalAmount,
                'tax' => $totalAmount * 0.1, // 10% tax
                'shipping_fee' => $data['shipping_fee'] ?? 10,
                'discount' => $data['discount'] ?? 0,
                'total_amount' => $totalAmount + ($totalAmount * 0.1) + ($data['shipping_fee'] ?? 10) - ($data['discount'] ?? 0),
                'shipping_address' => $data['shipping_address'] ?? $user->address,
                'billing_address' => $data['billing_address'] ?? $user->address,
                'payment_method' => $data['payment_method'] ?? 'cash_on_delivery',
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return $this->orderRepository->findById($order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $id, string $status, User $user): Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Business Rule: Authorization check
        if (!$this->canModifyOrder($order, $user)) {
            throw new \Exception('Unauthorized to modify this order');
        }

        // Business Rule: Validate status transition
        if (!$this->validateStatusTransition($order->status, $status)) {
            throw new \Exception("Cannot change status from {$order->status} to {$status}");
        }

        return $this->orderRepository->updateStatus($id, $status);
    }

    /**
     * Cancel order
     */
    public function cancelOrder(int $id, User $user): bool
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Business Rule: Only pending/processing orders can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw new \Exception('Cannot cancel order with status: ' . $order->status);
        }

        // Business Rule: Authorization check
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            throw new \Exception('Unauthorized to cancel this order');
        }

        DB::beginTransaction();

        try {
            // Restore stock
            foreach ($order->items as $item) {
                $product = $this->productRepository->findById($item->product_id);
                if ($product) {
                    $this->productRepository->updateStock(
                        $product->id,
                        $product->stock_quantity + $item->quantity
                    );
                }
            }

            // Update order status
            $this->orderRepository->updateStatus($id, 'cancelled');

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if user can view order
     */
    protected function canViewOrder(Order $order, User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'customer' && $order->user_id === $user->id) {
            return true;
        }

        // Vendors can view orders containing their products
        if ($user->role === 'vendor') {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->vendor_id === $user->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user can modify order
     */
    protected function canModifyOrder(Order $order, User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'vendor') {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->vendor_id === $user->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Validate status transition
     */
    protected function validateStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => ['refunded'],
            'cancelled' => [],
            'refunded' => [],
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(Str::random(10));
    }
}