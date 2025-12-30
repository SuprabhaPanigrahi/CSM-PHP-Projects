<?php

namespace App\Repositories;

use App\Models\Purchase;
use Carbon\Carbon;

class PurchaseRepository extends BaseRepository
{
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }

    public function createPurchase($customerId, $productId, $qty, $discountedPrice)
    {
        $totalValue = $qty * $discountedPrice;

        return $this->create([
            'date' => Carbon::now(),
            'customer_id' => $customerId,
            'product_id' => $productId,
            'qty' => $qty,
            'total_value' => $totalValue
        ]);
    }

    public function getCustomerPurchases($customerId)
    {
        return $this->model->with('product')
            ->where('customer_id', $customerId)
            ->get();
    }
}