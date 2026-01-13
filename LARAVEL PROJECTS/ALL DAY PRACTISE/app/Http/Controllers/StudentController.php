<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StudentController extends Controller
{
    public function create()
    {
        return view('form');
    }

    public function save(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:students,email',
            'course' => 'required'
        ]);

        Student::create($validated);

        return view('form', ['successMessage' => 'Student added successfully!']);
    }

    public function view()
    {
        return view('students');
    }

    // returns JSON
    public function fetch()
    {
        return response()->json(Student::all());
    }
}

