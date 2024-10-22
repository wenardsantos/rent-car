<?php
session_start();

include 'connect_db.php';

if (isset($_GET['car_id']) && isset($_SESSION['customer_id'])) {
    $car_id = $_GET['car_id'];
    $user_id = $_SESSION['customer_id'];
    
    $stmt = $conn->prepare("INSERT INTO rentals (car_id, customer_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $car_id, $user_id);
    $stmt->execute();
    header('Location: customer_dashboard.php');
} else {
    echo "No car ID specified!";
}

?>