@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Welcome Message -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Welcome, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ Auth::user()->isAdmin() ? 'Administrator Dashboard' : 
                           (Auth::user()->isManager() ? 'Manager Dashboard' : 'Employee Dashboard') }}
                    </p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <!-- Total Tasks Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Tasks</h3>
                                <p class="text-3xl font-bold text-blue-600">{{ $totalTasks ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- My Tasks Card -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">My Tasks</h3>
                                <p class="text-3xl font-bold text-green-600">{{ $myTasks ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- To Do Card -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">To Do</h3>
                                <p class="text-3xl font-bold text-yellow-600">{{ $todoTasks ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- In Progress Card -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">In Progress</h3>
                                <p class="text-3xl font-bold text-orange-600">{{ $inProgressTasks ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Done Card -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Done</h3>
                                <p class="text-3xl font-bold text-purple-600">{{ $doneTasks ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="flex flex-wrap gap-4">
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                            <a href="{{ route('tasks.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create New Task
                            </a>
                        @endif

                        <a href="{{ route('tasks.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All Tasks
                        </a>

                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                            <a href="{{ route('teams.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-11a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"></path>
                                </svg>
                                Create Team
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <a href="#" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-11a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"></path>
                                </svg>
                                Manage Users
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Teams Section (For Managers and Admins) -->
                @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    @if(isset($teams) && $teams->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Teams</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($teams as $team)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ $team->name }}</h3>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        {{ $team->members_count ?? $team->members->count() }} members
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-3">
                                    Manager: <span class="font-medium">{{ $team->manager->name ?? 'N/A' }}</span>
                                </p>
                                <a href="{{ route('teams.show', $team) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

                <!-- Recent Activity (Placeholder - Will be filled with API) -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <div id="recent-activity" class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <p class="text-gray-500 text-center">Loading recent activity...</p>
                    </div>
                </div>

                <!-- API Test Section (Required by project) -->
                <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">API Integration Test</h2>
                    <p class="text-gray-600 mb-4">This section demonstrates consuming our own API from Blade (as required in the project).</p>
                    
                    <div class="space-y-4">
                        <div>
                            <button onclick="loadMyTasks()" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                                Load My Tasks via API
                            </button>
                            <div id="my-tasks-result" class="mt-2"></div>
                        </div>

                        <div>
                            <button onclick="loadTaskStats()" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                                Load Task Statistics via API
                            </button>
                            <div id="task-stats-result" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for API Calls -->
<script>
// Get CSRF token for API calls
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Load user's tasks via API
function loadMyTasks() {
    const resultDiv = document.getElementById('my-tasks-result');
    resultDiv.innerHTML = '<p class="text-gray-500">Loading...</p>';

    fetch('/api/v1/my-tasks', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken(),
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.data && data.data.length > 0) {
            let html = '<div class="mt-2 space-y-2">';
            data.data.forEach(task => {
                html += `
                    <div class="p-3 bg-white border border-gray-200 rounded">
                        <div class="flex justify-between">
                            <strong>${task.title}</strong>
                            <span class="px-2 py-1 text-xs rounded ${getStatusColor(task.status)}">
                                ${task.status_display}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Due: ${task.due_date_formatted}</p>
                    </div>
                `;
            });
            html += '</div>';
            resultDiv.innerHTML = html;
        } else {
            resultDiv.innerHTML = '<p class="text-gray-500">No tasks found.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = '<p class="text-red-500">Error loading tasks. Please try again.</p>';
    });
}

// Load task statistics via API
function loadTaskStats() {
    const resultDiv = document.getElementById('task-stats-result');
    resultDiv.innerHTML = '<p class="text-gray-500">Loading...</p>';

    fetch('/api/v1/tasks', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken(),
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        const total = data.meta?.total || data.data?.length || 0;
        
        // Group by status
        const byStatus = {
            todo: 0,
            in_progress: 0,
            done: 0
        };
        
        if (data.data) {
            data.data.forEach(task => {
                if (byStatus.hasOwnProperty(task.status)) {
                    byStatus[task.status]++;
                }
            });
        }

        resultDiv.innerHTML = `
            <div class="grid grid-cols-3 gap-4 mt-2">
                <div class="text-center p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <div class="text-2xl font-bold text-yellow-600">${byStatus.todo}</div>
                    <div class="text-sm text-gray-600">To Do</div>
                </div>
                <div class="text-center p-3 bg-orange-50 border border-orange-200 rounded">
                    <div class="text-2xl font-bold text-orange-600">${byStatus.in_progress}</div>
                    <div class="text-sm text-gray-600">In Progress</div>
                </div>
                <div class="text-center p-3 bg-green-50 border border-green-200 rounded">
                    <div class="text-2xl font-bold text-green-600">${byStatus.done}</div>
                    <div class="text-sm text-gray-600">Done</div>
                </div>
            </div>
            <p class="text-center mt-2 text-gray-600">Total Tasks: ${total}</p>
        `;
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = '<p class="text-red-500">Error loading statistics.</p>';
    });
}

// Helper function to get status color
function getStatusColor(status) {
    const colors = {
        'todo': 'bg-yellow-100 text-yellow-800',
        'in_progress': 'bg-orange-100 text-orange-800',
        'done': 'bg-green-100 text-green-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

// Get authentication token (from meta tag or localStorage)
function getAuthToken() {
    // Check if token is stored in meta tag (Laravel Sanctum)
    const tokenMeta = document.querySelector('meta[name="api-token"]');
    if (tokenMeta) {
        return tokenMeta.getAttribute('content');
    }
    
    // Check localStorage (for SPA)
    const storedToken = localStorage.getItem('auth_token');
    if (storedToken) {
        return storedToken;
    }
    
    // If using session-based authentication with Sanctum
    return ''; // Sanctum will use session cookie
}

// Load recent activity on page load
document.addEventListener('DOMContentLoaded', function() {
    // You can add automatic loading here if needed
    // loadMyTasks();
});
</script>
@endsection