<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}
require 'db.php';
$users = $conn->query("SELECT * FROM users ORDER BY user_account ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <div class="report-header">
        <h2>👥 User Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </div>
    <table class="product-table">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Role</th></tr>
        </thead>
        <tbody>
            <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['first_name'] . ' ' . $u['last_name'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['user_account'] ?></td>
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
