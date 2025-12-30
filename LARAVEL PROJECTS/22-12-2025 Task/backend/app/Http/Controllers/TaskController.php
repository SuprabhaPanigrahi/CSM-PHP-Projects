<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $tasks = collect();
        
        if ($user->isAdmin()) {
            $tasks = Task::with(['assignedTo', 'createdBy'])->latest()->paginate(10);
        } elseif ($user->isManager()) {
            // Get team member IDs
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            $teamMembers[] = $user->id;
            
            $tasks = Task::with(['assignedTo', 'createdBy'])
                        ->whereIn('assigned_to', $teamMembers)
                        ->orWhereIn('created_by', $teamMembers)
                        ->latest()
                        ->paginate(10);
        } else {
            $tasks = Task::with(['assignedTo', 'createdBy'])
                        ->where('assigned_to', $user->id)
                        ->latest()
                        ->paginate(10);
        }
        
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $assignableUsers = collect();
        
        if ($user->isAdmin()) {
            $assignableUsers = User::where('id', '!=', $user->id)->get();
        } elseif ($user->isManager()) {
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            $assignableUsers = User::whereIn('id', $teamMembers)->get();
        } else {
            abort(403, 'Unauthorized action.');
        }
        
        return view('tasks.create', compact('assignableUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        
        // Check if manager is assigning to their team member
        if ($user->isManager()) {
            $teamMemberIds = $user->teams->flatMap->members->pluck('id')->unique();
            if (!$teamMemberIds->contains($request->assigned_to)) {
                return back()->withErrors(['assigned_to' => 'You can only assign tasks to your team members.']);
            }
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'todo',
            'assigned_to' => $request->assigned_to,
            'created_by' => $user->id,
        ]);

        return redirect()->route('tasks.index')
                        ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Authorization check
        if (!$this->canViewTask($user, $task)) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $user = Auth::user();
        
        if (!$this->canEditTask($user, $task)) {
            abort(403, 'Unauthorized action.');
        }
        
        $assignableUsers = collect();
        if ($user->isAdmin()) {
            $assignableUsers = User::where('id', '!=', $user->id)->get();
        } elseif ($user->isManager()) {
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            $assignableUsers = User::whereIn('id', $teamMembers)->get();
        }
        
        return view('tasks.edit', compact('task', 'assignableUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        if (!$this->canEditTask($user, $task)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date|after:today',
            'status' => 'required|in:todo,in_progress,done',
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Check if manager is assigning to their team member
        if ($user->isManager()) {
            $teamMemberIds = $user->teams->flatMap->members->pluck('id')->unique();
            if (!$teamMemberIds->contains($request->assigned_to)) {
                return back()->withErrors(['assigned_to' => 'You can only assign tasks to your team members.']);
            }
        }

        $task->update($request->all());

        return redirect()->route('tasks.index')
                        ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $task->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $task->delete();

        return redirect()->route('tasks.index')
                        ->with('success', 'Task deleted successfully.');
    }

    /**
     * Helper method to check if user can view task
     */
    private function canViewTask($user, $task)
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isManager()) {
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            $teamMembers[] = $user->id;
            return $teamMembers->contains($task->assigned_to) || 
                   $teamMembers->contains($task->created_by);
        }
        
        return $task->assigned_to === $user->id || 
               $task->created_by === $user->id;
    }

    /**
     * Helper method to check if user can edit task
     */
    private function canEditTask($user, $task)
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isManager()) {
            // Managers can edit tasks they created or tasks assigned to their team
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            return $task->created_by === $user->id || 
                   $teamMembers->contains($task->assigned_to);
        }
        
        // Employees can only edit tasks assigned to them
        return $task->assigned_to === $user->id;
    }
}