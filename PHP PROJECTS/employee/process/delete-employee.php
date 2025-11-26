 <?php
    include_once __DIR__ . '/../database/connection.php';

    $id = $_POST['id'];
    $sql = "DELETE FROM employees WHERE emp_id = $id";

    // $result = mysqli_query($conn, $sql);

    // echo json_encode(mysqli_fetch_assoc($result));
    if (mysqli_query($conn, $sql)) {
        echo "Employee deleted succesfully";
    } else {
        echo "Employee not deleted succesfully";
    }


    mysqli_close($conn);
