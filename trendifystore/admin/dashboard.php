<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; text-align: center; padding: 50px; }
        .container { background: white; padding: 30px; border-radius: 10px; display: inline-block; box-shadow: 0px 0px 10px gray; }
        a { display: block; margin: 20px; padding: 15px; background: #333; color: white; text-decoration: none; border-radius: 8px; }
        a:hover { background: #ff9800; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome Admin</h2>
        <a href="add_product.php">➕ Add Product</a>
        <a href="manage_products.php">📦 Manage Products</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</body>
</html>
