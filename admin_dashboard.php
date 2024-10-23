<?php
session_start();

include 'connect_db.php';

$cars_sql = "SELECT * FROM cars";
$cars_result = $conn->query($cars_sql);

$sql = "SELECT r.rental_id, r.car_id, r.customer_id, r.days, r.payment_amount, c.make, c.model, u.username, u.email, r.status 
        FROM rentals r
        JOIN cars c ON r.car_id = c.car_id
        JOIN customers u ON r.customer_id = u.customer_id
        WHERE r.status = 'pending'";

$rentals_result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            text-decoration: none;
        }
        .dashboard {
            
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #0C2340;
            color: white;
            padding: 2rem 1rem;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 1rem;
        }
        .sidebar ul li a {
            color: var(--color-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>Admin dashboard</h2>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#manage-cars">Manage cars</a></li>
                <li><a href="#rental-request">Rental request</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2 id="manage-cars">Manage Cars</h2>
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
        
            <h2 id="rental-request">Pending rental request</h2>
            <table border="1">
                <tr>
                    <th>Customer</th>
                    <th>Customer email</th>
                    <th>Car</th>
                    <th>Days to be rented</th>
                    <th>Payment amount</th>
                    <th>Actions</th>
                </tr>
                <?php while ($rental = $rentals_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($rental['username'])?></td>
                        <td><?= htmlspecialchars($rental['email'])?></td>
                        <td><?= htmlspecialchars($rental['make'])?></td>
                        <td><?= htmlspecialchars($rental['days'])?></td>
                        <td><?= htmlspecialchars($rental['payment_amount'])?></td>
                        <td>
                            <a href="admin_approve_rental.php?rental_id=<?= $rental['rental_id']?>">Approve</a>
                            <a href="admin_disapprove_rental.php?rental_id=<?= $rental['rental_id']?>">Disapprove</a>
                        </td>
                    </tr>
                <?php endwhile;?>
            </table>
        </div>
    </div>
    
</body>
</html>
