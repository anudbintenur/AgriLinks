<?php
// Start session and include database connection
session_start();
include "./dbAL.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Driver' && $_SESSION['role'] !== 'Admin') {
    header("Location: loginAL.php");
    exit();
}

// Get logged-in warehouse manager ID from session
$manager_id = $_SESSION['user_id'];

// Fetch incoming stock (graded batches) data
$sql = "SELECT 
            g.batch_id, 
            hb.crop_name, 
            hb.crop_type, 
            g.inspection_date, 
            g.weight AS graded_weight,
            g.grade
        FROM graded_batch g
        JOIN harvested_batch hb ON g.batch_id = hb.batch_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for charts
$monthlyStockData = [];
$cropStockData = [];
while ($row = $result->fetch_assoc()) {
    $month = date('M Y', strtotime($row['inspection_date']));
    $monthlyStockData[$month] = ($monthlyStockData[$month] ?? 0) + $row['graded_weight'];

    $crop = $row['crop_name'];
    $cropStockData[$crop] = ($cropStockData[$crop] ?? 0) + $row['graded_weight'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Manager Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Same vibrant green theme */
        :root {
            --primary-dark: #1B5E20;
            --primary-medium: #2E7D32;
            --primary-light: #4CAF50;
            --accent-green: #8BC34A;
            --background: #F1F8E9;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
            padding: 15px 30px;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo img {
            height: 50px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        .username {
            font-size: 1.4rem;
            font-weight: 600;
        }
        nav {
            background-color: var(--primary-dark);
            width: 240px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        nav .profile {
            text-align: center;
            margin-bottom: 30px;
        }
        nav .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid var(--accent-green);
            margin-bottom: 10px;
        }
        nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        nav a:hover {
            background-color: var(--primary-light);
            transform: translateX(5px);
        }
        .dashboard {
            margin-left: 260px;
            padding: 25px;
        }
        .chart-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #E0E0E0;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .summary-table th {
            background-color: var(--primary-medium);
            color: white;
            padding: 15px;
            text-align: left;
        }
        .summary-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #EEEEEE;
        }
        .summary-table tr:hover {
            background-color: #F5F5F5;
        }
        canvas {
            max-height: 300px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="logo">
            <img src="agrilinkLogo.webp" alt="Logo">
        </div>
        <div class="username">
            <span>Welcome, Warehouse Manager!</span>
        </div>
    </div>

    <nav>
        <div class="profile">
            <img src="profile_pic.png" alt="Profile Picture">
            <div><?= htmlspecialchars($_SESSION['username']) ?></div>
        </div>
        <a href="#">Dashboard</a>
        <a href="incoming_batches.php">Incoming Batches</a>
        <a href="inventory.php">Inventory</a>
        <a href="shipping_orders.php">Shipping Orders</a>
    </nav>

    <div class="dashboard">
        <h1>Warehouse Manager Dashboard</h1>

        <div class="chart-container">
            <div class="card">
                <h2>Monthly Incoming Stock</h2>
                <canvas id="monthlyChart"></canvas>
            </div>
            <div class="card">
                <h2>Crop Storage Overview</h2>
                <canvas id="cropChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Received Graded Batches</h2>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Batch ID</th>
                        <th>Crop Name</th>
                        <th>Inspection Date</th>
                        <th>Grade</th>
                        <th>Weight (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php mysqli_data_seek($result, 0); while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['batch_id'] ?></td>
                                <td><?= $row['crop_name'] ?> (<?= $row['crop_type'] ?>)</td>
                                <td><?= date('d M Y', strtotime($row['inspection_date'])) ?></td>
                                <td><?= $row['grade'] ?></td>
                                <td><?= $row['graded_weight'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No incoming stock data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const monthlyStockData = <?= json_encode($monthlyStockData); ?>;
        const cropStockData = <?= json_encode($cropStockData); ?>;

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Monthly Stock Chart
        new Chart(document.getElementById("monthlyChart"), {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyStockData),
                datasets: [{
                    label: "Total Weight (kg)",
                    data: Object.values(monthlyStockData),
                    backgroundColor: '#4CAF50',
                    borderColor: '#388E3C',
                    borderWidth: 1
                }]
            }
        });

        // Crop Storage Chart (Pie Chart)
        const cropColors = Object.keys(cropStockData).map(getRandomColor);

        new Chart(document.getElementById("cropChart"), {
            type: 'pie',
            data: {
                labels: Object.keys(cropStockData),
                datasets: [{
                    label: "Crop Storage",
                    data: Object.values(cropStockData),
                    backgroundColor: cropColors,
                    borderColor: '#388E3C',
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
