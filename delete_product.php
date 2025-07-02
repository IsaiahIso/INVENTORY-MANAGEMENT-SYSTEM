<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid product ID.");
}

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_products.php");
exit;
?>
