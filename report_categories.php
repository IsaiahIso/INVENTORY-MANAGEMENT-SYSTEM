<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Report</title>
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
                    <th>Category Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['name']) ?></td>
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
