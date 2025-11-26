<?php

session_start();
include 'database/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    //Fetch user Data from database
    $sql = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // Simple authentication logic (for demonstration purposes only)
    if ($user) {
      $_SESSION['username'] = $user['username'];
      header("Location: manage-product.php");
        // echo "Login successful! and username is ".$user['username'];
    } else {
        echo "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div>
    <h1>Login Here</h1>
    <form action="<?=($_SERVER['PHP_SELF'])?>" method="post" onsubmit="return validate()">
      <div>

        <label>Username</label>
        <input type="text" id="username" name="username" />
      </div>
      <div>
        <label>Password</label>
        <input type="password" id="password" name="password" />
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
  <script src="assets/script.js"></script>
</body>
</html>