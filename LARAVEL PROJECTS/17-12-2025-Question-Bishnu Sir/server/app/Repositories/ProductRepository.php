<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getAllWithOffers()
    {
        return $this->model->with('offer')->get();
    }

    public function getDiscountedPrice($productId, $customerType)
    {
        $product = $this->model->with('offer')->find($productId);
        
        if (!$product) {
            return null;
        }

        $price = $product->rate;

        // Apply discount only for diamond customers if product has an offer
        if ($customerType === 'diamond' && $product->offer) {
            $discount = ($price * $product->offer->percentage) / 100;
            $price = $price - $discount;
        }

        return $price;
    }
}