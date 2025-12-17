<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = DB::table('products')->find($productId);
            if ($product) {
                $price = $product->discount_price ?: $product->price;
                $subtotal = $price * $quantity;
                $total += $subtotal;

                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'stock' => $product->stock,
                ];
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add($id)
    {
        $product = DB::table('products')
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$product) {
            return back()->with('error', 'Product not found');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Product added to cart');
    }

    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity', 1);

        if ($quantity < 1) {
            return back()->with('error', 'Quantity must be at least 1');
        }

        $cart = Session::get('cart', []);
        $cart[$id] = $quantity;
        Session::put('cart', $cart);

        return back()->with('success', 'Cart updated');
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart');
    }

    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared');
    }
}