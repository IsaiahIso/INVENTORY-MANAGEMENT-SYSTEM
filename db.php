<?php
$servername = 'localhost';
$username = 'root';  
$password = 'ISAIAH254';      
$db_name  = 'inventory_management';

$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>