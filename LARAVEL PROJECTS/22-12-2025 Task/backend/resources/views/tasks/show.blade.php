@extends('layouts.simple')

@section('title', 'Task Details')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Task details will be loaded via API -->
        <div id="task-details" class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-500">Loading task details...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Load task details on page load
document.addEventListener('DOMContentLoaded', function() {
    const taskId = window.location.pathname.split('/').pop();
    loadTaskDetails(taskId);
});

function loadTaskDetails(taskId) {
    const container = document.getElementById('task-details');
    
    fetch(`/api/v1/tasks/${taskId}`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(task => {
        container.innerHTML = `
            <div>
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Task Details
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            ID: ${task.data.id}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="/tasks/${task.data.id}/edit" 
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Edit
                        </a>
                        <a href="/tasks" 
                           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Back to List
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                                ${task.data.title}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                ${task.data.description || 'No description'}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(task.data.status)}">
                                    ${task.data.status_display}
                                </span>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Priority</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getPriorityColor(task.data.priority)}">
                                    ${task.data.priority_display}
                                </span>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                            <dd class="mt-1 text-sm ${task.data.is_overdue ? 'text-red-600 font-semibold' : 'text-gray-900'} sm:mt-0 sm:col-span-2">
                                ${task.data.due_date_formatted}
                                ${task.data.is_overdue ? '(Overdue!)' : ''}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                ${task.data.assigned_to ? task.data.assigned_to.name : 'Not assigned'}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                ${task.data.created_by ? task.data.created_by.name : 'Unknown'}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                ${task.data.created_at_formatted}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-3"></i>
                <p class="text-red-500">Error loading task details.</p>
                <a href="/tasks" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Back to Tasks
                </a>
            </div>
        `;
    });
}

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
</script>
@endsection