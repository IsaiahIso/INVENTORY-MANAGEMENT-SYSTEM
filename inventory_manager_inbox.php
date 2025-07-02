<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$messages = $conn->query("
    SELECT m.*, u.first_name AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.receiver_id = $user_id
    ORDER BY m.sent_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Inbox</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <h2>📥 Your Inbox</h2>
    <?php if ($messages->num_rows > 0): ?>
        <table class="product-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['sender_name']) ?></td>
                        <td><?= htmlspecialchars($msg['subject']) ?></td>
                        <td><?= nl2br(htmlspecialchars($msg['body'])) ?></td>
                        <td><?= $msg['sent_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>
    <div class="report-actions bottom">
    <a href="inventory_manager_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
</div>
</body>
</html>
