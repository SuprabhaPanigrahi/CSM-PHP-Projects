<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Display all students
    public function index()
    {
        $students = Student::with('country', 'state')->get();
        return view('students.index', compact('students'));
    }

    // Show create form
    public function create()
    {
        $countries = Country::all();
        return view('students.create', compact('countries'));
    }

    // Get states for selected country (AJAX)
    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->get();
        return response()->json($states);
    }

    // Store new student
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string|max:15',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id'
        ]);

        // Handle Image Upload
        $imageName = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/students'), $imageName);
        }

        // Create Student
        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_image' => $imageName,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id
        ]);

        return redirect('/students')->with('success', 'Student created successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $countries = Country::all();
        $states = State::where('country_id', $student->country_id)->get();
        
        return view('students.edit', compact('student', 'countries', 'states'));
    }

    // Update student
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'required|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id'
        ]);

        $imageName = $student->profile_image;

        // Update image if new one is uploaded
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($student->profile_image && file_exists(public_path('images/students/' . $student->profile_image))) {
                unlink(public_path('images/students/' . $student->profile_image));
            }
            
            // Upload new image
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/students'), $imageName);
        }

        // Update Student
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_image' => $imageName,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id
        ]);

        return redirect('/students')->with('success', 'Student updated successfully!');
    }

    // Delete student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        
        // Delete image if exists
        if ($student->profile_image && file_exists(public_path('images/students/' . $student->profile_image))) {
            unlink(public_path('images/students/' . $student->profile_image));
        }
        
        $student->delete();
        
        return redirect('/students')->with('success', 'Student deleted successfully!');
    }
}