<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    // Submit form data
    public function submit(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|in:male,female,other',
            'interests' => 'nullable|array',
            'interests.*' => 'string',
            'message' => 'nullable|string',
            'terms_accepted' => 'required|accepted'
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Create form submission
        $submission = FormSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'image_path' => $imagePath,
            'gender' => $validated['gender'],
            'interests' => $validated['interests'] ?? [],
            'message' => $validated['message'],
            'terms_accepted' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully!',
            'data' => $submission
        ], 201);
    }

    // Get all submissions
    public function index()
    {
        $submissions = FormSubmission::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $submissions
        ]);
    }

    // Get single submission
    public function show($id)
    {
        $submission = FormSubmission::find($id);
        
        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $submission
        ]);
    }

    // Delete submission
    public function destroy($id)
    {
        $submission = FormSubmission::find($id);
        
        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }

        // Delete image if exists
        if ($submission->image_path) {
            Storage::disk('public')->delete($submission->image_path);
        }

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Submission deleted successfully'
        ]);
    }
}