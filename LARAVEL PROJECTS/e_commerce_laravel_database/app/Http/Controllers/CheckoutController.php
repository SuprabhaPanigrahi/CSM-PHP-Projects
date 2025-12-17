<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Admin access required');
        }

        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as customer_name')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Check if user is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Admin access required');
        }

        $order = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.id', $id)
            ->select('orders.*', 'users.name as customer_name', 'users.email as customer_email')
            ->first();

        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_id', $id)
            ->select('order_items.*', 'products.name as product_name')
            ->get();

        return view('admin.orders.show', compact('order', 'items'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Check if user is admin
        if (!Session::has('user_id') || Session::get('user_role') != 'admin') {
            return redirect('/login')->with('error', 'Admin access required');
        }

        DB::table('orders')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Order status updated');
    }
}