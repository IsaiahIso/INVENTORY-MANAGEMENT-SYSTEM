<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📋 Inventory Reports</h2>
    </header>

    <div class="dashboard-content">
        <ul class="dashboard-links">
            <li><a href="report_total_products.php">📦 Total Products Report</a></li>
            <li><a href="report_low_stock.php">⚠️ Low Stock Items Report</a></li>
            <li><a href="report_total_stock.php">📊 Total Stock Report</a></li>
            <li><a href="report_suppliers.php">🚚 Suppliers Report</a></li>
            <li><a href="report_categories.php">🗂️ Categories Report</a></li>
        </ul>
    </div>
    <div class="report-actions bottom">
    <a href="inventory_manager_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
</div>
</body>
</html>
