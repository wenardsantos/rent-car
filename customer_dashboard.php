<?php
session_start();

include 'connect_db.php';

//$cars_sql = "SELECT * FROM cars WHERE car_id NOT IN (SELECT car_id FROM rentals WHERE status='approved') AND availability = 1";
$cars_sql = "SELECT * FROM cars WHERE car_id NOT IN (SELECT car_id FROM rentals WHERE status='pending') AND availability = 1";
$cars_results = $conn->query($cars_sql);

$rentals_sql = "SELECT r.rental_id, c.make, c.model, r.days, r.status FROM rentals r JOIN cars c ON r.car_id = c.car_id WHERE r.customer_id = ? ";
$stmt = $conn->prepare($rentals_sql);
$stmt->bind_param('i', $_SESSION['customer_id']);
$stmt->execute();
$rentals_result = $stmt->get_result();

$sql = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['customer_id']);
$stmt->execute();
$customer = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Customer Dashboard</title>
    <style>
        .dashboard {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: var(--color-primary);
            padding: 2rem 1rem;
        }
        .main-content {
            color: black;
            flex-grow: 1;
            padding: 2rem;
            background-color: var(--color-tertiary);
        }

        .cars-container-dets .info {
            background-color: #0c2340;
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Combine borders */
            margin: 20px 0; /* Space above and below the table */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        th, td {
            padding: 12px; /* Space inside cells */
            text-align: left; /* Align text to the left */
            border-bottom: 1px solid #ddd; /* Bottom border for rows */
        }

        th {
            background-color: #4CAF50; /* Header background color */
            color: white; /* Header text color */
        }

        tr:hover {
            background-color: #f1f1f1; /* Row hover effect */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping for even rows */
        }

        tr:nth-child(odd) {
            background-color: #ffffff; /* Background for odd rows */
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px; /* Limit width */
            margin: auto; /* Center the form */
        }


        label {
            display: block; /* Make labels block elements */
            margin-bottom: 5px; /* Space below labels */
            color: #555; /* Slightly lighter color for labels */
        }

        select, input[type="number"], input[type="submit"] {
            width: 100%; /* Full width for inputs */
            padding: 10px; /* Inner padding */
            margin-bottom: 15px; /* Space between elements */
            border: 1px solid #ccc; /* Border style */
            border-radius: 4px; /* Rounded corners */
        }

        input[type="checkbox"] {
            width: auto; /* Default width for checkbox */
            margin-right: 10px; /* Space between checkbox and label */
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Green background for submit button */
            color: white; /* White text color */
            border: none; /* Remove border */
            cursor: pointer; /* Pointer cursor on hover */
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }

    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div id="dashboard-section" class="crud-section">
                <h1>Welcome, <?= $_SESSION['username'] ?></h1>
                <?php while ($customer_points = $customer->fetch_assoc()):?>
                <h4>Rental points: <?=$customer_points['points']?></h4>
                <?php endwhile;?>
            </div>
            <ul>
                <li><a href="customer_dashboard.php">Available cars</a></li>
                <li><a href="customer_dashboard.php">Pending rent</a></li>

            </ul>
            <a href="logout.php">Logout</a>
        </div>
        
        
        
        <div class="main-content">
            <div class="container cars-container-dets">
            <?php while ($car = $cars_results->fetch_assoc()): ?>
                <div class="box">
                    <img src="pic\cars pixels (1).jpg" alt="">
                    <div class="info">
                        <div class="tag">
                            <span class="lnr lnr-pointer-right"></span>
                            <p><?= $car['price_per_day'] ?>/DAY</p>
                            <p><?= $car['availability'] ? "Available" : "Rented" ?></p>
                        </div>
                        <h5 style="color:aliceblue;"><?= $car['make'] ?></h5>
                        <p><?= $car['model'] ?></p>
                        <div>
                            <a href="car-info.php"><?= $car['year'] ?></a>
                            
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

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
                        <td class="action-buttons">
                            <?php if ($rental['status'] == 'pending'): ?>
                                <a href="cancel_rental.php?rental_id=<?= $rental['rental_id'] ?>"><button>Cancel</button></a>
                            <?php else: ?>
                               
                                <a href="delete_rent_history.php?rental_id=<?= $rental['rental_id'] ?>"><button>Delete history</button></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="form-container">
                <form action="rent_car.php" method="POST">
                    <label for="car_id">Select Car:</label>
                    <select name="car_id" required>
                        <?php
                        $cars_results->data_seek(0); // Reset the result set
                        while ($car = $cars_results->fetch_assoc()): 
                        ?>
                            <option value="<?= $car['car_id'] ?>">
                                <?= htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']) ?>
                            </option>
                        <?php endwhile; ?>

                    </select><br><br>

                    <label for="redeem_points">Redeem Points:</label>
                    <input type="checkbox" name="redeem_points" value="yes">500 discount for 50 rent points<br><br>

                    <label for="days">How long you want to rent this car? (up to 7 days only)</label>
                    <input type="number" name="days" id="days" min="1" max="7" required>
                    <input type="submit"  value="Submit Rental Request">
                </form>
            </div>
            
        </div>
    </div>
</body>
</html>