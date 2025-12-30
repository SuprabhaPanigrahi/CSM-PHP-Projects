<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            // Get token from request
            $token = JWTAuth::parseToken();
            
            // Get payload
            $payload = $token->getPayload();
            
            // Get customer type from JWT payload
            $customerType = $payload->get('customer_type');
            
            // Check if customer type is in allowed roles
            if (!in_array($customerType, $roles)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. Required customer type: ' . implode(', ', $roles)
                ], 403);
            }
            
            // Attach customer type to request for later use
            $request->attributes->set('customer_type', $customerType);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token validation failed: ' . $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}