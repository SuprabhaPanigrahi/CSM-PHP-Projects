<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;
    protected $userRepository;

    public function __construct(
        AdminService $adminService,
        UserRepositoryInterface $userRepository
    ) {
        $this->adminService = $adminService;
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users
     */
    public function getUsers(Request $request): JsonResponse
    {
        $filters = [
            'role' => $request->input('role'),
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        try {
            $users = $this->adminService->getUsers($filters);

            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $stats = $this->adminService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}