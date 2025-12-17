<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Admin dashboard
    public function dashboard()
    {
        // Check if user is logged in and is admin
        if (!session('logged_in') || session('user_role') != 'admin') {
            return redirect('/login');
        }

        $products = Product::with('category')->get();
        $categories = Category::all();
        
        return view('admin.dashboard', compact('products', 'categories'));
    }

    // Create category
    public function createCategory(Request $request)
    {
        if (!session('logged_in') || session('user_role') != 'admin') {
            return redirect('/login');
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create($request->only('name'));

        return back()->with('success', 'Category created successfully');
    }

    // Update category
    public function updateCategory(Request $request, $id)
    {
        if (!session('logged_in') || session('user_role') != 'admin') {
            return redirect('/login');
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->only('name'));

        return back()->with('success', 'Category updated successfully');
    }

    // Delete category
    public function deleteCategory($id)
    {
        if (!session('logged_in') || session('user_role') != 'admin') {
            return redirect('/login');
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category deleted successfully');
    }

    // Create product
    public function createProduct(Request $request)
    {
        if (!session('logged_in') || session('user_role') != 'admin') {
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

    // Update product
    public function updateProduct(Request $request, $id)
    {
        if (!session('logged_in') || session('user_role') != 'admin') {
            return redirect('/login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return back()->with('success', 'Product updated successfully');
    }

    // Delete product
    public function deleteProduct($id)
    {
        if (!session('logged_in') || session('user_role') != 'admin') {
            return redirect('/login');
        }

        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully');
    }
}