<!DOCTYPE html>
<html>
<head>
    <title>User Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { background: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .tables-container { display: flex; gap: 30px; margin-top: 30px; }
        .table-wrapper { flex: 1; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h1>User Management System</h1>
    
    {{-- Display Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif
    
    {{-- FORM SECTION --}}
    <div class="form-container">
        <h2>Add New User & Profile</h2>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone">
            </div>
            
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" rows="3"></textarea>
            </div>
            
            <button type="submit">Submit Data to Both Tables</button>
        </form>
    </div>
    
    {{-- DISPLAY BOTH TABLES --}}
    <div class="tables-container">
        
        {{-- USERS TABLE --}}
        <div class="table-wrapper">
            <h2>Users Table</h2>
            @if($users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p>No users found in the database.</p>
            @endif
        </div>
        
        {{-- PROFILES TABLE --}}
        <div class="table-wrapper">
            <h2>Profiles Table</h2>
            @if($profiles->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profiles as $profile)
                    <tr>
                        <td>{{ $profile->id }}</td>
                        <td>{{ $profile->user_id }}</td>
                        <td>{{ $profile->phone ?? 'N/A' }}</td>
                        <td>{{ $profile->address ?? 'N/A' }}</td>
                        <td>{{ $profile->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p>No profiles found in the database.</p>
            @endif
        </div>
        
    </div>
    
    {{-- COMBINED DATA TABLE --}}
    <div style="margin-top: 40px;">
        <h2>Combined Data (Users with their Profiles)</h2>
        @if($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Profile Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->profile->phone ?? 'N/A' }}</td>
                    <td>{{ $user->profile->address ?? 'N/A' }}</td>
                    <td>{{ $user->profile ? $user->profile->created_at->format('d/m/Y') : 'No Profile' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>No data available.</p>
        @endif
    </div>
    
</body>
</html>