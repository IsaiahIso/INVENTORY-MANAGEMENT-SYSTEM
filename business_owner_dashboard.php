<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch counts
$user_query = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$user_count = $user_query->fetch_assoc()['total_users'] ?? 0;

$product_count = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'] ?? 0;
$low_stock_count = $conn->query("SELECT COUNT(*) AS low FROM products WHERE stock < 5")->fetch_assoc()['low'] ?? 0;
$supplier_count = $conn->query("SELECT COUNT(*) AS total FROM suppliers")->fetch_assoc()['total'] ?? 0;
$pending_requests = $conn->query("SELECT COUNT(*) AS total FROM stock_requests WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Owner Dashboard</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <div class="dashboard-wrapper">
        <header class="dashboard-header">
            <h2>Welcome, <?php echo $_SESSION['first_name']; ?> <span>(Business Owner)</span></h2>
        </header>

        <div class="dashboard-content">
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">📦 Total Products: <?= $product_count ?></div>
                <div class="card">⚠️ Low Stock: <?= $low_stock_count ?></div>
                <div class="card">🚚 Suppliers: <?= $supplier_count ?></div>
                <div class="card">👥 Users: <?= $user_count ?></div>     
            </div>
            
            <!-- Navigation Links -->
            <ul class="dashboard-links">
                <li><a href="manage_users.php">👥 Manage Users</a></li>
                <li><a href="manage_products.php">📦 Manage Products</a></li>
                <li><a href="manage_categories.php">🗂️ Manage Categories</a></li>
                <li><a href="manage_suppliers.php">🚚 Manage Suppliers</a></li>
                <li><a href="inbox.php">📥 Inbox</a></li>
                <li><a href="send_message.php">✉️ Send Message</a></li>
                <li><a href="business_owner_reports.php">📊 View Reports</a></li>
                <li><a href="business_owner_charts.php">📈 View Charts</a></li>
                <li><a href="bo_approve_request.php">🛠️ Approve Stock Requests</a></li>
            </ul>

            <div class="logout-bottom">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
 