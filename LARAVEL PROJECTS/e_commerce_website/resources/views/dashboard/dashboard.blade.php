@extends('dashboard.main')

@section('content')

<h2 class="mb-4 fw-bold">Dashboard Overview</h2>

<!-- Stats Row -->
<div class="row g-4">

    <!-- Categories -->
    <div class="col-md-4">
        <div class="admin-card pro shadow-sm">
            <div class="icon-box gradient-blue">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <h5 class="title">Total Categories</h5>
                <p class="count">10</p>
                <div class="progress">
                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="col-md-4">
        <div class="admin-card pro shadow-sm">
            <div class="icon-box gradient-green">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <h5 class="title">Total Products</h5>
                <p class="count">35</p>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: 55%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-md-4">
        <div class="admin-card pro shadow-sm">
            <div class="icon-box gradient-orange">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
                <h5 class="title">Total Orders</h5>
                <p class="count">120</p>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Graphs Section -->
<h3 class="mt-5 mb-3 fw-bold">Analytics</h3>

<div class="row g-4">

    <!-- Sales Trend Line Chart -->
    <div class="col-md-6">
        <div class="chart-card shadow-sm p-3">
            <h5 class="fw-bold">Sales Trend</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Orders by Month Bar Chart -->
    <div class="col-md-6">
        <div class="chart-card shadow-sm p-3">
            <h5 class="fw-bold">Orders (Last 6 Months)</h5>
            <canvas id="orderChart"></canvas>
        </div>
    </div>

</div>

<div class="row g-4 mt-3">

    <!-- Pie Chart -->
    <div class="col-md-6">
        <div class="chart-card shadow-sm p-3">
            <h5 class="fw-bold">Category Distribution</h5>
            <canvas id="categoryPie"></canvas>
        </div>
    </div>

</div>

<!-- Quick Actions -->
<h3 class="mt-5 mb-3 fw-bold">Quick Actions</h3>

<div class="row g-4">

    <div class="col-md-3">
        <a href="{{ route('admin.categories') }}" class="action-box shadow-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Add Category</span>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.products') }}" class="action-box shadow-sm">
            <i class="fa-solid fa-box"></i>
            <span>Add Product</span>
        </a>
    </div>

    <div class="col-md-3">
        <a href="#" class="action-box shadow-sm">
            <i class="fa-solid fa-file-invoice"></i>
            <span>View Reports</span>
        </a>
    </div>

    <div class="col-md-3">
        <a href="#" class="action-box shadow-sm">
            <i class="fa-solid fa-users"></i>
            <span>Manage Users</span>
        </a>
    </div>

</div>

@endsection
