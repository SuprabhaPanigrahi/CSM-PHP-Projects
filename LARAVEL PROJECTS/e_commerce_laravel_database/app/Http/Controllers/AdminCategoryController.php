<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    // Show all categories
    public function index()
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get all categories using Query Builder
        $categories = DB::table('categories')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    // Show create category form
    public function create()
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        return view('admin.categories.create');
    }

    // Store new category
    public function store(Request $request)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('categories', 'public');
        }

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Insert category using Query Builder
        DB::table('categories')->insert([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'status' => $request->has('status') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/categories')->with('success', 'Category created successfully!');
    }

    // Show edit category form
    public function edit($id)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get category by ID
        $category = DB::table('categories')->where('id', $id)->first();

        // Check if category exists
        if (!$category) {
            return redirect('/admin/categories')->with('error', 'Category not found');
        }

        return view('admin.categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, $id)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get category
        $category = DB::table('categories')->where('id', $id)->first();

        if (!$category) {
            return redirect('/admin/categories')->with('error', 'Category not found');
        }

        // Handle image update
        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            // Store new image
            $image = $request->file('image');
            $imagePath = $image->store('categories', 'public');
        }

        // Generate new slug from updated name
        $slug = Str::slug($request->name);

        // Update category using Query Builder
        DB::table('categories')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'slug' => $slug,
                'image' => $imagePath,
                'status' => $request->has('status') ? 1 : 0,
                'updated_at' => now(),
            ]);

        return redirect('/admin/categories')->with('success', 'Category updated successfully!');
    }

    // Delete category
    public function destroy($id)
    {
        // Check if user is logged in and is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Please login as admin');
        }

        // Get category
        $category = DB::table('categories')->where('id', $id)->first();

        if (!$category) {
            return redirect('/admin/categories')->with('error', 'Category not found');
        }

        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Delete category using Query Builder
        DB::table('categories')->where('id', $id)->delete();

        return redirect('/admin/categories')->with('success', 'Category deleted successfully!');
    }
}