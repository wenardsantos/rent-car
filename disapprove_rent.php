<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit();
}

include 'connect_db.php';

if (isset($_GET['rental_id'])) {
    $rental_id = $_GET['rental_id'];
    echo $rental_id;
    $stmt = $conn->prepare("DELETE FROM rentals WHERE rental_id = ?");
    $stmt->bind_param('i', $rental_id);
    $stmt->execute();
    header('Location: admin_dashboard.php');
}
?>