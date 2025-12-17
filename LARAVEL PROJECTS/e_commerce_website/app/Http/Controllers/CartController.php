<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // Get cart items from cookie
    private function getCart()
    {
        $cartCookie = request()->cookie('cart', '[]');
        return json_decode($cartCookie, true);
    }

    // Save cart to cookie
    private function saveCart($cart)
    {
        return cookie('cart', json_encode($cart), 60 * 24 * 30); // 30 days
    }

    // Show cart page
    public function index()
    {
        $cart = $this->getCart();
        $total = 0;
        $itemsCount = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $itemsCount += $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total', 'itemsCount'));
    }

    // Add item to cart
    public function add(Request $request, $id)
    {
        $cart = $this->getCart();
        
        // Check if product already in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $id) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        
        // If not found, add new item
        if (!$found) {
            $cart[] = [
                'id' => $id,
                'name' => $request->input('name', 'Product ' . $id),
                'price' => (float) $request->input('price', 0),
                'image' => $request->input('image', ''),
                'quantity' => 1
            ];
        }
        
        $cookie = $this->saveCart($cart);
        
        // Calculate total items for response
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
        }
        
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'count' => $totalItems
            ])->withCookie($cookie);
        }
        
        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', 'Product added to cart!');
    }

    // Remove item from cart
    public function remove($id)
    {
        $cart = $this->getCart();
        
        $cart = array_values(array_filter($cart, function($item) use ($id) {
            return $item['id'] != $id;
        }));
        
        $cookie = $this->saveCart($cart);
        
        return redirect()->route('cart.index')
            ->withCookie($cookie)
            ->with('success', 'Product removed from cart!');
    }

    // Update cart quantity
    public function update(Request $request, $id)
    {
        $cart = $this->getCart();
        $quantity = $request->input('quantity', 1);
        
        foreach ($cart as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] = max(1, (int) $quantity);
                break;
            }
        }
        
        $cookie = $this->saveCart($cart);
        
        return redirect()->route('cart.index')
            ->withCookie($cookie)
            ->with('success', 'Cart updated!');
    }

    // Clear entire cart
    public function clear()
    {
        $cookie = cookie('cart', '[]', -1); // Expire cookie
        
        return redirect()->route('cart.index')
            ->withCookie($cookie)
            ->with('success', 'Cart cleared!');
    }

    // Get cart count (for AJAX/API)
    public function count()
    {
        $cart = $this->getCart();
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return response()->json(['count' => $count]);
    }
}