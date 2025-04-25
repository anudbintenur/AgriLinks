<?php
// Start session and include database connection
session_start();
include "./dbAL.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginAL.php");
    exit();
}

// Get logged-in farmer ID from session
$farmer_id = $_SESSION['user_id'];

// Fetch grading data
$sql_grading = "SELECT 
                    g.batch_id, 
                    g.inspector_id, 
                    g.inspection_date,
                    g.freshness, 
                    g.weight AS graded_weight, 
                    g.color, 
                    g.taste, 
                    g.shelf_life, 
                    g.grade,
                    hb.crop_name, 
                    hb.crop_type
                FROM graded_batch g 
                JOIN harvested_batch hb ON g.batch_id = hb.batch_id
                WHERE hb.farmer_id = ?";
$stmt_grading = $conn->prepare($sql_grading);
$stmt_grading->bind_param("i", $farmer_id);
$stmt_grading->execute();
$result_grading = $stmt_grading->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading - Farmer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        
        nav {
            background-color: #2c5f2d;
            padding: 10px 20px;
            text-align: center;
        }

        nav a {
            color: white;
            font-size: 16px;
            text-decoration: none;
            margin: 0 15px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .content {
            padding: 20px;
            background-color: white;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            text-align: center;
            color: #2c5f2d;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .summary-table th, .summary-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #97bc62;
            color: white;
            font-size: 16px;
        }

        .summary-table td {
            font-size: 14px;
            color: #333;
        }

        .summary-table tr:hover {
            background-color: #f1f1f1;
        }

        .summary-table td[colspan="8"] {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <nav>
        <a href="farmer_dashboardAL.php">Dashboard</a>
        <a href="harvest.php">Harvest</a>
        <a href="grading.php">Grading</a>
        <a href="shipping.php">Shipping</a>
    </nav>

    <div class="content">
        <h1>Grading Results</h1>
        
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Batch ID</th>
                    <th>Crop Name</th>
                    <th>Inspection Date</th>
                    <th>Grade</th>
                    <th>Freshness</th>
                    <th>Taste</th>
                    <th>Color</th>
                    <th>Shelf Life</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_grading->num_rows > 0): ?>
                    <?php while ($row_grading = $result_grading->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row_grading['batch_id'] ?></td>
                            <td><?= $row_grading['crop_name'] ?> (<?= $row_grading['crop_type'] ?>)</td>
                            <td><?= date('d M Y', strtotime($row_grading['inspection_date'])) ?></td>
                            <td><?= $row_grading['grade'] ?></td>
                            <td><?= $row_grading['freshness'] ?> / 10</td>
                            <td><?= $row_grading['taste'] ?></td>
                            <td><?= $row_grading['color'] ?></td>
                            <td><?= $row_grading['shelf_life'] ?> days</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No grading results available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
