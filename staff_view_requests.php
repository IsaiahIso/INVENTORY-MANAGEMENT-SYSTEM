 <?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'staff') {
    header("Location: login.php");
    exit;
}

require 'db.php';
$staff_id = $_SESSION['user_id'];

$requests = $conn->query("
    SELECT r.id, p.name AS product, r.quantity, r.reason, r.status, r.request_date
    FROM stock_requests r
    JOIN products p ON r.product_id = p.id
    WHERE r.staff_id = $staff_id
    ORDER BY r.request_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Stock Requests</title>
    <link rel="stylesheet" href="styles.css">
        
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📄 My Stock Transfer Requests</h2>
    </header>

    <div class="dashboard-content">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($requests->num_rows > 0): ?>
                    <?php while ($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['product']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['request_date'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No stock requests yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="logout-bottom">
            <a href="staff.php" class="back-btn">⬅️ Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
