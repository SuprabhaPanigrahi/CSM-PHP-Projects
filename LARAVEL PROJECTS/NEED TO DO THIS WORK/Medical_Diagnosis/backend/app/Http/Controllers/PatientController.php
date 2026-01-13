<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        // ✅ Validate request
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'age'    => 'required|integer|min:0',
            'mobile' => 'nullable|string|max:20',
            'email'  => 'nullable|email',
            'gender' => 'nullable|string|max:10',
        ]);

        // ✅ Create patient using JWT user
        $patient = Patient::create([
            'name'       => $validated['name'],
            'age'        => $validated['age'],
            'mobile'     => $validated['mobile'] ?? null,
            'email'      => $validated['email'] ?? null,
            'gender'     => $validated['gender'] ?? null,
            'created_by' => auth()->id(), // 👈 FROM JWT
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient created successfully',
            'data'    => $patient
        ], 201);
    }

    public function view(){

        $patients = Patient::all();
        return response()->json($patients);
    }

    public function viewById($id){

        $patient = Patient::find($id);
        if(!$patient){
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $patient
        ]);

    }
}

