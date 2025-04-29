<?php
include "dbAL.php";

$searchQuery = "";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $searchQuery = "WHERE hb.batch_id LIKE '%$searchTerm%' OR hb.crop_name LIKE '%$searchTerm%' OR hb.crop_type LIKE '%$searchTerm%'";
}

$harvestedBatchQuery = "
    SELECT hb.*, gc.expected_color, gc.expected_taste, gc.expected_shelf_life, gc.expected_freshness
    FROM harvested_batch hb
    LEFT JOIN grading_criteria gc ON hb.grading_id = gc.grading_id
    $searchQuery
";
$harvestedBatchResult = $conn->query($harvestedBatchQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvested Batch Grading</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        select {
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input {
            padding: 8px;
            width: 200px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .search-bar button {
            padding: 8px 12px;
            margin-left: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="inspector_dashboardAL.php">
            <button>Inspector Dashboard</button>
        </a>
        <a href="show_graded_batch_resultAL.php">
            <button>Show Graded Batch Results</button>
        </a>
    </div>

    <h1>Harvested Batch</h1>
    <a href="inspector_dashboardAL.php">
        <button class="back-button">Back</button>
    </a>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="POST">
            <input type="text" name="search" placeholder="Search by Batch ID, Crop Name..."
                value="<?php echo isset($searchTerm) ? htmlspecialchars($searchTerm) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <table border="1">
        <thead>
            <tr>
                <th>Batch ID</th>
                <th>Crop Name</th>
                <th>Crop Type</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th>Harvest Date</th>
                <th>Freshness</th>
                <th>Color</th>
                <th>Taste</th>
                <th>Shelf Life</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php if ($harvestedBatchResult->num_rows > 0): ?>
                <?php while ($batch = $harvestedBatchResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $batch['batch_id']; ?></td>
                        <td><?php echo $batch['crop_name']; ?></td>
                        <td><?php echo $batch['crop_type']; ?></td>
                        <td><?php echo $batch['quantity']; ?></td>
                        <td><?php echo $batch['weight']; ?></td>
                        <td><?php echo $batch['batch_date']; ?></td>
                        <td><?php echo $batch['expected_freshness'] ?? 'N/A'; ?></td>
                        <td><?php echo $batch['expected_color'] ?? 'N/A'; ?></td>
                        <td><?php echo $batch['expected_taste'] ?? 'N/A'; ?></td>
                        <td><?php echo $batch['expected_shelf_life'] ?? 'N/A'; ?></td>

                        <td>
                            <form action="inspector_grade_batch.php" method="POST" style="display:inline;">
                                <input type="hidden" name="batch_id" value="<?php echo $batch['batch_id']; ?>">
                                <input type="hidden" name="weight" value="<?php echo $batch['weight']; ?>">
                                <input type="hidden" name="quantity" value="<?php echo $batch['quantity']; ?>">
                                <input type="hidden" name="expected_color" value="<?php echo $batch['expected_color']; ?>">
                                <input type="hidden" name="expected_taste" value="<?php echo $batch['expected_taste']; ?>">
                                <input type="hidden" name="expected_shelf_life"
                                    value="<?php echo $batch['expected_shelf_life']; ?>">
                                <input type="hidden" name="expected_freshness"
                                    value="<?php echo $batch['expected_freshness']; ?>">
                                <select name="grading_criteria" required>
                                    <option value="">Select Grade</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="S">S</option>
                                </select>
                                <button type="submit">Grade</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" style="text-align: center;">No results found</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</body>

</html>

<?php
$conn->close();
?>