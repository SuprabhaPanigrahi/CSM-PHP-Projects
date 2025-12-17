<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Get active sliders
        $sliders = DB::table('sliders')
            ->where('status', 1)
            ->get();

        // Get featured products
        $featured = DB::table('products')
            ->where('status', 1)
            ->where('featured', 1)
            ->limit(8)
            ->get();

        // Get new products
        $new = DB::table('products')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get categories
        $categories = DB::table('categories')
            ->where('status', 1)
            ->get();

        return view('home', compact('sliders', 'featured', 'new', 'categories'));
    }
}