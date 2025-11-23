<?php // src/views/auth/login.php ?>
<h2>Admin Login</h2>
<?php if (!empty($error)): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" action="?r=auth/login">
  <label>Username: <input type="text" name="username"></label><br>
  <label>Password: <input type="password" name="password"></label><br>
  <button type="submit">Login</button>
</form>
<p>Go to <a href="?r=guest/email">Guest Exam</a></p>
