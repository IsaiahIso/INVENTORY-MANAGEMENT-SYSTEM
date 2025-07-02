<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'inventory_manager') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = intval($_POST['product_id']);
    $new_stock = intval($_POST['stock']);

    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stock, $product_id);
    $stmt->execute();

    header("Location: view_products.php");
    exit;
}

// Fetch all products
$products = $conn->query("SELECT p.id, p.name, p.stock, p.price, c.name AS category, s.name AS supplier 
                          FROM products p
                          JOIN categories c ON p.category_id = c.id
                          JOIN suppliers s ON p.supplier_id = s.id
                          ORDER BY p.name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View & Update Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📋 View & Update Stock</h2>
    </header>

    <div class="dashboard-content">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Price (Ksh)</th>
                    <th>Current Stock</th>
                    <th>Update Stock</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td>
                        <form method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="number" name="stock" value="<?= $row['stock'] ?>" min="0" style="width: 70px;">
                            <button type="submit" name="update_stock" class="btn-edit">Update</button>
                        </form>
                    </td>
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
