<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit();
}

include 'connect_db.php';

$cars_sql = "SELECT * FROM cars";
$cars_result = $conn->query($cars_sql);

$sql = "SELECT r.rental_id, r.car_id, r.user_id, r.days, c.make, c.model, u.username, r.status 
        FROM rentals r
        JOIN cars c ON r.car_id = c.car_id
        JOIN users u ON r.user_id = u.user_id
        WHERE r.status = 'pending'";

$rentals_result = $conn->query($sql);

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
            <th>Price per day</th>
            <th>Actions</th>
        </tr>
        <?php while ($cars = $cars_result->fetch_assoc()): ?>
            <tr>
                <td><?= $cars['car_id']?></td>
                <td><?= $cars['make']?></td>
                <td><?= $cars['model']?></td>
                <td><?= $cars['year']?></td>
                <td><?= $cars['availability'] ? 'Available' : 'Not available'?></td>
                <td><?= $cars['price_per_day']?></td>
                <td>
                    <a href="edit_car.php?car_id=<?= $cars['car_id'] ?>">
                        <button>Edit</button>
                    </a>
                    <a href="delete_car.php?car_id=<?= $cars['car_id'] ?>">
                        <button>Delete</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
   
    <h2>Pending rental request</h2>
    <table border="1">
        <tr>
            <th>Customer</th>
            <th>Car</th>
            <th>Days to be rented</th>
            <th>Actions</th>
        </tr>
        <?php while ($rental = $rentals_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($rental['username'])?></td>
                <td><?= $rental['make']?></td>
                <td><?= $rental['days']?></td>
                <td>
                    <a href="approve_rental.php">Approve</a>
                    <a href="disapprove_rental.php">Disapprove</a>
                </td>
            </tr>
        <?php endwhile;?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>