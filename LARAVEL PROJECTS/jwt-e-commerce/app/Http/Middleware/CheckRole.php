<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        try {
            // Get authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Check if user has one of the required roles
            if (!in_array($user->role, $roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Required role(s): ' . implode(', ', $roles),
                    'user_role' => $user->role,
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization failed',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}