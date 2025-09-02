<?php
session_start();

// include db.php from includes folder
include(__DIR__ . '/../includes/db.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded admin credentials
    $admin_user = "admin";
    $admin_pass = "admin123";

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial; background: #f8f9fa; text-align: center; padding: 50px; }
        form { background: white; padding: 20px; display: inline-block; border-radius: 8px; box-shadow: 0px 0px 10px gray; }
        input { padding: 10px; margin: 10px; width: 250px; }
        button { padding: 10px 20px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #ff9800; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>
        <button type="submit">Login</button>
        <p class="error"><?php echo $error; ?></p>
    </form>
</body>
</html>
