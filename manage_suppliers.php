<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Add supplier logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supplier'])) {
    $name    = trim($_POST['name']);
    $contact = trim($_POST['contact_person']);
    $phone   = trim($_POST['phone']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);

    if ($name) {
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $contact, $phone, $email, $address);
        $stmt->execute();
        header("Location: manage_suppliers.php");
        exit;
    } else {
        $error = "Supplier name is required.";
    }
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Suppliers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>🚚 Manage Suppliers</h2>
    </header>

    <div class="dashboard-content">
        <h3>Add Supplier</h3>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

         <form method="POST" class="product-form">
            <input type="text" name="name" placeholder="Supplier Name" required>
            <input type="text" name="contact_person" placeholder="Contact Person">
            <input type="text" name="phone" placeholder="Phone Number">
            <input type="email" name="email" placeholder="Email Address">
            <textarea name="address" placeholder="Address"></textarea>
            <button type="submit" name="add_supplier">Add Supplier</button>
        </form>

        <h3>Existing Suppliers</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $suppliers->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['contact_person']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td>
                            <a href="edit_supplier.php?id=<?= $row['id'] ?>">Edit</a> |
                            <a href="delete_supplier.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</a>
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
