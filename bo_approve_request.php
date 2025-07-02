<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_account'], ['business_owner', 'inventory_manager'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';
require_once 'send_email.php'; // Make sure you’ve created this file for PHPMailer

// Handle Approve/Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    if (in_array($action, ['Approved', 'Rejected'])) {
        // Update the status
        $stmt = $conn->prepare("UPDATE stock_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $request_id);
        $stmt->execute();

        // Send email notification to staff
        $query = $conn->query("
            SELECT u.email, u.first_name, p.name AS product
            FROM stock_requests r
            JOIN users u ON r.staff_id = u.id
            JOIN products p ON r.product_id = p.id
            WHERE r.id = $request_id
        ");
        $user = $query->fetch_assoc();

        if ($user) {
            $subject = "Your Stock Request Has Been $action";
            $body = "
                <p>Hi <strong>{$user['first_name']}</strong>,</p>
                <p>Your stock request for <strong>{$user['product']}</strong> has been <strong>$action</strong> by the business owner.</p>
            ";
            sendEmail($user['email'], $subject, $body);
        }
    }
}

// Fetch all stock requests
$requests = $conn->query("
    SELECT r.id, u.first_name, u.last_name, u.email, p.name AS product, r.quantity, r.reason, r.status, r.request_date
    FROM stock_requests r
    JOIN users u ON r.staff_id = u.id
    JOIN products p ON r.product_id = p.id
    ORDER BY r.request_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Stock Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <header class="dashboard-header">
            <h2>🛠️ Approve Stock Transfer Requests</h2>
            
        </header>

        <div class="dashboard-content">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Email</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['product']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td><span class="request-status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['request_date'])) ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <form method="POST"  class="request-action-form">
                                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="action" value="Approved" class="request-action-btn approve">Approve</button>
                                        <button type="submit" name="action" value="Rejected" class="request-action-btn reject">Reject</button>
                                    </form>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="report-actions bottom">
    <a href="business_owner_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
             
        </div>
    </div>
</body>
</html>
