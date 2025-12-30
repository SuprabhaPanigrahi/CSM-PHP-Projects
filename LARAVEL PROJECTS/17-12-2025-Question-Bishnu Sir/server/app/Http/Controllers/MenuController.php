<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenu(Request $request)
    {
        try {
            // Get customer type from JWT token
            $customerType = auth()->user()->customer_type;
            
            // Get menu items for this customer type
            $menus = Menu::getForCustomerType($customerType);
            
            return response()->json([
                'status' => 'success',
                'data' => $menus
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkAccess(Request $request)
    {
        try {
            $route = $request->input('route');
            $customerType = auth()->user()->customer_type;
            
            $canAccess = Menu::canAccess($route, $customerType);
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'can_access' => $canAccess,
                    'customer_type' => $customerType,
                    'route' => $route
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}