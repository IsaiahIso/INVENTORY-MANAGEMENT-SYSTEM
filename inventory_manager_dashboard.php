<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch product count
$product_query = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$product_count = $product_query->fetch_assoc()['total_products'] ?? 0;

// Fetch low stock count
$low_stock_query = $conn->query("SELECT COUNT(*) AS low_stock FROM products WHERE stock < 10");
$low_stock_count = $low_stock_query->fetch_assoc()['low_stock'] ?? 0;

// Fetch categories
$category_query = $conn->query("SELECT COUNT(*) AS total_categories FROM categories");
$category_count = $category_query->fetch_assoc()['total_categories'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Manager Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>Welcome, <?= $_SESSION['first_name']; ?> <span>(Inventory Manager)</span></h2>
    </header>

    <div class="dashboard-cards">
        <div class="card">📦 Total Products: <?= $product_count; ?></div>
        <div class="card">⚠️ Low Stock: <?= $low_stock_count; ?></div>
        <div class="card">🗂️ Categories: <?= $category_count; ?></div>
    </div>

    <ul class="dashboard-links">
        <li><a href="view_products.php">📋 View & Update Stock</a></li>
        <li><a href="view_categories.php">🗂️ View Categories</a></li>
        <li><a href="view-suppliers.php">🚚 View Suppliers</a></li>
        <li><a href="inventory_manager_inbox.php">📥 Inbox</a></li>
        <li><a href="send_message.php">✉️ Send Message</a></li>
        <li><a href="inventory_manager_reports.php">📊 Inventory Reports</a></li>
    </ul>
     <div class="logout-bottom">
    <a href="logout.php" class="logout-btn">Logout</a>
</div>
</div>
</body>
</html>
