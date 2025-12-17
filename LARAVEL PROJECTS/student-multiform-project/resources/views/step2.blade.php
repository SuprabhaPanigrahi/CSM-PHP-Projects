@extends('layout')

@section('content')
<h5 class="mb-4">Step 2: Contact Information</h5>

<div class="alert alert-info mb-3">
    <strong>Previous data:</strong> 
    {{ $formData['full_name'] ?? '' }}, {{ $formData['age'] ?? '' }} years old
</div>

<form method="POST" action="{{ route('form.postStep2') }}">
    @csrf
    
    <div class="mb-3">
        <label for="email" class="form-label">Email Address *</label>
        <input type="email" class="form-control" id="email" name="email" 
               value="{{ old('email', $formData['email'] ?? '') }}" required>
    </div>
    
    <div class="mb-3">
        <label for="phone" class="form-label">Phone Number *</label>
        <input type="text" class="form-control" id="phone" name="phone" 
               value="{{ old('phone', $formData['phone'] ?? '') }}" required>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('form.step1') }}" class="btn btn-secondary">
            ← Back
        </a>
        <button type="submit" class="btn btn-primary">
            Next Step →
        </button>
    </div>
</form>
@endsection