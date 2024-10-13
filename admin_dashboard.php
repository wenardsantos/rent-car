<?php
session_start();

// Check if role is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit();
}

include './connect_db.php';

// Fetch all cars from database
$result = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Manage Cars</h2>
    <a href="add_car.html">Add New Car</a>

    <table border="1">
        <tr>
            <th>Car ID</th>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
        <?php while ($car = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $car['car_id'] ?></td>
            <td><?= $car['make'] ?></td>
            <td><?= $car['model'] ?></td>
            <td><?= $car['year'] ?></td>
            <td><?= $car['price_per_day'] ?></td>
            <td><?= $car['availability'] ? 'Available' : 'Rented' ?></td>
            <td>
                <a href="edit_car.php?car_id=<?= $car['car_id'] ?>">Edit</a>
                <a href="delete_car.php?car_id=<?= $car['car_id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>