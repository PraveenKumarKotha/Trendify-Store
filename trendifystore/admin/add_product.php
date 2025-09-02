<?php
session_start();

// check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// include database (correct path to includes folder)
include(__DIR__ . '/../includes/db.php');

// handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $affiliate_link = $_POST['affiliate_link'];

    // image upload
    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $image = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
    }

    // insert into database
    $sql = "INSERT INTO products (name, description, image, affiliate_link, created_at) 
            VALUES (:name, :description, :image, :affiliate_link, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':image' => $image,
        ':affiliate_link' => $affiliate_link
    ]);

    $success = "✅ Product added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; }
        h2 { text-align: center; }
        form { max-width: 450px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 12px; width: 100%; background: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #218838; }
        .success { color: green; text-align: center; }
        .links { text-align: center; margin-top: 15px; }
        .links a { margin: 0 10px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <h2>Add New Product</h2>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Affiliate Link</label>
        <input type="url" name="affiliate_link" placeholder="https://amzn.to/example" required>

        <label>Image</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>

    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a> | 
        <a href="logout.php">Logout</a>
    </div>

</body>
</html>
