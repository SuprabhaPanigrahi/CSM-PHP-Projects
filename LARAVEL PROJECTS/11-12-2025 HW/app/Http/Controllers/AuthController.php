<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facades\Debugbar;

// Add messages to debugbar
Debugbar::info('User login attempt');   

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user info in session
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'logged_in' => true
            ]);

            // Redirect based on role
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role == 'staff') {
                return redirect('/staff/dashboard');
            } else {
                return redirect('/customer/dashboard');
            }
        }

        // If authentication fails
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Handle logout
    public function logout()
    {
        // Clear all session data
        session()->flush();
        
        return redirect('/login')->with('success', 'Logged out successfully');
    }
}