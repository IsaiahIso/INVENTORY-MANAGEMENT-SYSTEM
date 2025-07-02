<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$stock = $conn->query("SELECT name, stock FROM products ORDER BY stock DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Stock Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📊 Total Stock Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </header>

    <div class="dashboard-content">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stock->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['stock'] ?></td>
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
