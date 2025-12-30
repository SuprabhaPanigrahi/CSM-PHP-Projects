<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    /**
     * Display a listing of users (admin only).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = User::with('roles');
        
        // Apply filters
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        
        $users = $query->paginate($request->get('per_page', 10));
        
        return UserResource::collection($users);
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        return new UserResource($request->user()->load('roles'));
    }

    /**
     * Get users for task assignment (based on role)
     */
    public function assignableUsers(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $users = User::where('id', '!=', $user->id)->get();
        } elseif ($user->isManager()) {
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            $users = User::whereIn('id', $teamMembers)->get();
        } else {
            $users = collect(); // Employees cannot assign tasks
        }
        
        return UserResource::collection($users);
    }
}