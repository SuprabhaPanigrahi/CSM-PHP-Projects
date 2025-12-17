<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Staff dashboard
    public function dashboard()
    {
        // Check if user is logged in and is staff
        if (!session('logged_in') || session('user_role') != 'staff') {
            return redirect('/login');
        }

        $products = Product::with('category')->get();
        $categories = Category::all();
        
        return view('staff.dashboard', compact('products', 'categories'));
    }

    // Create product (staff can only create)
    public function createProduct(Request $request)
    {
        if (!session('logged_in') || session('user_role') != 'staff') {
            return redirect('/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::create($request->all());

        return back()->with('success', 'Product created successfully');
    }
}