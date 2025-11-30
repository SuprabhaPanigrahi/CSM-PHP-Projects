@extends('layout')

@section('content')
<h1>Add New Student</h1>

<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone *</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image *</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                <div class="form-text">Allowed: JPEG, PNG, JPG. Max: 2MB</div>
            </div>
        </div>
    </div>

    <!-- Cascading Dropdowns -->
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="country_id" class="form-label">Country *</label>
                <select class="form-control" id="country_id" name="country_id" required>
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="state_id" class="form-label">State *</label>
                <select class="form-control" id="state_id" name="state_id" required>
                    <option value="">Select State</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Image Preview -->
    <div class="mb-3">
        <div id="image-preview" style="display: none;">
            <img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
        </div>
    </div>

    <button type="submit" class="btn btn-success">Save Student</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@section('scripts')
<script>
// Image Preview
document.getElementById('profile_image').addEventListener('change', function() {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('image-preview');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
});

// Cascading Dropdown
document.getElementById('country_id').addEventListener('change', function() {
    let countryId = this.value;
    let stateSelect = document.getElementById('state_id');
    
    // Clear states
    stateSelect.innerHTML = '<option value="">Select State</option>';
    
    if(countryId) {
        // Show loading
        stateSelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch('/get-states/' + countryId)
            .then(response => response.json())
            .then(data => {
                stateSelect.innerHTML = '<option value="">Select State</option>';
                data.forEach(state => {
                    stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                });
            })
            .catch(error => {
                stateSelect.innerHTML = '<option value="">Error loading states</option>';
                console.error('Error:', error);
            });
    }
});
</script>
@endsection
@endsection