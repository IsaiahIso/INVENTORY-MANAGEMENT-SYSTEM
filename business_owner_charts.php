<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_account'] !== 'business_owner') {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Chart Data: Products per Category
$categoryData = $conn->query("
    SELECT c.name, COUNT(p.id) AS total 
    FROM categories c 
    LEFT JOIN products p ON c.id = p.category_id 
    GROUP BY c.name
");

$categories = [];
$categoryTotals = [];
while ($row = $categoryData->fetch_assoc()) {
    $categories[] = $row['name'];
    $categoryTotals[] = $row['total'];
}

// Chart Data: User Roles
$roles = ['business_owner', 'inventory_manager', 'staff'];
$roleLabels = ['Business Owner', 'Inventory Manager', 'Staff'];
$roleCounts = [];

foreach ($roles as $role) {
    $result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE user_account = '$role'");
    $count = $result->fetch_assoc()['total'];
    $roleCounts[] = $count;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Business Owner Charts</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
        }
        canvas {
            margin: 30px 0;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .back-link {
            text-align: center;
            margin-top: 40px;
        }
        .back-link a {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .back-link a:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="chart-container">
        <h2 style="text-align:center;">📈 Inventory and User Analytics</h2>

        <canvas id="categoryChart"></canvas>
        <canvas id="userRolesChart"></canvas>

          <div class="report-actions bottom">
    <a href="business_owner_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
</div>
    </div>

    <script>
        // Bar Chart - Products by Category
        const ctx1 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?= json_encode($categories) ?>,
                datasets: [{
                    label: 'Products per Category',
                    data: <?= json_encode($categoryTotals) ?>,
                    backgroundColor: '#7b2cbf'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Products by Category' }
                }
            }
        });

        // Pie Chart - User Roles
        const ctx2 = document.getElementById('userRolesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?= json_encode($roleLabels) ?>,
                datasets: [{
                    data: <?= json_encode($roleCounts) ?>,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'User Roles Distribution' }
                }
            }
        });
    </script>
</body>
</html>
