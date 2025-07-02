<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}
require 'db.php';

// Fetch all products with stock, price, and total value
$summary = $conn->query("SELECT name, stock, price, (stock * price) AS total_value FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Summary Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <div class="report-header">
        <h2>📊 Stock Summary Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Unit Price (Ksh)</th>
                <th>Total Value (Ksh)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $summary->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['stock'] ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td><?= number_format($row['total_value'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

     <div class="report-actions bottom">
    <a href="business_owner_reports.php" class="back-btn">⬅️ Back to Reports</a>
</div>
</div>
</body>
</html>
