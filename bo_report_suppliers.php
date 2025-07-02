<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$suppliers = $conn->query("SELECT * FROM suppliers");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <div class="report-header">
        <h2>🚚 Supplier Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </div>
    <table class="product-table">
        <thead>
            <tr><th>Name</th><th>Contact</th><th>Phone</th><th>Email</th><th>Address</th></tr>
        </thead>
        <tbody>
            <?php while ($s = $suppliers->fetch_assoc()): ?>
            <tr>
                <td><?= $s['name'] ?></td>
                <td><?= $s['contact_person'] ?></td>
                <td><?= $s['phone'] ?></td>
                <td><?= $s['email'] ?></td>
                <td><?= $s['address'] ?></td>
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
