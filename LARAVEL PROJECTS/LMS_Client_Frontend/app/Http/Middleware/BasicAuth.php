<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        // dd($request->all());
        $username = $request->getUser();
        $password = $request->getPassword();
        
        if (!$username || !$password) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $user = User::where('username', $username)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        // Store user in request for later use
        $request->attributes->set('auth_user', $user);
        
        return $next($request);
    }
}