<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📊 Business Owner Reports</h2>
    </header>

    <div class="dashboard-content">
        <ul class="dashboard-links">
            <li><a href="bo_report_products.php">📦 Product Report</a></li>
            <li><a href="bo_report_users.php">👥 User Report</a></li>
            <li><a href="bo_report_suppliers.php">🚚 Supplier Report</a></li>
            <li><a href="bo_report_low_stock.php">⚠️ Low Stock Report</a></li>
            <li><a href="bo_report_stock_summary.php">📊 Stock Summary</a></li>
            <li><a href="bo_report_roles.php">🧑‍💼 Role Breakdown Report</a></li>
        </ul>
    </div>
     <div class="report-actions bottom">
    <a href="business_owner_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
</div>
</body>
</html>
