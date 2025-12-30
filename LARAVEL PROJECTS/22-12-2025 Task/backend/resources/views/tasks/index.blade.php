@extends('layouts.simple')

@section('title', 'Tasks')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
                @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                <a href="{{ route('tasks.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-plus mr-2"></i>New Task
                </a>
                @endif
            </div>
            
            <!-- Filters -->
            <div class="mt-4 bg-white p-4 rounded-lg shadow">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="filter-status" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Status</option>
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select id="filter-priority" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Priority</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="filter-search" placeholder="Search tasks..." 
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <button onclick="loadTasks()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-medium text-gray-900">
                    Task List
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    All tasks assigned to you or your team.
                </p>
            </div>
            
            <div id="tasks-container">
                <!-- Tasks will be loaded here via API -->
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-500">Loading tasks...</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <div id="pagination" class="px-4 py-3 border-t border-gray-200 sm:px-6">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for API Calls -->
<script>
let currentPage = 1;
let totalPages = 1;

// Load tasks on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTasks();
    
    // Load assignable users for quick create modal
    loadAssignableUsers();
});

// Load tasks via API
function loadTasks(page = 1) {
    currentPage = page;
    
    const status = document.getElementById('filter-status').value;
    const priority = document.getElementById('filter-priority').value;
    const search = document.getElementById('filter-search').value;
    
    const container = document.getElementById('tasks-container');
    container.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-500">Loading tasks...</p>
        </div>
    `;
    
    // Build query string
    let url = `/api/v1/tasks?page=${page}`;
    if (status) url += `&status=${status}`;
    if (priority) url += `&priority=${priority}`;
    if (search) url += `&search=${search}`;
    
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.data && data.data.length > 0) {
            renderTasks(data.data);
            renderPagination(data.meta);
        } else {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-tasks text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">No tasks found.</p>
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    <button onclick="openCreateModal()" 
                            class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Create Your First Task
                    </button>
                    @endif
                </div>
            `;
            document.getElementById('pagination').innerHTML = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-3"></i>
                <p class="text-red-500">Error loading tasks. Please try again.</p>
            </div>
        `;
    });
}

// Render tasks in table
function renderTasks(tasks) {
    const container = document.getElementById('tasks-container');
    
    let html = `
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    tasks.forEach(task => {
        html += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div>
                            <div class="text-sm font-medium text-gray-900">${task.title}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">${task.description || 'No description'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(task.status)}">
                        ${task.status_display}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getPriorityColor(task.priority)}">
                        ${task.priority_display}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm ${task.is_overdue ? 'text-red-600 font-semibold' : 'text-gray-500'}">
                    ${task.due_date_formatted}
                    ${task.is_overdue ? '<i class="fas fa-exclamation-circle ml-1"></i>' : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${task.assigned_to ? task.assigned_to.name : 'Not assigned'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="/tasks/${task.id}" class="text-blue-600 hover:text-blue-900 mr-3">
                        <i class="fas fa-eye"></i>
                    </a>
                    ${task.can_edit ? `
                        <a href="/tasks/${task.id}/edit" class="text-green-600 hover:text-green-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                    ` : ''}
                    <button onclick="updateStatus(${task.id}, '${task.status}')" 
                            class="text-purple-600 hover:text-purple-900 mr-3"
                            title="Update Status">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    `;
    
    container.innerHTML = html;
}

// Render pagination
function renderPagination(meta) {
    const paginationDiv = document.getElementById('pagination');
    
    if (!meta || meta.last_page <= 1) {
        paginationDiv.innerHTML = '';
        return;
    }
    
    totalPages = meta.last_page;
    
    let html = `
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <button onclick="loadTasks(${currentPage - 1})" 
                        ${currentPage === 1 ? 'disabled' : ''}
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button onclick="loadTasks(${currentPage + 1})" 
                        ${currentPage === totalPages ? 'disabled' : ''}
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">${meta.from || 0}</span> to 
                        <span class="font-medium">${meta.to || 0}</span> of 
                        <span class="font-medium">${meta.total}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
    `;
    
    // Previous button
    html += `
        <button onclick="loadTasks(${currentPage - 1})" 
                ${currentPage === 1 ? 'disabled' : ''}
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Previous</span>
            <i class="fas fa-chevron-left"></i>
        </button>
    `;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            html += `
                <button onclick="loadTasks(${i})" 
                        class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                    ${i}
                </button>
            `;
        } else {
            html += `
                <button onclick="loadTasks(${i})" 
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    ${i}
                </button>
            `;
        }
    }
    
    // Next button
    html += `
        <button onclick="loadTasks(${currentPage + 1})" 
                ${currentPage === totalPages ? 'disabled' : ''}
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Next</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    `;
    
    html += `
                    </nav>
                </div>
            </div>
        </div>
    `;
    
    paginationDiv.innerHTML = html;
}

// Update task status
function updateStatus(taskId, currentStatus) {
    const newStatus = prompt('Enter new status (todo, in_progress, done):', currentStatus);
    
    if (!newStatus || !['todo', 'in_progress', 'done'].includes(newStatus)) {
        alert('Invalid status. Please enter: todo, in_progress, or done');
        return;
    }
    
    fetch(`/api/v1/tasks/${taskId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => {
        if (response.ok) {
            alert('Task status updated successfully!');
            loadTasks(currentPage);
        } else {
            alert('Failed to update task status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating task status.');
    });
}

// Quick create modal
function openCreateModal() {
    // You can implement a quick create modal here
    window.location.href = '/tasks/create';
}

// Helper functions
function getStatusColor(status) {
    const colors = {
        'todo': 'bg-yellow-100 text-yellow-800',
        'in_progress': 'bg-orange-100 text-orange-800',
        'done': 'bg-green-100 text-green-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function getPriorityColor(priority) {
    const colors = {
        'high': 'bg-red-100 text-red-800',
        'medium': 'bg-yellow-100 text-yellow-800',
        'low': 'bg-green-100 text-green-800'
    };
    return colors[priority] || 'bg-gray-100 text-gray-800';
}

function getAuthToken() {
    const tokenMeta = document.querySelector('meta[name="api-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
}

// Load assignable users for reference
function loadAssignableUsers() {
    fetch('/api/v1/assignable-users', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        // Store for later use if needed
        window.assignableUsers = data.data || [];
    })
    .catch(error => console.error('Error loading users:', error));
}
</script>
@endsection