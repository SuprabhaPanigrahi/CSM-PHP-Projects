<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $authUser = $request->input('auth_user');
        
        if (!$authUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        if ($authUser['role'] !== $role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied for this role'
            ], 403);
        }

        return $next($request);
    }
}