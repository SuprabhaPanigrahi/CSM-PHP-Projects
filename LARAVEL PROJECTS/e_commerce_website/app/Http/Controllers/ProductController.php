<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Define categories
    private $categories = [
        'Electronics',
        'Fashion', 
        'Consumables',
        'Home & Kitchen',
        'Books',
        'Sports'
    ];
    
    // Number of products per page
    private $perPage = 8;
    
    // Get all products from session with default values
    private function getProducts()
    {
        $products = session('products', []);
        
        // Ensure all products have all required fields
        $validatedProducts = [];
        foreach ($products as $product) {
            $validatedProducts[] = [
                'id' => $product['id'] ?? time() . rand(100, 999),
                'name' => $product['name'] ?? 'Unknown Product',
                'category' => $product['category'] ?? 'Uncategorized',
                'price' => $product['price'] ?? 0.00,
                'description' => $product['description'] ?? 'No description available',
                'image' => $product['image'] ?? 'default.jpg'
            ];
        }
        
        return $validatedProducts;
    }

    // Save products to session
    private function saveProducts($products)
    {
        session(['products' => $products]);
    }

    // Get all categories
    private function getAllCategories()
    {
        return $this->categories;
    }

    // Show home page with featured products
    public function home(Request $request)
    {
        $products = $this->getProducts();
        $categories = $this->getAllCategories();
        
        // Handle search if search query is present
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $products = array_filter($products, function($product) use ($searchTerm) {
                return str_contains(strtolower($product['name']), $searchTerm) || 
                       str_contains(strtolower($product['description']), $searchTerm) ||
                       str_contains(strtolower($product['category']), $searchTerm);
            });
        }
        
        // Handle pagination
        $currentPage = $request->get('page', 1);
        $totalProducts = count($products);
        $totalPages = ceil($totalProducts / $this->perPage);
        
        // Slice products for current page
        $offset = ($currentPage - 1) * $this->perPage;
        $paginatedProducts = array_slice($products, $offset, $this->perPage);
        
        return view('products.home', compact('paginatedProducts', 'categories', 'products', 
                                             'currentPage', 'totalPages', 'totalProducts'));
    }
    
    // Show products by category
    public function category($categoryName, Request $request)
    {
        $products = $this->getProducts();
        $categories = $this->getAllCategories();
        
        // Filter products by category
        $filteredProducts = array_filter($products, function($product) use ($categoryName) {
            return $product['category'] == $categoryName;
        });
        
        // Handle search within category
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $filteredProducts = array_filter($filteredProducts, function($product) use ($searchTerm) {
                return str_contains(strtolower($product['name']), $searchTerm) || 
                       str_contains(strtolower($product['description']), $searchTerm);
            });
        }
        
        // Handle pagination
        $currentPage = $request->get('page', 1);
        $totalProducts = count($filteredProducts);
        $totalPages = ceil($totalProducts / $this->perPage);
        
        // Slice products for current page
        $offset = ($currentPage - 1) * $this->perPage;
        $paginatedProducts = array_slice($filteredProducts, $offset, $this->perPage);
        
        return view('products.category', compact('products', 'categories', 'paginatedProducts', 
                                                'categoryName', 'currentPage', 'totalPages', 'totalProducts'));
    }

    // Display list of products (admin page)
    public function index(Request $request)
    {
        $products = $this->getProducts();
        $categories = $this->getAllCategories();
        
        // Filter by category if requested
        if ($request->has('category') && $request->category != 'all') {
            $products = array_filter($products, function($product) use ($request) {
                return $product['category'] == $request->category;
            });
        }
        
        // Handle search
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $products = array_filter($products, function($product) use ($searchTerm) {
                return str_contains(strtolower($product['name']), $searchTerm) || 
                       str_contains(strtolower($product['description']), $searchTerm) ||
                       str_contains(strtolower($product['category']), $searchTerm);
            });
        }
        
        // Handle pagination for admin
        $currentPage = $request->get('page', 1);
        $perPageAdmin = 6; // Fewer products per page in admin for better view
        $totalProducts = count($products);
        $totalPages = ceil($totalProducts / $perPageAdmin);
        
        // Slice products for current page
        $offset = ($currentPage - 1) * $perPageAdmin;
        $paginatedProducts = array_slice($products, $offset, $perPageAdmin);
        
        return view('products.index', compact('paginatedProducts', 'categories', 
                                             'currentPage', 'totalPages', 'totalProducts'));
    }

    // Show create form
    public function create()
    {
        $categories = $this->getAllCategories();
        return view('products.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $products = $this->getProducts();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        } else {
            $imagePath = 'default.jpg';
        }

        $newProduct = [
            'id' => time() . rand(100, 999),
            'name' => $request->name,
            'category' => $request->category,
            'price' => (float) $request->price,
            'description' => $request->description,
            'image' => $imagePath
        ];

        $products[] = $newProduct;
        $this->saveProducts($products);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $products = $this->getProducts();
        $categories = $this->getAllCategories();
        $product = null;
        
        foreach ($products as $p) {
            if ($p['id'] == $id) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        return view('products.edit', compact('product', 'categories'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $products = $this->getProducts();
        $updated = false;

        foreach ($products as $key => $product) {
            if ($product['id'] == $id) {
                $products[$key]['name'] = $request->name;
                $products[$key]['category'] = $request->category;
                $products[$key]['price'] = (float) $request->price;
                $products[$key]['description'] = $request->description;

                // Update image if new one is uploaded
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $products[$key]['image'] = $image->storeAs('products', $imageName, 'public');
                }

                $updated = true;
                break;
            }
        }

        if (!$updated) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        $this->saveProducts($products);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy($id)
    {
        $products = $this->getProducts();
        
        $filteredProducts = [];
        foreach ($products as $product) {
            if ($product['id'] != $id) {
                $filteredProducts[] = $product;
            }
        }

        $this->saveProducts($filteredProducts);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}