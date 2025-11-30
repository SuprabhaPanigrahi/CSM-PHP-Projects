@extends('layout')

@section('content')
<h1>Edit Student</h1>

<form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $student->email }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone *</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $student->phone }}" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                <div class="form-text">Leave empty to keep current image. Allowed: JPEG, PNG, JPG. Max: 2MB</div>
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
                        <option value="{{ $country->id }}" {{ $student->country_id == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="state_id" class="form-label">State *</label>
                <select class="form-control" id="state_id" name="state_id" required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" {{ $student->state_id == $state->id ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Current Image Preview -->
    @if($student->profile_image)
    <div class="mb-3">
        <label class="form-label">Current Image:</label><br>
        <img src="{{ asset('images/students/' . $student->profile_image) }}" 
             alt="Current Profile" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
    </div>
    @endif

    <!-- New Image Preview -->
    <div class="mb-3">
        <div id="image-preview" style="display: none;">
            <label class="form-label">New Image Preview:</label><br>
            <img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
        </div>
    </div>

    <button type="submit" class="btn btn-success">Update Student</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@section('scripts')
<script>
// Image Preview for new image
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
    
    if(countryId) {
        stateSelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch('/get-states/' + countryId)
            .then(response => response.json())
            .then(data => {
                stateSelect.innerHTML = '<option value="">Select State</option>';
                data.forEach(state => {
                    stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                });
            });
    } else {
        stateSelect.innerHTML = '<option value="">Select State</option>';
    }
});
</script>
@endsection
@endsection