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

// Add harvest entry logic
if (isset($_POST['add_harvest'])) {
    $crop_name = $_POST['crop_name'];
    $crop_type = $_POST['crop_type'];
    $batch_date = $_POST['batch_date'];
    $weight = $_POST['weight'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO harvested_batch (farmer_id, crop_name, crop_type, batch_date, weight, quantity) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdii", $farmer_id, $crop_name, $crop_type, $batch_date, $weight, $quantity);
    $stmt->execute();
}

// Delete harvest entry logic
if (isset($_GET['delete_batch_id'])) {
    $batch_id = $_GET['delete_batch_id'];
    $sql = "DELETE FROM harvested_batch WHERE batch_id = ? AND farmer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $batch_id, $farmer_id);
    $stmt->execute();
    header("Location: harvest.php"); // Redirect to refresh the page
    exit();
}

// Fetch harvest data
$sql = "SELECT 
            batch_id,
            crop_name,
            crop_type,
            batch_date AS date_added,
            weight,
            quantity
        FROM harvested_batch 
        WHERE farmer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest - Farmer Dashboard</title>
    <style>
        /* General styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Navbar and header styles */
        .navbar {
            background-color: #4CAF50;
            padding: 15px 0;
            color: white;
            text-align: center;
            font-size: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            padding: 10px 15px;
        }

        .navbar a:hover {
            background-color: #45a049;
            border-radius: 5px;
        }

        /* Main content */
        .content {
            padding: 25px;
        }

        /* Header Styling */
        .header {
            background-color: #2E7D32;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .header .farmer-name {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        /* Add harvest form */
        .add-harvest-form input, .add-harvest-form select {
            margin-bottom: 10px;
            padding: 10px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        .add-harvest-form button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1rem;
        }

        .add-harvest-form button:hover {
            background-color: #45a049;
        }

        /* Harvest table styling */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .summary-table th, .summary-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #2E7D32;
            color: white;
        }

        .summary-table tr:hover {
            background-color: #f1f1f1;
        }

        .action-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        /* Footer for page */
        .footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

    </style>
</head>
<body>

    <div class="navbar">
        <a href="farmer_dashboardAL.php">Dashboard</a>
        <a href="harvest.php">Harvest</a>
        <a href="grading.php">Grading</a>
        <a href="shipping.php">Shipping</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Manage Your Harvest</h1>
            <p class="farmer-name">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        </div>

        <!-- Add Harvest Form -->
        <h3>Add Harvest Entry</h3>
        <form class="add-harvest-form" method="POST">
            <input type="text" name="crop_name" placeholder="Crop Name" required><br>
            <input type="text" name="crop_type" placeholder="Crop Type" required><br>
            <input type="date" name="batch_date" required><br>
            <input type="number" name="weight" placeholder="Weight (kg)" required step="0.01"><br>
            <input type="number" name="quantity" placeholder="Quantity" required><br>
            <button type="submit" name="add_harvest">Add Harvest</button>
        </form>

        <!-- Harvest Table -->
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Batch ID</th>
                    <th>Crop Name</th>
                    <th>Crop Type</th>
                    <th>Date Added</th>
                    <th>Weight (kg)</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['batch_id'] ?></td>
                            <td><?= $row['crop_name'] ?></td>
                            <td><?= $row['crop_type'] ?></td>
                            <td><?= date('d M Y', strtotime($row['date_added'])) ?></td>
                            <td><?= $row['weight'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>
                                <a href="harvest.php?delete_batch_id=<?= $row['batch_id'] ?>" class="action-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No harvest data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>AgriLinks &copy; 2025</p>
    </div>

</body>
</html>
