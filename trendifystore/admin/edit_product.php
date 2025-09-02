<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include(__DIR__ . '/../includes/db.php');

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$id = $_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: manage_products.php");
    exit();
}

$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $affiliate_link = $_POST['affiliate_link'];
    $image = $product['image'];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
    }

    $stmt = $conn->prepare("UPDATE products SET name=:name, description=:description, image=:image, affiliate_link=:affiliate_link WHERE id=:id");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':image' => $image,
        ':affiliate_link' => $affiliate_link,
        ':id' => $id
    ]);

    $success = "✅ Product updated successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f9f9f9; }
        form { max-width: 450px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 12px; width: 100%; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        .success { color: green; text-align: center; }
        .links { text-align: center; margin-top: 15px; }
        .links a { margin: 0 10px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

<h2>Edit Product</h2>
<?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Product Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

    <label>Description</label>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

    <label>Affiliate Link</label>
    <input type="url" name="affiliate_link" value="<?php echo htmlspecialchars($product['affiliate_link']); ?>" required>

    <label>Image</label>
    <input type="file" name="image" accept="image/*">
    <?php if (!empty($product['image'])): ?>
        <p>Current Image: <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="80"></p>
    <?php endif; ?>

    <button type="submit">Update Product</button>
</form>

<div class="links">
    <a href="manage_products.php">⬅ Back to Manage Products</a> | 
    <a href="dashboard.php">Dashboard</a>
</div>

</body>
</html>
