@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body text-center">
        <h2>Welcome to Leave Application System</h2>
        <p class="lead">Employee leave management system</p>
        <div class="mt-4">
            <a href="{{ route('leave.create') }}" class="btn btn-primary btn-lg">Apply for Leave</a>
            <a href="{{ route('leave.index') }}" class="btn btn-secondary btn-lg">View Applications</a>
        </div>
    </div>
</div>
@endsection