<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function byDepartment(Department $department){
        
        return response()->json($department->categories);
    }
}
