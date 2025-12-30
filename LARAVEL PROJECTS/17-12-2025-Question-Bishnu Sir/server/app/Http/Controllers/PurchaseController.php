<?php

namespace App\Http\Controllers;

use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,product_id',
            'qty' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customerId = auth()->user()->customer_id;
            $purchase = $this->purchaseService->createPurchase(
                $customerId,
                $request->product_id,
                $request->qty
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase successful',
                'data' => $purchase
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function history()
    {
        try {
            $customerId = auth()->user()->customer_id;
            $purchases = $this->purchaseService->getCustomerPurchases($customerId);
            
            return response()->json([
                'status' => 'success',
                'data' => $purchases
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}