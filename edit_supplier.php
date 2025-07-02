<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid supplier ID.");
}

// Get supplier details
$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$supplier = $stmt->get_result()->fetch_assoc();

if (!$supplier) {
    die("Supplier not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE suppliers SET name=?, contact=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $contact, $email, $id);
    $stmt->execute();

    header("Location: manage_suppliers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>✏️ Edit Supplier</h2>
        <a class="logout-btn" href="logout.php">Logout</a>
    </header>
    <div class="dashboard-content">
        <form method="POST" class="product-form">
            <label for="name">Supplier Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($supplier['name']) ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($supplier['contact_person']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($supplier['email']) ?>" required>

            <button type="submit">Update Supplier</button>
        </form>
    </div>
</div>
</body>
</html>
