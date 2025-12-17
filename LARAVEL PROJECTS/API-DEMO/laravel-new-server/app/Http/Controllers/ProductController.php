<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Add this for file handling

// class Product
// {
//     public $id;
//     public $name;
//     public $price;

//     public function __construct($id, $name, $price)
//     {
//         $this->id = $id;
//         $this->name = $name;
//         $this->price = $price;
//     }
// }

class ProductController extends Controller
{
    public function index()
    {
        // $products = [ 
        // new Product(1, 'Mobile', 10000), 
        // new Product(2, 'Laptop', 50000),   
        // new Product(3, 'Television', 20000),
        // ];
        // return response()->json($products);
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id', // Ensure category exists if provided
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max size
        ]);
        
        $name = $request->input('name');
        $price = $request->input('price');
        $category = $request->input('category_id');
        $description = $request->input('description');
        
        // Handle image upload
        $image = null;
        if ($request->hasFile('image')) {
            // Store the image in storage/app/public/products directory
            $imagePath = $request->file('image')->store('products', 'public');
            $image = $imagePath;
            
            // Alternative: Store in public/uploads directory
            // $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            // $request->file('image')->move(public_path('uploads'), $imageName);
            // $image = 'uploads/' . $imageName;
        }

        // $products = Product::create([
        //     $name,
        //     $price
        // ]);

        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->category_id = $category; // Use category_id to match database column
        $product->description = $description;
        $product->image = $image;
        $product->save();

        // Load category relationship for response (if you want to include category details)
        $product->load('category');

        // return redirect('index');
        return response()->json([
            'message' => 'Created successfully',
            'product' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|nullable|exists:categories,id',
                'description' => 'sometimes|nullable|string',
                'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'existing_image' => 'sometimes|nullable|string', // For keeping existing image
            ]);
            
            $product = Product::find($id);
            if (!$product) {
                // Handle product not found, e.g., redirect with an error message
                return response()->json(['error' => 'Product not found.'], 404);
            }
            
            $name = $request->input('name');
            $price = $request->input('price');
            $category = $request->input('category_id');
            $description = $request->input('description');
            
            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                // Store new image
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            } elseif ($request->has('existing_image') && $request->input('existing_image') !== null) {
                // Keep existing image
                $product->image = $request->input('existing_image');
            } elseif ($request->has('image') && !$request->file('image') && !$request->input('existing_image')) {
                // If image field is present but empty (removing image)
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = null;
            }
            
            $product->name = $name;
            $product->price = $price;
            $product->category_id = $category;
            $product->description = $description;
            $product->save();
            
            // Load category relationship for response
            $product->load('category');
            
            return response()->json([
                'message' => 'Updated successfully',
                'product' => $product
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        
        // Delete associated image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}