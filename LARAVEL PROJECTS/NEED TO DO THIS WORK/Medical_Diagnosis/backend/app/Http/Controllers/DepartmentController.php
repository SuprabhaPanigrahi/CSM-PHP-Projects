<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function departments()
    {
        $departments = Department::all();
        return response()->json($departments);
    }
}

