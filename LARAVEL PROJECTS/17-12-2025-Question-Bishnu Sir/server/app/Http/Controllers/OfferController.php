<?php

namespace App\Http\Controllers;

use App\Services\OfferService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
        $this->middleware('auth:api');
    }

    public function index()
    {
        // Check if user is diamond customer
        if (auth()->user()->customer_type !== 'diamond') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only diamond customers can view offers'
            ], 403);
        }

        try {
            $offers = $this->offerService->getAllOffers();
            
            return response()->json([
                'status' => 'success',
                'data' => $offers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}