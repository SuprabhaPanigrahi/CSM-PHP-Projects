<div class="container mt-5">
    <h2 class="text-center mb-4">Employees List</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Hire Date</th>
                    <th>Job ID</th>
                    <th>Salary</th>
                    <th>Commission PCT</th>
                    <th>Manager ID</th>
                    <th>Department ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connection_procedure.php';
                $sql = "SELECT * FROM employees";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die("<tr><td colspan='12' class='text-danger'>Query failed: " . mysqli_error($conn) . "</td></tr>");
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['EMPLOYEE_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['FIRST_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['LAST_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['EMAIL']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['PHONE_NUMBER']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HIRE_DATE']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['JOB_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['SALARY']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['COMMISSION_PCT']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['MANAGER_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DEPARTMENT_ID']) . "</td>";
                        echo "<td>
                                <div class='btn-group' role='group'>
                                    <button class='btn btn-sm btn-primary'>Edit</button>
                                    <button class='btn btn-sm btn-danger'>Delete</button>
                                    <button class='btn btn-sm btn-info'><a href='modal.php' target='_blank'>View</button>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center text-muted'>No results found</td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
    <?php
    $sql_join = "
        SELECT 
            E.EMPLOYEE_ID,
            E.FIRST_NAME AS EmployeeFirstName,
            E.LAST_NAME AS EmployeeLastName,
            M.FIRST_NAME AS ManagerFirstName,
            M.LAST_NAME AS ManagerLastName,
            D.DEPARTMENT_NAME
        FROM EMPLOYEES AS E
        LEFT JOIN EMPLOYEES AS M ON E.MANAGER_ID = M.EMPLOYEE_ID
        LEFT JOIN DEPARTMENTS AS D ON D.DEPARTMENT_ID = E.DEPARTMENT_ID
        ORDER BY E.EMPLOYEE_ID
    ";

    $result_join = mysqli_query($conn, $sql_join);

    if ($result_join && mysqli_num_rows($result_join) > 0) {
        echo "<h3 class='mt-5 mb-3 text-center'>Employees with Managers and Departments</h3>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead class='table-dark'><tr>
                <th>Employee</th>
                <th>Manager</th>
                <th>Department</th>
              </tr></thead><tbody>";

        while ($row = mysqli_fetch_assoc($result_join)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['EmployeeFirstName'] . ' ' . $row['EmployeeLastName']) . "</td>
                    <td>" . htmlspecialchars($row['ManagerFirstName'] . ' ' . $row['ManagerLastName']) . "</td>
                    <td>" . htmlspecialchars($row['DEPARTMENT_NAME']) . "</td>
                  </tr>";
        }

        echo "</tbody></table></div>";
    } else {
        echo "<p class='text-muted text-center mt-4'>No join results found.</p>";
    }

    mysqli_close($conn);
    ?>
</div>