<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name'
        ]);
        
        $category = new Category();
        $category->name = $request->input('name');
        $category->save();
        
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }
        
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100|unique:categories,name,' . $id
            ]);
            
            $category = Category::find($id);
            
            if (!$category) {
                return response()->json(['error' => 'Category not found.'], 404);
            }
            
            $category->name = $request->input('name');
            $category->save();
            
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }
        
        // Check if category has products before deleting
        if ($category->products()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete category because it has associated products.'
            ], 400);
        }
        
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}