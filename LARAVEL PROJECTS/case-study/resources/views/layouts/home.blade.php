@extends('layouts.app', [
    'title' => 'Home',
    'hero' => [
        'title' => 'Welcome to MySite',
        'subtitle' => 'A simple Laravel website with Bootstrap CDN'
    ]
])

@section('content')
    <h2>Home Page</h2>
    <p>This is the homepage content.</p>
@endsection
