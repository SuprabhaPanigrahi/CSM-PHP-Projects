<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomerType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!in_array($user->customer_type, $types)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Required customer type: ' . implode(', ', $types)
            ], 403);
        }

        return $next($request);
    }
}