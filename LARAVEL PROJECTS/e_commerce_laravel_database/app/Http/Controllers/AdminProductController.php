<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    // Show all products
    public function index()
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get all products with category name using Query Builder
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.*',
                'categories.name as category_name'
            )
            ->orderBy('products.id', 'desc')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    // Show create product form
    public function create()
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get all active categories for dropdown
        $categories = DB::table('categories')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
        }

        // Generate slug from name
        $slug = Str::slug($request->name) . '-' . time();

        // Insert product using Query Builder
        DB::table('products')->insert([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'image' => $imagePath,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'status' => $request->has('status') ? 1 : 0,
            'featured' => $request->has('featured') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/products')->with('success', 'Product created successfully!');
    }

    // Show edit product form
    public function edit($id)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get product by ID
        $product = DB::table('products')->where('id', $id)->first();

        // Check if product exists
        if (!$product) {
            return redirect('/admin/products')->with('error', 'Product not found');
        }

        // Get all active categories for dropdown
        $categories = DB::table('categories')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get product
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return redirect('/admin/products')->with('error', 'Product not found');
        }

        // Handle image update
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            // Store new image
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
        }

        // Generate new slug from updated name
        $slug = Str::slug($request->name) . '-' . time();

        // Update product using Query Builder
        DB::table('products')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'image' => $imagePath,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'status' => $request->has('status') ? 1 : 0,
                'featured' => $request->has('featured') ? 1 : 0,
                'updated_at' => now(),
            ]);

        return redirect('/admin/products')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy($id)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get product
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return redirect('/admin/products')->with('error', 'Product not found');
        }

        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete product using Query Builder
        DB::table('products')->where('id', $id)->delete();

        return redirect('/admin/products')->with('success', 'Product deleted successfully!');
    }
}