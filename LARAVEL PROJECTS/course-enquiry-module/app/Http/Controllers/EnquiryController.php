<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function create(Request $request)
    {
        // Get course name from query parameter
        $course = $request->query('course', '');
        
        return view('enquiry.create', ['prefilledCourse' => $course]);
    }

    public function store(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'course' => 'required|string',
            'message' => 'required|string|min:10'
        ]);

        // Check if client wants JSON
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $validated
            ], 201);
        }

        // For web browsers - store in session and redirect
        session()->flash('enquiry_name', $validated['name']);
        session()->flash('enquiry_course', $validated['course']);
        session()->flash('success', 'Your enquiry was submitted successfully!');

        return redirect()->route('enquiry.thankyou');
    }

    public function thankYou()
    {
        // Get data from session
        $name = session('enquiry_name', 'Guest');
        $course = session('enquiry_course', 'a course');
        
        return view('enquiry.thankyou', [
            'name' => $name,
            'course' => $course
        ]);
    }
}