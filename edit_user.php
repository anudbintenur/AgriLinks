<?php
include "./dbAL.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    echo "Access Denied";
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid user ID.";
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM user_dataal WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE user_dataal SET username=?, role=?, name=?, email=?, dob=?, address=? WHERE id=?");
    $stmt->bind_param("ssssssi", $username, $role, $name, $email, $dob, $address, $id);
    $stmt->execute();

    header("Location: admin_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
<h2>Edit User</h2>
<form method="POST">
    Username: <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>
    Role: 
    <select name="role" required>
        <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
        <option value="User" <?= $user['role'] === 'User' ? 'selected' : '' ?>>User</option>
    </select><br><br>
    Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>
    DOB: <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required><br><br>
    Address: <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required><br><br>
    <button type="submit">Update User</button>
    <a href="admin_home.php"><button type="button">Cancel</button></a>
</form>
</body>
</html>
