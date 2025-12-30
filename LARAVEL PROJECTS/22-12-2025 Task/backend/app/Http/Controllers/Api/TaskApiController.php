<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskApiController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['assignedTo', 'createdBy']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        // Role-based filtering
        if ($user->isAdmin()) {
            // Admin sees all tasks
        } elseif ($user->isManager()) {
            // Manager sees tasks of their team
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            $teamMembers[] = $user->id;
            $query->whereIn('assigned_to', $teamMembers)
                  ->orWhereIn('created_by', $teamMembers);
        } else {
            // Employee sees only their tasks
            $query->where('assigned_to', $user->id);
        }
        
        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $tasks = $query->paginate($request->get('per_page', 10));
        
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Check if manager is assigning to their team member
        if ($user->isManager()) {
            $teamMemberIds = $user->teams->flatMap->members->pluck('id')->unique();
            if (!$teamMemberIds->contains($request->assigned_to)) {
                return response()->json([
                    'message' => 'You can only assign tasks to your team members.'
                ], 422);
            }
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'todo',
            'assigned_to' => $request->assigned_to,
            'created_by' => $user->id,
        ]);

        return new TaskResource($task->load(['assignedTo', 'createdBy']));
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        if (!$this->canViewTask($user, $task)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return new TaskResource($task->load(['assignedTo', 'createdBy']));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        if (!$this->canEditTask($user, $task)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'sometimes|date|after:today',
            'status' => 'sometimes|in:todo,in_progress,done',
            'assigned_to' => 'sometimes|exists:users,id',
        ]);

        // Check if manager is assigning to their team member
        if ($user->isManager() && $request->has('assigned_to')) {
            $teamMemberIds = $user->teams->flatMap->members->pluck('id')->unique();
            if (!$teamMemberIds->contains($request->assigned_to)) {
                return response()->json([
                    'message' => 'You can only assign tasks to your team members.'
                ], 422);
            }
        }

        $task->update($request->all());

        return new TaskResource($task->load(['assignedTo', 'createdBy']));
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $task->created_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Get tasks assigned to current user
     */
    public function myTasks(Request $request)
    {
        $user = Auth::user();
        
        $query = Task::with(['assignedTo', 'createdBy'])
                    ->where('assigned_to', $user->id);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $tasks = $query->paginate($request->get('per_page', 10));
        
        return TaskResource::collection($tasks);
    }

    /**
     * Update task status
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Only assigned user or admin/manager can update status
        if ($task->assigned_to !== $user->id && !$user->isAdmin() && !$user->isManager()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update(['status' => $request->status]);

        return new TaskResource($task->load(['assignedTo', 'createdBy']));
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
            $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
            return $task->created_by === $user->id || 
                   $teamMembers->contains($task->assigned_to);
        }
        
        return $task->assigned_to === $user->id;
    }
}