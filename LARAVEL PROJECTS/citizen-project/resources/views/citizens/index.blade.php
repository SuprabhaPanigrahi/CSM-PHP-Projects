<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Citizens - Citizen Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .table-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .page-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .header-section {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <h1><i class="fas fa-users me-2"></i>Citizen Registration System</h1>
            <p class="lead mb-0">Manage all registered citizens in one place</p>
        </div>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">
                    <i class="fas fa-list me-2"></i>Registered Citizens
                </h2>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-success me-2">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                    <a href="{{ route('citizens.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Add New Citizen
                    </a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($citizens->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>State</th>
                                <th>Block</th>
                                <th>Panchayat</th>
                                <th>Village</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citizens as $citizen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $citizen->CitizenName }}</td>
                                <td>{{ $citizen->CitizenGender }}</td>
                                <td>{{ $citizen->CitizenPhone }}</td>
                                <td>{{ $citizen->CitizenEmail ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $stateName = 'N/A';
                                        if ($citizen->village && 
                                            $citizen->village->panchayat && 
                                            $citizen->village->panchayat->block && 
                                            $citizen->village->panchayat->block->state) {
                                            $stateName = $citizen->village->panchayat->block->state->StateName;
                                        }
                                    @endphp
                                    {{ $stateName }}
                                </td>
                                <td>
                                    @php
                                        $blockName = 'N/A';
                                        if ($citizen->village && 
                                            $citizen->village->panchayat && 
                                            $citizen->village->panchayat->block) {
                                            $blockName = $citizen->village->panchayat->block->BlockName;
                                        }
                                    @endphp
                                    {{ $blockName }}
                                </td>
                                <td>
                                    @php
                                        $panchayatName = 'N/A';
                                        if ($citizen->village && 
                                            $citizen->village->panchayat) {
                                            $panchayatName = $citizen->village->panchayat->PanchayatName;
                                        }
                                    @endphp
                                    {{ $panchayatName }}
                                </td>
                                <td>
                                    @php
                                        $villageName = 'N/A';
                                        if ($citizen->village) {
                                            $villageName = $citizen->village->VillageName;
                                        }
                                    @endphp
                                    {{ $villageName }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('citizens.edit', $citizen->CitizenId) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('citizens.destroy', $citizen->CitizenId) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this citizen?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Citizens Registered Yet</h4>
                    <p class="text-muted">Get started by adding your first citizen.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Register First Citizen
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>