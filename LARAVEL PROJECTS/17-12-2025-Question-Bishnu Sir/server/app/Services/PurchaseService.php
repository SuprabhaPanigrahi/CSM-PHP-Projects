<?php

namespace App\Services;

use App\Repositories\PurchaseRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;

class PurchaseService
{
    protected $purchaseRepository;
    protected $productRepository;
    protected $customerRepository;

    public function __construct(
        PurchaseRepository $purchaseRepository,
        ProductRepository $productRepository,
        CustomerRepository $customerRepository
    ) {
        $this->purchaseRepository = $purchaseRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
    }

    public function createPurchase($customerId, $productId, $qty)
    {
        $customerType = $this->customerRepository->getCustomerType($customerId);
        
        // Check if customer is eligible (gold or diamond)
        if (!in_array($customerType, ['gold', 'diamond'])) {
            throw new \Exception('Only gold and diamond customers can make purchases');
        }

        $discountedPrice = $this->productRepository->getDiscountedPrice($productId, $customerType);
        
        if ($discountedPrice === null) {
            throw new \Exception('Product not found');
        }

        // Check stock availability
        $product = $this->productRepository->find($productId);
        if ($product->qty < $qty) {
            throw new \Exception('Insufficient stock');
        }

        // Update product quantity
        $product->qty -= $qty;
        $product->save();

        return $this->purchaseRepository->createPurchase($customerId, $productId, $qty, $discountedPrice);
    }

    public function getCustomerPurchases($customerId)
    {
        return $this->purchaseRepository->getCustomerPurchases($customerId);
    }
}