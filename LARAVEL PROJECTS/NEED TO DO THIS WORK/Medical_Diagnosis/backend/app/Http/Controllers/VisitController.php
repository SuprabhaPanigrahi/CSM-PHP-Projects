<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'patientId' => 'required|integer|exists:patients,id',
            'visitDate' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $visit = Visit::create([
            'patient_id' => $validated['patientId'],
            'visit_date' => $validated['visitDate'],
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit created successfully',
            'data' => $visit
        ], 201);
    }

    public function indexByPatient($patientId)
    {
        $visits = Visit::with([
            'creator:id,name',
            'tests:id,test_name'
            ])
            ->where('patient_id', $patientId)
            ->where('is_deleted', false)
            ->get()
            ->map(function ($visit) {
                return [
                    'visit_date' => $visit->visit_date,
                    'notes' => $visit->notes,
                    'tests' => $visit->tests->pluck('test_name')->values(),
                    'created_by' => $visit->creator?->name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $visits
        ]);
    }

    public function addTests(Request $request, $visitId)
    {
        // 1. Validate input
        $validated = $request->validate([
            'testIds'   => 'required|array|min:1',
            'testIds.*' => 'integer|exists:tests,id',
        ]);

        // 2. Validate visit exists
        $visit = Visit::find($visitId);

        if (!$visit) {
            return response()->json([
                'success' => false,
                'message' => 'Visit not found'
            ], 404);
        }

        // 3. Attach tests to visit (no duplicates)
        $visit->tests()->syncWithoutDetaching($validated['testIds']);

        return response()->json([
            'success' => true,
            'message' => 'Tests added to visit successfully'
        ], 201);
    }

    public function showFull($id)
    {
        $visit = Visit::with([
            // Patient info
            'patient',

            // Visit creator (staff)
            'createdBy:id,name,email',

            // Tests + category + department
            'tests.category.department',

            // Files + uploader
            'files.uploadedBy:id,name,email',
        ])->find($id);

        if (!$visit) {
            return response()->json([
                'message' => 'Visit not found'
            ], 404);
        }

        return response()->json([
            'visit_id' => $visit->id,

            'visit_info' => [
                'visit_date' => $visit->visit_date,
                'notes'      => $visit->notes,
                'created_at' => $visit->created_at,
                'created_by' => $visit->createdBy,
            ],

            'patient' => $visit->patient,

            'tests' => $visit->tests->map(function ($test) {
                return [
                    'id'        => $test->id,
                    'test_name' => $test->test_name,
                    'category'  => $test->category?->name,
                    'department'=> $test->category?->department?->name,
                ];
            }),

            'files' => $visit->files->map(function ($file) {
                return [
                    'id'             => $file->id,
                    'original_name'  => $file->original_file_name,
                    'file_size'      => $file->file_size,
                    'file_type'      => $file->file_type,
                    'uploaded_at'    => $file->uploaded_at,
                    'uploaded_by'    => $file->uploadedBy,
                ];
            }),
        ]);
    }
}
