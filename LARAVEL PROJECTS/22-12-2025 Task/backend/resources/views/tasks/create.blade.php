@extends('layouts.simple')

@section('title', 'Create Task')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create New Task</h1>
                    <p class="mt-1 text-sm text-gray-600">Fill in the details to create a new task.</p>
                </div>
                
                <form id="create-task-form" onsubmit="createTask(event)">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Task Title *
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       id="title" 
                                       name="title"
                                       required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="title-error"></p>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <div class="mt-1">
                                <textarea id="description" 
                                          name="description" 
                                          rows="3"
                                          class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">
                                    Priority *
                                </label>
                                <select id="priority" 
                                        name="priority"
                                        required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Select Priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            
                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">
                                    Due Date *
                                </label>
                                <div class="mt-1">
                                    <input type="date" 
                                           id="due_date" 
                                           name="due_date"
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assigned To -->
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700">
                                Assign To *
                            </label>
                            <select id="assigned_to" 
                                    name="assigned_to"
                                    required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select User</option>
                                <!-- Will be populated via JavaScript -->
                            </select>
                        </div>
                        @endif
                        
                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('tasks.index') }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    id="submit-btn"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Create Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Load assignable users on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAssignableUsers();
});

function loadAssignableUsers() {
    const assignSelect = document.getElementById('assigned_to');
    if (!assignSelect) return;
    
    fetch('/api/v1/assignable-users', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAuthToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.data && data.data.length > 0) {
            assignSelect.innerHTML = '<option value="">Select User</option>';
            data.data.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} (${user.role_display})`;
                assignSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
    });
}

// Create task via API
function createTask(event) {
    event.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    submitBtn.disabled = true;
    
    // Clear previous errors
    document.querySelectorAll('.text-red-600').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    const formData = {
        title: document.getElementById('title').value,
        description: document.getElementById('description').value,
        priority: document.getElementById('priority').value,
        due_date: document.getElementById('due_date').value,
    };
    
    // Add assigned_to if field exists
    const assignedTo = document.getElementById('assigned_to');
    if (assignedTo) {
        formData.assigned_to = assignedTo.value;
    }
    
    fetch('/api/v1/tasks', {
        method: 'POST',
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
        alert('Task created successfully!');
        window.location.href = '/tasks';
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Display validation errors
        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.textContent = error.errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            });
        } else {
            alert('Error creating task: ' + (error.message || 'Unknown error'));
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