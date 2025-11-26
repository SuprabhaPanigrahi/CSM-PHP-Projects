<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products', compact('products'));
    }

    public function create()
    {
        return view('product-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Save image
        $path = $request->image->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'image' => $path
        ]);

        return redirect()->route('products')->with('success', 'Product added!');
    }
}
