<?php

include 'connection.php';
mysqli_select_db($conn, 'Student_Mgmt');

$users = [
    ['id' => 1, 'username' => 'situ', 'password_hash' => 'situ123', 'email' => 'situ@gmail.com'],
    ['id' => 2, 'username' => 'isha', 'password_hash' => 'isha123', 'email' => 'isha@gmail.com'],
    ['id' => 3, 'username' => 'ram', 'password_hash' => 'ram123', 'email' => 'ram@gmail.com'],
];

foreach ($users as $user) {
    $username = mysqli_real_escape_string($conn, $user['username']);
    $password_hash = mysqli_real_escape_string($conn, $user['password_hash']);
    $email = mysqli_real_escape_string($conn, $user['email']);

    $insert_sql = "INSERT INTO users (username, password_hash, email) VALUES ('$username', '$password_hash', '$email')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "Inserted user: " . $username . "\n";
    } else {
        die("Error inserting user: " . $username . ": " . mysqli_error($conn));
    }
}

$students = [
    ['id' => 1, 'full_name' => 'Situ', 'email' => 'situ@gmail.com', 'phone' => '7735822183', 'photo' => ''],
    ['id' => 2, 'full_name' => 'Isha', 'email' => 'isha@gmail.com', 'phone' => '7735822184', 'photo' => ''],
    ['id' => 3, 'full_name' => 'Ram', 'email' => 'ram@gmail.com', 'phone' => '7735822185', 'photo' => ''],
];

echo "Students table structure:\n";
$result = mysqli_query($conn, "DESC students");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}

foreach ($students as $student) {
    $id = mysqli_real_escape_string($conn, $student['id']);
    $full_name = mysqli_real_escape_string($conn, $student['full_name']);
    $email = mysqli_real_escape_string($conn, $student['email']);
    $phone = mysqli_real_escape_string($conn, $student['phone']);
    $photo = mysqli_real_escape_string($conn, $student['photo']);

    $insert_sql = "INSERT INTO students (id, full_name, email, phone, photo) VALUES ($id, '$full_name', '$email', '$phone', '$photo')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "Inserted student: " . $full_name . "\n";
    } else {
        die("Error inserting student: " . $full_name . ": " . mysqli_error($conn));
    }
}

mysqli_close($conn);