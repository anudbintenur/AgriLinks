<?php
session_start();
echo "Welcome, " . $_SESSION['admin_username'];
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AgriLinks Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background: #f1f5f9;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #2c5f2d, #97bc62);
      color: #fff;
      height: 100vh;
      position: fixed;
      padding-top: 20px;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 24px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      padding: 15px 20px;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
      transition: background 0.3s;
    }

    .sidebar ul li a:hover {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 5px;
    }

    /* Main content */
    .main-content {
      margin-left: 250px;
      padding: 20px;
      flex: 1;
    }

    .main-content h1 {
      font-size: 28px;
      color: #2c5f2d;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>AgriLinks Admin</h2>
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="farmer_dashboardAL.php">Farmer Dashboard</a></li>
      <li><a href="driver_dashboardAL.php">Driver Dashboard</a></li>
      <li><a href="#">Inspector Dashboard</a></li>
      <li><a href="#">Seller Dashboard</a></li>
      <li><a href="#">Grading Dashboard</a></li>
      <li><a href="#">Shipping Dashboard</a></li>
      <li><a href="#">Reports</a></li>
      <li><a href="#">Settings</a></li>
      <li><a href="#">Logout</a></li>
    </ul>
  </div>

  <!-- Main content area -->
  <div class="main-content">
    <h1>Welcome, Admin!</h1>
    <p>Select a dashboard from the sidebar to manage different sections.</p>
  </div>

</body>
</html>
