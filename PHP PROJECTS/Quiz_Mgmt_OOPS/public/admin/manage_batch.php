<!DOCTYPE html>
<html>
<head>
    <title>Manage Batch</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">

    <h2>Manage Batch</h2>
    <hr>

    <form class="row g-3 mb-4" method="POST" action="../../handler/ajax_save_batch.php">
        <div class="col-auto">
            <input type="text" name="name" class="form-control" placeholder="Batch Name" required>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Add Batch</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Batch Name</th>
            </tr>
        </thead>
        <tbody>
            <!-- Batch list fetch later -->
        </tbody>
    </table>

</div>
</body>
</html>
