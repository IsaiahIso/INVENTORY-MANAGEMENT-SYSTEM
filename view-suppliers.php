<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch suppliers
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Suppliers</title>
    <link rel="stylesheet" href="styles.css">
     
</head>
<body>
    <div class="dashboard-wrapper">
     <header class="dashboard-header">
        <h2>🚚 Supplier List</h2>
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
                <?php while ($row = $suppliers->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['contact_person']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="report-actions bottom">
    <a href="inventory_manager_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
    </div>
    </div>
</body>
</html>
