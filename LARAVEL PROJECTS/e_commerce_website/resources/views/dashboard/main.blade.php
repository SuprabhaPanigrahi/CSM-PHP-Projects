<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('build/assets/css/admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        
         
        .content {
            margin-left: 260px;
            margin-top: 80px; 
            padding: 20px;
        }
    </style>
</head>

<body>

@include('dashboard.leftsidebar')

{{-- TOP BAR --}}
@include('dashboard.topbar')

<div class="content">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    /* ---------------- Line Chart: Sales Trend ---------------- */
    new Chart(document.getElementById("salesChart"), {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [{
                label: "Sales",
                data: [1200, 1900, 3000, 5000, 4200, 6100],
                borderWidth: 3,
                borderColor: "#4f46e5",
                backgroundColor: "rgba(79,70,229,0.2)",
                tension: 0.4
            }]
        }
    });

    /* ---------------- Bar Chart: Orders by Month ---------------- */
    new Chart(document.getElementById("orderChart"), {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [{
                label: "Orders",
                data: [20, 30, 25, 40, 35, 50],
                backgroundColor: ["#0ea5e9","#6366f1","#22c55e","#eab308","#f97316","#ef4444"],
            }]
        }
    });

    /* ---------------- Pie Chart: Category Distribution ---------------- */
    new Chart(document.getElementById("categoryPie"), {
        type: "pie",
        data: {
            labels: ["Electronics", "Fashion", "Grocery", "Furniture"],
            datasets: [{
                data: [40, 25, 20, 15],
                backgroundColor: ["#6366f1","#22c55e","#f97316","#ef4444"]
            }]
        }
    });

});
</script>


</body>
</html>
