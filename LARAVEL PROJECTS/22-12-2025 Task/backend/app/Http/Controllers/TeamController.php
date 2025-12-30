<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $teams = Team::with('manager')->paginate(10);
        } elseif ($user->isManager()) {
            $teams = $user->managedTeams()->with('manager')->paginate(10);
        } else {
            $teams = $user->teams()->with('manager')->paginate(10);
        }
        
        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get employees for team members
        $employees = User::where('role', 'employee')->get();
        $managers = User::where('role', 'manager')->get();
        
        return view('teams.create', compact('employees', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        // If manager is creating team, they can only assign themselves as manager
        if ($user->isManager() && $request->manager_id != $user->id) {
            return back()->withErrors(['manager_id' => 'You can only create teams with yourself as manager.']);
        }

        $team = Team::create([
            'name' => $request->name,
            'manager_id' => $request->manager_id,
        ]);

        // Add members to team
        if ($request->has('members')) {
            $team->members()->attach($request->members);
        }

        return redirect()->route('teams.index')
                        ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!$user->isAdmin() && 
            !$user->managedTeams->contains($team->id) && 
            !$user->teams->contains($team->id)) {
            abort(403, 'Unauthorized action.');
        }
        
        $team->load(['manager', 'members']);
        
        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $team->manager_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $employees = User::where('role', 'employee')->get();
        $managers = User::where('role', 'manager')->get();
        $team->load('members');
        
        return view('teams.edit', compact('team', 'employees', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $team->manager_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        // If manager is updating, they can't change manager
        if ($user->isManager() && $request->manager_id != $user->id) {
            return back()->withErrors(['manager_id' => 'You cannot change the team manager.']);
        }

        $team->update([
            'name' => $request->name,
            'manager_id' => $request->manager_id,
        ]);

        // Sync team members
        if ($request->has('members')) {
            $team->members()->sync($request->members);
        } else {
            $team->members()->detach();
        }

        return redirect()->route('teams.index')
                        ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $team->manager_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $team->delete();

        return redirect()->route('teams.index')
                        ->with('success', 'Team deleted successfully.');
    }
}