<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}
require 'db.php';

// Get total users per role
$roles = ['business_owner', 'inventory_manager', 'staff'];
$data = [];

foreach ($roles as $role) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM users WHERE user_account = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $data[$role] = $stmt->get_result()->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Role Breakdown Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <div class="report-header">
        <h2>🧑‍💼 Role Breakdown Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print Report</button>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>User Role</th>
                <th>Total Users</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Business Owner</td>
                <td><?= $data['business_owner'] ?></td>
            </tr>
            <tr>
                <td>Inventory Manager</td>
                <td><?= $data['inventory_manager'] ?></td>
            </tr>
            <tr>
                <td>Staff</td>
                <td><?= $data['staff'] ?></td>
            </tr>
        </tbody>
    </table>

      <div class="report-actions bottom">
    <a href="business_owner_reports.php" class="back-btn">⬅️ Back to Reports</a>
</div>
</div>
</body>
</html>
