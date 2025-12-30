@extends('layouts.simple')

@section('title', 'Edit Task')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="edit-task-container" class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-500">Loading task details...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Load task for editing
document.addEventListener('DOMContentLoaded', function() {
    const taskId = window.location.pathname.split('/').slice(-2, -1)[0];
    loadTaskForEdit(taskId);
    loadAssignableUsers();
});

function loadTaskForEdit(taskId) {
    const container = document.getElementById('edit-task-container');
    
    fetch(`/api/v1/tasks/${taskId}`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(task => {
        container.innerHTML = `
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Task</h1>
                    <p class="mt-1 text-sm text-gray-600">Update the task details.</p>
                </div>
                
                <form id="edit-task-form" onsubmit="updateTask(event, ${task.data.id})">
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="edit-title" class="block text-sm font-medium text-gray-700">
                                Task Title *
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       id="edit-title" 
                                       name="title"
                                       value="${task.data.title}"
                                       required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="edit-title-error"></p>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="edit-description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <div class="mt-1">
                                <textarea id="edit-description" 
                                          name="description" 
                                          rows="3"
                                          class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">${task.data.description || ''}</textarea>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Priority -->
                            <div>
                                <label for="edit-priority" class="block text-sm font-medium text-gray-700">
                                    Priority *
                                </label>
                                <select id="edit-priority" 
                                        name="priority"
                                        required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="low" ${task.data.priority === 'low' ? 'selected' : ''}>Low</option>
                                    <option value="medium" ${task.data.priority === 'medium' ? 'selected' : ''}>Medium</option>
                                    <option value="high" ${task.data.priority === 'high' ? 'selected' : ''}>High</option>
                                </select>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label for="edit-status" class="block text-sm font-medium text-gray-700">
                                    Status *
                                </label>
                                <select id="edit-status" 
                                        name="status"
                                        required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="todo" ${task.data.status === 'todo' ? 'selected' : ''}>To Do</option>
                                    <option value="in_progress" ${task.data.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                    <option value="done" ${task.data.status === 'done' ? 'selected' : ''}>Done</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Due Date -->
                            <div>
                                <label for="edit-due_date" class="block text-sm font-medium text-gray-700">
                                    Due Date *
                                </label>
                                <div class="mt-1">
                                    <input type="date" 
                                           id="edit-due_date" 
                                           name="due_date"
                                           value="${task.data.due_date}"
                                           required
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            
                            <!-- Assigned To -->
                            <div>
                                <label for="edit-assigned_to" class="block text-sm font-medium text-gray-700">
                                    Assign To *
                                </label>
                                <select id="edit-assigned_to" 
                                        name="assigned_to"
                                        required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Select User</option>
                                    <!-- Will be populated via JavaScript -->
                                </select>
                            </div>
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="/tasks/${task.data.id}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    id="edit-submit-btn"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        `;
        
        // Populate assigned_to dropdown
        setTimeout(() => {
            const assignSelect = document.getElementById('edit-assigned_to');
            if (assignSelect && window.assignableUsers) {
                window.assignableUsers.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.role_display})`;
                    option.selected = (user.id == task.data.assigned_to?.id);
                    assignSelect.appendChild(option);
                });
            }
        }, 100);
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-3"></i>
                <p class="text-red-500">Error loading task for editing.</p>
                <a href="/tasks" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Back to Tasks
                </a>
            </div>
        `;
    });
}

// Load assignable users
function loadAssignableUsers() {
    fetch('/api/v1/assignable-users', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        window.assignableUsers = data.data || [];
    })
    .catch(error => {
        console.error('Error loading users:', error);
        window.assignableUsers = [];
    });
}

// Update task via API
function updateTask(event, taskId) {
    event.preventDefault();
    
    const submitBtn = document.getElementById('edit-submit-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitBtn.disabled = true;
    
    // Clear previous errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    const formData = {
        title: document.getElementById('edit-title').value,
        description: document.getElementById('edit-description').value,
        priority: document.getElementById('edit-priority').value,
        status: document.getElementById('edit-status').value,
        due_date: document.getElementById('edit-due_date').value,
        assigned_to: document.getElementById('edit-assigned_to').value,
    };
    
    fetch(`/api/v1/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        alert('Task updated successfully!');
        window.location.href = `/tasks/${taskId}`;
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Display validation errors
        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const errorElement = document.getElementById(`edit-${field}-error`);
                if (errorElement) {
                    errorElement.textContent = error.errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            });
        } else {
            alert('Error updating task: ' + (error.message || 'Unknown error'));
        }
        
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function getAuthToken() {
    const tokenMeta = document.querySelector('meta[name="api-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
}
</script>
@endsection