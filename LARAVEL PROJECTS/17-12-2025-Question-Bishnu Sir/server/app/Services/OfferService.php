<?php

namespace App\Services;

use App\Repositories\OfferRepository;

class OfferService
{
    protected $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getAllOffers()
    {
        return $this->offerRepository->all();
    }
}