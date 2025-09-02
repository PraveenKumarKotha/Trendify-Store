<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Include database
include(__DIR__ . '/../includes/db.php');

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: manage_products.php");
    exit();
}

// Fetch products
$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #333; color: white; }
        a { text-decoration: none; color: #007bff; }
        a:hover { color: #ff9800; }
        .actions a { margin: 0 5px; }
    </style>
</head>
<body>
    <h2>Manage Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Affiliate Link</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['id']); ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo htmlspecialchars($product['description']); ?></td>
            <td>
                <?php if (!empty($product['image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="80">
                <?php endif; ?>
            </td>
            <td><a href="<?php echo htmlspecialchars($product['affiliate_link']); ?>" target="_blank">Link</a></td>
            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
            <td class="actions">
                <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> |
                <a href="?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</body>
</html>
