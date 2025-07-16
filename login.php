<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']      = $user['id'];
            $_SESSION['first_name']   = $user['first_name'];
            $_SESSION['user_account'] = $user['user_account'];

            // Redirect by role
            switch ($user['user_account']) {
                case 'business_owner':
                    header("Location: business_owner_dashboard.php");
                    break;
                case 'inventory_manager':
                    header("Location: inventory_manager_dashboard.php");
                    break;
                case 'staff':
                    header("Location:  staff.php");
                    break;
                default:
                    echo "Unknown user role.";
            }
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="image-side"></div>
        <div class="form-side">
            <div class="form-wrapper">
                <h2>Login</h2>

                <?php if (isset($error)) echo "<div class='message error'>$error</div>"; ?>

                <form method="POST">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <span class="toggle-password" onclick="togglePassword()">👁️</span>
                    </div>
                    <button type="submit">Login</button>
                </form>

                <div class="login-link">Don't have an account? <a href="signup.php">Sign Up</a></div>
            </div>
        </div>
    </div>

     <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.querySelector(".toggle-password");
            const isPassword = input.type === "password";

            input.type = isPassword ? "text" : "password";
            icon.textContent = isPassword ? "🙈" : "👁️";
        }
    </script>
</body>
</html>
