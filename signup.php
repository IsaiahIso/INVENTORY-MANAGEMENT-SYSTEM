<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = trim($_POST['first_name']);
    $last_name    = trim($_POST['last_name']);
    $email        = trim($_POST['email']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_account = $_POST['user_account'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($user_account)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, user_account) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $user_account);
        if ($stmt->execute()) {
            $success = "Account created successfully. <a href='login.php'>Login now</a>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="image-side"></div>
        <div class="form-side">
            <div class="form-wrapper">
                <h2>Sign Up</h2>

                <?php if (isset($error)) echo "<div class='message error'>$error</div>"; ?>
                <?php if (isset($success)) echo "<div class='message success'>$success</div>"; ?>

                <form method="POST">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="password" name="password" placeholder="Password" required>

                    <select name="user_account" required>
                        <option value="">Select User Type</option>
                        <option value="business_owner">Business Owner</option>
                        <option value="inventory_manager">Inventory Manager</option>
                        <option value="staff">Staff</option>
                    </select>

                    <button type="submit">Sign Up</button>
                </form>

                <div class="login-link">Already have an account? <a href="login.php">Sign In</a></div>
            </div>
        </div>
    </div>
</body>
</html>
