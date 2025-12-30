<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientController extends Controller
{
    private $apiBaseUrl = 'http://localhost:8080/api';
    
    public function login()
    {
        return view('auth.login');
    }
    
    public function register()
    {
        return view('auth.register');
    }
    
    public function dashboard()
    {
        if (!session()->has('token')) {
            return redirect()->route('login');
        }
        return view('dashboard');
    }
    
    public function products()
    {
        if (!session()->has('token')) {
            return redirect()->route('login');
        }
        
        // Check access via API
        $response = $this->checkRouteAccess('products');
        if (!$response['success']) {
            return redirect()->route('error.403')->with('error', $response['message']);
        }
        
        return view('products');
    }
    
    public function offers()
    {
        if (!session()->has('token')) {
            return redirect()->route('login');
        }
        
        // Check access via API
        $response = $this->checkRouteAccess('offers');
        if (!$response['success']) {
            return redirect()->route('error.403')->with('error', $response['message']);
        }
        
        return view('offers');
    }
    
    public function purchase()
    {
        if (!session()->has('token')) {
            return redirect()->route('login');
        }
        
        // Check access via API
        $response = $this->checkRouteAccess('purchase');
        if (!$response['success']) {
            return redirect()->route('error.403')->with('error', $response['message']);
        }
        
        return view('purchase');
    }
    
    public function purchaseHistory()
    {
        if (!session()->has('token')) {
            return redirect()->route('login');
        }
        
        // Check access via API
        $response = $this->checkRouteAccess('purchase.history');
        if (!$response['success']) {
            return redirect()->route('error.403')->with('error', $response['message']);
        }
        
        return view('purchase-history');
    }
    
    private function checkRouteAccess($route)
    {
        try {
            $token = session('token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($this->apiBaseUrl . '/check-access', [
                'route' => $route
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'success' && $data['data']['can_access']) {
                    return ['success' => true];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Access denied for this customer type'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to verify access'
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'API connection failed: ' . $e->getMessage()
            ];
        }
    }
    
    public function setSession(Request $request)
    {
        $request->session()->put('token', $request->token);
        $request->session()->put('customer_id', $request->customer['customer_id']);
        $request->session()->put('customer_name', $request->customer['customer_name']);
        $request->session()->put('customer_type', $request->customer['customer_type']);
        $request->session()->put('email', $request->customer['email']);
        
        return response()->json(['status' => 'success']);
    }
    
    public function clearSession(Request $request)
    {
        $request->session()->flush();
        return response()->json(['status' => 'success']);
    }
}