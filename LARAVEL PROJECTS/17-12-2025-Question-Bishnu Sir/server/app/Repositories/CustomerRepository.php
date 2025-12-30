<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getCustomerType($customerId)
    {
        $customer = $this->find($customerId);
        return $customer ? $customer->customer_type : null;
    }
}