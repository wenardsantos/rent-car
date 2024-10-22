<?php
session_start();

include 'connect_db.php';

if (isset($_GET['rental_id'])) {
    $rental_id = $_GET['rental_id'];
    echo $rental_id;
    $stmt = $conn->prepare("UPDATE rentals SET status = 'approved' WHERE rental_id = ?");
    $stmt->bind_param('i', $rental_id);

    if ($stmt->execute()) {
        $sql = "UPDATE cars SET availability = 0
                WHERE car_id = (SELECT car_id FROM rentals WHERE rental_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $rental_id);
        $stmt->execute();

        header('Location: admin_dashboard.php');
    } else {
        echo "Error" . $stmt->error;
    }
    
    
}
?>