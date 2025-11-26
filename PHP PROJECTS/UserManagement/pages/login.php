<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <?php include '../includes/styles.php'; ?>
</head>
<body>
  <div class="container m-5 p-5 border rounded shadow w-50  mx-auto" style="margin-top: 10% !important;background-color: #FFF176">
    <h2 class="text-center text-danger">Login</h2>
    <form action="../process/authenticate.php" method="POST" class="p-4">
      <div class="mb-3">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" class="form-control" >
      </div>
      <div class="mb-3">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control"> 
      </div>
      <div class="mb-3">
       <button type="submit" class="btn btn-primary w-100">Login</button>
      </div>
      <div>
        <p class="text-center">Don't have an account? <a href="registration.php" class="btn btn-link">Register here</a></p>
      </div>
    </form>
      
  </div>
  <?php include '../includes/scripts.php'; ?>
  <script>
    <?php if (isset($_GET['error'])): ?>
      alert("<?php echo $_GET['error']; ?>");
    <?php endif; ?>
  </script>
</body>
</html>