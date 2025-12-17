<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        $user = User::where('username', $credentials['username'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        // Store in session
        session(['user_id' => $user->id]);
        session(['user_role' => $user->role]);
        
        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ]
        ]);
    }
    
    public function logout(Request $request)
    {
        session()->flush();
        return response()->json(['message' => 'Logout successful']);
    }
}