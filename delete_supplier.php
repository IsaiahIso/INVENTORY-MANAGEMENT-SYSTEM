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

$stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_suppliers.php");
exit;
?>
