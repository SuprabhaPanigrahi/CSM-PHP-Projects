@extends('layout')

@section('content')
<h5 class="mb-4">Step 1: Personal Information</h5>

<form method="POST" action="{{ route('form.postStep1') }}">
    @csrf
    
    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name *</label>
        <input type="text" class="form-control" id="full_name" name="full_name" 
               value="{{ old('full_name', $formData['full_name'] ?? '') }}" required>
    </div>
    
    <div class="mb-3">
        <label for="age" class="form-label">Age *</label>
        <input type="number" class="form-control" id="age" name="age" 
               value="{{ old('age', $formData['age'] ?? '') }}" required min="1">
    </div>
    
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            Next Step →
        </button>
    </div>
</form>

@if(isset($formData) && !empty($formData))
<div class="mt-4 p-3 border rounded bg-light">
    <h6>Data stored in cookie (persists even if browser closes):</h6>
    <pre class="mb-0">{{ json_encode($formData, JSON_PRETTY_PRINT) }}</pre>
</div>
@endif
@endsection