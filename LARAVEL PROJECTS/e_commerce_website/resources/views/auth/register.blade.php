@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 450px;">
    <div class="card p-4">
        <h3 class="text-center mb-3">Register</h3>

        <form>
            <div class="mb-3">
                <label>Name</label>
                <input type="text" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" class="form-control">
            </div>

            <button class="btn btn-success w-100 mt-2">Register</button>
        </form>
    </div>
</div>
@endsection
