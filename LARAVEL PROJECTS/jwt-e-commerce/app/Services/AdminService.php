<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;

class AdminService
{
    protected $userRepository;
    protected $productRepository;
    protected $orderRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Get dashboard statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_users' => $this->userRepository->getAll()->count(),
            'total_customers' => $this->userRepository->countByRole('customer'),
            'total_vendors' => $this->userRepository->countByRole('vendor'),
            'total_admins' => $this->userRepository->countByRole('admin'),
            'total_products' => $this->productRepository->count(),
            'total_orders' => $this->orderRepository->count(),
        ];
    }

    /**
     * Get users with filters
     */
    public function getUsers(array $filters = [])
    {
        return $this->userRepository->getAll($filters);
    }
}