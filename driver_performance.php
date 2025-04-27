<?php
session_start();
include "./dbAL.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Driver') {
    header("Location: loginAL.php");
    exit();
}

$driver_id = $_SESSION['user_id'];

// Get shipment counts by delivery date (daily view)
$shipmentQuery = "SELECT DATE(delivery_time) AS delivery_date, COUNT(*) AS total 
                  FROM shipping_harvest 
                  WHERE shipping_status = 'Shipped' 
                  GROUP BY DATE(delivery_time)
                  ORDER BY delivery_date ASC";
$result = $conn->query($shipmentQuery);

$dataPoints = [];
while ($row = $result->fetch_assoc()) {
    $dataPoints[] = ['date' => $row['delivery_date'], 'total' => $row['total']];
}

// Get locations for the map
$locationQuery = "SELECT from_location, to_location FROM shipping_harvest WHERE shipping_status = 'Shipped'";
$locationResult = $conn->query($locationQuery);

$locations = [];
while ($row = $locationResult->fetch_assoc()) {
    $locations[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://unpkg.com/leaflet/dist/leaflet.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(#2c5f2d, rgb(25, 147, 204));
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        .sidebar img.logo {
            width: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidebar .profile-pic {
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            margin: 10px auto;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 12px 20px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .sidebar a:hover {
            background-color: rgb(63, 142, 232);
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        #chartContainer {
            width: 100%;
            max-width: 800px;
            margin-bottom: 30px;
        }

        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="profile_pic.png" alt="Logo" class="logo">
    <div class="profile_pic"></div>
    <a href="driver_dashboardAL.php">Dashboard</a>
    <a href="driverperformance.php">Performance</a>
</div>

<div class="main-content">
    <h2>Daily Shipments</h2>
    <div id="chartContainer">
        <canvas id="shipmentChart"></canvas>
    </div>

    <h2>Delivery Routes</h2>
    <div id="map"></div>
</div>

<script>
    const ctx = document.getElementById('shipmentChart').getContext('2d');
    const chartData = {
        labels: <?= json_encode(array_column($dataPoints, 'date')) ?>,
        datasets: [{
            label: 'Shipments',
            data: <?= json_encode(array_column($dataPoints, 'total')) ?>,
            backgroundColor: '#97bc62'
        }]
    };

    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Delivery Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Shipments'
                    }
                }
            }
        }
    });

    const map = L.map('map').setView([22.9734, 78.6569], 5); // Centered in India
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    <?php foreach ($locations as $loc): ?>
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=<?= urlencode($loc['from_location']) ?>`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                const fromLatLng = [data[0].lat, data[0].lon];
                L.marker(fromLatLng).addTo(map).bindPopup("From: <?= $loc['from_location'] ?>");

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=<?= urlencode($loc['to_location']) ?>`)
                    .then(res => res.json())
                    .then(data2 => {
                        if (data2.length > 0) {
                            const toLatLng = [data2[0].lat, data2[0].lon];
                            L.marker(toLatLng).addTo(map).bindPopup("To: <?= $loc['to_location'] ?>");
                            L.polyline([fromLatLng, toLatLng], {color: 'green'}).addTo(map);
                        }
                    });
            }
        });
    <?php endforeach; ?>
</script>

</body>
</html>
