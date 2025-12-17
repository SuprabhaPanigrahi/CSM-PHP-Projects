<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Customer dashboard - landing page with products
    public function dashboard()
    {
        // Check if user is logged in
        if (!session('logged_in')) {
            return redirect('/login');
        }

        $products = Product::with('category')->get();
        
        return view('customer.dashboard', compact('products'));
    }
}