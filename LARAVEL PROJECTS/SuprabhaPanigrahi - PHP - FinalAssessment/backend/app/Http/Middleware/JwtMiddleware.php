<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JwtService;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Extract token
        $token = $this->jwtService->extractToken($request);
        
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token not provided'
            ], 401);
        }

        // Validate token
        $decoded = $this->jwtService->validateToken($token);
        
        if (isset($decoded['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $decoded['error']
            ], 401);
        }

        // Store user info in request for controller access
        $request->merge([
            'auth_user' => [
                'id' => $decoded['user_id'],
                'username' => $decoded['username'],
                'role' => $decoded['role']
            ]
        ]);

        // Check if role is allowed
        if (!empty($roles) && count($roles) > 0) {
            $userRole = $decoded['role'];
            
            $allowedRoles = $roles;
            
            if (!in_array($userRole, $allowedRoles)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient permissions. Required roles: ' . implode(', ', $allowedRoles)
                ], 403);
            }
        }

        return $next($request);
    }
}