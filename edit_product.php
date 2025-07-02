<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid product ID.");
}

// Fetch dropdown data
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$suppliers  = $conn->query("SELECT id, name FROM suppliers ORDER BY name");

// Get product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, supplier_id=?, stock=?, price=? WHERE id=?");
    $stmt->bind_param("siiidi", $name, $category_id, $supplier_id, $stock, $price, $id);
    $stmt->execute();

    header("Location: manage_products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>✏️ Edit Product</h2>
        <a class="logout-btn" href="logout.php">Logout</a>
    </header>
    <div class="dashboard-content">
        <form method="POST" class="product-form">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="supplier_id">Supplier:</label>
            <select name="supplier_id" id="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($sup = $suppliers->fetch_assoc()): ?>
                    <option value="<?= $sup['id'] ?>" <?= $product['supplier_id'] == $sup['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sup['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="stock">Stock Quantity:</label>
            <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" min="0" required>

            <label for="price">Price (Ksh):</label>
            <input type="number" id="price" name="price" value="<?= $product['price'] ?>" step="0.01" min="0" required>

            <button type="submit">Update Product</button>
        </form>
    </div>
</div>
</body>
</html>
