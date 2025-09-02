<?php
include_once __DIR__ . "/includes/db.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: manage_products.php");
exit();
?>
