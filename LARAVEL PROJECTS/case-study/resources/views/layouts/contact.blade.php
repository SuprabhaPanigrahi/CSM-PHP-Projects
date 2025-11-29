@extends('layouts.app', ['title' => 'Contact'])

@section('content')
<h2>Contact Us</h2>

<form class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" class="form-control">
    </div>

    <div class="col-12">
        <label class="form-label">Message</label>
        <textarea class="form-control" rows="5"></textarea>
    </div>

    <div class="col-12">
        <button class="btn btn-primary">Send Message</button>
    </div>
</form>

@endsection
