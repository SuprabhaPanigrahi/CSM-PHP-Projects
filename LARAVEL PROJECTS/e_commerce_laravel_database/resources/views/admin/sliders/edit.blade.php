@extends('layouts.app')

@section('title', 'Edit Slider - Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Slider</h4>
            </div>
            <div class="card-body">
                <form action="/admin/sliders/{{ $slider->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Slider Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="{{ $slider->title }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">
                            {{ $slider->description }}
                        </textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>
                        <img src="{{ asset('storage/' . $slider->image) }}" 
                             style="width: 300px; height: 150px; object-fit: cover; margin-bottom: 10px;">
                        
                        <label for="image" class="form-label">Change Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status" 
                               {{ $slider->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/admin/sliders" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Update Slider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection