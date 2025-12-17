<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Get product reviews
     */
    public function index(int $productId, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        try {
            $reviews = $this->reviewService->getProductReviews($productId, $perPage);

            return response()->json([
                'success' => true,
                'data' => $reviews->items(),
                'meta' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'average_rating' => $this->reviewService->getAverageRating($productId),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create review
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $review = $this->reviewService->createReview($request->all(), $productId, $user);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update review
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::user();
            $review = $this->reviewService->updateReview($id, $request->all(), $user);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete review
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $this->reviewService->deleteReview($id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}