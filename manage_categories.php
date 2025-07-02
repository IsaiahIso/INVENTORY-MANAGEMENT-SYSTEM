<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Add category logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if ($name) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        header("Location: manage_categories.php");
        exit;
    } else {
        $error = "Category name is required.";
    }
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>🗂️ Manage Categories</h2>
        
    </header>

    <div class="dashboard-content">
        <h3>Add Category</h3>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" class="product-form">
            <input type="text" name="name" placeholder="Category Name" required>
            <button type="submit" name="add_category" class="back-btn">Add</button>
        </form>

        <h3>Existing Categories</h3>
        <ul>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <li><?= htmlspecialchars($row['name']) ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <div class="report-actions bottom">
    <a href="business_owner_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
</div>
</body>
</html>
