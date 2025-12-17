@extends('dashboard.main')

@section('content')
<h2 class="mb-4">Manage Categories</h2>

<div class="card p-4 shadow-sm">
    <form>
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" class="form-control" placeholder="Enter Category">
        </div>

        <button class="btn btn-primary">Add Category</button>
    </form>
</div>

@endsection
