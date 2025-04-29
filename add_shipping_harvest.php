<?php
include 'dbAL.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $batch_id = intval($_POST['batch_id']);
    $farmer_id = intval($_POST['farmer_id']);
    $crop_name = $_POST['crop_name'];
    $quantity = intval($_POST['quantity']);
    $weight = floatval($_POST['weight']);
    $farmer_name = $_POST['farmer_name'];
    $from_location = $_POST['from_location'];
    $to_location = $_POST['to_location'];
    $shipping_status = $_POST['shipping_status'];
    $delivery_time = $_POST['delivery_time'];

    $sql = "INSERT INTO shipping_harvest 
            (batch_id, farmer_id, crop_name, quantity, weight, farmer_name, from_location, to_location, shipping_status, delivery_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iisidsssss", $batch_id, $farmer_id, $crop_name, $quantity, $weight, $farmer_name, $from_location, $to_location, $shipping_status, $delivery_time);
        if ($stmt->execute()) {
            $message = "<p class='success'>New Shipping Harvest record added successfully!</p>";
        } else {
            $message = "<p class='error'>Execute failed: " . htmlspecialchars($stmt->error) . "</p>";
        }
    } else {
        $message = "<p class='error'>Prepare failed: " . htmlspecialchars($conn->error) . "</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Shipping Harvest</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            margin-top: 10px;
            color: #555;
        }

        form input,
        form select,
        form button.fetch-btn {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button[type="submit"]:hover {
            background: #45a049;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        .fetch-btn {
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        .fetch-btn:hover {
            background-color: #2980b9;
        }
    </style>

    <script>
        function fetchBatchData() {
            var batchId = document.getElementsByName('batch_id')[0].value;

            if (!batchId) {
                alert("Please enter a Batch ID first.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_batch_data.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.status === "success") {
                        var data = response.data;
                        document.getElementsByName('farmer_id')[0].value = data.farmer_id || "";
                        document.getElementsByName('crop_name')[0].value = data.crop_name || "";
                        document.getElementsByName('quantity')[0].value = data.quantity || "";
                        document.getElementsByName('weight')[0].value = data.weight || "";
                        document.getElementsByName('farmer_name')[0].value = data.farmer_name || "";
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send("batch_id=" + batchId);
        }
    </script>
</head>

<body>
    <div style="position: absolute; top: 20px; left: 20px;">
        <a href="packer_dashboardAL.php"
            style="text-decoration: none; padding: 10px 20px; background: #3498db; color: white; border-radius: 5px; font-size: 14px;">Back</a>
    </div>

    <div class="container">
        <h2>Add Shipping Harvest</h2>
        <?php echo $message; ?>
        <form method="post" action="">
            <label>Batch ID:</label>
            <input type="number" name="batch_id" required>
            <button type="button" class="fetch-btn" onclick="fetchBatchData()">Fetch Batch Info</button>

            <label>Farmer ID:</label>
            <input type="number" name="farmer_id" readonly required>

            <label>Crop Name:</label>
            <input type="text" name="crop_name" readonly required>

            <label>Quantity:</label>
            <input type="number" name="quantity" readonly required>

            <label>Weight (kg):</label>
            <input type="text" name="weight" readonly required>


            <label>Farmer Name:</label>
            <input type="text" name="farmer_name" required>

            <label>From Location:</label>
            <input type="text" name="from_location" required>

            <label>To Location:</label>
            <input type="text" name="to_location" required>

            <label>Shipping Status:</label>
            <select name="shipping_status" required>
                <option value="Pending">Pending</option>
                <option value="Shipped">Shipped</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>

            <label>Delivery Time:</label>
            <input type="datetime-local" name="delivery_time" required>

            <button type="submit">Add Shipping Harvest</button>
        </form>
    </div>

</body>

</html>