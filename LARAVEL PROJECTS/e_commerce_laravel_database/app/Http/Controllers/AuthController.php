<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Barryvdh\Debugbar\Facades\Debugbar;

class AuthController extends Controller
{
    // Show login page
    public function login()
    {
        return view('auth.login');
    }

    // Handle login
    public function loginPost(Request $request)
    {
        Debugbar::info("Testing Debug");
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug: Check what users exist
        $users = DB::table('users')->get();

        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user in session
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_email', $user->email);
            Session::put('user_role', $user->role);

            // Redirect based on role
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Login successful!');
            } else {
                return redirect('/')->with('success', 'Login successful!');
            }
        }

        return back()->with('error', 'Invalid email or password');
    }

    // Show register page
    public function register()
    {
        return view('auth.register');
    }

    // Handle registration
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    // Check if user is logged in 
    public static function check()
    {
        return Session::has('user_id');
    }

    // Check if user is admin
    public static function isAdmin()
    {
        return Session::get('user_role') == 'admin';
    }

    // Get user ID (helper function)
    public static function userId()
    {
        return Session::get('user_id');
    }
}
