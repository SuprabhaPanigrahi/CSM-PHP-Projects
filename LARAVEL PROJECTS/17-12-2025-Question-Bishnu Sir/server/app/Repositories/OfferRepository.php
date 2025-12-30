<?php

namespace App\Repositories;

use App\Models\Offer;

class OfferRepository extends BaseRepository
{
    public function __construct(Offer $model)
    {
        parent::__construct($model);
    }
}