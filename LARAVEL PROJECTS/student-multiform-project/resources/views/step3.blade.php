@extends('layout')

@section('content')
<h5 class="mb-4">Step 3: Review & Submit</h5>

<div class="card mb-4">
    <div class="card-header">
        <h6>Your Information Summary</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="30%">Full Name:</th>
                <td>{{ $formData['full_name'] ?? '' }}</td>
            </tr>
            <tr>
                <th>Age:</th>
                <td>{{ $formData['age'] ?? '' }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $formData['email'] ?? '' }}</td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td>{{ $formData['phone'] ?? '' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="alert alert-warning">
    <strong>Note:</strong> Data is currently stored in session and cookies. 
    Clicking "Submit Form" will clear all stored data.
</div>

<form method="POST" action="{{ route('form.submit') }}">
    @csrf
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('form.step2') }}" class="btn btn-secondary">
            ← Back
        </a>
        <button type="submit" class="btn btn-success">
            Submit Form
        </button>
    </div>
</form>
@endsection