<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        // Get data from both tables
        $users = User::with('profile')->get();
        $profiles = Profile::with('user')->get();
        
        return view('users.create', compact('users', 'profiles'));
    }
    
    public function store(Request $request)
    {
        // Simple validation
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);
        
        try {
            // DEBUG: Log the received data
            Log::info('Form Data Received:', $validated);
            
            // Start transaction
            DB::beginTransaction();
            
            // Create user FIRST
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = bcrypt($validated['password']);
            $user->save();
            
            // DEBUG: Log created user
            Log::info('User Created with ID: ' . $user->id);
            
            // Create profile SECOND
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->phone = $validated['phone'] ?? null;
            $profile->address = $validated['address'] ?? null;
            $profile->save();
            
            // DEBUG: Log created profile
            Log::info('Profile Created for User ID: ' . $user->id);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->back()->with('success', 'Data saved successfully!');
            
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            
            // Log the error
            Log::error('Error saving data: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }
}