<?php
session_start();

include 'connect_db.php';

$cars_sql = "SELECT * FROM cars WHERE car_id NOT IN (SELECT car_id FROM rentals WHERE status='approved' AND status='pending')";

$cars_results = $conn->query($cars_sql);

$rentals_sql = "SELECT r.rental_id, c.make, c.model, r.days, r.status FROM rentals r JOIN cars c ON r.car_id = c.car_id WHERE r.user_id = ?";
$stmt = $conn->prepare($rentals_sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$rentals_result = $stmt->get_result();
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
        <?php while ($car = $cars_results->fetch_assoc()): ?>
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
    <h2>Rent</h2>
    <table border="1">
       <tr>
        <th>Make</th>
        <th>Status</th>
        <th>Action</th>
       </tr> 
        <?php while ($rental = $rentals_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($rental['make']) . ' ' . htmlspecialchars($rental['model']) ?></td>
                <td><?= htmlspecialchars($rental['status']) ?></td>
                <td>
                    <?php if ($rental['status'] == 'pending'): ?>
                        <a href="cancel_rental.php?rental_id=<?= $rental['rental_id'] ?>">Cancel</a>
                    <?php else: ?>
                        No Action Available
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>