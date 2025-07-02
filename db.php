<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$username   = $_ENV['DB_USERNAME'];  // 🔁 Matches .env
$password   = $_ENV['DB_PASSWORD'];  // 🔁 Matches .env
$db_name    = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
