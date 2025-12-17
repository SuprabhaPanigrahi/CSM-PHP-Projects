<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->where('status', 1);

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        // Sort
        if ($request->has('sort')) {
            if ($request->sort == 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_high') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort == 'new') {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = DB::table('categories')->where('status', 1)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = DB::table('products')
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$product) {
            return redirect('/products')->with('error', 'Product not found');
        }

        $related = DB::table('products')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->where('status', 1)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    public function category($id)
    {
        $category = DB::table('categories')
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$category) {
            return redirect('/')->with('error', 'Category not found');
        }

        $products = DB::table('products')
            ->where('category_id', $id)
            ->where('status', 1)
            ->paginate(12);

        return view('products.category', compact('category', 'products'));
    }
}
