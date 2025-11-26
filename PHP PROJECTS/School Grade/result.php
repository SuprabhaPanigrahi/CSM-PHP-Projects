<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize the student name
    $name = htmlspecialchars($_POST['name']);

    // Get subject marks as integers
    $subject1 = (int) $_POST['subject1'];
    $subject2 = (int) $_POST['subject2'];
    $subject3 = (int) $_POST['subject3'];
    $subject4 = (int) $_POST['subject4'];
    $subject5 = (int) $_POST['subject5'];

    // Total and percentage calculation
    $totalMarks = $subject1 + $subject2 + $subject3 + $subject4 + $subject5;
    $percentage = $totalMarks / 5;

    // Grade assignment
    if ($percentage >= 90) {
        $grade = "A+";
    } elseif ($percentage >= 80) {
        $grade = "A";
    } elseif ($percentage >= 70) {
        $grade = "B";
    } elseif ($percentage >= 60) {
        $grade = "C";
    } elseif ($percentage >= 50) {
        $grade = "D";
    } else {
        $grade = "F";
    }
} else {
    // Redirect to index.html if accessed directly
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Result</title>
</head>
<body>
    <h2>Student Result</h2>
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Subject 1:</strong> <?php echo $subject1; ?></p>
    <p><strong>Subject 2:</strong> <?php echo $subject2; ?></p>
    <p><strong>Subject 3:</strong> <?php echo $subject3; ?></p>
    <p><strong>Subject 4:</strong> <?php echo $subject4; ?></p>
    <p><strong>Subject 5:</strong> <?php echo $subject5; ?></p>
    <p><strong>Total Marks:</strong> <?php echo $totalMarks; ?> / 500</p>
    <p><strong>Percentage:</strong> <?php echo number_format($percentage, 2); ?>%</p>
    <p><strong>Grade:</strong> <?php echo $grade; ?></p>
</body>
</html>
