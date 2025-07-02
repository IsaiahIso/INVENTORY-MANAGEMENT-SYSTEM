<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Handle new user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $first_name   = trim($_POST['first_name']);
    $last_name    = trim($_POST['last_name']);
    $email        = trim($_POST['email']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_account = $_POST['user_account'];

    if ($first_name && $last_name && $email && $user_account) {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, user_account) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $user_account);
        $stmt->execute();
        header("Location: manage_users.php");
        exit;
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <h2>👥 Manage Users</h2>
         
    </header>

    <div class="dashboard-content">
        <h3>Add New User</h3>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" class="product-form">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_account" required>
                <option value="">-- Select Role --</option>
                <option value="business_owner">Business Owner</option>
                <option value="inventory_manager">Inventory Manager</option>
                <option value="staff">Staff</option>
            </select>
            <button type="submit" name="add_user">Add User</button>
        </form>

        <h3>User List</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Account Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['user_account']) ?></td>
                    <td>
                        <a class="edit-btn" href="edit.php?table=users&id=<?= $user['id'] ?>">Edit</a> |
                        <a class="delete-btn" href="delete.php?table=users&id=<?= $user['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
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
