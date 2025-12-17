@extends('layouts.app')

@section('title', 'Add Slider - Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Add New Slider</h4>
            </div>
            <div class="card-body">
                <form action="/admin/sliders" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Slider Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Slider Image</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                        <small class="text-muted">Recommended size: 1200x400px</small>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status" checked>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/admin/sliders" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Add Slider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection