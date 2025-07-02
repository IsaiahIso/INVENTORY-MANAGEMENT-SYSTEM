<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Fetch dropdown data
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$suppliers  = $conn->query("SELECT id, name FROM suppliers ORDER BY name");

// Handle Add Product form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name        = trim($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $stock       = intval($_POST['stock']);
    $price       = floatval($_POST['price']);

    if ($name && $category_id && $supplier_id && $stock >= 0 && $price >= 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, category_id, supplier_id, stock, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiid", $name, $category_id, $supplier_id, $stock, $price);
        if ($stmt->execute()) {
            header("Location: manage_products.php");
            exit;
        } else {
            $error = "Failed to add product: " . $conn->error;
        }
    } else {
        $error = "All fields are required and must be valid.";
    }
}

// Fetch all products with joined category and supplier names
$products = $conn->query("
    SELECT p.id, p.name, c.name AS category, s.name AS supplier, p.stock, p.price
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN suppliers s ON p.supplier_id = s.id
    ORDER BY p.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>📦 Manage Products</h2>
         
    </header>

    <div class="dashboard-content">
        <h3>Add New Product</h3>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" class="product-form">
            <input type="text" name="name" placeholder="Product Name" required>

            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <select name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($sup = $suppliers->fetch_assoc()): ?>
                    <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <input type="number" name="stock" placeholder="Stock Quantity" min="0" required>
            <input type="number" name="price" step="0.01" placeholder="Price (Ksh)" min="0" required>

            <button type="submit" name="add_product">Add Product</button>
        </form>

        <h3>Product List</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Stock</th>
                    <th>Price (Ksh)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
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
