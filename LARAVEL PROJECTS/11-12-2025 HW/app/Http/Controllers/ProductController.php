<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric',
            'category'     => 'required|string',
            'availability' => 'required|string',
            'tags'         => 'array',        // multiple checkboxes
            'tags.*'       => 'string',       // checkbox items
            'images.*'     => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Handle multiple file uploads
        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $uploadedFiles[] = $file->store('products', 'public');
            }
        }

        // Add file list
        $validated['uploaded_files'] = $uploadedFiles;

        // JSON output for result page
        $json = [
            'status'   => 'success',
            'received' => $validated
        ];

        $redirectMessage = "Example message after redirect.";

        return view('products/result', [
            'all'             => $request->all(),
            'json'            => $json,
            'uploadedFiles'   => $uploadedFiles,
            'redirectMessage' => $redirectMessage
        ]);
    }
}
