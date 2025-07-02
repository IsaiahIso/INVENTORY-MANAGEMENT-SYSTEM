<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'staff') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <header class="dashboard-header">
            <h2>Welcome, <?= $_SESSION['first_name']; ?> <span>(Staff)</span></h2>
            
        </header>

        <ul class="dashboard-links">
            <li><a href="staff_view_products.php">📦 View Products</a></li>
            <li><a href="staff_reguest_stock.php">📦 Reguest Stock</a></li>
            <li><a href="staff_view_requests.php">📦 View Requests</a></li>
            <li><a href="send_message.php">✉️ Send Message</a></li>
            <li><a href="staff_inbox.php">📥 View Inbox</a></li>
        </ul>
         <div class="logout-bottom">
    <a href="inventory_manager_reports.php" class="logout-btn">Logout</a>
</div>
    </div>
</body>
</html>
