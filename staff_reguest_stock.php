<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'staff') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch products for dropdown
$products = $conn->query("SELECT id, name FROM products");

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $reason = trim($_POST['reason']);
    $staff_id = $_SESSION['user_id'];

    if ($product_id && $quantity > 0) {
        $stmt = $conn->prepare("INSERT INTO stock_requests (staff_id, product_id, quantity, reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $staff_id, $product_id, $quantity, $reason);
        $stmt->execute();
        $success = "Stock request submitted successfully!";
    } else {
        $error = "Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Stock Transfer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-box">
        <h2>📦 Request Stock Transfer</h2>

        <?php if (isset($success)): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="product_id">Select Product:</label>
            <select name="product_id" required>
                <option value="">-- Select --</option>
                <?php while ($p = $products->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="quantity">Quantity Needed:</label>
            <input type="number" name="quantity" min="1" required>

            <label for="reason">Reason for Request:</label>
            <textarea name="reason" rows="3" placeholder="Optional..."></textarea>

            <button type="submit">Submit Request</button>
        </form>
        <div class="report-actions bottom">
    <a href="staff.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
    </div>
</body>
</html>
