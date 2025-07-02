<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id   = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $subject     = trim($_POST['subject']);
    $body        = trim($_POST['body']);

    if ($receiver_id && $subject && $body) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $sender_id, $receiver_id, $subject, $body);
        $stmt->execute();
        $success = "Message sent!";
    } else {
        $error = "All fields are required.";
    }
}

$users = $conn->query("SELECT id, first_name, user_account FROM users WHERE id != {$_SESSION['user_id']}");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <h2>✉️ Send a Message</h2>

    <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>

    <form method="POST" class="product-form">
        <label>To:</label>
        <select name="receiver_id" required>
            <option value="">Select User</option>
            <?php while ($user = $users->fetch_assoc()): ?>
                <option value="<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['first_name']) ?> (<?= $user['user_account'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Subject:</label>
        <input type="text" name="subject" placeholder="Enter subject" required>

        <label>Message:</label>
        <textarea name="body" placeholder="Enter your message..." required></textarea>

        <button type="submit">Send</button>
    </form>
</div>
</body>
</html>
