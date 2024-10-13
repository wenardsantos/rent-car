<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit();
}

include 'connect_db.php';  // Use the connection from db_connect.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price_per_day = $_POST['price_per_day'];

    // Insert car into the database
    $stmt = $conn->prepare("INSERT INTO cars (make, model, year, price_per_day) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssii', $make, $model, $year, $price_per_day);

    if ($stmt->execute()) {
        echo "Car added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
