<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_account'], ['business_owner', 'inventory_manager'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>🗂️ View Categories</h2>
    </header>

    <div class="dashboard-content">
        <h3>Category List</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
     <div class="report-actions bottom">
    <a href="inventory_manager_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
</div>
</body>
</html>
 