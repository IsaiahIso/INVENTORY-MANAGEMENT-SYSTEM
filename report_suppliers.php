<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>🚚 Suppliers Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </header>

    <div class="dashboard-content">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = $suppliers->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['contact_person']) ?></td>
                        <td><?= htmlspecialchars($s['phone']) ?></td>
                        <td><?= htmlspecialchars($s['email']) ?></td>
                        <td><?= htmlspecialchars($s['address']) ?></td>
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
