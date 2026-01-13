<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center mb-3">
                    <a href="{{ route('student.view') }}"><button class="btn btn-info">View Registered Student List</button></a>
                </div>

                <div class="card shadow">
                    <div class="card-header text-center fw-bold">
                        Student Details
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('student.save') }}" method="post">
                            @csrf
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>

                            <!-- Course -->
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-select" id="course" name="course">
                                    <option selected disabled>Select course</option>
                                    <option value="1">BTech</option>
                                    <option value="2">MCA</option>
                                    <option value="3">BSc</option>
                                    <option value="4">BCA</option>
                                </select>
                            </div>

                            <!-- Button -->
                            <div class="d-grid">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @if(isset($successMessage))
    <div class="alert alert-success">
        {{ $successMessage }}
    </div>
    @endif


</body>

</html>