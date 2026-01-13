<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File as FileFacade;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitFileController extends Controller
{
    public function store(Request $request, $visitId)
    {
        // 1. Check visit exists
        $visit = Visit::findOrFail($visitId);

        // 2. Validate request
        $request->validate(
            [
                'files' => 'required|array|min:1|max:5',
                'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ],
            [
                'files.required' => 'Please upload at least one file.',
                'files.array' => 'Files must be sent as an array.',
                'files.*.required' => 'Each file is required.',
                'files.*.file' => 'Each upload must be a valid file.',
                'files.max' => 'You can upload a maximum of 5 files at a time.',
                'files.*.mimes' => 'Only PDF, JPG, JPEG, and PNG files are allowed.',
                'files.*.max' => 'Each file must not exceed 5MB.',
            ]
        );

        $uploadPath = public_path('uploads');

        // 3. Create uploads folder if not exists
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $savedFiles = [];

        // 4. Loop through files
        foreach ($request->file('files') as $uploadedFile) {

            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $fileSize = $uploadedFile->getSize();

            $storedName = Str::uuid() . '.' . $extension;

            $uploadedFile->move($uploadPath, $storedName);

            $file = File::create([
                'visit_id' => $visit->id,
                'original_file_name' => $originalName,
                'stored_file_name' => $storedName,
                'file_path' => 'uploads/' . $storedName,
                'file_size' => $fileSize,
                'file_type' => $extension,
                'uploaded_by' => auth()->id() ?? 1,
                'uploaded_at' => now(),
            ]);

            // ✅ THIS WAS MISSING
            $savedFiles[] = [
                'id' => $file->id,
                'originalName' => $file->original_file_name,
                'storedName' => $file->stored_file_name,
                'filePath' => $file->file_path,
                'fileSize' => $file->file_size,
            ];
        }


        // 7. Return response
        return response()->json([
            'message' => 'Files uploaded successfully',
            'files' => $savedFiles
        ], 201);
    }

    public function index($visitId)
    {
        $visit = Visit::findOrFail($visitId);

        $files = $visit->files()
            ->with('uploadedBy:id,name')
            ->orderBy('uploaded_at', 'desc')
            ->get()
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'original_name' => $file->original_file_name,
                    'file_size' => $file->file_size,
                    'uploaded_at' => $file->uploaded_at->toDateTimeString(),
                    'uploaded_by' => $file->uploadedBy->name ?? 'System',
                ];
            });

        return response()->json([
            'visit_id' => $visit->id,
            'total_files' => $files->count(),
            'files' => $files
        ], 200);
    }

    public function download($fileid)
    {
        $file = File::findorFail($fileid);
        $filepath = public_path($file->file_path);

        if (!FileFacade::exists($filepath)) {
            return response()->json([
                'message' => 'File not found on the serve'
            ], 404);
        }

        return Response::download(
            $filepath,
            $file->original_file_name,
            [
                'Content-Type' => mime_content_type($filepath),
                'Content_Disposition' => 'attachment; filename="' . $file->original_file_name
                    . '"'
            ]
        );
    }

    public function visitSummary()
    {
        $visits = Visit::with([
            'patient:id,name,age',
            'tests:id,test_name',
            'createdBy:id,name'
        ])
            ->withCount('files')
            ->orderBy('visit_date', 'desc')
            ->get();

        $summary = $visits->map(function ($visit) {
            return [
                'patient_name' => $visit->patient->name,
                'patient_age' => $visit->patient->age,
                'visit_date' => $visit->visit_date,
                'selected_tests' => $visit->tests->pluck('name')->values(),
                'file_count' => $visit->files_count,
                'created_by' => $visit->createdBy->name ?? 'System',
            ];
        });

        return response()->json($summary, 200);
    }
}
