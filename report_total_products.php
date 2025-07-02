<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$products = $conn->query("SELECT p.name, c.name AS category, s.name AS supplier, stock, price
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id
                          LEFT JOIN suppliers s ON p.supplier_id = s.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Products Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📦 Total Products Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </header>

    <div class="dashboard-content">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Stock</th>
                    <th>Price (Ksh)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['supplier']) ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
         <div class="report-actions bottom">
    <a href="inventory_manager_reports.php" class="back-btn">⬅️ Back to Reports</a>
</div>
    </div>
</div>
</body>
</html>
