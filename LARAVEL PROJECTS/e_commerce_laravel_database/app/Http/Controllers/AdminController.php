<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get counts using Query Builder
        $totalProducts = DB::table('products')->count();
        $totalCategories = DB::table('categories')->count();
        $totalOrders = DB::table('orders')->count();
        $totalUsers = DB::table('users')->where('role', 'customer')->count();

        // Get recent orders
        $recentOrders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalUsers',
            'recentOrders'
        ));
    }
}