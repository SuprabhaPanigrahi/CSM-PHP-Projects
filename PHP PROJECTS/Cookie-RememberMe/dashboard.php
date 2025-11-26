<?php
// LOGIN CHECK
if (!isset($_COOKIE['login_user'])) {
    header("Location: login.php");
    exit();
}

// LOGOUT
if (isset($_GET['logout'])) {
    setcookie("login_user", "", time() - 3600, "/");
    setcookie("user_list", "", time() - 3600, "/");   // Clear all users
    header("Location: login.php");
    exit();
}

// LOAD EXISTING USERS FROM COOKIE
$userList = [];
if (isset($_COOKIE['user_list'])) {
    $userList = json_decode($_COOKIE['user_list'], true);
    if (!is_array($userList)) $userList = [];
}

// DELETE USER
if (isset($_GET["delete"])) {
    $delIndex = intval($_GET["delete"]);

    if (isset($userList[$delIndex])) {
        unset($userList[$delIndex]);        
        $userList = array_values($userList); // reindex array
        setcookie("user_list", json_encode($userList), time() + 3600, "/");
    }

    header("Location: dashboard.php");
    exit();
}

// ADD NEW USER
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // push new user into list
    $userList[] = [
        "name"  => $name,
        "email" => $email,
        "phone" => $phone
    ];

    // save back to cookie for 1 hour
    setcookie("user_list", json_encode($userList), time() + 3600, "/");

    // refresh to see updated table
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4 mb-4">
        <h2>Welcome, <?= $_COOKIE['login_user'] ?>!</h2>
        <a href="dashboard.php?logout=1" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <!-- Add User Form -->
    <div class="card p-4 mb-4">
        <h4>Add User</h4>
        <form method="POST">
            <div class="mb-3">
                <label>Name</label>
                <input 
                   type="text" 
                   name="name" 
                   class="form-control" 
                   required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input 
                   type="email" 
                   name="email" 
                   class="form-control" 
                   required>
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input 
                   type="text" 
                   name="phone" 
                   class="form-control" 
                   required>
            </div>

            <button class="btn btn-primary w-100">Add User</button>
        </form>
    </div>

    <!-- User Table -->
    <div class="card p-4">
        <h4>Saved Users (Cookie)</h4>

        <?php if (count($userList) == 0): ?>
            <p>No users added yet.</p>
        <?php else: ?>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($userList as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($user["name"]) ?></td>
                        <td><?= htmlspecialchars($user["email"]) ?></td>
                        <td><?= htmlspecialchars($user["phone"]) ?></td>
                        <td>
                            <a href="dashboard.php?delete=<?= $index ?>" 
                               class="btn btn-danger btn-sm">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

</div>

</body>
</html>
