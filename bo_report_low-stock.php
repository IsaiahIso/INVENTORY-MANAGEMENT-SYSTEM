<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$low_stock = $conn->query("SELECT p.name, c.name AS category, s.name AS supplier, stock
                           FROM products p
                           LEFT JOIN categories c ON p.category_id = c.id
                           LEFT JOIN suppliers s ON p.supplier_id = s.id
                           WHERE stock < 5");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Reports </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
     <div class="report-header">
    <h2>⚠️ Low Stock Items Report</h2>
    <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
</div>


    <div class="dashboard-content">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $low_stock->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['supplier']) ?></td>
                        <td><?= $row['stock'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
          <div class="report-actions bottom">
    <a href="business_owner_reports.php" class="back-btn">⬅️ Back to Reports</a>
</div>

    </div>
</div>
</body>
</html>
