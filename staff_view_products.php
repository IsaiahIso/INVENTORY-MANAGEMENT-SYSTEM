<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'staff') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Handle optional search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$query = "SELECT p.name, c.name AS category, s.name AS supplier, p.stock, p.price
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          LEFT JOIN suppliers s ON p.supplier_id = s.id";

if ($search !== '') {
    $query .= " WHERE p.name LIKE '%$search%' OR c.name LIKE '%$search%' OR s.name LIKE '%$search%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <h2>📦 Product Inventory</h2>

    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by product, category, or supplier" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <table class="product-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Stock</th>
                <th>Price (Ksh)</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No products found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="report-actions bottom">
    <a href="staff.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
</div>
</body>
</html>
