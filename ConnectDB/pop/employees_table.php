<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Employees List</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>First Name</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'connection_procedure.php';

                    $sql = "SELECT e.first_name, e.salary FROM employees e";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        die("<tr><td colspan='2' class='text-danger'>Query failed: " . mysqli_error($conn) . "</td></tr>");
                    }

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='text-center text-muted'>No results found</td></tr>";
                    }

                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
