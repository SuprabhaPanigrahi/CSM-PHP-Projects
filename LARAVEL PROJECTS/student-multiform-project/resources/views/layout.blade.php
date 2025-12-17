<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Multi-Step Form</h4>
                        <div class="progress mt-3">
                            @php
                                $step = request()->route()->getName();
                                $progress = 33;
                                if ($step == 'form.step2') $progress = 66;
                                if ($step == 'form.step3') $progress = 100;
                            @endphp
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                                Step {{ $progress/33 }} of 3
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                @if(request()->route()->getName() != 'form.step1')
                                    <a href="{{ route('form.clear') }}" class="btn btn-outline-danger btn-sm">
                                        Clear All Data
                                    </a>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted">
                                    Data is stored in session & cookies
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>