<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

include 'connect_db.php';

if (isset($_GET['car_id']) && isset($_SESSION['user_id'])) {
    $car_id = $_GET['car_id'];
    $user_id = $_SESSION['user_id'];

    // Insert the rental record
    $stmt = $conn->prepare("INSERT INTO rentals (car_id, user_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $car_id, $user_id);
    $stmt->execute();
    header('Location: customer_dashboard.php');
} else {
    echo "No car ID specified!";
}
?>