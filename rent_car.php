<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

include 'connect_db.php';

if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
    $user_id = $_SESSION['user_id'];
    $rent_date = date('Y-m-d'); // current date as rent date

    // Insert the rental record
    $stmt = $conn->prepare("INSERT INTO rentals (car_id, user_id, rent_date) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $car_id, $user_id, $rent_date);

    if ($stmt->execute()) {
        echo "<h1>Car rented successfully!</h1>";
        header('Location: customer_dashboard.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No car ID specified!";
}

$conn->close();
?>>