<?php
session_start();

// Not a customer go log in again
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

include 'connect_db.php';

$sql = "SELECT * FROM cars WHERE availability=1";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= $_SESSION['username'] ?></h1>

    <h2>Available Cars</h2>
    <table border="1">
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Availability</th>
            <th>Price per Day</th>
            <th>Actions</th>
        </tr>
        <?php while ($car = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $car['make'] ?></td>
            <td><?= $car['model'] ?></td>
            <td><?= $car['year'] ?></td>
            <td><?= $car['availability'] ? "Available" : "Rented" ?></td>
            <td><?= $car['price_per_day'] ?></td>
            <td><a href="rent_car.php?car_id=<?=$car['car_id']?>">Rent this car</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>