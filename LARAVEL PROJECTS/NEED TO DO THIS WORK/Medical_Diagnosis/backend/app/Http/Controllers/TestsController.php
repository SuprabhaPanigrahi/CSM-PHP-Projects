<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Test;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    public function tests(){
        $tests = Test::all();
        return response()->json($tests);
    }

    public function byCategory(Category $category){

        return response()->json($category->tests);
    }
}
