<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAllWithOffers();
    }

    public function getProductWithDiscount($productId, $customerType)
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            return null;
        }

        $discountedPrice = $this->productRepository->getDiscountedPrice($productId, $customerType);
        
        return [
            'product' => $product,
            'original_price' => $product->rate,
            'discounted_price' => $discountedPrice,
            'has_discount' => $customerType === 'diamond' && $product->offer,
            'discount_percentage' => $customerType === 'diamond' && $product->offer 
                ? $product->offer->percentage 
                : 0
        ];
    }
}