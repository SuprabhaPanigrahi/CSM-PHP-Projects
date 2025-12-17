<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Get all products
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    // Get single product
    public function show($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    // Create new product
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category' => 'required|in:Electronics,Clothing,Books,Home,Other',
            'status' => 'required|in:active,inactive,discontinued',
            'features' => 'nullable|array',
            'color' => 'nullable|string',
            'manufacturing_date' => 'nullable|date',
            'image' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'is_featured' => 'boolean',
            'in_stock' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'quantity' => 'sometimes|integer|min:0',
            'category' => 'sometimes|in:Electronics,Clothing,Books,Home,Other',
            'status' => 'sometimes|in:active,inactive,discontinued',
            'features' => 'nullable|array',
            'color' => 'nullable|string',
            'manufacturing_date' => 'nullable|date',
            'image' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'is_featured' => 'boolean',
            'in_stock' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Delete images if they exist
        if ($product->image) {
            Storage::delete('public/products/' . basename($product->image));
        }

        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                Storage::delete('public/products/gallery/' . basename($image));
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    // Upload single image
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $url = asset('storage/' . $path);
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image uploaded'
        ], 400);
    }

    // Upload gallery images
    public function uploadGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $uploadedImages = [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $uploadedImages[] = asset('storage/' . $path);
            }
            
            return response()->json([
                'success' => true,
                'images' => $uploadedImages
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No images uploaded'
        ], 400);
    }
}